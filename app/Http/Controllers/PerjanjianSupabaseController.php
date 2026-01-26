<?php

namespace App\Http\Controllers;

use App\Models\Perjanjian;
use App\Services\SupabaseService;
use App\Jobs\ExportPerjanjianPdfJob;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PerjanjianSupabaseController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Export PDF dan upload ke Supabase
     */
    public function exportPdfToSupabase($id)
    {
        try {
            $perjanjian = Perjanjian::findOrFail($id);

            $result = $this->generateAndUploadPdf($perjanjian);

            return response()->json($result, $result['success'] ? 200 : 500);

        } catch (\Exception $e) {
            Log::error('Error export PDF to Supabase: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    protected function generateAndUploadPdf(Perjanjian $perjanjian): array
{
    try {
        // Decode tabel
        $tabelA = json_decode($perjanjian->tabelA, true) ?? [];
        $tabelB = json_decode($perjanjian->tabelB, true) ?? [];
        $tabelC = json_decode($perjanjian->tabelC, true) ?? [];

        // Generate PDF
        $pdf = Pdf::loadView('perjanjian.pdf', [
            'data'   => $perjanjian,
            'tabelA' => $tabelA,
            'tabelB' => $tabelB,
            'tabelC' => $tabelC,
            'for_pdf' => true,
        ]);

        // Gunakan ukuran custom F4 dalam points (approx 612 x 936)
        $orientation = 'portrait';
        $paperSize = $orientation === 'landscape'
            ? [0, 0, 936, 612]
            : [0, 0, 612, 936];
        $pdf->setPaper($paperSize, $orientation);

        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('dpi', 96);
        $pdf->setOption('defaultMediaType', 'print');

        // Margin biar tidak kepotong
        $pdf->setOption('margin-bottom', 12);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('margin-right', 10);

        // Simpan sementara
        $fileName = 'perjanjian-' . $perjanjian->nomor_perjanjian . '.pdf';
        $tempDir  = storage_path('app/temp');

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir . '/' . $fileName;
        $pdf->save($tempPath);

        // Upload ke Supabase
        $folder = 'pdf/' . date('Y') . '/' . date('m');
        $upload = $this->supabase->uploadFile($tempPath, $fileName, $folder);

        // Hapus temp file
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        if (!$upload['success']) {
            return [
                'success' => false,
                'error'   => $upload['error'] ?? 'Upload gagal',
            ];
        }

        // Update DB
        $perjanjian->update([
            'pdf_url'  => $upload['url'],
            'pdf_path' => $upload['path'],
        ]);

        // Backup (optional)
        $this->backupToSupabase($perjanjian);

        return [
            'success' => true,
            'url'     => $upload['url'],
            'path'    => $upload['path'],
        ];

    } catch (\Throwable $e) {
        Log::error('Generate PDF error', [
            'id' => $perjanjian->id,
            'msg' => $e->getMessage(),
        ]);

        return [
            'success' => false,
            'error'   => $e->getMessage(),
        ];
    }
}

    /**
     * Backup data perjanjian ke Supabase
     */
    protected function backupToSupabase($perjanjian)
    {
        try {
            $data = [
                'user_id' => $perjanjian->user_id,
                'nomor_perjanjian' => $perjanjian->nomor_perjanjian,
                'tahun' => $perjanjian->tahun,
                'tanggal' => $perjanjian->tanggal,
                'jenis_perjanjian' => $perjanjian->jenis_perjanjian,
                'pihak1_nama' => $perjanjian->pihak1_nama,
                'pihak1_jabatan' => $perjanjian->pihak1_jabatan,
                'pihak1_nip' => $perjanjian->pihak1_nip,
                'pihak2_nama' => $perjanjian->pihak2_nama,
                'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
                'pihak2_nip' => $perjanjian->pihak2_nip,
                'jabatan_pelaksana' => $perjanjian->jabatan_pelaksana,
                'tugas_pelaksana'   => $perjanjian->tugas_pelaksana,
                'fungsi_pelaksana'  => $perjanjian->fungsi_pelaksana,
                'tabelA' => $perjanjian->tabelA,
                'tabelB' => $perjanjian->tabelB,
                'tabelC' => $perjanjian->tabelC,
                'status' => 'active',
            ];

            $result = $this->supabase->insert('perjanjians_backup', $data);

            if ($result['success']) {
                Log::info('Data berhasil di-backup ke Supabase', ['id' => $perjanjian->id]);
            }

        } catch (\Exception $e) {
            Log::error('Error backup to Supabase: ' . $e->getMessage());
        }
    }

    /**
     * Download PDF dari Supabase
     */
    public function downloadFromSupabase($id)
    {
        try {
            $perjanjian = Perjanjian::findOrFail($id);

            if (!$perjanjian->pdf_url) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF belum di-upload ke Supabase',
                ], 404);
            }

            // Redirect ke URL Supabase
            return redirect($perjanjian->pdf_url);

        } catch (\Exception $e) {
            Log::error('Error download from Supabase: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync semua data ke Supabase
     */
    public function syncAllToSupabase()
    {
        try {
            $perjanjians = Perjanjian::all();
            $success = 0;
            $failed = 0;

            foreach (Perjanjian::all() as $perjanjian) {
                $result = $this->generateAndUploadPdf($perjanjian);
                
                if ($result['success']) {
                    $success++;
                } else {
                    $failed++;
                }

                // Delay untuk menghindari rate limit
                usleep(500000); // 0.5 detik
            }

        } catch (\Exception $e) {
            Log::error('Error sync to Supabase: ' . $e->getMessage());
           return response()->json([
                'success' => true,
                'message' => "Sync selesai",
                'success_count' => $success,
                'failed_count' => $failed,
            ]);
        }
    }
}