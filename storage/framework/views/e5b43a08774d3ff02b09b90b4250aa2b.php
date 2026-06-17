<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?> — Direktur</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #E3F8F6; min-height: 100vh; display: flex; flex-direction: column; }

        /* Header */
        .top-header {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            position: sticky; top: 0; z-index: 100;
        }
        .top-header .back-btn {
            color: #009970; font-size: 20px; text-decoration: none;
            width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        }
        .top-header h1 {
            font-size: 17px; font-weight: 700; color: #009970;
            text-align: center; flex: 1;
        }
        .top-header .right-icons { display: flex; align-items: center; gap: 14px; }
        .top-header .right-icons a { color: #009970; font-size: 20px; text-decoration: none; }

        /* Content */
        .page-body { flex: 1; padding: 20px 16px 100px; max-width: 760px; margin: 0 auto; width: 100%; }

        /* Cards */
        .pk-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px 20px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            transition: box-shadow .2s;
        }
        .pk-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.13); }
        .pk-icon {
            width: 64px; height: 64px; background: #2196F3; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .pk-icon i { color: #fff; font-size: 28px; }
        .pk-info { flex: 1; min-width: 0; }
        .pk-title { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 5px; }
        .pk-badge {
            display: inline-block; padding: 4px 13px; border-radius: 12px;
            font-size: 13px; font-weight: 600; margin-bottom: 5px;
        }
        .badge-menunggu { background: #fff3cd; color: #FFA500; }
        .badge-disetujui { background: #d4edda; color: #009970; }
        .badge-ditolak   { background: #f8d7da; color: #DC3545; }
        .pk-date { font-size: 13px; color: #888; }
        .pk-reason { font-size: 12px; color: #DC3545; margin-top: 3px; }
        .pk-action { flex-shrink: 0; }
        .view-btn {
            background: none; border: none; cursor: pointer;
            font-size: 26px; color: #2196F3; padding: 6px; line-height: 1;
            transition: opacity .2s;
        }
        .view-btn:hover { opacity: .7; }

        /* Empty */
        .empty { text-align: center; padding: 60px 20px; color: #bbb; }
        .empty i { font-size: 56px; margin-bottom: 16px; display: block; }

        /* Footer */
        footer {
            background: #fff; border-top: 1px solid #dbe2ea;
            text-align: center; font-size: 13px; font-weight: 700; line-height: 1.4;
            color: #1B2A41; padding: 14px 12px; font-family: 'Segoe UI', Tahoma, sans-serif;
            position: fixed; bottom: 0; left: 0; right: 0;
        }
    </style>
</head>
<body>

    <div class="top-header">
        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1><?php echo e($pageTitle); ?></h1>
        <div class="right-icons">
            <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'profil'])); ?>" title="Profil">
                <i class="fas fa-user-circle"></i>
            </a>
            <a href="#" onclick="document.getElementById('logoutFrm').submit();" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>

    <form id="logoutFrm" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>

    <div class="page-body">
        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $badgeClass = ['disetujui'=>'badge-disetujui','menunggu'=>'badge-menunggu','ditolak'=>'badge-ditolak'][$pk['status']] ?? 'badge-menunggu';
                $badgeLabel = ['disetujui'=>'Disetujui','menunggu'=>'Menunggu','ditolak'=>'Ditolak'][$pk['status']] ?? ucfirst($pk['status']);
            ?>
            <div class="pk-card">
                <div class="pk-icon"><i class="fas fa-file-alt"></i></div>
                <div class="pk-info">
                    <div class="pk-title">Perjanjian Kinerja <?php echo e($pk['periode']); ?></div>
                    <span class="pk-badge <?php echo e($badgeClass); ?>"><?php echo e($badgeLabel); ?></span>
                    <div class="pk-date"><?php echo e($pk['tanggal']); ?></div>
                    <?php if(!empty($pk['rejection_reason'])): ?>
                        <div class="pk-reason">Alasan: <?php echo e($pk['rejection_reason']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="pk-action">
                    <button class="view-btn" title="Lihat"
                        onclick="window.open('<?php echo e($pk['print_url']); ?>', '_blank')">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty">
                <i class="fas fa-inbox"></i>
                Tidak ada data perjanjian.
            </div>
        <?php endif; ?>
    </div>

    <footer>© <?php echo e(date('Y')); ?> RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\dashboard\direktur-perjanjian-list.blade.php ENDPATH**/ ?>