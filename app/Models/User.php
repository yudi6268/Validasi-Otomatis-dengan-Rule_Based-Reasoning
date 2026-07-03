<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id_pegawai',
        'nama',
        'email',
        'nip',
        'jabatan',
        'fungsi',
        'tugas',
        'membawahi',
        'pangkat',
        'divisi',
        'role',
        'password',
        'foto_profil',
        'tanda_tangan',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user uses the shared non-admin dashboard shell
     */
    public function isWadir(): bool
    {
        return $this->role !== 'admin';
    }

    /**
     * Get perjanjian created by this user
     */
    public function perjanjians()
    {
        return $this->hasMany(Perjanjian::class);
    }
}