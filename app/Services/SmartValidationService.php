<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\Perjanjian;
use Illuminate\Support\Facades\Log;

/**
 * Smart Validation Service
 * 
 * Validasi cerdas untuk laporan kinerja dengan fitur:
 * - Validasi kelengkapan data
 * - Validasi konsistensi target vs realisasi
 * - Deteksi anomali data
 * - Saran perbaikan otomatis
 * - Scoring kualitas laporan
 */
class SmartValidationService
{
    /**
     * Validasi lengkap sebuah laporan
     * 
     * @param Laporan $laporan
     * @return array
     */
    public function validateLaporan(Laporan $laporan): array
    {
        $issues = [];
        $warnings = [];
        $suggestions = [];
        $score = 100;

        // 1. Validasi kelengkapan dasar
        $basicCheck = $this->validateBasicCompleteness($laporan);
        $issues = array_merge($issues, $basicCheck['issues']);
        $warnings = array_merge($warnings, $basicCheck['warnings']);
        $suggestions = array_merge($suggestions, $basicCheck['suggestions']);
        $score -= $basicCheck['scoreDeduction'];

        // 2. Validasi konsistensi target-realisasi
        $consistencyCheck = $this->validateTargetRealisasiConsistency($laporan);
        $issues = array_merge($issues, $consistencyCheck['issues']);
        $warnings = array_merge($warnings, $consistencyCheck['warnings']);
        $suggestions = array_merge($suggestions, $consistencyCheck['suggestions']);
        $score -= $consistencyCheck['scoreDeduction'];

        // 3. Deteksi anomali
        $anomalyCheck = $this->detectAnomalies($laporan);
        $issues = array_merge($issues, $anomalyCheck['issues']);
        $warnings = array_merge($warnings, $anomalyCheck['warnings']);
        $suggestions = array_merge($suggestions, $anomalyCheck['suggestions']);
        $score -= $anomalyCheck['scoreDeduction'];

        // 4. Validasi timeline
        $timelineCheck = $this->validateTimeline($laporan);
        $warnings = array_merge($warnings, $timelineCheck['warnings']);
        $suggestions = array_merge($suggestions, $timelineCheck['suggestions']);
        $score -= $timelineCheck['scoreDeduction'];

        // Ensure score tidak negatif
        $score = max(0, $score);

        return [
            'is_valid' => empty($issues),
            'score' => $score,
            'issues' => $issues,
            'warnings' => $warnings,
            'suggestions' => $suggestions,
            'summary' => $this->generateSummary($score, $issues, $warnings),
            'validated_at' => now()->toISOString(),
        ];
    }

    /**
     * Validasi kelengkapan dasar laporan
     */
    private function validateBasicCompleteness(Laporan $laporan): array
    {
        $issues = [];
        $warnings = [];
        $suggestions = [];
        $scoreDeduction = 0;

        // Cek apakah laporan memiliki uraian kegiatan
        if (empty($laporan->uraian_kegiatan)) {
            $issues[] = [
                'type' => 'missing_field',
                'field' => 'uraian_kegiatan',
                'message' => 'Uraian kegiatan belum diisi',
                'severity' => 'high',
                'fix' => 'Masukkan uraian kegiatan sesuai dengan perjanjian kinerja'
            ];
            $scoreDeduction += 15;
        }

        // Cek sasaran
        if (empty($laporan->sasaran)) {
            $issues[] = [
                'type' => 'missing_field',
                'field' => 'sasaran',
                'message' => 'Sasaran belum diisi',
                'severity' => 'high',
                'fix' => 'Masukkan sasaran kegiatan'
            ];
            $scoreDeduction += 10;
        }

        // Cek bobot
        if ($laporan->bobot === null || $laporan->bobot <= 0) {
            $issues[] = [
                'type' => 'missing_field',
                'field' => 'bobot',
                'message' => 'Bobot belum diisi atau tidak valid',
                'severity' => 'high',
                'fix' => 'Masukkan bobot nilai (0-100)'
            ];
            $scoreDeduction += 10;
        } elseif ($laporan->bobot > 100) {
            $issues[] = [
                'type' => 'invalid_value',
                'field' => 'bobot',
                'message' => 'Bobot tidak boleh lebih dari 100',
                'severity' => 'high',
                'fix' => 'Sesuaikan bobot menjadi maksimal 100'
            ];
            $scoreDeduction += 10;
        }

        // Cek sumber data
        if (empty($laporan->sumber_data)) {
            $warnings[] = [
                'type' => 'missing_field',
                'field' => 'sumber_data',
                'message' => 'Sumber data belum diisi',
                'severity' => 'medium',
                'fix' => 'Masukkan sumber data pendukung'
            ];
            $scoreDeduction += 5;
            $suggestions[] = [
                'type' => 'enhancement',
                'message' => 'Menambahkan sumber data akan meningkatkan kredibilitas laporan'
            ];
        }

        // Cek kelengkapan triwulan
        $triwulanFields = ['realisasi_tb1', 'realisasi_tb2', 'realisasi_tb3', 'realisasi_tb4'];
        $filledTriwulan = 0;
        
        foreach ($triwulanFields as $field) {
            $value = $laporan->$field;
            if (!empty($value)) {
                $filledTriwulan++;
            }
        }

        if ($filledTriwulan === 0) {
            $issues[] = [
                'type' => 'incomplete_data',
                'field' => 'triwulan',
                'message' => 'Belum ada realisasi triwulan yang diisi',
                'severity' => 'high',
                'fix' => 'Isi realisasi untuk minimal satu triwulan'
            ];
            $scoreDeduction += 20;
        } elseif ($filledTriwulan < 4) {
            $warnings[] = [
                'type' => 'incomplete_data',
                'field' => 'triwulan',
                'message' => "Hanya $filledTriwulan dari 4 triwulan yang sudah diisi",
                'severity' => 'medium',
                'fix' => 'Lengkapi realisasi untuk triwulan yang belum diisi'
            ];
            $scoreDeduction += 5 * (4 - $filledTriwulan);
            $suggestions[] = [
                'type' => 'completion',
                'message' => 'Lengkapi semua triwulan untuk evaluasi kinerja yang komprehensif'
            ];
        }

        // Cek kelengkapan bab laporan
        $babFields = [
            'bab_pelaksanaan' => 'Pelaksanaan',
            'bab_capaian' => 'Capaian',
            'bab_kendala' => 'Kendala',
            'bab_rencana' => 'Rencana Tindak Lanjut'
        ];

        $filledBabs = 0;
        foreach ($babFields as $field => $name) {
            if (!empty($laporan->$field)) {
                $filledBabs++;
            }
        }

        if ($filledBabs < 4) {
            $warnings[] = [
                'type' => 'incomplete_data',
                'field' => 'bab_laporan',
                'message' => "Hanya $filledBabs dari 4 bab laporan yang lengkap",
                'severity' => 'medium',
                'fix' => 'Lengkapi semua bab laporan: ' . implode(', ', array_values($babFields))
            ];
            $scoreDeduction += 5 * (4 - $filledBabs);
        }

        return [
            'issues' => $issues,
            'warnings' => $warnings,
            'suggestions' => $suggestions,
            'scoreDeduction' => $scoreDeduction,
        ];
    }

    /**
     * Validasi konsistensi antara target dan realisasi
     */
    private function validateTargetRealisasiConsistency(Laporan $laporan): array
    {
        $issues = [];
        $warnings = [];
        $suggestions = [];
        $scoreDeduction = 0;

        $perjanjian = $laporan->perjanjian;
        if (!$perjanjian) {
            return [
                'issues' => $issues,
                'warnings' => $warnings,
                'suggestions' => $suggestions,
                'scoreDeduction' => $scoreDeduction,
            ];
        }

        // Ambil target dari tabelA perjanjian
        $tabelA = $perjanjian->tabelA;
        if (empty($tabelA) || !is_array($tabelA)) {
            $warnings[] = [
                'type' => 'no_reference',
                'message' => 'Tidak ada data target dari perjanjian kinerja',
                'severity' => 'low',
            ];
            return [
                'issues' => $issues,
                'warnings' => $warnings,
                'suggestions' => $suggestions,
                'scoreDeduction' => $scoreDeduction,
            ];
        }

        // Cek setiap triwulan
        for ($i = 1; $i <= 4; $i++) {
            $realisasiField = 'realisasi_tb' . $i;
            $realisasi = $laporan->$realisasiField;

            if (empty($realisasi)) {
                continue;
            }

            // Decode JSON jika perlu
            if (is_string($realisasi)) {
                $realisasi = json_decode($realisasi, true);
            }

            if (!is_array($realisasi) || empty($realisasi['rows'])) {
                continue;
            }

            // Validasi setiap baris realisasi
            foreach ($realisasi['rows'] as $index => $row) {
                $rowNum = $row['row'] ?? $index + 1;
                
                // Cek apakah ada target untuk baris ini
                $target = $row['target'] ?? null;
                $realisasiValue = $row['realisasi'] ?? '';

                // Validasi: realisasi tidak boleh negatif
                if (is_numeric($realisasiValue) && $realisasiValue < 0) {
                    $issues[] = [
                        'type' => 'invalid_value',
                        'field' => $realisasiField . '.rows.' . $index . '.realisasi',
                        'message' => "Realisasi Triwulan $i baris $rowNum tidak boleh negatif",
                        'severity' => 'high',
                        'fix' => 'Koreksi nilai realisasi menjadi bilangan positif'
                    ];
                    $scoreDeduction += 5;
                }

                // Warning: realisasi melebihi target signifikan (>120%)
                if (is_numeric($target) && $target > 0 && is_numeric($realisasiValue)) {
                    $percentage = ($realisasiValue / $target) * 100;
                    
                    if ($percentage > 150) {
                        $issues[] = [
                            'type' => 'anomaly',
                            'field' => $realisasiField . '.rows.' . $index,
                            'message' => "Realisasi Triwulan $i baris $rowNum ($percentage% dari target) sangat tinggi - perlu verifikasi",
                            'severity' => 'medium',
                            'fix' => 'Verifikasi apakah data realisasi sudah benar'
                        ];
                        $scoreDeduction += 3;
                    } elseif ($percentage > 120) {
                        $warnings[] = [
                            'type' => 'unusual_value',
                            'field' => $realisasiField . '.rows.' . $index,
                            'message' => "Realisasi Triwulan $i baris $rowNum ($percentage% dari target) melebihi target",
                            'severity' => 'low',
                        ];
                    }
                }

                // Warning: realisasi kosong tapi ada target
                if (is_numeric($target) && $target > 0 && (empty($realisasiValue) || $realisasiValue === '')) {
                    $warnings[] = [
                        'type' => 'missing_data',
                        'field' => $realisasiField . '.rows.' . $index,
                        'message' => "Triwulan $i baris $rowNum: Target ada ($target) tapi realisasi kosong",
                        'severity' => 'medium',
                        'fix' => 'Masukkan nilai realisasi atau jelaskan mengapa kosong'
                    ];
                    $scoreDeduction += 2;
                }
            }
        }

        // Saran: hitung total pencapaian
        $totalTarget = 0;
        $totalRealisasi = 0;
        
        $triwulanFields = ['realisasi_tb1', 'realisasi_tb2', 'realisasi_tb3', 'realisasi_tb4'];
        foreach ($triwulanFields as $field) {
            $realisasi = $laporan->$field;
            if (!empty($realisasi) && is_array($realisasi = is_string($realisasi) ? json_decode($realisasi, true) : $realisasi)) {
                foreach ($realisasi['rows'] ?? [] as $row) {
                    if (isset($row['target']) && is_numeric($row['target'])) {
                        $totalTarget += $row['target'];
                    }
                    if (isset($row['realisasi']) && is_numeric($row['realisasi'])) {
                        $totalRealisasi += $row['realisasi'];
                    }
                }
            }
        }

        if ($totalTarget > 0) {
            $overallPercentage = ($totalRealisasi / $totalTarget) * 100;
            
            if ($overallPercentage < 50) {
                $suggestions[] = [
                    'type' => 'attention',
                    'message' => "Total pencapaian keseluruhan: " . round($overallPercentage, 1) . "% - Perlu perhatian serius"
                ];
            } elseif ($overallPercentage < 75) {
                $suggestions[] = [
                    'type' => 'improvement',
                    'message' => "Total pencapaian keseluruhan: " . round($overallPercentage, 1) . "% - Perlu peningkatan"
                ];
            } elseif ($overallPercentage >= 100) {
                $suggestions[] = [
                    'type' => 'achievement',
                    'message' => "Total pencapaian keseluruhan: " . round($overallPercentage, 1) . "% - Target tercapai! 🎉"
                ];
            }
        }

        return [
            'issues' => $issues,
            'warnings' => $warnings,
            'suggestions' => $suggestions,
            'scoreDeduction' => $scoreDeduction,
        ];
    }

    /**
     * Deteksi anomali dalam data
     */
    private function detectAnomalies(Laporan $laporan): array
    {
        $issues = [];
        $warnings = [];
        $suggestions = [];
        $scoreDeduction = 0;

        // Deteksi: pola realisasi yang tidak masuk akal
        $triwulanValues = [];
        for ($i = 1; $i <= 4; $i++) {
            $field = 'realisasi_tb' . $i;
            $value = $laporan->$field;
            
            if (!empty($value) && is_array($value = is_string($value) ? json_decode($value, true) : $value)) {
                $total = 0;
                foreach ($value['rows'] ?? [] as $row) {
                    if (isset($row['realisasi']) && is_numeric($row['realisasi'])) {
                        $total += $row['realisasi'];
                    }
                }
                $triwulanValues[$i] = $total;
            } else {
                $triwulanValues[$i] = 0;
            }
        }

        // Cek apakah ada triwulan yang melonjak drastis
        $nonZeroValues = array_filter($triwulanValues);
        if (count($nonZeroValues) >= 2) {
            $maxValue = max($nonZeroValues);
            $minValue = min($nonZeroValues);
            
            if ($minValue > 0 && $maxValue > ($minValue * 5)) {
                $warnings[] = [
                    'type' => 'anomaly',
                    'field' => 'triwulan_pattern',
                    'message' => 'Terdapat perbedaan signifikan antar triwulan - perlu verifikasi',
                    'severity' => 'medium',
                    'fix' => 'Pastikan data realisasi sesuai dengan kondisi реальный'
                ];
                $scoreDeduction += 5;
            }
        }

        // Deteksi: teks terlalu pendek untuk bab laporan
        $babFields = ['bab_pelaksanaan', 'bab_capaian', 'bab_kendala', 'bab_rencana'];
        foreach ($babFields as $field) {
            $value = $laporan->$field;
            if (!empty($value) && strlen(strip_tags($value)) < 20) {
                $warnings[] = [
                    'type' => 'insufficient_content',
                    'field' => $field,
                    'message' => ucfirst(str_replace('bab_', '', $field)) . ' terlalu pendek - kurang detail',
                    'severity' => 'low',
                    'fix' => 'Berikan penjelasan yang lebih detail'
                ];
                $scoreDeduction += 2;
            }
        }

        // Deteksi: karakter mencurigakan (potential data entry error)
        $suspiciousPatterns = [
            '/^[\s\d\.\,\-]+$/' => 'uraian_kegiatan',
            '/^(test|tes|TEST|TES|aaa|111|abc)$/i' => 'uraian_kegiatan',
        ];

        foreach ($suspiciousPatterns as $pattern => $field) {
            $value = $laporan->$field;
            if (!empty($value) && preg_match($pattern, $value)) {
                $issues[] = [
                    'type' => 'suspicious_data',
                    'field' => $field,
                    'message' => 'Terdapat pola data yang mencurigakan',
                    'severity' => 'medium',
                    'fix' => 'Periksa kembali data yang entered'
                ];
                $scoreDeduction += 10;
            }
        }

        return [
            'issues' => $issues,
            'warnings' => $warnings,
            'suggestions' => $suggestions,
            'scoreDeduction' => $scoreDeduction,
        ];
    }

    /**
     * Validasi timeline pelaporan
     */
    private function validateTimeline(Laporan $laporan): array
    {
        $warnings = [];
        $suggestions = [];
        $scoreDeduction = 0;

        $createdAt = $laporan->created_at;
        $updatedAt = $laporan->updated_at;

        // Cek apakah laporan jarang diupdate
        if ($createdAt && $updatedAt) {
            $daysDiff = $createdAt->diffInDays($updatedAt);
            
            if ($daysDiff > 30 && $laporan->realisasi_tb1) {
                $warnings[] = [
                    'type' => 'stale_data',
                    'field' => 'updated_at',
                    'message' => "Laporan tidak diupdate selama $daysDiff hari",
                    'severity' => 'low',
                    'fix' => 'Update laporan secara berkala'
                ];
                $scoreDeduction += 2;
            }
        }

        // Saran berdasarkan waktu
        $now = now();
        $bulan = (int) $now->format('m');
        
        if ($bulan >= 1 && $bulan <= 3) {
            $suggestions[] = [
                'type' => 'timing',
                'message' => 'Saat ini Triwulan 1 - pastikan realisasi sudah diisi'
            ];
        } elseif ($bulan >= 4 && $bulan <= 6) {
            $suggestions[] = [
                'type' => 'timing',
                'message' => 'Saat ini Triwulan 2 - evaluasi pencapaian Triwulan 1'
            ];
        } elseif ($bulan >= 7 && $bulan <= 9) {
            $suggestions[] = [
                'type' => 'timing',
                'message' => 'Saat ini Triwulan 3 - pertengahan tahun, evaluasi progres'
            ];
        } else {
            $suggestions[] = [
                'type' => 'timing',
                'message' => 'Saat ini Triwulan 4 - siapkan laporan akhir tahun'
            ];
        }

        return [
            'warnings' => $warnings,
            'suggestions' => $suggestions,
            'scoreDeduction' => $scoreDeduction,
        ];
    }

    /**
     * Generate ringkasan validasi
     */
    private function generateSummary(int $score, array $issues, array $warnings): string
    {
        $issueCount = count($issues);
        $warningCount = count($warnings);

        if ($score >= 90 && $issueCount === 0) {
            return "✅ Laporan sangat baik! Skor: $score/100";
        } elseif ($score >= 75 && $issueCount === 0) {
            return "✅ Laporan baik. Skor: $score/100";
        } elseif ($score >= 60 && $issueCount <= 2) {
            return "⚠️ Laporan cukup lengkap. Skor: $score/100 - Perbaiki $issueCount issue";
        } elseif ($score >= 40) {
            return "⚠️ Laporan perlu perbaikan. Skor: $score/100 - $issueCount issues, $warningCount warnings";
        } else {
            return "❌ Laporan belum lengkap. Skor: $score/100 - $issueCount issues harus diperbaiki";
        }
    }

    /**
     * Validasi cepat untuk preview (tanpa full analysis)
     * 
     * @param array $data
     * @return array
     */
    public function quickValidate(array $data): array
    {
        $issues = [];
        
        // Validasi required fields
        $requiredFields = ['uraian_kegiatan', 'sasaran', 'bobot'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $issues[] = "Field '$field' wajib diisi";
            }
        }

        // Validasi bobot
        if (isset($data['bobot'])) {
            if (!is_numeric($data['bobot']) || $data['bobot'] < 0 || $data['bobot'] > 100) {
                $issues[] = 'Bobot harus berupa angka antara 0-100';
            }
        }

        return [
            'is_valid' => empty($issues),
            'issues' => $issues,
        ];
    }
}