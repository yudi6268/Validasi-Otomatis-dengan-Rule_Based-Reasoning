@extends('admin.layout')

@section('title', 'Tambah Program')
@section('page-title', 'Tambah Program Baru')

@section('content')
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.program.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="kode_program" class="form-label">Kode Program <span class="text-danger">*</span></label>
                    <input type="text" name="kode_program" id="kode_program" class="form-control @error('kode_program') is-invalid @enderror" placeholder="Contoh: PROG001" required value="{{ old('kode_program') }}">
                    @error('kode_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_program" class="form-label">Nama Program <span class="text-danger">*</span></label>
                    <textarea name="nama_program" id="nama_program" class="form-control @error('nama_program') is-invalid @enderror" rows="3" required>{{ old('nama_program') }}</textarea>
                    @error('nama_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.program.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
