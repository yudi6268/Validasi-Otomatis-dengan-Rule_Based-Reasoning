<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Perjanjian Kinerja - Dashboard Direktur</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #E3F8F6;
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: #fff;
            padding: 15px 40px;
            box-shadow: 0 4px 12px rgba(0,153,112,0.15);
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-icon {
            font-size: 24px;
            color: #009970;
            cursor: pointer;
            transition: color 0.3s;
        }

        .back-icon:hover {
            color: #007a5a;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-container img {
            height: 60px;
        }

        .system-title {
            font-size: 24px;
            font-weight: 700;
            color: #009970;
            text-align: center;
            flex: 1;
        }

        .icons {
            display: flex;
            gap: 15px;
            align-items: center;
            position: relative;
        }

        .icons i {
            font-size: 20px;
            cursor: pointer;
            color: #333;
        }

        .logout-button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #333;
        }

        .profile-menu {
            display: none;
            position: absolute;
            top: 35px;
            right: 0;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 10px 0;
            min-width: 180px;
            z-index: 100;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }

        .profile-menu a:hover {
            background: #f5f5f5;
        }

        .profile-menu i {
            font-size: 16px;
        }

        .dashboard-container {
            padding: 30px 40px;
            min-height: calc(100vh - 120px - 50px);
        }

        .search-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin: 0 auto 30px auto;
            display: none; /* Hidden - search available on perjanjian list page */
        }

        .search-container input {
            padding: 10px 40px 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        .search-container i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            cursor: pointer;
        }

        .stats-cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 30px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .stat-card {
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            aspect-ratio: 1 / 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .stat-card .number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
        }

        .stat-card .label {
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-bottom: 15px;
        }

        .stat-card .view-btn {
            background: white;
            color: #333;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .stat-card .view-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .stat-card.green { background: #009970; }
        .stat-card.yellow { background: #FFD700; }
        .stat-card.red { background: #DC3545; }
        .stat-card.blue { background: #2196F3; }

        .content-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-top: 30px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .content-header h2 {
            font-size: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            text-align: left;
            padding: 12px;
            font-weight: 600;
            font-size: 14px;
            color: #555;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-approved {
            background: #d4edda;
            color: #009970;
            font-weight: 600;
        }

        .status-waiting {
            background: #fff3cd;
            color: #FFA500;
            font-weight: 600;
        }

        .status-rejected {
            background: #f8d7da;
            color: #DC3545;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-view {
            background: #2196F3;
            color: white;
        }

        .btn-view:hover {
            background: #1976D2;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-body label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .modal-body textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            resize: vertical;
            min-height: 120px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
        }

        .modal-btn-cancel {
            background: #6c757d;
            color: white;
        }

        .modal-btn-cancel:hover {
            background: #5a6268;
        }

        .modal-btn-submit {
            background: #DC3545;
            color: white;
        }

        .modal-btn-submit:hover {
            background: #c82333;
        }

        /* Notifikasi & Aktivitas */
        .notification-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-top: 30px;
        }

        .notification-header {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .notification-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .notification-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .notification-item:hover {
            background: #e9ecef;
        }

        .notification-content {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .notification-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .notification-icon.approved {
            color: #009970;
        }

        .notification-icon.rejected {
            color: #DC3545;
        }

        .notification-text {
            font-size: 14px;
            color: #333;
        }

        .notification-time {
            font-size: 12px;
            color: #999;
            white-space: nowrap;
        }

        .notification-footer {
            margin-top: 15px;
            text-align: center;
        }

        .btn-more {
            background: #009970;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s;
        }

        .btn-more:hover {
            background: #007a5a;
        }

        .notification-item.hidden {
            display: none;
        }

        footer {
            background: #00B5A0;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-top">
            <div class="header-left">
                <i class="fas fa-arrow-left back-icon" style="cursor: pointer;"></i>
                <div class="logo-container">
                    <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo Pemda">
                    <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD">
                </div>
            </div>

            <div class="system-title">SISTEM LAPORAN KINERJA RSUD BANGIL</div>

            <div class="icons">
                <i id="profileIcon" class="fa-solid fa-user"></i>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="logout-button">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>

                <div id="profileMenu" class="profile-menu">
                    <a href="<?php echo e(route('profil')); ?>"><i class="fa-solid fa-user"></i>Profil Saya</a>
                    <a href="<?php echo e(route('settings')); ?>"><i class="fa-solid fa-gear"></i>Settings</a>
                    <a href="<?php echo e(route('kontak')); ?>"><i class="fa-solid fa-phone"></i>Kontak</a>
                    <a href="<?php echo e(route('tentang')); ?>"><i class="fa-solid fa-info-circle"></i>Tentang</a>
                </div>
            </div>
        </div>
    </header>

    <div class="dashboard-container">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="cari nama pegawai, perjanjian, status, tanggal" style="width:100%;padding:10px 40px 10px 15px;border:1px solid #ddd;border-radius:25px;font-size:15px;">
            <i class="fas fa-search" style="position:absolute;right:15px;top:50%;transform:translateY(-50%);color:#999;"></i>
        </div>

        <div class="stats-cards">
            <div class="stat-card green" data-filter="all">
                <div class="label">Total Laporan Diterima</div>
                <div class="number" id="count-all">0</div>
                <button class="view-btn">Lihat</button>
            </div>
            <div class="stat-card yellow" data-filter="approved">
                <div class="label">Disetujui</div>
                <div class="number" id="count-approved">0</div>
                <button class="view-btn">Lihat</button>
            </div>
            <div class="stat-card red" data-filter="rejected">
                <div class="label">Ditolak</div>
                <div class="number" id="count-rejected">0</div>
                <button class="view-btn">Lihat</button>
            </div>
            <div class="stat-card blue" data-filter="waiting">
                <div class="label">Menunggu Persetujuan</div>
                <div class="number" id="count-waiting">0</div>
                <button class="view-btn">Lihat</button>
            </div>
        </div>

        <div class="content-card">
            <div class="content-header">
                <h2 id="table-title">Perjanjian Kinerja Terbaru</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Pegawai</th>
                        <th>Jenis Perjanjian</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr>
                        <td colspan="7" class="no-data">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Notifikasi & Aktivitas section removed -->
    </div>

    <footer style="margin-top:40px;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

    <!-- Modal Reject -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Tolak Perjanjian</div>
            <div class="modal-body">
                <label for="rejectionReason">Alasan Penolakan: <span style="color: red;">*</span></label>
                <textarea id="rejectionReason" placeholder="Masukkan alasan mengapa perjanjian ditolak (minimal 10 karakter)"></textarea>
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="closeRejectModal()">Batal</button>
                <button class="modal-btn modal-btn-submit" onclick="submitRejection()">Tolak Perjanjian</button>
            </div>
        </div>
    </div>

    <script>
        // Dashboard for Direktur - Perjanjian Kinerja
        // Clicking cards navigates to perjanjian list page

        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== Dashboard Perjanjian Kinerja Loaded ===');
            loadData('all');
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    loadData('all');
                });
            }
            console.log('=== Dashboard Initialization Complete ===');
        });

        function updateActiveCard() {
            document.querySelectorAll('.stat-card').forEach(card => {
                card.classList.remove('active');
                if (card.dataset.filter === currentFilter) {
                    card.classList.add('active');
                }
            });
        }

        function loadData(filter = 'all') {
            fetch(`<?php echo e(route('direktur.perjanjian')); ?>?filter=${filter}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('count-all').textContent = data.counts.all;
                document.getElementById('count-approved').textContent = data.counts.approved;
                document.getElementById('count-rejected').textContent = data.counts.rejected || 0;
                document.getElementById('count-waiting').textContent = data.counts.waiting;

                const titles = {
                    'all': 'Perjanjian Kinerja Terbaru',
                    'approved': 'Perjanjian Disetujui',
                    'rejected': 'Perjanjian Ditolak',
                    'waiting': 'Perjanjian Menunggu Tanda Tangan'
                };
                document.getElementById('table-title').textContent = titles[filter] || titles.all;

                const tbody = document.getElementById('table-body');
                if (data.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="no-data">Tidak ada data</td></tr>';
                    return;
                }

                let html = '';
                const searchValue = (document.getElementById('searchInput')?.value || '').toLowerCase();
                data.data.forEach((item, index) => {
                    let statusClass = 'status-waiting';
                    let statusText = 'Menunggu';
                    if (item.status === 'approved') {
                        statusClass = 'status-approved';
                        statusText = 'Disetujui';
                    } else if (item.status === 'rejected') {
                        statusClass = 'status-rejected';
                        statusText = 'Ditolak';
                    }
                    let namaPegawai = (item.pihak1_name || '').toLowerCase();
                    let jenisPerjanjian = (item.jenis_perjanjian || '').toLowerCase();
                    let periode = (item.created_at || '').toLowerCase();
                    let status = statusText.toLowerCase();
                    let matchSearch = !searchValue || namaPegawai.includes(searchValue) || jenisPerjanjian.includes(searchValue) || periode.includes(searchValue) || status.includes(searchValue);
                    if (!matchSearch) return;
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.pihak1_name}</td>
                            <td>${item.jenis_perjanjian}</td>
                            <td>${item.created_at}</td>
                            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/perjanjian/${item.id}/print" class="btn-action btn-view" target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
                tbody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('table-body').innerHTML = 
                    '<tr><td colspan="7" class="no-data">Terjadi kesalahan saat memuat data</td></tr>';
            });
        }

        let currentRejectId = null;

        function approvePerjanjian(id) {
            if (!confirm('Apakah Anda yakin ingin menyetujui perjanjian ini?\n\nTanda tangan Anda akan otomatis ditambahkan pada perjanjian.')) {
                return;
            }

            // Show loading
            const btnElement = event.target.closest('button');
            const originalText = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btnElement.disabled = true;

            fetch(`/dashboard/direktur/perjanjian/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ Perjanjian berhasil disetujui!\n\nTanda tangan Anda telah ditambahkan.');
                    loadData('all');
                } else {
                    alert('✗ Gagal menyetujui perjanjian:\n' + (data.message || 'Unknown error'));
                    btnElement.innerHTML = originalText;
                    btnElement.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('✗ Terjadi kesalahan saat menyetujui perjanjian');
                btnElement.innerHTML = originalText;
                btnElement.disabled = false;
            });
        }

        function rejectPerjanjian(id) {
            currentRejectId = id;
            document.getElementById('rejectionReason').value = '';
            document.getElementById('rejectModal').style.display = 'block';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            currentRejectId = null;
        }

        function submitRejection() {
            const reason = document.getElementById('rejectionReason').value.trim();
            
            if (reason === '') {
                alert('⚠ Alasan penolakan harus diisi!');
                return;
            }
            
            if (reason.length < 10) {
                alert('⚠ Alasan penolakan minimal 10 karakter!');
                return;
            }

            fetch(`/dashboard/direktur/perjanjian/${currentRejectId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rejection_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✓ Perjanjian berhasil ditolak!\n\nAlasan penolakan telah dicatat.');
                    closeRejectModal();
                    loadData('all');
                } else {
                    alert('✗ Gagal menolak perjanjian:\n' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('✗ Terjadi kesalahan saat menolak perjanjian');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('rejectModal');
            if (event.target === modal) {
                closeRejectModal();
            }
        }

        // Profile menu toggle
        const profileIcon = document.getElementById('profileIcon');
        const profileMenu = document.getElementById('profileMenu');

        profileIcon?.addEventListener('click', () => {
            profileMenu.style.display = profileMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (e) => {
            if (!profileIcon?.contains(e.target) && !profileMenu?.contains(e.target)) {
                if (profileMenu) profileMenu.style.display = 'none';
            }
        });

        // Toggle notifikasi
        let showingAll = false;
        function toggleNotifications() {
            const items = document.querySelectorAll('.notification-item.hidden');
            const button = document.querySelector('.btn-more');
            
            if (!showingAll) {
                items.forEach(item => item.classList.remove('hidden'));
                button.textContent = 'Lihat Lebih Sedikit';
                showingAll = true;
            } else {
                items.forEach(item => item.classList.add('hidden'));
                button.textContent = 'Lihat Lebih Banyak';
                showingAll = false;
            }
        }
    </script>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\dashboard\perjanjian-kinerja.blade.php ENDPATH**/ ?>