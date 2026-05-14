<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Bagian/Bidang - RSUD Bangil</title>
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

        /* SIDEBAR */
        .dashboard-container {
            display: flex;
            flex: 1;
            gap: 0;
        }

        .sidebar {
            width: 260px;
            background: #fff;
            padding: 25px 20px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
        }

        .sidebar h3 {
            font-size: 13px;
            font-weight: 700;
            color: #999;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-menu a {
            padding: 12px 15px;
            background: #f9f9f9;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            border-left: 4px solid transparent;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu a i {
            color: #00B5A0;
            width: 18px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #E6F6F2;
            border-left-color: #00B5A0;
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
            border-left: 5px solid;
            transition: 0.3s;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-card.blue {
            border-left-color: #4C9CF0;
        }

        .stat-card.green {
            border-left-color: #00B5A0;
        }

        .stat-card.yellow {
            border-left-color: #F5E94E;
        }

        .stat-card.red {
            border-left-color: #FF2E2E;
        }

        .stat-card .label {
            font-size: 13px;
            color: #999;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 800;
            color: #1B2A41;
        }

        .stat-card .sublabel {
            font-size: 12px;
            color: #999;
            margin-top: 8px;
        }

        /* TABLE & LISTS */
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

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.waiting {
            background: #4C9CF0;
            color: white;
        }

        .status-badge.approved {
            background: #28a745;
            color: white;
        }

        .status-badge.rejected {
            background: #FF2E2E;
            color: white;
        }

        .btn-action {
            background: #00B5A0;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-action:hover {
            background: #008F7E;
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

        @media (max-width: 1024px) {
            .sidebar {
                width: 200px;
            }
            .main-content {
                padding: 20px 25px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                padding: 15px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="logo-container">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
            <span class="header-title">Dashboard Kepala Bagian/Bidang</span>
        </div>

        <nav>
            <a href="{{ route('panduan') }}">Panduan</a>
            <a href="{{ route('kontak') }}">Kontak</a>
            <a href="{{ route('tentang') }}">Tentang</a>
        </nav>

        <div class="icons">
            <i id="profileIcon" class="fas fa-user"></i>
            <i id="logoutIcon" class="fas fa-right-from-bracket"></i>

            <div id="profileMenu" class="profile-menu">
                <a href="{{ route('profil') }}"><i class="fas fa-user"></i> Profil Saya</a>
                <a href="{{ route('settings') }}"><i class="fas fa-gear"></i> Pengaturan</a>
            </div>
        </div>
    </header>

    <!-- MAIN DASHBOARD -->
    <div class="dashboard-container">
        
        <!-- SIDEBAR MENU -->
        <aside class="sidebar">
            <h3>Menu</h3>
            <div class="sidebar-menu">
                <a href="{{ route('perjanjian.index') }}" class="active">
                    <i class="fas fa-file-contract"></i>
                    Perjanjian Kinerja
                </a>
                <a href="{{ route('laporan.kinerja') }}">
                    <i class="fas fa-chart-line"></i>
                    Laporan Kinerja
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            
            <!-- PAGE HEADER -->
            <div class="page-header">
                <h1>Selamat Datang, {{ auth()->user()->nama }}</h1>
                <p>Kelola perjanjian dan laporan kinerja departemen Anda</p>
            </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="label">Perjanjian Kinerja</div>
                    <div class="number">{{ $totalPerjanjian ?? 0 }}</div>
                    <div class="sublabel">Total Dokumen</div>
                </div>
                <div class="stat-card green">
                    <div class="label">Sudah Disetujui</div>
                    <div class="number">{{ $perjanjianApproved ?? 0 }}</div>
                    <div class="sublabel">Perjanjian</div>
                </div>
                <div class="stat-card yellow">
                    <div class="label">Menunggu Approval</div>
                    <div class="number">{{ $perjanjianWaiting ?? 0 }}</div>
                    <div class="sublabel">Perjanjian</div>
                </div>
                <div class="stat-card red">
                    <div class="label">Ditolak</div>
                    <div class="number">{{ $perjanjianRejected ?? 0 }}</div>
                    <div class="sublabel">Perjanjian</div>
                </div>
            </div>

            <!-- PERJANJIAN SECTION -->
            <div class="section">
                <div class="section-title">
                    <h2><i class="fas fa-file-contract" style="color: #00B5A0; margin-right: 10px;"></i>Perjanjian Kinerja</h2>
                    <a href="{{ route('perjanjian.index') }}">Lihat Semua</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Tgl. Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px;">Belum ada perjanjian kinerja</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <!-- FOOTER -->
    <footer>© 2026 RSUD Bangil – Sistem Laporan Kinerja</footer>

    <!-- LOGOUT MODAL -->
    <div id="logoutModal" class="logout-modal">
        <div class="logout-box">
            <h3>Keluar?</h3>
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="logout-buttons">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Ya, Keluar</button>
                </form>
                <button id="cancelLogout" class="btn-cancel">Batal</button>
            </div>
        </div>
    </div>

    <script>
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
