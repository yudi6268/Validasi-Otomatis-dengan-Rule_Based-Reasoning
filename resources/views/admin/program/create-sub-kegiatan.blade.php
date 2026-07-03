@extends('admin.layout')

@section('title', 'Tambah Sub-Kegiatan')
@section('page-title', 'Tambah Sub-Kegiatan')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info mb-4">
                @if(isset($kegiatan['program']))
                    <div><strong><i class="fas fa-folder"></i> Program:</strong> {{ $kegiatan['program']['nama_program'] }}</div>
                @endif
                <div><strong><i class="fas fa-list-ul"></i> Kegiatan:</strong> {{ $kegiatan['nama_kegiatan'] }}</div>
            </div>

            <form action="{{ route('admin.sub-kegiatan.store', $kegiatan['id']) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="kode_sub_kegiatan" class="form-label">Kode Sub-Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_sub_kegiatan" id="kode_sub_kegiatan" class="form-control @error('kode_sub_kegiatan') is-invalid @enderror" required value="{{ old('kode_sub_kegiatan') }}" placeholder="e.g., SK01, SK02">
                    @error('kode_sub_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="nama_sub_kegiatan" class="form-label">Nama Sub-Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="nama_sub_kegiatan" id="nama_sub_kegiatan" class="form-control @error('nama_sub_kegiatan') is-invalid @enderror" rows="3" required>{{ old('nama_sub_kegiatan') }}</textarea>
                    @error('nama_sub_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Sub-Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
