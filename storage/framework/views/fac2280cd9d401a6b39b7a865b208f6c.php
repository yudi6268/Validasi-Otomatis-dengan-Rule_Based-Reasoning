<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Ringkasan Validasi – RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f4f8;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #1B2A41;
    }
    header {
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 36px;
      box-shadow: 0 4px 12px rgba(0,153,112,0.10);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .logo-container { display:flex; align-items:center; gap:14px; }
    .logo-container img { height:50px; }
    .header-title { font-weight:700; color:#009970; font-size:17px; }
    nav a { text-decoration:none; color:#555; font-weight:600; font-size:14px; margin-left:22px; }
    nav a:hover { color:#00B5A0; }
    .layout { display:flex; flex:1; min-height:0; }
    .sidebar { width:250px; background:#fff; padding:22px 16px; box-shadow:2px 0 8px rgba(0,0,0,0.05); flex-shrink:0; overflow-y:auto; }
    .sidebar h3 { font-size:11px; font-weight:700; color:#999; margin-bottom:14px; text-transform:uppercase; letter-spacing:.8px; }
    .sidebar-menu { display:flex; flex-direction:column; gap:8px; }
    .sidebar-menu a { display:flex; align-items:center; gap:10px; padding:11px 14px; background:#f9f9f9; border-radius:8px; text-decoration:none; color:#333; font-weight:600; font-size:13px; border-left:4px solid transparent; transition:.25s; }
    .sidebar-menu a:hover, .sidebar-menu a.active { background:#E6F6F2; border-left-color:#00B5A0; color:#00B5A0; }
    .sidebar-menu a.logout-link { color:#e53e3e; }
    .sidebar-menu a.logout-link i { color:#e53e3e; }
    .sidebar-menu a.logout-link:hover { background:#fff5f5; border-left-color:#e53e3e; color:#e53e3e; }
    .sidebar-menu i { width:16px; color:#00B5A0; }
    .main-content { flex:1; padding:30px 36px; overflow-y:auto; }
    .page-header { margin-bottom:28px; text-align:center; }
    .page-title { font-size:28px; font-weight:800; margin-bottom:4px; }
    .page-subtitle { color:#5F6F81; font-size:13px; }
    .info-card { background:#fff; border-radius:12px; padding:20px 24px; margin-bottom:24px; box-shadow:0 2px 8px rgba(0,0,0,0.07); }
    .info-card h5 { font-size:14px; font-weight:700; color:#00B5A0; margin-bottom:14px; }
    .info-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:16px; }
    .info-item { padding:10px 12px; background:#f5f5f5; border-radius:8px; }
    .info-label { font-size:11px; color:#666; font-weight:600; text-transform:uppercase; margin-bottom:3px; }
    .info-value { font-size:14px; color:#1B2A41; font-weight:600; }
    .tw-banner { display:flex; align-items:center; gap:14px; padding:16px 22px; border-radius:12px; margin-bottom:24px; box-shadow:0 2px 10px rgba(0,0,0,0.07); }
    .tw-banner-1 { background:linear-gradient(135deg,#5C6BC0,#3F51B5); }
    .tw-banner-2 { background:linear-gradient(135deg,#26A69A,#00897B); }
    .tw-banner-3 { background:linear-gradient(135deg,#EF6C00,#E65100); }
    .tw-banner-4 { background:linear-gradient(135deg,#AD1457,#880E4F); }
    .tw-banner-icon { width:52px; height:52px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:900; color:#fff; flex-shrink:0; }
    .tw-banner-info { color:#fff; }
    .tw-banner-title { font-size:18px; font-weight:800; }
    .tw-banner-sub { font-size:12px; opacity:.85; margin-top:2px; }
    .stat-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:28px; }
    @media(max-width:900px) { .stat-grid { grid-template-columns:repeat(3,1fr); } }
    @media(max-width:600px) { .stat-grid { grid-template-columns:repeat(2,1fr); } }
    .stat-card { background:#fff; border-radius:14px; padding:20px 16px; box-shadow:0 3px 12px rgba(0,0,0,0.07); text-align:center; border-top:4px solid #d0d7de; }
    .stat-card .stat-num { font-size:38px; font-weight:900; line-height:1; margin-bottom:6px; }
    .stat-card .stat-lbl { font-size:12px; font-weight:700; color:#667085; text-transform:uppercase; letter-spacing:.5px; }
    .stat-card .stat-icon { font-size:20px; margin-bottom:8px; display:block; }
    .stat-total { border-top-color:#5C6BC0; } .stat-total .stat-num,.stat-total .stat-icon { color:#5C6BC0; }
    .stat-valid { border-top-color:#2e7d32; } .stat-valid .stat-num,.stat-valid .stat-icon { color:#2e7d32; }
    .stat-invalid { border-top-color:#c62828; } .stat-invalid .stat-num,.stat-invalid .stat-icon { color:#c62828; }
    .stat-revisi { border-top-color:#1565c0; } .stat-revisi .stat-num,.stat-revisi .stat-icon { color:#1565c0; }
    .stat-warning { border-top-color:#e65100; } .stat-warning .stat-num,.stat-warning .stat-icon { color:#e65100; }
    .score-section { background:#fff; border-radius:14px; padding:22px 24px; box-shadow:0 2px 10px rgba(0,0,0,0.07); margin-bottom:24px; }
    .score-row { display:flex; align-items:center; gap:16px; margin-bottom:16px; }
    .score-big { font-size:52px; font-weight:900; line-height:1; }
    .score-meta { flex:1; }
    .score-lbl { font-size:13px; color:#667085; margin-bottom:6px; }
    .score-bar-wrap { height:10px; background:#eee; border-radius:999px; overflow:hidden; }
    .score-bar-fill { height:100%; border-radius:999px; }
    .score-bar-ok  { background:linear-gradient(90deg,#43A047,#66BB6A); }
    .score-bar-warn{ background:linear-gradient(90deg,#FB8C00,#FFA726); }
    .score-bar-bad { background:linear-gradient(90deg,#E53935,#EF5350); }
    .c-ok  { color:#2e7d32; } .c-warn { color:#e65100; } .c-bad { color:#c62828; }
    .finding-group { margin-bottom:18px; }
    .finding-group-title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; margin-bottom:8px; display:flex; align-items:center; gap:6px; }
    .finding-group-title.issues { color:#c62828; } .finding-group-title.warnings { color:#e65100; } .finding-group-title.suggestions { color:#1565c0; }
    .finding-list { display:flex; flex-direction:column; gap:6px; }
    .finding-item { padding:10px 14px; border-radius:8px; font-size:13px; border-left:4px solid transparent; }
    .finding-item.issue      { background:#fff5f5; border-left-color:#e53935; }
    .finding-item.warning    { background:#fff8f0; border-left-color:#fb8c00; }
    .finding-item.suggestion { background:#f0f6ff; border-left-color:#1976d2; }
    .finding-item-msg { font-weight:600; color:#1B2A41; }
    .finding-item-fix { font-size:12px; color:#667085; margin-top:3px; }
    .confirm-section { background:#fff; border-radius:14px; padding:28px; box-shadow:0 2px 12px rgba(0,0,0,0.07); margin-top:28px; text-align:center; }
    .confirm-section h3 { font-size:18px; font-weight:700; margin-bottom:8px; }
    .confirm-section p { font-size:13px; color:#667085; margin-bottom:20px; line-height:1.7; }
    .btn-confirm { display:inline-flex; align-items:center; gap:9px; background:linear-gradient(135deg,#00B5A0,#009970); color:#fff; border:none; border-radius:99px; padding:13px 34px; font-size:15px; font-weight:700; cursor:pointer; text-decoration:none; box-shadow:0 4px 14px rgba(0,181,160,0.35); transition:.2s; }
    .btn-confirm:hover { background:linear-gradient(135deg,#00977f,#007a58); color:#fff; transform:translateY(-2px); }
    .alert-info { padding:14px 18px; border-radius:10px; background:#E0F9F7; color:#00796B; border:1px solid #80DEEA; display:flex; align-items:center; gap:10px; margin-bottom:20px; font-size:14px; }
    footer { background:#fff; text-align:center; font-size:11px; font-weight:700; padding:10px; border-top:1px solid #dbe2ea; color:#1B2A41; }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo Pemda">
      <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD">
      <span class="header-title">Dashboard – RSUD Bangil</span>
    </div>
    <nav>
      <a href="<?php echo e(route('panduan')); ?>">Panduan</a>
      <a href="<?php echo e(route('kontak')); ?>">Kontak</a>
      <a href="<?php echo e(route('tentang')); ?>">Tentang</a>
    </nav>
    <div></div>
  </header>

  <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>

  <div class="layout">
    <?php $isWadir = auth()->user()?->isWadir(); ?>
    <?php if($isWadir): ?>
      <?php echo $__env->make('dashboard.partials.wadir-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
      <?php echo $__env->make('dashboard.partials.pimpinan-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <main class="main-content">

      <div class="page-header">
        <div class="page-title">Ringkasan Validasi</div>
        <div class="page-subtitle">Hasil pemeriksaan otomatis laporan kinerja</div>
      </div>

      <?php if(!$perjanjian): ?>
        <div class="alert-info">
          <i class="fas fa-info-circle"></i>
          Tidak ada perjanjian kinerja yang disetujui. Buat perjanjian kinerja terlebih dahulu.
        </div>
      <?php else: ?>

      <div class="info-card">
        <h5><i class="fas fa-user-circle"></i> &nbsp;Data Pegawai &amp; Perjanjian</h5>
        <div class="info-grid">
          <div class="info-item"><div class="info-label">Nama Pegawai</div><div class="info-value"><?php echo e($perjanjian->pihak1_name ?? '-'); ?></div></div>
          <div class="info-item"><div class="info-label">NIP</div><div class="info-value"><?php echo e($perjanjian->pihak1_nip ?? '-'); ?></div></div>
          <div class="info-item"><div class="info-label">Jabatan</div><div class="info-value"><?php echo e($perjanjian->pihak1_jabatan ?? '-'); ?></div></div>
          <div class="info-item"><div class="info-label">Tahun</div><div class="info-value"><?php echo e($perjanjian->tahun ?? date('Y')); ?></div></div>
          <div class="info-item"><div class="info-label">Atasan Langsung</div><div class="info-value"><?php echo e($perjanjian->pihak2_name ?? '-'); ?></div></div>
        </div>
      </div>

      <?php
        $twPeriods = [1=>'Januari – Maret',2=>'April – Juni',3=>'Juli – September',4=>'Oktober – Desember'];
        $tw  = $triwulanAktif;
        $res = $twResult;
        $score    = $res ? ($res['score'] ?? 0) : 0;
        $scoreCls = $score >= 80 ? 'c-ok' : ($score >= 50 ? 'c-warn' : 'c-bad');
        $barCls   = $score >= 80 ? 'score-bar-ok' : ($score >= 50 ? 'score-bar-warn' : 'score-bar-bad');
      ?>

      <div class="tw-banner tw-banner-<?php echo e($tw); ?>">
        <div class="tw-banner-icon"><?php echo e($tw); ?></div>
        <div class="tw-banner-info">
          <div class="tw-banner-title">Triwulan <?php echo e($tw); ?> — Aktif</div>
          <div class="tw-banner-sub"><?php echo e($twPeriods[$tw] ?? ''); ?></div>
        </div>
      </div>

      <div class="stat-grid">
        <div class="stat-card stat-total">
          <span class="stat-icon"><i class="fas fa-list-check"></i></span>
          <div class="stat-num"><?php echo e($stats['total']); ?></div>
          <div class="stat-lbl">Total Indikator</div>
        </div>
        <div class="stat-card stat-valid">
          <span class="stat-icon"><i class="fas fa-circle-check"></i></span>
          <div class="stat-num"><?php echo e($stats['valid']); ?></div>
          <div class="stat-lbl">Terisi</div>
        </div>
        <div class="stat-card stat-invalid">
          <span class="stat-icon"><i class="fas fa-circle-xmark"></i></span>
          <div class="stat-num"><?php echo e($stats['tidak_valid']); ?></div>
          <div class="stat-lbl">Tidak Valid</div>
        </div>
        <div class="stat-card stat-revisi">
          <span class="stat-icon"><i class="fas fa-pen-to-square"></i></span>
          <div class="stat-num"><?php echo e($stats['revisi']); ?></div>
          <div class="stat-lbl">Revisi</div>
        </div>
        <div class="stat-card stat-warning">
          <span class="stat-icon"><i class="fas fa-triangle-exclamation"></i></span>
          <div class="stat-num"><?php echo e($stats['peringatan']); ?></div>
          <div class="stat-lbl">Peringatan</div>
        </div>
      </div>

      <?php if(!$res): ?>
        <div class="alert-info">
          <i class="fas fa-info-circle"></i>
          Laporan untuk Triwulan <?php echo e($tw); ?> belum tersedia. Isi laporan realisasi terlebih dahulu.
        </div>
      <?php else: ?>
        <div class="score-section">
          <div class="score-row">
            <div class="score-big <?php echo e($scoreCls); ?>"><?php echo e($score); ?></div>
            <div class="score-meta">
              <div class="score-lbl">Skor Validasi Triwulan <?php echo e($tw); ?></div>
              <div class="score-bar-wrap">
                <div class="score-bar-fill <?php echo e($barCls); ?>" style="width:<?php echo e($score); ?>%;"></div>
              </div>
            </div>
            <?php if($res['is_valid']): ?>
              <span style="background:#E8F5E9;color:#2E7D32;padding:6px 14px;border-radius:99px;font-size:13px;font-weight:700;"><i class="fas fa-check"></i> Valid</span>
            <?php else: ?>
              <span style="background:#FFEBEE;color:#C62828;padding:6px 14px;border-radius:99px;font-size:13px;font-weight:700;"><i class="fas fa-xmark"></i> Tidak Valid</span>
            <?php endif; ?>
          </div>

          <?php if(!empty($res['issues'])): ?>
            <div class="finding-group">
              <div class="finding-group-title issues"><i class="fas fa-circle-xmark"></i> Tidak Valid (<?php echo e(count($res['issues'])); ?>)</div>
              <div class="finding-list">
                <?php $__currentLoopData = $res['issues']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="finding-item issue">
                    <div class="finding-item-msg"><?php echo e($item['message']); ?></div>
                    <?php if(!empty($item['fix'])): ?><div class="finding-item-fix"><i class="fas fa-wrench"></i> <?php echo e($item['fix']); ?></div><?php endif; ?>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          <?php endif; ?>

          <?php if(!empty($res['warnings'])): ?>
            <div class="finding-group">
              <div class="finding-group-title warnings"><i class="fas fa-triangle-exclamation"></i> Peringatan (<?php echo e(count($res['warnings'])); ?>)</div>
              <div class="finding-list">
                <?php $__currentLoopData = $res['warnings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="finding-item warning">
                    <div class="finding-item-msg"><?php echo e($item['message']); ?></div>
                    <?php if(!empty($item['fix'])): ?><div class="finding-item-fix"><i class="fas fa-wrench"></i> <?php echo e($item['fix']); ?></div><?php endif; ?>
                  </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          <?php endif; ?>

          <?php if(!empty($res['suggestions'])): ?>
            <div class="finding-group">
              <div class="finding-group-title suggestions"><i class="fas fa-pen-to-square"></i> Revisi / Saran (<?php echo e(count($res['suggestions'])); ?>)</div>
              <div class="finding-list">
                <?php $__currentLoopData = $res['suggestions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="finding-item suggestion"><div class="finding-item-msg"><?php echo e($item['message']); ?></div></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          <?php endif; ?>

          <?php if(empty($res['issues']) && empty($res['warnings']) && empty($res['suggestions'])): ?>
            <div style="text-align:center;padding:16px 0;color:#2e7d32;">
              <i class="fas fa-circle-check" style="font-size:32px;margin-bottom:8px;display:block;"></i>
              <p style="font-weight:600;">Tidak ada temuan. Laporan triwulan ini sudah sesuai.</p>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <div class="confirm-section">
        <h3>
          <?php if($stats['tidak_valid'] === 0): ?>
            <i class="fas fa-shield-check" style="color:#2e7d32;"></i> Validasi Selesai
          <?php else: ?>
            <i class="fas fa-shield-exclamation" style="color:#c62828;"></i> Perlu Perbaikan
          <?php endif; ?>
        </h3>
        <?php if($isAlreadyValidated): ?>
          <p><strong>Laporan ini sudah tervalidasi sebelumnya.</strong> Anda tidak dapat mengkonfirmasi ulang.</p>
          <button type="button" class="btn-confirm" disabled style="opacity:0.5;cursor:not-allowed;"><i class="fas fa-check-double"></i> Sudah Tervalidasi</button>
        <?php elseif($stats['tidak_valid'] === 0 && $stats['peringatan'] === 0): ?>
          <p>Laporan Triwulan <?php echo e($tw); ?> telah lolos validasi otomatis. Klik tombol di bawah untuk mengkonfirmasi laporan sudah sesuai.</p>
          <button type="button" class="btn-confirm" onclick="saveValidationAndRedirect(<?php echo e($tw); ?>)"><i class="fas fa-check-double"></i> Konfirmasi</button>
        <?php elseif($stats['tidak_valid'] === 0): ?>
          <p>Laporan lolos validasi namun terdapat <strong><?php echo e($stats['peringatan']); ?> peringatan</strong> dan <strong><?php echo e($stats['revisi']); ?> saran</strong>. Anda tetap dapat melanjutkan atau melakukan perbaikan.</p>
          <button type="button" class="btn-confirm" onclick="saveValidationAndRedirect(<?php echo e($tw); ?>)"><i class="fas fa-check"></i> Konfirmasi</button>
        <?php else: ?>
          <p>Terdapat <strong><?php echo e($stats['tidak_valid']); ?> masalah kritis</strong> yang harus diperbaiki sebelum laporan dapat dikonfirmasi.</p>
          <a href="<?php echo e(route('laporan.kinerja')); ?>" class="btn-confirm" style="background:linear-gradient(135deg,#e53935,#c62828);"><i class="fas fa-pen"></i> Perbaiki Laporan</a>
        <?php endif; ?>
      </div>

      <?php endif; ?>
    </main>
  </div>

  <footer>© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja</footer>

  <script>
    function saveValidationAndRedirect(tw) {
      // Data hasil validasi
      const validationData = {
        tw: tw,
        score: parseInt(document.querySelector('.score-big')?.textContent || '0'),
        total: <?php echo e($stats['total']); ?>,
        valid: <?php echo e($stats['valid']); ?>,
        invalid: <?php echo e($stats['tidak_valid']); ?>,
        warnings: <?php echo e($stats['peringatan']); ?>,
        suggestions: <?php echo e($stats['revisi']); ?>,
        perjanjianId: <?php echo e($perjanjian?->id ?? 'null'); ?>,
        laporanId: <?php echo e($laporan?->id ?? 'null'); ?>

      };
      
      // Ambil CSRF token
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      
      // POST ke API untuk menyimpan
      fetch('/api/validasi-laporan', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(validationData)
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Redirect ke dashboard wadir dengan panel validasi
            window.location.href = '<?php echo e(route("dashboard.wadir", ["panel" => "validasi"])); ?>';
          } else {
            alert('Gagal menyimpan validasi. ' + (data.message || ''));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat menyimpan validasi.');
        });
    }
  </script>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\laporan\validasi-summary.blade.php ENDPATH**/ ?>