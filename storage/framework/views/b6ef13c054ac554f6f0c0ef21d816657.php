

<?php $__env->startSection('title', 'Tambah Template'); ?>
<?php $__env->startSection('page-title', 'Tambah Template Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8">
        <div class="data-table">
            <form action="<?php echo e(route('admin.templates.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

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
                           value="<?php echo e(old('nama_template')); ?>" placeholder="Contoh: Template Perjanjian Kinerja Tahunan" required>
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
                        <option value="perjanjian" <?php echo e(old('tipe') == 'perjanjian' ? 'selected' : ''); ?>>Perjanjian Kinerja</option>
                        <option value="laporan" <?php echo e(old('tipe') == 'laporan' ? 'selected' : ''); ?>>Laporan Kinerja</option>
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
                              rows="12" required placeholder="Masukkan konten template (bisa berisi HTML)..."><?php echo e(old('konten')); ?></textarea>
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
                        <i class="fas fa-info-circle"></i> Anda bisa menggunakan tag HTML untuk formatting. 
                        Gunakan placeholder seperti <code>{{nama}}</code>, <code>{{nip}}</code>, dll untuk data dinamis.
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
                              rows="3" placeholder="Catatan atau keterangan tambahan tentang template ini"><?php echo e(old('keterangan')); ?></textarea>
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
                               id="is_active" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">
                            Template Aktif
                        </label>
                    </div>
                    <div class="form-text">Template yang tidak aktif tidak akan muncul dalam daftar pilihan.</div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Template
                    </button>
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
            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Panduan Template</h5>
            
            <div class="alert alert-info mb-3">
                <small>
                    <strong>Template</strong> digunakan untuk membuat dokumen perjanjian atau laporan kinerja dengan format yang konsisten.
                </small>
            </div>

            <p class="mb-2"><strong>Placeholder yang Tersedia:</strong></p>
            <div class="bg-light p-2 rounded mb-3" style="font-size: 0.85em;">
                
                <code>{{nama}}</code> - Nama pegawai<br>
                <code>{{nip}}</code> - NIP pegawai<br>
                <code>{{jabatan}}</code> - Jabatan<br>
                <code>{{divisi}}</code> - Divisi<br>
                <code>{{tanggal}}</code> - Tanggal<br>
                <code>{{tahun}}</code> - Tahun<br>
                
            </div>

            <p class="mb-2"><strong>Contoh Penggunaan:</strong></p>
            <div class="bg-light p-2 rounded" style="font-size: 0.85em;">
                
                <pre class="mb-0">Yang bertanda tangan di bawah ini:
Nama: {{nama}}
NIP: {{nip}}
Jabatan: {{jabatan}}</pre>
                
            </div>

            <hr class="my-3">

            <p class="text-muted small mb-0">
                <i class="fas fa-lightbulb"></i>
                <strong>Tips:</strong> Gunakan tag HTML seperti &lt;strong&gt;, &lt;p&gt;, &lt;br&gt; untuk formatting yang lebih baik.
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\templates\create.blade.php ENDPATH**/ ?>