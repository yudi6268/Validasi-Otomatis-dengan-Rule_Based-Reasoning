<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Pimpinan - RSUD Bangil</title>
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
            height: 100vh;
            overflow: hidden;
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
            min-height: calc(100vh - 136px);
        }

        .sidebar {
            width: 260px;
            background: #fff;
            padding: 22px 18px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.05);
            overflow: auto;
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
            padding: 20px 24px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 18px;
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

        /* SEARCH AND FILTER */
        .search-filter-area {
            background: #fff;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            flex: 1;
            display: flex;
            align-items: center;
            background: #f9f9f9;
            border-radius: 8px;
            padding: 0 15px;
            border: 1px solid #e0e0e0;
        }

        .search-box input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 10px 0;
            font-size: 14px;
            outline: none;
        }

        .search-box i {
            color: #999;
        }

        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: #fff;
            padding: 16px;
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

        .stat-card.blue {
            border-top-color: #4C9CF0;
        }

        .stat-card.green {
            border-top-color: #00B5A0;
        }

        .stat-card.yellow {
            border-top-color: #F5E94E;
        }

        .stat-card.red {
            border-top-color: #FF2E2E;
        }

        .stat-card.active {
            background: #E6F6F2;
            border-top-color: #00B5A0;
        }

        .stat-card .label {
            font-size: 12px;
            color: #999;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: 800;
            color: #1B2A41;
        }

        /* TABLE */
        .section {
            background: #fff;
            border-radius: 12px;
            padding: 18px;
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
            background: #F5E94E;
            color: #222;
        }

        .status-badge.rejected {
            background: #FF2E2E;
            color: white;
        }

        .btn-action {
            background: #00B5A0;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.3s;
            margin-right: 5px;
        }

        .btn-action:hover {
            background: #008F7E;
        }

        .btn-action.reject {
            background: #FF2E2E;
        }

        .btn-action.reject:hover {
            background: #cc2424;
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

        .action-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.35);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.18);
            width: min(950px, 95%);
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-small {
            width: min(520px, 95%);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
            border-bottom: 1px solid #f0f0f0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 18px;
            color: #1B2A41;
        }

        .modal-close {
            border: none;
            background: transparent;
            font-size: 18px;
            color: #888;
            cursor: pointer;
        }

        .modal-body {
            padding: 18px 22px;
            overflow: auto;
            flex: 1;
            min-height: 300px;
        }

        .modal-body iframe {
            width: 100%;
            min-height: 65vh;
            border-radius: 12px;
        }

        .modal-body textarea {
            width: 100%;
            min-height: 160px;
            border: 1px solid #d9d9d9;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 14px;
            resize: vertical;
            color: #333;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 22px 22px;
        }

        .modal-actions .btn-action {
            min-width: 120px;
        }

        /* RESPONSIVE */
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
            }
            .search-filter-area {
                flex-direction: column;
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
            <span class="header-title">Dashboard Pimpinan</span>
        </div>

        <nav>
            <a href="{{ route('panduan') }}">Panduan</a>
            <a href="{{ route('kontak') }}">Kontak</a>
            <a href="{{ route('tentang') }}">Tentang</a>
        </nav>

        <div></div>
    </header>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <!-- MAIN DASHBOARD -->
    <div class="dashboard-container">
        
        <!-- SIDEBAR MENU -->
        @include('dashboard.partials.pimpinan-sidebar')

        <!-- MAIN CONTENT -->
        <main class="main-content">
            @php $panel = request()->query('panel'); @endphp

            @if ($panel === 'profil')
                <div class="page-header">
                    <h1>Profil</h1>
                    <p>Informasi akun pimpinan dalam panel dashboard yang sama.</p>
                </div>

                @include('dashboard.partials.profile-panel', [
                    'title' => 'Profil Pimpinan',
                    'description' => 'Profil ditampilkan langsung di dalam dashboard pimpinan agar tidak perlu membuka halaman terpisah.'
                ])
            @else
                <!-- PAGE HEADER -->
                <div class="page-header">
                    <h1>Selamat Datang, {{ auth()->user()->nama }}</h1>
                    <p>Kelola dan Review Laporan Kinerja</p>
                </div>

                <!-- SEARCH AND FILTER -->
                <div class="search-filter-area">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari nama pegawai atau tanggal...">
                    </div>
                </div>

                <!-- STATS CARDS -->
                <div class="stats-grid">
                    <div class="stat-card green active" data-filter="all">
                        <div class="label">Total Perjanjian</div>
                        <div class="number">{{ $counts['all'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card yellow" data-filter="setuju">
                        <div class="label">Disetujui</div>
                        <div class="number">{{ $counts['approved'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card blue" data-filter="menunggu">
                        <div class="label">Menunggu Approval</div>
                        <div class="number">{{ $counts['waiting'] ?? 0 }}</div>
                    </div>
                    <div class="stat-card red" data-filter="tolak">
                        <div class="label">Ditolak</div>
                        <div class="number">{{ $counts['rejected'] ?? 0 }}</div>
                    </div>
                </div>

                <!-- PERJANJIAN TABLE -->
                <div class="section">
                    <div class="section-title">
                        <h2><i class="fas fa-file-contract" style="color: #00B5A0; margin-right: 10px;"></i>Menunggu Review</h2>
                        <a href="{{ route('direktur.perjanjian.list') }}">Lihat Semua</a>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Dokumen</th>
                                <th>Unit Kerja</th>
                                <th>Periode</th>
                                <th>Diunggah Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="dataTable">
                            @forelse($perjanjians ?? [] as $i => $perjanjian)
                                <tr>
                                    <td>{{ ($perjanjians->currentPage() - 1) * $perjanjians->perPage() + $loop->iteration }}</td>
                                    <td>{{ $perjanjian->jenis ?? ($perjanjian->jenis_perjanjian ?? 'Perjanjian Kinerja') }}</td>
                                    <td>{{ $perjanjian->pihak1_jabatan ?? '-' }}</td>
                                    <td>{{ $perjanjian->periode ?? $perjanjian->created_at?->format('Y') ?? '-' }}</td>
                                    <td>{{ $perjanjian->pihak1_name ?? '-' }}</td>
                                    <td>
                                        <button type="button" class="btn-action" onclick="showPreview({{ $perjanjian->id }})">Review</button>
                                        <button type="button" class="btn-action reject" onclick="showRejectModal({{ $perjanjian->id }})">Tolak</button>
                                        <a href="{{ route('direktur.perjanjian.print', $perjanjian->id) }}" target="_blank">
                                            <button type="button" class="btn-action">Cetak</button>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px;">Belum ada data perjanjian kinerja</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div style="margin-top:12px; display:flex; justify-content:flex-end;">
                        {{ $perjanjians->links() }}
                    </div>
                </div>

                <div class="section" style="margin-top:16px;">
                    <div class="section-title">
                        <h2>Ringkasan Persetujuan (Tahun ini)</h2>
                        <a href="#">Lihat Semua</a>
                    </div>
                    <canvas id="approvalChart" height="96"></canvas>
                </div>
            @endif
        </main>
    </div>

    <!-- FOOTER -->
    <footer style="margin-top:0;background:#fff;text-align:center;font-size:12px;font-weight:700;line-height:1.4;padding:10px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

    <!-- PREVIEW MODAL -->
    <div id="previewModal" class="action-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Preview Perjanjian</h3>
                <button type="button" class="modal-close" onclick="closePreview()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <iframe id="previewIframe" src="" frameborder="0"></iframe>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-action" onclick="approvePerjanjian(currentPreviewId)">Setujui</button>
                <button type="button" class="btn-action reject" onclick="closePreview()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- REJECT MODAL -->
    <div id="rejectModal" class="action-modal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h3>Tolak Perjanjian</h3>
                <button type="button" class="modal-close" onclick="closeReject()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p>Silakan isi alasan penolakan sebelum mengirim.</p>
                <textarea id="rejectionReason" placeholder="Tulis alasan penolakan minimal 10 karakter..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-action reject" onclick="submitReject()">Kirim Penolakan</button>
                <button type="button" class="btn-action" onclick="closeReject()">Batal</button>
            </div>
        </div>
    </div>

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
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogout = document.getElementById('cancelLogout');

        function showLogoutModal() {
            if (logoutModal) {
                logoutModal.style.display = 'flex';
            }
        }

        if (cancelLogout) {
            cancelLogout.addEventListener('click', () => {
                logoutModal.style.display = 'none';
            });
        }

        if (logoutModal) {
            logoutModal.addEventListener('click', (e) => {
                if (e.target === logoutModal) {
                    logoutModal.style.display = 'none';
                }
            });
        }

        // Search and filter
        const searchInput = document.getElementById('searchInput');
        const statCards = document.querySelectorAll('.stat-card');
        const dataTable = document.getElementById('dataTable');
        let activeFilter = 'all';

        statCards.forEach(card => {
            card.addEventListener('click', () => {
                statCards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                activeFilter = card.dataset.filter;
                filterTable();
            });
        });

        if (searchInput) searchInput.addEventListener('keyup', filterTable);

        function filterTable() {
            const keyword = (searchInput?.value || '').toLowerCase();
            const rows = dataTable ? dataTable.querySelectorAll('tr') : [];

            rows.forEach(row => {
                const rowText = row.innerText.toLowerCase();

                // simple search only (status filter handled by backend via stat cards)
                const searchMatch = rowText.includes(keyword);
                row.style.display = searchMatch ? '' : 'none';
            });
        }

        const previewModal = document.getElementById('previewModal');
        const rejectModal = document.getElementById('rejectModal');
        const previewIframe = document.getElementById('previewIframe');
        const rejectionReason = document.getElementById('rejectionReason');
        let currentPreviewId = null;
        let currentRejectId = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const reviewBaseUrl = '{{ url('/dashboard/direktur/perjanjian') }}';

        function showPreview(id) {
            currentPreviewId = id;
            previewIframe.src = `${reviewBaseUrl}/${id}`;
            previewModal.style.display = 'flex';
        }

        function closePreview() {
            previewModal.style.display = 'none';
            previewIframe.src = '';
            currentPreviewId = null;
        }

        function approvePerjanjian(id) {
            if (!id) return;
            if (!confirm('Yakin ingin menyetujui perjanjian ini?')) return;
            fetch(`${reviewBaseUrl}/${id}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(async r => {
                const res = await r.json();
                alert(res.message || 'Operasi selesai');
                if (res.success) {
                    closePreview();
                    window.location.reload();
                }
            }).catch(err => {
                alert('Terjadi kesalahan: ' + err.message);
            });
        }

        function showRejectModal(id) {
            currentRejectId = id;
            rejectionReason.value = '';
            rejectModal.style.display = 'flex';
        }

        function closeReject() {
            rejectModal.style.display = 'none';
            rejectionReason.value = '';
            currentRejectId = null;
        }

        function submitReject() {
            if (!currentRejectId) return;
            const reason = rejectionReason.value.trim();
            if (reason.length < 10) {
                alert('Alasan penolakan minimal 10 karakter.');
                return;
            }
            fetch(`${reviewBaseUrl}/${currentRejectId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ rejection_reason: reason })
            }).then(async r => {
                const res = await r.json();
                alert(res.message || 'Operasi selesai');
                if (res.success) {
                    closeReject();
                    window.location.reload();
                }
            }).catch(err => {
                alert('Terjadi kesalahan: ' + err.message);
            });
        }

        // Close modals when clicking outside content
        if (previewModal) {
            previewModal.addEventListener('click', (event) => {
                if (event.target === previewModal) closePreview();
            });
        }
        if (rejectModal) {
            rejectModal.addEventListener('click', (event) => {
                if (event.target === rejectModal) closeReject();
            });
        }

        // Chart.js render
        (function() {
            const ctx = document.getElementById('approvalChart');
            if (!ctx) return;
            const labels = {!! json_encode($monthly['labels'] ?? []) !!};
            const approved = {!! json_encode($monthly['approved'] ?? []) !!};
            const rejected = {!! json_encode($monthly['rejected'] ?? []) !!};

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = () => {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Disetujui',
                                data: approved,
                                borderColor: '#00B5A0',
                                backgroundColor: 'rgba(0,181,144,0.08)',
                                tension: 0.3,
                                fill: true
                            },
                            {
                                label: 'Ditolak',
                                data: rejected,
                                borderColor: '#FF2E2E',
                                backgroundColor: 'rgba(255,46,46,0.08)',
                                tension: 0.3,
                                fill: true
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' }
                        }
                    }
                });
            };
            document.head.appendChild(script);
        })();
    </script>

</body>
</html>

