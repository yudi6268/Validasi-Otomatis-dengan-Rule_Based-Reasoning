<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perjanjian;
// Program/Kegiatan/SubKegiatan are managed via Supabase; no admin UI references
use App\Models\Jabatan;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics
        $totalUsers = User::count();
        $totalPerjanjian = Perjanjian::count();
        // Program/Kegiatan/SubKegiatan totals removed from Admin dashboard
        // $totalTemplates = Template::count();
        $totalNotifications = Notification::count();

        // Get all users (for table)
        $users = User::latest()->take(10)->get();

        // Get recent perjanjian (last 5)
        $recentPerjanjian = Perjanjian::with('user')->latest()->take(5)->get();

        // Get program/kegiatan/sub-kegiatan data from approved perjanjian
        $programKegiatanData = [];
        $perjanjians = Perjanjian::with('user')
            ->whereNotNull('tabelC')
            ->whereNotNull('pihak2_signature')
            ->where(function($query) {
                $query->whereNull('rejected')->orWhere('rejected', false);
            })
            ->get();
        
        foreach ($perjanjians as $perjanjian) {
            $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC, true);
            
            if (!empty($tabelC['programs'])) {
                foreach ($tabelC['programs'] as $programIdx => $program) {
                    $programName = $program['name'] ?? 'Program ' . ($programIdx + 1);
                    $programAmount = floatval(str_replace([',', '.'], '', $program['amount'] ?? '0'));
                    
                    // Initialize program in array
                    if (!isset($programKegiatanData[$programName])) {
                        $programKegiatanData[$programName] = [
                            'total' => 0,
                            'kegiatan' => [],
                            'color' => $this->getRandomColor($programIdx)
                        ];
                    }
                    
                    $programKegiatanData[$programName]['total'] += $programAmount;
                    
                    // Process kegiatan
                    if (!empty($program['kegiatan']) && is_array($program['kegiatan'])) {
                        foreach ($program['kegiatan'] as $kegiatanIdx => $kegiatan) {
                            $kegiatanName = $kegiatan['name'] ?? 'Kegiatan ' . ($kegiatanIdx + 1);
                            $kegiatanAmount = floatval(str_replace([',', '.'], '', $kegiatan['amount'] ?? '0'));
                            
                            if (!isset($programKegiatanData[$programName]['kegiatan'][$kegiatanName])) {
                                $programKegiatanData[$programName]['kegiatan'][$kegiatanName] = [
                                    'total' => 0,
                                    'subKegiatan' => []
                                ];
                            }
                            
                            $programKegiatanData[$programName]['kegiatan'][$kegiatanName]['total'] += $kegiatanAmount;
                            
                            // Process sub-kegiatan
                            if (!empty($kegiatan['subKegiatan']) && is_array($kegiatan['subKegiatan'])) {
                                foreach ($kegiatan['subKegiatan'] as $subIdx => $sub) {
                                    $subName = $sub['name'] ?? 'Sub-Kegiatan ' . ($subIdx + 1);
                                    $subAmount = floatval(str_replace([',', '.'], '', $sub['amount'] ?? '0'));
                                    
                                    $programKegiatanData[$programName]['kegiatan'][$kegiatanName]['subKegiatan'][$subName] = $subAmount;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Format for bar chart: flatten to show top programs and kegiatan
        $chartData = [
            'programs' => [],
            'kegiatan' => [],
            'total' => 0
        ];
        
        foreach ($programKegiatanData as $programName => $programData) {
            $chartData['programs'][$programName] = $programData['total'];
            $chartData['total'] += $programData['total'];
            
            // Add top kegiatan
            foreach ($programData['kegiatan'] as $kegiatanName => $kegiatanData) {
                $key = $programName . ' > ' . $kegiatanName;
                $chartData['kegiatan'][$key] = $kegiatanData['total'];
            }
        }
        
        // Budget by Jabatan (kept for reference/other purposes)
        $budgetByJabatan = [];
        foreach ($perjanjians as $perjanjian) {
            if ($perjanjian->user && $perjanjian->user->jabatan) {
                $jabatan = $perjanjian->user->jabatan;
                $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC, true);
                
                if (!empty($tabelC['programs'])) {
                    foreach ($tabelC['programs'] as $program) {
                        if (!empty($program['amount'])) {
                            $anggaranValue = floatval(str_replace([',', '.'], '', $program['amount']));
                            
                            if (!isset($budgetByJabatan[$jabatan])) {
                                $budgetByJabatan[$jabatan] = 0;
                            }
                            $budgetByJabatan[$jabatan] += $anggaranValue;
                        }
                    }
                }
            }
        }

        // Count perjanjian by status (sama dengan user dashboard)
        // Status: waiting (belum signature & not rejected), approved (ada signature & not rejected), rejected (rejected=true)
        $allPerjanjians = Perjanjian::all();
        
        $totalWaiting = $allPerjanjians->filter(function($item) {
            return empty($item->pihak2_signature) && (empty($item->rejected) || $item->rejected == false);
        })->count();
        
        $totalApproved = $allPerjanjians->filter(function($item) {
            return !empty($item->pihak2_signature) && (empty($item->rejected) || $item->rejected == false);
        })->count();
        
        $totalRejected = $allPerjanjians->filter(function($item) {
            return !empty($item->rejected) && $item->rejected == true;
        })->count();
        
        $perjanjianByStatus = collect([
            'waiting' => $totalWaiting,
            'approved' => $totalApproved,
            'rejected' => $totalRejected
        ]);

        // Get budget data per source from tabelB
        $budgetBySource = [];
        $perjanjians = Perjanjian::whereNotNull('tabelB')->get();
        
        foreach ($perjanjians as $perjanjian) {
            $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB, true);
            
            if (!empty($tabelB['sumber_dana']) && !empty($tabelB['anggaran'])) {
                $sumberDana = $tabelB['sumber_dana'];
                $anggaran = $tabelB['anggaran'];
                
                foreach ($sumberDana as $index => $sumber) {
                    $nilai = isset($anggaran[$index]) ? floatval(str_replace([',', '.'], '', $anggaran[$index])) : 0;
                    
                    if (!isset($budgetBySource[$sumber])) {
                        $budgetBySource[$sumber] = 0;
                    }
                    $budgetBySource[$sumber] += $nilai;
                }
            }
        }

        // Get jabatan statistics
        $jabatanStats = Jabatan::withCount('users')->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPerjanjian',
            // Program/Kegiatan/SubKegiatan totals removed
            // 'totalTemplates',
            'totalNotifications',
            'users',
            'recentPerjanjian',
            'budgetByJabatan',
            'perjanjianByStatus',
            'budgetBySource',
            'jabatanStats',
            'programKegiatanData',
            'chartData'
        ));
    }
    
    /**
     * Helper function to get random color
     */
    private function getRandomColor($index)
    {
        $colors = [
            '#00B5A0', '#1E88E5', '#FF9800', '#9C27B0', '#4CAF50',
            '#F44336', '#00BCD4', '#E91E63', '#FFC107', '#795548',
            '#607D8B', '#2196F3', '#8BC34A', '#FF5722', '#673AB7'
        ];
        return $colors[$index % count($colors)];
    }
}
