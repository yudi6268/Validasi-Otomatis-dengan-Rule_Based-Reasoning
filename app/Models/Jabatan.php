<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    
    protected $fillable = [
        'nama_jabatan',
        'tugas',
        'fungsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fungsi' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope: Active positions only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Relationship: Users with this position
     */
    public function users()
    {
        return $this->hasMany(User::class, 'jabatan', 'nama_jabatan');
    }
}
