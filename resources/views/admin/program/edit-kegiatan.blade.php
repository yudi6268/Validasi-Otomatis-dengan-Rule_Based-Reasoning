@extends('admin.layout')

@section('title', 'Edit Kegiatan')
@section('page-title', 'Edit Kegiatan')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            @if(isset($kegiatan['program']))
                <div class="alert alert-info mb-4">
                    <strong><i class="fas fa-folder"></i> Program:</strong> {{ $kegiatan['program']['nama_program'] }}
                </div>
            @endif

            <form action="{{ route('admin.kegiatan.update', $kegiatan['id']) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="kode_kegiatan" class="form-label">Kode Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_kegiatan" id="kode_kegiatan" class="form-control @error('kode_kegiatan') is-invalid @enderror" placeholder="Contoh: KGT001" required value="{{ old('kode_kegiatan', $kegiatan['kode_kegiatan']) }}">
                    @error('kode_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="nama_kegiatan" id="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" rows="3" required>{{ old('nama_kegiatan', $kegiatan['nama_kegiatan']) }}</textarea>
                    @error('nama_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $kegiatan['is_active'] ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
