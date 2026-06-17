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
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Log;



class PerjanjianController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    protected function resolveJabatanDataForUser(?string $jabatanName, $fallbackUser = null)
    {
        if (!$jabatanName) {
            return null;
        }

        $jabatanData = Jabatan::where('nama_jabatan', $jabatanName)->first()
            ?? Jabatan::where('nama_jabatan', 'LIKE', '%'.$jabatanName.'%')->first();

        if ($jabatanData) {
            return $jabatanData;
        }

        if ($fallbackUser) {
            return (object) [
                'nama_jabatan' => $jabatanName,
                'tugas' => $fallbackUser->tugas ?? null,
                'fungsi' => $fallbackUser->fungsi ?? null,
                'membawahi' => $fallbackUser->membawahi ?? null,
            ];
        }

        return null;
    }

    protected function decodeJsonArray($value)
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_values(array_filter($decoded, function ($item) {
                return $item !== null && $item !== '';
            }));
        }

        return null;
    }

    protected function normalizeTugasFungsiValue($value, bool $forceList = false)
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return array_values(array_filter($value, function ($item) {
                return $item !== null && $item !== '';
            }));
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value === '') {
                return null;
            }

            $decoded = $this->decodeJsonArray($value);
            if (is_array($decoded)) {
                return $decoded;
            }

            if ($forceList) {
                if (preg_match('/[\r\n;]/', $value)) {
                    $items = preg_split('/[\r\n;]+/', $value);
                    return array_values(array_filter(array_map('trim', $items), function ($item) {
                        return $item !== null && $item !== '';
                    }));
                }

                return [$value];
            }

            return $value;
        }

        return $value;
    }

    protected function resolveTugasFungsiForPerjanjian(Request $request, $authUser = null)
    {
        $jabatanPelaksana = $request->input('jabatan_pelaksana') ?: (isset($authUser->jabatan) ? $authUser->jabatan : null);
        $tugasPelaksana = $this->normalizeTugasFungsiValue($request->input('tugas_pelaksana'), true);
        $fungsiPelaksana = $this->normalizeTugasFungsiValue($request->input('fungsi_pelaksana'), true);

        $isTugasMissing = $tugasPelaksana === null || $tugasPelaksana === [];
        $isFungsiMissing = $fungsiPelaksana === null || $fungsiPelaksana === [];

        if ($isTugasMissing || $isFungsiMissing) {
            $jabatanData = $this->resolveJabatanDataForUser($jabatanPelaksana, $authUser);

            if ($jabatanData) {
                if ($isTugasMissing && isset($jabatanData->tugas)) {
                    $tugasPelaksana = $this->normalizeTugasFungsiValue($jabatanData->tugas);
                }
                if ($isFungsiMissing && isset($jabatanData->fungsi)) {
                    $fungsiPelaksana = $this->normalizeTugasFungsiValue($jabatanData->fungsi);
                }
            }

            if ($isTugasMissing && $authUser) {
                $tugasPelaksana = $this->normalizeTugasFungsiValue($authUser->tugas) ?? $tugasPelaksana;
            }
            if ($isFungsiMissing && $authUser) {
                $fungsiPelaksana = $this->normalizeTugasFungsiValue($authUser->fungsi) ?? $fungsiPelaksana;
            }
        }

        return [
            'jabatan_pelaksana' => $jabatanPelaksana,
            'tugas_pelaksana' => $tugasPelaksana,
            'fungsi_pelaksana' => $fungsiPelaksana,
        ];
    }

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
            $jabatanData = $this->resolveJabatanDataForUser($user->jabatan, $user);
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
                    'agreement_date' => optional($item->agreement_date)->format('d M Y'),
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
            $status = strtolower((string) ($item->status ?? ''));

            // Prioritas status faktual dari kolom legacy agar data lama/sinkronisasi tetap akurat.
            if (!empty($item->rejected) && (string) $item->rejected !== '0') {
                $status = 'ditolak';
            } elseif (!empty($item->pihak2_signature)) {
                $status = 'disetujui';
            } elseif ($status === '') {
                $status = !empty($item->pihak2_name) ? 'draft' : 'draft';
            }

            // Map dari status database ke count keys
            $statusMap = [
                'sent' => 'sent',
                'draft' => 'sent',
                'terkirim' => 'sent',
                'menunggu' => 'waiting',
                'waiting' => 'waiting',
                'disetujui' => 'approved',
                'approved' => 'approved',
                'ditolak' => 'rejected',
                'rejected' => 'rejected',
            ];

            $countKey = $statusMap[$status] ?? 'sent';
            
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

        if (is_array($tugas)) {
            $tugas = implode("\n", array_filter(array_map('trim', $tugas), 'strlen'));
        }
        if (is_array($fungsi)) {
            $fungsi = implode("\n", array_filter(array_map('trim', $fungsi), 'strlen'));
        }

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

        $tugasFungsiData = $this->resolveTugasFungsiForPerjanjian($request, auth()->user());

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

            'jabatan_pelaksana' => $tugasFungsiData['jabatan_pelaksana'],
            'tugas_pelaksana'   => $tugasFungsiData['tugas_pelaksana'],
            'fungsi_pelaksana'  => $tugasFungsiData['fungsi_pelaksana'],


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
                'indicator_type' => $request->a_indicator_type ?? [],
                'satuan'    => $request->a_satuan ?? [],
                'target'    => $request->a_target ?? [],
            ]),

            // TABEL C (was B, now B/TABELB)
            'tabelB' => json_encode([
                'sasaran'  => $request->c_sasaran ?? [],
                'indikator'=> $request->c_indikator ?? [],
                'indicator_type' => $request->c_indicator_type ?? [],
                'target'   => $request->c_target ?? [],
                'tw1'      => $request->c_tw1 ?? [],
                'tw2'      => $request->c_tw2 ?? [],
                'tw3'      => $request->c_tw3 ?? [],
                'tw4'      => $request->c_tw4 ?? [],
            ]),

            // TABEL D (HIERARCHICAL BUDGET)
            'tabelC' => $tabelC,
        ]);

        // Try to mirror the new record to Supabase for consistent reads
        try {
            $payload = [
                'local_id' => $save->id,
                'tahun' => $save->tahun,
                'nomor_perjanjian' => $save->nomor_perjanjian,
                'user_id' => $save->user_id,
                'pihak1_name' => $save->pihak1_name,
                'pihak1_jabatan' => $save->pihak1_jabatan,
                'pihak1_pangkat' => $save->pihak1_pangkat,
                'pihak1_nip' => $save->pihak1_nip,
                'pihak1_ttd' => $save->pihak1_ttd,
                'pihak2_name' => $save->pihak2_name,
                'pihak2_jabatan' => $save->pihak2_jabatan,
                'pihak2_pangkat' => $save->pihak2_pangkat,
                'pihak2_nip' => $save->pihak2_nip,
                'location' => $save->location,
                'agreement_date' => $save->agreement_date,
                'jabatan' => $save->jabatan,
                'jabatan_pelaksana' => $save->jabatan_pelaksana,
                'tugas_pelaksana' => $save->tugas_pelaksana,
                'fungsi_pelaksana' => $save->fungsi_pelaksana,
                'tabelA' => $save->tabelA,
                'tabelB' => $save->tabelB,
                'tabelC' => $save->tabelC,
                'status' => $save->status ?? 'menunggu',
            ];

            $res = $this->supabase->insert('perjanjians', [$payload]);
            if (empty($res['success'])) {
                Log::warning('Supabase insert failed for perjanjian local_id=' . $save->id . ': ' . ($res['error'] ?? 'unknown'));
            } else {
                Log::info('Supabase insert succeeded for perjanjian local_id=' . $save->id);
            }
        } catch (\Exception $e) {
            Log::warning('Supabase insert exception for perjanjian local_id=' . $save->id . ': ' . $e->getMessage());
        }

        $dashboardRoute = (auth()->check() && auth()->user()->isWadir())
            ? route('dashboard.wadir')
            : route('home', ['section' => 'dashboard']);

        return redirect($dashboardRoute)->with('success', 'Data perjanjian berhasil disimpan!');
    }


    // ==============================
    // EDIT
    // ==============================
    public function edit($id)
    {
        $perjanjian = Perjanjian::findOrFail($id);
        $user = auth()->user();

        $isOwner = $user && (int) $perjanjian->user_id === (int) $user->id;
        $isAdmin = $user && $user->role === 'admin';

        if (!$user || (!$isAdmin && !$isOwner)) {
            return redirect()->route('perjanjian.index')->with('error', 'Anda tidak memiliki akses untuk mengedit perjanjian ini.');
        }
        // Cek apakah perjanjian masih bisa diedit
        if (!empty($perjanjian->pihak2_signature)) {
            return redirect()->route('perjanjian.index')->with('error', 'Perjanjian sudah ditandatangani dan tidak dapat diedit.');
        }
        // Ambil data jabatan pelaksana (pihak1)
        $jabatanData = null;
        if ($perjanjian->pihak1_jabatan) {
            $jabatanData = $this->resolveJabatanDataForUser($perjanjian->pihak1_jabatan, $perjanjian->user);
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

        $isOwner = $user && (int) $perjanjian->user_id === (int) $user->id;
        $isAdmin = $user && $user->role === 'admin';

        if (!$user || (!$isAdmin && !$isOwner)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah perjanjian ini.');
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

        $tugasFungsiData = $this->resolveTugasFungsiForPerjanjian($request, $user);

        // Update data
        $perjanjian->update([
            'pihak2_name'       => $request->pihak2_name,
            'pihak2_jabatan'    => $request->pihak2_jabatan,
            'pihak2_nip'        => $request->pihak2_nip ?? $perjanjian->pihak2_nip,
            'location'          => $request->location ?? $perjanjian->location,
            'agreement_date'    => $request->agreement_date ?? $perjanjian->agreement_date,
            'jabatan'           => $request->pihak2_jabatan ?? $perjanjian->jabatan,
            'jabatan_pelaksana' => $tugasFungsiData['jabatan_pelaksana'],
            'tugas_pelaksana'   => $tugasFungsiData['tugas_pelaksana'],
            'fungsi_pelaksana'  => $tugasFungsiData['fungsi_pelaksana'],
            'pihak1_ttd'        => auth()->user()->tanda_tangan,
            
            // Reset status ke menunggu jika sebelumnya ditolak (kolom baru)
            'status'            => $statusBaru,
            'catatan_penolakan' => $catatanPenolakan,
            
            // Update kolom lama untuk backward compatibility
            'rejected'          => ($statusBaru === 'ditolak'),
            'rejection_reason'  => $catatanPenolakan,
            
            'tabelA' => json_encode([
                'sasaran'   => $request->a_sasaran ?? [],
                'indikator' => $request->a_indikator ?? [],
                'indicator_type' => $request->a_indicator_type ?? [],
                'satuan'    => $request->a_satuan ?? [],
                'target'    => $request->a_target ?? [],
            ]),
            
            'tabelB' => json_encode([
                'sasaran'   => $request->c_sasaran ?? [],
                'indikator' => $request->c_indikator ?? [],
                'indicator_type' => $request->c_indicator_type ?? [],
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

        // Mirror update to Supabase (use nomor_perjanjian as stable key)
        try {
            $payload = [
                'pihak2_name' => $perjanjian->pihak2_name,
                'pihak2_jabatan' => $perjanjian->pihak2_jabatan,
                'pihak2_nip' => $perjanjian->pihak2_nip,
                'location' => $perjanjian->location,
                'agreement_date' => $perjanjian->agreement_date,
                'jabatan' => $perjanjian->jabatan,
                'jabatan_pelaksana' => $perjanjian->jabatan_pelaksana,
                'tugas_pelaksana' => $perjanjian->tugas_pelaksana,
                'fungsi_pelaksana' => $perjanjian->fungsi_pelaksana,
                'pihak1_ttd' => $perjanjian->pihak1_ttd,
                'status' => $perjanjian->status,
                'catatan_penolakan' => $perjanjian->catatan_penolakan,
                'rejected' => $perjanjian->rejected,
                'rejection_reason' => $perjanjian->rejection_reason,
                'tabelA' => $perjanjian->tabelA,
                'tabelB' => $perjanjian->tabelB,
                'tabelC' => $perjanjian->tabelC,
            ];

            $filters = ['nomor_perjanjian' => 'eq.' . $perjanjian->nomor_perjanjian];
            $res = $this->supabase->update('perjanjians', $filters, $payload);
            if (empty($res['success'])) {
                Log::warning('Supabase update failed for perjanjian #' . $perjanjian->id . ': ' . ($res['error'] ?? 'unknown'));
            } else {
                Log::info('Supabase update succeeded for perjanjian #' . $perjanjian->id);
            }
        } catch (\Exception $e) {
            Log::warning('Supabase update exception for perjanjian #' . $perjanjian->id . ': ' . $e->getMessage());
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

        $backSource = $request->input('from');
        $redirectParams = ['status' => 'waiting'];
        if (!empty($backSource)) {
            $redirectParams['from'] = $backSource;
        }

        return redirect()->route('perjanjian.index', $redirectParams)
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
            $tugasFungsiData = $this->resolveTugasFungsiForPerjanjian($request, auth()->user());

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
                'jabatan_pelaksana' => $tugasFungsiData['jabatan_pelaksana'],
                'tugas_pelaksana'   => $tugasFungsiData['tugas_pelaksana'],
                'fungsi_pelaksana'  => $tugasFungsiData['fungsi_pelaksana'],
                'tabelA' => json_encode([
                    'sasaran'   => $request->a_sasaran ?? [],
                    'indikator' => $request->a_indikator ?? [],
                    'indicator_type' => $request->a_indicator_type ?? [],
                    'satuan'    => $request->a_satuan ?? [],
                    'target'    => $request->a_target ?? [],
                ]),
                'tabelB' => json_encode([
                    'sasaran'   => $request->c_sasaran ?? [],
                    'indikator' => $request->c_indikator ?? [],
                    'indicator_type' => $request->c_indicator_type ?? [],
                    'target'    => $request->c_target ?? [],
                    'tw1'       => $request->c_tw1 ?? [],
                    'tw2'       => $request->c_tw2 ?? [],
                    'tw3'       => $request->c_tw3 ?? [],
                    'tw4'       => $request->c_tw4 ?? [],
                ]),
                'tabelC' => $tabelC,
            ]);

                // Mirror to Supabase
                try {
                    $payload = [
                        'local_id' => $save->id,
                        'tahun' => $save->tahun,
                        'nomor_perjanjian' => $save->nomor_perjanjian,
                        'user_id' => $save->user_id,
                        'pihak1_name' => $save->pihak1_name,
                        'pihak1_jabatan' => $save->pihak1_jabatan,
                        'pihak1_pangkat' => $save->pihak1_pangkat,
                        'pihak1_nip' => $save->pihak1_nip,
                        'pihak1_ttd' => $save->pihak1_ttd,
                        'pihak2_name' => $save->pihak2_name,
                        'pihak2_jabatan' => $save->pihak2_jabatan,
                        'pihak2_pangkat' => $save->pihak2_pangkat,
                        'pihak2_nip' => $save->pihak2_nip,
                        'location' => $save->location,
                        'agreement_date' => $save->agreement_date,
                        'jabatan' => $save->jabatan,
                        'tabelA' => $save->tabelA,
                        'tabelB' => $save->tabelB,
                        'tabelC' => $save->tabelC,
                        'status' => $save->status ?? 'menunggu',
                    ];

                    $res = $this->supabase->insert('perjanjians', [$payload]);
                    if (empty($res['success'])) {
                        Log::warning('Supabase insert failed for perjanjian local_id=' . $save->id . ': ' . ($res['error'] ?? 'unknown'));
                    } else {
                        Log::info('Supabase insert succeeded for perjanjian local_id=' . $save->id);
                    }
                } catch (\Exception $e) {
                    Log::warning('Supabase insert exception for perjanjian local_id=' . $save->id . ': ' . $e->getMessage());
                }

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

            $isOwner = $user && (int) $perjanjian->user_id === (int) $user->id;
            $isAdmin = $user && $user->role === 'admin';

            if (!$user || (!$isAdmin && !$isOwner)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus perjanjian ini'
                ], 403);
            }

            if (!empty($perjanjian->pihak2_signature)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Perjanjian yang sudah disetujui tidak dapat dihapus'
                ], 422);
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