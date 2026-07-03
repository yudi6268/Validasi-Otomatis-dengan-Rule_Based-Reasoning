<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perjanjian extends Model
{
    protected $table = 'perjanjians';
    protected $fillable = [
        'nomor_perjanjian',
        'user_id',
        'jabatan', 
        'judul', 
        'deskripsi', 
        'indikator', 
        'tahun', 
        'jenis', 
        'tanggal_pembuatan', 
        'change_mode', 
        'jabatan_pelaksana',
        'tugas_pelaksana',
        'fungsi_pelaksana',
        'sasaran', 
        'bobot', 
        'sumber_data', 
        'pihak1_name', 
        'pihak1_signature', 
        'pihak1_jabatan',
        'pihak1_pangkat',
        'pihak1_ttd',
        'pihak1_nip',
        'pihak2_name', 
        'pihak2_signature',
        'pihak2_ttd_path',
        'pihak2_jabatan',
        'pihak2_pangkat',
        'pihak2_nip',
        'location',
        'agreement_date',
        'tabelA',
        'tabelB',
        'tabelC',
        'tabelD',
        'status',
        'catatan_penolakan',
        'rejected',
        'rejection_reason',
        'pdf_url',
        'pdf_path'
    ];
    protected $casts = [
        'indikator' => 'array',
        'tabelA' => 'array',
        'tabelB' => 'array',
        'tabelC' => 'array',
        'tabelD' => 'array',
        'fungsi_pelaksana' => 'array',
        'tugas_pelaksana' => 'array',
        'rejected' => 'boolean',
    ];

    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'perjanjian_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}