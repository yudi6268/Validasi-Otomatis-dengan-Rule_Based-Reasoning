@extends('admin.layout')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pengguna Baru
    </a>
</div>

<!-- Search and Filter -->
<div class="data-table mb-4">
    <div class="row g-3">
        <div class="col-md-6">
            <input type="text" id="searchUserInput" class="form-control" placeholder="Cari nama, email, NIP..." value="{{ request('search') }}" autocomplete="off">
        </div>
        <div class="col-md-4">
            <select id="roleUserSelect" class="form-select">
                <option value="">Semua Role</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="direktur" {{ request('role') === 'direktur' ? 'selected' : '' }}>Direktur</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" id="btnSearchUser" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
        </div>
    </div>
</div>


<div id="usersTableContainer"></div>

@push('scripts')
<script>
const searchUserInput = document.getElementById('searchUserInput');
const roleUserSelect = document.getElementById('roleUserSelect');
const btnSearchUser = document.getElementById('btnSearchUser');
const usersTableContainer = document.getElementById('usersTableContainer');

function fetchUsers() {
    const params = new URLSearchParams({
        search: searchUserInput.value,
        role: roleUserSelect.value
    });
    usersTableContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><div>Memuat data...</div></div>';
    fetch(`/admin/users?${params.toString()}&ajax=1`)
        .then(res => res.text())
        .then(html => {
            usersTableContainer.innerHTML = html;
        });
}

btnSearchUser.addEventListener('click', fetchUsers);
searchUserInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') fetchUsers();
});
roleUserSelect.addEventListener('change', fetchUsers);
window.addEventListener('DOMContentLoaded', fetchUsers);
</script>
@endpush
@endsection
