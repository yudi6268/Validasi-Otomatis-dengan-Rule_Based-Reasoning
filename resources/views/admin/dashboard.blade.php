@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
@php $activeSection = $activeSection ?? 'dashboard'; @endphp

@if ($activeSection === 'profile')
<div class="container-fluid" style="max-width: 100%; overflow-x: hidden;">
    @include('dashboard.partials.profile-panel', [
        'title' => 'Profil Administrator',
        'description' => 'Profil admin sekarang ditampilkan sebagai panel internal agar tetap berada dalam shell dashboard admin.'
    ])
</div>
@else
<div class="container-fluid" style="max-width: 100%; overflow-x: hidden;">
    <!-- Alert Pending Users -->
    @php
        $pendingUsers = \App\Models\User::where('status', 'pending')->count();
    @endphp
    @if($pendingUsers > 0)
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian!</strong> Ada <strong>{{ $pendingUsers }}</strong> pengguna baru yang menunggu persetujuan.
            <a href="{{ route('admin.users.pending') }}" class="alert-link">Lihat sekarang →</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Dashboard: 3 Atas, 3 Bawah, Responsive & Konsisten -->
    <div class="row g-2 g-md-4 mb-3 mb-md-4">
    <!-- Atas -->
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="border-inline-start: 4px solid #00B5A0; background: linear-gradient(135deg, #E6F6F2, #CFF2E9); min-height: 120px;">
            <div class="icon mb-2" style="background: #00B5A0; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-users"></i>
            </div>
            <div style="font-size: 1rem; color: #00B5A0; font-weight: 700;">Total Pengguna</div>
            <div style="font-size: 1.3rem; color: #00B5A0; font-weight: 700;">{{ $totalUsers }}</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center"
             style="border-inline-start: 4px solid #1E88E5; background: linear-gradient(135deg, #E3F2FD, #BBDEFB); min-height: 120px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#perjanjianModal">
            <div class="icon mb-2" style="background: #1E88E5; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-file-contract"></i>
            </div>
            <div style="font-size: 1rem; color: #1E88E5; font-weight: 700;">Total Perjanjian</div>
            <div style="font-size: 1.3rem; color: #1E88E5; font-weight: 700;">{{ $totalPerjanjian }}</div>
            <div style="font-size: 0.75rem; color: #1E88E5; opacity: 0.8; margin-top: 4px;"><i class="fas fa-mouse-pointer"></i> Klik untuk detail</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="border-inline-start: 4px solid #FF9800; background: linear-gradient(135deg, #FFF3E0, #FFE0B2); min-height: 120px;">
            <div class="icon mb-2" style="background: #FF9800; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-briefcase"></i>
            </div>
            <div style="font-size: 1rem; color: #FF9800; font-weight: 700;">Total Jabatan</div>
            <div style="font-size: 1.3rem; color: #FF9800; font-weight: 700;">{{ $jabatanStats->count() }}</div>
        </div>
    </div>
</div>

<div class="row g-2 g-md-4">
    <!-- Card: Total Program -->
    <div class="col-md-4">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
             style="border-inline-start: 4px solid #9C27B0; background: linear-gradient(135deg, #F3E5F5, #E1BEE7); min-height: 140px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#programModal">
            <div class="icon mb-2" style="background: #9C27B0; color: white; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.8rem;">
                <i class="fas fa-folder-open"></i>
            </div>
            <div style="font-size: 1rem; color: #9C27B0; font-weight: 700;">Total Program</div>
            <div style="font-size: 1.8rem; color: #9C27B0; font-weight: 700;">{{ $totalPrograms }}</div>
        </div>
    </div>

    <!-- Card: Total Kegiatan -->
    <div class="col-md-4">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
             style="border-inline-start: 4px solid #FF5722; background: linear-gradient(135deg, #FBE9E7, #FFCCBC); min-height: 140px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#kegiatanModal">
            <div class="icon mb-2" style="background: #FF5722; color: white; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.8rem;">
                <i class="fas fa-tasks"></i>
            </div>
            <div style="font-size: 1rem; color: #FF5722; font-weight: 700;">Total Kegiatan</div>
            <div style="font-size: 1.8rem; color: #FF5722; font-weight: 700;">{{ $totalKegiatan }}</div>
        </div>
    </div>

    <!-- Card: Total Sub-Kegiatan -->
    <div class="col-md-4">
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
             style="border-inline-start: 4px solid #3F51B5; background: linear-gradient(135deg, #E8EAF6, #C5CAE9); min-height: 140px; cursor: pointer;"
             data-bs-toggle="modal" data-bs-target="#subKegiatanModal">
            <div class="icon mb-2" style="background: #3F51B5; color: white; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.8rem;">
                <i class="fas fa-list-ul"></i>
            </div>
            <div style="font-size: 1rem; color: #3F51B5; font-weight: 700;">Total Sub-Kegiatan</div>
            <div style="font-size: 1.8rem; color: #3F51B5; font-weight: 700;">{{ $totalSubKegiatan }}</div>
        </div>
    </div>
</div>

<div class="row g-2 g-md-4 mt-3">
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0" style="font-size: 1rem;"><i class="fas fa-users"></i> Pengguna</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div style="max-height: 280px; overflow-y: auto; overflow-x: hidden;">
                <table class="table table-hover table-sm" style="margin-bottom: 0;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th style="font-size: 0.9rem;">Nama</th>
                            <th style="font-size: 0.9rem;">Jabatan</th>
                            <th style="font-size: 0.9rem;">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td style="font-size: 0.9rem;"><strong>{{ Str::limit($user->nama, 25) }}</strong></td>
                                <td style="font-size: 0.9rem;">{{ Str::limit($user->jabatan ?? '-', 20) }}</td>
                                <td style="font-size: 0.9rem;">
                                    <span class="badge bg-primary">{{ $user->role }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted" style="font-size: 0.9rem;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endif

<!-- Modals for Program, Kegiatan, Sub-Kegiatan -->
<!-- Modal Program -->
<div class="modal fade" id="programModal" tabindex="-1" aria-labelledby="programModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #9C27B0; color: white;">
                <h5 class="modal-title" id="programModalLabel">
                    <i class="fas fa-folder-open"></i> Daftar Program ({{ $totalPrograms }})
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!empty($allPrograms))
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Kode</th>
                                    <th style="width: 50%;">Nama Program</th>
                                    <th style="width: 25%;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allPrograms as $index => $program)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge bg-primary">{{ $program['kode'] }}</span></td>
                                        <td><strong>{{ $program['nama'] }}</strong></td>
                                        <td><small class="text-muted">{{ $program['deskripsi'] ?? '-' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-folder-open fa-3x mb-3"></i>
                        <p>Belum ada data program</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Kegiatan -->
<div class="modal fade" id="kegiatanModal" tabindex="-1" aria-labelledby="kegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #FF5722; color: white;">
                <h5 class="modal-title" id="kegiatanModalLabel">
                    <i class="fas fa-tasks"></i> Daftar Kegiatan ({{ $totalKegiatan }})
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!empty($allKegiatan))
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Kode</th>
                                    <th style="width: 35%;">Nama Kegiatan</th>
                                    <th style="width: 25%;">Program</th>
                                    <th style="width: 20%;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allKegiatan as $index => $kegiatan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge bg-warning">{{ $kegiatan['kode'] }}</span></td>
                                        <td><strong>{{ $kegiatan['nama'] }}</strong></td>
                                        <td><small class="text-primary">{{ $kegiatan['program'] }}</small></td>
                                        <td><small class="text-muted">{{ $kegiatan['deskripsi'] ?? '-' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-tasks fa-3x mb-3"></i>
                        <p>Belum ada data kegiatan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Sub-Kegiatan -->
<div class="modal fade" id="subKegiatanModal" tabindex="-1" aria-labelledby="subKegiatanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #3F51B5; color: white;">
                <h5 class="modal-title" id="subKegiatanModalLabel">
                    <i class="fas fa-list-ul"></i> Daftar Sub-Kegiatan ({{ $totalSubKegiatan }})
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!empty($allSubKegiatan))
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 15%;">Kode</th>
                                    <th style="width: 30%;">Nama Sub-Kegiatan</th>
                                    <th style="width: 20%;">Kegiatan</th>
                                    <th style="width: 15%;">Program</th>
                                    <th style="width: 15%;">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allSubKegiatan as $index => $subKegiatan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge bg-info">{{ $subKegiatan['kode'] }}</span></td>
                                        <td><strong>{{ $subKegiatan['nama'] }}</strong></td>
                                        <td><small class="text-warning">{{ $subKegiatan['kegiatan'] }}</small></td>
                                        <td><small class="text-primary">{{ $subKegiatan['program'] }}</small></td>
                                        <td><small class="text-muted">{{ $subKegiatan['deskripsi'] ?? '-' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-list-ul fa-3x mb-3"></i>
                        <p>Belum ada data sub-kegiatan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Perjanjian -->
<div class="modal fade" id="perjanjianModal" tabindex="-1" aria-labelledby="perjanjianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: #1E88E5; color: white;">
                <h5 class="modal-title" id="perjanjianModalLabel">
                    <i class="fas fa-file-contract"></i> Daftar Perjanjian Kinerja ({{ count($allPerjanjianModal) }})
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('admin.perjanjian.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-cog"></i> Kelola Perjanjian
                    </a>
                </div>
                @if(!empty($allPerjanjianModal))
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="width:5%;">#</th>
                                    <th style="width:28%;">Nama Pembuat</th>
                                    <th style="width:28%;">Jabatan</th>
                                    <th style="width:10%;">Tahun</th>
                                    <th style="width:14%;">Tanggal</th>
                                    <th style="width:15%;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allPerjanjianModal as $index => $pj)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $pj['nama'] }}</strong></td>
                                        <td><small>{{ $pj['jabatan'] }}</small></td>
                                        <td>{{ $pj['tahun'] }}</td>
                                        <td><small>{{ $pj['tanggal'] }}</small></td>
                                        <td><span class="badge {{ $pj['badgeClass'] }}">{{ $pj['statusText'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-contract fa-3x mb-3"></i>
                        <p>Belum ada data perjanjian</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Jabatan & Perjanjian Tables -->
<div class="row g-4 mt-3">
    <!-- Jabatan Table -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="mb-0"><i class="fas fa-briefcase"></i> Daftar Jabatan</h5>
                <a href="{{ route('admin.jabatan.index') }}" class="btn btn-sm btn-primary">Kelola</a>
            </div>
            <div style="max-height: 350px; overflow-y: auto; overflow-x: hidden;">
                <table class="table table-hover table-sm" style="margin-bottom: 0;">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th style="width: 40%;">Nama Jabatan</th>
                            <th style="width: 30%;">Tugas</th>
                            <th style="width: 20%;" class="text-center">User</th>
                            <th style="width: 10%;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jabatanStats as $jabatan)
                            <tr>
                                <td><strong>{{ $jabatan->nama_jabatan }}</strong></td>
                                <td>
                                    @if($jabatan->tugas)
                                        {{ Str::limit($jabatan->tugas, 50) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $jabatan->users_count ?? 0 }}</span>
                                </td>
                                <td class="text-center">
                                    @if($jabatan->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada jabatan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Perjanjian -->
    <div class="col-md-6">
        <div class="data-table">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <h5 class="mb-0"><i class="fas fa-file-contract"></i> Perjanjian Terbaru</h5>
                <div class="input-group input-group-sm" style="max-width: 320px;">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input id="adminPerjanjianSearch" type="text" class="form-control" placeholder="Cari nama, jabatan, status, atau tanggal" aria-label="Cari perjanjian">
                </div>
            </div>
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table id="adminPerjanjianTable" class="table table-hover table-sm">
                    <thead style="position: sticky; top: 0; background: white; z-index: 1;">
                        <tr>
                            <th>Pembuat</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPerjanjian as $perjanjian)
                            <tr>
                                <td><strong>{{ $perjanjian->user->nama ?? 'N/A' }}</strong></td>
                                <td>{{ $perjanjian->user->jabatan ?? '-' }}</td>
                                <td>
                                    @php
                                        // Status logic sama dengan user dashboard
                                        if (!empty($perjanjian->rejected) && $perjanjian->rejected == true) {
                                            $status = 'rejected';
                                            $statusText = 'Ditolak';
                                            $badgeClass = 'bg-danger';
                                        } elseif (!empty($perjanjian->pihak2_signature)) {
                                            $status = 'approved';
                                            $statusText = 'Disetujui';
                                            $badgeClass = 'bg-success';
                                        } else {
                                            $status = 'waiting';
                                            $statusText = 'Menunggu';
                                            $badgeClass = 'bg-warning text-dark';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td>{{ $perjanjian->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
// Live search pada tabel perjanjian
const adminPerjanjianSearch = document.getElementById('adminPerjanjianSearch');
const adminPerjanjianTableBody = document.querySelector('#adminPerjanjianTable tbody');

function filterAdminPerjanjianTable() {
    if (!adminPerjanjianTableBody) return;
    const term = (adminPerjanjianSearch?.value || '').toLowerCase();
    adminPerjanjianTableBody.querySelectorAll('tr').forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

if (adminPerjanjianSearch) {
    adminPerjanjianSearch.addEventListener('input', filterAdminPerjanjianTable);
    filterAdminPerjanjianTable();
}
</script>
@endpush

@endsection
