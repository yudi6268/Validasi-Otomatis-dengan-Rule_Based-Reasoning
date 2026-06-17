

<?php $__env->startSection('title', 'Kelola Pengguna'); ?>
<?php $__env->startSection('page-title', 'Kelola Pengguna'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Pengguna Baru
    </a>
</div>

<!-- Search and Filter -->
<div class="data-table mb-4">
    <div class="row g-3">
        <div class="col-md-6">
            <input type="text" id="searchUserInput" class="form-control" placeholder="Cari nama, email, NIP..." value="<?php echo e(request('search')); ?>" autocomplete="off">
        </div>
        <div class="col-md-4">
            <select id="roleUserSelect" class="form-select">
                <option value="">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="direktur">Direktur</option>
                <option value="wadir">Wadir</option>
                <option value="kabag-kabid">Kabag/Kabid</option>
                <option value="katimker-staf">Katimker/Staf</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" id="btnSearchUser" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
        </div>
    </div>
</div>


<div id="usersTableContainer"></div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/admin/users/index.blade.php ENDPATH**/ ?>