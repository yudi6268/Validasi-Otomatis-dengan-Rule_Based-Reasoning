@extends('admin.layout')

@section('title', 'Kelola Laporan Kinerja')
@section('page-title', 'Kelola Laporan Kinerja')

@section('content')

{{-- Filter & Search --}}
<div class="data-table mb-3">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-600 small mb-1">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama, periode, tahun..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-600 small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="waiting"  {{ request('status') === 'waiting'  ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label fw-600 small mb-1">Tahun</label>
            <select name="tahun" class="form-select form-select-sm">
                <option value="">Semua</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

{{-- Info ringkas --}}
<div class="d-flex align-items-center justify-content-between mb-2">
    <span class="text-muted small">
        Total: <strong>{{ $laporans->total() }}</strong> laporan
    </span>
    <small class="text-muted">Halaman {{ $laporans->currentPage() }} / {{ $laporans->lastPage() }}</small>
</div>

{{-- Triwulan Aktif (indikator & kontrol via modal) --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <strong>Triwulan Aktif:</strong>
        <span id="activeTriwulanBadge" class="badge bg-primary">TW {{ $activeTriwulan ?? 1 }}</span>
        <small class="text-muted ms-2">(Tampilkan laporan untuk triwulan aktif sebagai referensi)</small>
    </div>

    @if(auth()->check() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
        <div>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#selectTriwulanModal">
                <i class="fas fa-calendar-alt"></i> Pilih Triwulan Laporan Aktif
            </button>
        </div>
    @else
        <a href="{{ route('admin.triwulan.setting') }}" class="btn btn-sm btn-outline-secondary">Lihat pengaturan triwulan</a>
    @endif
</div>

{{-- Modal Pilih Triwulan --}}
@if(auth()->check() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin())
<div class="modal fade" id="selectTriwulanModal" tabindex="-1" aria-labelledby="selectTriwulanLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectTriwulanLabel"><i class="fas fa-calendar-alt"></i> Pilih Triwulan Aktif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="selectTriwulanForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Triwulan</label>
                        <select name="triwulan" id="selectTriwulanInput" class="form-select">
                            @for($i=1;$i<=4;$i++)
                                <option value="{{ $i }}" {{ ($activeTriwulan ?? 1) == $i ? 'selected' : '' }}>TW {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
                <div id="selectTriwulanAlert" class="alert d-none" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="saveTriwulanBtn" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- JS: kirim AJAX untuk mengubah triwulan aktif dan update badge tanpa reload --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveTriwulanBtn');
    if (!saveBtn) return;

    saveBtn.addEventListener('click', async function() {
        const form = document.getElementById('selectTriwulanForm');
        const select = document.getElementById('selectTriwulanInput');
        const alertBox = document.getElementById('selectTriwulanAlert');
        const badge = document.getElementById('activeTriwulanBadge');

        const triwulan = select.value;
        const token = form.querySelector('input[name="_token"]').value;

        alertBox.classList.add('d-none');

        try {
            const res = await fetch('{{ route('admin.triwulan.setting.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ triwulan: parseInt(triwulan) })
            });

            const data = await res.json();

            if (res.ok && data.success) {
                // Update badge and close modal
                badge.textContent = 'TW ' + data.triwulan;
                var modal = bootstrap.Modal.getInstance(document.getElementById('selectTriwulanModal'));
                modal.hide();

                // flash message (temporary)
                const flash = document.createElement('div');
                flash.className = 'alert alert-success mt-3';
                flash.textContent = data.message || 'Triwulan aktif berhasil diubah.';
                document.querySelector('.data-table').prepend(flash);
                setTimeout(() => flash.remove(), 3500);
            } else {
                alertBox.classList.remove('d-none');
                alertBox.classList.add('alert-danger');
                alertBox.textContent = data.message || 'Gagal mengubah triwulan.';
            }
        } catch (err) {
            alertBox.classList.remove('d-none');
            alertBox.classList.add('alert-danger');
            alertBox.textContent = 'Terjadi error saat menghubungi server.';
            console.error(err);
        }
    });
});
</script>
@endpush

{{-- Table --}}
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="22%">Nama Pembuat</th>
                    <th width="18%">Jabatan</th>
                    <th width="10%">Periode</th>
                    <th width="8%">Tahun</th>
                    <th width="10%">Tanggal</th>
                    <th width="10%">Status</th>
                    <th width="18%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $idx => $l)
                    @php
                        if (!empty($l->pihak2_signature)) {
                            $statusText = 'Disetujui';
                            $badgeClass = 'bg-success';
                        } else {
                            $statusText = 'Menunggu';
                            $badgeClass = 'bg-warning text-dark';
                        }
                        $periodeLabel = match((int)($l->periode ?? $l->triwulan_aktif)) {
                            1 => 'TW I',
                            2 => 'TW II',
                            3 => 'TW III',
                            4 => 'TW IV',
                            default => ($l->periode ?? $l->triwulan_aktif ?? '-')
                        };
                    @endphp
                    <tr>
                        <td>{{ ($laporans->currentPage() - 1) * $laporans->perPage() + $idx + 1 }}</td>
                        <td>
                            <strong>{{ $l->pihak1_name ?? ($l->user->nama ?? 'N/A') }}</strong><br>
                            <small class="text-muted">{{ $l->pihak1_jabatan ?? ($l->user->jabatan ?? '-') }}</small>
                        </td>
                        <td><small>{{ $l->jabatan ?? ($l->user->jabatan ?? '-') }}</small></td>
                        <td>
                            <span class="badge bg-info text-dark">{{ $periodeLabel }}</span>
                        </td>
                        <td>{{ $l->tahun ?? '-' }}</td>
                        <td><small>{{ $l->created_at ? $l->created_at->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#revisiModal{{ $l->id }}"
                                    title="Revisi Status">
                                <i class="fas fa-edit"></i> Revisi
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusModal{{ $l->id }}"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Revisi Status --}}
                    <div class="modal fade" id="revisiModal{{ $l->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background:#00B5A0;color:#fff;">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit"></i> Revisi Status Laporan
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.laporan.revisi', $l->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="mb-1"><strong>Pembuat:</strong> {{ $l->pihak1_name ?? ($l->user->nama ?? 'N/A') }}</p>
                                        <p class="mb-1"><strong>Periode:</strong> {{ $periodeLabel }} — Tahun {{ $l->tahun ?? '-' }}</p>
                                        <p class="mb-3"><strong>Status saat ini:</strong> <span class="badge {{ $badgeClass }}">{{ $statusText }}</span></p>

                                        <label class="form-label fw-bold">Ubah Status ke:</label>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="reset" id="lreset{{ $l->id }}" checked>
                                                <label class="form-check-label" for="lreset{{ $l->id }}">
                                                    <span class="badge bg-warning text-dark">Menunggu</span> — Reset ke status awal (hapus tanda tangan)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="approve" id="lapprove{{ $l->id }}">
                                                <label class="form-check-label" for="lapprove{{ $l->id }}">
                                                    <span class="badge bg-success">Disetujui</span> — Setujui paksa oleh admin
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Hapus --}}
                    <div class="modal fade" id="hapusModal{{ $l->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-trash"></i> Hapus Laporan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin menghapus laporan <strong>{{ $periodeLabel }}</strong> milik <strong>{{ $l->pihak1_name ?? ($l->user->nama ?? 'N/A') }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.laporan.destroy', $l->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Ya, Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="fas fa-chart-line fa-3x mb-3 d-block text-muted"></i>
                            Belum ada data laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $laporans->links() }}
    </div>
</div>

@endsection
