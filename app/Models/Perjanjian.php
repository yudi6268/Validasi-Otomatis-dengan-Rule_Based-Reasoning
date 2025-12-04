<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perjanjian extends Model
{
    protected $table = 'perjanjians';
    protected $fillable = [
        'user_id',
        'jabatan', 
        'judul', 
        'deskripsi', 
        'indikator', 
        'tahun', 
        'jenis', 
        'tanggal_pembuatan', 
        'change_mode', 
        'sasaran', 
        'bobot', 
        'sumber_data', 
        'pihak1_name', 
        'pihak1_signature', 
        'pihak1_jabatan',
        'pihak1_ttd',
        'pihak1_nip',
        'pihak2_name', 
        'pihak2_signature', 
        'pihak2_jabatan',
        'pihak2_nip',
        'location',
        'agreement_date',
        'tabelA',
        'tabelB',
        'tabelC'
    ];
    protected $casts = [
        'indikator' => 'array',
        'tabelA' => 'array',
        'tabelB' => 'array',
        'tabelC' => 'array',
    ];
}