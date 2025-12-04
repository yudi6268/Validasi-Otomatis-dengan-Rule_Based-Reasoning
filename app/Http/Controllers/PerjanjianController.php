<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjanjian;
use Barryvdh\DomPDF\Facade\Pdf;

class PerjanjianController extends Controller
{
    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $query = Perjanjian::orderBy('id', 'desc');

        // If AJAX request for filtered list, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            $filter = request()->get('filter');

            $items = $query->get()->map(function($item) {
                // determine status
                $status = 'sent'; // default: laporan dikirim (created)
                if (!empty($item->pihak2_signature)) {
                    $status = 'approved';
                } else if (!empty($item->pihak2_name)) {
                    // waiting for signature from pihak kedua
                    $status = 'waiting';
                }

                // if there's an explicit rejection flag, prefer it (best-effort)
                if (isset($item->rejected) && $item->rejected) {
                    $status = 'rejected';
                }

                return [
                    'id' => $item->id,
                    'pihak1_name' => $item->pihak1_name,
                    'pihak2_name' => $item->pihak2_name,
                    'jabatan' => $item->jabatan,
                    'created_at' => optional($item->created_at)->format('d M Y'),
                    'status' => $status,
                    'approved' => ($status === 'approved')
                ];
            });

            if ($filter) {
                $items = $items->filter(function($i) use ($filter) {
                    return $i['status'] === $filter;
                })->values();
            }

            return response()->json(['data' => $items]);
        }

        $items = $query->get();

        // compute counts per status for dashboard
        $counts = [
            'sent' => 0,
            'approved' => 0,
            'rejected' => 0,
            'waiting' => 0,
        ];

        foreach ($items as $item) {
            $status = 'sent';
            if (!empty($item->pihak2_signature)) {
                $status = 'approved';
            } else if (!empty($item->pihak2_name)) {
                $status = 'waiting';
            }
            if (isset($item->rejected) && $item->rejected) {
                $status = 'rejected';
            }
            if (isset($counts[$status])) {
                $counts[$status]++;
            }
        }

        return view('perjanjian.index', compact('items', 'counts'));
    }


    // ==============================
    // CREATE
    // ==============================
    public function create()
    {
        return view('perjanjian.create');
    }


    // ==============================
    // STORE
    // ==============================
    public function store(Request $request)
    {
        // ===== VALIDASI =====
        $request->validate([
            'pihak2_name'   => 'required|string',
            'pihak2_jabatan' => 'required|string',
        ]);

        // ===== CEK TANDA TANGAN PIHAK PERTAMA =====
        if (!auth()->user()->tanda_tangan) {
            return back()->with('error', 'Gagal menyimpan! TTD anda kosong.');
        }

        // ==========================
        // SIMPAN DATA PERJANJIAN
        // ==========================
        // Prepare tabelC: accept hierarchical_budget_json (string or array) or fall back to flat fields
        $tabelC = $request->hierarchical_budget_json ?? null;
        if ($tabelC && !is_string($tabelC)) {
            $tabelC = json_encode($tabelC);
        }
        if (!$tabelC) {
            $tabelC = json_encode([
                'program'     => $request->d_program ?? [],
                'anggaran'    => $request->d_anggaran ?? [],
                'keterangan'  => $request->d_keterangan ?? [],
            ]);
        }

        $save = Perjanjian::create([
            'user_id'           => auth()->id() ?? null,
            'pihak1_name'       => auth()->user()->nama,
            'pihak1_jabatan'    => auth()->user()->jabatan,
            'pihak1_nip'        => auth()->user()->nip,
            'pihak1_ttd'        => auth()->user()->tanda_tangan,

            // PIHAK KEDUA
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,
            'pihak2_nip'        => $request->pihak2_nip ?? null,
            'location'          => $request->location ?? 'Pasuruan',
            'agreement_date'    => $request->agreement_date ?? now(),

            // LAINNYA: store pihak2_jabatan as 'jabatan' if present, otherwise fallback to authenticated user's jabatan
            'jabatan'           => $request->pihak2_jabatan ?? (auth()->user()->jabatan ?? null),

            // TABEL A
            'tabelA' => json_encode([
                'sasaran'   => $request->a_sasaran ?? [],
                'indikator' => $request->a_indikator ?? [],
                'satuan'    => $request->a_satuan ?? [],
                'target'    => $request->a_target ?? [],
            ]),

            // TABEL C (was B, now B/TABELB)
            'tabelB' => json_encode([
                'sasaran'  => $request->c_sasaran ?? [],
                'indikator'=> $request->c_indikator ?? [],
                'target'   => $request->c_target ?? [],
                'tw1'      => $request->c_tw1 ?? [],
                'tw2'      => $request->c_tw2 ?? [],
                'tw3'      => $request->c_tw3 ?? [],
                'tw4'      => $request->c_tw4 ?? [],
            ]),

            // TABEL D (HIERARCHICAL BUDGET)
            'tabelC' => $tabelC,
        ]);

        return redirect()->route('perjanjian.index')->with('success', 'Data perjanjian berhasil disimpan!');
    }


    // ==============================
    // PRINT/VIEW PDF
    // ==============================
    public function print($id)
    {
        $data = Perjanjian::findOrFail($id);

        return view('perjanjian.print', compact('data'));
    }

    // ==============================
    // EXPORT TO PDF
    // ==============================
    public function exportPdf($id)
    {
        $data = Perjanjian::findOrFail($id);
        
        // Format data untuk PDF
        $pdfData = [
            'data' => $data,
            'perjanjian' => $data,
            'tabelA' => json_decode($data->tabelA, true),
            'tabelB' => json_decode($data->tabelB, true),
            'tabelC' => json_decode($data->tabelC, true),
        ];

        // Try to generate PDF; if system lacks GD (or other required extensions), fall back to HTML view
        try {
            // mark view as PDF so blade can adjust image paths
            $pdfData['for_pdf'] = true;

            // Helper to convert local image path or storage path to data URI
            $toDataUri = function ($path) {
                if (empty($path)) {
                    return null;
                }
                // already a data URI
                if (strpos($path, 'data:') === 0) {
                    return $path;
                }

                // Try public path direct
                $candidates = [];
                if (file_exists(public_path($path))) {
                    $candidates[] = public_path($path);
                }
                // Try storage path
                if (file_exists(public_path('storage/' . ltrim($path, '/')))) {
                    $candidates[] = public_path('storage/' . ltrim($path, '/'));
                }

                foreach ($candidates as $candidate) {
                    try {
                        $contents = @file_get_contents($candidate);
                        if ($contents === false) {
                            continue;
                        }
                        $mime = @mime_content_type($candidate) ?: 'image/png';
                        $base = base64_encode($contents);
                        return "data:{$mime};base64,{$base}";
                    } catch (\Exception $e) {
                        continue;
                    }
                }

                return null;
            };

            // Convert logo and signatures to data URIs when possible (makes Dompdf render reliably)
            $logoPath = 'images/logo_pemda.png';
            $pdfData['logo_data'] = $toDataUri($logoPath);
            $pdfData['pihak1_ttd_data'] = $toDataUri($data->pihak1_ttd ?? null);
            $pdfData['pihak2_ttd_data'] = $toDataUri($data->pihak2_signature ?? null);

            $pdf = Pdf::loadView('perjanjian.print', $pdfData);
            // F4 kertas: 210mm x 330mm (595 x 935 points @ 72 DPI)
            $pdf->setPaper([0, 0, 595, 935], 'portrait');
            
            // Set margins ke 0 karena akan di-handle via padding dalam CSS
            $pdf->setOption('margin-left', 0);
            $pdf->setOption('margin-right', 0);
            $pdf->setOption('margin-top', 0);
            $pdf->setOption('margin-bottom', 0);
            
            // DomPDF Options untuk consistency
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isFontSubsettingEnabled', true);
            $pdf->setOption('dpi', 96); // Standard screen DPI
            $pdf->setOption('defaultMediaType', 'print'); // Use print media type
            $pdf->setOption('defaultPaperSize', 'F4');
            // Allow remote images and constrain file access to public folder
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('chroot', public_path());
            
            $filename = 'Perjanjian_Kinerja_' . $data->pihak1_name . '_' . date('Y-m-d') . '.pdf';
            // Download PDF
            return $pdf->download($filename);
        } catch (\Exception $e) {
            // Log error and return JSON error so client doesn't download HTML as .pdf
            \Log::error('PDF generation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'PDF generation failed: ' . $e->getMessage()
            ], 500);
        }
    }    // ==============================
    // SAVE TO SUPABASE & RETURN JSON
    // ==============================
    public function savePerjanjian(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'pihak2_name'   => 'required|string',
            'pihak2_jabatan' => 'required|string',
        ]);

        // CEK TANDA TANGAN
        if (!auth()->user()->tanda_tangan) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan tidak ditemukan. Silakan atur di menu profil.'
            ], 422);
        }

        try {
            // SIMPAN KE DATABASE (Supabase)
            // Normalize tabelC payload
            $tabelC = $request->hierarchical_budget_json ?? null;
            if ($tabelC && !is_string($tabelC)) {
                $tabelC = json_encode($tabelC);
            }
            if (!$tabelC) {
                $tabelC = json_encode([
                    'program'     => $request->d_program ?? [],
                    'anggaran'    => $request->d_anggaran ?? [],
                    'keterangan'  => $request->d_keterangan ?? [],
                ]);
            }

            $save = Perjanjian::create([
                'user_id'           => auth()->id() ?? null,
                'pihak1_name'       => auth()->user()->nama,
                'pihak1_jabatan'    => auth()->user()->jabatan,
                'pihak1_nip'        => auth()->user()->nip,
                'pihak1_ttd'        => auth()->user()->tanda_tangan,
                'pihak2_name'       => $request->pihak2_name,
                'pihak2_jabatan'    => $request->pihak2_jabatan,
                'pihak2_nip'        => $request->pihak2_nip ?? null,
                'location'          => $request->location ?? 'Pasuruan',
                'agreement_date'    => $request->agreement_date ?? now(),
                'jabatan'           => $request->pihak2_jabatan ?? (auth()->user()->jabatan ?? null),
                'tabelA' => json_encode([
                    'sasaran'   => $request->a_sasaran ?? [],
                    'indikator' => $request->a_indikator ?? [],
                    'satuan'    => $request->a_satuan ?? [],
                    'target'    => $request->a_target ?? [],
                ]),
                'tabelB' => json_encode([
                    'sasaran'   => $request->c_sasaran ?? [],
                    'indikator' => $request->c_indikator ?? [],
                    'target'    => $request->c_target ?? [],
                    'tw1'       => $request->c_tw1 ?? [],
                    'tw2'       => $request->c_tw2 ?? [],
                    'tw3'       => $request->c_tw3 ?? [],
                    'tw4'       => $request->c_tw4 ?? [],
                ]),
                'tabelC' => $tabelC,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data perjanjian berhasil disimpan ke Supabase!',
                'id' => $save->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}