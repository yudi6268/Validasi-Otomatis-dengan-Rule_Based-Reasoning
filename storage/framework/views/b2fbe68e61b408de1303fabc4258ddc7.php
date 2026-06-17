<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Preview Perjanjian - Dashboard Direktur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to bottom, #e0f7fa 0%, #b2ebf2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            background: white;
            padding: 15px 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-left {
            flex: 0 0 50px;
        }

        .header h1 {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            flex: 1;
            text-align: center;
            margin: 0;
        }

        .header-right {
            flex: 0 0 50px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            transition: all 0.2s ease;
            padding: 5px;
        }

        .btn-icon:hover {
            opacity: 0.7;
        }

        .btn-icon.back {
            color: #333;
        }

        .btn-icon.logout {
            color: #4CAF50;
        }

        .btn-icon.profile {
            color: #00B5A0;
        }

        /* Notification Bell */
        .notification-container {
            position: relative;
            display: inline-block;
        }

        .notification-bell {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #ff5252;
            padding: 5px;
            transition: all 0.2s ease;
            position: relative;
        }

        .notification-bell:hover {
            opacity: 0.7;
        }

        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            background: #ff0000;
            color: white;
            border-radius: 50%;
            width: 12px;
            height: 12px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Profile Menu */
        .profile-menu {
            position: absolute;
            top: 60px;
            right: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 10px;
            display: none;
            flex-direction: column;
            gap: 8px;
            min-width: 150px;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .profile-menu.show {
            display: flex;
        }

        .profile-menu a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
        }

        .profile-menu a:hover {
            background: #f0f0f0;
            color: #00B5A0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Content */
        .content {
            flex: 1;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .pdf-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .pdf-container iframe {
            width: 100%;
            height: 600px;
            border: none;
            border-radius: 5px;
        }

        /* Info Card */
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            width: 150px;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-badge.approved {
            background-color: #FDD835;
            color: #000;
        }

        .status-badge.rejected {
            background-color: #f44336;
            color: white;
        }

        .status-badge.waiting {
            background-color: #2196F3;
            color: white;
        }

        /* Action Buttons */
        .action-buttons {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-action {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-approve {
            background-color: #4CAF50;
            color: white;
        }

        .btn-approve:hover {
            background-color: #45a049;
        }

        .btn-reject {
            background-color: #f44336;
            color: white;
        }

        .btn-reject:hover {
            background-color: #da190b;
        }

        .btn-print {
            background-color: #2196F3;
            color: white;
        }

        .btn-print:hover {
            background-color: #0b7dda;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
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
            font-weight: 600;
            color: #666;
        }

        .modal-body textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
        }

        .modal-body textarea:focus {
            outline: none;
            border-color: #2196F3;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-btn-cancel {
            background-color: #6c757d;
            color: white;
        }

        .modal-btn-cancel:hover {
            background-color: #5a6268;
        }

        .modal-btn-submit {
            background-color: #f44336;
            color: white;
        }

        .modal-btn-submit:hover {
            background-color: #da190b;
        }

        /* Rejection Info */
        .rejection-info {
            background: #fee;
            border-left: 4px solid #f44336;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .rejection-info h4 {
            color: #f44336;
            margin-bottom: 8px;
        }

        .rejection-info p {
            color: #666;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }

            .pdf-container iframe {
                height: 400px;
            }

            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <button onclick="goBack()" class="btn-icon back" title="Kembali">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>
        <h1>Preview Perjanjian Kinerja</h1>
        <div class="header-right">
            <?php if($status === 'rejected' && $perjanjian->rejection_reason): ?>
            <div class="notification-container">
                <button onclick="showRejectionNotification()" class="notification-bell" title="Lihat Alasan Penolakan">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge"></span>
                </button>
            </div>
            <?php endif; ?>
            <div class="notification-container" style="position: relative;">
                <button onclick="toggleProfileMenu()" class="btn-icon profile" title="Profil">
                    <i class="fas fa-user"></i>
                </button>
                <div id="profileMenu" class="profile-menu">
                    <a href="<?php echo e(route('profil')); ?>"><i class="fas fa-user"></i> Profil Saya</a>
                    <a href="<?php echo e(route('settings')); ?>"><i class="fas fa-cog"></i> Pengaturan</a>
                </div>
            </div>
            <button onclick="showLogoutModal()" class="btn-icon logout" title="Keluar">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Info Card -->
        <div class="info-card">
            <div class="info-row">
                <div class="info-label">Pihak Pertama:</div>
                <div class="info-value"><?php echo e($perjanjian->pihak1_name); ?> (<?php echo e($perjanjian->pihak1_jabatan); ?>)</div>
            </div>
            <div class="info-row">
                <div class="info-label">Pihak Kedua:</div>
                <div class="info-value"><?php echo e($perjanjian->pihak2_name); ?> (<?php echo e($perjanjian->pihak2_jabatan); ?>)</div>
            </div>
            <div class="info-row">
                <div class="info-label">Periode:</div>
                <div class="info-value"><?php echo e($perjanjian->periode ?? '-'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Dibuat:</div>
                <div class="info-value"><?php echo e($perjanjian->created_at->format('d F Y')); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    <?php if($status === 'approved'): ?>
                        <span class="status-badge approved">Disetujui</span>
                    <?php elseif($status === 'rejected'): ?>
                        <span class="status-badge rejected">Ditolak</span>
                    <?php else: ?>
                        <span class="status-badge waiting">Menunggu Persetujuan</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($status === 'rejected' && $perjanjian->rejection_reason): ?>
            <div class="rejection-info">
                <h4><i class="fas fa-exclamation-circle"></i> Alasan Penolakan:</h4>
                <p><?php echo e($perjanjian->rejection_reason); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- PDF Preview -->
        <div class="pdf-container">
            <iframe src="<?php echo e(route('direktur.perjanjian.print', $perjanjian->id)); ?>" title="Preview Perjanjian"></iframe>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button onclick="printPDF()" class="btn-action btn-print">
                <i class="fas fa-download"></i> Download PDF
            </button>

            <?php if($status === 'waiting'): ?>
                <button onclick="approvePerjanjian()" class="btn-action btn-approve">
                    <i class="fas fa-check"></i> Setujui
                </button>
                <button onclick="showRejectModal()" class="btn-action btn-reject">
                    <i class="fas fa-times"></i> Tolak
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content" style="text-align:center;">
            <h3 style="font-size:16px;font-weight:600;color:#1a2a25;margin-bottom:20px;">Apa anda ingin keluar?</h3>
            <div class="modal-footer" style="justify-content:center;">
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="modal-btn modal-btn-submit">Ya, Keluar</button>
                </form>
                <button onclick="closeLogoutModal()" class="modal-btn modal-btn-cancel">Batal</button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Tolak Perjanjian</div>
            <div class="modal-body">
                <label for="rejectionReason">Alasan Penolakan: <span style="color: red;">*</span></label>
                <textarea id="rejectionReason" placeholder="Masukkan alasan mengapa perjanjian ditolak (minimal 10 karakter)"></textarea>
            </div>
            <div class="modal-footer">
                <button onclick="closeRejectModal()" class="modal-btn modal-btn-cancel">Batal</button>
                <button onclick="submitRejection()" class="modal-btn modal-btn-submit">Tolak Perjanjian</button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const perjanjianId = <?php echo e($perjanjian->id); ?>;

        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'flex';
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        function goBack() {
            window.location.href = '<?php echo e(route('dashboard.wadir')); ?>';
        }

        function printPDF() {
            // Download PDF dengan upload ke Supabase
            const link = document.createElement('a');
            link.href = '<?php echo e(route('direktur.perjanjian.download', $perjanjian->id)); ?>';
            link.download = 'Perjanjian_Kinerja_<?php echo e(str_replace(' ', '_', $perjanjian->pihak1_name)); ?>.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success notification
            alert('✓ PDF sedang diunduh dan disimpan ke Supabase');
        }

        function showRejectModal() {
            document.getElementById('rejectModal').style.display = 'flex';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            document.getElementById('rejectionReason').value = '';
        }

        function approvePerjanjian() {
            if (!confirm('Apakah Anda yakin ingin menyetujui perjanjian ini?')) {
                return;
            }

            fetch(`/dashboard/direktur/perjanjian/${perjanjianId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menyetujui perjanjian');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyetujui perjanjian');
            });
        }

        function submitRejection() {
            const reason = document.getElementById('rejectionReason').value.trim();

            if (reason.length < 10) {
                alert('Alasan penolakan harus minimal 10 karakter');
                return;
            }

            fetch(`/dashboard/direktur/perjanjian/${perjanjianId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rejection_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closeRejectModal();
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menolak perjanjian');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menolak perjanjian');
            });
        }

        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }

        function showRejectionNotification() {
            alert('Alasan Penolakan:\n\n' + <?php echo json_encode($perjanjian->rejection_reason ?? '', 15, 512) ?>);
        }

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const profileMenu = document.getElementById('profileMenu');
            const profileButton = event.target.closest('.btn-icon.profile');
            
            if (!profileButton && !event.target.closest('.profile-menu')) {
                profileMenu?.classList.remove('show');
            }
        });

        window.onclick = function(event) {
            const logoutModal = document.getElementById('logoutModal');
            const rejectModal = document.getElementById('rejectModal');
            
            if (event.target === logoutModal) {
                closeLogoutModal();
            }
            if (event.target === rejectModal) {
                closeRejectModal();
            }
        }
    </script>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\dashboard\perjanjian-show.blade.php ENDPATH**/ ?>