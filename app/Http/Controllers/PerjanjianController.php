<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjanjian;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;



class PerjanjianController extends Controller
{

    // ==============================
    // SUBMIT PENOLAKAN PERJANJIAN
    // ==============================
    public function tolakSubmit(Request $request, $id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        // Validasi hanya direktur/pimpinan
        $isDirektur = false;
        if ($user && isset($user->jabatan)) {
            $jabatan = strtolower($user->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }
        if (!$isDirektur) {
            abort(403, 'Hanya direktur/pimpinan yang dapat menolak perjanjian');
        }
        // Validasi alasan
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        // Simpan alasan dan update status
        $perjanjian->rejected = true;
        $perjanjian->rejection_reason = $request->rejection_reason;
        $perjanjian->save();
        // Redirect ke preview dengan notifikasi sukses
        return redirect()->route('perjanjian.print', $perjanjian->id)->with('success', 'Alasan penolakan berhasil dikirim.');
    }

    // ==============================
    // FORM PENOLAKAN PERJANJIAN
    // ==============================
    public function tolakForm($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        // Status dan role
        $isDirektur = false;
        if ($user && isset($user->jabatan)) {
            $jabatan = strtolower($user->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }
        // Hanya direktur/pimpinan yang boleh akses form penolakan
        if (!$isDirektur) {
            abort(403, 'Hanya direktur/pimpinan yang dapat menolak perjanjian');
        }
        return view('perjanjian.tolak', compact('perjanjian', 'user'));
    }
    // ==============================
    // CREATE
    // ==============================
    public function create()
    {
        // Ambil data jabatan user aktif
        $user = auth()->user();
        $jabatanData = null;
        if ($user && $user->jabatan) {
            $jabatanData = Jabatan::where('nama_jabatan', $user->jabatan)->first();
        }
        // Ambil user direktur yang benar (misal dr. Arma)
        $direktur = User::where('jabatan', 'like', '%Direktur%')
            ->where('nama', 'like', '%Arma%')
            ->first();
        if (!$direktur) {
            // fallback: ambil direktur pertama jika tidak ketemu nama Arma
            $direktur = User::where('jabatan', 'like', '%Direktur%')->first();
        }
        
        // Ambil data program, kegiatan, dan sub kegiatan untuk dropdown
        $programs = Program::where('is_active', true)->orderBy('nama_program')->get();
        $kegiatans = Kegiatan::where('is_active', true)->with('program')->orderBy('nama_kegiatan')->get();
        $subKegiatans = SubKegiatan::where('is_active', true)->with('kegiatan')->orderBy('nama_sub_kegiatan')->get();
        
        return view('perjanjian.create', compact('jabatanData', 'direktur', 'programs', 'kegiatans', 'subKegiatans'));
    }
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
                    'approved' => ($status === 'approved'),
                    'pihak2_signature' => $item->pihak2_signature,
                ];
            });

            if ($filter && $filter !== 'all') {
                $items = $items->filter(function($i) use ($filter) {
                    return $i['status'] === $filter;
                })->values();
            } elseif ($filter === 'all') {
                // Filter untuk 'all' mengambil semua kecuali 'sent'
                $items = $items->filter(function($i) {
                    return in_array($i['status'], ['approved', 'rejected', 'waiting']);
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
            'total' => 0,
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
            $counts['total']++;
        }

        return view('perjanjian.index', compact('items', 'counts'));
    }

    // PRINT/VIEW PDF
    public function print($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        \Log::debug('PRINT: tugas_pelaksana = ' . var_export($perjanjian->tugas_pelaksana, true));
        \Log::debug('PRINT: fungsi_pelaksana = ' . var_export($perjanjian->fungsi_pelaksana, true));
        \Log::debug('PRINT: rejected = ' . var_export($perjanjian->rejected, true) . ', rejection_reason = ' . var_export($perjanjian->rejection_reason, true));

        // Logo
        $logoSrc = asset('images/logo_pemda.png');
        $logoPemda = $logoSrc;
        $logoRsud = asset('images/logo_rsud.png');

        // Tanggal (bisa dari field, atau default hari ini)
        $tanggal = $perjanjian->tanggal ?? date('d F Y');

        // Gabungkan tugas & fungsi pelaksana (untuk preview)
        $tugas = $perjanjian->tugas_pelaksana ?? '';
        $fungsi = $perjanjian->fungsi_pelaksana ?? '';
        
        // Parse fungsi jika berbentuk JSON array
        if ($fungsi && is_string($fungsi)) {
            $fungsiArray = json_decode($fungsi, true);
            if (is_array($fungsiArray)) {
                $fungsi = implode("\n", $fungsiArray);
            }
        }
        
        $tugas_fungsi = '';
        if ($tugas && $fungsi) {
            $tugas_fungsi = "<b>Tugas:</b>\n" . $tugas . "\n<b>Fungsi:</b>\n" . $fungsi;
        } elseif ($tugas) {
            $tugas_fungsi = "<b>Tugas:</b>\n" . $tugas;
        } elseif ($fungsi) {
            $tugas_fungsi = "<b>Fungsi:</b>\n" . $fungsi;
        }

        // Tabel A/B/C decoding for print view (match PDF logic)
        $tabelA = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
        $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);

        // Normalisasi agar $tabelC['programs'] selalu ada (untuk preview dan print)
        if (!isset($tabelC['programs']) || !is_array($tabelC['programs']) || count($tabelC['programs']) === 0) {
            // Cek format flat (array program, anggaran, keterangan)
            if (isset($tabelC['program']) && is_array($tabelC['program'])) {
                $tabelC['programs'] = [];
                $count = max(count($tabelC['program']), count($tabelC['anggaran'] ?? []), count($tabelC['keterangan'] ?? []));
                for ($i = 0; $i < $count; $i++) {
                    $prog = $tabelC['program'][$i] ?? '';
                    $amount = isset($tabelC['anggaran'][$i]) && is_numeric($tabelC['anggaran'][$i]) ? $tabelC['anggaran'][$i] : 0;
                    $ket = $tabelC['keterangan'][$i] ?? '-';
                    if ($prog !== '' || $amount != 0 || $ket !== '-') {
                        $tabelC['programs'][] = [
                            'name' => $prog,
                            'amount' => $amount,
                            'source' => $ket,
                        ];
                    }
                }
            }
            // Jika format nested/hierarchical (misal: sudah ada 'programs' tapi kosong, atau format lain)
            elseif (isset($tabelC[0]) && is_array($tabelC[0]) && (isset($tabelC[0]['name']) || isset($tabelC[0]['program']))) {
                // Asumsikan array of program rows
                $tabelC['programs'] = [];
                foreach ($tabelC as $row) {
                    if (is_array($row)) {
                        $tabelC['programs'][] = [
                            'name' => $row['name'] ?? $row['program'] ?? '',
                            'amount' => $row['amount'] ?? $row['anggaran'] ?? 0,
                            'source' => $row['source'] ?? $row['keterangan'] ?? '-',
                        ];
                    }
                }
            }
            // Jika tidak ada data, biarkan kosong (Blade akan handle tampilan kosong)
        }

        // Deteksi apakah user adalah direktur/pimpinan
        $authUser = auth()->user();
        $isDirektur = false;
        if ($authUser && isset($authUser->jabatan)) {
            $jabatan = strtolower($authUser->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }

        // Ambil user yang membuat perjanjian (pihak pertama) untuk data pangkat/jabatan
        $user = User::find($perjanjian->user_id);

        // Ambil user pihak kedua berdasarkan nama pihak kedua
        $pihak2User = null;
        if (!empty($perjanjian->pihak2_name)) {
            $pihak2User = User::where('nama', $perjanjian->pihak2_name)->first();
        }
        // Jika pihak2_pangkat kosong, ambil dari pihak2User
        if (empty($perjanjian->pihak2_pangkat) && $pihak2User) {
            $perjanjian->pihak2_pangkat = $pihak2User->pangkat ?? null;
        }

        // Jika tugas_pelaksana atau fungsi_pelaksana null, ambil dari tabel jabatan
        if (empty($perjanjian->tugas_pelaksana) || empty($perjanjian->fungsi_pelaksana)) {
            $jabatanData = Jabatan::where('nama_jabatan', $perjanjian->pihak1_jabatan)->first();
            if ($jabatanData) {
                if (empty($perjanjian->tugas_pelaksana)) {
                    $perjanjian->tugas_pelaksana = $jabatanData->tugas;
                }
                if (empty($perjanjian->fungsi_pelaksana)) {
                    // Jabatan fungsi is already stored as JSON array in DB
                    $perjanjian->fungsi_pelaksana = $jabatanData->fungsi;
                }
            }
        }

        // Set status konsisten dari database
        $status = 'menunggu';
        if ($perjanjian->rejected === true || $perjanjian->rejected === 1 || $perjanjian->rejected === '1') {
            $status = 'ditolak';
        } elseif (!empty($perjanjian->pihak2_signature)) {
            $status = 'disetujui';
        }

        return view('perjanjian.print', compact(
            'perjanjian',
            'logoSrc',
            'logoPemda',
            'logoRsud',
            'tanggal',
            'tugas_fungsi',
            'tabelA',
            'tabelB',
            'tabelC',
            'user',
            'isDirektur',
            'status'
        ));
    }


    // ==============================
    // STORE
    // ==============================
    public function store(Request $request)
    {
        // ===== VALIDASI =====
        $request->validate([
            'pihak2_name'        => 'required|string',
            'pihak2_jabatan'     => 'required|string',

            // TAMBAHAN BARU
            'jabatan_pelaksana'  => 'nullable|string',
            'tugas_pelaksana'    => 'nullable|string',
            'fungsi_pelaksana'   => 'nullable|string',
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

        // Generate nomor_perjanjian (simple: PK-YYYYMMDD-<user_id>-<random>)
        $nomor_perjanjian = 'PK-' . date('Ymd') . '-' . (auth()->id() ?? 'X') . '-' . strtoupper(substr(uniqid(), -4));

        $tahun = date('Y');
        $save = Perjanjian::create([
            'tahun' => $tahun,
            'nomor_perjanjian'  => $nomor_perjanjian,
            'user_id'           => auth()->id() ?? null,
            'pihak1_name'       => auth()->user()->nama,
            'pihak1_jabatan'    => auth()->user()->jabatan,
            'pihak1_pangkat'    => auth()->user()->pangkat,
            'pihak1_nip'        => auth()->user()->nip,
            'pihak1_ttd'        => auth()->user()->tanda_tangan,

            'jabatan_pelaksana' => $request->jabatan_pelaksana,
            'tugas_pelaksana'   => $request->tugas_pelaksana,
            'fungsi_pelaksana'  => $request->fungsi_pelaksana,


            // PIHAK KEDUA
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,
            'pihak2_pangkat'    => $request->pihak2_pangkat ?? null,
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
    // EDIT
    // ==============================
    public function edit($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        // Hanya direktur/pimpinan yang boleh edit
        if (!$user || !(stripos($user->jabatan, 'direktur') !== false || stripos($user->jabatan, 'pimpinan') !== false)) {
            return redirect()->route('perjanjian.index')->with('error', 'Hanya Direktur/Pimpinan yang dapat mengedit perjanjian.');
        }
        // Cek apakah perjanjian masih bisa diedit
        if (!empty($perjanjian->pihak2_signature)) {
            return redirect()->route('perjanjian.index')->with('error', 'Perjanjian sudah ditandatangani dan tidak dapat diedit.');
        }
        // Ambil data jabatan pelaksana (pihak1)
        $jabatanData = null;
        if ($perjanjian->pihak1_jabatan) {
            $jabatanData = \App\Models\Jabatan::where('nama_jabatan', $perjanjian->pihak1_jabatan)->first();
        }
        
        // Ambil data program, kegiatan, dan sub kegiatan untuk dropdown
        $programs = Program::where('is_active', true)->orderBy('nama_program')->get();
        $kegiatans = Kegiatan::where('is_active', true)->with('program')->orderBy('nama_kegiatan')->get();
        $subKegiatans = SubKegiatan::where('is_active', true)->with('kegiatan')->orderBy('nama_sub_kegiatan')->get();
        
        return view('perjanjian.edit', compact('perjanjian', 'jabatanData', 'programs', 'kegiatans', 'subKegiatans'));
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update(Request $request, $id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        // Hanya direktur/pimpinan yang boleh update
        if (!$user || !(stripos($user->jabatan, 'direktur') !== false || stripos($user->jabatan, 'pimpinan') !== false)) {
            return back()->with('error', 'Hanya Direktur/Pimpinan yang dapat mengubah perjanjian.');
        }
        // Cek apakah perjanjian masih bisa diedit
        if (!empty($perjanjian->pihak2_signature)) {
            return back()->with('error', 'Perjanjian sudah ditandatangani dan tidak dapat diedit.');
        }
        
        // Validasi
        $request->validate([
            'pihak2_name'        => 'required|string',
            'pihak2_jabatan'     => 'required|string',

            // TAMBAHAN
            'jabatan_pelaksana'  => 'nullable|string',
            'tugas_pelaksana'    => 'nullable|string',
            'fungsi_pelaksana'   => 'nullable|string',
        ]);

        // Prepare tabelC
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

        // Update data
        $perjanjian->update([
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,
            'pihak2_nip'        => $request->pihak2_nip ?? $perjanjian->pihak2_nip,
            'location'          => $request->location ?? $perjanjian->location,
            'agreement_date'    => $request->agreement_date ?? $perjanjian->agreement_date,
            'jabatan'           => $request->pihak2_jabatan ?? $perjanjian->jabatan,
            'jabatan_pelaksana' => $request->jabatan_pelaksana,
            'tugas_pelaksana'   => $request->tugas_pelaksana,
            'fungsi_pelaksana'  => $request->fungsi_pelaksana,
            
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
        // Return JSON when requested via fetch/AJAX
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data perjanjian berhasil diperbarui!',
                'id' => $perjanjian->id,
            ]);
        }

        return redirect()->route('perjanjian.index')
            ->with('success', 'Data perjanjian berhasil diperbarui!');
    }


    // ==============================
    // EXPORT TO PDF
    // ==============================
    public function exportPdf($id)
    {
        $data = Perjanjian::findOrFail($id);
        
        // Fetch program & kegiatan data from Supabase
        $supabaseService = app(\App\Services\SupabaseService::class);
        $programsData = [];
        $kegiatanData = [];
        
        // Get tabelC to find program IDs
        $tabelC = is_array($data->tabelC) ? $data->tabelC : json_decode($data->tabelC, true);
        
        if (!empty($tabelC['programs'])) {
            foreach ($tabelC['programs'] as $programEntry) {
                // Fetch program details from Supabase if program_id exists
                if (!empty($programEntry['program_id'])) {
                    $programResult = $supabaseService->select('program', ['id' => 'eq.' . $programEntry['program_id']]);
                    if ($programResult['success'] && !empty($programResult['data'])) {
                        $programsData[$programEntry['program_id']] = $programResult['data'][0];
                    }
                }
                
                // Fetch kegiatan details if exists
                if (!empty($programEntry['kegiatan'])) {
                    foreach ($programEntry['kegiatan'] as $kegiatanEntry) {
                        if (!empty($kegiatanEntry['kegiatan_id'])) {
                            $kegiatanResult = $supabaseService->select('kegiatan', ['id' => 'eq.' . $kegiatanEntry['kegiatan_id']]);
                            if ($kegiatanResult['success'] && !empty($kegiatanResult['data'])) {
                                $kegiatanData[$kegiatanEntry['kegiatan_id']] = $kegiatanResult['data'][0];
                            }
                        }
                    }
                }
            }
        }
        
        // Format data untuk PDF
        $pdfData = [
            'data' => $data,
            'perjanjian' => $data,
            'tabelA' => is_array($data->tabelA) ? $data->tabelA : json_decode($data->tabelA, true),
            'tabelB' => is_array($data->tabelB) ? $data->tabelB : json_decode($data->tabelB, true),
            'tabelC' => $tabelC,
            'programsData' => $programsData,
            'kegiatanData' => $kegiatanData,
        ];

        // Deteksi apakah user adalah direktur/pimpinan
        $authUser = auth()->user();
        $isDirektur = false;
        if ($authUser && isset($authUser->jabatan)) {
            $jabatan = strtolower($authUser->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }
        $pdfData['isDirektur'] = $isDirektur;

        // Ambil user yang membuat perjanjian (pihak pertama)
        $user = User::find($data->user_id);
        $pdfData['user'] = $user;

        // Set status
        $status = 'menunggu';
        if ($data->rejected === true || $data->rejected === 1 || $data->rejected === '1') {
            $status = 'ditolak';
        } elseif (!empty($data->pihak2_signature)) {
            $status = 'disetujui';
        }
        $pdfData['status'] = $status;

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
            $logoRsudPath = 'images/logo_rsud.png';
            $pdfData['logo_data'] = $toDataUri($logoPath);
            $pdfData['logoPemda'] = $pdfData['logo_data'] ?: $toDataUri($logoPath) ?: asset($logoPath);
            $pdfData['logoRsud'] = $toDataUri($logoRsudPath) ?: asset($logoRsudPath);
            $pdfData['pihak1_ttd_data'] = $toDataUri($data->pihak1_ttd ?? null);
            $pdfData['pihak2_ttd_data'] = $toDataUri($data->pihak2_signature ?? null);

            $pdf = Pdf::loadView('perjanjian.print', $pdfData);

            // Allow optional orientation via query param (?orientation=landscape)
            $orientation = request()->get('orientation', 'portrait');
            if (!in_array($orientation, ['portrait', 'landscape'])) {
                $orientation = 'portrait';
            }

            // Dompdf tidak mengenal string F4; gunakan ukuran custom F4 dalam points (approx 612 x 936)
            $paperSize = $orientation === 'landscape'
                ? [0, 0, 936, 612]   // width x height untuk landscape
                : [0, 0, 612, 936];  // width x height untuk portrait
            $pdf->setPaper($paperSize, $orientation);

            // Set margins untuk hasil PDF yang rapi
            $pdf->setOption('margin-left', 8);
            $pdf->setOption('margin-right', 8);
            $pdf->setOption('margin-top', 10);
            $pdf->setOption('margin-bottom', 10);

            // DomPDF Options untuk konsistensi & kualitas
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isFontSubsettingEnabled', true);
            $pdf->setOption('dpi', 100);
            $pdf->setOption('defaultMediaType', 'print');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('chroot', public_path());
            $pdf->setOption('enable_javascript', false);
            $pdf->setOption('enable_css_float', false);
            
            $filename = 'Perjanjian_Kinerja_' . $data->pihak1_name . '_' . date('Y-m-d') . '.pdf';
            
            // Auto-upload to Supabase if perjanjian is approved
            if (!empty($data->pihak2_signature)) {
                try {
                    // Generate PDF content
                    $pdfContent = $pdf->output();
                    
                    // Save to temporary file
                    $tempPath = storage_path('app/temp/' . $filename);
                    if (!is_dir(dirname($tempPath))) {
                        mkdir(dirname($tempPath), 0755, true);
                    }
                    file_put_contents($tempPath, $pdfContent);
                    
                    // Upload to Supabase
                    $uploadResult = $supabaseService->uploadFile(
                        $tempPath,
                        $filename,
                        'perjanjian-' . $data->id
                    );
                    
                    if ($uploadResult['success']) {
                        // Update perjanjian with PDF URL
                        $data->pdf_url = $uploadResult['url'];
                        $data->pdf_path = $uploadResult['path'];
                        $data->save();
                        
                        \Log::info('PDF uploaded to Supabase', [
                            'perjanjian_id' => $data->id,
                            'url' => $uploadResult['url']
                        ]);
                    }
                    
                    // Clean up temp file
                    @unlink($tempPath);
                } catch (\Exception $e) {
                    \Log::error('Failed to upload PDF to Supabase: ' . $e->getMessage());
                }
            }
            
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
    }   

    
     // ==============================
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

            // Generate nomor_perjanjian (simple: PK-YYYYMMDD-<user_id>-<random>)
            $nomor_perjanjian = 'PK-' . date('Ymd') . '-' . (auth()->id() ?? 'X') . '-' . strtoupper(substr(uniqid(), -4));

            $tahun = date('Y');
            $save = Perjanjian::create([
                'tahun' => $tahun,
                'nomor_perjanjian'  => $nomor_perjanjian,
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

    // ==============================
    // DELETE PERJANJIAN
    // ==============================
    public function destroy($id)
    {
        try {
            \Log::info("Attempting to delete perjanjian with ID: " . $id);
            
            $perjanjian = Perjanjian::findOrFail($id);
            
            \Log::info("Perjanjian found, deleting...");
            
            // Since migration has onDelete('set null'), we can safely delete
            $perjanjian->delete();
            
            \Log::info("Perjanjian deleted successfully");
            
            return response()->json([
                'success' => true,
                'message' => 'Perjanjian berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error("Perjanjian not found: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Perjanjian tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            \Log::error("Error deleting perjanjian: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus perjanjian: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==============================
    // GET USER BY JABATAN (API)
    // ==============================
    public function getUserByJabatan($jabatan)
    {
        $user = \App\Models\User::where('jabatan', $jabatan)->first();
        
        if ($user) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $user->nama,
                    'nip' => $user->nip,
                    'jabatan' => $user->jabatan,
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User dengan jabatan tersebut tidak ditemukan'
        ], 404);
    }

    // ==============================
    // GET KEGIATAN BY PROGRAM ID (API)
    // ==============================
    public function getKegiatanByProgram($programId)
    {
        $kegiatans = Kegiatan::where('program_id', $programId)
            ->where('is_active', true)
            ->orderBy('nama_kegiatan')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $kegiatans
        ]);
    }

    // ==============================
    // GET SUB KEGIATAN BY KEGIATAN ID (API)
    // ==============================
    public function getSubKegiatanByKegiatan($kegiatanId)
    {
        $subKegiatans = SubKegiatan::where('kegiatan_id', $kegiatanId)
            ->where('is_active', true)
            ->orderBy('nama_sub_kegiatan')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $subKegiatans
        ]);
    }
}