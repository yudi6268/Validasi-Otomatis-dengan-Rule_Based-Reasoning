<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Perjanjian;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\Jabatan;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index(Request $request)
    {
        $activeSection = $request->query('section', 'dashboard');

        // CACHE EVERYTHING FOR 5 MINUTES (300 seconds)
        // because Admin dashboard is heavy and doesn't need to be down-to-the-millisecond realtime
        $dashboardData = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_data', 300, function() {
            $totalUsers = User::count();
            $totalPerjanjian = Perjanjian::count();
            $totalNotifications = Notification::count();

            $users = User::orderByDesc('created_at')->take(10)->get();
            $allUsers = collect(); // Deprecated for performance

            // Gunakan SQL Count agar tidak membebani memory dan koneksi
            $totalWaiting = Perjanjian::where(function($q) {
                $q->where(function($sub) {
                    $sub->whereNull('pihak2_signature')->orWhere('pihak2_signature', '');
                })->where(function($sub2) {
                    $sub2->whereNull('rejected')->orWhere('rejected', false);
                });
            })->count();
            
            $totalApproved = Perjanjian::where(function($q) {
                $q->whereNotNull('pihak2_signature')
                  ->where('pihak2_signature', '!=', '')
                  ->where(function($sub2) {
                      $sub2->whereNull('rejected')->orWhere('rejected', false);
                  });
            })->count();
            
            $totalRejected = Perjanjian::where('rejected', true)->count();
            
            $perjanjianByStatus = collect([
                'waiting' => $totalWaiting,
                'approved' => $totalApproved,
                'rejected' => $totalRejected
            ]);

            // Ambil tabelB saja untuk anggaran, memangkas transfer data 90%
            $budgetBySource = [];
            $perjanjiansTabelB = Perjanjian::select('tabelB')->whereNotNull('tabelB')->get();
            foreach ($perjanjiansTabelB as $perjanjian) {
                $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB, true);
                if (!empty($tabelB['sumber_dana']) && !empty($tabelB['anggaran'])) {
                    foreach ($tabelB['sumber_dana'] as $index => $sumber) {
                        $nilai = isset($tabelB['anggaran'][$index]) ? floatval(str_replace([',', '.'], '', $tabelB['anggaran'][$index])) : 0;
                        if (!isset($budgetBySource[$sumber])) {
                            $budgetBySource[$sumber] = 0;
                        }
                        $budgetBySource[$sumber] += $nilai;
                    }
                }
            }

            // Untuk modal dan recent, ambil kolom yang DIBUTUHKAN saja, limit 5 untuk performa
            $recentPerjanjian = Perjanjian::with('user:id,nama,jabatan')
                ->select('id', 'user_id', 'pihak1_name', 'pihak1_jabatan', 'tahun', 'created_at', 'rejected', 'pihak2_signature')
                ->latest()
                ->take(5)
                ->get();

            $allPerjanjianModal = $recentPerjanjian->map(function ($p) {
                if (!empty($p->rejected) && $p->rejected == true) {
                    $statusText = 'Ditolak';
                    $badgeClass = 'bg-danger';
                } elseif (!empty($p->pihak2_signature)) {
                    $statusText = 'Disetujui';
                    $badgeClass = 'bg-success';
                } else {
                    $statusText = 'Menunggu';
                    $badgeClass = 'bg-warning text-dark';
                }
                return [
                    'id'         => $p->id,
                    'nama'       => $p->pihak1_name ?? ($p->user->nama ?? 'N/A'),
                    'jabatan'    => $p->pihak1_jabatan ?? ($p->user->jabatan ?? '-'),
                    'tahun'      => $p->tahun ?? '-',
                    'tanggal'    => $p->created_at ? $p->created_at->format('d/m/Y') : '-',
                    'statusText' => $statusText,
                    'badgeClass' => $badgeClass,
                ];
            })->toArray();

            // Mengambil count data master untuk dashboard
            $totalPrograms = Program::count();
            $totalKegiatan = Kegiatan::count();
            $totalSubKegiatan = SubKegiatan::count();

            $allPrograms = [];
            $allKegiatan = [];
            $allSubKegiatan = [];

            $jabatanStats = collect(); // Deprecated for performance
            $totalJabatan = Jabatan::count();

            return compact(
                'totalUsers', 'totalPerjanjian', 'totalNotifications', 'totalPrograms', 'totalKegiatan', 'totalSubKegiatan',
                'users', 'allUsers', 'recentPerjanjian', 'perjanjianByStatus', 'jabatanStats', 'totalJabatan', 'allPrograms', 'allKegiatan', 'allSubKegiatan', 'allPerjanjianModal'
            );
        });
        
        extract($dashboardData);

        return view('admin.dashboard', compact(
            'activeSection',
            'totalUsers',
            'totalPerjanjian',
            'totalNotifications',
            'totalPrograms',
            'totalKegiatan',
            'totalSubKegiatan',
            'users',
            'allUsers',
            'recentPerjanjian',
            'perjanjianByStatus',
            'jabatanStats',
            'totalJabatan',
            'allPrograms',
            'allKegiatan',
            'allSubKegiatan',
            'allPerjanjianModal'
        ));
    }
}
