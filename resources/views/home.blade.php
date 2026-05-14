<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - RSUD Bangil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f2fbf8;
            min-height: 100vh;
            color: #1d2d3d;
        }

        .page-shell {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
            gap: 24px;
        }

        .sidebar {
            background: #ffffff;
            padding: 28px 22px;
            border-radius: 28px;
            box-shadow: 0 16px 45px rgba(0, 0, 0, 0.06);
            position: sticky;
            top: 24px;
            align-self: start;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .sidebar .brand img {
            height: 60px;
            width: auto;
        }

        .sidebar h2 {
            font-size: 18px;
            font-weight: 700;
            color: #0f4d3d;
        }

        .sidebar .menu-title {
            margin-top: 34px;
            margin-bottom: 12px;
            font-size: 12px;
            letter-spacing: 0.15em;
            color: #6f8880;
            text-transform: uppercase;
        }

        .menu-list {
            display: grid;
            gap: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 16px;
            text-decoration: none;
            color: #2c3a44;
            background: #f7fdfb;
            font-weight: 600;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .menu-item.active,
        .menu-item:hover {
            background: #e6faf1;
            border-left-color: #00b58f;
            color: #007457;
        }

        .menu-item i {
            font-size: 16px;
            width: 24px;
            text-align: center;
            color: #00b58f;
        }

        .sidebar .section {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #edf8f5;
        }

        .sidebar .section h3 {
            font-size: 13px;
            font-weight: 700;
            color: #4f6e67;
            margin-bottom: 16px;
        }

        .sidebar .profile-card {
            display: grid;
            gap: 10px;
            padding: 16px;
            border-radius: 18px;
            background: #f8fffb;
        }

        .profile-card .avatar {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            background: #00b58f;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 20px;
            font-weight: 800;
        }

        .profile-card .info {
            display: grid;
            gap: 4px;
        }

        .profile-card .name {
            font-weight: 700;
            color: #16333a;
        }

        .profile-card .role {
            font-size: 13px;
            color: #5f7b74;
        }

        .main-area {
            padding: 24px 0 40px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 28px;
        }

        .topbar .greeting {
            display: grid;
            gap: 10px;
        }

        .topbar .greeting span {
            display: inline-block;
            font-size: 14px;
            color: #55716d;
        }

        .topbar .greeting h1 {
            font-size: 34px;
            line-height: 1.05;
            color: #12282f;
            max-width: 700px;
        }

        .topbar .actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .topbar .button {
            padding: 12px 18px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, #00b58f, #00896f);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .topbar .button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(0, 181, 144, 0.18);
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card-panel {
            background: #fff;
            border-radius: 24px;
            padding: 28px 24px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
        }

        .card-panel::before {
            content: '';
            position: absolute;
            top: -24px;
            right: -24px;
            width: 110px;
            height: 110px;
            background: rgba(0, 181, 144, 0.1);
            border-radius: 50%;
        }

        .card-panel .title {
            font-size: 13px;
            color: #5f7b74;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .card-panel .value {
            font-size: 36px;
            font-weight: 800;
            color: #12282f;
            margin-bottom: 8px;
        }

        .card-panel .help {
            font-size: 13px;
            color: #6f8b82;
            line-height: 1.7;
        }

        .content-block {
            display: grid;
            gap: 24px;
        }

        .section-card {
            background: #fff;
            border-radius: 28px;
            padding: 28px 26px;
            box-shadow: 0 18px 42px rgba(0, 0, 0, 0.05);
        }

        .section-card .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 22px;
        }

        .section-card .section-header h2 {
            font-size: 20px;
            color: #12282f;
            font-weight: 800;
        }

        .section-card .section-header a {
            color: #00b58f;
            text-decoration: none;
            font-weight: 700;
            border: 1px solid #d7f0ea;
            padding: 10px 18px;
            border-radius: 14px;
            background: #f5fdf8;
        }

        .section-card table {
            width: 100%;
            border-collapse: collapse;
        }

        .section-card thead th {
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid #edf7f3;
            font-size: 13px;
            color: #5e7871;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .section-card tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f8f3;
            color: #475552;
            font-size: 14px;
        }

        .section-card tbody tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
        }

        .badge.waiting { background: #f4b400; }
        .badge.approved { background: #00b58f; }
        .badge.rejected { background: #ff5c5c; }

        .section-card .empty-row {
            text-align: center;
            padding: 30px 0;
            color: #7a8d86;
        }

        .section-card .action-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #00b58f;
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .section-card .action-link:hover {
            text-decoration: underline;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(17, 30, 27, 0.48);
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal-box {
            width: min(360px, calc(100% - 40px));
            background: #fff;
            border-radius: 24px;
            padding: 32px 28px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.12);
        }

        .modal-box h3 {
            font-size: 22px;
            margin-bottom: 12px;
            color: #12282f;
        }

        .modal-box p {
            color: #5e7a70;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .modal-actions {
            display: flex;
            gap: 14px;
            justify-content: flex-end;
            flex-wrap: wrap;
        }

        .modal-actions button,
        .modal-actions a {
            min-width: 120px;
            border: none;
            border-radius: 14px;
            padding: 12px 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-secondary {
            background: #eef8f3;
            color: #225a43;
        }

        .btn-primary {
            background: #00b58f;
            color: white;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            opacity: 0.95;
        }

        @media (max-width: 1140px) {
            .page-shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 860px) {
            .grid-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .topbar {
                align-items: stretch;
            }

            .topbar .greeting h1 {
                font-size: 28px;
            }

            .grid-cards {
                grid-template-columns: 1fr;
            }

            .menu-item {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <aside class="sidebar">
            <div class="brand">
                <img src="{{ asset('images/logo_rsud.png') }}" alt="RSUD Bangil">
                <div>
                    <div style="font-size: 18px; font-weight: 700; color: #0f4d3d;">RSUD Bangil</div>
                    <div style="font-size: 13px; color: #5f7b74;">Dashboard User</div>
                </div>
            </div>

            <div class="profile-card">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
                <div class="info">
                    <div class="name">{{ auth()->user()->nama }}</div>
                    <div class="role">{{ auth()->user()->jabatan ?? 'Pengguna' }}</div>
                </div>
            </div>

            <div class="section">
                <div class="menu-title">Navigasi</div>
                <div class="menu-list">
                    <a href="{{ route('home') }}" class="menu-item active">
                        <i class="fas fa-chart-pie"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('perjanjian.index') }}" class="menu-item">
                        <i class="fas fa-file-contract"></i>
                        Perjanjian Kinerja
                    </a>
                    <a href="{{ route('laporan.kinerja') }}" class="menu-item">
                        <i class="fas fa-chart-line"></i>
                        Laporan Kinerja
                    </a>
                    <a href="{{ route('profil') }}" class="menu-item">
                        <i class="fas fa-user"></i>
                        Profil Saya
                    </a>
                    <a href="#" id="logoutButton" class="menu-item">
                        <i class="fas fa-right-from-bracket"></i>
                        Keluar
                    </a>
                </div>
            </div>
        </aside>

        <section class="main-area">
            <div class="topbar">
                <div class="greeting">
                    <span>Halo, {{ auth()->user()->nama }}</span>
                    <h1>Dashboard Perjanjian & Laporan Kinerja</h1>
                </div>
                <div class="actions">
                    <a href="{{ route('perjanjian.index') }}" class="button"><i class="fas fa-file-contract"></i> Perjanjian</a>
                    <a href="{{ route('laporan.kinerja') }}" class="button"><i class="fas fa-chart-line"></i> Laporan</a>
                </div>
            </div>

            <div class="grid-cards">
                <div class="card-panel">
                    <div class="title">Perjanjian Kinerja</div>
                    <div class="value">{{ $totalPerjanjian ?? 0 }}</div>
                    <div class="help">Jumlah perjanjian yang Anda buat.</div>
                </div>
                <div class="card-panel">
                    <div class="title">Disetujui</div>
                    <div class="value">{{ $perjanjianApproved ?? 0 }}</div>
                    <div class="help">Perjanjian yang sudah disetujui pihak terkait.</div>
                </div>
                <div class="card-panel">
                    <div class="title">Menunggu Review</div>
                    <div class="value">{{ $perjanjianWaiting ?? 0 }}</div>
                    <div class="help">Perjanjian yang sedang menunggu tanda tangan atau validasi.</div>
                </div>
            </div>

            <div class="content-block">
                <div class="section-card">
                    <div class="section-header">
                        <h2>Perjanjian Kinerja Terbaru</h2>
                        <a href="{{ route('perjanjian.index') }}">Lihat Semua</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Tgl dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perjanjians as $perjanjian)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ Str::limit($perjanjian->judul ?? 'Perjanjian Kinerja', 30) }}</td>
                                    <td>
                                        @if(empty($perjanjian->pihak2_signature) && (empty($perjanjian->rejected) || !$perjanjian->rejected))
                                            <span class="badge waiting">Menunggu</span>
                                        @elseif(!empty($perjanjian->pihak2_signature) && (empty($perjanjian->rejected) || !$perjanjian->rejected))
                                            <span class="badge approved">Disetujui</span>
                                        @else
                                            <span class="badge rejected">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $perjanjian->created_at?->format('d M Y') ?? '-' }}</td>
                                    <td><a class="action-link" href="{{ route('perjanjian.edit', $perjanjian->id) }}"><i class="fas fa-pen"></i> Edit</a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="empty-row">Belum ada perjanjian kinerja. Silakan buat perjanjian baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <h2>Menu Cepat</h2>
                        <a href="{{ route('profil') }}">Profil Saya</a>
                    </div>
                    <div style="display: grid; gap: 16px;">
                        <a href="{{ route('perjanjian.index') }}" class="action-link"><i class="fas fa-file-contract"></i> Buka Perjanjian Kinerja</a>
                        <a href="{{ route('laporan.kinerja') }}" class="action-link"><i class="fas fa-chart-line"></i> Buka Laporan Kinerja</a>
                        <a href="{{ route('home') }}" class="action-link"><i class="fas fa-home"></i> Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="logoutModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Keluar dari aplikasi?</h3>
            <p>Pastikan semua pekerjaan Anda sudah tersimpan sebelum keluar.</p>
            <div class="modal-actions">
                <button type="button" class="btn-secondary" id="cancelModal">Batal</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-primary">Keluar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const logoutButton = document.getElementById('logoutButton');
        const logoutModal = document.getElementById('logoutModal');
        const cancelModal = document.getElementById('cancelModal');

        if (logoutButton) {
            logoutButton.addEventListener('click', function(e) {
                e.preventDefault();
                logoutModal.style.display = 'grid';
            });
        }

        if (cancelModal) {
            cancelModal.addEventListener('click', function() {
                logoutModal.style.display = 'none';
            });
        }

        if (logoutModal) {
            logoutModal.addEventListener('click', function(event) {
                if (event.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
