@extends('admin.layout')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna: ' . $user->nama)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="data-table">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">ID Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="id_pegawai" class="form-control @error('id_pegawai') is-invalid @enderror" value="{{ old('id_pegawai', $user->id_pegawai) }}" required>
                    @error('id_pegawai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip', $user->nip) }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatan as $jab)
                                <option value="{{ $jab->nama_jabatan }}" {{ old('jabatan', $user->jabatan) == $jab->nama_jabatan ? 'selected' : '' }}>
                                    {{ $jab->nama_jabatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pangkat <span class="text-danger">*</span></label>
                        <input type="text" name="pangkat" class="form-control @error('pangkat') is-invalid @enderror" value="{{ old('pangkat', $user->pangkat) }}" required>
                        @error('pangkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Divisi <span class="text-danger">*</span></label>
                    <input type="text" name="divisi" class="form-control @error('divisi') is-invalid @enderror" value="{{ old('divisi', $user->divisi) }}" required>
                    @error('divisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">Pilih Role...</option>
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="direktur" {{ old('role', $user->role) == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        <option value="wadir" {{ old('role', $user->role) == 'wadir' ? 'selected' : '' }}>Wakil Direktur</option>
                        <option value="kabag-kabid" {{ old('role', $user->role) == 'kabag-kabid' ? 'selected' : '' }}>Kabag/Kabid</option>
                        <option value="katimker-staf" {{ old('role', $user->role) == 'katimker-staf' ? 'selected' : '' }}>Katimker/Staf</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="non-active" {{ old('status', $user->status) == 'non-active' ? 'selected' : '' }}>Non Aktif</option>
                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Pengguna
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="data-table">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi User</h5>
            <table class="table table-sm">
                <tr>
                    <td><strong>Dibuat:</strong></td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Update Terakhir:</strong></td>
                    <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Total Perjanjian:</strong></td>
                    <td>{{ $user->perjanjians->count() ?? 0 }}</td>
                </tr>
            </table>

            <div class="alert alert-info mt-3">
                <strong>Catatan:</strong>
                <ul class="mb-0 mt-2 small">
                    <li>Field dengan <span class="text-danger">*</span> wajib diisi</li>
                    <li>ID Pegawai dan NIP harus tetap unik</li>
                    <li>Untuk reset password, gunakan tombol "Reset Password" di halaman daftar user</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
