<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\SupabaseService;

class DirekturDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // All perjanjian where direktur is pihak kedua
        $allPerjanjians = Perjanjian::where(function ($q) use ($user) {
            $q->where('pihak2_name', $user->nama)->orWhere('pihak2_nip', $user->nip);
        })->orderBy('created_at', 'desc')->get();

        $perjanjianItems = $allPerjanjians->map(function ($p) {
            $status = $this->isRejectedValue($p->rejected) ? 'ditolak'
                : (!empty($p->pihak2_signature) ? 'disetujui' : 'menunggu');
            return [
                'id'               => $p->id,
                'pihak1_name'      => $p->pihak1_name ?? '-',
                'pihak1_jabatan'   => $p->pihak1_jabatan ?? '-',
                'jenis'            => 'Perjanjian Kinerja',
                'periode'          => $p->periode ?? optional($p->created_at)->format('Y') ?? '-',
                'tanggal'          => optional($p->created_at)->format('d M Y') ?? '-',
                'status'           => $status,
                'rejection_reason' => $p->rejection_reason ?? null,
            ];
        })->values();

        $perjanjianCounts = [
            'total'     => $perjanjianItems->count(),
            'disetujui' => $perjanjianItems->where('status', 'disetujui')->count(),
            'menunggu'  => $perjanjianItems->where('status', 'menunggu')->count(),
            'ditolak'   => $perjanjianItems->where('status', 'ditolak')->count(),
        ];

        $perjanjianIds = $allPerjanjians->pluck('id');
        $allLaporans   = Laporan::whereIn('perjanjian_id', $perjanjianIds)
            ->with('perjanjian')
            ->orderBy('created_at', 'desc')
            ->get();

        $laporanItems = $allLaporans->map(function ($l) {
            $hasRealisasi = false;
            for ($tw = 1; $tw <= 4; $tw++) {
                if (!empty($l->{'realisasi_tb' . $tw})) { $hasRealisasi = true; break; }
            }
            $status = 'terkirim';
            if (!empty($l->pihak2_signature)) {
                $status = 'disetujui';
            } elseif (!empty($l->tanggapan_pimpinan) && empty($l->kesimpulan)) {
                $status = 'ditolak';
            } elseif ($hasRealisasi && empty($l->kesimpulan) && empty($l->tanggapan_pimpinan)) {
                $status = 'menunggu';
            }
            return [
                'id'             => $l->id,
                'perjanjian_id'  => $l->perjanjian_id,
                'pihak1_name'    => $l->perjanjian?->pihak1_name ?? '-',
                'pihak1_jabatan' => $l->perjanjian?->pihak1_jabatan ?? '-',
                'uraian'         => $l->uraian_kegiatan ?? 'Laporan Kinerja',
                'triwulan'       => $l->triwulan_aktif ?? '-',
                'tahun'          => $l->tahun ?? optional($l->created_at)->format('Y') ?? '-',
                'status'         => $status,
            ];
        })->values();

        $laporanCounts = [
            'total'     => $laporanItems->count(),
            'disetujui' => $laporanItems->where('status', 'disetujui')->count(),
            'menunggu'  => $laporanItems->where('status', 'menunggu')->count(),
            'ditolak'   => $laporanItems->where('status', 'ditolak')->count(),
        ];

        // Build monthly chart data
        $months         = [];
        $pkDisetujui    = array_fill(0, 12, 0);
        $pkDitolak      = array_fill(0, 12, 0);
        $pkMenunggu     = array_fill(0, 12, 0);
        $lkDisetujui    = array_fill(0, 12, 0);
        $lkMenunggu     = array_fill(0, 12, 0);
        for ($m = 1; $m <= 12; $m++) {
            $months[] = \Carbon\Carbon::create(null, $m)->locale('id')->translatedFormat('M');
        }
        foreach ($allPerjanjians as $p) {
            $idx = (int) optional($p->created_at)->format('n') - 1;
            if ($idx < 0 || $idx > 11) continue;
            $s = $this->isRejectedValue($p->rejected) ? 'ditolak'
                : (!empty($p->pihak2_signature) ? 'disetujui' : 'menunggu');
            if ($s === 'disetujui') $pkDisetujui[$idx]++;
            elseif ($s === 'ditolak') $pkDitolak[$idx]++;
            else $pkMenunggu[$idx]++;
        }
        foreach ($allLaporans as $l) {
            $idx = (int) optional($l->created_at)->format('n') - 1;
            if ($idx < 0 || $idx > 11) continue;
            if (!empty($l->pihak2_signature)) $lkDisetujui[$idx]++;
            else $lkMenunggu[$idx]++;
        }

        $chartData = compact('months', 'pkDisetujui', 'pkDitolak', 'pkMenunggu', 'lkDisetujui', 'lkMenunggu');

        return view('dashboard.direktur', compact(
            'perjanjianItems', 'perjanjianCounts',
            'laporanItems', 'laporanCounts',
            'chartData'
        ));
    }

    public function perjanjianList(Request $request)
    {
        $user = Auth::user();

        $allPerjanjians = Perjanjian::where(function ($q) use ($user) {
            $q->where('pihak2_name', $user->nama)->orWhere('pihak2_nip', $user->nip);
        })->orderBy('created_at', 'desc')->get();

        $allItems = $allPerjanjians->map(function ($p) {
            $status = $this->isRejectedValue($p->rejected) ? 'ditolak'
                : (!empty($p->pihak2_signature) ? 'disetujui' : 'menunggu');
            return [
                'id'               => $p->id,
                'periode'          => $p->periode ?? optional($p->created_at)->format('Y') ?? '-',
                'tanggal'          => optional($p->created_at)->translatedFormat('d F Y') ?? '-',
                'status'           => $status,
                'rejection_reason' => $p->rejection_reason ?? null,
                'print_url'        => route('direktur.perjanjian.print', $p->id),
            ];
        })->values();

        $statusParam = $request->get('status', 'total');
        $items = $statusParam === 'total'
            ? $allItems
            : $allItems->where('status', $statusParam)->values();

        $counts = [
            'total'     => $allItems->count(),
            'disetujui' => $allItems->where('status', 'disetujui')->count(),
            'menunggu'  => $allItems->where('status', 'menunggu')->count(),
            'ditolak'   => $allItems->where('status', 'ditolak')->count(),
        ];

        $titles = [
            'total'     => 'Semua Perjanjian Kinerja',
            'disetujui' => 'Perjanjian Kinerja Disetujui',
            'menunggu'  => 'Menunggu Persetujuan',
            'ditolak'   => 'Perjanjian Kinerja Ditolak',
        ];
        $pageTitle = $titles[$statusParam] ?? 'Perjanjian Kinerja';

        return view('dashboard.direktur-perjanjian-list', compact('items', 'counts', 'statusParam', 'pageTitle'));
    }

    public function laporanList(Request $request)
    {
        $user = Auth::user();

        $allPerjanjians = Perjanjian::where(function ($q) use ($user) {
            $q->where('pihak2_name', $user->nama)->orWhere('pihak2_nip', $user->nip);
        })->pluck('id');

        $allLaporans = Laporan::whereIn('perjanjian_id', $allPerjanjians)
            ->with('perjanjian')
            ->orderBy('created_at', 'desc')
            ->get();

        $allItems = $allLaporans->map(function ($l) {
            $status = 'terkirim';
            if (!empty($l->pihak2_signature)) {
                $status = 'disetujui';
            } elseif (!empty($l->tanggapan_pimpinan) && empty($l->kesimpulan)) {
                $status = 'ditolak';
            } else {
                $hasRealisasi = false;
                for ($tw = 1; $tw <= 4; $tw++) {
                    if (!empty($l->{'realisasi_tb' . $tw})) { $hasRealisasi = true; break; }
                }
                if ($hasRealisasi && empty($l->kesimpulan) && empty($l->tanggapan_pimpinan)) {
                    $status = 'menunggu';
                }
            }
            return [
                'id'            => $l->id,
                'perjanjian_id' => $l->perjanjian_id,
                'pihak1_name'   => $l->perjanjian?->pihak1_name ?? '-',
                'triwulan'      => $l->triwulan_aktif ?? '-',
                'tahun'         => $l->tahun ?? optional($l->created_at)->format('Y') ?? '-',
                'status'        => $status,
                'view_url'      => route('laporan.pdf.preview', ['id' => $l->id]) . '?triwulan=' . ($l->triwulan_aktif ?? 1),
            ];
        })->values();

        $statusParam = $request->get('status', 'total');
        $items = $statusParam === 'total'
            ? $allItems
            : $allItems->where('status', $statusParam)->values();

        $counts = [
            'total'     => $allItems->count(),
            'disetujui' => $allItems->where('status', 'disetujui')->count(),
            'menunggu'  => $allItems->where('status', 'menunggu')->count(),
            'ditolak'   => $allItems->where('status', 'ditolak')->count(),
        ];

        $titles = [
            'total'     => 'Semua Laporan Kinerja',
            'disetujui' => 'Laporan Kinerja Disetujui',
            'menunggu'  => 'Menunggu Reviu',
            'ditolak'   => 'Laporan Kinerja Ditolak',
        ];
        $pageTitle = $titles[$statusParam] ?? 'Laporan Kinerja';

        return view('dashboard.direktur-laporan-list', compact('items', 'counts', 'statusParam', 'pageTitle'));
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
        if ($this->isRejectedValue($perjanjian->rejected)) {
            $status = 'rejected';
        } elseif (!empty($perjanjian->pihak2_signature)) {
            $status = 'approved';
        }
        $rejection_reason = $perjanjian->rejection_reason ?? null;
        return view('dashboard.perjanjian-show', compact('perjanjian', 'status', 'rejection_reason'));
    }

    public function perjanjianKinerja(Request $request)
    {
        return redirect()->route('dashboard.direktur', ['panel' => 'perjanjian']);
    }

    public function perjanjianKinerja_legacy(Request $request)
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
                $query->whereNotNull('pihak2_signature')
                      ->where('rejected', false);
            } elseif ($filter === 'rejected') {
                $query->where('rejected', true);
            } elseif ($filter === 'waiting') {
                $query->whereNull('pihak2_signature')
                      ->where('rejected', false);
            }
        }

        // Ambil semua data direktur sebagai pihak kedua untuk kartu status (selalu real-time)
        $allData = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })
                ->orderBy('created_at', 'desc')
                ->get();

        $counts = [
            'all' => $allData->count(),
            'approved' => $allData->filter(function($item) {
                return !empty($item->pihak2_signature) && $this->isNotRejected($item->rejected);
            })->count(),
            'rejected' => $allData->filter(function($item) {
                return $this->isRejectedValue($item->rejected);
            })->count(),
            'waiting' => $allData->filter(function($item) {
                return empty($item->pihak2_signature) && $this->isNotRejected($item->rejected);
            })->count(),
        ];

        // Untuk AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            // Filter data sesuai request
            $items = $query->get()->map(function($item) {
                $status = 'waiting';
                
                if ($this->isRejectedValue($item->rejected)) {
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
        
        // Build monthly summary (Jan..Dec) for chart: approved vs rejected
        $months = [];
        $approvedSeries = array_fill(0, 12, 0);
        $rejectedSeries = array_fill(0, 12, 0);
        for ($m = 1; $m <= 12; $m++) {
            $months[] = \Carbon\Carbon::create(null, $m, 1)->translatedFormat('M');
        }
        foreach ($allData as $item) {
            $monthIndex = (int) (\Carbon\Carbon::parse($item->created_at)->format('n')) - 1;
            if ($this->isRejectedValue($item->rejected)) {
                $rejectedSeries[$monthIndex]++;
            } elseif (!empty($item->pihak2_signature)) {
                $approvedSeries[$monthIndex]++;
            }
        }
        $monthly = [
            'labels' => $months,
            'approved' => $approvedSeries,
            'rejected' => $rejectedSeries,
        ];

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
                    $status = $this->isRejectedValue($item->rejected) ? 'rejected' : 'approved';
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
        
        return view('dashboard.direktur', compact('perjanjians', 'notifications', 'counts', 'monthly'));
    }

    public function laporanKinerja(Request $request)
    {
        return redirect()->route('dashboard.direktur', ['panel' => 'laporan']);
    }

    public function laporanKinerja_legacy(Request $request)
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

        $perjanjian = Perjanjian::whereIn('id', $perjanjianIds)->orderBy('updated_at', 'desc')->first();
        $triwulanAktif = Setting::where('key', 'triwulan_aktif')->value('value') ?? 1;
        $message = null;
        
        return view('laporan-kinerja', compact('perjanjian', 'laporans', 'triwulanAktif', 'message', 'notifications'));
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
     * Approve Laporan Kinerja (Direktur memberikan tanggapan & menyetujui)
     */
    public function approveLaporan(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $laporan = Laporan::findOrFail($id);
            $perjanjian = $laporan->perjanjian;

            // Validasi: hanya pihak2 dari perjanjian ini yang boleh approve
            if ($user->nama !== $perjanjian->pihak2_name && $user->nip !== $perjanjian->pihak2_nip) {
                return response()->json(['success' => false, 'message' => 'Tidak memiliki izin untuk menyetujui laporan ini.'], 403);
            }

            // Validasi: laporan harus sudah tervalidasi untuk triwulan aktif
            if (!$laporan->hasValidationForTriwulan()) {
                return response()->json(['success' => false, 'message' => 'Laporan belum tervalidasi. Tidak dapat disetujui.'], 422);
            }

            $request->validate([
                'tanggapan_pimpinan' => 'required|string',
            ]);

            // Simpan tanggapan & tanda tangani
            $laporan->tanggapan_pimpinan = $request->tanggapan_pimpinan;
            $laporan->pihak2_name       = $perjanjian->pihak2_name ?? $user->nama;
            $laporan->pihak2_jabatan    = $perjanjian->pihak2_jabatan ?? $user->jabatan;
            $laporan->pihak2_signature  = $user->tanda_tangan ?? $perjanjian->pihak2_signature ?? '';
            $laporan->save();

            return response()->json(['success' => true, 'message' => 'Laporan kinerja berhasil disetujui.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Pilih salah satu tanggapan terlebih dahulu.'], 422);
        } catch (\Exception $e) {
            Log::error('approveLaporan error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Tolak Laporan Kinerja — hapus data validasi agar dikembalikan ke user
     */
    public function rejectLaporan(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $laporan = Laporan::findOrFail($id);
            $perjanjian = $laporan->perjanjian;

            if ($user->nama !== $perjanjian->pihak2_name && $user->nip !== $perjanjian->pihak2_nip) {
                return response()->json(['success' => false, 'message' => 'Tidak memiliki izin.'], 403);
            }

            // Hapus semua data validasi agar laporan kembali ke kondisi awal
            $laporan->kesimpulan         = null;
            $laporan->tanggapan_pimpinan = null;
            $laporan->pihak2_signature   = null;
            $laporan->pihak2_name        = null;
            $laporan->pihak2_jabatan     = null;
            $laporan->bab_capaian        = null;
            $laporan->bab_rencana        = null;
            $laporan->save();

            return response()->json(['success' => true, 'message' => 'Laporan kinerja telah dikembalikan ke pegawai.']);

        } catch (\Exception $e) {
            Log::error('rejectLaporan error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Print/Preview Perjanjian PDF
     */
    public function printPerjanjian($id)
    {
        try {
            $user = Auth::user();
            
            // Ambil perjanjian - optimized query untuk preview HTML saja
            $perjanjian = Perjanjian::where('id', $id)
                                ->where(function($q) use ($user) {
                                        $q->where('pihak2_name', $user->nama)
                                            ->orWhere('pihak2_nip', $user->nip)
                                            ->orWhere('pihak1_name', $user->nama)
                                            ->orWhere('pihak1_nip', $user->nip);
                                })
                                ->firstOrFail();
            
            // Decode tables - lazy decode hanya saat dibutuhkan
            $data = $perjanjian;
            $tabelA = json_decode($perjanjian->tabelA, true) ?? [];
            $tabelB = json_decode($perjanjian->tabelB, true) ?? [];
            $tabelC = json_decode($perjanjian->tabelC, true) ?? [];
            
            // Deteksi apakah user adalah pihak kedua (direktur/pimpinan)
            $isDirektur = false;
            if ($user && ($user->nama === $perjanjian->pihak2_name || $user->nip === $perjanjian->pihak2_nip)) {
                $isDirektur = true;
            }

            // Isi pangkat dari profil user jika belum tersimpan di perjanjian
            if (empty($perjanjian->pihak2_pangkat)) {
                $pihak2User = User::where('nama', $perjanjian->pihak2_name)
                    ->orWhere('nip', $perjanjian->pihak2_nip)
                    ->first();
                if ($pihak2User && !empty($pihak2User->pangkat)) {
                    $perjanjian->pihak2_pangkat = $pihak2User->pangkat;
                }
            }
            if (empty($perjanjian->pihak1_pangkat)) {
                $pihak1User = User::where('nama', $perjanjian->pihak1_name)
                    ->orWhere('nip', $perjanjian->pihak1_nip)
                    ->first();
                if ($pihak1User && !empty($pihak1User->pangkat)) {
                    $perjanjian->pihak1_pangkat = $pihak1User->pangkat;
                }
            }
            
            // Set status konsisten dari database
            $status = 'menunggu';
            if ($this->isRejectedValue($perjanjian->rejected)) {
                $status = 'ditolak';
            } elseif (!empty($perjanjian->pihak2_signature)) {
                $status = 'disetujui';
            }

            // Cek apakah pihak1 sudah punya perjanjian lain yang disetujui (selain ini)
            $approvedOther = null;
            if ($isDirektur && $status === 'menunggu') {
                $approvedOther = Perjanjian::where('id', '!=', $perjanjian->id)
                    ->where('pihak1_name', $perjanjian->pihak1_name)
                    ->whereNotNull('pihak2_signature')
                    ->where(function ($q) { $q->where('rejected', false)->orWhereNull('rejected'); })
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }
            
            // Flag untuk HTML preview (bukan PDF)
            $for_pdf = false;
            
            return view('perjanjian.print', compact('data', 'perjanjian', 'tabelA', 'tabelB', 'tabelC', 'isDirektur', 'status', 'for_pdf', 'approvedOther'));
            
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
            
            // Use PdfHelper for consistency dengan preview
            // Menggunakan Snappy logic
            $pdfContent = \App\Helpers\PdfHelper::generatePerjanjianSnappy($perjanjian);
            
            // Generate filename
            $filename = \App\Helpers\PdfHelper::generateFilename($perjanjian);
            
            // Save PDF temporarily untuk upload ke Supabase
            $tempPath = storage_path('app/temp/' . $filename);
            if (!file_exists(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }
            
            // Note: $pdfContent is string (raw bytes)
            file_put_contents($tempPath, $pdfContent);
            
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
            
            // Delete temp file after upload (or keep it if needed, but usually we delete)
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            // Return PDF download
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
        } catch (\Exception $e) {
            Log::error('Download PDF error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    private function isRejectedValue($value): bool
    {
        // Handle semua variasi boolean: true, 1, '1', 't', 'true'
        if (is_bool($value)) {
            return $value === true;
        }
        if (is_numeric($value)) {
            return (int)$value === 1;
        }
        if (is_string($value)) {
            return in_array(strtolower($value), ['1', 't', 'true', 'yes']);
        }
        return false;
    }

    private function isNotRejected($value): bool
    {
        return !$this->isRejectedValue($value);
    }
}
