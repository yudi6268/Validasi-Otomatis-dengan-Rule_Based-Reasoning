
<?php if($status === 'ditolak'): ?>
    
<?php elseif($status === 'disetujui'): ?>
                <!-- Setelah disetujui, tampilkan tanda tangan kedua belah pihak -->
                <div style="display: flex; gap: 48px; margin-top: 32px; justify-content: center;">
                        <div class="ttd-pihak1" style="text-align:center;">
                                <div style="font-weight:700;">Pihak Pertama</div>
                                <?php if(!empty($perjanjian->pihak1_signature)): ?>
                                        <img src="<?php echo e($perjanjian->pihak1_signature); ?>" alt="Tanda Tangan Pihak Pertama" style="height:64px; margin:8px 0;">
                                <?php else: ?>
                                        <div style="height:64px;">(Belum ada tanda tangan)</div>
                                <?php endif; ?>
                                <div style="margin-top:8px;"><?php echo e($perjanjian->pihak1_name ?? '-'); ?></div>
                        </div>
                        <div class="ttd-pihak2" style="text-align:center;">
                                <div style="font-weight:700;">Pihak Kedua</div>
                                <?php if(!empty($perjanjian->pihak2_signature)): ?>
                                        <img src="<?php echo e($perjanjian->pihak2_signature); ?>" alt="Tanda Tangan Pihak Kedua" style="height:64px; margin:8px 0;">
                                <?php else: ?>
                                        <div style="height:64px;">(Belum ada tanda tangan)</div>
                                <?php endif; ?>
                                <div style="margin-top:8px;"><?php echo e($perjanjian->pihak2_name ?? '-'); ?></div>
                        </div>
                </div>
<?php elseif($status === 'menunggu'): ?>
        <!-- Tombol aksi di pojok kanan atas, hanya untuk status menunggu -->
        <style>
        .aksi-btn {
                background: #ffe066;
                color: #222;
                border: none;
                font-weight: 700;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                padding: 10px 28px;
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 1rem;
                transition: transform 0.2s cubic-bezier(.4,2,.3,1), box-shadow 0.2s cubic-bezier(.4,2,.3,1);
                will-change: transform, box-shadow;
        }
        .aksi-btn.tolak {
                background: #ff4d4f;
                color: #fff;
        }
        .aksi-btn:hover {
                transform: scale(1.07);
                box-shadow: 0 6px 24px rgba(0,0,0,0.16);
                z-index: 10001;
        }
        /* Modal styling */
        .modal-bg {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.25);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
        }
        .modal-box {
                background: #fdf3f3;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(0,0,0,0.18);
                padding: 32px 24px;
                min-width: 400px;
                max-width: 96vw;
                position: relative;
                border: 2px solid #e3e3e3;
        }
        .modal-close {
                position: absolute;
                top: 12px;
                left: 16px;
                background: none;
                border: none;
                font-size: 2rem;
                color: #222;
                cursor: pointer;
        }
        .modal-title {
                text-align: center;
                font-size: 1.6rem;
                font-weight: 700;
                margin-bottom: 18px;
                color: #2d1c0b;
                letter-spacing: 1px;
        }
        .modal-form-group {
                margin-bottom: 12px;
        }
        .modal-form-input {
                width: 100%;
                border-radius: 6px;
                border: 1px solid #ccc;
                padding: 8px 12px;
                font-size: 1rem;
                background: #fff;
        }
        .modal-form-textarea {
                width: 100%;
                border-radius: 6px;
                border: 1px solid #ccc;
                padding: 8px 12px;
                font-size: 1rem;
                min-height: 120px;
                background: #fff;
                resize: vertical;
        }
        .modal-form-submit {
                width: 100%;
                background: #009e60;
                color: #fff;
                font-weight: 700;
                border: none;
                border-radius: 8px;
                padding: 12px 0;
                font-size: 1.1rem;
                margin-top: 18px;
                margin-bottom: 8px;
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                transition: background 0.2s;
        }
        .modal-form-submit:hover {
                background: #007a48;
        }
        .modal-success {
                display: flex;
                align-items: center;
                gap: 8px;
                color: #009e60;
                font-size: 1rem;
                margin-top: 4px;
        }
        </style>
        <div style="position: fixed; top: 32px; right: 32px; z-index: 9999; display: flex; gap: 16px;">
                <form method="POST" action="/perjanjian/<?php echo e($perjanjian->id); ?>/setujui">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="aksi-btn">
                                <span style="display: flex; align-items: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path d="M13.485 1.929a1 1 0 0 1 0 1.414l-7.071 7.071a1 1 0 0 1-1.414 0L2.515 8.071a1 1 0 1 1 1.414-1.414l1.071 1.071 6.364-6.364a1 1 0 0 1 1.414 0z"/></svg>
                                </span>
                                Terima
                        </button>
                </form>
                <button type="button" class="aksi-btn tolak" onclick="document.getElementById('modalTolak').style.display='flex'">
                        <span style="display: flex; align-items: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
                        </span>
                        Tolak
                </button>
        </div>
        <!-- Modal alasan penolakan -->
        <div id="modalTolak" class="modal-bg" style="display:none;">
                <div class="modal-box">
                        <button class="modal-close" onclick="document.getElementById('modalTolak').style.display='none'">&#8592;</button>
                        <div class="modal-title">ALASAN MENOLAK</div>
                        <form method="POST" action="/perjanjian/<?php echo e($perjanjian->id); ?>/tolak" id="formTolak" onsubmit="event.preventDefault(); document.getElementById('modalSuccess').style.display='flex'; setTimeout(function(){document.getElementById('modalTolak').style.display='none'; document.getElementById('modalSuccess').style.display='none'; document.getElementById('formTolak').submit();}, 1200);">
                                <?php echo csrf_field(); ?>
                                <div class="modal-form-group">
                                        <input type="text" class="modal-form-input" value="<?php echo e(auth()->user()->nama ?? ''); ?>" placeholder="Nama Lengkap" readonly>
                                </div>
                                <div class="modal-form-group">
                                        <input type="text" class="modal-form-input" value="<?php echo e(auth()->user()->jabatan ?? ''); ?>" placeholder="Jabatan" readonly>
                                </div>
                                <div class="modal-form-group">
                                        <input type="text" class="modal-form-input" value="<?php echo e(date('d-m-Y')); ?>" placeholder="Tanggal" readonly>
                                </div>
                                <div class="modal-form-group">
                                        <textarea class="modal-form-textarea" name="rejection_reason" placeholder="Tulis Alasan" required></textarea>
                                </div>
                                <button type="submit" class="modal-form-submit">KIRIM ALASAN</button>
                                <div id="modalSuccess" class="modal-success" style="display:none;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#009e60" viewBox="0 0 16 16"><path d="M16 2a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-2.293 5.707a1 1 0 0 0-1.414 0L7 13.293l-2.293-2.293a1 1 0 0 0-1.414 1.414l3 3a1 1 0 0 0 1.414 0l6-6a1 1 0 0 0 0-1.414z"/></svg>
                                        Alasan Anda telah terkirim. Terima kasih.
                                </div>
                        </form>
                </div>
        </div>
<?php endif; ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\perjanjian\_preview_direktur.blade.php ENDPATH**/ ?>