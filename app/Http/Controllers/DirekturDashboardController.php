<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseService;
use Barryvdh\DomPDF\Facade\Pdf;

class DirekturDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Dashboard direktur langsung menampilkan halaman perjanjian
        return $this->perjanjianKinerja($request);
    }

    public function perjanjianList(Request $request)
    {
        $user = Auth::user();
        
        // Tentukan page title berdasarkan filter
        $filter = $request->get('filter', 'all');
        $pageTitle = 'Total Laporan Diterima';
        
        if ($filter === 'approved') {
            $pageTitle = 'Total Laporan Disetujui';
        } elseif ($filter === 'rejected') {
            $pageTitle = 'Total Laporan Ditolak';
        } elseif ($filter === 'waiting') {
            $pageTitle = 'Total Laporan Menunggu Persetujuan';
        }
        
        return view('dashboard.perjanjian-list', compact('pageTitle'));
    }

    public function showPerjanjian($id)
    {
        $user = Auth::user();
        // Force reload data perjanjian dari database
        $perjanjian = Perjanjian::findOrFail($id);
        \Log::debug('Perjanjian status:', [
            'id' => $perjanjian->id,
            'rejected' => $perjanjian->rejected,
            'rejection_reason' => $perjanjian->rejection_reason,
            'pihak2_signature' => $perjanjian->pihak2_signature,
            'updated_at' => $perjanjian->updated_at,
        ]);
        // Jika user bukan pihak kedua, abort
        if (!($user && ($user->nama === $perjanjian->pihak2_name || $user->nip === $perjanjian->pihak2_nip))) {
            abort(403, 'Anda tidak berhak mengakses perjanjian ini sebagai pihak kedua');
        }
        
        // Tentukan status dari database
        $status = 'waiting';
        if (!empty($perjanjian->rejected) && $perjanjian->rejected == true) {
            $status = 'rejected';
        } elseif (!empty($perjanjian->pihak2_signature)) {
            $status = 'approved';
        }
        $rejection_reason = $perjanjian->rejection_reason ?? null;
        return view('dashboard.perjanjian-show', compact('perjanjian', 'status', 'rejection_reason'));
    }

    public function perjanjianKinerja(Request $request)
    {
        $user = Auth::user();
        
        // Ambil perjanjian yang direktur sebagai pihak kedua
        $query = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })
                ->orderBy('created_at', 'desc');

        // Search functionality - filter berdasarkan nama, tanggal, atau status
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search by nama pegawai
                $q->where('pihak1_name', 'ILIKE', '%' . $search . '%')
                  // Search by tanggal
                  ->orWhereRaw('TO_CHAR(created_at, \'DD-MM-YYYY\') LIKE ?', ['%' . $search . '%'])
                  ->orWhereRaw('TO_CHAR(created_at, \'DD Mon YYYY\') ILIKE ?', ['%' . $search . '%'])
                  // Search by jabatan
                  ->orWhere('pihak1_jabatan', 'ILIKE', '%' . $search . '%');
            });
        }

        // Filter berdasarkan status jika ada
        if ($request->has('filter') && $request->filter !== 'all') {
            $filter = $request->filter;
            
            if ($filter === 'approved') {
                $query->whereNotNull('pihak2_signature')->where(function($q) {
                    $q->whereNull('rejected')->orWhere('rejected', false);
                });
            } elseif ($filter === 'rejected') {
                $query->where('rejected', true);
            } elseif ($filter === 'waiting') {
                $query->whereNull('pihak2_signature')->where(function($q) {
                    $q->whereNull('rejected')->orWhere('rejected', false);
                });
            }
        }

        // Untuk AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            // Ambil semua data sekaligus untuk menghindari multiple query
            $allData = Perjanjian::where(function($q) use ($user) {
                        $q->where('pihak2_name', $user->nama)
                          ->orWhere('pihak2_nip', $user->nip);
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            
            // Hitung counts dari data yang sudah diambil
            $counts = [
                'all' => $allData->count(),
                'approved' => $allData->filter(function($item) {
                    return !empty($item->pihak2_signature) && (empty($item->rejected) || $item->rejected == false);
                })->count(),
                'rejected' => $allData->filter(function($item) {
                    return !empty($item->rejected) && $item->rejected == true;
                })->count(),
                'waiting' => $allData->filter(function($item) {
                    return empty($item->pihak2_signature) && (empty($item->rejected) || $item->rejected == false);
                })->count(),
            ];
            
            // Filter data sesuai request
            $items = $query->get()->map(function($item) {
                $status = 'waiting';
                
                if (!empty($item->rejected) && $item->rejected == true) {
                    $status = 'rejected';
                } elseif (!empty($item->pihak2_signature)) {
                    $status = 'approved';
                }

                // Tentukan jenis perjanjian
                $jenisPerjanjian = 'Perjanjian';
                if (!empty($item->type) && $item->type === 'perubahan') {
                    $jenisPerjanjian = 'Perjanjian Perubahan';
                }

                return [
                    'id' => $item->id,
                    'pihak1_name' => $item->pihak1_name,
                    'pihak1_jabatan' => $item->pihak1_jabatan,
                    'pihak2_name' => $item->pihak2_name,
                    'jenis_perjanjian' => $jenisPerjanjian,
                    'tanggal' => optional($item->created_at)->format('d F Y'),
                    'created_at' => optional($item->created_at)->format('d M Y'),
                    'status' => $status,
                    'approved' => ($status === 'approved'),
                    'rejected' => ($status === 'rejected'),
                    'rejection_reason' => $item->rejection_reason ?? null,
                    'pihak2_signature' => $item->pihak2_signature,
                ];
            });

            return response()->json([
                'items' => $items,
                'data' => $items,
                'counts' => $counts
            ]);
        }

        $perjanjians = $query->paginate(10);
        
        // Ambil aktivitas/notifikasi (perjanjian yang sudah di-approve atau reject)
        $notifications = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })
                ->where(function($q) {
                    $q->whereNotNull('pihak2_signature')
                      ->orWhere('rejected', true);
                })
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    $status = !empty($item->rejected) && $item->rejected == true ? 'rejected' : 'approved';
                    $action = $status === 'approved' ? 'Disetujui' : 'Ditolak';
                    
                    // Tentukan jenis dokumen
                    $jenis = 'Perjanjian';
                    if (!empty($item->periode)) {
                        $jenis = $item->periode;
                    }
                    
                    return [
                        'action' => $action,
                        'status' => $status,
                        'text' => $action . ' ' . $jenis . ' ' . $item->pihak1_name,
                        'time' => $item->updated_at->diffForHumans(),
                    ];
                });
        
        return view('dashboard.direktur', compact('perjanjians', 'notifications'));
    }

    public function laporanKinerja(Request $request)
    {
        $user = Auth::user();
        
        // Ambil laporan yang terkait dengan perjanjian dimana direktur sebagai pihak kedua
        $perjanjianIds = Perjanjian::where(function($q) use ($user) {
                            $q->where('pihak2_name', $user->nama)
                              ->orWhere('pihak2_nip', $user->nip);
                        })->pluck('id');
        
        $query = Laporan::whereIn('perjanjian_id', $perjanjianIds)
                        ->orderBy('created_at', 'desc');

        // Filter jika ada
        if ($request->has('filter') && $request->filter !== 'all') {
            $filter = $request->filter;
            
            if ($filter === 'approved') {
                $query->whereNotNull('pihak2_signature');
            } elseif ($filter === 'waiting') {
                $query->whereNull('pihak2_signature');
            }
        }

        $laporans = $query->paginate(10);
        
        // Ambil aktivitas/notifikasi dari laporan yang sudah di-approve
        $notifications = Laporan::whereIn('perjanjian_id', $perjanjianIds)
                        ->whereNotNull('pihak2_signature')
                        ->orderBy('updated_at', 'desc')
                        ->limit(10)
                        ->get()
                        ->map(function($item) {
                            $perjanjian = $item->perjanjian;
                            return [
                                'action' => 'Disetujui',
                                'status' => 'approved',
                                'text' => 'Disetujui Laporan ' . ($item->periode ?? 'Kinerja') . ' ' . ($perjanjian->pihak1_name ?? ''),
                                'time' => $item->updated_at->diffForHumans(),
                            ];
                        });
        
        return view('dashboard.laporan-kinerja', compact('laporans', 'notifications'));
    }

    public function approvePerjanjian(Request $request, $id)
    {
        try {
            $user = Auth::user();
            // Hanya direktur/pimpinan yang boleh approve
            if (!$user || !(stripos($user->jabatan, 'direktur') !== false || stripos($user->jabatan, 'pimpinan') !== false)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Direktur/Pimpinan yang dapat menyetujui perjanjian.'
                ], 403);
            }
            // Cari perjanjian
            $perjanjian = Perjanjian::where('id', $id)
                                   ->where(function($q) use ($user) {
                                       $q->where('pihak2_name', $user->nama)
                                         ->orWhere('pihak2_nip', $user->nip);
                                   })
                                   ->firstOrFail();
            // Cek apakah sudah disetujui atau ditolak
            if ($perjanjian->pihak2_signature) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Perjanjian sudah disetujui sebelumnya'
                    ]);
                } else {
                    return redirect()->route('dashboard.direktur');
                }
            }
            if ($perjanjian->rejected) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Perjanjian sudah ditolak, tidak dapat disetujui'
                    ]);
                } else {
                    return redirect()->route('dashboard.direktur');
                }
            }
            // Validasi: Direktur HARUS punya tanda tangan di profile
            if (empty($user->tanda_tangan)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum mengupload tanda tangan. Silakan upload tanda tangan di menu Profile terlebih dahulu.'
                ], 422);
            }
            // Gunakan tanda tangan dari profil direktur
            $signaturePath = $user->tanda_tangan;
            // Update perjanjian - simpan tanda tangan di kedua field agar muncul di semua halaman
            $perjanjian->pihak2_signature = $signaturePath;
            $perjanjian->pihak2_ttd_path = $signaturePath; // Field ini digunakan di halaman 2 & 3
            $perjanjian->rejected = false;
            $perjanjian->rejection_reason = null;
            $perjanjian->save();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perjanjian berhasil disetujui dan tanda tangan telah ditambahkan'
                ]);
            } else {
                return redirect()->route('dashboard.direktur');
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui perjanjian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rejectPerjanjian(Request $request, $id)
    {
        try {
            $user = Auth::user();
            // Hanya direktur/pimpinan yang boleh reject
            if (!$user || !(stripos($user->jabatan, 'direktur') !== false || stripos($user->jabatan, 'pimpinan') !== false)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Direktur/Pimpinan yang dapat menolak perjanjian.'
                ], 403);
            }
            // Validasi - hanya perlu rejection_reason
            $request->validate([
                'rejection_reason' => 'required|string|min:10'
            ]);
            // Cari perjanjian
            $perjanjian = Perjanjian::where('id', $id)
                                   ->where(function($q) use ($user) {
                                       $q->where('pihak2_name', $user->nama)
                                         ->orWhere('pihak2_nip', $user->nip);
                                   })
                                   ->firstOrFail();
            // Cek apakah sudah disetujui
            if ($perjanjian->pihak2_signature) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Perjanjian sudah disetujui, tidak dapat ditolak'
                    ]);
                } else {
                    return redirect()->route('dashboard.direktur');
                }
            }
            // Update perjanjian
            $perjanjian->rejected = true;
            $perjanjian->rejection_reason = $request->rejection_reason;
            $perjanjian->pihak2_signature = null;
            $perjanjian->approved = false;
            $perjanjian->save();
            // Create notification for the user who created the perjanjian
            $pengusul = User::where('nama', $perjanjian->pihak1_name)->first();
            if ($pengusul) {
                Notification::create([
                    'user_id' => $pengusul->id,
                    'title' => 'Perjanjian Kinerja Ditolak',
                    'message' => 'Perjanjian Kinerja yang Anda ajukan telah ditolak oleh ' . $user->nama . '. Alasan: ' . $request->rejection_reason,
                    'type' => 'rejection',
                    'is_read' => false
                ]);
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perjanjian berhasil ditolak dan notifikasi telah dikirim'
                ]);
            } else {
                // Redirect ke halaman preview perjanjian dengan status ditolak
                return redirect()->route('direktur.perjanjian.show', ['id' => $perjanjian->id, 'rejected' => 1]);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Semua field harus diisi dengan benar'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Print/Preview Perjanjian PDF
     */
    public function printPerjanjian($id)
    {
        try {
            $user = Auth::user();
            
            // Ambil perjanjian
                        $perjanjian = Perjanjian::where('id', $id)
                                ->where(function($q) use ($user) {
                                        $q->where('pihak2_name', $user->nama)
                                            ->orWhere('pihak2_nip', $user->nip)
                                            ->orWhere('pihak1_name', $user->nama)
                                            ->orWhere('pihak1_nip', $user->nip);
                                })
                                ->firstOrFail();
            
            // Decode tables
            $data = $perjanjian;
            $tabelA = json_decode($perjanjian->tabelA, true) ?? [];
            $tabelB = json_decode($perjanjian->tabelB, true) ?? [];
            $tabelC = json_decode($perjanjian->tabelC, true) ?? [];
            
            // Deteksi apakah user adalah pihak kedua (direktur/pimpinan)
            $isDirektur = false;
            if ($user && ($user->nama === $perjanjian->pihak2_name || $user->nip === $perjanjian->pihak2_nip)) {
                $isDirektur = true;
            }
            // Set status konsisten dari database
            $status = 'menunggu';
            if (!empty($perjanjian->rejected) && $perjanjian->rejected == true) {
                $status = 'ditolak';
            } elseif (!empty($perjanjian->pihak2_signature)) {
                $status = 'disetujui';
            }
            return view('perjanjian.print', compact('data', 'perjanjian', 'tabelA', 'tabelB', 'tabelC', 'isDirektur', 'status'));
            
        } catch (\Exception $e) {
            Log::error('Print perjanjian error: ' . $e->getMessage());
            abort(404, 'Perjanjian tidak ditemukan');
        }
    }

    /**
     * Download Perjanjian as PDF and upload to Supabase
     */
    public function downloadPerjanjian($id)
    {
        try {
            $user = Auth::user();
            
            // Ambil perjanjian
            $perjanjian = Perjanjian::where('id', $id)
                                   ->where(function($q) use ($user) {
                                       $q->where('pihak2_name', $user->nama)
                                         ->orWhere('pihak2_nip', $user->nip);
                                   })
                                   ->firstOrFail();
            
            // Format data untuk PDF
            $pdfData = [
                'data' => $perjanjian,
                'perjanjian' => $perjanjian,
                'tabelA' => json_decode($perjanjian->tabelA, true) ?? [],
                'tabelB' => json_decode($perjanjian->tabelB, true) ?? [],
                'tabelC' => json_decode($perjanjian->tabelC, true) ?? [],
                'for_pdf' => true,
            ];

            // Helper to convert images to data URI
            $toDataUri = function ($path) {
                if (empty($path)) {
                    return null;
                }
                if (strpos($path, 'data:') === 0) {
                    return $path;
                }

                $candidates = [];
                if (file_exists(public_path($path))) {
                    $candidates[] = public_path($path);
                }
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

            // Convert images to data URIs
            $logoPath = 'images/logo_pemda.png';
            $pdfData['logo_data'] = $toDataUri($logoPath);
            $pdfData['pihak1_ttd_data'] = $toDataUri($perjanjian->pihak1_ttd ?? null);
            $pdfData['pihak2_ttd_data'] = $toDataUri($perjanjian->pihak2_signature ?? null);

            // Generate PDF
            $pdf = Pdf::loadView('perjanjian.print', $pdfData);
            $pdf->setPaper('F4', 'portrait');
            
            // Set PDF options
            $pdf->setOption('margin-left', 0);
            $pdf->setOption('margin-right', 0);
            $pdf->setOption('margin-top', 0);
            $pdf->setOption('margin-bottom', 0);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isFontSubsettingEnabled', true);
            $pdf->setOption('dpi', 96);
            $pdf->setOption('defaultMediaType', 'print');
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('chroot', public_path());
            
            // Generate filename
            $filename = 'Perjanjian_Kinerja_' . str_replace(' ', '_', $perjanjian->pihak1_name) . '_' . date('Y-m-d_His') . '.pdf';
            
            // Save PDF temporarily
            $tempPath = storage_path('app/temp/' . $filename);
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            file_put_contents($tempPath, $pdf->output());
            
            // Upload to Supabase
            try {
                $supabaseService = new SupabaseService();
                $uploadResult = $supabaseService->uploadFile(
                    $tempPath, 
                    $filename, 
                    'perjanjian-pdf'
                );
                
                if ($uploadResult['success']) {
                    Log::info('PDF uploaded to Supabase: ' . $uploadResult['url']);
                    
                    // Update perjanjian with PDF URL
                    $perjanjian->pdf_url = $uploadResult['url'];
                    $perjanjian->save();
                }
            } catch (\Exception $e) {
                Log::warning('Supabase upload failed: ' . $e->getMessage());
            }
            
            // Delete temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            // Return PDF download
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Download PDF error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}
