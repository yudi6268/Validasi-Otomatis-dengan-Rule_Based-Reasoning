<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perjanjian;

class DashboardController extends Controller
{
    /**
     * Redirect user ke dashboard sesuai dengan jabatan mereka
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Redirect berdasarkan jabatan
        $jabatan = $user->jabatan;
        
        // Direktur
        if ($jabatan === 'Direktur') {
            return redirect()->route('dashboard.direktur');
        }
        
        // Wakil Direktur (Umum dan Pelayanan digabung)
        if ($jabatan === 'Wakil Direktur Umum dan Keuangan' || $jabatan === 'Wakil Direktur Pelayanan') {
            return redirect()->route('dashboard.wadir');
        }
        
        // Kepala Bagian dan Kepala Bidang (Kabag.Kabid digabung)
        if (strpos($jabatan, 'Kabag') !== false || strpos($jabatan, 'Kepala Bagian') !== false ||
            strpos($jabatan, 'Kabid') !== false || strpos($jabatan, 'Kepala Bidang') !== false) {
            return redirect()->route('dashboard.kabag.kabid');
        }
        
        // Katimker/Staf (dahulu Kasi)
        if (strpos($jabatan, 'Kasi') !== false || strpos($jabatan, 'Kepala Seksi') !== false) {
            return redirect()->route('dashboard.katimker.staf');
        }
        
        // Default ke home untuk staff dan lainnya
        return redirect()->route('home');
    }
    
    /**
     * Dashboard untuk Wakil Direktur (Umum dan Pelayanan digabung)
     */
    public function wadir()
    {
        $user = Auth::user();
        
        // Statistik perjanjian yang menunggu persetujuan dari Wadir
        $totalPerjanjian = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })->count();
        $perjanjianApproved = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })
                ->whereNotNull('pihak2_signature')
                ->where(function($q) {
                    $q->whereNull('rejected')->orWhere('rejected', false);
                })
                ->count();
        $perjanjianWaiting = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })
                ->whereNull('pihak2_signature')
                ->where(function($q) {
                    $q->whereNull('rejected')->orWhere('rejected', false);
                })
                ->count();
        $perjanjianRejected = Perjanjian::where(function($q) use ($user) {
                    $q->where('pihak2_name', $user->nama)
                      ->orWhere('pihak2_nip', $user->nip);
                })
                ->where('rejected', true)
                ->count();
        
        return view('dashboard.wadir', compact('totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected'));
    }
    
    /**
     * Dashboard untuk Kabag.Kabid (Kabag dan Kabid digabung)
     */
    public function kabagKabid()
    {
        $user = Auth::user();
        
        // Statistik perjanjian yang dibuat oleh user
        $totalPerjanjian = Perjanjian::where('user_id', $user->id)->count();
        $perjanjianApproved = Perjanjian::where('user_id', $user->id)
            ->whereNotNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')->orWhere('rejected', false);
            })
            ->count();
        $perjanjianWaiting = Perjanjian::where('user_id', $user->id)
            ->whereNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')->orWhere('rejected', false);
            })
            ->count();
        $perjanjianRejected = Perjanjian::where('user_id', $user->id)
            ->where('rejected', true)
            ->count();
        
        return view('dashboard.kabag-kabid', compact('totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected'));
    }
    
    /**
     * Dashboard untuk Katimker/Staf (dahulu Kasi)
     */
    public function katimkerStaf()
    {
        $user = Auth::user();
        
        // Statistik perjanjian yang dibuat oleh user
        $totalPerjanjian = Perjanjian::where('user_id', $user->id)->count();
        $perjanjianApproved = Perjanjian::where('user_id', $user->id)
            ->whereNotNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')->orWhere('rejected', false);
            })
            ->count();
        $perjanjianWaiting = Perjanjian::where('user_id', $user->id)
            ->whereNull('pihak2_signature')
            ->where(function($q) {
                $q->whereNull('rejected')->orWhere('rejected', false);
            })
            ->count();
        $perjanjianRejected = Perjanjian::where('user_id', $user->id)
            ->where('rejected', true)
            ->count();
        
        return view('dashboard.katimker-staf', compact('totalPerjanjian', 'perjanjianApproved', 'perjanjianWaiting', 'perjanjianRejected'));
    }
}
