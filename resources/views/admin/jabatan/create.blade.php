@extends('admin.layout')

@section('title', 'Tambah Jabatan Baru')
@section('page-title', 'Tambah Jabatan Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="data-table">
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center justify-content-between">
                    <div><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div>
                </div>
            @endif
            <form id="jabatanForm" method="POST" action="{{ route('admin.jabatan.store') }}" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jabatan" class="form-control" placeholder="Contoh: Direktur" required @if(session('success')) disabled @endif>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tugas</label>
                    <textarea name="tugas" class="form-control" placeholder="Deskripsi tugas jabatan ini..." rows="3" @if(session('success')) disabled @endif></textarea>
                </div>
                <label class="form-label">Fungsi</label>
                <div id="fungsiList">
                    <div class="input-group mb-2 fungsi-item">
                        <span class="input-group-text">1.</span>
                        <input type="text" name="fungsi[]" class="form-control" placeholder="Fungsi jabatan..." @if(session('success')) disabled @endif>
                        <button type="button" class="btn btn-danger d-none remove-fungsi" tabindex="-1"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <button type="button" id="addFungsi" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Fungsi</button>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked @if(session('success')) disabled @endif>
                    <label class="form-check-label" for="isActive">Jabatan Aktif</label>
                </div>
                <div class="mb-2 text-muted" style="font-size:0.95em;">Jabatan yang tidak aktif tidak akan muncul dalam dropdown pilihan.</div>
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary" @if(session('success')) disabled @endif>
                        <i class="fas fa-save"></i> Simpan Jabatan
                    </button>
                    <a href="{{ route('admin.jabatan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="data-table">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi</h5>
            <div class="alert alert-info">
                <strong>Jabatan</strong> digunakan untuk mengelompokkan user berdasarkan posisi mereka.
            </div>
            <div>
                <b>Isi dengan detail:</b>
                <ul class="mb-0 mt-2">
                    <li><b>Nama Jabatan:</b> Nama resmi posisi/jabatan</li>
                    <li><b>Tugas:</b> Uraian tugas yang harus dikerjakan</li>
                    <li><b>Fungsi:</b> Fungsi dan tanggung jawab jabatan</li>
                </ul>
                <hr class="my-2">
                <span class="text-muted"><b>Tips:</b> Nama jabatan harus unik dan konsisten dengan struktur organisasi.</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let fungsiList = document.getElementById('fungsiList');
    let addFungsiBtn = document.getElementById('addFungsi');
    function updateFungsiNumbers() {
        let items = fungsiList.querySelectorAll('.fungsi-item');
        items.forEach((item, idx) => {
            item.querySelector('.input-group-text').textContent = (idx + 1) + '.';
            let removeBtn = item.querySelector('.remove-fungsi');
            if (idx === 0) {
                removeBtn.classList.add('d-none');
            } else {
                removeBtn.classList.remove('d-none');
            }
        });
    }
    addFungsiBtn.addEventListener('click', function() {
        let count = fungsiList.querySelectorAll('.fungsi-item').length;
        let div = document.createElement('div');
        div.className = 'input-group mb-2 fungsi-item';
        div.innerHTML = `<span class="input-group-text">${count+1}.</span>
            <input type="text" name="fungsi[]" class="form-control" placeholder="Fungsi jabatan..." ${document.querySelector('form').hasAttribute('disabled') ? 'disabled' : ''}>
            <button type="button" class="btn btn-danger remove-fungsi" tabindex="-1"><i class="fas fa-times"></i></button>`;
        fungsiList.appendChild(div);
        updateFungsiNumbers();
    });
    fungsiList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-fungsi')) {
            let item = e.target.closest('.fungsi-item');
            item.remove();
            updateFungsiNumbers();
        }
    });
    updateFungsiNumbers();
    // Lock form if success
    @if(session('success'))
        document.querySelectorAll('form input, form textarea, form select, form button').forEach(el => {
            el.setAttribute('disabled', 'disabled');
        });
    @endif
});
</script>
@endpush
@endsection
