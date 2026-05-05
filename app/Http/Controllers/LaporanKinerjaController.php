<?php

namespace App\Http\Controllers;

use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Setting;
use App\Services\SmartValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanKinerjaController extends Controller
{
    public function index(Request $request)
    {
        // Redirect ke login jika tidak login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Ambil perjanjian yang telah disetujui untuk user saat ini
        // Status disetujui bisa ditandai dengan:
        // 1. Field status = 'disetujui'
        // 2. Atau pihak2_signature tidak kosong dan rejected = false
        $perjanjianDisetujui = Perjanjian::where('user_id', $user->id)
            ->where(function($query) {
                // Cek melalui field status (jika sudah menggunakan field status)
                $query->where('status', 'disetujui')
                    // Atau cek melalui pihak2_signature (untuk kompatibilitas backward)
                    ->orWhere(function($q) {
                        $q->whereNotNull('pihak2_signature')
                            ->where('rejected', false);
                    });
            })
            ->orderBy('updated_at', 'desc')
            ->orderBy('agreement_date', 'desc')
            ->first();

        // Jika tidak ada perjanjian yang disetujui, tampilkan halaman kosong/informasi
        if (!$perjanjianDisetujui) {
            return view('laporan-kinerja', [
                'perjanjian' => null,
                'laporans' => collect(),
                'triwulanAktif' => $this->getTriwulanAktif(),
                'message' => 'Belum ada perjanjian kinerja yang disetujui. Silakan buat atau tunggu perjanjian kinerja Anda disetujui oleh atasan.'
            ]);
        }

        // Ambil laporan yang terkait dengan perjanjian yang disetujui tersebut
        $laporans = Laporan::where('perjanjian_id', $perjanjianDisetujui->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil triwulan aktif dari setting admin
        $triwulanAktif = (int) $this->getTriwulanAktif();

        return view('laporan-kinerja', [
            'perjanjian' => $perjanjianDisetujui,
            'laporans' => $laporans,
            'triwulanAktif' => $triwulanAktif,
            'message' => null
        ]);
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
            $validated = $request->validate([
                'triwulan' => 'required|integer|min:1|max:4',
                'realisasi' => 'nullable|string',
                'realisasi_rows' => 'nullable|array',
                'realisasi_rows.*.row' => 'nullable|integer',
                'realisasi_rows.*.realisasi' => 'nullable|string',
                'realisasi_rows.*.target' => 'nullable|numeric',
                'rencana_tindak_lanjut' => 'nullable|string',
                'kesimpulan' => 'nullable|string',
            ]);

            $laporan = null;
            $perjanjian = null;

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

                // Cari atau buat laporan untuk perjanjian ini
                $laporan = Laporan::where('perjanjian_id', $perjanjianId)
                    ->orderBy('created_at', 'desc')
                    ->first();

                if (!$laporan) {
                    // Buat laporan baru jika belum ada
                    $laporan = Laporan::create([
                        'perjanjian_id' => $perjanjianId,
                        'periode' => 'Triwulan ' . $validated['triwulan'],
                        'tahun' => $perjanjian->tahun ?? date('Y'),
                        'pihak1_name' => $perjanjian->pihak1_name,
                        'pihak1_jabatan' => $perjanjian->pihak1_jabatan ?? $perjanjian->jabatan,
                        'pihak2_name' => $perjanjian->pihak2_name,
                        'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
                    ]);
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
            $triwulan = $validated['triwulan'];
            $columnName = 'realisasi_tb' . $triwulan;

            $realisasiValue = $validated['realisasi'];
            if (!empty($validated['realisasi_rows']) && is_array($validated['realisasi_rows'])) {
                $realisasiValue = json_encode([
                    'text' => $validated['realisasi'],
                    'rows' => array_map(function ($row) {
                        return [
                            'row' => isset($row['row']) ? intval($row['row']) : null,
                            'realisasi' => $row['realisasi'] ?? '',
                            'target' => isset($row['target']) ? floatval($row['target']) : null,
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

            $laporan->$columnName = $realisasiValue;
            
            // Simpan kesimpulan jika ada
            if (!empty($validated['kesimpulan'])) {
                $laporan->kesimpulan = $validated['kesimpulan'];
            }
            
            $laporan->save();

            return response()->json([
                'success' => true,
                'message' => 'Realisasi Triwulan ' . $triwulan . ' berhasil disimpan',
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
            $result = $validationService->validateLaporan($laporan);

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
}


