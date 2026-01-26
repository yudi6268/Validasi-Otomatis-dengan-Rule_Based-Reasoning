@extends('admin.layout')

@section('title', 'Kelola Jabatan')
@section('page-title', 'Kelola Jabatan')

@section('content')

<div class="d-flex justify-content-between mb-4">
    <h5><i class="fas fa-briefcase"></i> Daftar Jabatan</h5>
    <a href="{{ route('admin.jabatan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah
    </a>
</div>
<div class="mb-3">
    <input type="text" id="searchJabatanInput" class="form-control" placeholder="Cari nama jabatan, tugas, fungsi..." autocomplete="off">
</div>
<div id="jabatanTableContainer"></div>

@push('scripts')
<script>
const searchJabatanInput = document.getElementById('searchJabatanInput');
const jabatanTableContainer = document.getElementById('jabatanTableContainer');

function fetchJabatan() {
    const params = new URLSearchParams({ search: searchJabatanInput.value });
    fetch(`/admin/jabatan?${params.toString()}&ajax=1`)
        .then(res => res.text())
        .then(html => {
            jabatanTableContainer.innerHTML = html;
        });
}
searchJabatanInput.addEventListener('input', fetchJabatan);
window.addEventListener('DOMContentLoaded', fetchJabatan);
</script>
@endpush
@endsection


