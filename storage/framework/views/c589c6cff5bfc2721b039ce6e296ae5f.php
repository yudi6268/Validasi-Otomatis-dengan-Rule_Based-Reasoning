<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - RSUD Bangil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* HEADER */
        header {
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 40px;
            box-shadow: 0 4px 12px rgba(0,153,112,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-container img {
            height: 55px;
        }

        .header-title {
            font-weight: 700;
            color: #009970;
            font-size: 18px;
        }

        nav {
            display: flex;
            gap: 25px;
        }

        nav a {
            text-decoration: none;
            color: #555;
            font-weight: 600;
            font-size: 15px;
            transition: 0.3s;
        }

        nav a:hover {
            color: #00B5A0;
        }

        .icons {
            display: flex;
            align-items: center;
            gap: 20px;
            position: relative;
        }

        .icons i {
            background: #E6F6F2;
            color: #00B5A0;
            font-size: 18px;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .icons i:hover {
            background: #CFF2E9;
            transform: scale(1.05);
        }

        .profile-menu {
            position: absolute;
            top: 60px;
            right: 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 12px 0;
            display: none;
            flex-direction: column;
            min-width: 180px;
        }

        .profile-menu a {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .profile-menu a:hover {
            background: #f0f0f0;
            color: #00B5A0;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            padding: 30px 40px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            color: #1B2A41;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #999;
            font-size: 14px;
        }

        /* ALERT */
        .alert-box {
            background: #FFF3CD;
            border-left: 5px solid #FFC107;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .alert-box i {
            color: #FF9800;
            font-size: 20px;
        }

        .alert-box-content {
            flex: 1;
        }

        .alert-box-content strong {
            color: #333;
        }

        .alert-box a {
            color: #00B5A0;
            text-decoration: none;
            font-weight: 600;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border-top: 5px solid;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-card.green {
            border-top-color: #00B5A0;
            background: linear-gradient(135deg, #E6F6F2 0%, #fff 100%);
        }

        .stat-card.blue {
            border-top-color: #1E88E5;
            background: linear-gradient(135deg, #E3F2FD 0%, #fff 100%);
        }

        .stat-card.orange {
            border-top-color: #FF9800;
            background: linear-gradient(135deg, #FFF3E0 0%, #fff 100%);
        }

        .stat-card.purple {
            border-top-color: #9C27B0;
            background: linear-gradient(135deg, #F3E5F5 0%, #fff 100%);
        }

        .stat-card.red {
            border-top-color: #FF5722;
            background: linear-gradient(135deg, #FBE9E7 0%, #fff 100%);
        }

        .stat-card.indigo {
            border-top-color: #3F51B5;
            background: linear-gradient(135deg, #E8EAF6 0%, #fff 100%);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
            color: white;
        }

        .stat-card.green .icon {
            background: #00B5A0;
        }

        .stat-card.blue .icon {
            background: #1E88E5;
        }

        .stat-card.orange .icon {
            background: #FF9800;
        }

        .stat-card.purple .icon {
            background: #9C27B0;
        }

        .stat-card.red .icon {
            background: #FF5722;
        }

        .stat-card.indigo .icon {
            background: #3F51B5;
        }

        .stat-card .label {
            font-size: 13px;
            color: #999;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 800;
            color: #1B2A41;
        }

        /* TABLE SECTION */
        .section {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .section-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .section-title h2 {
            font-size: 18px;
            color: #1B2A41;
        }

        .section-title a {
            background: #00B5A0;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: 0.3s;
        }

        .section-title a:hover {
            background: #008F7E;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f9f9f9;
            padding: 12px;
            text-align: left;
            font-weight: 700;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 13px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            font-size: 13px;
        }

        table tr:hover {
            background: #f9f9f9;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            background: #00B5A0;
            color: white;
        }

        /* FOOTER */
        footer {
            background: #fff;
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 13px;
            border-top: 1px solid #e0e0e0;
        }

        /* LOGOUT MODAL */
        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.3);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .logout-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            text-align: center;
            max-width: 350px;
        }

        .logout-box h3 {
            color: #1B2A41;
            margin-bottom: 10px;
        }

        .logout-box p {
            color: #999;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .logout-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .logout-buttons button {
            padding: 10px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
            font-size: 14px;
        }

        .logout-buttons .btn-logout {
            background: #FF2E2E;
            color: white;
        }

        .logout-buttons .btn-logout:hover {
            background: #cc2424;
        }

        .logout-buttons .btn-cancel {
            background: #e0e0e0;
            color: #333;
        }

        .logout-buttons .btn-cancel:hover {
            background: #d0d0d0;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .main-content {
                padding: 20px 25px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 12px 20px;
            }
            nav {
                gap: 12px;
                font-size: 12px;
            }
            .main-content {
                padding: 15px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="logo-container">
            <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo Pemda">
            <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD">
            <span class="header-title">Dashboard Admin</span>
        </div>

        <nav>
            <a href="<?php echo e(route('panduan')); ?>">Panduan</a>
            <a href="<?php echo e(route('kontak')); ?>">Kontak</a>
            <a href="<?php echo e(route('tentang')); ?>">Tentang</a>
        </nav>

        <div class="icons">
            <i id="profileIcon" class="fas fa-user"></i>
            <i id="logoutIcon" class="fas fa-right-from-bracket"></i>

            <div id="profileMenu" class="profile-menu">
                <a href="<?php echo e(route('profil')); ?>"><i class="fas fa-user"></i> Profil Saya</a>
                <a href="<?php echo e(route('settings')); ?>"><i class="fas fa-gear"></i> Pengaturan</a>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        
        <!-- PAGE HEADER -->
        <div class="page-header">
            <h1>Dashboard Admin</h1>
            <p>Kelola sistem, pengguna, program, dan aktivitas keseluruhan</p>
        </div>

        <!-- ALERT PENDING USERS -->
        <?php
            $pendingUsers = \App\Models\User::where('status', 'pending')->count();
        ?>
        <?php if($pendingUsers > 0): ?>
            <div class="alert-box">
                <i class="fas fa-exclamation-triangle"></i>
                <div class="alert-box-content">
                    <strong>Perhatian!</strong> Ada <strong><?php echo e($pendingUsers); ?></strong> pengguna baru yang menunggu persetujuan.
                    <a href="<?php echo e(route('admin.users.pending')); ?>">Lihat sekarang →</a>
                </div>
            </div>
        <?php endif; ?>

        <!-- STATS GRID -->
        <div class="stats-grid">
            <div class="stat-card green">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="label">Total Pengguna</div>
                <div class="number"><?php echo e($totalUsers ?? 0); ?></div>
            </div>
            <div class="stat-card blue">
                <div class="icon"><i class="fas fa-file-contract"></i></div>
                <div class="label">Total Perjanjian</div>
                <div class="number"><?php echo e($totalPerjanjian ?? 0); ?></div>
            </div>
            <div class="stat-card orange">
                <div class="icon"><i class="fas fa-briefcase"></i></div>
                <div class="label">Total Jabatan</div>
                <div class="number"><?php echo e($jabatanStats->count() ?? 0); ?></div>
            </div>
            <div class="stat-card purple">
                <div class="icon"><i class="fas fa-folder-open"></i></div>
                <div class="label">Total Program</div>
                <div class="number"><?php echo e($totalPrograms ?? 0); ?></div>
            </div>
            <div class="stat-card red">
                <div class="icon"><i class="fas fa-tasks"></i></div>
                <div class="label">Total Kegiatan</div>
                <div class="number"><?php echo e($totalKegiatan ?? 0); ?></div>
            </div>
            <div class="stat-card indigo">
                <div class="icon"><i class="fas fa-list-ul"></i></div>
                <div class="label">Total Sub-Kegiatan</div>
                <div class="number"><?php echo e($totalSubKegiatan ?? 0); ?></div>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="section">
            <div class="section-title">
                <h2><i class="fas fa-users" style="color: #00B5A0; margin-right: 10px;"></i>Daftar Pengguna</h2>
                <a href="<?php echo e(route('admin.users.index')); ?>">Lihat Semua</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td><strong><?php echo e($user->nama); ?></strong></td>
                            <td><?php echo e($user->jabatan ?? '-'); ?></td>
                            <td><span class="badge"><?php echo e($user->role); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px;">Belum ada data pengguna</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PERJANJIAN TABLE -->
        <div class="section">
            <div class="section-title">
                <h2><i class="fas fa-file-contract" style="color: #00B5A0; margin-right: 10px;"></i>Perjanjian Kinerja Terbaru</h2>
                <a href="<?php echo e(route('admin.dashboard')); ?>">Lihat Semua</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentPerjanjian ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $perjanjian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td><strong><?php echo e($perjanjian->pihak1_name ?? '-'); ?></strong></td>
                            <td><?php echo e($perjanjian->created_at?->format('d/m/Y') ?? '-'); ?></td>
                            <td>
                                <?php if($perjanjian->rejected === true): ?>
                                    <span class="badge" style="background: #FF2E2E;">Ditolak</span>
                                <?php elseif(!empty($perjanjian->pihak2_signature)): ?>
                                    <span class="badge" style="background: #28a745;">Disetujui</span>
                                <?php else: ?>
                                    <span class="badge" style="background: #4C9CF0;">Menunggu</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px;">Belum ada data perjanjian</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <!-- FOOTER -->
    <footer style="margin-top:40px;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

    <!-- LOGOUT MODAL -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-box">
            <h3>Keluar?</h3>
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="logout-buttons">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-logout">Ya, Keluar</button>
                </form>
                <button id="cancelLogout" class="btn-cancel">Batal</button>
            </div>
        </div>
    </div>

    <script>
        // Profile menu toggle
        const profileIcon = document.getElementById('profileIcon');
        const profileMenu = document.getElementById('profileMenu');
        const logoutIcon = document.getElementById('logoutIcon');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');

        if (profileIcon && profileMenu) {
            profileIcon.addEventListener('click', () => {
                profileMenu.style.display = profileMenu.style.display === 'flex' ? 'none' : 'flex';
            });
            document.addEventListener('click', (e) => {
                if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
                    profileMenu.style.display = 'none';
                }
            });
        }

        // Logout functionality
        if (logoutIcon) {
            logoutIcon.addEventListener('click', () => {
                logoutModal.style.display = 'flex';
            });
        }

        if (cancelLogout) {
            cancelLogout.addEventListener('click', () => {
                logoutModal.style.display = 'none';
            });
        }

        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                logoutModal.style.display = 'none';
            }
        });
    </script>

</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\dashboard-standalone.blade.php ENDPATH**/ ?>