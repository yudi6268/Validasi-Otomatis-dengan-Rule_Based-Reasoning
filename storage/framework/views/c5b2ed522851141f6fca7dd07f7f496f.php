

<?php $__env->startSection('title', 'Edit Template'); ?>
<?php $__env->startSection('page-title', 'Edit Template: ' . $template->nama_template); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="data-table">
            <form action="<?php echo e(route('admin.templates.update', $template)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label class="form-label">Nama Template <span class="text-danger">*</span></label>
                    <input type="text" name="nama_template" class="form-control <?php $__errorArgs = ['nama_template'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('nama_template', $template->nama_template)); ?>" required>
                    <?php $__errorArgs = ['nama_template'];
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

                <div class="mb-3">
                    <label class="form-label">Tipe Template <span class="text-danger">*</span></label>
                    <select name="tipe" class="form-select <?php $__errorArgs = ['tipe'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="perjanjian" <?php echo e(old('tipe', $template->tipe) == 'perjanjian' ? 'selected' : ''); ?>>Perjanjian Kinerja</option>
                        <option value="laporan" <?php echo e(old('tipe', $template->tipe) == 'laporan' ? 'selected' : ''); ?>>Laporan Kinerja</option>
                    </select>
                    <?php $__errorArgs = ['tipe'];
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

                <div class="mb-3">
                    <label class="form-label">Konten Template <span class="text-danger">*</span></label>
                    <textarea name="konten" class="form-control <?php $__errorArgs = ['konten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              rows="12" required><?php echo e(old('konten', $template->konten)); ?></textarea>
                    <?php $__errorArgs = ['konten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="form-text">
                        <i class="fas fa-info-circle"></i> Gunakan placeholder seperti <code>{{nama}}</code>, <code>{{nip}}</code> untuk data dinamis.
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                              rows="3"><?php echo e(old('keterangan', $template->keterangan)); ?></textarea>
                    <?php $__errorArgs = ['keterangan'];
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

                <div class="mb-3">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                               id="is_active" <?php echo e(old('is_active', $template->is_active) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">
                            Template Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Template
                    </button>
                    <a href="<?php echo e(route('admin.templates.show', $template)); ?>" class="btn btn-info text-white">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                    <a href="<?php echo e(route('admin.templates.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="data-table">
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Informasi Template</h5>
            
            <table class="table table-sm">
                <tr>
                    <td><strong>Tipe:</strong></td>
                    <td>
                        <span class="badge <?php echo e($template->tipe == 'perjanjian' ? 'bg-primary' : 'bg-success'); ?>">
                            <?php echo e(ucfirst($template->tipe)); ?>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        <span class="badge <?php echo e($template->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                            <?php echo e($template->is_active ? 'Aktif' : 'Tidak Aktif'); ?>

                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Dibuat:</strong></td>
                    <td><?php echo e($template->created_at->format('d/m/Y H:i')); ?></td>
                </tr>
                <tr>
                    <td><strong>Update Terakhir:</strong></td>
                    <td><?php echo e($template->updated_at->format('d/m/Y H:i')); ?></td>
                </tr>
            </table>

            <hr class="my-3">

            <div class="alert alert-warning">
                <small>
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Perhatian:</strong> Perubahan template akan mempengaruhi dokumen baru yang dibuat menggunakan template ini.
                </small>
            </div>

            <div class="d-grid gap-2">
                <a href="<?php echo e(route('admin.templates.duplicate', $template)); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-copy"></i> Duplikat Template
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\templates\edit.blade.php ENDPATH**/ ?>