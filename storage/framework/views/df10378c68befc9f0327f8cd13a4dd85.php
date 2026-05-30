

<?php $__env->startSection('title', 'Kelola Jabatan'); ?>
<?php $__env->startSection('page-title', 'Kelola Jabatan'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between mb-4">
    <h5><i class="fas fa-briefcase"></i> Daftar Jabatan</h5>
    <a href="<?php echo e(route('admin.jabatan.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah
    </a>
</div>
<div class="mb-3">
    <input type="text" id="searchJabatanInput" class="form-control" placeholder="Cari nama jabatan, tugas, fungsi..." autocomplete="off">
</div>
<div id="jabatanTableContainer"></div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\jabatan\index.blade.php ENDPATH**/ ?>