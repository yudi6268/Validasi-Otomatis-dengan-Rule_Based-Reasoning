<?php

namespace App\Http\Controllers;

use App\Models\Perjanjian;
use App\Models\Laporan;
use App\Models\Setting;
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
        $triwulanAktif = $this->getTriwulanAktif();

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
                'realisasi' => 'nullable|string'
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

            $laporan->$columnName = $validated['realisasi'];
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
}


