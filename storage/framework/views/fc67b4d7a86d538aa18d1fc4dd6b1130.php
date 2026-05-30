

<?php $__env->startSection('title', 'Tambah Kegiatan'); ?>
<?php $__env->startSection('page-title', 'Tambah Kegiatan untuk Program: ' . $program['nama_program']); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info mb-4">
                <strong><i class="fas fa-folder"></i> Program:</strong> <?php echo e($program['nama_program']); ?>

            </div>

            <form action="<?php echo e(route('admin.kegiatan.store', $program['id'])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="mb-3">
                    <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                    <textarea name="nama_kegiatan" id="nama_kegiatan" class="form-control <?php $__errorArgs = ['nama_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" required><?php echo e(old('nama_kegiatan')); ?></textarea>
                    <?php $__errorArgs = ['nama_kegiatan'];
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

                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.program.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Kegiatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\program\create-kegiatan.blade.php ENDPATH**/ ?>