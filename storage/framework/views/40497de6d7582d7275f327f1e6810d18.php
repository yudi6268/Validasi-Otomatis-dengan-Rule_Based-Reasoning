

<?php $__env->startSection('title', 'Persetujuan Pengguna Baru'); ?>
<?php $__env->startSection('page-title', 'Persetujuan Pengguna Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="mb-4">
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        <strong>Informasi:</strong> Daftar pengguna baru yang mendaftar dan menunggu persetujuan admin. Setelah disetujui, pengguna dapat login ke sistem.
    </div>
</div>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
        </a>
    </div>
    <div>
        <span class="badge bg-warning text-dark fs-6">
            <i class="fas fa-clock"></i> <?php echo e($users->total()); ?> Pending
        </span>
    </div>
</div>

<?php if($users->isEmpty()): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> Tidak ada pengguna yang menunggu persetujuan.
    </div>
<?php else: ?>
    <div class="data-table">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">ID Pegawai</th>
                        <th width="20%">Nama</th>
                        <th width="15%">Email</th>
                        <th width="12%">NIP</th>
                        <th width="15%">Jabatan</th>
                        <th width="10%">Tanggal Daftar</th>
                        <th width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($users->firstItem() + $index); ?></td>
                        <td>
                            <span class="badge bg-secondary"><?php echo e($user->id_pegawai); ?></span>
                        </td>
                        <td>
                            <strong><?php echo e($user->nama); ?></strong>
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-briefcase"></i> <?php echo e($user->divisi ?? '-'); ?>

                            </small>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e($user->nip); ?></td>
                        <td>
                            <span class="badge bg-info"><?php echo e($user->jabatan); ?></span>
                            <br>
                            <small class="text-muted"><?php echo e($user->pangkat); ?></small>
                        </td>
                        <td>
                            <small><?php echo e($user->created_at->format('d/m/Y H:i')); ?></small>
                            <br>
                            <small class="text-muted"><?php echo e($user->created_at->diffForHumans()); ?></small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-success" title="Setujui" onclick="showApproveModal(<?php echo e($user->id); ?>, '<?php echo e($user->nama); ?>')">
                                    <i class="fas fa-check"></i>
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-danger" title="Tolak & Hapus" onclick="showRejectModal(<?php echo e($user->id); ?>, '<?php echo e($user->nama); ?>')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            
                            <!-- Hidden forms -->
                            <form id="approveForm<?php echo e($user->id); ?>" action="<?php echo e(route('admin.users.approve', $user)); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                            
                            <form id="rejectForm<?php echo e($user->id); ?>" action="<?php echo e(route('admin.users.reject', $user)); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            <?php echo e($users->links()); ?>

        </div>
    </div>
<?php endif; ?>

<!-- Modal Approve -->
<div id="approveModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="modal-box" style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:380px;text-align:center;">
        <h3 style="margin-bottom:18px;" id="approveModalText">Setujui pendaftaran user ini?</h3>
        <p style="margin-bottom:24px;color:#666;">User akan dapat login setelah disetujui.</p>
        <div class="modal-buttons" style="display:flex;gap:16px;justify-content:center;">
            <button type="button" onclick="submitApprove()" style="background:#28a745;color:#fff;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">OK</button>
            <button type="button" onclick="hideApproveModal()" style="background:#eee;color:#333;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Cancel</button>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="custom-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="modal-box" style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:380px;text-align:center;">
        <h3 style="margin-bottom:18px;" id="rejectModalText">Tolak dan hapus pendaftaran user ini?</h3>
        <p style="margin-bottom:24px;color:#dc3545;font-weight:500;">Aksi ini tidak dapat dibatalkan!</p>
        <div class="modal-buttons" style="display:flex;gap:16px;justify-content:center;">
            <button type="button" onclick="submitReject()" style="background:#dc3545;color:#fff;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">OK</button>
            <button type="button" onclick="hideRejectModal()" style="background:#eee;color:#333;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Cancel</button>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .btn-group .btn {
        margin: 0 2px;
    }
    
    .badge {
        font-weight: 500;
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .alert-info {
        border-left-color: #0dcaf0;
    }
    
    .alert-success {
        border-left-color: #198754;
    }
    
    .custom-modal { display: none; }
    .custom-modal[style*="display: flex"] { display: flex !important; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentUserId = null;
    
    // Approve Modal Functions
    function showApproveModal(userId, userName) {
        currentUserId = userId;
        document.getElementById('approveModalText').textContent = 'Setujui pendaftaran ' + userName + '?';
        document.getElementById('approveModal').style.display = 'flex';
    }
    
    function hideApproveModal() {
        document.getElementById('approveModal').style.display = 'none';
        currentUserId = null;
    }
    
    function submitApprove() {
        if (currentUserId) {
            document.getElementById('approveForm' + currentUserId).submit();
        }
    }
    
    // Reject Modal Functions
    function showRejectModal(userId, userName) {
        currentUserId = userId;
        document.getElementById('rejectModalText').textContent = 'Tolak dan hapus pendaftaran ' + userName + '?';
        document.getElementById('rejectModal').style.display = 'flex';
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        currentUserId = null;
    }
    
    function submitReject() {
        if (currentUserId) {
            document.getElementById('rejectForm' + currentUserId).submit();
        }
    }
    
    // ESC key and click outside handlers
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideApproveModal();
                hideRejectModal();
            }
        });
        
        document.getElementById('approveModal').addEventListener('click', function(e) {
            if (e.target === this) hideApproveModal();
        });
        
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) hideRejectModal();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\users\pending.blade.php ENDPATH**/ ?>