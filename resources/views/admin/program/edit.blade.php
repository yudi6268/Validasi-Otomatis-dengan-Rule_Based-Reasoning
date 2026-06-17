@extends('admin.layout')

@section('title', 'Edit Program')
@section('page-title', 'Edit Program')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.program.update', $program['id']) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="kode_program" class="form-label">Kode Program <span class="text-danger">*</span></label>
                    <input type="text" name="kode_program" id="kode_program" class="form-control @error('kode_program') is-invalid @enderror" placeholder="Contoh: PROG001" required value="{{ old('kode_program', $program['kode_program']) }}">
                    @error('kode_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_program" class="form-label">Nama Program <span class="text-danger">*</span></label>
                    <textarea name="nama_program" id="nama_program" class="form-control @error('nama_program') is-invalid @enderror" rows="3" required>{{ old('nama_program', $program['nama_program']) }}</textarea>
                    @error('nama_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $program['is_active'] ?? true) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
