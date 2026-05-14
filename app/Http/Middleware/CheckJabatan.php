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
            // Redirect sesuai jabatan user
            if ($user->jabatan === 'Direktur') {
                return redirect()->route('dashboard.direktur')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if (in_array($user->jabatan, ['Wakil Direktur Umum dan Keuangan', 'Wakil Direktur Pelayanan', 'Wakil Direktur Perencanaan dan Keuangan'])) {
                return redirect()->route('dashboard.wadir')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if (strpos($user->jabatan, 'Kabag') !== false || strpos($user->jabatan, 'Kepala Bagian') !== false ||
                strpos($user->jabatan, 'Kabid') !== false || strpos($user->jabatan, 'Kepala Bidang') !== false) {
                return redirect()->route('dashboard.kabag.kabid')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            if (strpos($user->jabatan, 'Kasi') !== false || strpos($user->jabatan, 'Kepala Seksi') !== false || 
                strpos($user->jabatan, 'Katimker') !== false || strpos($user->jabatan, 'Staf') !== false) {
                return redirect()->route('dashboard.katimker.staf')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}