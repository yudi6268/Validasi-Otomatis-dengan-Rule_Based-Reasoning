<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporans';

    protected $fillable = [
        'perjanjian_id',
        'periode',
        'tahun',
        'uraian_kegiatan',
        'sasaran',
        'bobot',
        'sumber_data',
        'pihak1_name',
        'pihak1_signature',
        'pihak2_name',
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
    ];

    protected $casts = [
        'tabelA' => 'array',
        'tabelB' => 'array',
        'tabelC' => 'array',
        'realisasi_tb1' => 'array',
        'realisasi_tb2' => 'array',
        'realisasi_tb3' => 'array',
        'realisasi_tb4' => 'array',
    ];

    public function perjanjian()
    {
        return $this->belongsTo(Perjanjian::class);
    }
}
