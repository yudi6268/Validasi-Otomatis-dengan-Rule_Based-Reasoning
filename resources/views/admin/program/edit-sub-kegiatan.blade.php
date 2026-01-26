@extends('admin.layout')

@section('title', 'Edit Sub-Kegiatan')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            @if(isset($subKegiatan['kegiatan']))
                <div class="alert alert-info mb-4">
                    @if(isset($subKegiatan['kegiatan']['program']))
                        <div><strong><i class="fas fa-folder"></i> Program:</strong> {{ $subKegiatan['kegiatan']['program']['nama_program'] }}</div>
                    @endif
                    <div><strong><i class="fas fa-list-ul"></i> Kegiatan:</strong> {{ $subKegiatan['kegiatan']['nama_kegiatan'] }}</div>
                </div>
            @endif

            <form action="{{ route('admin.sub-kegiatan.update', $subKegiatan['id']) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="kode_sub_kegiatan" class="form-label">Kode Sub-Kegiatan <span class="text-danger">*</span></label>
                    <input type="text" name="kode_sub_kegiatan" id="kode_sub_kegiatan" class="form-control @error('kode_sub_kegiatan') is-invalid @enderror" required value="{{ old('kode_sub_kegiatan', $subKegiatan['kode_sub_kegiatan']) }}" placeholder="e.g., SK01, SK02">
                    @error('kode_sub_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="nama_sub_kegiatan" class="form-label">Nama Sub-Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="nama_sub_kegiatan" id="nama_sub_kegiatan" class="form-control @error('nama_sub_kegiatan') is-invalid @enderror" rows="3" required>{{ old('nama_sub_kegiatan', $subKegiatan['nama_sub_kegiatan']) }}</textarea>
                    @error('nama_sub_kegiatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $subKegiatan['is_active'] ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Sub-Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
