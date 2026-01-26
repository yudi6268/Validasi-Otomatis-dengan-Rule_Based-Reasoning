@extends('admin.layout')

@section('title', 'Edit Template')
@section('page-title', 'Edit Template: ' . $template->nama_template)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="data-table">
            <form action="{{ route('admin.templates.update', $template) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama Template <span class="text-danger">*</span></label>
                    <input type="text" name="nama_template" class="form-control @error('nama_template') is-invalid @enderror" 
                           value="{{ old('nama_template', $template->nama_template) }}" required>
                    @error('nama_template')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipe Template <span class="text-danger">*</span></label>
                    <select name="tipe" class="form-select @error('tipe') is-invalid @enderror" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="perjanjian" {{ old('tipe', $template->tipe) == 'perjanjian' ? 'selected' : '' }}>Perjanjian Kinerja</option>
                        <option value="laporan" {{ old('tipe', $template->tipe) == 'laporan' ? 'selected' : '' }}>Laporan Kinerja</option>
                    </select>
                    @error('tipe')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Konten Template <span class="text-danger">*</span></label>
                    <textarea name="konten" class="form-control @error('konten') is-invalid @enderror" 
                              rows="12" required>{{ old('konten', $template->konten) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i> Gunakan placeholder seperti <code>@{{nama}}</code>, <code>@{{nip}}</code> untuk data dinamis.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                              rows="3">{{ old('keterangan', $template->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                               id="is_active" {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Template Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Template
                    </button>
                    <a href="{{ route('admin.templates.show', $template) }}" class="btn btn-info text-white">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                    <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="data-table">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi Template</h5>
            
            <table class="table table-sm">
                <tr>
                    <td><strong>Tipe:</strong></td>
                    <td>
                        <span class="badge {{ $template->tipe == 'perjanjian' ? 'bg-primary' : 'bg-success' }}">
                            {{ ucfirst($template->tipe) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $template->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Dibuat:</strong></td>
                    <td>{{ $template->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Update Terakhir:</strong></td>
                    <td>{{ $template->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            <hr class="my-3">

            <div class="alert alert-warning">
                <small>
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Perubahan template akan mempengaruhi dokumen baru yang dibuat menggunakan template ini.
                </small>
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('admin.templates.duplicate', $template) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-copy"></i> Duplikat Template
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
