<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Setting;
use App\Services\RuleBasedReasoningService;
use App\Services\SupabaseService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Redirect user ke dashboard sesuai dengan jabatan mereka
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Admin tetap ke panel admin, semua non-admin ke dashboard wadir bersama
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Direktur tetap memakai dashboard direktur
        if ($user->jabatan === 'Direktur') {
            return redirect()->route('dashboard.direktur');
        }

        return redirect()->route('dashboard.wadir');
    }
    
    /**
     * Halaman home untuk staff
     */
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    private function resolveDashboardRouteForUser($user): ?string
    {
        if (!$user) {
            return null;
        }

        if ($user->role === 'admin') {
            return 'admin.dashboard';
        }

        $jabatan = (string) $user->jabatan;

        if ($jabatan === 'Direktur') {
            return 'dashboard.direktur';
        }

        if (in_array($jabatan, ['Wakil Direktur Umum dan Keuangan', 'Wakil Direktur Pelayanan', 'Wakil Direktur Perencanaan dan Keuangan'], true)) {
            return 'dashboard.wadir';
        }

        if (strpos($jabatan, 'Kabag') !== false || strpos($jabatan, 'Kepala Bagian') !== false ||
            strpos($jabatan, 'Kabid') !== false || strpos($jabatan, 'Kepala Bidang') !== false) {
            return 'dashboard.kabag.kabid';
        }

        if (strpos($jabatan, 'Kasi') !== false || strpos($jabatan, 'Kepala Seksi') !== false ||
            strpos($jabatan, 'Katimker') !== false || strpos($jabatan, 'Staf') !== false) {
            return 'dashboard.katimker.staf';
        }

        return null;
    }

    public function home(Request $request)
    {
        $user = Auth::user();
        return redirect()->route('dashboard.wadir');
    }

    private function buildUserDashboardChartData(?Perjanjian $perjanjian): array
    {
        $labels = ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'];
        $emptySeries = [0, 0, 0, 0];

        if (!$perjanjian) {
            return [
                'labels' => $labels,
                'targets' => $emptySeries,
                'realisasi' => $emptySeries,
                'hasData' => false,
            ];
        }

        $tabelC = is_array($perjanjian->tabelC)
            ? $perjanjian->tabelC
            : json_decode($perjanjian->tabelC ?? '[]', true);

        if (!is_array($tabelC) || empty($tabelC)) {
            return [
                'labels' => $labels,
                'targets' => $emptySeries,
                'realisasi' => $emptySeries,
                'hasData' => false,
            ];
        }

        $targets = $this->extractKeuanganTargetsByTriwulan($tabelC);
        $realisasi = $this->extractKeuanganRealisasiByTriwulan($perjanjian->id, $tabelC);

        return [
            'labels' => $labels,
            'targets' => $targets,
            'realisasi' => $realisasi,
            'hasData' => collect($targets)->sum() > 0 || collect($realisasi)->sum() > 0,
        ];
    }

    /**
     * Dashboard untuk Wadir
     */
    public function wadir()
    {
        $user = Auth::user();

        if ($user?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $matchesCreatorIdentity = function ($query) use ($user) {
            $normalizedNama = trim((string) ($user->nama ?? ''));
            $normalizedJabatan = trim((string) ($user->jabatan ?? ''));
            $normalizedNip = trim((string) ($user->nip ?? ''));
            $normalizedNipDigits = preg_replace('/\D+/', '', $normalizedNip);

            $query->orWhere('user_id', $user->id);

            $query->orWhere(function ($q) use ($normalizedNama, $normalizedJabatan, $normalizedNip, $normalizedNipDigits) {
                if ($normalizedNama !== '') {
                    $q->whereRaw('LOWER(TRIM(pihak1_name)) = LOWER(TRIM(?))', [$normalizedNama])
                      ->orWhereRaw('LOWER(TRIM(pihak1_name)) LIKE LOWER(TRIM(?))', ['%' . $normalizedNama . '%']);
                }

                if ($normalizedJabatan !== '') {
                    $q->orWhereRaw('LOWER(TRIM(pihak1_jabatan)) = LOWER(TRIM(?))', [$normalizedJabatan])
                      ->orWhereRaw('LOWER(TRIM(pihak1_jabatan)) LIKE LOWER(TRIM(?))', ['%' . $normalizedJabatan . '%']);
                }

                if ($normalizedNip !== '') {
                    $q->orWhereRaw("LOWER(REPLACE(TRIM(COALESCE(pihak1_nip, '')), ' ', '')) = LOWER(REPLACE(TRIM(?), ' ', ''))", [$normalizedNip]);
                }

                if (!empty($normalizedNipDigits)) {
                    $q->orWhereRaw("regexp_replace(COALESCE(pihak1_nip, ''), '[^0-9]', '', 'g') = ?", [$normalizedNipDigits]);
                }
            });
        };

        $matchesPihakKeduaIdentity = function ($query) use ($user) {
            $normalizedNama = trim((string) ($user->nama ?? ''));
            $normalizedJabatan = trim((string) ($user->jabatan ?? ''));
            $normalizedNip = trim((string) ($user->nip ?? ''));
            $normalizedNipDigits = preg_replace('/\D+/', '', $normalizedNip);

            $query->orWhere(function ($q) use ($normalizedNama, $normalizedJabatan, $normalizedNip, $normalizedNipDigits) {
                // Identitas pihak kedua dibuat ketat agar nama sama lintas akun tidak masuk bersamaan.
                if (!empty($normalizedNipDigits)) {
                    if ($normalizedJabatan !== '') {
                        $q->whereRaw("regexp_replace(COALESCE(pihak2_nip, ''), '[^0-9]', '', 'g') = ?", [$normalizedNipDigits])
                          ->whereRaw("LOWER(TRIM(COALESCE(pihak2_jabatan, ''))) = LOWER(TRIM(?))", [$normalizedJabatan]);
                    } else {
                        $q->whereRaw("regexp_replace(COALESCE(pihak2_nip, ''), '[^0-9]', '', 'g') = ?", [$normalizedNipDigits]);
                    }
                } elseif ($normalizedNip !== '') {
                    if ($normalizedJabatan !== '') {
                        $q->whereRaw("LOWER(REPLACE(TRIM(COALESCE(pihak2_nip, '')), ' ', '')) = LOWER(REPLACE(TRIM(?), ' ', ''))", [$normalizedNip])
                          ->whereRaw("LOWER(TRIM(COALESCE(pihak2_jabatan, ''))) = LOWER(TRIM(?))", [$normalizedJabatan]);
                    } else {
                        $q->whereRaw("LOWER(REPLACE(TRIM(COALESCE(pihak2_nip, '')), ' ', '')) = LOWER(REPLACE(TRIM(?), ' ', ''))", [$normalizedNip]);
                    }
                }

                if ($normalizedNama !== '' && $normalizedJabatan !== '') {
                    $q->orWhere(function ($qq) use ($normalizedNama, $normalizedJabatan) {
                        $qq->whereRaw('LOWER(TRIM(pihak2_name)) = LOWER(TRIM(?))', [$normalizedNama])
                           ->whereRaw('LOWER(TRIM(pihak2_jabatan)) = LOWER(TRIM(?))', [$normalizedJabatan]);
                    });

                    $q->orWhere(function ($qq) use ($normalizedNama, $normalizedJabatan) {
                        $qq->whereRaw('LOWER(TRIM(pihak2_name)) LIKE LOWER(TRIM(?))', ['%#' . $normalizedNama . '%'])
                           ->whereRaw('LOWER(TRIM(pihak2_jabatan)) = LOWER(TRIM(?))', [$normalizedJabatan]);
                    });

                    return;
                }

                if ($normalizedNama !== '' && $normalizedJabatan === '') {
                    $q->orWhereRaw('LOWER(TRIM(pihak2_name)) = LOWER(TRIM(?))', [$normalizedNama]);
                }
            });
        };

        $jabatanLower = strtolower((string) ($user->jabatan ?? ''));
        $isPihakKeduaMode = str_contains($jabatanLower, 'direktur') || str_contains($jabatanLower, 'wadir') || str_contains($jabatanLower, 'wakil direktur');

        // Role user harus selalu melihat perjanjian yang dia buat.
        // Untuk jabatan pimpinan/wadir, tetap tampilkan juga perjanjian saat menjadi pihak kedua.
        if ($user->role === 'user') {
            $wadirPerjanjianItems = Perjanjian::with('user')->where(function ($q) use ($matchesCreatorIdentity, $matchesPihakKeduaIdentity, $isPihakKeduaMode) {
                $matchesCreatorIdentity($q);

                if ($isPihakKeduaMode) {
                    $matchesPihakKeduaIdentity($q);
                }
            })->get();
        } else {
            // Akun pimpinan/direktur fokus sebagai pihak kedua agar akun nama sama beda role tidak saling tarik data.
            if ($isPihakKeduaMode) {
                $wadirPerjanjianItems = Perjanjian::with('user')->where(function ($q) use ($matchesPihakKeduaIdentity) {
                    $matchesPihakKeduaIdentity($q);
                })->get();
            } else {
                // Statistik perjanjian panel Wadir: samakan classifier status dengan halaman perjanjian.
                $wadirPerjanjianItems = Perjanjian::with('user')->where(function ($q) use ($matchesCreatorIdentity) {
                        $matchesCreatorIdentity($q);
                    })
                    ->orWhere(function ($q) use ($matchesPihakKeduaIdentity) {
                        $matchesPihakKeduaIdentity($q);
                    })
                    ->get();
            }
        }

        // Jika pihak kedua menggunakan format tag (#), pastikan data pihak pertama
        // mereferensikan user pembuat perjanjian (user yang melakukan tagging).
        $wadirPerjanjianItems->each(function ($item) {
            $pihak2Name = strtolower((string) ($item->pihak2_name ?? ''));
            if (!str_contains($pihak2Name, '#')) {
                return;
            }

            $creator = $item->user;
            if (!$creator) {
                return;
            }

            $item->pihak1_name = $creator->nama ?? $item->pihak1_name;
            $item->pihak1_jabatan = $creator->jabatan ?? $item->pihak1_jabatan;
            $item->pihak1_nip = $creator->nip ?? $item->pihak1_nip;
            $item->pihak1_pangkat = $creator->pangkat ?? $item->pihak1_pangkat;
        });

        $normalizeStatus = function ($item) {
            $status = strtolower((string) ($item->status ?? ''));

            // Utamakan indikator faktual agar status stale di kolom `status` tidak menyesatkan panel.
            if (!empty($item->rejected) && (string) $item->rejected !== '0') {
                return 'ditolak';
            }
            if ($status === 'disetujui' && empty($item->pihak2_signature)) {
                return 'menunggu';
            }
            if (!empty($item->pihak2_signature)) {
                return 'disetujui';
            }
            if ($status === '') {
                return 'terkirim';
            }

            $statusMap = [
                'sent' => 'terkirim',
                'draft' => 'terkirim',
                'terkirim' => 'terkirim',
                'menunggu' => 'menunggu',
                'waiting' => 'menunggu',
                'disetujui' => 'disetujui',
                'approved' => 'disetujui',
                'ditolak' => 'ditolak',
                'rejected' => 'ditolak',
            ];

            return $statusMap[$status] ?? 'terkirim';
        };

        $totalPerjanjian = $wadirPerjanjianItems->count();
        $perjanjianSent = $wadirPerjanjianItems->filter(function ($item) use ($normalizeStatus) {
            return $normalizeStatus($item) === 'terkirim';
        })->count();
        $perjanjianApproved = $wadirPerjanjianItems->filter(function ($item) use ($normalizeStatus) {
            return $normalizeStatus($item) === 'disetujui';
        })->count();
        $perjanjianRejected = $wadirPerjanjianItems->filter(function ($item) use ($normalizeStatus) {
            return $normalizeStatus($item) === 'ditolak';
        })->count();
        $perjanjianWaiting = $wadirPerjanjianItems->filter(function ($item) use ($normalizeStatus) {
            return $normalizeStatus($item) === 'menunggu';
        })->count();

        $perjanjianItems = $wadirPerjanjianItems
            ->sortByDesc('updated_at')
            ->map(function ($item) use ($normalizeStatus) {
                return [
                    'id' => $item->id,
                    'nomor_perjanjian' => $item->nomor_perjanjian,
                    'pihak1_name' => $item->pihak1_name,
                    'pihak2_name' => $item->pihak2_name,
                    'status' => $normalizeStatus($item),
                    'agreement_date' => optional($item->agreement_date)->format('d M Y'),
                    'created_at' => optional($item->created_at)->format('d M Y'),
                    'document_url' => route('perjanjian.print', ['id' => $item->id]),
                ];
            })
            ->values();

        // Data perjanjian disetujui untuk turunan data laporan/chart Wadir.
        // Gunakan classifier status yang sama agar otomatis sinkron saat status berubah.
        $perjanjians = $wadirPerjanjianItems->filter(function ($item) use ($normalizeStatus) {
            return $normalizeStatus($item) === 'disetujui';
        })->values();

        $chartPerjanjian = null;
        $chartLaporan = null;
        $chartData = [
            'kinerja_labels' => [],
            'kinerja_targets' => [],
            'kinerja_realisasi' => [],
            'keuangan_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
            'keuangan_targets' => [0, 0, 0, 0],
            'keuangan_realisasi' => [0, 0, 0, 0],
        ];

        $chartPerjanjian = $wadirPerjanjianItems
            ->filter(function ($item) use ($normalizeStatus, $user) {
                return (int) $item->user_id === (int) $user->id && $normalizeStatus($item) === 'disetujui';
            })
            ->sortByDesc('updated_at')
            ->first();

        if ($chartPerjanjian) {
            $chartLaporan = Laporan::where('perjanjian_id', $chartPerjanjian->id)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->first();

            if ($chartLaporan && $this->hasLaporanChartData($chartLaporan)) {
                $chartData = $this->buildWadirChartData($chartPerjanjian, $chartLaporan);
            }
        }

        // Build simple notifications from recent laporans (fallback)
        $notifications = \App\Models\Laporan::whereIn('perjanjian_id', $perjanjians->pluck('id')->toArray())
                            ->latest()
                            ->take(6)
                            ->get()
                            ->map(function($l){
                                return (object) [
                                    'title' => 'Laporan: ' . substr($l->uraian_kegiatan ?? 'Kegiatan', 0, 40),
                                    'message' => 'Triwulan ' . ($l->triwulan_aktif ?? '-') . ' — ' . ($l->bab_capaian ? substr(strip_tags($l->bab_capaian),0,120) : ''),
                                    'created_at' => $l->updated_at ?? $l->created_at,
                                ];
                            });

        // Compute modal counts for dashboard using collection operations to avoid SQL on missing columns.
        // Gunakan SEMUA perjanjian terkait Wadir agar laporan tetap terlihat saat perjanjian dikembalikan untuk edit.
        $perjanjianIds = $wadirPerjanjianItems->pluck('id')->toArray();
        $perjanjianStatusById = $wadirPerjanjianItems->mapWithKeys(function ($p) use ($normalizeStatus) {
            return [$p->id => $normalizeStatus($p)];
        });

        // Fetch related laporans into collection (single query)
        $laporansForWaiting = \App\Models\Laporan::whereIn('perjanjian_id', $perjanjianIds)->get();

        // Total laporan kinerja dari semua perjanjian yang terkait
        $totalLaporan = $laporansForWaiting->count();

        // Laporan yang ditandatangani/approved by pimpinan (from fetched collection)
        $laporanApprovedByPimpinan = $laporansForWaiting->filter(function($l) use ($perjanjianStatusById){
            $perjanjianStatus = $perjanjianStatusById[$l->perjanjian_id] ?? 'terkirim';
            return $perjanjianStatus === 'disetujui' && !empty($l->pihak2_signature);
        })->count();

        // Laporan yang divalidasi (heuristic: kesimpulan tidak kosong) — computed in PHP to avoid missing-column SQL
        $laporanValidatedCount = $laporansForWaiting->filter(function($l){
            return !empty($l->kesimpulan);
        })->count();

        // Laporan menunggu reviu: memiliki minimal 1 realisasi triwulan, tetapi belum ada kesimpulan/tanggapan
        $laporanWaitingReviewCount = 0;
        foreach ($laporansForWaiting as $lap) {
            $perjanjianStatus = $perjanjianStatusById[$lap->perjanjian_id] ?? 'terkirim';

            // Jika parent perjanjian tidak disetujui (mis. dikembalikan untuk edit),
            // paksa laporan berada pada status menunggu.
            if ($perjanjianStatus !== 'disetujui') {
                $laporanWaitingReviewCount++;
                continue;
            }

            $hasRealisasi = false;
            for ($tw = 1; $tw <= 4; $tw++) {
                $field = 'realisasi_tb' . $tw;
                if (!empty($lap->{$field})) { $hasRealisasi = true; break; }
            }
            if ($hasRealisasi && empty($lap->pihak2_signature) && (empty($lap->rejected) || $lap->rejected == false || $lap->rejected == 0 || $lap->rejected === '0')) {
                $laporanWaitingReviewCount++;
            }
        }

        // Data untuk modal pilih triwulan PDF pada panel laporan wadir.
        $pdfPerjanjianId = optional($perjanjians->sortByDesc('updated_at')->first())->id;
        $pdfTriwulanAvailability = [1 => false, 2 => false, 3 => false, 4 => false];
        if (!empty($pdfPerjanjianId)) {
            $laporanPdf = $laporansForWaiting
                ->where('perjanjian_id', $pdfPerjanjianId)
                ->sortByDesc('updated_at')
                ->first();

            if ($laporanPdf) {
                for ($tw = 1; $tw <= 4; $tw++) {
                    $pdfTriwulanAvailability[$tw] = $this->hasNonZeroTriwulanRealisasi($laporanPdf->{'realisasi_tb' . $tw} ?? null);
                }
            }
        }

        // Build laporan items with computed status for the preview modal
        $laporanItems = $laporansForWaiting->map(function($l) use ($perjanjianStatusById) {
            $hasRealisasi = false;
            for ($tw = 1; $tw <= 4; $tw++) {
                if (!empty($l->{'realisasi_tb' . $tw})) { $hasRealisasi = true; break; }
            }
            $perjanjianStatus = $perjanjianStatusById[$l->perjanjian_id] ?? 'terkirim';

            if ($perjanjianStatus !== 'disetujui') {
                $status = 'menunggu';
            } elseif (!empty($l->pihak2_signature)) {
                $status = 'disetujui';
            } elseif ($hasRealisasi && empty($l->pihak2_signature) && (empty($l->rejected) || $l->rejected == false || $l->rejected == 0 || $l->rejected === '0')) {
                $status = 'menunggu';
            } elseif ((!empty($l->rejected) && (string) $l->rejected !== '0') || (!empty($l->tanggapan_pimpinan) && empty($l->kesimpulan))) {
                $status = 'ditolak';
            } else {
                $status = 'terkirim';
            }
            return [
                'id'               => $l->id,
                'perjanjian_id'    => $l->perjanjian_id,
                'uraian_kegiatan'  => $l->uraian_kegiatan ?? 'Laporan Kinerja',
                'triwulan_aktif'   => $l->triwulan_aktif,
                'status'           => $status,
                'tahun'            => $l->tahun ?? null,
                'periode'          => $l->periode ?? null,
            ];
        })->values();

        $laporanRejectedCount  = $laporanItems->filter(fn($i) => $i['status'] === 'ditolak')->count();
        $laporanTerkirimCount  = $laporanItems->filter(fn($i) => $i['status'] === 'terkirim')->count();

        // Triwulan laporan milik Wadir sendiri (bukan bawahan) untuk cek duplikat di tombol Tambah Laporan
        $ownPerjanjianIds = $wadirPerjanjianItems->filter(fn($p) => $p->user_id === $user->id)->pluck('id');
        $ownLaporanTriwulans = \App\Models\Laporan::whereIn('perjanjian_id', $ownPerjanjianIds)
            ->pluck('triwulan_aktif')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Check if active triwulan has already been validated
        $isActiveTrwValidated = false;

        // Sumber data panel validasi:
        // - Untuk akun Wadir/Direktur (pihak kedua), gunakan seluruh perjanjian yang direview.
        // - Untuk akun user biasa, batasi ke perjanjian milik sendiri.
        $validasiPerjanjianIds = $isPihakKeduaMode
            ? $wadirPerjanjianItems->pluck('id')
            : $ownPerjanjianIds;

        $validasiLaporanItems = $laporansForWaiting
            ->whereIn('perjanjian_id', $validasiPerjanjianIds->toArray())
            ->sortByDesc('updated_at')
            ->sortByDesc('id')
            ->values()
            ->map(function ($l) {
                return [
                    'id' => $l->id,
                    'perjanjian_id' => $l->perjanjian_id,
                    'triwulan_aktif' => $l->triwulan_aktif,
                    'tahun' => $l->tahun ?? null,
                    'periode' => $l->periode ?? null,
                    'uraian_kegiatan' => $l->uraian_kegiatan ?? 'Laporan Kinerja',
                ];
            })
            ->values();
        $setting = Setting::where('key', 'triwulan_aktif')->first();
        $triwulanAktif = $setting ? $setting->value : 1;
        $userPerjanjian = $wadirPerjanjianItems->firstWhere('user_id', $user->id);
        if ($userPerjanjian) {
            $userLaporan = \App\Models\Laporan::where('perjanjian_id', $userPerjanjian->id)->latest()->first();
            $userPerjanjianStatus = $normalizeStatus($userPerjanjian);
            if ($userPerjanjianStatus === 'disetujui' && $userLaporan && $userLaporan->hasValidationForTriwulan($triwulanAktif)) {
                $isActiveTrwValidated = true;
            }
        }

        return view('dashboard.wadir', compact('totalPerjanjian', 'perjanjianSent', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected'))
            ->with('chartData', $chartData)
            ->with('notifications', $notifications)
            ->with('totalLaporan', $totalLaporan)
            ->with('laporanTerkirimCount', $laporanTerkirimCount)
            ->with('laporanApprovedByPimpinan', $laporanApprovedByPimpinan)
            ->with('laporanValidatedCount', $laporanValidatedCount)
            ->with('laporanWaitingReviewCount', $laporanWaitingReviewCount)
            ->with('laporanRejectedCount', $laporanRejectedCount)
            ->with('isActiveTrwValidated', $isActiveTrwValidated)
            ->with('laporanItems', $laporanItems)
                ->with('perjanjianItems', $perjanjianItems)
            ->with('ownLaporanTriwulans', $ownLaporanTriwulans)
            ->with('validasiLaporanItems', $validasiLaporanItems)
            ->with('pdfPerjanjianId', $pdfPerjanjianId)
            ->with('pdfTriwulanAvailability', $pdfTriwulanAvailability);
    }

    private function hasNonZeroTriwulanRealisasi($raw): bool
    {
        if (empty($raw)) {
            return false;
        }

        $parsed = is_string($raw) ? json_decode($raw, true) : $raw;
        if (!is_array($parsed) || empty($parsed['rows']) || !is_array($parsed['rows'])) {
            return false;
        }

        foreach ($parsed['rows'] as $row) {
            $value = isset($row['realisasi']) ? floatval($row['realisasi']) : 0;
            if ($value > 0) {
                return true;
            }
        }

        return false;
    }

    private function extractKinerjaTargetsByTriwulan($tabelB): array
    {
        $totals = [0, 0, 0, 0];
        if (!is_array($tabelB) || empty($tabelB)) {
            return $totals;
        }

        for ($tw = 1; $tw <= 4; $tw++) {
            $key = 'tw' . $tw;
            if (!empty($tabelB[$key]) && is_array($tabelB[$key])) {
                foreach ($tabelB[$key] as $value) {
                    $totals[$tw - 1] += $this->normalizeNumericValue($value);
                }
            }
        }

        if (array_sum($totals) > 0) {
            return $totals;
        }

        if (!empty($tabelB[0]) && is_array($tabelB[0])) {
            foreach ($tabelB as $row) {
                for ($tw = 1; $tw <= 4; $tw++) {
                    $key = 'tw' . $tw;
                    if (array_key_exists($key, $row)) {
                        $totals[$tw - 1] += $this->normalizeNumericValue($row[$key]);
                    }
                }
            }
        }

        return $totals;
    }

    private function extractKinerjaLabels(array $tabelB): array
    {
        $indikator = $tabelB['indikator'] ?? [];
        $sasaran = $tabelB['sasaran'] ?? [];
        $count = max(count($indikator), count($sasaran));
        $labels = [];

        for ($index = 0; $index < $count; $index++) {
            $label = trim((string) ($indikator[$index] ?? ''));
            if ($label === '') {
                $label = trim((string) ($sasaran[$index] ?? ''));
            }
            $labels[] = $label !== '' ? $label : 'Indikator ' . ($index + 1);
        }

        return $labels;
    }

    private function extractKinerjaTargetsForTriwulan(array $tabelB, int $triwulan): array
    {
        $key = 'tw' . $triwulan;
        $targets = [];
        $rows = $tabelB[$key] ?? [];

        if (!is_array($rows)) {
            return $targets;
        }

        foreach ($rows as $value) {
            $targets[] = $this->normalizeNumericValue($value);
        }

        return $targets;
    }

    private function extractKinerjaRealisasiForTriwulan(?Laporan $laporan, int $triwulan, int $expectedCount): array
    {
        $realisasi = array_fill(0, $expectedCount, 0.0);
        if (!$laporan) {
            return $realisasi;
        }

        $raw = $laporan->{'realisasi_tb' . $triwulan} ?? null;
        $rowMap = $this->extractRealisasiRowMap($raw);

        foreach ($rowMap as $rowKey => $value) {
            if (preg_match('/^kinerja-(\d+)$/', $rowKey, $matches)) {
                $index = (int) $matches[1];
                if ($index >= 0 && $index < $expectedCount) {
                    $realisasi[$index] += $value;
                }
            }
        }

        return $realisasi;
    }

    private function extractKeuanganTargetsByTriwulan($tabelC): array
    {
        $totals = [0, 0, 0, 0];
        if (!is_array($tabelC) || empty($tabelC)) {
            return $totals;
        }

        $normalized = $this->normalizeTabelCPrograms($tabelC);
        if (!empty($normalized['programs']) && is_array($normalized['programs'])) {
            foreach ($normalized['programs'] as $program) {
                $this->accumulateKeuanganNodeTargets($program, $totals);
            }

            if (array_sum($totals) > 0) {
                return $totals;
            }
        }

        for ($tw = 1; $tw <= 4; $tw++) {
            $key = 'tw' . $tw;
            if (!empty($tabelC[$key]) && is_array($tabelC[$key])) {
                foreach ($tabelC[$key] as $value) {
                    $totals[$tw - 1] += $this->normalizeNumericValue($value);
                }
            }
        }

        return $totals;
    }

    private function extractKeuanganRealisasiByTriwulan(int $perjanjianId, array $tabelC): array
    {
        $totals = [0, 0, 0, 0];
        $leafRowIds = $this->collectKeuanganLeafRowIds($tabelC);
        if (empty($leafRowIds)) {
            return $totals;
        }

        $leafRowLookup = array_fill_keys($leafRowIds, true);

        for ($tw = 1; $tw <= 4; $tw++) {
            $laporan = $this->resolveLaporanForTriwulan($perjanjianId, $tw);

            if (!$laporan) {
                continue;
            }

            $raw = $laporan->{'realisasi_tb' . $tw} ?? null;
            $rowMap = $this->extractRealisasiRowMap($raw);

            foreach ($rowMap as $rowKey => $value) {
                if (isset($leafRowLookup[$rowKey])) {
                    $totals[$tw - 1] += $value;
                }
            }
        }

        return $totals;
    }

    private function collectKeuanganLeafRowIds(array $tabelC): array
    {
        $normalized = $this->normalizeTabelCPrograms($tabelC);
        $rowIds = [];

        foreach (($normalized['programs'] ?? []) as $programIndex => $program) {
            if (is_array($program)) {
                $this->collectKeuanganLeafRowIdsFromNode($program, $rowIds, null, (int) $programIndex + 1, null, null);
            }
        }

        return array_values(array_unique($rowIds));
    }

    private function collectKeuanganLeafRowIdsFromNode(
        array $node,
        array &$rowIds,
        ?string $parentNo = null,
        ?int $programIndex = null,
        ?int $kegiatanIndex = null,
        ?int $subIndex = null
    ): void
    {
        $rawNo = trim((string) ($node['no'] ?? ''));
        if ($rawNo !== '') {
            $resolvedNo = $rawNo;
        } elseif ($parentNo === null && $programIndex !== null) {
            $resolvedNo = 'p' . $programIndex;
        } elseif ($parentNo !== null && $kegiatanIndex !== null) {
            $resolvedNo = $parentNo . '.k' . $kegiatanIndex;
        } elseif ($parentNo !== null && $subIndex !== null) {
            $resolvedNo = $parentNo . '.s' . $subIndex;
        } else {
            $resolvedNo = $parentNo ?? '';
        }

        $hasKegiatan = !empty($node['kegiatan']) && is_array($node['kegiatan']);
        $hasSubKegiatan = !empty($node['subKegiatan']) && is_array($node['subKegiatan']);

        if ($hasKegiatan || $hasSubKegiatan) {
            foreach (($node['kegiatan'] ?? []) as $index => $kegiatan) {
                if (is_array($kegiatan)) {
                    $this->collectKeuanganLeafRowIdsFromNode($kegiatan, $rowIds, $resolvedNo, null, (int) $index + 1, null);
                }
            }

            foreach (($node['subKegiatan'] ?? []) as $index => $subKegiatan) {
                if (is_array($subKegiatan)) {
                    $this->collectKeuanganLeafRowIdsFromNode($subKegiatan, $rowIds, $resolvedNo, null, null, (int) $index + 1);
                }
            }

            return;
        }

        if ($resolvedNo !== '') {
            $rowIds[] = 'anggaran-' . strtolower($resolvedNo);
        }
    }

    private function extractRealisasiRowMap($raw): array
    {
        $totals = [];
        if (empty($raw)) {
            return $totals;
        }

        $parsed = is_string($raw) ? json_decode($raw, true) : $raw;
        if (!is_array($parsed)) {
            return $totals;
        }

        $rows = $parsed['rows'] ?? [];
        if (!is_array($rows) || empty($rows)) {
            return $totals;
        }

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $rowKey = strtolower(trim((string) ($row['row'] ?? '')));
            if ($rowKey === '') {
                continue;
            }

            $value = $this->normalizeNumericValue($row['realisasi'] ?? null);
            if (!isset($totals[$rowKey])) {
                $totals[$rowKey] = 0.0;
            }

            $totals[$rowKey] += $value;
        }

        return $totals;
    }

    private function buildWadirChartData(Perjanjian $perjanjian, ?Laporan $laporan): array
    {
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
        $targetKeuangan = $this->extractKeuanganTargetsByTriwulan($tabelC);
        $realisasiKeuangan = $this->extractKeuanganRealisasiByTriwulan((int) $perjanjian->id, $tabelC);

        $kinerjaPersen = [];
        $anggaranPersen = [];
        for ($tw = 1; $tw <= 4; $tw++) {
            $twLaporan = $this->resolveLaporanForTriwulan((int) $perjanjian->id, $tw);
                
            $avgKinerjaPct = 0.0;
            if ($twLaporan) {
                $raw = $twLaporan->{'realisasi_tb' . $tw} ?? null;
                $parsed = is_string($raw) ? json_decode($raw, true) : $raw;
                if (is_array($parsed) && isset($parsed['rows']) && is_array($parsed['rows'])) {
                    $kinerjaPcts = [];
                    foreach ($parsed['rows'] as $row) {
                        if (isset($row['row']) && str_starts_with($row['row'], 'kinerja-')) {
                            $targetVal = isset($row['target']) ? floatval($row['target']) : null;
                            $realVal = isset($row['realisasi']) ? floatval($row['realisasi']) : null;
                            $indicatorType = $this->normalizeIndicatorType($row['indicator_type'] ?? 'positif');
                            $performancePct = $this->calculatePerformancePercentage($targetVal, $realVal, $indicatorType);
                            if ($performancePct !== null) {
                                $kinerjaPcts[] = $performancePct;
                            } elseif (isset($row['performance_pct']) && $row['performance_pct'] !== null && $row['performance_pct'] !== '') {
                                // Fallback data lama jika target/realisasi tidak lengkap.
                                $kinerjaPcts[] = floatval($row['performance_pct']);
                            }
                        }
                    }
                    if (count($kinerjaPcts) > 0) {
                        $avgKinerjaPct = array_sum($kinerjaPcts) / count($kinerjaPcts);
                    }
                }
            }
            $kinerjaPersen[] = round($avgKinerjaPct, 2);
            $anggaranPersen[] = $this->calculatePercentage($realisasiKeuangan[$tw - 1] ?? 0, $targetKeuangan[$tw - 1] ?? 0);
        }

        return [
            'kinerja_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
            'kinerja_realisasi_kinerja_persen' => $kinerjaPersen,
            'kinerja_realisasi_anggaran_persen' => $anggaranPersen,
            'keuangan_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
            'keuangan_targets' => $targetKeuangan,
            'keuangan_realisasi' => $realisasiKeuangan,
        ];
    }

    private function buildAggregatedWadirChartData(Collection $perjanjians): array
    {
        $sumTargets = [0, 0, 0, 0];
        $sumRealisasi = [0, 0, 0, 0];
        $sumKinerjaPersen = [0, 0, 0, 0];
        $sumAnggaranPersen = [0, 0, 0, 0];
        $count = 0;

        foreach ($perjanjians as $perjanjian) {
            if (!$perjanjian instanceof Perjanjian) {
                continue;
            }

            $singleChart = $this->buildWadirChartData($perjanjian, null);
            $count++;

            for ($i = 0; $i < 4; $i++) {
                $sumTargets[$i] += (float) ($singleChart['keuangan_targets'][$i] ?? 0);
                $sumRealisasi[$i] += (float) ($singleChart['keuangan_realisasi'][$i] ?? 0);
                $sumKinerjaPersen[$i] += (float) ($singleChart['kinerja_realisasi_kinerja_persen'][$i] ?? 0);
                $sumAnggaranPersen[$i] += (float) ($singleChart['kinerja_realisasi_anggaran_persen'][$i] ?? 0);
            }
        }

        if ($count <= 0) {
            return [
                'kinerja_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
                'kinerja_realisasi_kinerja_persen' => [0, 0, 0, 0],
                'kinerja_realisasi_anggaran_persen' => [0, 0, 0, 0],
                'keuangan_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
                'keuangan_targets' => [0, 0, 0, 0],
                'keuangan_realisasi' => [0, 0, 0, 0],
            ];
        }

        $avgKinerjaPersen = array_map(function ($value) use ($count) {
            return round($value / $count, 2);
        }, $sumKinerjaPersen);

        $avgAnggaranPersen = array_map(function ($value) use ($count) {
            return round($value / $count, 2);
        }, $sumAnggaranPersen);

        return [
            'kinerja_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
            'kinerja_realisasi_kinerja_persen' => $avgKinerjaPersen,
            'kinerja_realisasi_anggaran_persen' => $avgAnggaranPersen,
            'keuangan_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
            'keuangan_targets' => $sumTargets,
            'keuangan_realisasi' => $sumRealisasi,
        ];
    }

    private function hasLaporanChartData(Laporan $laporan): bool
    {
        for ($tw = 1; $tw <= 4; $tw++) {
            if (!empty($laporan->{'realisasi_tb' . $tw})) {
                return true;
            }
        }

        return !empty($laporan->kesimpulan) || !empty($laporan->bab_capaian) || !empty($laporan->bab_pelaksanaan);
    }

    private function resolveLaporanForTriwulan(int $perjanjianId, int $tw): ?Laporan
    {
        $field = 'realisasi_tb' . $tw;

        // Prioritas 1: record yang memang ditandai triwulan aktif tersebut.
        $laporan = Laporan::where('perjanjian_id', $perjanjianId)
            ->where('triwulan_aktif', $tw)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if ($laporan && !empty($laporan->{$field})) {
            return $laporan;
        }

        // Prioritas 2: fallback ke record terbaru yang menyimpan field triwulan terkait.
        $fallback = Laporan::where('perjanjian_id', $perjanjianId)
            ->whereNotNull($field)
            ->where($field, '!=', '')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if ($fallback) {
            return $fallback;
        }

        // Prioritas 3: record terbaru apapun untuk menjaga backward compatibility.
        return Laporan::where('perjanjian_id', $perjanjianId)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();
    }

    private function extractKinerjaRealisasiByTriwulan(int $perjanjianId): array
    {
        $totals = [0, 0, 0, 0];

        for ($tw = 1; $tw <= 4; $tw++) {
            $laporan = Laporan::where('perjanjian_id', $perjanjianId)
                ->where('triwulan_aktif', $tw)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->first();

            if (!$laporan) {
                continue;
            }

            $rowMap = $this->extractRealisasiRowMap($laporan->{'realisasi_tb' . $tw} ?? null);
            foreach ($rowMap as $rowKey => $value) {
                if (str_starts_with($rowKey, 'kinerja-')) {
                    $totals[$tw - 1] += $value;
                }
            }
        }

        return $totals;
    }

    private function calculatePercentage($actual, $target): float
    {
        $actualValue = $this->normalizeNumericValue($actual);
        $targetValue = $this->normalizeNumericValue($target);

        if ($targetValue <= 0) {
            return 0.0;
        }

        return round(($actualValue / $targetValue) * 100, 2);
    }

    private function normalizeIndicatorType($rawType): string
    {
        return RuleBasedReasoningService::normalizeIndicatorType($rawType);
    }

    private function calculateCapaianPercentage($target, $realisasi): ?float
    {
        return RuleBasedReasoningService::calculateCapaianPercentage($target, $realisasi);
    }

    private function calculatePerformancePercentage($target, $realisasi, $indicatorType = 'positif'): ?float
    {
        return RuleBasedReasoningService::calculatePerformancePercentage($target, $realisasi, $indicatorType);
    }

    private function normalizeTabelCPrograms(array $tabelC): array
    {
        if (isset($tabelC['programs']) && is_array($tabelC['programs']) && count($tabelC['programs']) > 0) {
            return $tabelC;
        }

        if (isset($tabelC['program']) && is_array($tabelC['program'])) {
            $programs = [];
            $count = max(
                count($tabelC['program']),
                count($tabelC['anggaran'] ?? []),
                count($tabelC['keterangan'] ?? [])
            );

            for ($index = 0; $index < $count; $index++) {
                $programs[] = [
                    'name' => $tabelC['program'][$index] ?? '',
                    'amount' => $tabelC['anggaran'][$index] ?? 0,
                    'source' => $tabelC['keterangan'][$index] ?? '-',
                    'tw1' => $tabelC['tw1'][$index] ?? null,
                    'tw2' => $tabelC['tw2'][$index] ?? null,
                    'tw3' => $tabelC['tw3'][$index] ?? null,
                    'tw4' => $tabelC['tw4'][$index] ?? null,
                ];
            }

            $tabelC['programs'] = $programs;
            return $tabelC;
        }

        if (isset($tabelC[0]) && is_array($tabelC[0])) {
            $tabelC['programs'] = $tabelC;
        }

        return $tabelC;
    }

    private function accumulateKeuanganNodeTargets(array $node, array &$totals): void
    {
        $hasKegiatan = !empty($node['kegiatan']) && is_array($node['kegiatan']);
        $hasSubKegiatan = !empty($node['subKegiatan']) && is_array($node['subKegiatan']);

        if ($hasKegiatan || $hasSubKegiatan) {
            foreach ($node['kegiatan'] ?? [] as $kegiatan) {
                if (is_array($kegiatan)) {
                    $this->accumulateKeuanganNodeTargets($kegiatan, $totals);
                }
            }

            foreach ($node['subKegiatan'] ?? [] as $subKegiatan) {
                if (is_array($subKegiatan)) {
                    $this->accumulateKeuanganNodeTargets($subKegiatan, $totals);
                }
            }

            return;
        }

        for ($tw = 1; $tw <= 4; $tw++) {
            $key = 'tw' . $tw;
            if (array_key_exists($key, $node)) {
                $totals[$tw - 1] += $this->normalizeNumericValue($node[$key]);
            }
        }
    }

    private function extractRealisasiTotals($raw): array
    {
        $totals = ['kinerja' => 0.0, 'keuangan' => 0.0];
        if (empty($raw)) {
            return $totals;
        }

        $parsed = is_string($raw) ? json_decode($raw, true) : $raw;
        if (!is_array($parsed)) {
            return $totals;
        }

        $rows = $parsed['rows'] ?? [];
        if (!is_array($rows) || empty($rows)) {
            return $totals;
        }

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $value = $this->normalizeNumericValue($row['realisasi'] ?? null);
            $rowKey = strtolower((string) ($row['row'] ?? ''));

            if (str_starts_with($rowKey, 'anggaran-')) {
                $totals['keuangan'] += $value;
                continue;
            }

            $totals['kinerja'] += $value;
        }

        return $totals;
    }

    private function normalizeNumericValue($value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $normalized = trim($value);
            if ($normalized === '') {
                return 0.0;
            }

            $normalized = preg_replace('/[^0-9,\.\-]/', '', $normalized);
            if ($normalized === '' || $normalized === '-' || $normalized === null) {
                return 0.0;
            }

            if (str_contains($normalized, ',') && str_contains($normalized, '.')) {
                $normalized = str_replace('.', '', $normalized);
                $normalized = str_replace(',', '.', $normalized);
            } elseif (str_contains($normalized, ',') && !str_contains($normalized, '.')) {
                $normalized = str_replace(',', '.', $normalized);
            }

            return is_numeric($normalized) ? (float) $normalized : 0.0;
        }

        return 0.0;
    }
    
    /**
     * Dashboard untuk Kabag.Kabid (Kabag dan Kabid digabung)
     */
    public function kabagKabid()
    {
        return redirect()->route('dashboard.wadir');
    }
    
    /**
     * Dashboard untuk Katimker/Staf (dahulu Kasi)
     */
     public function katimkerStaf()
     {
        return redirect()->route('dashboard.wadir');
    }
}
