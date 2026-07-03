<?php

namespace App\Services;

class RuleBasedReasoningService
{
    public static function normalizeIndicatorType($rawType): string
    {
        $normalized = strtolower(trim((string) ($rawType ?? 'positif')));
        $compact = preg_replace('/[^a-z]/', '', $normalized);

        if (
            in_array($normalized, ['negatif', 'negative', 'minus', '-', 'rn', 'n'], true)
            || in_array($compact, ['negatif', 'negative', 'minus', 'rn', 'n'], true)
            || str_contains($compact, 'negatif')
            || str_contains($compact, 'negative')
        ) {
            return 'negatif';
        }

        return 'positif';
    }

    public static function calculateCapaianPercentage($target, $realisasi): ?float
    {
        if ($realisasi === null || $realisasi === '') {
            return null;
        }

        $realisasiValue = is_numeric($realisasi) ? floatval($realisasi) : null;
        if ($realisasiValue === null) {
            return null;
        }

        $targetValue = is_numeric($target) ? floatval($target) : null;
        if ($targetValue === null || $targetValue <= 0.0) {
            // Sesuai kebutuhan bisnis: target 0 => capaian 0 agar tetap tercantum di tabel.
            return 0.0;
        }

        return round(($realisasiValue / $targetValue) * 100, 2);
    }

    public static function calculatePerformancePercentage($target, $realisasi, $indicatorType = 'positif'): ?float
    {
        $capaianPercentage = self::calculateCapaianPercentage($target, $realisasi);
        if ($capaianPercentage === null) {
            return null;
        }

        $targetValue = is_numeric($target) ? floatval($target) : null;
        if ($targetValue === null || $targetValue <= 0.0) {
            return 0.0;
        }

        $normalizedType = self::normalizeIndicatorType($indicatorType);
        if ($normalizedType === 'negatif') {
            $realisasiValue = is_numeric($realisasi) ? floatval($realisasi) : null;
            if ($realisasiValue === null) {
                return null;
            }

            // Rumus indikator negatif:
            // (Target - (Realisasi - Target)) / Target x 100
            $adjusted = $targetValue - ($realisasiValue - $targetValue);
            return round(($adjusted / $targetValue) * 100, 2);
        }

        return $capaianPercentage;
    }
}