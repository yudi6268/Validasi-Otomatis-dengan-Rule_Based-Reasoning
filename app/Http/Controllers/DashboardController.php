<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Setting;
use App\Services\SupabaseService;
use Illuminate\Support\Carbon;

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
        
        // Redirect berdasarkan jabatan
        $jabatan = $user->jabatan;
        
        // Direktur
        if ($jabatan === 'Direktur') {
            return redirect()->route('dashboard.direktur');
        }
        
        // Wakil Direktur (Umum, Pelayanan, dan Perencanaan/Keuangan digabung)
        if ($jabatan === 'Wakil Direktur Umum dan Keuangan' || $jabatan === 'Wakil Direktur Pelayanan' || $jabatan === 'Wakil Direktur Perencanaan dan Keuangan') {
            return redirect()->route('dashboard.wadir');
        }
        
        // Kepala Bagian dan Kepala Bidang (Kabag.Kabid digabung)
        if (strpos($jabatan, 'Kabag') !== false || strpos($jabatan, 'Kepala Bagian') !== false ||
            strpos($jabatan, 'Kabid') !== false || strpos($jabatan, 'Kepala Bidang') !== false) {
            return redirect()->route('dashboard.kabag.kabid');
        }
        
        // Katimker/Staf (dahulu Kasi)
        if (strpos($jabatan, 'Kasi') !== false || strpos($jabatan, 'Kepala Seksi') !== false) {
            return redirect()->route('dashboard.katimker.staf');
        }
        
        // Default ke home untuk staff dan lainnya
        return redirect()->route('home');
    }
    
    /**
     * Halaman home untuk staff
     */
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function home(Request $request)
    {
        $user = Auth::user();
        $activeSection = $request->query('section', 'dashboard');
        $showNotificationModal = $request->session()->pull('show_notification_modal', false);

        $homeNotification = null;
        $shouldShowHomeNotification = false;

        if ($showNotificationModal && $activeSection === 'dashboard') {
            $homeNotification = Notification::where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhereNull('user_id');
                })
                ->where('is_read', false)
                ->latest('created_at')
                ->first();

            $shouldShowHomeNotification = $homeNotification !== null;
        }

        $chartPerjanjian = Perjanjian::where('user_id', $user->id)
            ->latest('updated_at')
            ->first();
        $userChartData = $this->buildUserDashboardChartData($chartPerjanjian);

        // Try to fetch perjanjian data from Supabase first. If it fails, fallback to local DB.
        try {
            $filters = [
                'user_id' => 'eq.' . $user->id,
                'select' => '*',
                'order' => 'created_at.desc',
                'limit' => 100
            ];
            $res = $this->supabase->select('perjanjians', $filters);

            if (!empty($res['success']) && !empty($res['data'])) {
                $items = collect($res['data'])->map(function($row) {
                    // Normalize array to object with expected fields
                    $obj = (object) $row;
                    if (isset($obj->created_at)) {
                        try { $obj->created_at = Carbon::parse($obj->created_at); } catch (\Exception $e) { $obj->created_at = null; }
                    }
                    return $obj;
                });

                $totalPerjanjian = $items->count();
                $perjanjianApproved = $items->filter(function($i){
                    return !empty($i->pihak2_signature) && (empty($i->rejected) || $i->rejected == false || $i->rejected == 0);
                })->count();
                $perjanjianWaiting = $items->filter(function($i){
                    return empty($i->pihak2_signature) && (empty($i->rejected) || $i->rejected == false || $i->rejected == 0);
                })->count();
                $perjanjianRejected = $items->filter(function($i){
                    return !empty($i->rejected) && ($i->rejected == true || $i->rejected == 1 || $i->rejected === '1');
                })->count();

                $perjanjians = $items->take(5);

                $laporans = Laporan::whereHas('perjanjian', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->latest()->get();

                $laporanTotal = $laporans->count();
                $laporanValidated = $laporans->filter(function($laporan) {
                    return !empty($laporan->kesimpulan);
                })->count();
                $laporanWaiting = $laporans->filter(function($laporan) {
                    $hasRealisasi = false;
                    for ($tw = 1; $tw <= 4; $tw++) {
                        if (!empty($laporan->{'realisasi_tb' . $tw})) {
                            $hasRealisasi = true;
                            break;
                        }
                    }
                    return $hasRealisasi && empty($laporan->kesimpulan);
                })->count();
                $laporanRejected = $laporans->filter(function($laporan) {
                    return !empty($laporan->tanggapan_pimpinan) && empty($laporan->kesimpulan);
                })->count();
                $laporansLatest = $laporans->take(5);

                return view('home', compact('activeSection', 'totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected', 'perjanjians', 'laporanTotal', 'laporanValidated', 'laporanWaiting', 'laporanRejected', 'laporansLatest', 'userChartData', 'homeNotification', 'shouldShowHomeNotification'));
            }

            // If supabase select returned no data or failed, fallthrough to DB
        } catch (\Exception $e) {
            // log and continue to fallback
            Log::warning('Supabase fetch failed in DashboardController@home: ' . $e->getMessage());
        }

        // Fallback to local DB queries to avoid zeroing data when supabase is unreachable
        $totalPerjanjian = Perjanjian::where('user_id', $user->id)->count();
        $perjanjianApproved = Perjanjian::where('user_id', $user->id)
            ->whereNotNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')
                  ->orWhere('rejected', false)
                  ->orWhere('rejected', 0)
                  ->orWhere('rejected', '0');
            })
            ->count();
        $perjanjianWaiting = Perjanjian::where('user_id', $user->id)
            ->whereNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')
                  ->orWhere('rejected', false)
                  ->orWhere('rejected', 0)
                  ->orWhere('rejected', '0');
            })
            ->count();
        $perjanjianRejected = Perjanjian::where('user_id', $user->id)
            ->where(function($q){
                $q->where('rejected', true)
                  ->orWhere('rejected', 1)
                  ->orWhere('rejected', '1');
            })
            ->count();

        $perjanjians = Perjanjian::where('user_id', $user->id)->latest()->take(5)->get();

        $laporans = Laporan::whereHas('perjanjian', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->get();

        $laporanTotal = $laporans->count();
        $laporanValidated = $laporans->filter(function($laporan) {
            return !empty($laporan->kesimpulan);
        })->count();
        $laporanWaiting = $laporans->filter(function($laporan) {
            $hasRealisasi = false;
            for ($tw = 1; $tw <= 4; $tw++) {
                if (!empty($laporan->{'realisasi_tb' . $tw})) {
                    $hasRealisasi = true;
                    break;
                }
            }
            return $hasRealisasi && empty($laporan->kesimpulan);
        })->count();
        $laporanRejected = $laporans->filter(function($laporan) {
            return !empty($laporan->tanggapan_pimpinan) && empty($laporan->kesimpulan);
        })->count();
        $laporansLatest = $laporans->take(5);

        return view('home', compact('activeSection', 'totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected', 'perjanjians', 'laporanTotal', 'laporanValidated', 'laporanWaiting', 'laporanRejected', 'laporansLatest', 'userChartData', 'homeNotification', 'shouldShowHomeNotification'));
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

        // Statistik perjanjian panel Wadir: samakan classifier status dengan halaman perjanjian.
        $wadirPerjanjianItems = Perjanjian::where(function($q) use ($user) {
            $q->where('pihak2_name', $user->nama)
                ->orWhere('pihak2_jabatan', $user->jabatan)
                ->orWhere('pihak2_nip', $user->nip)
                ->orWhere('user_id', $user->id);
        })->get();

        $normalizeStatus = function ($item) {
            $status = strtolower((string) ($item->status ?? ''));

            // Utamakan indikator faktual agar status stale di kolom `status` tidak menyesatkan panel.
            if (!empty($item->rejected) && (string) $item->rejected !== '0') {
                return 'ditolak';
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

        // Data perjanjian disetujui untuk turunan data laporan/chart Wadir.
        // Gunakan classifier status yang sama agar otomatis sinkron saat status berubah.
        $perjanjians = $wadirPerjanjianItems->filter(function ($item) use ($normalizeStatus) {
            return $normalizeStatus($item) === 'disetujui';
        })->values();

        $chartPerjanjian = $perjanjians->sortByDesc('updated_at')->first();
        $chartLaporan = null;
        $chartData = [
            'kinerja_labels' => [],
            'kinerja_targets' => [],
            'kinerja_realisasi' => [],
            'keuangan_labels' => ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'],
            'keuangan_targets' => [0, 0, 0, 0],
            'keuangan_realisasi' => [0, 0, 0, 0],
        ];

        if ($chartPerjanjian) {
            $chartLaporan = Laporan::where('perjanjian_id', $chartPerjanjian->id)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->first();

            $chartData = $this->buildWadirChartData($chartPerjanjian, $chartLaporan);
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

        // Compute modal counts for dashboard using collection operations to avoid SQL on missing columns
        $perjanjianIds = $perjanjians->pluck('id')->toArray();

        // Fetch related laporans into collection (single query)
        $laporansForWaiting = \App\Models\Laporan::whereIn('perjanjian_id', $perjanjianIds)->get();

        // Total laporan kinerja dari semua perjanjian yang terkait
        $totalLaporan = $laporansForWaiting->count();

        // Laporan yang ditandatangani/approved by pimpinan (from fetched collection)
        $laporanApprovedByPimpinan = $laporansForWaiting->filter(function($l){
            return !empty($l->pihak2_signature);
        })->count();

        // Laporan yang divalidasi (heuristic: kesimpulan tidak kosong) — computed in PHP to avoid missing-column SQL
        $laporanValidatedCount = $laporansForWaiting->filter(function($l){
            return !empty($l->kesimpulan);
        })->count();

        // Laporan menunggu reviu: memiliki minimal 1 realisasi triwulan, tetapi belum ada kesimpulan/tanggapan
        $laporanWaitingReviewCount = 0;
        foreach ($laporansForWaiting as $lap) {
            $hasRealisasi = false;
            for ($tw = 1; $tw <= 4; $tw++) {
                $field = 'realisasi_tb' . $tw;
                if (!empty($lap->{$field})) { $hasRealisasi = true; break; }
            }
            $hasKesimpulan = !empty($lap->kesimpulan);
            $hasTanggapan = !empty($lap->tanggapan_pimpinan);

            if ($hasRealisasi && !$hasKesimpulan && !$hasTanggapan) {
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
        $laporanItems = $laporansForWaiting->map(function($l) {
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
        $setting = Setting::where('key', 'triwulan_aktif')->first();
        $triwulanAktif = $setting ? $setting->value : 1;
        $userPerjanjian = $wadirPerjanjianItems->firstWhere('user_id', $user->id);
        if ($userPerjanjian) {
            $userLaporan = \App\Models\Laporan::where('perjanjian_id', $userPerjanjian->id)->latest()->first();
            if ($userLaporan && $userLaporan->hasValidationForTriwulan($triwulanAktif)) {
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
            ->with('ownLaporanTriwulans', $ownLaporanTriwulans)
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
            $laporan = Laporan::where('perjanjian_id', $perjanjianId)
                ->where('triwulan_aktif', $tw)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->first();

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

        foreach ($normalized['programs'] ?? [] as $program) {
            if (is_array($program)) {
                $this->collectKeuanganLeafRowIdsFromNode($program, $rowIds);
            }
        }

        return array_values(array_unique($rowIds));
    }

    private function collectKeuanganLeafRowIdsFromNode(array $node, array &$rowIds): void
    {
        $hasKegiatan = !empty($node['kegiatan']) && is_array($node['kegiatan']);
        $hasSubKegiatan = !empty($node['subKegiatan']) && is_array($node['subKegiatan']);

        if ($hasKegiatan || $hasSubKegiatan) {
            foreach ($node['kegiatan'] ?? [] as $kegiatan) {
                if (is_array($kegiatan)) {
                    $this->collectKeuanganLeafRowIdsFromNode($kegiatan, $rowIds);
                }
            }

            foreach ($node['subKegiatan'] ?? [] as $subKegiatan) {
                if (is_array($subKegiatan)) {
                    $this->collectKeuanganLeafRowIdsFromNode($subKegiatan, $rowIds);
                }
            }

            return;
        }

        $no = trim((string) ($node['no'] ?? ''));
        if ($no !== '') {
            $rowIds[] = 'anggaran-' . $no;
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
        $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
        $kinerjaTargetByTriwulan = $this->extractKinerjaTargetsByTriwulan($tabelB);
        $kinerjaRealisasiByTriwulan = $this->extractKinerjaRealisasiByTriwulan((int) $perjanjian->id);
        $targetKeuangan = $this->extractKeuanganTargetsByTriwulan($tabelC);
        $realisasiKeuangan = $this->extractKeuanganRealisasiByTriwulan((int) $perjanjian->id, $tabelC);

        $kinerjaPersen = [];
        $anggaranPersen = [];
        for ($index = 0; $index < 4; $index++) {
            $kinerjaPersen[] = $this->calculatePercentage($kinerjaRealisasiByTriwulan[$index] ?? 0, $kinerjaTargetByTriwulan[$index] ?? 0);
            $anggaranPersen[] = $this->calculatePercentage($realisasiKeuangan[$index] ?? 0, $targetKeuangan[$index] ?? 0);
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
        $user = Auth::user();
        
        // Statistik perjanjian yang dibuat oleh user
        $totalPerjanjian = Perjanjian::where('user_id', $user->id)->count();
        $perjanjianApproved = Perjanjian::where('user_id', $user->id)
            ->whereNotNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')
                  ->orWhere('rejected', false)
                  ->orWhere('rejected', 0)
                  ->orWhere('rejected', '0');
            })
            ->count();
        $perjanjianWaiting = Perjanjian::where('user_id', $user->id)
            ->whereNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')
                  ->orWhere('rejected', false)
                  ->orWhere('rejected', 0)
                  ->orWhere('rejected', '0');
            })
            ->count();
        $perjanjianRejected = Perjanjian::where('user_id', $user->id)
            ->where(function($q){
                $q->where('rejected', true)
                  ->orWhere('rejected', 1)
                  ->orWhere('rejected', '1');
            })
            ->count();
        
        return view('dashboard.kabag-kabid', compact('totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected'));
    }
    
    /**
     * Dashboard untuk Katimker/Staf (dahulu Kasi)
     */
    public function katimkerStaf()
    {
        $user = Auth::user();
        
        // Statistik perjanjian yang dibuat oleh user
        $totalPerjanjian = Perjanjian::where('user_id', $user->id)->count();
        $perjanjianApproved = Perjanjian::where('user_id', $user->id)
            ->whereNotNull('pihak2_signature')
            ->where('rejected', false)
            ->count();
        $perjanjianWaiting = Perjanjian::where('user_id', $user->id)
            ->whereNull('pihak2_signature')
            ->where('rejected', false)
            ->count();
        $perjanjianRejected = Perjanjian::where('user_id', $user->id)
            ->where('rejected', true)
            ->count();
        
        return view('dashboard.katimker-staf', compact('totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected'));
    }
}
