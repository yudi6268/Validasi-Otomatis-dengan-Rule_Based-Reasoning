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

        // Get statistics
        $totalUsers = User::count();
        $totalPerjanjian = Perjanjian::count();
        // Program/Kegiatan/SubKegiatan totals removed from Admin dashboard
        // $totalTemplates = Template::count();
        $totalNotifications = Notification::count();

        // Get all users for modal and dashboard table preview
        $allUsers = User::orderBy('nama')->get();
        $users = User::latest()->take(10)->get();

        // Get recent perjanjian (last 5)
        $recentPerjanjian = Perjanjian::with('user')->latest()->take(5)->get();

        // Get program/kegiatan/sub-kegiatan data from MASTER tables (REALTIME)
        // Mengambil data dari tabel programs, kegiatan, sub_kegiatan bukan dari snapshot perjanjian
        $allPrograms = Program::where('is_active', true)
            ->orderBy('kode_program')
            ->get()
            ->map(function($program) {
                return [
                    'id' => $program->id,
                    'kode' => $program->kode_program,
                    'nama' => $program->nama_program,
                    'deskripsi' => $program->deskripsi
                ];
            })
            ->toArray();
        
        $allKegiatan = Kegiatan::where('is_active', true)
            ->with('program')
            ->orderBy('kode_kegiatan')
            ->get()
            ->map(function($kegiatan) {
                return [
                    'id' => $kegiatan->id,
                    'kode' => $kegiatan->kode_kegiatan,
                    'nama' => $kegiatan->nama_kegiatan,
                    'program' => $kegiatan->program ? $kegiatan->program->nama_program : '-',
                    'deskripsi' => $kegiatan->deskripsi
                ];
            })
            ->toArray();
        
        $allSubKegiatan = SubKegiatan::where('is_active', true)
            ->with('kegiatan.program')
            ->orderBy('kode_sub_kegiatan')
            ->get()
            ->map(function($sub) {
                return [
                    'id' => $sub->id,
                    'kode' => $sub->kode_sub_kegiatan,
                    'nama' => $sub->nama_sub_kegiatan,
                    'kegiatan' => $sub->kegiatan ? $sub->kegiatan->nama_kegiatan : '-',
                    'program' => $sub->kegiatan && $sub->kegiatan->program ? $sub->kegiatan->program->nama_program : '-',
                    'deskripsi' => $sub->deskripsi
                ];
            })
            ->toArray();
        
        // Count totals
        $totalPrograms = count($allPrograms);
        $totalKegiatan = count($allKegiatan);
        $totalSubKegiatan = count($allSubKegiatan);

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

        // Get ALL perjanjian for dashboard modal (with status)
        $allPerjanjianModal = Perjanjian::with('user')->latest()->get()->map(function ($p) {
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
            'allPrograms',
            'allKegiatan',
            'allSubKegiatan',
            'allPerjanjianModal'
        ));
    }
}
