<div class="data-table">
    <h5 class="mb-4"><i class="fas fa-users"></i> Daftar Pengguna ({{ $users->total() }})</h5>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Pegawai</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td><strong>{{ $user->id_pegawai }}</strong></td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->nip }}</td>
                        <td>{{ $user->jabatan }}</td>
                        <td>
                            @php
                                $badgeClass = match($user->role) {
                                    'admin' => 'bg-danger',
                                    'direktur' => 'bg-primary',
                                    'wadir' => 'bg-info',
                                    'kabag-kabid' => 'bg-warning',
                                    'katimker-staf' => 'bg-success',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($user->status) {
                                    'active' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'non-active' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $user->status === 'active' ? 'Aktif' : ($user->status === 'pending' ? 'Pending' : 'Non Aktif') }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit user"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info" title="Reset password (generate random password)" onclick="return confirm('Reset password untuk {{ $user->nama }}? Password baru akan di-generate otomatis.')"><i class="fas fa-key"></i></button>
                            </form>
                            @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Tidak ada pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
