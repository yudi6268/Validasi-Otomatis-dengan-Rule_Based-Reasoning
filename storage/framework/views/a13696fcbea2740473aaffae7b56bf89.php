

<?php $__env->startSection('title', 'Kelola Template'); ?>
<?php $__env->startSection('page-title', 'Kelola Template'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <a href="<?php echo e(route('admin.templates.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Template Baru
    </a>
</div>

<!-- Search and Filter -->
<div class="data-table mb-4">
    <form method="GET" action="<?php echo e(route('admin.templates.index')); ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Cari nama template..." value="<?php echo e(request('search')); ?>">
            </div>
            <div class="col-md-4">
                <select name="tipe" class="form-select">
                    <option value="">Semua Tipe</option>
                    <option value="perjanjian" <?php echo e(request('tipe') == 'perjanjian' ? 'selected' : ''); ?>>Perjanjian</option>
                    <option value="laporan" <?php echo e(request('tipe') == 'laporan' ? 'selected' : ''); ?>>Laporan</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
            </div>
        </div>
    </form>
</div>

<!-- Templates Table -->
<div class="data-table">
    <h5 class="mb-4"><i class="fas fa-file-alt"></i> Daftar Template (<?php echo e($templates->total()); ?>)</h5>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Template</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Terakhir Update</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration + ($templates->currentPage() - 1) * $templates->perPage()); ?></td>
                        <td>
                            <strong><?php echo e($template->nama_template); ?></strong>
                            <?php if($template->keterangan): ?>
                                <br><small class="text-muted"><?php echo e(Str::limit($template->keterangan, 50)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($template->tipe == 'perjanjian'): ?>
                                <span class="badge bg-primary">Perjanjian</span>
                            <?php else: ?>
                                <span class="badge bg-info">Laporan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($template->is_active): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($template->updated_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo e(route('admin.templates.show', $template)); ?>" class="btn btn-sm btn-info" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.templates.edit', $template)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#duplicateModal<?php echo e($template->id); ?>" title="Duplicate">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($template->id); ?>" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <!-- Duplicate Modal -->
                            <div class="modal fade" id="duplicateModal<?php echo e($template->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Duplicate Template</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Duplicate template <strong><?php echo e($template->nama_template); ?></strong>?<br>
                                            <small class="text-muted">Template baru akan dibuat dengan nama "<?php echo e($template->nama_template); ?> (Copy)"</small>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="<?php echo e(route('admin.templates.duplicate', $template)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-success">Duplicate</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal<?php echo e($template->id); ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Yakin ingin menghapus template <strong><?php echo e($template->nama_template); ?></strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="<?php echo e(route('admin.templates.destroy', $template)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-file-alt fa-3x mb-3" style="opacity: 0.3;"></i><br>
                            Tidak ada data template
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        <?php echo e($templates->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\templates\index.blade.php ENDPATH**/ ?>