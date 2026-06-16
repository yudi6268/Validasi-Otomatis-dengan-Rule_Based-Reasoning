@extends('admin.layout')

@section('title', 'Kelola Perjanjian Kinerja')
@section('page-title', 'Kelola Perjanjian Kinerja')

@section('content')

{{-- Filter & Search --}}
<div class="data-table mb-3">
    <form method="GET" action="{{ route('admin.perjanjian.index') }}" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-600 small mb-1">Cari</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Nama, tahun..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-600 small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="waiting"  {{ request('status') === 'waiting'  ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
            <a href="{{ route('admin.perjanjian.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

{{-- Info ringkas --}}
<div class="d-flex align-items-center justify-content-between mb-2">
    <span class="text-muted small">
        Total: <strong>{{ $perjanjians->total() }}</strong> perjanjian
    </span>
    <small class="text-muted">Halaman {{ $perjanjians->currentPage() }} / {{ $perjanjians->lastPage() }}</small>
</div>

{{-- Table --}}
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover table-sm align-middle">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="22%">Nama Pembuat</th>
                    <th width="20%">Jabatan</th>
                    <th width="8%">Tahun</th>
                    <th width="12%">Tanggal</th>
                    <th width="12%">Status</th>
                    <th width="22%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perjanjians as $idx => $p)
                    @php
                        if (!empty($p->rejected) && $p->rejected == true) {
                            $statusText  = 'Ditolak';
                            $badgeClass  = 'bg-danger';
                        } elseif (!empty($p->pihak2_signature)) {
                            $statusText  = 'Disetujui';
                            $badgeClass  = 'bg-success';
                        } else {
                            $statusText  = 'Menunggu';
                            $badgeClass  = 'bg-warning text-dark';
                        }
                    @endphp
                    <tr>
                        <td>{{ ($perjanjians->currentPage() - 1) * $perjanjians->perPage() + $idx + 1 }}</td>
                        <td>
                            <strong>{{ $p->pihak1_name ?? ($p->user->nama ?? 'N/A') }}</strong><br>
                            <small class="text-muted">NIP: {{ $p->pihak1_nip ?? '-' }}</small>
                        </td>
                        <td><small>{{ $p->pihak1_jabatan ?? ($p->user->jabatan ?? '-') }}</small></td>
                        <td>{{ $p->tahun ?? '-' }}</td>
                        <td><small>{{ $p->created_at ? $p->created_at->format('d/m/Y') : '-' }}</small></td>
                        <td>
                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                            @if($p->catatan_penolakan)
                                <br><small class="text-danger" style="font-size:10px;">{{ \Str::limit($p->catatan_penolakan, 40) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#revisiModal{{ $p->id }}"
                                    title="Revisi Status">
                                <i class="fas fa-edit"></i> Revisi
                            </button>
                            <button class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusModal{{ $p->id }}"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Revisi Status --}}
                    <div class="modal fade" id="revisiModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="background:#00B5A0;color:#fff;">
                                    <h5 class="modal-title">
                                        <i class="fas fa-edit"></i> Revisi Status Perjanjian
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.perjanjian.revisi', $p->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="mb-1"><strong>Pembuat:</strong> {{ $p->pihak1_name ?? ($p->user->nama ?? 'N/A') }}</p>
                                        <p class="mb-3"><strong>Status saat ini:</strong> <span class="badge {{ $badgeClass }}">{{ $statusText }}</span></p>

                                        <label class="form-label fw-bold">Ubah Status ke:</label>
                                        <div class="d-flex flex-column gap-2 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="reset" id="reset{{ $p->id }}" checked onchange="toggleCatatan('catatan{{ $p->id }}', false)">
                                                <label class="form-check-label" for="reset{{ $p->id }}">
                                                    <span class="badge bg-warning text-dark">Menunggu</span> — Reset ke status awal
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="approve" id="approve{{ $p->id }}" onchange="toggleCatatan('catatan{{ $p->id }}', false)">
                                                <label class="form-check-label" for="approve{{ $p->id }}">
                                                    <span class="badge bg-success">Disetujui</span> — Setujui paksa oleh admin
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="action" value="reject" id="reject{{ $p->id }}" onchange="toggleCatatan('catatan{{ $p->id }}', true)">
                                                <label class="form-check-label" for="reject{{ $p->id }}">
                                                    <span class="badge bg-danger">Ditolak</span> — Tolak paksa oleh admin
                                                </label>
                                            </div>
                                        </div>

                                        <div id="catatan{{ $p->id }}" style="display:none;">
                                            <label class="form-label fw-bold">Catatan Penolakan <span class="text-danger">*</span></label>
                                            <textarea name="catatan" class="form-control" rows="3" placeholder="Tuliskan alasan penolakan..."></textarea>
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
                    <div class="modal fade" id="hapusModal{{ $p->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title"><i class="fas fa-trash"></i> Hapus Perjanjian</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin menghapus perjanjian milik <strong>{{ $p->pihak1_name ?? ($p->user->nama ?? 'N/A') }}</strong> ({{ $p->tahun }})? Tindakan ini tidak dapat dibatalkan.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.perjanjian.destroy', $p->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-file-contract fa-3x mb-3 d-block text-muted"></i>
                            Belum ada data perjanjian.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-end mt-3">
        {{ $perjanjians->links() }}
    </div>
</div>

@push('scripts')
<script>
function toggleCatatan(id, show) {
    const el = document.getElementById(id);
    if (el) el.style.display = show ? 'block' : 'none';
}
</script>
@endpush

@endsection
