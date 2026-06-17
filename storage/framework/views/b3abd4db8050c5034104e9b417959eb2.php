<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?> - RSUD Bangil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #00B5A0;
            --secondary-color: #009B8D;
            --accent-color: #00D4BA;
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --dark-bg: #1B2A41;
            --sidebar-bg: #16213e;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E3F8F6, #D6F5EF);
            min-height: 100vh;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            padding: 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.3);
        }

        .sidebar-header {
            padding: 25px 20px;
            background: var(--dark-bg);
            border-bottom: 2px solid #00B5A0;
        }

        .sidebar-header h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
        }

        .sidebar-header p {
            color: #b0bec5;
            margin: 5px 0 0 0;
            font-size: 0.85rem;
        }

        .sidebar-nav {
            padding: 25px 0 0 0;
        }

        .nav-item {
            margin: 0 0 10px 0;
        }

        .nav-item:last-child {
            margin-bottom: 0;
        }

        .sidebar .nav-link {
            border-radius: 8px;
            margin: 0 10px;
        }

        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .nav-link {
            color: #b0bec5;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(0, 181, 160, 0.1);
            color: #00D4BA;
            border-left-color: #00B5A0;
        }

        .nav-link.active {
            background: rgba(0, 181, 160, 0.15);
            color: #fff;
            border-left-color: #00B5A0;
        }

        .nav-link i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0;
            min-height: 100vh;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .top-navbar {
            background: #fff;
            padding: 12px 22px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title h3 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #00B5A0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
        }

        /* Content Area */
        .content-area {
            padding: 16px;
            overflow-x: hidden;
            overflow-y: auto;
            flex: 1;
        }

        /* Cards */
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-left: 4px solid #00B5A0;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 181, 160, 0.15);
        }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .stat-card.primary { border-left-color: #00B5A0; }
        .stat-card.primary .icon { background: rgba(0, 181, 160, 0.1); color: #00B5A0; }

        .stat-card.success { border-left-color: var(--success-color); }
        .stat-card.success .icon { background: rgba(76, 175, 80, 0.1); color: var(--success-color); }

        .stat-card.warning { border-left-color: var(--warning-color); }
        .stat-card.warning .icon { background: rgba(255, 152, 0, 0.1); color: var(--warning-color); }

        .stat-card.danger { border-left-color: var(--danger-color); }
        .stat-card.danger .icon { background: rgba(244, 67, 54, 0.1); color: var(--danger-color); }

        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0 5px 0;
        }

        .stat-card p {
            color: #757575;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Tables */
        .data-table {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            overflow-x: auto;
        }

        .data-table table {
            margin-bottom: 0;
            min-width: 100%;
            table-layout: auto;
        }

        .table thead th {
            background: var(--primary-color);
            color: #fff;
            border: none;
            font-weight: 600;
            padding: 12px;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #00B5A0;
            border: none;
        }

        .btn-primary:hover {
            background: #009B8D;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,181,160,0.3);
        }

        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: var(--card-shadow);
        }

        /* Badges */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--dark-bg);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #00B5A0;
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .content-area {
                padding: 15px;
            }
            
            .stat-card {
                padding: 15px;
                min-height: auto;
            }
            
            .data-table {
                padding: 15px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .content-area {
                padding: 10px;
            }
            
            .stat-card {
                padding: 12px;
                min-height: auto;
                margin-bottom: 10px;
            }
            
            .data-table {
                padding: 12px;
                border-radius: 8px;
            }
            
            .table thead th {
                padding: 8px;
                font-size: 0.8rem;
            }
            
            .table tbody td {
                padding: 8px;
                font-size: 0.8rem;
            }
            
            .btn {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            
            .form-control, .form-select {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            :root {
                --sidebar-width: 0;
            }
            
            .top-navbar {
                padding: 12px 15px;
            }
            
            .navbar-title h3 {
                font-size: 1.2rem;
            }
            
            .content-area {
                padding: 8px;
            }
            
            .row {
                margin-right: -4px;
                margin-left: -4px;
            }
            
            .col-12, [class*="col-"] {
                padding-right: 4px;
                padding-left: 4px;
            }
            
            .stat-card {
                padding: 10px;
                min-height: auto;
            }
            
            .data-table {
                padding: 10px;
            }
            
            .table thead th {
                padding: 6px;
                font-size: 0.75rem;
            }
            
            .table tbody td {
                padding: 6px;
                font-size: 0.75rem;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-hospital"></i> RSUD Bangil</h4>
            <p>Admin Panel</p>
        </div>
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') && !request()->query('section') ? 'active' : ''); ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users.index', 'admin.users.create', 'admin.users.edit', 'admin.users.pending') ? 'active' : ''); ?>">
                        <i class="fas fa-users"></i> Kelola Pengguna
                        <?php $pendingCount = \App\Models\User::where('status', 'pending')->count(); ?>
                        <?php if($pendingCount > 0): ?>
                            <span class="badge bg-warning text-dark ms-auto"><?php echo e($pendingCount); ?></span>
                        <?php endif; ?>
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.jabatan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.jabatan.*') ? 'active' : ''); ?>">
                        <i class="fas fa-briefcase"></i> Kelola Jabatan
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.program.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.program.*', 'admin.kegiatan.*', 'admin.sub-kegiatan.*') ? 'active' : ''); ?>">
                        <i class="fas fa-sitemap"></i> Kelola Program/Kegiatan
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.perjanjian.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.perjanjian.*') ? 'active' : ''); ?>">
                        <i class="fas fa-file-contract"></i> Kelola Perjanjian
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.laporan.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.laporan.*') ? 'active' : ''); ?>">
                        <i class="fas fa-chart-line"></i> Kelola Laporan
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.settings.*', 'admin.triwulan.*') ? 'active' : ''); ?>">
                        <i class="fas fa-calendar-alt"></i> Kelola Tahun
                    </a>
                </li>

                
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard', ['section' => 'profile'])); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') && request()->query('section') === 'profile' ? 'active' : ''); ?>">
                        <i class="fas fa-user"></i> Profil
                    </a>
                </li>

                
                <li class="nav-item" style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 12px;">
                    <a href="#" onclick="showLogoutModal(); return false;" class="nav-link" style="color:#ff6b6b;">
                        <i class="fas fa-sign-out-alt" style="color:#ff6b6b;"></i> Keluar
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="navbar-title">
                <h3><?php echo $__env->yieldContent('page-title', 'Admin Dashboard'); ?></h3>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo e(strtoupper(substr(auth()->user()->nama, 0, 1))); ?>

                </div>
                <div>
                    <strong><?php echo e(auth()->user()->nama); ?></strong><br>
                    <small class="text-muted">Administrator</small>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?php echo session('success'); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo session('error'); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('components.status-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\admin\layout.blade.php ENDPATH**/ ?>