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
use App\Services\RuleBasedReasoningService;
use App\Services\SupabaseService;

class DirekturDashboardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    protected function applyDirekturPihakKeduaScope($query, $user, bool $excludeBagianBidangMaker = true)
    {
        $normalizedNama = trim((string) ($user->nama ?? ''));
        $normalizedJabatan = trim((string) ($user->jabatan ?? ''));
        $normalizedNipDigits = preg_replace('/\D+/', '', (string) ($user->nip ?? ''));

        $query->where(function ($q) use ($normalizedNama, $normalizedJabatan, $normalizedNipDigits) {
            if ($normalizedNipDigits !== '') {
                if ($normalizedJabatan !== '') {
                    $q->whereRaw("regexp_replace(COALESCE(pihak2_nip, ''), '[^0-9]', '', 'g') = ?", [$normalizedNipDigits])
                      ->whereRaw("LOWER(TRIM(COALESCE(pihak2_jabatan, ''))) = LOWER(TRIM(?))", [$normalizedJabatan]);
                } else {
                    $q->whereRaw("regexp_replace(COALESCE(pihak2_nip, ''), '[^0-9]', '', 'g') = ?", [$normalizedNipDigits]);
                }
            }

            if ($normalizedNama !== '' && $normalizedJabatan !== '') {
                $q->orWhere(function ($qq) use ($normalizedNama, $normalizedJabatan) {
                          $qq->whereRaw('LOWER(TRIM(pihak2_name)) = LOWER(TRIM(?))', [$normalizedNama])
                              ->whereRaw("LOWER(TRIM(COALESCE(pihak2_jabatan, ''))) = LOWER(TRIM(?))", [$normalizedJabatan]);
                });
            } elseif ($normalizedNama !== '') {
                $q->orWhereRaw('LOWER(TRIM(pihak2_name)) = LOWER(TRIM(?))', [$normalizedNama]);
            }
        });

        if ($excludeBagianBidangMaker) {
            // Perjanjian yang dibuat unit Bagian/Bidang wajib diproses di akun Wakil Direktur, bukan Direktur.
            $query->whereDoesntHave('user', function ($userQuery) {
                $userQuery->where(function ($j) {
                    $j->whereRaw("LOWER(COALESCE(jabatan, '')) LIKE ?", ['%bagian%'])
                      ->orWhereRaw("LOWER(COALESCE(jabatan, '')) LIKE ?", ['%bidang%'])
                      ->orWhereRaw("LOWER(COALESCE(jabatan, '')) LIKE ?", ['%kabag%'])
                      ->orWhereRaw("LOWER(COALESCE(jabatan, '')) LIKE ?", ['%kabid%'])
                      ->orWhereRaw("LOWER(COALESCE(jabatan, '')) LIKE ?", ['%kepala bagian%'])
                      ->orWhereRaw("LOWER(COALESCE(jabatan, '')) LIKE ?", ['%kepala bidang%']);
                });
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // CACHE UNTUK DIREKTUR (1 Menit) - Cukup untuk mempercepat tanpa terlalu stale
        $cacheKey = "direktur_dashboard_data_{$user->id}";
        
        $data = \Illuminate\Support\Facades\Cache::remember($cacheKey, 60, function () use ($user) {
            // All perjanjian where direktur is pihak kedua
            $allPerjanjians = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)
                ->orderBy('created_at', 'desc')
                ->get();

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
                } elseif ((!empty($l->rejected) && (string) $l->rejected !== '0') || (!empty($l->tanggapan_pimpinan) && empty($l->kesimpulan))) {
                    $status = 'ditolak';
                } elseif ($hasRealisasi && !empty($l->kesimpulan) && empty($l->pihak2_signature)) {
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

            $chartData = $this->buildDirectorChartData($allPerjanjians, $allLaporans);
            
            return compact(
                'perjanjianItems', 'perjanjianCounts',
                'laporanItems', 'laporanCounts',
                'chartData'
            );
        });

        return view('dashboard.direktur', $data);
    }

    private function buildDirectorChartData($allPerjanjians, $allLaporans): array
    {
        $currentYear = date('Y');
        $chartPerjanjians = [];
        
        foreach ($allPerjanjians as $p) {
            $periode = $p->periode ?? optional($p->created_at)->format('Y');
            if ($periode != $currentYear) continue;
            
            $key = $p->pihak1_name ?? $p->pihak1_nip;
            if (!isset($chartPerjanjians[$key]) || $p->id > $chartPerjanjians[$key]->id) {
                $chartPerjanjians[$key] = $p;
            }
        }

        $keuanganTargets = [0, 0, 0, 0];
        $leafRowIds = [];
        $perjanjianBData = [];

        foreach ($chartPerjanjians as $p) {
            $tabelB = is_array($p->tabelB) ? $p->tabelB : json_decode($p->tabelB ?? '[]', true);
            $tabelC = is_array($p->tabelC) ? $p->tabelC : json_decode($p->tabelC ?? '[]', true);

            $perjanjianBData[$p->id] = $tabelB;
            $keuanganTargets = $this->addSeries($keuanganTargets, $this->extractKeuanganTargetsByTriwulan($tabelC));
            $leafRowIds = array_merge($leafRowIds, $this->collectKeuanganLeafRowIds($tabelC));
        }

        $leafRowLookup = array_fill_keys(array_unique($leafRowIds), true);
        
        $latestLaporans = [];
        $validPerjanjianIds = array_keys($perjanjianBData);

        $keuanganRealisasi = [0, 0, 0, 0];
        $kinerjaPercent = [0.0, 0.0, 0.0, 0.0];
        $hasLaporanForTw = [false, false, false, false];

        for ($tw = 1; $tw <= 4; $tw++) {
            $sumPct = 0;
            $countPct = 0;

            foreach ($chartPerjanjians as $p) {
                $pId = $p->id;
                $tabelB = $perjanjianBData[$pId];
                $sasaranCount = isset($tabelB['sasaran']) && is_array($tabelB['sasaran']) ? count($tabelB['sasaran']) : 0;
                
                if (!isset($latestLaporans[$pId][$tw])) {
                    $latestLaporans[$pId][$tw] = $this->resolveLaporanForTriwulan((int) $pId, $tw);
                }
                $laporan = $latestLaporans[$pId][$tw] ?? null;
                
                if ($sasaranCount > 0) {
                    if ($laporan) {
                        $hasLaporanForTw[$tw - 1] = true;
                        $capaianMap = $this->extractCapaianKinerjaMap($laporan->{'realisasi_tb' . $tw} ?? null);
                        foreach ($capaianMap as $pct) {
                            $sumPct += $pct;
                        }
                    }
                    $countPct += $sasaranCount;
                }

                if ($laporan) {
                    $hasLaporanForTw[$tw - 1] = true;
                    $rowMap = $this->extractRealisasiRowMap($laporan->{'realisasi_tb' . $tw} ?? null);
                    foreach ($rowMap as $rowKey => $value) {
                        if (isset($leafRowLookup[$rowKey])) {
                            $keuanganRealisasi[$tw - 1] += $value;
                        }
                    }
                }
            }
            
            if ($hasLaporanForTw[$tw - 1]) {
                $kinerjaPercent[$tw - 1] = $countPct > 0 ? round($sumPct / $countPct, 2) : 0.0;
            } else {
                $kinerjaPercent[$tw - 1] = null;
                $keuanganRealisasi[$tw - 1] = null;
            }
        }

        $keuanganPercent = [];
        for ($idx = 0; $idx < 4; $idx++) {
            if ($keuanganRealisasi[$idx] === null) {
                $keuanganPercent[] = null;
            } else {
                $keuanganPercent[] = $this->calculatePercentage($keuanganRealisasi[$idx], $keuanganTargets[$idx] ?? 0);
            }
        }

        return [
            'kinerja_labels' => ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'],
            'kinerja_percent' => $kinerjaPercent,
            'keuangan_labels' => ['Triwulan 1', 'Triwulan 2', 'Triwulan 3', 'Triwulan 4'],
            'keuangan_targets' => $keuanganTargets,
            'keuangan_realisasi' => $keuanganRealisasi,
            'keuangan_percent' => $keuanganPercent,
        ];
    }

    private function extractCapaianKinerjaMap($raw): array
    {
        $capaian = [];
        if (empty($raw)) return $capaian;
        $parsed = is_string($raw) ? json_decode($raw, true) : $raw;
        if (!is_array($parsed) || empty($parsed['rows'])) return $capaian;

        foreach ($parsed['rows'] as $row) {
            $rowKey = strtolower(trim((string) ($row['row'] ?? '')));
            if ($rowKey !== '' && str_starts_with($rowKey, 'kinerja-')) {
                $target = $row['target'] ?? null;
                $realisasi = $row['realisasi'] ?? null;
                $indicatorType = $this->normalizeIndicatorType($row['indicator_type'] ?? 'positif');
                $performancePct = $this->calculatePerformancePercentage($target, $realisasi, $indicatorType);
                if ($performancePct !== null) {
                    $capaian[$rowKey] = $performancePct;
                } elseif (isset($row['performance_pct']) && is_numeric($row['performance_pct'])) {
                    // Fallback data lama jika target/realisasi tidak lengkap.
                    $capaian[$rowKey] = (float) $row['performance_pct'];
                } elseif (isset($row['pct']) && is_numeric($row['pct'])) {
                    // Fallback untuk data legacy.
                    $capaian[$rowKey] = (float) $row['pct'];
                }
            }
        }
        return $capaian;
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

    private function calculatePercentage($actual, $target): float
    {
        $actualValue = $this->normalizeNumericValue($actual);
        $targetValue = $this->normalizeNumericValue($target);

        if ($targetValue <= 0) {
            return 0.0;
        }

        return round(($actualValue / $targetValue) * 100, 2);
    }

    private function addSeries(array $base, array $addition): array
    {
        $result = $base;
        foreach ($addition as $idx => $value) {
            $result[$idx] = ($result[$idx] ?? 0) + $value;
        }
        return $result;
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
                if (!is_array($row)) {
                    continue;
                }
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

    private function resolveLaporanForTriwulan(int $perjanjianId, int $tw): ?Laporan
    {
        $field = 'realisasi_tb' . $tw;

        $laporan = Laporan::where('perjanjian_id', $perjanjianId)
            ->where('triwulan_aktif', $tw)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if ($laporan && !empty($laporan->{$field})) {
            return $laporan;
        }

        $fallback = Laporan::where('perjanjian_id', $perjanjianId)
            ->whereNotNull($field)
            ->where($field, '!=', '')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        if ($fallback) {
            return $fallback;
        }

        return Laporan::where('perjanjian_id', $perjanjianId)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();
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

    private function normalizeNumericValue($value): float
    {
        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        if ($value === null || $value === '') {
            return 0.0;
        }

        $s = trim((string)$value);
        
        $isNegative = false;
        if (str_starts_with($s, '-')) {
            $isNegative = true;
            $s = trim(substr($s, 1));
        }

        if (preg_match('/^(?<int>[0-9]{1,3}(?:[\.\s][0-9]{3})*|[0-9]+)(?:[\.,](?<dec>[0-9]+))?$/', $s, $m)) {
            $int = (float)str_replace(['.', ' '], '', $m['int']);
            $dec = !empty($m['dec']) ? (float)('0.' . $m['dec']) : 0.0;
            $val = $int + $dec;
            return $isNegative ? -$val : $val;
        }

        return 0.0;
    }

    public function perjanjianList(Request $request)
    {
        $user = Auth::user();

        $allPerjanjians = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)
            ->orderBy('created_at', 'desc')
            ->get();

        $allItems = $allPerjanjians->map(function ($p) {
            $status = $this->isRejectedValue($p->rejected) ? 'ditolak'
                : (!empty($p->pihak2_signature) ? 'disetujui' : 'menunggu');
            return [
                'id'               => $p->id,
                'periode'          => $p->periode ?? optional($p->created_at)->format('Y') ?? '-',
                'tanggal'          => optional($p->created_at)->locale('id')->translatedFormat('d F Y') ?? '-',
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

        $allPerjanjians = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)->pluck('id');

        $allLaporans = Laporan::whereIn('perjanjian_id', $allPerjanjians)
            ->with('perjanjian')
            ->orderBy('created_at', 'desc')
            ->get();

        $allItems = $allLaporans->map(function ($l) {
            $status = 'terkirim';
            if (!empty($l->pihak2_signature)) {
                $status = 'disetujui';
            } elseif ((!empty($l->rejected) && (string) $l->rejected !== '0') || (!empty($l->tanggapan_pimpinan) && empty($l->kesimpulan))) {
                $status = 'ditolak';
            } else {
                $hasRealisasi = false;
                for ($tw = 1; $tw <= 4; $tw++) {
                    if (!empty($l->{'realisasi_tb' . $tw})) { $hasRealisasi = true; break; }
                }
                if ($hasRealisasi && !empty($l->kesimpulan) && empty($l->pihak2_signature)) {
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
        $canAccess = $user
            ? $this->applyDirekturPihakKeduaScope(Perjanjian::query()->where('id', $perjanjian->id), $user)->exists()
            : false;
        if (!$canAccess) {
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
                $query = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)
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
                $allData = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)
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
                $notifications = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)
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
                $perjanjianIds = $this->applyDirekturPihakKeduaScope(Perjanjian::query(), $user)->pluck('id');
        
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
                        $perjanjian = $this->applyDirekturPihakKeduaScope(Perjanjian::query()->where('id', $id), $user, false)->firstOrFail();
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
            $perjanjian->catatan_penolakan = null;
            $perjanjian->status = 'disetujui';
            $perjanjian->save();

            // Sync to Supabase
            try {
                $payload = [
                    'pihak2_name' => $perjanjian->pihak2_name,
                    'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
                    'pihak2_nip' => $perjanjian->pihak2_nip,
                    'location' => $perjanjian->location,
                    'agreement_date' => $perjanjian->agreement_date,
                    'jabatan' => $perjanjian->jabatan,
                    'jabatan_pelaksana' => $perjanjian->jabatan_pelaksana,
                    'tugas_pelaksana' => $perjanjian->tugas_pelaksana,
                    'fungsi_pelaksana' => $perjanjian->fungsi_pelaksana,
                    'pihak1_ttd' => $perjanjian->pihak1_ttd,
                    'status' => $perjanjian->status,
                    'catatan_penolakan' => $perjanjian->catatan_penolakan,
                    'rejected' => $perjanjian->rejected,
                    'rejection_reason' => $perjanjian->rejection_reason,
                    'pihak2_signature' => $perjanjian->pihak2_signature,
                    'pihak2_ttd_path' => $perjanjian->pihak2_ttd_path,
                    'tabelA' => $perjanjian->tabelA,
                    'tabelB' => $perjanjian->tabelB,
                    'tabelC' => $perjanjian->tabelC,
                ];

                $filters = ['local_id' => 'eq.' . $perjanjian->id];
                $res = $this->supabase->update('perjanjians', $filters, $payload);
                if (empty($res['success']) && !empty($perjanjian->nomor_perjanjian)) {
                    $filters = ['nomor_perjanjian' => 'eq.' . $perjanjian->nomor_perjanjian];
                    $res = $this->supabase->update('perjanjians', $filters, $payload);
                }
                if (empty($res['success'])) {
                    Log::warning('Supabase update failed for perjanjian #' . $perjanjian->id . ' in approvePerjanjian: ' . ($res['error'] ?? 'unknown'));
                } else {
                    Log::info('Supabase update succeeded for perjanjian #' . $perjanjian->id . ' in approvePerjanjian');
                }
            } catch (\Exception $e) {
                Log::warning('Supabase update exception for perjanjian #' . $perjanjian->id . ' in approvePerjanjian: ' . $e->getMessage());
            }
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
                        $perjanjian = $this->applyDirekturPihakKeduaScope(Perjanjian::query()->where('id', $id), $user, false)->firstOrFail();
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
            $perjanjian->catatan_penolakan = $request->rejection_reason;
            $perjanjian->pihak2_signature = null;
            $perjanjian->pihak2_ttd_path = null;
            $perjanjian->status = 'ditolak';
            $perjanjian->save();

            // Sync to Supabase
            try {
                $payload = [
                    'pihak2_name' => $perjanjian->pihak2_name,
                    'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
                    'pihak2_nip' => $perjanjian->pihak2_nip,
                    'location' => $perjanjian->location,
                    'agreement_date' => $perjanjian->agreement_date,
                    'jabatan' => $perjanjian->jabatan,
                    'jabatan_pelaksana' => $perjanjian->jabatan_pelaksana,
                    'tugas_pelaksana' => $perjanjian->tugas_pelaksana,
                    'fungsi_pelaksana' => $perjanjian->fungsi_pelaksana,
                    'pihak1_ttd' => $perjanjian->pihak1_ttd,
                    'status' => $perjanjian->status,
                    'catatan_penolakan' => $perjanjian->catatan_penolakan,
                    'rejected' => $perjanjian->rejected,
                    'rejection_reason' => $perjanjian->rejection_reason,
                    'pihak2_signature' => null,
                    'pihak2_ttd_path' => null,
                    'tabelA' => $perjanjian->tabelA,
                    'tabelB' => $perjanjian->tabelB,
                    'tabelC' => $perjanjian->tabelC,
                ];

                $filters = ['local_id' => 'eq.' . $perjanjian->id];
                $res = $this->supabase->update('perjanjians', $filters, $payload);
                if (empty($res['success']) && !empty($perjanjian->nomor_perjanjian)) {
                    $filters = ['nomor_perjanjian' => 'eq.' . $perjanjian->nomor_perjanjian];
                    $res = $this->supabase->update('perjanjians', $filters, $payload);
                }
                if (empty($res['success'])) {
                    Log::warning('Supabase update failed for perjanjian #' . $perjanjian->id . ' in rejectPerjanjian: ' . ($res['error'] ?? 'unknown'));
                } else {
                    Log::info('Supabase update succeeded for perjanjian #' . $perjanjian->id . ' in rejectPerjanjian');
                }
            } catch (\Exception $e) {
                Log::warning('Supabase update exception for perjanjian #' . $perjanjian->id . ' in rejectPerjanjian: ' . $e->getMessage());
            }
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
            $laporan->rejected          = false;
            $laporan->rejection_reason  = null;
            $laporan->save();

            // Sync to Supabase
            $this->syncLaporanToSupabase($laporan, (int) ($laporan->triwulan_aktif ?? 1));

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
            $laporan->validation_results = null;
            $laporan->validation_timestamp = null;
            $laporan->rejected           = true;
            $laporan->rejection_reason   = 'Ditolak oleh pimpinan';
            $laporan->save();

            // Sync to Supabase
            $this->syncLaporanToSupabase($laporan, (int) ($laporan->triwulan_aktif ?? 1));

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

    private function syncLaporanToSupabase(Laporan $laporan, int $triwulan): void
    {
        try {
            $payload = [
                'local_id' => $laporan->id,
                'perjanjian_id' => $laporan->perjanjian_id,
                'periode' => $laporan->periode,
                'tahun' => $laporan->tahun,
                'uraian_kegiatan' => $laporan->uraian_kegiatan,
                'sasaran' => $laporan->sasaran,
                'bobot' => $laporan->bobot,
                'sumber_data' => $laporan->sumber_data,
                'pihak1_name' => $laporan->pihak1_name,
                'pihak2_name' => $laporan->pihak2_name,
                'tabelA' => $laporan->tabelA,
                'tabelB' => $laporan->tabelB,
                'tabelC' => $laporan->tabelC,
                'bab_capaian' => $laporan->bab_capaian,
                'bab_rencana' => $laporan->bab_rencana,
                'rencana_tindak_lanjut' => $laporan->rencana_tindak_lanjut,
                'kesimpulan' => $laporan->kesimpulan,
                'triwulan_aktif' => $triwulan,
                'realisasi_tb1' => $laporan->realisasi_tb1,
                'realisasi_tb2' => $laporan->realisasi_tb2,
                'realisasi_tb3' => $laporan->realisasi_tb3,
                'realisasi_tb4' => $laporan->realisasi_tb4,
                'pihak2_signature' => $laporan->pihak2_signature,
                'rejected' => $laporan->rejected,
                'rejection_reason' => $laporan->rejection_reason,
                'tanggapan_pimpinan' => $laporan->tanggapan_pimpinan,
                'updated_at' => now()->toDateTimeString(),
            ];

            $existing = $this->supabase->select('laporans', [
                'local_id' => 'eq.' . $laporan->id,
                'select' => 'id',
            ]);

            if (!empty($existing['success']) && !empty($existing['data'])) {
                $filters = ['local_id' => 'eq.' . $laporan->id];
                $res = $this->supabase->update('laporans', $filters, $payload);
                if (empty($res['success'])) {
                    Log::warning('Supabase update failed for laporan local_id=' . $laporan->id . ': ' . ($res['error'] ?? 'unknown'));
                }
                return;
            }

            $res = $this->supabase->insert('laporans', [$payload]);
            if (empty($res['success'])) {
                Log::warning('Supabase insert failed for laporan local_id=' . $laporan->id . ': ' . ($res['error'] ?? 'unknown'));
            }
        } catch (\Throwable $e) {
            Log::warning('Supabase sync exception for laporan local_id=' . $laporan->id . ': ' . $e->getMessage());
        }
    }
}
