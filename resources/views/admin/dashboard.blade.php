@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="container-fluid" style="max-width: 100%; overflow-x: hidden;">
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
        <div class="stat-card w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="border-inline-start: 4px solid #1E88E5; background: linear-gradient(135deg, #E3F2FD, #BBDEFB); min-height: 120px;">
            <div class="icon mb-2" style="background: #1E88E5; color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 1.5rem;">
                <i class="fas fa-file-contract"></i>
            </div>
            <div style="font-size: 1rem; color: #1E88E5; font-weight: 700;">Total Perjanjian</div>
            <div style="font-size: 1.3rem; color: #1E88E5; font-weight: 700;">{{ $totalPerjanjian }}</div>
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
    <!-- Total Anggaran - Pie Chart -->
    <div class="col-md-6">
        <div class="data-table" style="background: white; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,153,112,0.1);">
            <h5 class="mb-4" style="color: #1B2A41;"><i class="fas fa-chart-pie"></i> Total Anggaran</h5>
            <div style="position: relative; height: 280px;">
                <canvas id="budgetByJabatanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Pengguna Table -->
    <div class="col-md-6">
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

<!-- Program / Kegiatan / Sub-Kegiatan - Hierarchical List -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="data-table" style="background: white; border-radius: 14px; box-shadow: 0 4px 12px rgba(0,153,112,0.1);">
            <h5 class="mb-3" style="color: #1B2A41;"><i class="fas fa-list"></i> Program / Kegiatan / Sub-Kegiatan</h5>
            <div style="max-height: 400px; overflow-y: auto; overflow-x: hidden;">
                @php
                    function format_rp($v) { return 'Rp ' . number_format((float)$v, 0, ',', '.'); }
                @endphp
                @if(!empty($programKegiatanData))
                    <ul class="list-group" style="word-wrap: break-word; overflow-wrap: break-word; border: none;">
                        @foreach($programKegiatanData as $programName => $program)
                            <li class="list-group-item" style="overflow: hidden; border-bottom: 1px solid #eee;">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <strong style="word-break: break-word; flex: 1; font-size: 0.95rem;">{{ Str::limit($programName, 50) }}</strong>
                                    <span class="badge bg-teal" style="background:#00B5A0; white-space: nowrap; flex-shrink: 0;">{{ format_rp($program['total'] ?? 0) }}</span>
                                </div>
                                @if(!empty($program['kegiatan']))
                                    <ul class="mt-2" style="padding-left: 15px; list-style-type: none; margin-bottom: 0;">
                                        @foreach($program['kegiatan'] as $kegiatanName => $kegiatan)
                                            <li class="mb-1">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <span style="word-break: break-word; flex: 1; font-size: 0.9rem;">{{ Str::limit($kegiatanName, 45) }}</span>
                                                    <span class="badge bg-primary" style="white-space: nowrap; flex-shrink: 0; font-size: 0.8rem;">{{ format_rp($kegiatan['total'] ?? 0) }}</span>
                                                </div>
                                                @if(!empty($kegiatan['subKegiatan']))
                                                    <ul class="mt-1" style="padding-left: 15px; list-style-type: none; margin-bottom: 0;">
                                                        @foreach($kegiatan['subKegiatan'] as $subName => $subAmount)
                                                            <li class="d-flex justify-content-between align-items-start gap-2 py-1">
                                                                <span class="text-muted" style="word-break: break-word; flex: 1; font-size: 0.85rem;">— {{ Str::limit($subName, 40) }}</span>
                                                                <span class="badge bg-secondary" style="white-space: nowrap; flex-shrink: 0; font-size: 0.75rem;">{{ format_rp($subAmount ?? 0) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-center text-muted py-4"><i class="fas fa-list fa-2x mb-3"></i><p>Belum ada data program/kegiatan</p></div>
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
// Budget by Jabatan - Pie Chart
const budgetByJabatanCtx = document.getElementById('budgetByJabatanChart').getContext('2d');
const budgetByJabatanData = @json($budgetByJabatan);

if (Object.keys(budgetByJabatanData).length === 0) {
    document.getElementById('budgetByJabatanChart').parentElement.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-chart-pie fa-3x mb-3"></i><p>Belum ada data anggaran dari perjanjian yang disetujui</p></div>';
} else {
    new Chart(budgetByJabatanCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(budgetByJabatanData),
            datasets: [{
                data: Object.values(budgetByJabatanData),
                backgroundColor: [
                    '#00B5A0', '#1E88E5', '#FF9800', '#9C27B0', '#4CAF50',
                    '#F44336', '#00BCD4', '#E91E63', '#FFC107', '#795548', '#607D8B'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 12,
                        font: { size: 11, family: 'Segoe UI' },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            let formatted = 'Rp ';
                            if (value >= 1000000000) formatted += (value / 1000000000).toFixed(2) + ' Miliar';
                            else if (value >= 1000000) formatted += (value / 1000000).toFixed(2) + ' Juta';
                            else if (value >= 1000) formatted += (value / 1000).toFixed(0) + ' Ribu';
                            else formatted += value.toLocaleString('id-ID');
                            return context.label + ': ' + formatted;
                        }
                    }
                }
            }
        }
    });
}

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
