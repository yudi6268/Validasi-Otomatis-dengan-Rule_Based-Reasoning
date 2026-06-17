
<?php if($status === 'ditolak'): ?>
    <?php
        // Fallback values for rejection modal content and edit link
        $alasan_penolakan = $alasan_penolakan ?? ($perjanjian->rejection_reason ?? '-');
        $penolak_nama = $penolak_nama ?? ($perjanjian->pihak2_name ?? '-');
        $penolak_jabatan = $penolak_jabatan ?? ($perjanjian->pihak2_jabatan ?? '-');
        $tanggal_penolakan = $tanggal_penolakan ?? (
            ($perjanjian->rejection_date ?? null)
                ? \Carbon\Carbon::parse($perjanjian->rejection_date)->translatedFormat('d F Y')
                : (
                    ($perjanjian->updated_at ?? null)
                        ? \Carbon\Carbon::parse($perjanjian->updated_at)->translatedFormat('d F Y')
                        : '-'
                  )
        );
        $url_revisi = $url_revisi ?? route('perjanjian.edit', $perjanjian->id);
    ?>
    <style>
        body, .preview-bg-user { background: #e6fcfc !important; min-height: 100vh; }
        .user-preview-header { position: sticky; top: 0; z-index: 10; }
        .preview-card-user { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.10); max-width: 700px; margin: 36px auto 0 auto; padding: 36px 32px 32px 32px; }
        @media (max-width: 800px) {
            .preview-card-user { padding: 18px 4vw 18px 4vw; }
            .user-preview-header { max-width: 100vw !important; }
        }
        @media print {
            .d-print-none { display: none !important; }
            body, .preview-bg-user { background: #fff !important; }
            .preview-card-user { box-shadow: none !important; border-radius: 0 !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
    <div class="preview-bg-user" style="min-height:100vh; width:100vw; position:relative;">
        <!-- Header -->
        <div class="user-preview-header d-print-none" style="display: flex; align-items: center; justify-content: center; background: #fff; padding: 0; border-bottom: 2.5px solid #009970; max-width: 520px; margin: 0 auto 28px auto; min-height: 56px; position: relative; border-radius: 0 0 18px 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
            <button onclick="window.history.back()" style="background: none; border: none; position: absolute; left: 0; top: 50%; transform: translateY(-50%); margin-left: 8px; cursor: pointer; display: flex; align-items: center;">
                <svg xmlns='http://www.w3.org/2000/svg' width='36' height='36' fill='none' viewBox='0 0 24 24' stroke='#009970' stroke-width='2.5'><path stroke-linecap='round' stroke-linejoin='round' d='M15 19l-7-7 7-7'/></svg>
            </button>
            <div style="font-size: 1.6rem; font-weight: 700; color: #009970; letter-spacing: 0.5px; text-align: center;">Perjanjian</div>
            <div style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); margin-right: 8px;">
                <button id="notifBellBtn" onclick="showNotifModal()" style="background: none; border: none; cursor: pointer; position: relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#009970" viewBox="0 0 24 24"><path d="M12 2a7 7 0 0 1 7 7v3.764c0 .414.168.81.468 1.1l.964.964A1 1 0 0 1 20 17H4a1 1 0 0 1-.707-1.707l.964-.964A1.55 1.55 0 0 0 5.5 12.764V9a7 7 0 0 1 7-7zm0 18a3 3 0 0 0 3-3H9a3 3 0 0 0 3 3z"/></svg>
                    <span style="position: absolute; top: 2px; right: 2px; width: 15px; height: 15px; background: #ff2222; border-radius: 50%; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 12px; font-weight: bold;">1</span>
                </button>
            </div>
        </div>
        <!-- Card Preview Dokumen -->
        <div class="preview-card-user">
            
            <?php echo $__env->yieldContent('preview_content'); ?>
        </div>
    </div>
    <!-- Modal Notifikasi: card merah lembut -->
    <div id="notifModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.18); z-index:10000; align-items:center; justify-content:center;">
        <div style="background:#fff0f0; border-radius:16px; max-width:90vw; width:320px; box-shadow:0 4px 24px rgba(0,0,0,0.10); padding:24px 18px 18px 18px; position:relative; border:1.5px solid #ffb3b3; display:flex; flex-direction:column; align-items:center;">
            <button onclick="closeNotifModal()" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:22px; color:#888; cursor:pointer;">&times;</button>
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
                <svg xmlns='http://www.w3.org/2000/svg' width='22' height='22' fill='none' viewBox='0 0 24 24' stroke='#ff2222' stroke-width='2'><circle cx='12' cy='12' r='10' stroke='#ff2222' stroke-width='2' fill='none'/><path d='M12 8v4m0 4h.01' stroke='#ff2222' stroke-width='2' stroke-linecap='round'/></svg>
                <span style="font-weight:600; color:#ff2222;">Cek alasan penolakan</span>
            </div>
            <div style="color:#222; font-size:1rem; text-align:left; margin-bottom:18px; width:100%;"><?php echo e($alasan_penolakan ?? '-'); ?></div>
            <button onclick="showDetailAlasan()" style="background:#ff2222; color:#fff; border:none; border-radius:18px; padding:8px 28px; font-weight:600; font-size:1rem; margin-top:8px; box-shadow:0 2px 8px rgba(0,0,0,0.08); cursor:pointer;">Lihat Detail</button>
        </div>
    </div>
    <!-- Modal Detail Alasan Penolakan -->
    <div id="alasanModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.18); z-index:10001; align-items:center; justify-content:center; font-family: Arial, sans-serif;">
        <div style="background:#eaf7fe; border-radius:14px; max-width:95vw; width:440px; box-shadow:0 6px 18px rgba(0,0,0,0.13); padding:0 0 28px 0; position:relative; display:flex; flex-direction:column; align-items:center; border:2px solid #b8e0f7;">
            <button onclick="closeAlasanModal()" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:22px; color:#888; cursor:pointer;">&times;</button>
            <div style="width:100%; padding:0 18px; margin-top:18px;">
                <div style="background:#fff; border-radius:10px; border:1.5px solid #b8e0f7; padding:18px 18px 12px 18px; margin-bottom:16px;">
                    <div style="display:flex; flex-direction:column; gap:0;">
                        <div style="display:flex; align-items:center; margin-bottom:5px;">
                            <span style="width:105px; font-weight:600; color:#222; font-size:1.08rem;">Status</span>
                            <span style="color:#e53935; font-weight:700; font-size:1.08rem;">: Ditolak</span>
                        </div>
                        <div style="display:flex; align-items:center; margin-bottom:5px;">
                            <span style="width:105px; font-weight:600; color:#222; font-size:1.08rem;">Dari</span>
                            <span style="font-size:1.08rem;">: <?php echo e($penolak_nama ?? '-'); ?></span>
                        </div>
                        <div style="display:flex; align-items:center; margin-bottom:5px;">
                            <span style="width:105px; font-weight:600; color:#222; font-size:1.08rem;">Jabatan</span>
                            <span style="font-size:1.08rem;">: <?php echo e($penolak_jabatan ?? '-'); ?></span>
                        </div>
                        <div style="display:flex; align-items:center; margin-bottom:10px;">
                            <span style="width:105px; font-weight:600; color:#222; font-size:1.08rem;">Tanggal</span>
                            <span style="font-size:1.08rem;">: <?php echo e($tanggal_penolakan ?? '-'); ?></span>
                        </div>
                    </div>
                    <div style="background:#fff; border:1.5px solid #e53935; border-radius:8px; padding:14px 14px 12px 14px; margin-top:8px; display:flex; align-items:flex-start; gap:10px;">
                        <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none' viewBox='0 0 24 24' stroke='#e53935' stroke-width='2' style="margin-top:2px;"><circle cx='12' cy='12' r='10' stroke='#e53935' stroke-width='2' fill='none'/><path d='M12 8v4m0 4h.01' stroke='#e53935' stroke-width='2' stroke-linecap='round'/></svg>
                        <div>
                            <span style="font-weight:700; color:#e53935; font-size:1.08rem;">Alasan penolakan :</span>
                            <div style="color:#222; font-size:1.08rem; line-height:1.7; white-space:pre-line; font-weight:500; margin-top:4px;"><?php echo e($alasan_penolakan ?? '-'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="width:100%; display:flex; justify-content:center; margin-top:0;">
                <a href="<?php echo e($url_revisi ?? '#'); ?>" class="btn btn-primary" style="background:#b8d7fa;border:none;border-radius:8px;padding:11px 34px;font-size:1.13rem;font-weight:600;box-shadow:0 2px 8px rgba(0,0,0,0.08);color:#1a237e;">Revisi Perjanjian</a>
            </div>
        </div>
    </div>
    <script>
        function showNotifModal() {
            document.getElementById('notifModal').style.display = 'flex';
        }
        function closeNotifModal() {
            document.getElementById('notifModal').style.display = 'none';
        }
        function showDetailAlasan() {
            closeNotifModal();
            document.getElementById('alasanModal').style.display = 'flex';
        }
        function closeAlasanModal() {
            document.getElementById('alasanModal').style.display = 'none';
        }
    </script>
<?php elseif($status === 'disetujui'): ?>
    <!-- Tanda tangan kedua belah pihak -->
    <div class="ttd-pihak2">
        
    </div>
<?php endif; ?>
<!-- UI khusus user: tombol back, bell notifikasi, modal alasan penolakan --><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\perjanjian\_preview_user.blade.php ENDPATH**/ ?>