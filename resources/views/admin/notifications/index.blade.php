@extends('admin.layout')

@section('title', 'Kelola Notifikasi')
@section('page-title', 'Kelola Notifikasi')

@section('content')
<div class="data-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-bell"></i> Daftar Notifikasi</h5>
        <a href="{{ route('notifications.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Kirim Notifikasi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <table class="table table-hover">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="15%">Penerima</th>
                <th width="20%">Judul</th>
                <th width="35%">Pesan</th>
                <th width="10%">Tipe</th>
                <th width="10%">Tanggal</th>
                <th width="5%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $notification)
                <tr>
                    <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                    <td>
                        @if($notification->user_id)
                            <span class="badge bg-primary">{{ $notification->user->nama ?? 'N/A' }}</span>
                        @else
                            <span class="badge bg-success"><i class="fas fa-users"></i> Semua User</span>
                        @endif
                    </td>
                    <td><strong>{{ $notification->title }}</strong></td>
                    <td>{{ Str::limit($notification->message, 80) }}</td>
                    <td>
                        @if($notification->type === 'success')
                            <span class="badge bg-success">Success</span>
                        @elseif($notification->type === 'warning')
                            <span class="badge bg-warning">Warning</span>
                        @elseif($notification->type === 'danger')
                            <span class="badge bg-danger">Danger</span>
                        @else
                            <span class="badge bg-info">Info</span>
                        @endif
                    </td>
                    <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal{{ $notification->id }}">
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $notification->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus notifikasi <strong>{{ $notification->title }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline">
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
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        Belum ada notifikasi
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
