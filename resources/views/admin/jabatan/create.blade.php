@extends('admin.layout')

@section('title', 'Tambah Jabatan Baru')
@section('page-title', 'Tambah Jabatan Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="data-table">

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> 
                    <strong>Gagal Validasi:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="jabatanForm" method="POST" action="{{ route('admin.jabatan.store') }}" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_jabatan" class="form-control" placeholder="Contoh: Direktur" value="{{ old('nama_jabatan') }}" required @if(session('success')) disabled @endif>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tugas</label>
                    <textarea name="tugas" class="form-control" placeholder="Deskripsi tugas jabatan ini..." rows="3" @if(session('success')) disabled @endif>{{ old('tugas') }}</textarea>
                </div>
                <label class="form-label">Fungsi</label>
                <div id="fungsiList">
                    @php
                        $fungsiOld = old('fungsi', ['']);
                    @endphp
                    @foreach($fungsiOld as $index => $fungsi)
                        <div class="input-group mb-2 fungsi-item">
                            <span class="input-group-text">{{ $index + 1 }}.</span>
                            <input type="text" name="fungsi[]" class="form-control" placeholder="Fungsi jabatan..." value="{{ $fungsi }}" @if(session('success')) disabled @endif>
                            <button type="button" class="btn btn-danger {{ $index === 0 ? 'd-none' : '' }} remove-fungsi" tabindex="-1"><i class="fas fa-times"></i></button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="addFungsi" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Fungsi</button>
                <br>
                <label class="form-label">Membawahi</label>
                <div id="membawahiList">
                    @php
                        $membawahiOld = old('membawahi', ['']);
                    @endphp
                    @foreach($membawahiOld as $index => $unit)
                        <div class="input-group mb-2 membawahi-item">
                            <span class="input-group-text">{{ $index + 1 }}.</span>
                            <input type="text" name="membawahi[]" class="form-control" placeholder="Unit atau jabatan yang dibawahi..." value="{{ $unit }}" @if(session('success')) disabled @endif>
                            <button type="button" class="btn btn-danger {{ $index === 0 ? 'd-none' : '' }} remove-membawahi" tabindex="-1"><i class="fas fa-times"></i></button>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="addMembawahi" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Membawahi</button>
                <div class="form-text mb-3 text-muted">Jika tidak diisi, informasi membawahi tidak akan ditampilkan di Laporan Kinerja.</div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', '1') == '1' ? 'checked' : '' }} @if(session('success')) disabled @endif>
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
                    <li><b>Membawahi:</b> Unit, tim, atau jabatan yang berada di bawah pengawasan</li>
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
    function createListHandler(listId, addButtonId, itemClass, removeClass, placeholder) {
        const list = document.getElementById(listId);
        const addBtn = document.getElementById(addButtonId);
        if (!list || !addBtn) return;

        function updateNumbers() {
            const items = list.querySelectorAll('.' + itemClass);
            items.forEach((item, idx) => {
                item.querySelector('.input-group-text').textContent = (idx + 1) + '.';
                const removeBtn = item.querySelector('.' + removeClass);
                if (idx === 0) {
                    removeBtn.classList.add('d-none');
                } else {
                    removeBtn.classList.remove('d-none');
                }
            });
        }

        addBtn.addEventListener('click', function() {
            const count = list.querySelectorAll('.' + itemClass).length;
            const div = document.createElement('div');
            div.className = 'input-group mb-2 ' + itemClass;
            div.innerHTML = `<span class="input-group-text">${count+1}.</span>
                <input type="text" name="${itemClass.replace('-item', '')}[]" class="form-control" placeholder="${placeholder}">
                <button type="button" class="btn btn-danger ${removeClass}" tabindex="-1"><i class="fas fa-times"></i></button>`;
            list.appendChild(div);
            updateNumbers();
        });

        list.addEventListener('click', function(e) {
            if (e.target.closest('.' + removeClass)) {
                const item = e.target.closest('.' + itemClass);
                item.remove();
                updateNumbers();
            }
        });

        updateNumbers();
    }

    createListHandler('fungsiList', 'addFungsi', 'fungsi-item', 'remove-fungsi', 'Fungsi jabatan...');
    createListHandler('membawahiList', 'addMembawahi', 'membawahi-item', 'remove-membawahi', 'Unit atau jabatan yang dibawahi...');
});
</script>
@endpush
@endsection
