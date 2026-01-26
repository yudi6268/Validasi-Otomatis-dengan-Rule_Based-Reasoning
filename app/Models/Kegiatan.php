<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $fillable = [
        'program_id',
        'kode_kegiatan',
        'nama_kegiatan',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the program that owns this kegiatan
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get sub kegiatan for this kegiatan
     */
    public function subKegiatan()
    {
        return $this->hasMany(SubKegiatan::class);
    }
}
