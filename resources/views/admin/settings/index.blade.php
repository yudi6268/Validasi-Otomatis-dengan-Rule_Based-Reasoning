@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengaturan Tahun Perjanjian</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Atur Tahun yang Tersedia untuk Perjanjian</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> User hanya dapat memilih 2 tahun saat membuat perjanjian kinerja. Atur tahun mana saja yang ingin ditampilkan.
            </div>

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tahun_perjanjian_1" class="form-label">Tahun Pilihan Pertama <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('tahun_perjanjian_1') is-invalid @enderror" 
                                   id="tahun_perjanjian_1" 
                                   name="tahun_perjanjian_1" 
                                   value="{{ old('tahun_perjanjian_1', $tahun1) }}" 
                                   min="2020" 
                                   max="2050"
                                   required>
                            @error('tahun_perjanjian_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tahun_perjanjian_2" class="form-label">Tahun Pilihan Kedua <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('tahun_perjanjian_2') is-invalid @enderror" 
                                   id="tahun_perjanjian_2" 
                                   name="tahun_perjanjian_2" 
                                   value="{{ old('tahun_perjanjian_2', $tahun2) }}" 
                                   min="2020" 
                                   max="2050"
                                   required>
                            @error('tahun_perjanjian_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Preview</h6>
        </div>
        <div class="card-body">
            <p class="mb-2"><strong>Tahun yang akan ditampilkan saat user membuat perjanjian:</strong></p>
            <ul class="list-group">
                <li class="list-group-item">{{ $tahun1 }}</li>
                <li class="list-group-item">{{ $tahun2 }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
