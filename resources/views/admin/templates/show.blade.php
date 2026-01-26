@extends('admin.layout')

@section('title', 'Preview Template')
@section('page-title', 'Preview Template: ' . $template->nama_template)

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Template Info Card -->
        <div class="data-table mb-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h4 class="mb-1">{{ $template->nama_template }}</h4>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge {{ $template->tipe == 'perjanjian' ? 'bg-primary' : 'bg-success' }}">
                            {{ ucfirst($template->tipe) }}
                        </span>
                        <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $template->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.templates.duplicate', $template) }}" class="btn btn-sm btn-info text-white">
                        <i class="fas fa-copy"></i> Duplikat
                    </a>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>

            @if($template->keterangan)
            <div class="alert alert-info">
                <strong>Keterangan:</strong> {{ $template->keterangan }}
            </div>
            @endif
        </div>

        <!-- Template Content Preview -->
        <div class="data-table">
            <h5 class="mb-3"><i class="fas fa-eye"></i> Preview Konten</h5>
            <div class="border rounded p-4 bg-white" style="min-height: 400px;">
                {!! nl2br(e($template->konten)) !!}
            </div>
        </div>

        <!-- Raw Content -->
        <div class="data-table mt-4">
            <h5 class="mb-3"><i class="fas fa-code"></i> Konten Mentah</h5>
            <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>{{ $template->konten }}</code></pre>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="data-table">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Detail Template</h5>
            
            <table class="table table-sm">
                <tr>
                    <td><strong>Nama:</strong></td>
                    <td>{{ $template->nama_template }}</td>
                </tr>
                <tr>
                    <td><strong>Tipe:</strong></td>
                    <td>{{ ucfirst($template->tipe) }}</td>
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
                    <td><strong>Diperbarui:</strong></td>
                    <td>{{ $template->updated_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>Panjang Konten:</strong></td>
                    <td>{{ strlen($template->konten) }} karakter</td>
                </tr>
            </table>

            <hr class="my-3">

            <div class="d-grid gap-2">
                <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>
                <a href="{{ route('admin.templates.edit', $template) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Template
                </a>
                <a href="{{ route('admin.templates.duplicate', $template) }}" class="btn btn-info text-white">
                    <i class="fas fa-copy"></i> Duplikat Template
                </a>
            </div>
        </div>

        <!-- Placeholder Info -->
        <div class="data-table mt-4">
            <h6 class="mb-2"><i class="fas fa-tags"></i> Placeholder Terdeteksi</h6>
            @php
                preg_match_all('/\{\{(\w+)\}\}/', $template->konten, $matches);
                $placeholders = array_unique($matches[1]);
            @endphp
            
            @if(count($placeholders) > 0)
                <div class="d-flex flex-wrap gap-1">
                    @foreach($placeholders as $placeholder)
                        <span class="badge bg-secondary">@{{{{ $placeholder }}}}</span>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-0">Tidak ada placeholder ditemukan</p>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus template <strong>{{ $template->nama_template }}</strong>?</p>
                <p class="text-danger mb-0">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.templates.destroy', $template) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
