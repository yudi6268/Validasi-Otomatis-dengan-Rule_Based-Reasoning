

<?php $__env->startSection('title', 'Kelola Notifikasi'); ?>
<?php $__env->startSection('page-title', 'Kelola Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="data-table">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5><i class="fas fa-bell"></i> Daftar Notifikasi</h5>
        <a href="<?php echo e(route('notifications.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Kirim Notifikasi Baru
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <table class="table table-hover">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="15%">Penerima</th>
                <th width="20%">Judul</th>
                <th width="35%">Pesan</th>
                <th width="10%">Tipe</th>
                <th width="10%">Tanggal</th>
                <th width="5%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage()); ?></td>
                    <td>
                        <?php if($notification->user_id): ?>
                            <span class="badge bg-primary"><?php echo e($notification->user->nama ?? 'N/A'); ?></span>
                        <?php else: ?>
                            <span class="badge bg-success"><i class="fas fa-users"></i> Semua User</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo e($notification->title); ?></strong></td>
                    <td><?php echo e(Str::limit($notification->message, 80)); ?></td>
                    <td>
                        <?php if($notification->type === 'success'): ?>
                            <span class="badge bg-success">Success</span>
                        <?php elseif($notification->type === 'warning'): ?>
                            <span class="badge bg-warning">Warning</span>
                        <?php elseif($notification->type === 'danger'): ?>
                            <span class="badge bg-danger">Danger</span>
                        <?php else: ?>
                            <span class="badge bg-info">Info</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($notification->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteModal<?php echo e($notification->id); ?>">
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal<?php echo e($notification->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus notifikasi <strong><?php echo e($notification->title); ?></strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="<?php echo e(route('notifications.destroy', $notification)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash"></i> Ya, Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        Belum ada notifikasi
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($notifications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\notifications\index.blade.php ENDPATH**/ ?>