<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perjanjian;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\Setting;
use App\Helpers\PdfHelper;



class PerjanjianController extends Controller
{

    // ==============================
    // SUBMIT PENOLAKAN PERJANJIAN
    // ==============================
    public function tolakSubmit(Request $request, $id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        // Validasi hanya direktur/pimpinan
        $isDirektur = false;
        if ($user && isset($user->jabatan)) {
            $jabatan = strtolower($user->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }
        if (!$isDirektur) {
            abort(403, 'Hanya direktur/pimpinan yang dapat menolak perjanjian');
        }
        // Validasi alasan
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        // Simpan alasan dan update status - GUNAKAN KOLOM BARU
        $perjanjian->status = 'ditolak';
        $perjanjian->catatan_penolakan = $request->rejection_reason;
        
        // Tetap isi kolom lama untuk backward compatibility
        $perjanjian->rejected = true;
        $perjanjian->rejection_reason = $request->rejection_reason;
        $perjanjian->save();
        // Redirect ke preview dengan notifikasi sukses
        return redirect()->route('perjanjian.print', $perjanjian->id)->with('success', 'Alasan penolakan berhasil dikirim.');
    }

    // ==============================
    // FORM PENOLAKAN PERJANJIAN
    // ==============================
    public function tolakForm($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        // Status dan role
        $isDirektur = false;
        if ($user && isset($user->jabatan)) {
            $jabatan = strtolower($user->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }
        // Hanya direktur/pimpinan yang boleh akses form penolakan
        if (!$isDirektur) {
            abort(403, 'Hanya direktur/pimpinan yang dapat menolak perjanjian');
        }
        return view('perjanjian.tolak', compact('perjanjian', 'user'));
    }
    // ==============================
    // CREATE
    // ==============================
    public function create()
    {
        // Ambil data jabatan user aktif
        $user = auth()->user();
        $jabatanData = null;
        if ($user && $user->jabatan) {
            $jabatanData = Jabatan::where('nama_jabatan', $user->jabatan)->first();
        }
        // Ambil user direktur yang benar (misal dr. Arma)
        $direktur = User::where('jabatan', 'like', '%Direktur%')
            ->where('nama', 'like', '%Arma%')
            ->first();
        if (!$direktur) {
            // fallback: ambil direktur pertama jika tidak ketemu nama Arma
            $direktur = User::where('jabatan', 'like', '%Direktur%')->first();
        }
        
        // Ambil data program, kegiatan, dan sub kegiatan untuk dropdown (diurutkan berdasarkan kode)
        // Include relasi untuk auto-populate
        $programs = Program::where('is_active', true)
            ->with(['kegiatan' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('kode_kegiatan')
                      ->with(['subKegiatan' => function($q) {
                          $q->where('is_active', true)->orderBy('kode_sub_kegiatan');
                      }]);
            }])
            ->orderBy('kode_program')
            ->get();
        
        $kegiatans = Kegiatan::where('is_active', true)->with('program')->orderBy('kode_kegiatan')->get();
        $subKegiatans = SubKegiatan::where('is_active', true)->with('kegiatan')->orderBy('kode_sub_kegiatan')->get();
        
        // Ambil tahun yang tersedia dari settings
        $availableYears = Setting::getAvailableYears();
        
        return view('perjanjian.create', compact('jabatanData', 'direktur', 'programs', 'kegiatans', 'subKegiatans', 'availableYears'));
    }
    // ==============================
    // INDEX
    // ==============================
    public function index()
    {
        $user = auth()->user();
        $query = Perjanjian::query();
        
        // Filter berdasarkan role user
        if ($user) {
            $isAdmin = $user->role === 'admin';
            $isDirektur = false;
            
            // Cek apakah user adalah direktur/pimpinan berdasarkan jabatan
            if (isset($user->jabatan)) {
                $jabatan = strtolower($user->jabatan);
                $isDirektur = strpos($jabatan, 'direktur') !== false || 
                             strpos($jabatan, 'wadir') !== false ||
                             strpos($jabatan, 'wakil direktur') !== false;
            }
            
            // Admin melihat semua perjanjian
            if (!$isAdmin) {
                if ($isDirektur) {
                    // Direktur/Pimpinan melihat perjanjian yang ditujukan kepada mereka
                    // Cek berdasarkan nama (pihak2_name) atau jabatan mereka
                    $query->where(function($q) use ($user) {
                        $q->where('pihak2_name', $user->nama)
                          ->orWhere('pihak2_jabatan', $user->jabatan)
                          ->orWhere('user_id', $user->id); // juga lihat perjanjian yang mereka buat sendiri
                    });
                } else {
                    // User biasa hanya melihat perjanjian mereka sendiri
                    $query->where('user_id', $user->id);
                }
            }
        }
        
        $query->orderBy('id', 'desc');

        // If AJAX request for filtered list, return JSON
        if (request()->ajax() || request()->wantsJson()) {
            $filter = request()->get('filter');

            $items = $query->get()->map(function($item) {
                // Gunakan kolom 'status' baru (menunggu, disetujui, ditolak, draft)
                $status = $item->status ?? 'waiting'; // default: menunggu
                
                // Map dari status database ke status untuk frontend
                $statusMap = [
                    'menunggu' => 'waiting',
                    'disetujui' => 'approved',
                    'ditolak' => 'rejected',
                    'draft' => 'sent',
                ];
                
                $frontendStatus = $statusMap[$status] ?? 'waiting';

                return [
                    'id' => $item->id,
                    'pihak1_name' => $item->pihak1_name,
                    'pihak2_name' => $item->pihak2_name,
                    'jabatan' => $item->jabatan,
                    'created_at' => optional($item->created_at)->format('d M Y'),
                    'status' => $frontendStatus,
                    'approved' => ($frontendStatus === 'approved'),
                    'pihak2_signature' => $item->pihak2_signature,
                ];
            });

            if ($filter && $filter !== 'all') {
                $items = $items->filter(function($i) use ($filter) {
                    return $i['status'] === $filter;
                })->values();
            } elseif ($filter === 'all') {
                // Filter untuk 'all' mengambil semua kecuali 'sent'
                $items = $items->filter(function($i) {
                    return in_array($i['status'], ['approved', 'rejected', 'waiting']);
                })->values();
            }

            return response()->json(['data' => $items]);
        }

        $items = $query->get();

        // compute counts per status for dashboard - GUNAKAN KOLOM STATUS BARU
        $counts = [
            'sent' => 0,
            'approved' => 0,
            'rejected' => 0,
            'waiting' => 0,
            'total' => 0,
        ];

        foreach ($items as $item) {
            $status = $item->status ?? 'menunggu';
            
            // Map dari status database ke count keys
            $statusMap = [
                'menunggu' => 'waiting',
                'disetujui' => 'approved',
                'ditolak' => 'rejected',
                'draft' => 'sent',
            ];
            
            $countKey = $statusMap[$status] ?? 'waiting';
            
            if (isset($counts[$countKey])) {
                $counts[$countKey]++;
            }
            $counts['total']++;
        }

        return view('perjanjian.index', compact('items', 'counts'));
    }

    // PRINT/VIEW PDF
    public function print($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        
        // Validasi akses: user hanya bisa melihat perjanjian mereka sendiri atau yang ditujukan kepada mereka
        if ($user) {
            $isAdmin = $user->role === 'admin';
            
            if (!$isAdmin) {
                $isDirektur = false;
                if (isset($user->jabatan)) {
                    $jabatan = strtolower($user->jabatan);
                    $isDirektur = strpos($jabatan, 'direktur') !== false || 
                                 strpos($jabatan, 'wadir') !== false ||
                                 strpos($jabatan, 'wakil direktur') !== false;
                }
                
                $canAccess = false;
                
                if ($isDirektur) {
                    // Direktur bisa akses jika perjanjian ditujukan kepada mereka atau mereka yang buat
                    $canAccess = ($perjanjian->user_id == $user->id) ||
                                ($perjanjian->pihak2_name == $user->nama) ||
                                ($perjanjian->pihak2_jabatan == $user->jabatan);
                } else {
                    // User biasa hanya bisa akses perjanjian mereka sendiri
                    $canAccess = ($perjanjian->user_id == $user->id);
                }
                
                if (!$canAccess) {
                    abort(403, 'Anda tidak memiliki akses ke perjanjian ini.');
                }
            }
        }
        
        \Log::debug('PRINT: tugas_pelaksana = ' . var_export($perjanjian->tugas_pelaksana, true));
        \Log::debug('PRINT: fungsi_pelaksana = ' . var_export($perjanjian->fungsi_pelaksana, true));
        \Log::debug('PRINT: rejected = ' . var_export($perjanjian->rejected, true) . ', rejection_reason = ' . var_export($perjanjian->rejection_reason, true));
        
        // Debug tabel data
        $tabelBRaw = $perjanjian->tabelB;
        $tabelCRaw = $perjanjian->tabelC;
        \Log::info("PRINT ID {$id}: TabelB = " . (empty($tabelBRaw) ? 'KOSONG' : (is_string($tabelBRaw) ? strlen($tabelBRaw) . ' chars' : 'array')));
        \Log::info("PRINT ID {$id}: TabelC = " . (empty($tabelCRaw) ? 'KOSONG' : (is_string($tabelCRaw) ? strlen($tabelCRaw) . ' chars' : 'array')));

        // Logo
        $logoSrc = asset('images/logo_pemda.png');
        $logoPemda = $logoSrc;
        $logoRsud = asset('images/logo_rsud.png');

        // Tanggal menggunakan agreement_date atau created_at (snapshot saat perjanjian dibuat)
        $tanggalData = $perjanjian->agreement_date ?? $perjanjian->created_at;
        $tanggal = \Carbon\Carbon::parse($tanggalData)->translatedFormat('d F Y');
        
        // Tahun dari tanggal perjanjian dibuat
        $tahun = \Carbon\Carbon::parse($tanggalData)->format('Y');
        
        // Ambil data user untuk mendapatkan pangkat yang mungkin kosong di perjanjian
        $pihak1User = \App\Models\User::where('nama', $perjanjian->pihak1_name)->first();
        $pihak2User = \App\Models\User::where('nama', $perjanjian->pihak2_name)->first();
        
        // Override pangkat jika kosong di perjanjian tapi ada di user
        if (empty($perjanjian->pihak1_pangkat) && $pihak1User && !empty($pihak1User->pangkat)) {
            $perjanjian->pihak1_pangkat = $pihak1User->pangkat;
        }
        if (empty($perjanjian->pihak2_pangkat) && $pihak2User && !empty($pihak2User->pangkat)) {
            $perjanjian->pihak2_pangkat = $pihak2User->pangkat;
        }

        // Gabungkan tugas & fungsi pelaksana (untuk preview)
        $tugas = $perjanjian->tugas_pelaksana ?? '';
        $fungsi = $perjanjian->fungsi_pelaksana ?? '';
        
        // Parse fungsi jika berbentuk JSON array
        if ($fungsi && is_string($fungsi)) {
            $fungsiArray = json_decode($fungsi, true);
            if (is_array($fungsiArray)) {
                $fungsi = implode("\n", $fungsiArray);
            }
        }
        
        $tugas_fungsi = '';
        if ($tugas && $fungsi) {
            $tugas_fungsi = "<b>Tugas:</b>\n" . $tugas . "\n<b>Fungsi:</b>\n" . $fungsi;
        } elseif ($tugas) {
            $tugas_fungsi = "<b>Tugas:</b>\n" . $tugas;
        } elseif ($fungsi) {
            $tugas_fungsi = "<b>Fungsi:</b>\n" . $fungsi;
        }

        // Tabel A/B/C decoding for print view (match PDF logic)
        $tabelA = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
        $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
        $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);

        // Normalisasi agar $tabelC['programs'] selalu ada (untuk preview dan print)
        if (!isset($tabelC['programs']) || !is_array($tabelC['programs']) || count($tabelC['programs']) === 0) {
            // Cek format flat (array program, anggaran, keterangan)
            if (isset($tabelC['program']) && is_array($tabelC['program'])) {
                $tabelC['programs'] = [];
                $count = max(count($tabelC['program']), count($tabelC['anggaran'] ?? []), count($tabelC['keterangan'] ?? []));
                for ($i = 0; $i < $count; $i++) {
                    $prog = $tabelC['program'][$i] ?? '';
                    $amount = isset($tabelC['anggaran'][$i]) && is_numeric($tabelC['anggaran'][$i]) ? $tabelC['anggaran'][$i] : 0;
                    $ket = $tabelC['keterangan'][$i] ?? '-';
                    if ($prog !== '' || $amount != 0 || $ket !== '-') {
                        $tabelC['programs'][] = [
                            'name' => $prog,
                            'amount' => $amount,
                            'source' => $ket,
                        ];
                    }
                }
            }
            // Jika format nested/hierarchical (misal: sudah ada 'programs' tapi kosong, atau format lain)
            elseif (isset($tabelC[0]) && is_array($tabelC[0]) && (isset($tabelC[0]['name']) || isset($tabelC[0]['program']))) {
                // Asumsikan array of program rows
                $tabelC['programs'] = [];
                foreach ($tabelC as $row) {
                    if (is_array($row)) {
                        $tabelC['programs'][] = [
                            'name' => $row['name'] ?? $row['program'] ?? '',
                            'amount' => $row['amount'] ?? $row['anggaran'] ?? 0,
                            'source' => $row['source'] ?? $row['keterangan'] ?? '-',
                        ];
                    }
                }
            }
            // Jika tidak ada data, biarkan kosong (Blade akan handle tampilan kosong)
        }

        // Deteksi apakah user adalah direktur/pimpinan
        $authUser = auth()->user();
        $isDirektur = false;
        if ($authUser && isset($authUser->jabatan)) {
            $jabatan = strtolower($authUser->jabatan);
            $isDirektur = strpos($jabatan, 'direktur') !== false || strpos($jabatan, 'pimpinan') !== false;
        }

        // GUNAKAN DATA SNAPSHOT DARI PERJANJIAN, BUKAN DATA REAL-TIME DARI USER
        if (empty($perjanjian->tugas_pelaksana) || empty($perjanjian->fungsi_pelaksana)) {
            $jabatanData = Jabatan::where('nama_jabatan', $perjanjian->pihak1_jabatan)->first();
            if ($jabatanData) {
                if (empty($perjanjian->tugas_pelaksana)) {
                    $perjanjian->tugas_pelaksana = $jabatanData->tugas;
                }
                if (empty($perjanjian->fungsi_pelaksana)) {
                    // Jabatan fungsi is already stored as JSON array in DB
                    $perjanjian->fungsi_pelaksana = $jabatanData->fungsi;
                }
            }
        }

        // Set status konsisten dari database
        $status = 'menunggu';
        if ($perjanjian->rejected === true || $perjanjian->rejected === 1 || $perjanjian->rejected === '1') {
            $status = 'ditolak';
        } elseif (!empty($perjanjian->pihak2_signature)) {
            $status = 'disetujui';
        }

        return view('perjanjian.print', compact(
            'perjanjian',
            'logoSrc',
            'logoPemda',
            'logoRsud',
            'tanggal',
            'tahun',
            'tugas_fungsi',
            'tabelA',
            'tabelB',
            'tabelC',
            'user',
            'isDirektur',
            'status'
        ) + ['for_pdf' => false]);
    }


    // ==============================
    // STORE
    // ==============================
    public function store(Request $request)
    {
        // ===== VALIDASI =====
        $request->validate([
            'pihak2_name'        => 'required|string',
            'pihak2_jabatan'     => 'required|string',

            // TAMBAHAN BARU
            'jabatan_pelaksana'  => 'nullable|string',
            'tugas_pelaksana'    => 'nullable|string',
            'fungsi_pelaksana'   => 'nullable|string',
        ]);

        // ===== CEK TANDA TANGAN PIHAK PERTAMA =====
        if (!auth()->user()->tanda_tangan) {
            return back()->with('error', 'Gagal menyimpan! TTD anda kosong.');
        }

        // ==========================
        // SIMPAN DATA PERJANJIAN
        // ==========================
        // Prepare tabelC: accept hierarchical_budget_json (string or array) or fall back to flat fields
        $tabelC = $request->hierarchical_budget_json ?? null;
        if ($tabelC && !is_string($tabelC)) {
            $tabelC = json_encode($tabelC);
        }
        if (!$tabelC) {
            $tabelC = json_encode([
                'program'     => $request->d_program ?? [],
                'anggaran'    => $request->d_anggaran ?? [],
                'keterangan'  => $request->d_keterangan ?? [],
            ]);
        }

        // Generate nomor_perjanjian (simple: PK-YYYYMMDD-<user_id>-<random>)
        $nomor_perjanjian = 'PK-' . date('Ymd') . '-' . (auth()->id() ?? 'X') . '-' . strtoupper(substr(uniqid(), -4));

        $tahun = date('Y');
        $save = Perjanjian::create([
            'tahun' => $tahun,
            'nomor_perjanjian'  => $nomor_perjanjian,
            'user_id'           => auth()->id() ?? null,
            'pihak1_name'       => auth()->user()->nama,
            'pihak1_jabatan'    => auth()->user()->jabatan,
            'pihak1_pangkat'    => auth()->user()->pangkat,
            'pihak1_nip'        => auth()->user()->nip,
            'pihak1_ttd'        => auth()->user()->tanda_tangan,

            'jabatan_pelaksana' => $request->jabatan_pelaksana,
            'tugas_pelaksana'   => $request->tugas_pelaksana,
            'fungsi_pelaksana'  => $request->fungsi_pelaksana,


            // PIHAK KEDUA
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,
            'pihak2_pangkat'    => $request->pihak2_pangkat ?? null,
            'pihak2_nip'        => $request->pihak2_nip ?? null,
            'location'          => $request->location ?? 'Pasuruan',
            'agreement_date'    => $request->agreement_date ?? now(),

            // LAINNYA: store pihak2_jabatan as 'jabatan' if present, otherwise fallback to authenticated user's jabatan
            'jabatan'           => $request->pihak2_jabatan ?? (auth()->user()->jabatan ?? null),

            // TABEL A
            'tabelA' => json_encode([
                'sasaran'   => $request->a_sasaran ?? [],
                'indikator' => $request->a_indikator ?? [],
                'satuan'    => $request->a_satuan ?? [],
                'target'    => $request->a_target ?? [],
            ]),

            // TABEL C (was B, now B/TABELB)
            'tabelB' => json_encode([
                'sasaran'  => $request->c_sasaran ?? [],
                'indikator'=> $request->c_indikator ?? [],
                'target'   => $request->c_target ?? [],
                'tw1'      => $request->c_tw1 ?? [],
                'tw2'      => $request->c_tw2 ?? [],
                'tw3'      => $request->c_tw3 ?? [],
                'tw4'      => $request->c_tw4 ?? [],
            ]),

            // TABEL D (HIERARCHICAL BUDGET)
            'tabelC' => $tabelC,
        ]);

        return redirect()->route('perjanjian.index')->with('success', 'Data perjanjian berhasil disimpan!');
    }


    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        
        // Validasi akses
        if ($user && $user->role !== 'admin') {
            // User biasa hanya bisa edit perjanjian mereka sendiri
            if ($perjanjian->user_id != $user->id) {
                return redirect()->route('perjanjian.index')->with('error', 'Anda tidak memiliki akses untuk mengedit perjanjian ini.');
            }
        }
        
        // Hanya direktur/pimpinan yang boleh edit
        if (!$user || !(stripos($user->jabatan, 'direktur') !== false || stripos($user->jabatan, 'pimpinan') !== false)) {
            return redirect()->route('perjanjian.index')->with('error', 'Hanya Direktur/Pimpinan yang dapat mengedit perjanjian.');
        }
        // Cek apakah perjanjian masih bisa diedit
        if (!empty($perjanjian->pihak2_signature)) {
            return redirect()->route('perjanjian.index')->with('error', 'Perjanjian sudah ditandatangani dan tidak dapat diedit.');
        }
        // Ambil data jabatan pelaksana (pihak1)
        $jabatanData = null;
        if ($perjanjian->pihak1_jabatan) {
            $jabatanData = \App\Models\Jabatan::where('nama_jabatan', $perjanjian->pihak1_jabatan)->first();
        }
        
        // Ambil data program, kegiatan, dan sub kegiatan untuk dropdown (diurutkan berdasarkan kode)
        // Include relasi untuk auto-populate
        $programs = Program::where('is_active', true)
            ->with(['kegiatan' => function($query) {
                $query->where('is_active', true)
                      ->orderBy('kode_kegiatan')
                      ->with(['subKegiatan' => function($q) {
                          $q->where('is_active', true)->orderBy('kode_sub_kegiatan');
                      }]);
            }])
            ->orderBy('kode_program')
            ->get();
        
        $kegiatans = Kegiatan::where('is_active', true)->with('program')->orderBy('kode_kegiatan')->get();
        $subKegiatans = SubKegiatan::where('is_active', true)->with('kegiatan')->orderBy('kode_sub_kegiatan')->get();
        
        // Ambil tahun yang tersedia dari settings
        $availableYears = Setting::getAvailableYears();
        
        return view('perjanjian.edit', compact('perjanjian', 'jabatanData', 'programs', 'kegiatans', 'subKegiatans', 'availableYears'));
    }

    // ==============================
    // UPDATE
    // ==============================
    public function update(Request $request, $id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();
        
        // Validasi akses
        if ($user && $user->role !== 'admin') {
            // User biasa hanya bisa update perjanjian mereka sendiri
            if ($perjanjian->user_id != $user->id) {
                return back()->with('error', 'Anda tidak memiliki akses untuk mengubah perjanjian ini.');
            }
        }
        
        // Hanya direktur/pimpinan yang boleh update
        if (!$user || !(stripos($user->jabatan, 'direktur') !== false || stripos($user->jabatan, 'pimpinan') !== false)) {
            return back()->with('error', 'Hanya Direktur/Pimpinan yang dapat mengubah perjanjian.');
        }
        // Cek apakah perjanjian masih bisa diedit
        if (!empty($perjanjian->pihak2_signature)) {
            return back()->with('error', 'Perjanjian sudah ditandatangani dan tidak dapat diedit.');
        }
        
        // Validasi
        $request->validate([
            'pihak2_name'        => 'required|string',
            'pihak2_jabatan'     => 'required|string',

            // TAMBAHAN
            'jabatan_pelaksana'  => 'nullable|string',
            'tugas_pelaksana'    => 'nullable|string',
            'fungsi_pelaksana'   => 'nullable|string',
        ]);

        // Prepare tabelC from hierarchical-budget-json (Tabel D input)
        $tabelC = $request->hierarchical_budget_json ?? null;
        if ($tabelC && !is_string($tabelC)) {
            $tabelC = json_encode($tabelC);
        }
        if (!$tabelC || $tabelC === '[]' || $tabelC === 'null') {
            // Fallback to old flat format if hierarchical empty
            $tabelC = json_encode([
                'program'     => $request->d_program ?? [],
                'anggaran'    => $request->d_anggaran ?? [],
                'keterangan'  => $request->d_keterangan ?? [],
            ]);
        }
        
        \Log::info("UPDATE Perjanjian #{$id}: TabelC size = " . strlen($tabelC) . " bytes");

        // Cek status sebelum update - jika ditolak, ubah ke menunggu
        $statusLama = $perjanjian->status ?? 'menunggu';
        $statusBaru = ($statusLama === 'ditolak') ? 'menunggu' : $statusLama;
        $catatanPenolakan = ($statusLama === 'ditolak') ? null : $perjanjian->catatan_penolakan;
        
        \Log::info("UPDATE PERJANJIAN #{$perjanjian->id}: Status lama = '{$statusLama}', Status baru = '{$statusBaru}'");

        // Update data
        $perjanjian->update([
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,
            'pihak2_nip'        => $request->pihak2_nip ?? $perjanjian->pihak2_nip,
            'location'          => $request->location ?? $perjanjian->location,
            'agreement_date'    => $request->agreement_date ?? $perjanjian->agreement_date,
            'jabatan'           => $request->pihak2_jabatan ?? $perjanjian->jabatan,
            'jabatan_pelaksana' => $request->jabatan_pelaksana,
            'tugas_pelaksana'   => $request->tugas_pelaksana,
            'fungsi_pelaksana'  => $request->fungsi_pelaksana,
            
            // Reset status ke menunggu jika sebelumnya ditolak (kolom baru)
            'status'            => $statusBaru,
            'catatan_penolakan' => $catatanPenolakan,
            
            // Update kolom lama untuk backward compatibility
            'rejected'          => ($statusBaru === 'ditolak'),
            'rejection_reason'  => $catatanPenolakan,
            
            'tabelA' => json_encode([
                'sasaran'   => $request->a_sasaran ?? [],
                'indikator' => $request->a_indikator ?? [],
                'satuan'    => $request->a_satuan ?? [],
                'target'    => $request->a_target ?? [],
            ]),
            
            'tabelB' => json_encode([
                'sasaran'   => $request->c_sasaran ?? [],
                'indikator' => $request->c_indikator ?? [],
                'target'    => $request->c_target ?? [],
                'tw1'       => $request->c_tw1 ?? [],
                'tw2'       => $request->c_tw2 ?? [],
                'tw3'       => $request->c_tw3 ?? [],
                'tw4'       => $request->c_tw4 ?? [],
            ]),
            
            'tabelC' => $tabelC,
        ]);
        
        // Refresh data dari database untuk memastikan
        $perjanjian->refresh();
        
        // Log perubahan status untuk debugging
        \Log::info("UPDATE SUKSES - Perjanjian #{$perjanjian->id}: Status akhir di database = '{$perjanjian->status}', Catatan = " . ($perjanjian->catatan_penolakan ? 'ada' : 'null'));
        
        if ($statusLama === 'ditolak') {
            \Log::info("✓ Status berhasil diubah dari 'ditolak' ke '{$perjanjian->status}'");
        }
        
        // Return JSON when requested via fetch/AJAX
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data perjanjian berhasil diperbarui!',
                'id' => $perjanjian->id,
                'status' => $statusBaru,
            ]);
        }

        $successMessage = 'Data perjanjian berhasil diperbarui!';
        if ($statusLama === 'ditolak') {
            $successMessage .= ' Status perjanjian dikembalikan ke menunggu persetujuan.';
        }

        return redirect()->route('perjanjian.index')
            ->with('success', $successMessage);
    }


    // ==============================
    // EXPORT TO PDF
    // ==============================
    public function exportPdf(string $id)
    {
        // Cari data perjanjian by ID
        $perjanjian = Perjanjian::findOrFail($id);

        // Generate PDF using Snappy (Split & Merge approach)
        $pdfContent = PdfHelper::generatePerjanjianSnappy($perjanjian);
        
        // Setup filename
        $fileName = PdfHelper::generateFilename($perjanjian);

        // Check if output is raw content (string) or Snappy object
        if (is_string($pdfContent)) {
             return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        }

        // Fallback or explicit object return
        return $pdfContent->download($fileName);
    }   

    
     // ==============================
    // SAVE TO SUPABASE & RETURN JSON
    // ==============================
    public function savePerjanjian(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'pihak2_name'   => 'required|string',
            'pihak2_jabatan' => 'required|string',
        ]);

        // CEK TANDA TANGAN
        if (!auth()->user()->tanda_tangan) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan tidak ditemukan. Silakan atur di menu profil.'
            ], 422);
        }

        try {
            // SIMPAN KE DATABASE (Supabase)
            // Normalize tabelC payload
            $tabelC = $request->hierarchical_budget_json ?? null;
            if ($tabelC && !is_string($tabelC)) {
                $tabelC = json_encode($tabelC);
            }
            if (!$tabelC) {
                $tabelC = json_encode([
                    'program'     => $request->d_program ?? [],
                    'anggaran'    => $request->d_anggaran ?? [],
                    'keterangan'  => $request->d_keterangan ?? [],
                ]);
            }

            // Generate nomor_perjanjian (simple: PK-YYYYMMDD-<user_id>-<random>)
            $nomor_perjanjian = 'PK-' . date('Ymd') . '-' . (auth()->id() ?? 'X') . '-' . strtoupper(substr(uniqid(), -4));

            $tahun = date('Y');
            $save = Perjanjian::create([
                'tahun' => $tahun,
                'nomor_perjanjian'  => $nomor_perjanjian,
                'user_id'           => auth()->id() ?? null,
                'pihak1_name'       => auth()->user()->nama,
                'pihak1_jabatan'    => auth()->user()->jabatan,
                'pihak1_pangkat'    => auth()->user()->pangkat,
                'pihak1_nip'        => auth()->user()->nip,
                'pihak1_ttd'        => auth()->user()->tanda_tangan,
                'pihak2_name'       => $request->pihak2_name,
                'pihak2_jabatan'    => $request->pihak2_jabatan,
                'pihak2_nip'        => $request->pihak2_nip ?? null,
                'location'          => $request->location ?? 'Pasuruan',
                'agreement_date'    => $request->agreement_date ?? now(),
                'jabatan'           => $request->pihak2_jabatan ?? (auth()->user()->jabatan ?? null),
                'tabelA' => json_encode([
                    'sasaran'   => $request->a_sasaran ?? [],
                    'indikator' => $request->a_indikator ?? [],
                    'satuan'    => $request->a_satuan ?? [],
                    'target'    => $request->a_target ?? [],
                ]),
                'tabelB' => json_encode([
                    'sasaran'   => $request->c_sasaran ?? [],
                    'indikator' => $request->c_indikator ?? [],
                    'target'    => $request->c_target ?? [],
                    'tw1'       => $request->c_tw1 ?? [],
                    'tw2'       => $request->c_tw2 ?? [],
                    'tw3'       => $request->c_tw3 ?? [],
                    'tw4'       => $request->c_tw4 ?? [],
                ]),
                'tabelC' => $tabelC,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data perjanjian berhasil disimpan ke Supabase!',
                'id' => $save->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==============================
    // DELETE PERJANJIAN
    // ==============================
    public function destroy($id)
    {
        try {
            \Log::info("Attempting to delete perjanjian with ID: " . $id);
            
            $perjanjian = Perjanjian::findOrFail($id);
            $user = auth()->user();
            
            // Validasi akses: hanya pembuat perjanjian atau admin yang bisa hapus
            if ($user && $user->role !== 'admin') {
                if ($perjanjian->user_id != $user->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk menghapus perjanjian ini'
                    ], 403);
                }
            }
            
            \Log::info("Perjanjian found, deleting...");
            
            // Since migration has onDelete('set null'), we can safely delete
            $perjanjian->delete();
            
            \Log::info("Perjanjian deleted successfully");
            
            return response()->json([
                'success' => true,
                'message' => 'Perjanjian berhasil dihapus'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error("Perjanjian not found: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Perjanjian tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            \Log::error("Error deleting perjanjian: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus perjanjian: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==============================
    // GET USER BY JABATAN (API)
    // ==============================
    public function getUserByJabatan($jabatan)
    {
        $user = \App\Models\User::where('jabatan', $jabatan)->first();
        
        if ($user) {
            return response()->json([
                'success' => true,
                'data' => [
                    'nama' => $user->nama,
                    'nip' => $user->nip,
                    'jabatan' => $user->jabatan,
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User dengan jabatan tersebut tidak ditemukan'
        ], 404);
    }

    // ==============================
    // GET KEGIATAN BY PROGRAM ID (API)
    // ==============================
    public function getKegiatanByProgram($programId)
    {
        $kegiatans = Kegiatan::where('program_id', $programId)
            ->where('is_active', true)
            ->orderBy('nama_kegiatan')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $kegiatans
        ]);
    }

    // ==============================
    // GET SUB KEGIATAN BY KEGIATAN ID (API)
    // ==============================
    public function getSubKegiatanByKegiatan($kegiatanId)
    {
        $subKegiatans = SubKegiatan::where('kegiatan_id', $kegiatanId)
            ->where('is_active', true)
            ->orderBy('nama_sub_kegiatan')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $subKegiatans
        ]);
    }
}