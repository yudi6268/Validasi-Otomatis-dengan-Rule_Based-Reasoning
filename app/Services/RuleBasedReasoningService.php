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

    public static function parseNumeric($value): ?float
    {
        if ($value === null || $value === '') return null;
        if (is_float($value) || is_int($value)) return (float) $value;
        
        $valStr = trim((string) $value);
        
        // Check if it's formatted in Indonesian locale (e.g. 1.000.000,50)
        if (strpos($valStr, '.') !== false && strpos($valStr, ',') !== false) {
            $valStr = str_replace('.', '', $valStr);
            $valStr = str_replace(',', '.', $valStr);
        } 
        // If it only contains comma, assume it's decimal separator (e.g. 1,5)
        elseif (strpos($valStr, ',') !== false) {
            $valStr = str_replace(',', '.', $valStr);
        }
        
        // Remove all characters except numbers, dot, and minus
        $valStr = preg_replace('/[^0-9\.\-]/', '', $valStr);
        
        // Check if there are multiple dots (e.g. 1.000.000) which means thousands separator was used but no decimal
        if (substr_count($valStr, '.') > 1) {
            $valStr = str_replace('.', '', $valStr);
        }
        
        return is_numeric($valStr) ? floatval($valStr) : null;
    }

    public static function calculateCapaianPercentage($target, $realisasi): ?float
    {
        $realisasiValue = self::parseNumeric($realisasi);

        if ($realisasiValue === null) {
            return null;
        }

        $targetValue = self::parseNumeric($target);
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

        $targetValue = self::parseNumeric($target);
        if ($targetValue === null || $targetValue <= 0.0) {
            return 0.0;
        }

        $normalizedType = self::normalizeIndicatorType($indicatorType);
        if ($normalizedType === 'negatif') {
            $realisasiValue = self::parseNumeric($realisasi);
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