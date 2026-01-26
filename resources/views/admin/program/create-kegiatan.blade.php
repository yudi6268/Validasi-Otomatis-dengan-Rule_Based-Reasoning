@extends('admin.layout')

@section('title', 'Tambah Kegiatan')
@section('page-title', 'Tambah Kegiatan untuk Program: ' . $program['nama_program'])

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong><i class="fas fa-folder"></i> Program:</strong> {{ $program['nama_program'] }}
            </div>

            <form action="{{ route('admin.kegiatan.store', $program['id']) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="nama_kegiatan" id="nama_kegiatan" class="form-control @error('nama_kegiatan') is-invalid @enderror" rows="3" required>{{ old('nama_kegiatan') }}</textarea>
                    @error('nama_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
