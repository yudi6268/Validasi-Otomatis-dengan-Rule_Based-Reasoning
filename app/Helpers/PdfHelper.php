<?php

namespace App\Helpers;

use App\Models\Perjanjian;
/**
 * Helper class untuk generate PDF
 * Menggunakan Snappy (wkhtmltopdf) dan FPDI untuk merge
 */
class PdfHelper
{

    /**
     * Generate PDF using Snappy (wkhtmltopdf)
     * Better for complex layouts & mixed orientation
     */
    public static function generatePerjanjianSnappy(Perjanjian $perjanjian, $laporan = null)
    {
        $viewData = self::prepareViewData($perjanjian, $laporan);
        $viewData['for_pdf'] = true;

        // 1. Generate PORTRAIT part (Page 1-2)
        $viewData['pdf_part'] = 'portrait';
        $pdfPortrait = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('perjanjian.pdf-snappy', $viewData);
        $pdfPortrait->setPaper('Folio');
        $pdfPortrait->setOrientation('Portrait');
        $pdfPortrait->setOption('enable-local-file-access', true);
        $pdfPortrait->setOption('disable-smart-shrinking', true);
        $pdfPortrait->setOption('zoom', 1.0);
        $pdfPortrait->setOption('margin-top', 0);
        $pdfPortrait->setOption('margin-right', 0);
        $pdfPortrait->setOption('margin-bottom', 0);
        $pdfPortrait->setOption('margin-left', 0);
        
        $contentPortrait = $pdfPortrait->output();

        // Check if we need landscape part
        $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        // Also check Tabel D (Hierarchical budget) existence logic similar to blade
        $hasLandscapeData = (!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0);
        
        // Simple check for Tabel D existence
        if (!$hasLandscapeData) {
             $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
             if (!empty($tabelC)) $hasLandscapeData = true; 
        }

        if (!$hasLandscapeData) {
            return $contentPortrait; // Return raw content if only portrait
        }

        // 2. Generate LANDSCAPE part (Page 3)
        $viewData['pdf_part'] = 'landscape';
        $pdfLandscape = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('perjanjian.pdf-snappy', $viewData);
        $pdfLandscape->setPaper('Folio');
        $pdfLandscape->setOrientation('Landscape'); // Physical Landscape
        $pdfLandscape->setOption('enable-local-file-access', true);
        $pdfLandscape->setOption('disable-smart-shrinking', true);
        $pdfLandscape->setOption('zoom', 1.0);
        $pdfLandscape->setOption('margin-top', 0);
        $pdfLandscape->setOption('margin-right', 0);
        $pdfLandscape->setOption('margin-bottom', 0);
        $pdfLandscape->setOption('margin-left', 0);

        $contentLandscape = $pdfLandscape->output();

        // 3. Merge using FPDI
        $pdf = new \setasign\Fpdi\Fpdi();
        
        // Helper to add pages from stream
        $addPagesFromContent = function($content) use ($pdf) {
            $stream = \setasign\Fpdi\PdfParser\StreamReader::createByString($content);
            $pageCount = $pdf->setSourceFile($stream);
            for ($i = 1; $i <= $pageCount; $i++) {
                $tplId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplId);
                // Set page orientation based on imported page size
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                $pdf->AddPage($orientation, array($size['width'], $size['height']));
                $pdf->useTemplate($tplId);
            }
        };

        $addPagesFromContent($contentPortrait);
        $addPagesFromContent($contentLandscape);

        return $pdf->Output('S'); // Return as string
    }

    /**
     * Prepare view data untuk PDF generation
     *
     * @param Perjanjian $perjanjian
     * @return array
     */
    private static function prepareViewData(Perjanjian $perjanjian, $laporan = null)
    {
        // Decode tabel data dari perjanjian model
        $rawTabelA = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
        $rawTabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        $rawTabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);

        // Convert images to base64 for PDF embedding
        $logo_data = self::imageToBase64('images/logo_pemda.png');
        $pihak1_ttd_data = self::imageToBase64($perjanjian->pihak1_ttd);
        $pihak2_ttd_data = self::imageToBase64($perjanjian->pihak2_signature);

        // Prepare tugas and fungsi
        $tugasValue = $perjanjian->tugas_pelaksana;
        if ($tugasValue === null || $tugasValue === '') {
            $tugasValue = $perjanjian->tugas ?: '-';
        }

        $fungsiValue = $perjanjian->fungsi_pelaksana;
        if ($fungsiValue === null || $fungsiValue === '') {
            $fungsiValue = $perjanjian->fungsi ?: '-';
        }

        if ($fungsiValue && is_string($fungsiValue) && $fungsiValue !== '-') {
            $fungsiArray = json_decode($fungsiValue, true);
            if (is_array($fungsiArray)) {
                $fungsiValue = implode("\n", $fungsiArray);
            }
        }

        // Fill in missing pangkat from User table (for older records)
        if (empty($perjanjian->pihak1_pangkat)) {
            $pihak1User = \App\Models\User::where('nama', $perjanjian->pihak1_name)->first();
            if ($pihak1User && !empty($pihak1User->pangkat)) {
                $perjanjian->pihak1_pangkat = $pihak1User->pangkat;
            }
        }
        if (empty($perjanjian->pihak2_pangkat)) {
            $pihak2User = \App\Models\User::where('nama', $perjanjian->pihak2_name)->first();
            if ($pihak2User && !empty($pihak2User->pangkat)) {
                $perjanjian->pihak2_pangkat = $pihak2User->pangkat;
            }
        }

        $user = new \stdClass();
        $user->id = $perjanjian->user_id;
        $user->nama = $perjanjian->pihak1_name;
        $user->jabatan = $perjanjian->pihak1_jabatan;
        $user->pangkat = $perjanjian->pihak1_pangkat ?? null;
        $user->nip = $perjanjian->pihak1_nip ?? null;
        $user->tanda_tangan = $perjanjian->pihak1_ttd ?? null;

        $tanggalData = $perjanjian->agreement_date ?? $perjanjian->created_at;
        $tahun = \Carbon\Carbon::parse($tanggalData)->format('Y');

        // Jika ada data Laporan (dari form yang baru disimpan), gunakan nilainya
        // untuk menimpa bagian-bagian yang akan ditampilkan di PDF sehingga
        // preview/download konsisten dengan input terbaru.
        if ($laporan) {
            try {
                $lap = is_object($laporan) ? $laporan : (is_array($laporan) ? (object) $laporan : null);
                if ($lap) {
                    if (!empty($lap->bab_capaian)) $perjanjian->bab_capaian = $lap->bab_capaian;
                    if (!empty($lap->bab_rencana)) $perjanjian->bab_rencana = $lap->bab_rencana;
                    if (!empty($lap->kesimpulan)) $perjanjian->kesimpulan = $lap->kesimpulan;
                    // Override realisasi triwulan jika tersedia
                    for ($i = 1; $i <= 4; $i++) {
                        $col = 'realisasi_tb' . $i;
                        if (isset($lap->{$col}) && $lap->{$col} !== null) {
                            $perjanjian->{$col} = $lap->{$col};
                        }
                    }
                }
            } catch (\Throwable $e) {
                // ignore and continue with original perjanjian
            }
        }

        return [
            'perjanjian' => $perjanjian,
            'data' => $perjanjian,
            'tabelA' => $rawTabelA,
            'tabelB' => $rawTabelB,
            'tabelC' => $rawTabelC,
            'logo_data' => $logo_data,
            'logoSrc' => $logo_data,
            'logoPemda' => $logo_data,
            'logoRsud' => $logo_data,
            'pihak1_ttd_data' => $pihak1_ttd_data,
            'pihak2_ttd_data' => $pihak2_ttd_data,
            'tanggal' => \Carbon\Carbon::parse($tanggalData)->locale('id')->translatedFormat('d F Y'),
            'tahun' => $tahun,
            'for_pdf' => true,
            'user' => $user,
            'isDirektur' => false,
            'status' => self::determineStatus($perjanjian),
            'tugas_fungsi' => '',
        ];
    }

    /**
     * Convert image file to base64 data URI
     *
     * @param string|null $path Path relatif dari public/ atau absolute path
     * @return string Base64 data URI atau empty string
     */
    public static function imageToBase64($path)
    {
        if (empty($path)) {
            return '';
        }

        // Jika sudah base64, return as is
        if (strpos($path, 'data:') === 0) {
            return $path;
        }

        $contents = null;
        $mimeType = 'image/png';

        // Handle remote URLs (Supabase, HTTP, HTTPS)
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            try {
                $context = stream_context_create([
                    'http' => [
                        'timeout' => 5,
                        'user_agent' => 'Mozilla/5.0'
                    ],
                    'https' => [
                        'timeout' => 5,
                        'user_agent' => 'Mozilla/5.0'
                    ]
                ]);

                $contents = @file_get_contents($path, false, $context);

                if ($contents !== false) {
                    $pathInfo = parse_url($path);
                    $fileExt = pathinfo($pathInfo['path'] ?? '', PATHINFO_EXTENSION);
                    $mimeType = self::getMimeTypeFromExtension($fileExt);
                }
            } catch (\Exception $e) {
                // Remote URL fetch failed
            }
        }

        // If remote fetch failed, try local paths
        if ($contents === null) {
            $candidates = [
                public_path($path),
                public_path('storage/' . ltrim($path, '/')),
                storage_path('app/public/' . ltrim($path, '/')),
                $path,
            ];

            foreach ($candidates as $filePath) {
                if (file_exists($filePath)) {
                    try {
                        $contents = file_get_contents($filePath);
                        if ($contents !== false) {
                            $mimeType = mime_content_type($filePath) ?: 'image/png';
                            break;
                        }
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        if ($contents !== false && $contents !== null) {
            $base64 = base64_encode($contents);
            return "data:{$mimeType};base64,{$base64}";
        }

        return '';
    }

    /**
     * Get MIME type from file extension
     *
     * @param string $ext
     * @return string
     */
    private static function getMimeTypeFromExtension($ext)
    {
        $mimeTypes = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];

        return $mimeTypes[strtolower($ext)] ?? 'image/png';
    }

    /**
     * Determine status of perjanjian
     *
     * @param Perjanjian $perjanjian
     * @return string
     */
    private static function determineStatus(Perjanjian $perjanjian)
    {
        if ($perjanjian->rejected === true || $perjanjian->rejected === 1 || $perjanjian->rejected === '1') {
            return 'ditolak';
        } elseif (!empty($perjanjian->pihak2_signature)) {
            return 'disetujui';
        }
        return 'menunggu';
    }

    /**
     * Generate filename untuk PDF
     * Format: Perjanjian_Kinerja_{nomor}_{nama}_{timestamp}.pdf
     *
     * @param Perjanjian $perjanjian
     * @return string
     */
    public static function generateFilename(Perjanjian $perjanjian)
    {
        $nomor = $perjanjian->nomor_perjanjian ?? ('PK-' . $perjanjian->id);
        $nomor = str_replace(['/', '\\'], '-', $nomor);
        
        $nama = $perjanjian->pihak1_name ?? 'User';
        $nama = str_replace(' ', '_', $nama);
        $nama = str_replace(['/', '\\'], '-', $nama);
        
        $timestamp = now('Asia/Jakarta')->format('Y-m-d_His');
        
        return "Perjanjian_Kinerja_{$nomor}_{$nama}_{$timestamp}.pdf";
    }
}
