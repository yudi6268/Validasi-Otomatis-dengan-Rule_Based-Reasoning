

<?php $__env->startSection('title', 'Edit Program'); ?>
<?php $__env->startSection('page-title', 'Edit Program'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="<?php echo e(route('admin.program.update', $program['id'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="mb-3">
                    <label for="nama_program" class="form-label">Nama Program <span class="text-danger">*</span></label>
                    <textarea name="nama_program" id="nama_program" class="form-control <?php $__errorArgs = ['nama_program'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" required><?php echo e(old('nama_program', $program['nama_program'])); ?></textarea>
                    <?php $__errorArgs = ['nama_program'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Admin tidak mengelola anggaran pada struktur master -->

                <div class="mb-3 form-check">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" <?php echo e(old('is_active', $program['is_active'] ?? true) ? 'checked' : ''); ?>>
                    <label for="is_active" class="form-check-label">Aktif</label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.program.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\program\edit.blade.php ENDPATH**/ ?>