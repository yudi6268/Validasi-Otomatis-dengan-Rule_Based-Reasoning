

<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('admin.jabatan.update', $jabatan['id'])); ?>">
<?php echo csrf_field(); ?>
<?php echo method_field('PUT'); ?>

<input name="nama_jabatan" class="form-control mb-2"
       value="<?php echo e($jabatan['nama_jabatan']); ?>">

<textarea name="tugas" class="form-control mb-2"><?php echo e($jabatan['tugas']); ?></textarea>

<?php $__currentLoopData = $jabatan['fungsi'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <input name="fungsi[]" class="form-control mb-1" value="<?php echo e($f); ?>">
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<label>
    <input type="checkbox" name="is_active" value="1"
        <?php echo e($jabatan['is_active'] ? 'checked' : ''); ?>> Aktif
</label>

<button class="btn btn-primary mt-3">Update</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\jabatan\edit.blade.php ENDPATH**/ ?>