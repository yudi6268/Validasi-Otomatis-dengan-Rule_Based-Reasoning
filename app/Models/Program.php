<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
        'kode_program',
        'nama_program',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get kegiatan for this program
     */
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class);
    }
}
