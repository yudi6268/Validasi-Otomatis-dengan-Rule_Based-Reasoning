@extends('admin.layout')

@section('title', 'Tambah Pengguna Baru')
@section('page-title', 'Tambah Pengguna Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="data-table">
            <form id="userCreateForm" action="{{ route('admin.users.store') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-3">
                    <label class="form-label">ID Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="id_pegawai" class="form-control @error('id_pegawai') is-invalid @enderror" value="{{ old('id_pegawai') }}" required>
                    @error('id_pegawai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                <div class="mb-3">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="non-active" {{ old('status') == 'non-active' ? 'selected' : '' }}>Non Aktif</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" value="{{ old('nip') }}" required>
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
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
                                <option value="{{ $jab->nama_jabatan }}" {{ old('jabatan') == $jab->nama_jabatan ? 'selected' : '' }}>
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
                        <input type="text" name="pangkat" class="form-control @error('pangkat') is-invalid @enderror" value="{{ old('pangkat') }}" required>
                        @error('pangkat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Divisi <span class="text-danger">*</span></label>
                    <input type="text" name="divisi" class="form-control @error('divisi') is-invalid @enderror" value="{{ old('divisi') }}" required>
                    @error('divisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">Pilih Role...</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="direktur" {{ old('role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pengguna
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
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi</h5>
            <div class="alert alert-info">
                <strong>Catatan:</strong>
                <ul class="mb-0 mt-2">
                    <li>Field dengan tanda <span class="text-danger">*</span> wajib diisi</li>
                    <li>ID Pegawai harus unik</li>
                    <li>NIP harus unik</li>
                    <li>Email harus valid dan unik</li>
                    <li><strong>Password akan di-generate otomatis</strong> dan dikirim ke email user</li>
                </ul>
            </div>

        </div>
    </div>
</div>
        <!-- Modal for required fields -->
        <div class="modal fade" id="requiredFieldsModal" tabindex="-1" aria-labelledby="requiredFieldsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="requiredFieldsModalLabel"><i class="fas fa-exclamation-triangle"></i> Data Wajib Belum Lengkap</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Mohon lengkapi semua field bertanda <span class="text-danger">*</span> sebelum menyimpan pengguna.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Required fields check
    const userCreateForm = document.getElementById('userCreateForm');
    if (userCreateForm) {
        userCreateForm.addEventListener('submit', function(e) {
            let valid = true;
            this.querySelectorAll('[required]').forEach(function(input) {
                if (!input.value || (input.type === 'checkbox' && !input.checked)) {
                    valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
                var modal = new bootstrap.Modal(document.getElementById('requiredFieldsModal'));
                modal.show();
            }
        });
    }
});
</script>
@endpush
@endsection
