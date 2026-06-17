

<?php $__env->startSection('title', 'Kelola Notifikasi Deadline'); ?>
<?php $__env->startSection('page-title', 'Kelola Notifikasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="data-table">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-1"><i class="fas fa-bell"></i> Notifikasi</h5>
            <small class="text-muted">Notifikasi yang dikirim admin akan muncul di pojok kiri atas dashboard setiap pengguna.</small>
        </div>
        <a href="<?php echo e(route('admin.notifications.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Notifikasi Baru
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="12%">Penerima</th>
                    <th width="28%">Judul Notifikasi</th>
                    <th width="30%">Pesan</th>
                    <th width="10%">Urgensi</th>
                    <th width="10%">Dibuat</th>
                    <th width="6%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage()); ?></td>
                        <td>
                            <?php if($notification->user_id): ?>
                                <span class="badge bg-primary" title="<?php echo e($notification->user->nama ?? 'N/A'); ?>">
                                    <i class="fas fa-user"></i> <?php echo e(\Str::limit($notification->user->nama ?? 'N/A', 14)); ?>

                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-users"></i> Semua User
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($notification->title); ?></strong>
                        </td>
                        <td>
                            <small class="text-muted"><?php echo e(\Str::limit($notification->message, 100)); ?></small>
                        </td>
                        <td>
                            <?php if($notification->type === 'danger'): ?>
                                <span class="badge bg-danger">🚨 Mendesak</span>
                            <?php elseif($notification->type === 'warning'): ?>
                                <span class="badge bg-warning text-dark">⚠️ Peringatan</span>
                            <?php elseif($notification->type === 'success'): ?>
                                <span class="badge bg-success">✅ Sukses</span>
                            <?php else: ?>
                                <span class="badge bg-info">ℹ️ Info</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small><?php echo e($notification->created_at->format('d/m/Y')); ?></small><br>
                            <small class="text-muted"><?php echo e($notification->created_at->format('H:i')); ?></small>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?php echo e($notification->id); ?>"
                                    title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>

                            <div class="modal fade" id="deleteModal<?php echo e($notification->id); ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="fas fa-trash"></i> Hapus Notifikasi</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Hapus notifikasi <strong>"<?php echo e($notification->title); ?>"</strong>? Notifikasi ini tidak akan lagi muncul di dashboard pengguna.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="<?php echo e(route('admin.notifications.destroy', $notification)); ?>" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fas fa-bell-slash fa-3x mb-3 d-block text-muted"></i>
                            Belum ada notifikasi yang dibuat.
                            <div class="mt-2">
                                <a href="<?php echo e(route('admin.notifications.create')); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus"></i> Buat Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <?php echo e($notifications->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\notifications\index.blade.php ENDPATH**/ ?>