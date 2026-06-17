@extends('admin.layout')

@section('title', 'Kelola Notifikasi Deadline')
@section('page-title', 'Kelola Notifikasi')

@section('content')
<div class="data-table">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-1"><i class="fas fa-bell"></i> Notifikasi</h5>
            <small class="text-muted">Notifikasi yang dikirim admin akan muncul di pojok kiri atas dashboard setiap pengguna.</small>
        </div>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Notifikasi Baru
        </a>
    </div>



    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="12%">Penerima</th>
                    <th width="28%">Judul Notifikasi</th>
                    <th width="30%">Pesan</th>
                    <th width="10%">Urgensi</th>
                    <th width="10%">Dibuat</th>
                    <th width="6%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr>
                        <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                        <td>
                            @if($notification->user_id)
                                <span class="badge bg-primary" title="{{ $notification->user->nama ?? 'N/A' }}">
                                    <i class="fas fa-user"></i> {{ \Str::limit($notification->user->nama ?? 'N/A', 14) }}
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="fas fa-users"></i> Semua User
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $notification->title }}</strong>
                        </td>
                        <td>
                            <small class="text-muted">{{ \Str::limit($notification->message, 100) }}</small>
                        </td>
                        <td>
                            @if($notification->type === 'danger')
                                <span class="badge bg-danger">🚨 Mendesak</span>
                            @elseif($notification->type === 'warning')
                                <span class="badge bg-warning text-dark">⚠️ Peringatan</span>
                            @elseif($notification->type === 'success')
                                <span class="badge bg-success">✅ Sukses</span>
                            @else
                                <span class="badge bg-info">ℹ️ Info</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $notification->created_at->format('d/m/Y') }}</small><br>
                            <small class="text-muted">{{ $notification->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $notification->id }}"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>

                            <div class="modal fade" id="deleteModal{{ $notification->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="fas fa-trash"></i> Hapus Notifikasi</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Hapus notifikasi <strong>"{{ $notification->title }}"</strong>? Notifikasi ini tidak akan lagi muncul di dashboard pengguna.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.notifications.destroy', $notification) }}" method="POST" class="d-inline">
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
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-bell-slash fa-3x mb-3 d-block text-muted"></i>
                            Belum ada notifikasi yang dibuat.
                            <div class="mt-2">
                                <a href="{{ route('admin.notifications.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Buat Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection