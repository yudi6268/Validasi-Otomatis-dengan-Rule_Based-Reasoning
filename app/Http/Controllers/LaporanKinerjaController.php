<?php

namespace App\Http\Controllers;

use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Services\RuleBasedReasoningService;
use App\Services\SupabaseService;
use App\Services\SmartValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LaporanKinerjaController extends Controller
{
    protected $supabase;

    private function isActualWadir($user): bool
    {
        if (!$user) {
            return false;
        }

        $jabatan = strtolower(trim((string) ($user->jabatan ?? '')));
        if ($jabatan === '') {
            return false;
        }

        return str_contains($jabatan, 'wakil direktur')
            || str_contains($jabatan, 'wadir');
    }

    private function matchesPerjanjianPihakKedua($user, ?Perjanjian $perjanjian): bool
    {
        if (!$user || !$perjanjian) {
            return false;
        }

        $userNama = strtolower(trim((string) ($user->nama ?? '')));
        $userJabatan = strtolower(trim((string) ($user->jabatan ?? '')));
        $userNipDigits = preg_replace('/\D+/', '', (string) ($user->nip ?? ''));

        $pihak2Nama = strtolower(trim((string) ($perjanjian->pihak2_name ?? '')));
        $pihak2Jabatan = strtolower(trim((string) ($perjanjian->pihak2_jabatan ?? '')));
        $pihak2NipDigits = preg_replace('/\D+/', '', (string) ($perjanjian->pihak2_nip ?? ''));

        $matchByNipAndJabatan = $userNipDigits !== ''
            && $pihak2NipDigits !== ''
            && $userNipDigits === $pihak2NipDigits
            && $userJabatan !== ''
            && $userJabatan === $pihak2Jabatan;

        $matchByNameAndJabatan = $userNama !== ''
            && $userJabatan !== ''
            && $userNama === $pihak2Nama
            && $userJabatan === $pihak2Jabatan;

        $matchByTaggedNameAndJabatan = $userNama !== ''
            && $userJabatan !== ''
            && str_contains($pihak2Nama, '#')
            && str_contains($pihak2Nama, $userNama)
            && $userJabatan === $pihak2Jabatan;

        return $matchByNipAndJabatan || $matchByNameAndJabatan || $matchByTaggedNameAndJabatan;
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

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index(Request $request)
    {
        // Redirect ke login jika tidak login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $isActualWadir = $this->isActualWadir($user);
        $activeSection = $request->query('section', 'laporan');

        // Redirect old validasi section URL to the regular laporan page
        if ($activeSection === 'validasi') {
            return redirect()->route('laporan.kinerja', array_filter([
                'from' => $request->query('from'),
            ]));
        }
        // ------------------------------------------------------------------
        $perjanjianIdParam = $request->query('perjanjian_id');
        if ($perjanjianIdParam) {
            $perjanjian = Perjanjian::find((int) $perjanjianIdParam);
            $isWadirUser = $this->isActualWadir($user);
            $isOwner = $perjanjian && (int) $perjanjian->user_id === (int) $user->id;

            // Wadir dapat melihat semua; Direktur/Pimpinan hanya jika ia adalah pihak2
            $isPihak2 = $this->matchesPerjanjianPihakKedua($user, $perjanjian);

            if ($isWadirUser || $isPihak2 || $isOwner) {
                if (!$perjanjian) {
                    return view('laporan-kinerja', [
                        'perjanjian'     => null,
                        'laporans'       => collect(),
                        'triwulanAktif'  => $this->getTriwulanAktif(),
                        'message'        => 'Perjanjian tidak ditemukan.',
                        'activeSection'  => $activeSection,
                        'viewMode'       => 'list',
                    ]);
                }
                $laporans = Laporan::where('perjanjian_id', $perjanjian->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Edit mode: wadir opens specific laporan in form view
                $laporanIdParam = $request->query('laporan_id');
                $mode = $request->query('mode');
                if ($laporanIdParam && $mode === 'edit') {
                    if (!$isWadirUser && $user->role !== 'user') {
                        abort(403, 'Akses edit laporan hanya untuk akun user atau Wakil Direktur.');
                    }

                    $selectedLaporan = $laporans->firstWhere('id', (int) $laporanIdParam) ?? $laporans->first();
                    return view('laporan-kinerja', [
                        'perjanjian'     => $perjanjian,
                        'laporans'       => $laporans,
                        'triwulanAktif'  => $selectedLaporan?->triwulan_aktif ?? (int) $this->getTriwulanAktif(),
                        'message'        => null,
                        'activeSection'  => $activeSection,
                        'viewMode'       => 'form',
                        'editLaporanId'  => (int) $laporanIdParam,
                        'isActualWadir'  => $isActualWadir,
                    ]);
                }

                return view('laporan-kinerja', [
                    'perjanjian'    => $perjanjian,
                    'laporans'      => $laporans,
                    'triwulanAktif' => (int) $this->getTriwulanAktif(),
                    'message'       => null,
                    'activeSection' => $activeSection,
                    'viewMode'      => 'list',
                    'isActualWadir' => $isActualWadir,
                ]);
            }
            // Bukan Wadir dan bukan pihak2 – lanjut ke alur normal staf
        }

        // ------------------------------------------------------------------
        // Normal flow: staff viewing their own laporan
        // ------------------------------------------------------------------
        $perjanjianDisetujui = Perjanjian::where('user_id', $user->id)
            ->where(function($query) {
                // Cek status baru dan variasi legacy agar input realisasi langsung mengikuti perubahan status.
                $query->whereIn('status', ['disetujui', 'approved'])
                    ->orWhere(function($q) {
                        $q->whereNotNull('pihak2_signature')
                            ->where(function($sub) {
                                $sub->whereNull('rejected')
                                    ->orWhere('rejected', false)
                                    ->orWhere('rejected', 0)
                                    ->orWhere('rejected', '0');
                            });
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->orderBy('agreement_date', 'desc')
            ->first();

        if (!$perjanjianDisetujui) {
            return view('laporan-kinerja', [
                'perjanjian' => null,
                'laporans' => collect(),
                'triwulanAktif' => $this->getTriwulanAktif(),
                'message' => 'Belum ada perjanjian kinerja yang disetujui. Silakan buat atau tunggu perjanjian kinerja Anda disetujui oleh atasan.',
                'activeSection' => $activeSection,
                'viewMode' => 'form',
                'isActualWadir' => false,
            ]);
        }

        $laporans = Laporan::where('perjanjian_id', $perjanjianDisetujui->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $triwulanAktif = (int) $this->getTriwulanAktif();

        return view('laporan-kinerja', [
            'perjanjian' => $perjanjianDisetujui,
            'laporans' => $laporans,
            'triwulanAktif' => $triwulanAktif,
            'message' => null,
            'activeSection' => $activeSection,
            'viewMode' => 'form',
            'isActualWadir' => false,
        ]);
    }

    /**
     * Dedicated validation summary page.
     * Only validates the currently active triwulan.
     * Route: GET /laporan-kinerja/validasi-summary
     */
    public function validasiSummaryPage(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();

        $triwulanAktif = (int) $this->getTriwulanAktif();

        // Find user's most recent approved perjanjian
                $perjanjian = Perjanjian::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('status', 'disetujui')
                  ->orWhere('status', 'approved')
                  ->orWhereNotNull('pihak2_signature');
            })
            ->orderByDesc('tahun')
            ->orderByDesc('created_at')
            ->first();

        if (!$perjanjian) {
            return view('laporan.validasi-summary', [
                'perjanjian'    => null,
                'laporan'       => null,
                'triwulanAktif' => $triwulanAktif,
                'twResult'      => null,
                'stats'         => ['total' => 0, 'valid' => 0, 'tidak_valid' => 0, 'revisi' => 0, 'peringatan' => 0],
            ]);
        }

        $laporan = Laporan::where('perjanjian_id', $perjanjian->id)
            ->orderByDesc('created_at')
            ->first();

        $twResult = null;
        if ($laporan) {
            $service  = new SmartValidationService();
            $twResult = $service->validateLaporan($laporan, $triwulanAktif);
        }

        // Count actual indicators from the perjanjian (tabelB sasaran rows)
        $tabelB = is_array($perjanjian->tabelB)
            ? $perjanjian->tabelB
            : json_decode((string) ($perjanjian->tabelB ?? '[]'), true);
        $totalIndikator = count($tabelB['sasaran'] ?? []);

        // Count how many indicators have been filled (realisasi value present in active TW)
        $validCount = 0;
        if ($laporan) {
            $realisasiField = 'realisasi_tb' . $triwulanAktif;
            $realisasiData  = $laporan->$realisasiField;
            if (is_string($realisasiData)) {
                $realisasiData = json_decode($realisasiData, true);
            }
            if (is_array($realisasiData)) {
                foreach ($realisasiData['rows'] ?? [] as $row) {
                    $rowId = $row['row'] ?? '';
                    if (str_starts_with($rowId, 'kinerja-')) {
                        $val = $row['realisasi'] ?? '';
                        if ($val !== '' && $val !== null) {
                            $validCount++;
                        }
                    }
                }
                // If no rows in realisasi but laporan exists, fall back to total
                if (empty($realisasiData['rows']) && $totalIndikator === 0) {
                    $totalIndikator = 0;
                }
            }
        }

        $stats = [
            'total'       => $totalIndikator,
            'valid'       => $validCount,
            'tidak_valid' => $twResult ? count($twResult['issues']) : 0,
            'revisi'      => $twResult ? count($twResult['suggestions']) : 0,
            'peringatan'  => $twResult ? count($twResult['warnings']) : 0,
        ];

        // Check if laporan sudah tervalidasi untuk triwulan ini
        $isAlreadyValidated = false;
        if ($laporan && $laporan->hasValidationForTriwulan($triwulanAktif)) {
            $isAlreadyValidated = true;
        }

        return view('laporan.validasi-summary', [
            'perjanjian'    => $perjanjian,
            'laporan'       => $laporan,
            'triwulanAktif' => $triwulanAktif,
            'twResult'      => $twResult,
            'stats'         => $stats,
            'isAlreadyValidated' => $isAlreadyValidated,
        ]);
    }

    /**
     * Wadir laporan kinerja list page (full page, like perjanjian/index)
     * Route: GET /laporan-kinerja/wadir
     */
    public function wadirIndex(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $isActualWadir = $this->isActualWadir($user);

        // Untuk Wadir aktual: lihat seluruh perjanjian yang disupervisi.
        // Untuk user dashboard non-Wadir aktual: batasi ke data milik sendiri agar endpoint tidak 403.
        if ($isActualWadir) {
            $wadirPerjanjians = Perjanjian::where(function ($q) use ($user) {
                $q->where('pihak2_name', $user->nama)
                  ->orWhere('pihak2_jabatan', $user->jabatan)
                  ->orWhere('pihak2_nip', $user->nip)
                  ->orWhere('user_id', $user->id);
            })->get();
        } else {
            $wadirPerjanjians = Perjanjian::where('user_id', $user->id)->get();
        }

        // Klasifikasi status perjanjian agar laporan tetap terlihat saat perjanjian dikembalikan untuk edit.
        $perjanjians = $wadirPerjanjians->filter(function ($p) {
            if (!empty($p->rejected) && (string) $p->rejected !== '0') return false;
            if (!empty($p->pihak2_signature)) return true;
            return in_array(strtolower((string) ($p->status ?? '')), ['disetujui', 'approved']);
        })->values();

        $normalizePerjanjianStatus = function ($p) {
            if (!$p) {
                return 'terkirim';
            }

            if (!empty($p->rejected) && (string) $p->rejected !== '0') {
                return 'ditolak';
            }

            $status = strtolower((string) ($p->status ?? ''));
            if (!empty($p->pihak2_signature)) {
                return 'disetujui';
            }
            if (in_array($status, ['menunggu', 'waiting'])) {
                return 'menunggu';
            }
            if (in_array($status, ['ditolak', 'rejected'])) {
                return 'ditolak';
            }

            return 'terkirim';
        };

        $perjanjianIds = $wadirPerjanjians->pluck('id')->toArray();
        $laporans = Laporan::whereIn('perjanjian_id', $perjanjianIds)->get();

        // Build items with computed status
        $allItems = $laporans->map(function ($l) use ($wadirPerjanjians, $normalizePerjanjianStatus) {
            $perjanjian = $wadirPerjanjians->firstWhere('id', $l->perjanjian_id);
            $perjanjianStatus = $normalizePerjanjianStatus($perjanjian);

            $hasRealisasi = false;
            for ($tw = 1; $tw <= 4; $tw++) {
                if (!empty($l->{'realisasi_tb' . $tw})) { $hasRealisasi = true; break; }
            }

            // Parent perjanjian non-disetujui -> laporan tetap ada namun status menunggu.
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
                'id'            => $l->id,
                'perjanjian_id' => $l->perjanjian_id,
                'employee_name' => $perjanjian->pihak1_name ?? '-',
                'jabatan'       => $perjanjian->pihak1_jabatan ?? '-',
                'triwulan'      => $l->triwulan_aktif,
                'status'        => $status,
                'tahun'         => $l->tahun ?? ($perjanjian->tahun ?? date('Y')),
                'updated_at'    => ($l->updated_at ?? $l->created_at)?->toDateString(),
            ];
        })->values();

        $statusFilter = $request->query('status'); // menunggu/disetujui/ditolak/terkirim or null (=all)

        // Filter by status if provided
        $filtered = $statusFilter
            ? $allItems->filter(fn($i) => $i['status'] === $statusFilter)->values()
            : $allItems;

        // If filter returns empty but there is overall data, redirect to show all
        if ($statusFilter && $filtered->isEmpty() && $allItems->isNotEmpty()) {
            return redirect()->route('laporan.wadir.index', ['from' => $request->query('from', 'dashboard_wadir_laporan')]);
        }

        $statusTitles = [
            'terkirim'  => 'Laporan Kinerja Terkirim',
            'disetujui' => 'Laporan Kinerja Disetujui',
            'ditolak'   => 'Laporan Kinerja Ditolak',
            'menunggu'  => 'Laporan Menunggu Reviu',
        ];
        $pageTitle = $statusFilter ? ($statusTitles[$statusFilter] ?? 'Laporan Kinerja') : 'Semua Laporan Kinerja';

        return view('laporan.wadir-index', [
            'items'        => $filtered,
            'statusFilter' => $statusFilter,
            'pageTitle'    => $pageTitle,
            'from'         => $request->query('from', 'dashboard_wadir_laporan'),
        ]);
    }

    /**
     * Delete a laporan kinerja. Wadir only, must not be disetujui.
     * Route: DELETE /laporan/{id}
     */
    public function destroy(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        if (!$this->isActualWadir($user)) {
            abort(403, 'Hanya Wakil Direktur yang dapat menghapus laporan.');
        }

        $laporan = Laporan::findOrFail($id);

        // Prevent deleting an already-approved laporan
        if (!empty($laporan->pihak2_signature)) {
            return back()->with('error', 'Laporan yang sudah disetujui tidak dapat dihapus.');
        }

        $laporan->delete();

        return redirect()
            ->route('laporan.wadir.index', ['from' => $request->query('from', 'dashboard_wadir_laporan')])
            ->with('success', 'Laporan kinerja berhasil dihapus.');
    }

    /**
     * Preview PDF laporan kinerja per triwulan (opens in new window)
     */
    public function pdfPreview(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $laporan = Laporan::findOrFail($id);
        $perjanjian = $laporan->perjanjian;

        // Authorization: owner, wadir, or pihak2 (direktur)
        $user = Auth::user();
        $isPihak2 = $this->matchesPerjanjianPihakKedua($user, $perjanjian);
        if ((int) $perjanjian->user_id !== (int) $user->id && !$this->isActualWadir($user) && !$isPihak2) {
            abort(403, 'Tidak diizinkan mengakses laporan ini.');
        }

        $triwulan = (int) $request->query('triwulan', $laporan->triwulan_aktif ?? 1);
        if ($triwulan < 1 || $triwulan > 4) {
            $triwulan = 1;
        }

        // Detect direktur: pihak2 match OR jabatan contains 'direktur' (but not wakil direktur)
        $jabatanStr   = strtolower($user->jabatan ?? '');
        $isJabDirektur = str_contains($jabatanStr, 'direktur') && !str_contains($jabatanStr, 'wakil');
        $isDirekturUser = $isPihak2 || $isJabDirektur;

        $viewData = [
            'laporan'       => $laporan,
            'perjanjian'    => $perjanjian,
            'triwulan'      => $triwulan,
            'for_pdf'       => false,
            'isDirektur'    => $isDirekturUser,
            'tanggapanVal'  => $laporan->tanggapan_pimpinan ?? '',
        ];

        $downloadRequested = in_array((string) $request->query('download', '0'), ['1', 'true', 'yes'], true);
        if ($downloadRequested) {
            $viewData['for_pdf'] = true;
            $html = view('laporan.pdf', $viewData)->render();

            $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadHTML($html)
                ->setPaper('Folio')
                ->setOrientation('Portrait')
                ->setOption('enable-local-file-access', true)
                ->setOption('disable-smart-shrinking', true)
                ->setOption('zoom', 1.0);

            $namaPerjanjian = $perjanjian->nomor_perjanjian ?? ('PK-' . $perjanjian->id);
            $namaPerjanjian = preg_replace('/[^A-Za-z0-9]+/', '_', $namaPerjanjian);
            $fileName = 'Laporan_Kinerja_' . $namaPerjanjian . '_Triwulan_' . $triwulan . '.pdf';

            return response($pdf->output())
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        }

        // Keep HTML preview for on-screen reading and action buttons.
        return view('laporan.pdf', $viewData);
    }

    /**
     * Ambil triwulan aktif dari setting
     */
    private function getTriwulanAktif()
    {
        $setting = Setting::where('key', 'triwulan_aktif')->first();
        return $setting ? $setting->value : 1;
    }

    /**
     * Get laporan ID by perjanjian ID
     * Endpoint: GET /api/laporan/by-perjanjian/{perjanjianId}
     */
    public function getLaporanByPerjanjian($perjanjianId)
    {
        try {
            $laporan = Laporan::where('perjanjian_id', $perjanjianId)->first();
            
            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }
            
            // Verifikasi akses
            $perjanjian = $laporan->perjanjian;
            if ($perjanjian->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak mengakses laporan ini'
                ], 403);
            }
            
            return response()->json([
                'success' => true,
                'laporan_id' => $laporan->id
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk menyimpan realisasi
     * Support dua mode:
     * 1. Dari perjanjian langsung: POST /api/laporan/perjanjian dengan perjanjian_id
     * 2. Dari laporan yang ada: POST /api/laporan/{laporanId}/realisasi
     */
    public function saveRealisasi(Request $request, $laporanId = null)
    {
        try {
            $validationReset = false; // Initialize early
            
            $validated = $request->validate([
                'triwulan' => 'required|integer|min:1|max:4',
                'realisasi' => 'nullable|string',
                'realisasi_rows' => 'nullable|array',
                'realisasi_rows.*.row' => 'nullable|string',
                'realisasi_rows.*.realisasi' => 'nullable|numeric',
                'realisasi_rows.*.target' => 'nullable|numeric',
                'realisasi_rows.*.indicator_type' => 'nullable|string',
                'realisasi_rows.*.ket' => 'nullable|string',
                'rencana_tindak_lanjut' => 'nullable|string',
                'kesimpulan' => 'nullable|string',
                'finalize' => 'nullable|boolean',
            ]);

            $triwulanAktif = (int) $this->getTriwulanAktif();
            if ((int) $validated['triwulan'] !== $triwulanAktif) {
                return response()->json([
                    'success' => false,
                    'message' => 'Input laporan hanya diizinkan pada Triwulan aktif (Triwulan ' . $triwulanAktif . ').'
                ], 422);
            }

            $laporan = null;
            $perjanjian = null;
            $triwulan = (int) $validated['triwulan'];

            // Mode 1: Dari perjanjian_id (langsung input realisasi)
            if ($request->has('perjanjian_id')) {
                $perjanjianId = $request->input('perjanjian_id');
                $perjanjian = Perjanjian::findOrFail($perjanjianId);

                // Verifikasi access
                if ($perjanjian->user_id !== auth()->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak berhak mengakses perjanjian ini'
                    ], 403);
                }

                // Cari atau buat laporan untuk perjanjian + triwulan yang aktif.
                // Jangan ambil "laporan terbaru" lintas triwulan karena dapat membuat status panel tidak sinkron.
                $laporan = Laporan::where('perjanjian_id', $perjanjianId)
                    ->where('triwulan_aktif', $triwulan)
                    ->orderBy('updated_at', 'desc')
                    ->first();

                if (!$laporan) {
                    // Buat laporan baru jika belum ada
                    $laporan = Laporan::create(
                        $this->buildLaporanCreatePayload($perjanjian, $perjanjianId, $triwulan)
                    );
                }
            }
            // Mode 2: Dari laporan yang sudah ada
            else if ($laporanId) {
                $laporan = Laporan::findOrFail($laporanId);
                $perjanjian = $laporan->perjanjian;

                // Verifikasi access
                if ($perjanjian->user_id !== auth()->id()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak berhak mengakses laporan ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request'
                ], 400);
            }

            // Simpan realisasi ke kolom yang sesuai
            $columnName = 'realisasi_tb' . $triwulan;

            $realisasiValue = $validated['realisasi'];
            if (!empty($validated['realisasi_rows']) && is_array($validated['realisasi_rows'])) {
                $realisasiValue = json_encode([
                    'text' => $validated['realisasi'],
                    'rows' => array_map(function ($row) {
                        $realisasiValue = null;
                        $targetValue = isset($row['target']) ? floatval($row['target']) : null;
                        $indicatorType = $this->normalizeIndicatorType($row['indicator_type'] ?? 'positif');
                        $pct = null;
                        $performancePct = null;
                        
                        if (array_key_exists('realisasi', $row) && $row['realisasi'] !== null && $row['realisasi'] !== '') {
                            $realisasiValue = floatval($row['realisasi']);
                            // Capaian selalu realisasi/target*100; analisis performa disesuaikan tipe indikator.
                            $pct = $this->calculateCapaianPercentage($targetValue, $realisasiValue);
                            $performancePct = $this->calculatePerformancePercentage($targetValue, $realisasiValue, $indicatorType);
                        }

                        return [
                            'row' => isset($row['row']) ? (string) $row['row'] : null,
                            'realisasi' => $realisasiValue,
                            'target' => $targetValue,
                            'indicator_type' => $indicatorType,
                            'pct' => $pct,
                            'performance_pct' => $performancePct,
                            'ket' => isset($row['ket']) ? (string) $row['ket'] : '',
                        ];
                    }, $validated['realisasi_rows']),
                    'followup' => $validated['rencana_tindak_lanjut'] ?? '',
                ], JSON_UNESCAPED_UNICODE);
            } else {
                $realisasiValue = json_encode([
                    'text' => $validated['realisasi'],
                    'rows' => [],
                    'followup' => $validated['rencana_tindak_lanjut'] ?? '',
                ], JSON_UNESCAPED_UNICODE);
            }

            $updates = [
                $columnName => $realisasiValue,
                'triwulan_aktif' => $triwulan,
                'rejected' => false,
                'rejection_reason' => null,
                'catatan_pihak2' => null,
                'tanggapan_pimpinan' => null,
                'pihak2_signature' => null,
                'tanggapan_laporan_kurang_baik' => false,
                'tanggapan_laporan_sudah_baik' => false,
                'tanggapan_laporan_diperbaiki' => false,
                'tanggapan_laporan_diteliti_ulang' => false,
                'tanggapan_realisasi_diteliti_ulang' => false,
                'tanggapan_capaian_diteliti_ulang' => false,
            ];

            // Sinkronisasi BAB laporan agar validasi rule-based memiliki konteks yang lengkap.
            if (!empty($validated['realisasi'])) {
                $updates['bab_capaian'] = $validated['realisasi'];
                $updates['uraian_kegiatan'] = $validated['realisasi'];
            }

            if (!empty($validated['rencana_tindak_lanjut'])) {
                $updates['bab_rencana'] = $validated['rencana_tindak_lanjut'];
                $updates['rencana_tindak_lanjut'] = $validated['rencana_tindak_lanjut'];
            }

            // Isi minimal metadata agar tidak dianggap kosong oleh validasi dasar.
            if (empty($laporan->sasaran)) {
                $updates['sasaran'] = 'Sasaran kinerja mengacu pada indikator perjanjian kinerja triwulan berjalan.';
            }
            if (empty($laporan->bobot) || $laporan->bobot <= 0) {
                $updates['bobot'] = 100;
            }
            if (empty($laporan->sumber_data)) {
                $updates['sumber_data'] = 'Data realisasi input pengguna dan tabel target perjanjian kinerja.';
            }

            // Simpan kesimpulan jika ada
            if (!empty($validated['kesimpulan'])) {
                $updates['kesimpulan'] = $validated['kesimpulan'];
            }

            $filteredUpdates = $this->filterColumnsForTable('laporans', $updates, [$columnName]);
            foreach ($filteredUpdates as $column => $value) {
                $laporan->setAttribute($column, $value);
            }

            $laporan->save();

            // Saat laporan diedit (bukan finalisasi), batalkan validasi untuk triwulan ini
            // agar user harus validasi ulang setelah ada perubahan konten.
            if (empty($validated['finalize'])) {
                $validationResults = $laporan->validation_results ?? [];
                if (is_string($validationResults)) {
                    $validationResults = json_decode($validationResults, true) ?? [];
                }
                if (is_array($validationResults)) {
                    // Hapus validasi untuk triwulan ini
                    if (isset($validationResults[$triwulan]) || isset($validationResults['tw_' . $triwulan])) {
                        $validationReset = true;
                    }
                    unset($validationResults[$triwulan]);
                    unset($validationResults['tw_' . $triwulan]);
                    $laporan->update([
                        'validation_results' => $validationResults,
                    ]);
                }
            }

            // Mirror laporan ke Supabase (best-effort, tidak mengganggu simpan utama)
            $this->syncLaporanToSupabase($laporan, $triwulan);

            $workflowStatus = 'draft_saved';
            $validation = null;
            $revisionGuides = [];

            // Ketika finalisasi, jalankan validasi rule-based dan tentukan alur lanjutan.
            if (!empty($validated['finalize'])) {
                $validationService = new SmartValidationService();
                $validation = $validationService->validateLaporan($laporan, $triwulanAktif);

                $highIssues = collect($validation['issues'] ?? [])->where('severity', 'high')->count();
                $isPassed = ($validation['score'] ?? 0) >= 75 && $highIssues === 0;

                if ($isPassed) {
                    $workflowStatus = 'forwarded_to_pimpinan';
                    $laporan->rejected = false;
                    $laporan->rejection_reason = null;
                    $laporan->catatan_pihak2 = 'Lolos validasi otomatis rule-based dan diteruskan ke pimpinan untuk review.';
                    $laporan->tanggapan_pihak2 = false;
                    $laporan->save();

                    $perjanjian = $laporan->perjanjian;
                    $pimpinan = null;
                    if ($perjanjian) {
                        if (!empty($perjanjian->pihak2_nip)) {
                            $pimpinan = User::where('nip', $perjanjian->pihak2_nip)->first();
                        }
                        if (!$pimpinan && !empty($perjanjian->pihak2_name)) {
                            $pimpinan = User::where('nama', $perjanjian->pihak2_name)->first();
                        }
                    }

                    if ($pimpinan) {
                        Notification::create([
                            'user_id' => $pimpinan->id,
                            'title' => 'Laporan Kinerja Siap Direview',
                            'message' => 'Laporan kinerja triwulan ' . $triwulan . ' atas nama ' . ($perjanjian->pihak1_name ?? 'pegawai') . ' telah lolos validasi otomatis dan menunggu review pimpinan.',
                            'type' => 'info',
                            'is_read' => false,
                        ]);
                    }
                } else {
                    $workflowStatus = 'returned_for_revision';
                    $revisionGuides = $this->buildRevisionGuides($validation);
                    $laporan->rejected = true;
                    $laporan->rejection_reason = implode("\n", $revisionGuides);
                    $laporan->catatan_pihak2 = 'Perlu revisi berdasarkan validasi otomatis rule-based sebelum dapat diteruskan ke pimpinan.';
                    $laporan->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Realisasi Triwulan ' . $triwulan . ' berhasil disimpan',
                'workflow_status' => $workflowStatus,
                'validation_reset' => $validationReset,
                'validation' => $validation,
                'revision_guides' => $revisionGuides,
                'data' => $laporan
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Smart Validation - Validasi laporan dengan AI-powered validation
     * 
     * Endpoint: POST /api/laporan/{id}/smart-validate
     */
    public function smartValidate(Request $request, $id)
    {
        try {
            $laporan = Laporan::findOrFail($id);
            $perjanjian = $laporan->perjanjian;

            // Verifikasi akses
            if ($perjanjian->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak berhak mengakses laporan ini'
                ], 403);
            }

            // Jalankan smart validation
            $validationService = new SmartValidationService();
            $result = $validationService->validateLaporan($laporan, (int) $this->getTriwulanAktif());

            return response()->json([
                'success' => true,
                'message' => 'Validasi selesai',
                'validation' => $result
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick Validation - Validasi cepat tanpa full analysis
     * 
     * Endpoint: POST /api/laporan/quick-validate
     */
    public function quickValidate(Request $request)
    {
        try {
            $validated = $request->validate([
                'uraian_kegiatan' => 'nullable|string',
                'sasaran' => 'nullable|string',
                'bobot' => 'nullable|numeric',
                'realisasi_tb1' => 'nullable|string',
                'realisasi_tb2' => 'nullable|string',
                'realisasi_tb3' => 'nullable|string',
                'realisasi_tb4' => 'nullable|string',
            ]);

            $validationService = new SmartValidationService();
            $result = $validationService->quickValidate($validated);

            return response()->json([
                'success' => true,
                'validation' => $result
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bangun petunjuk revisi terstruktur berdasarkan hasil validasi.
     */
    private function buildRevisionGuides(array $validation): array
    {
        $guides = [];

        foreach ($validation['issues'] ?? [] as $issue) {
            $section = $this->mapFieldToSection($issue['field'] ?? null);
            $message = $issue['message'] ?? 'Temuan perlu perbaikan.';
            $fix = $issue['fix'] ?? 'Sesuaikan data pada bagian terkait.';
            $guides[] = "[$section] $message. Saran: $fix";
        }

        foreach ($validation['warnings'] ?? [] as $warning) {
            $section = $this->mapFieldToSection($warning['field'] ?? null);
            $message = $warning['message'] ?? 'Terdapat peringatan yang perlu ditindaklanjuti.';
            $fix = $warning['fix'] ?? 'Tinjau kembali kelengkapan/konsistensi data.';
            $guides[] = "[$section] $message. Saran: $fix";
        }

        if (empty($guides)) {
            $guides[] = '[Umum] Laporan perlu ditinjau ulang untuk memastikan seluruh komponen C, D, dan BAB III Penutup konsisten.';
        }

        return $guides;
    }

    /**
     * Mapping field validasi ke section formulir laporan.
     */
    private function mapFieldToSection(?string $field): string
    {
        $field = strtolower((string) $field);

        if (str_contains($field, 'realisasi_tb') || str_contains($field, 'rows') || str_contains($field, 'uraian_kegiatan') || str_contains($field, 'sasaran') || str_contains($field, 'bobot')) {
            return 'Form C - Evaluasi dan Analisis Kinerja';
        }

        if (str_contains($field, 'bab_rencana') || str_contains($field, 'rencana_tindak_lanjut')) {
            return 'Form D - Rencana Tindak Lanjut';
        }

        if (str_contains($field, 'kesimpulan')) {
            return 'BAB III - Penutup';
        }

        if (str_contains($field, 'sumber_data') || str_contains($field, 'triwulan')) {
            return 'Kelengkapan Data Laporan';
        }

        return 'Bagian Laporan';
    }

    /**
     * Sinkronisasi data laporan ke Supabase.
     */
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

    /**
     * Bangun payload create laporan yang kompatibel dengan skema DB aktif.
     */
    private function buildLaporanCreatePayload(Perjanjian $perjanjian, int $perjanjianId, int $triwulan): array
    {
        $candidate = [
            'user_id' => $perjanjian->user_id ?? Auth::id(),
            'perjanjian_id' => $perjanjianId,
            'periode' => 'Triwulan ' . $triwulan,
            'triwulan_aktif' => $triwulan,
            'tahun' => $perjanjian->tahun ?? date('Y'),
            'pihak1_name' => $perjanjian->pihak1_name,
            'pihak1_jabatan' => $perjanjian->pihak1_jabatan ?? $perjanjian->jabatan,
            'pihak2_name' => $perjanjian->pihak2_name,
            'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
        ];

        return $this->filterColumnsForTable('laporans', $candidate, ['perjanjian_id']);
    }

    /**
     * Filter data hanya untuk kolom yang ada pada tabel DB aktif.
     */
    private function filterColumnsForTable(string $table, array $data, array $alwaysInclude = []): array
    {
        try {
            $connection = (new Laporan())->getConnection();
            $schema = $connection->getSchemaBuilder();

            $filtered = [];
            foreach ($data as $column => $value) {
                if (in_array($column, $alwaysInclude, true) || $schema->hasColumn($table, $column)) {
                    $filtered[$column] = $value;
                }
            }

            return $filtered;
        } catch (\Throwable $e) {
            Log::warning('Column filter fallback for table ' . $table . ': ' . $e->getMessage());
            return $data;
        }
    }

    /**
     * Simpan hasil validasi laporan ke database
     * 
     * POST /api/validasi-laporan
     * Body: {tw, score, total, valid, invalid, warnings, suggestions, perjanjianId, laporanId}
     */
    public function saveValidasiResult(Request $request)
    {
        try {
            $validated = $request->validate([
                'tw' => 'required|integer|between:1,4',
                'score' => 'required|integer|min:0|max:100',
                'total' => 'required|integer',
                'valid' => 'required|integer',
                'invalid' => 'required|integer',
                'warnings' => 'required|integer',
                'suggestions' => 'required|integer',
                'laporanId' => 'required|integer',
                'perjanjianId' => 'nullable|integer',
            ]);

            $laporan = Laporan::findOrFail($validated['laporanId']);

            // Buat array validation result untuk triwulan ini
            $validationResults = $laporan->validation_results ?? [];
            if (!is_array($validationResults)) {
                $validationResults = [];
            }

            $validationResults[$validated['tw']] = [
                'score' => $validated['score'],
                'issues' => $validated['invalid'],
                'warnings' => $validated['warnings'],
                'suggestions' => $validated['suggestions'],
                'validated' => true,
                // Simpan dengan offset timezone agar tidak ambigu saat ditampilkan di klien.
                'validated_at' => now('Asia/Jakarta')->toIso8601String(),
                'total' => $validated['total'],
                'valid' => $validated['valid'],
            ];

            // Update laporan dengan hasil validasi
            $laporan->update([
                'validation_results' => $validationResults,
                'validation_timestamp' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Validasi berhasil disimpan',
                'data' => $validationResults,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi data gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Save validation result error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ambil hasil validasi dari database
     * 
     * GET /api/validasi-laporan/{laporanId}/{tw}
     */
    public function getValidasiResult($laporanId, $tw)
    {
        try {
            $laporan = Laporan::findOrFail($laporanId);
            $result = $laporan->getValidationResult($tw);

            if (!$result) {
                return response()->json([
                    'success' => true,
                    'validated' => false,
                ]);
            }

            $capaian_pct = 0.0;
            $raw = $laporan->{'realisasi_tb' . $tw} ?? null;
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
                            // Fallback untuk data lama jika target/realisasi tidak lengkap.
                            $kinerjaPcts[] = floatval($row['performance_pct']);
                        }
                    }
                }
                if (count($kinerjaPcts) > 0) {
                    $capaian_pct = array_sum($kinerjaPcts) / count($kinerjaPcts);
                }
            }
            $capaian_pct = round($capaian_pct, 2);

            $validatedAtRaw = $result['validated_at'] ?? null;
            $validatedAtWib = null;
            if (!empty($validatedAtRaw)) {
                try {
                    $validatedAt = \Carbon\Carbon::parse($validatedAtRaw);
                    // Kompatibilitas data lama: string tanpa offset dianggap UTC.
                    if (!preg_match('/(Z|[+\-]\d{2}:?\d{2})$/', (string) $validatedAtRaw)) {
                        $validatedAt = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', (string) $validatedAtRaw, 'UTC');
                    }
                    $validatedAtWib = $validatedAt->setTimezone('Asia/Jakarta')->toIso8601String();
                } catch (\Throwable $e) {
                    $validatedAtWib = null;
                }
            }

            return response()->json([
                'success' => true,
                'validated' => true,
                'score' => $result['score'] ?? 0,
                'issues' => $result['issues'] ?? 0,
                'warnings' => $result['warnings'] ?? 0,
                'suggestions' => $result['suggestions'] ?? 0,
                'validated_at' => $validatedAtWib,
                'total' => $result['total'] ?? 0,
                'valid' => $result['valid'] ?? 0,
                'capaian_pct' => $capaian_pct,
            ]);
        } catch (\Throwable $e) {
            Log::error('Get validation result error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}


