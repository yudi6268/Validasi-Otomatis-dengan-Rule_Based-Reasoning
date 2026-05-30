

<?php $__env->startSection('content'); ?>
<div style="max-width:700px;margin:40px auto;padding:32px;background:#f8fafd;border-radius:16px;box-shadow:0 2px 16px rgba(0,0,0,0.08);">
    <h2 style="text-align:center;font-weight:700;margin-bottom:24px;color:#2c2c2c;">ALASAN MENOLAK</h2>
    <form method="POST" action="/perjanjian/<?php echo e($perjanjian->id); ?>/tolak">
        <?php echo csrf_field(); ?>
        <div style="background:#fff6f6;padding:24px;border-radius:12px;border:1px solid #f5c2c7;">
            <div style="margin-bottom:16px;">
                <input type="text" name="nama" value="<?php echo e($user->nama); ?>" placeholder="Nama Lengkap" readonly style="width:100%;padding:10px;border-radius:6px;border:1px solid #bbb;">
            </div>
            <div style="margin-bottom:16px;">
                <input type="text" name="jabatan" value="<?php echo e($user->jabatan); ?>" placeholder="Jabatan" readonly style="width:100%;padding:10px;border-radius:6px;border:1px solid #bbb;">
            </div>
            <div style="margin-bottom:16px;">
                <input type="text" name="tanggal" value="<?php echo e(date('d-m-Y')); ?>" placeholder="Tanggal" readonly style="width:100%;padding:10px;border-radius:6px;border:1px solid #bbb;">
            </div>
            <div style="margin-bottom:16px;">
                <textarea name="alasan" placeholder="Tulis Alasan" required style="width:100%;height:120px;padding:10px;border-radius:6px;border:1px solid #bbb;"></textarea>
            </div>
            <button type="submit" style="width:100%;background:#009e6d;color:#fff;font-weight:700;padding:12px 0;border:none;border-radius:8px;font-size:1.1rem;">KIRIM ALASAN</button>
            <?php if(session('success')): ?>
                <div style="margin-top:16px;color:#009e6d;display:flex;align-items:center;gap:8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" viewBox="0 0 16 16"><path d="M13.485 1.929a1 1 0 0 1 0 1.414l-7.071 7.071a1 1 0 0 1-1.414 0L2.515 8.071a1 1 0 1 1 1.414-1.414l1.071 1.071 6.364-6.364a1 1 0 0 1 1.414 0z"/></svg>
                    Alasan Anda telah terkirim. Terima kasih.
                </div>
            <?php endif; ?>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\perjanjian\tolak.blade.php ENDPATH**/ ?>