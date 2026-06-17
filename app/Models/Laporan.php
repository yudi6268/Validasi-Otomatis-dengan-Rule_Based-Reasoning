<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $fillable = [
        'user_id',
        'perjanjian_id',
        'periode',
        'tahun',
        'uraian_kegiatan',
        'sasaran',
        'bobot',
        'sumber_data',
        'pihak1_name',
        'pihak1_jabatan',
        'pihak1_signature',
        'pihak2_name',
        'pihak2_jabatan',
        'pihak2_signature',
        'jabatan',
        'tabelA',
        'tabelB',
        'tabelC',
        'bab_pelaksanaan',
        'bab_capaian',
        'bab_kendala',
        'bab_rencana',
        'tanggapan_pimpinan',
        'triwulan_aktif',
        'kesimpulan',
        'realisasi_tb1',
        'realisasi_tb2',
        'realisasi_tb3',
        'realisasi_tb4',
        'validation_results',
        'validation_timestamp',
    ];

    protected $casts = [
        'tabelA' => 'array',
        'tabelB' => 'array',
        'tabelC' => 'array',
        'realisasi_tb1' => 'array',
        'realisasi_tb2' => 'array',
        'realisasi_tb3' => 'array',
        'realisasi_tb4' => 'array',
        'validation_results' => 'array',
    ];

    public function getValidationResults(): array
    {
        $results = $this->validation_results;
        if (is_string($results)) {
            $results = json_decode($results, true);
        }

        return is_array($results) ? $results : [];
    }

    public function getValidationResult(?int $tw = null): ?array
    {
        if ($tw === null) {
            $tw = $this->triwulan_aktif;
        }

        if (!$tw) {
            return null;
        }

        $results = $this->getValidationResults();
        if (isset($results[$tw])) {
            return $results[$tw];
        }

        if (isset($results['tw_' . $tw])) {
            return $results['tw_' . $tw];
        }

        return null;
    }

    public function hasValidationForTriwulan(?int $tw = null): bool
    {
        return $this->getValidationResult($tw) !== null;
    }

    public function perjanjian()
    {
        return $this->belongsTo(Perjanjian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
