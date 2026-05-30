

<?php
    $backRoute = route('dashboard.wadir', ['panel' => 'laporan']);
    $laporanKinerjaUrl = route('laporan.kinerja');
?>

<?php $__env->startSection('title', $pageTitle); ?>
<?php $__env->startSection('header_title', $pageTitle); ?>

<?php $__env->startSection('back'); ?>
<a href="<?php echo e($backRoute); ?>" id="headerBackBtn"><i class="fa-solid fa-arrow-left header-icon"></i></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    body { background: #E3F8F6; }

    .dashboard-container {
        max-width: 100%;
        margin: 0;
        padding: 20px;
        padding-bottom: 80px;
        min-height: 100vh;
    }

    .card-list {
        max-width: 900px;
        margin: 0 auto;
    }

    .perjanjian-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        gap: 20px;
        align-items: center;
        transition: box-shadow 0.3s;
        cursor: pointer;
    }
    .perjanjian-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

    @media (min-width: 768px) {
        .perjanjian-card { padding: 30px; gap: 25px; }
    }

    .card-icon {
        background: #2196F3;
        width: 70px;
        height: 70px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .card-icon i { color: white; font-size: 32px; }

    @media (min-width: 768px) {
        .card-icon { width: 80px; height: 80px; }
        .card-icon i { font-size: 36px; }
    }

    .card-content { flex: 1; }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    @media (min-width: 768px) {
        .card-title { font-size: 20px; }
    }

    .card-employee {
        font-size: 13px;
        color: #888;
        margin-bottom: 6px;
    }

    .card-status {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    @media (min-width: 768px) {
        .card-status { padding: 7px 16px; font-size: 14px; }
    }

    .status-terkirim  { background: #d4edda; color: #009970; }
    .status-menunggu  { background: #fff3cd; color: #FFA500; }
    .status-ditolak   { background: #f8d7da; color: #DC3545; }
    .status-disetujui { background: #d4edda; color: #009970; }

    .card-date { font-size: 14px; color: #666; }
    @media (min-width: 768px) { .card-date { font-size: 15px; } }

    .card-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 22px;
        padding: 10px;
        transition: opacity 0.3s;
        text-decoration: none;
    }
    @media (min-width: 768px) {
        .action-btn { font-size: 24px; padding: 12px; }
    }
    .action-btn:hover { opacity: 0.7; }
    .btn-view     { color: #2196F3; }
    .btn-edit     { color: #FF9800; }
    .btn-delete   { color: #F44336; }
    .btn-download { color: #4CAF50; }

    /* Delete confirmation modal */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 12px;
        padding: 28px 24px;
        max-width: 360px;
        width: 90%;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
    .modal-box h3 { margin: 0 0 10px; font-size: 17px; color: #333; }
    .modal-box p  { margin: 0 0 20px; font-size: 14px; color: #666; }
    .modal-btn-row { display: flex; gap: 12px; justify-content: center; }
    .modal-btn-cancel { flex: 1; padding: 10px; border: 1.5px solid #ccc; border-radius: 8px; background: #fff; color: #555; font-size: 14px; cursor: pointer; }
    .modal-btn-delete { flex: 1; padding: 10px; border: none; border-radius: 8px; background: #F44336; color: #fff; font-size: 14px; font-weight: 600; cursor: pointer; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }
    .empty-state i { font-size: 64px; margin-bottom: 20px; color: #ddd; }
</style>


<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <h3>Hapus Laporan?</h3>
        <p>Laporan kinerja ini akan dihapus permanen dan tidak dapat dikembalikan.</p>
        <div class="modal-btn-row">
            <button class="modal-btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="flex:1">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="modal-btn-delete" style="width:100%">Hapus</button>
            </form>
        </div>
    </div>
</div>

<div class="dashboard-container">
    <div class="card-list">
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $pdfViewUrl = route('laporan.pdf.preview', $item['id']);
                $pdfDownloadUrl = route('laporan.pdf.preview', $item['id']) . '?download=1';
                $editUrl = $laporanKinerjaUrl
                    . '?section=laporan&from=dashboard_wadir_laporan'
                    . '&perjanjian_id=' . $item['perjanjian_id']
                    . '&laporan_id=' . $item['id'] . '&mode=edit';
                $deleteUrl = route('laporan.destroy', $item['id']);
                $triwulanText = $item['triwulan'] ? 'Triwulan ' . $item['triwulan'] : '-';
                $isApproved = $item['status'] === 'disetujui';
            ?>
            <div class="perjanjian-card" onclick="window.open('<?php echo e($pdfViewUrl); ?>','_blank')">
                <div class="card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Laporan Kinerja <?php echo e($item['tahun'] ?? date('Y')); ?></div>
                    <div class="card-employee"><?php echo e($item['employee_name']); ?></div>
                    <div class="card-status status-<?php echo e($item['status']); ?>">
                        <?php
                            $labels = ['terkirim'=>'Terkirim','menunggu'=>'Menunggu','ditolak'=>'Ditolak','disetujui'=>'Disetujui'];
                        ?>
                        <?php echo e($labels[$item['status']] ?? ucfirst($item['status'])); ?>

                    </div>
                    <div class="card-date"><?php echo e($triwulanText); ?></div>
                </div>
                <div class="card-actions" onclick="event.stopPropagation()">
                    
                    <a href="<?php echo e($pdfViewUrl); ?>" target="_blank" class="action-btn btn-view" title="Lihat PDF">
                        <i class="fas fa-eye"></i>
                    </a>

                    <?php if($isApproved): ?>
                        
                        <a href="<?php echo e($pdfDownloadUrl); ?>" class="action-btn btn-download" title="Download PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    <?php else: ?>
                        
                        <a href="<?php echo e($editUrl); ?>" class="action-btn btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="action-btn btn-delete" title="Hapus"
                            onclick="confirmDelete('<?php echo e($deleteUrl); ?>', '<?php echo e($item['employee_name']); ?>')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada laporan kinerja<?php echo e($statusFilter ? ' dengan status ini' : ''); ?>.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmDelete(url, name) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.add('active');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('active');
}
// Close on overlay click
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/laporan/wadir-index.blade.php ENDPATH**/ ?>