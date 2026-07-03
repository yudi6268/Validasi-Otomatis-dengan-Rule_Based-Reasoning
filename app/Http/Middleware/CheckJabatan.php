<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckJabatan
{
    /**
     * Handle an incoming request.
     * Middleware ini digunakan untuk memastikan hanya jabatan tertentu yang bisa mengakses route.
     */
    public function handle(Request $request, Closure $next, ...$allowedJabatan)
    {
        $user = Auth::user();

        // Jika user belum login
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Jika tidak ada jabatan yang diizinkan, atau jabatan user tidak termasuk dalam daftar yang diizinkan
        if (!empty($allowedJabatan) && !in_array($user->jabatan, $allowedJabatan)) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('dashboard.wadir')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}