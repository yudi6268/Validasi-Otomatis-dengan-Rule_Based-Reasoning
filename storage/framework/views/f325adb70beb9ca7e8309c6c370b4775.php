<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Wadir - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f3f8f7;
      min-height: 100vh;
      height: 100vh;
      color: #1B2A41;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    header {
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 40px;
      box-shadow: 0 12px 30px rgba(0,153,112,0.08);
      position: sticky;
      inset-block-start: 0;
      z-index: 100;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .logo-container img {
      height: 52px;
    }

    .header-title {
      font-weight: 700;
      color: #009970;
      font-size: 18px;
    }

    nav {
      display: flex;
      gap: 24px;
    }

    nav a {
      text-decoration: none;
      color: #55606f;
      font-weight: 600;
      font-size: 15px;
      transition: color 0.25s ease;
    }

    nav a:hover {
      color: #00B5A0;
    }

    .dashboard-container {
      display: flex;
      flex: 1;
      gap: 0;
      min-height: calc(100vh - 142px);
    }

    .sidebar {
      width: 260px;
      background: #fff;
      padding: 24px 20px;
      box-shadow: 2px 0 12px rgba(0,0,0,0.04);
      overflow: auto;
    }

    .sidebar h3 {
      font-size: 13px;
      font-weight: 700;
      color: #8f9ba5;
      margin-bottom: 18px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }

    .sidebar-menu {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .sidebar-menu a {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 14px 16px;
      background: #f8fcfb;
      border-radius: 14px;
      text-decoration: none;
      color: #32404b;
      font-weight: 600;
      font-size: 14px;
      border-inline-start: 4px solid transparent;
      transition: all 0.25s ease;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
      background: #e8f8f3;
      border-inline-start-color: #00B5A0;
      color: #008872;
    }

    .sidebar-menu i {
      width: 20px;
      color: #00B5A0;
    }

    .main-content {
      flex: 1;
      padding: 22px 26px;
      overflow-y: auto;
    }

    .page-header {
      margin-bottom: 18px;
    }

    .page-header h1 {
      font-size: 32px;
      color: #1B2A41;
      margin-bottom: 8px;
    }

    .page-header p {
      color: #667085;
      font-size: 15px;
    }

    .summary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 16px;
      margin-bottom: 18px;
    }

    .summary-card {
      background: #fff;
      padding: 20px 20px;
      border-radius: 24px;
      box-shadow: 0 22px 48px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .summary-card i {
      font-size: 32px;
      color: #00B5A0;
    }

    .summary-card h4 {
      font-size: 15px;
      font-weight: 700;
      color: #5f6f81;
      margin: 0;
    }

    .summary-card .number {
      font-size: 36px;
      font-weight: 800;
      color: #1B2A41;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 16px;
      margin-bottom: 18px;
    }

    .stat-card {
      background: linear-gradient(180deg, #ffffff 0%, #f7fffb 100%);
      padding: 20px 20px;
      border-radius: 24px;
      box-shadow: 0 22px 48px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      gap: 14px;
      align-items: flex-start;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: pointer;
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 28px 56px rgba(0,0,0,0.08);
    }

    .stat-card i {
      font-size: 30px;
      color: #00B5A0;
    }

    .stat-card h4 {
      font-size: 14px;
      font-weight: 700;
      color: #5f6f81;
      margin: 0;
    }

    .stat-card .number {
      font-size: 38px;
      font-weight: 800;
      color: #1B2A41;
    }

    .action-block {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 18px;
      margin-bottom: 28px;
    }

    .action-card {
      background: #fff;
      border: 1px solid #e9f2ef;
      padding: 20px;
      border-radius: 18px;
      box-shadow: 0 14px 30px rgba(0,0,0,0.04);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      text-align: center;
      color: #1b2a41;
      text-decoration: none;
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .action-card:hover {
      transform: translateY(-2px);
      border-color: #00b79f;
      box-shadow: 0 18px 34px rgba(0,0,0,0.06);
    }

    .action-card i {
      font-size: 36px;
      color: #00b5a0;
    }

    .action-card p {
      margin: 0;
      font-size: 15px;
      font-weight: 700;
    }

    .dashboard-widgets {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 24px;
      margin-top: 30px;
    }

    .chart-card {
      background:#fff;
      padding:24px;
      border-radius:22px;
      box-shadow:0 22px 48px rgba(0,0,0,0.06);
      min-height: 320px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .chart-card h5 {
      margin:0 0 16px 0;
      color:#1B2A41;
      font-weight:700;
      font-size:17px;
    }

    .chart-card canvas {
      width:100% !important;
      height:auto !important;
      aspect-ratio: 16 / 9;
      max-height: 320px;
    }

    .popup-modal {
      display:none;
      position:fixed;
      inset:0;
      background:rgba(0,0,0,0.35);
      justify-content:center;
      align-items:center;
      z-index:1000;
      padding:24px;
    }

    .popup-box {
      background:#fff;
      padding:32px 30px;
      border-radius:24px;
      width:min(520px,100%);
      box-shadow:0 30px 60px rgba(0,0,0,0.18);
      text-align:center;
    }

    .popup-box h3 {
      margin:0;
      font-size:24px;
      color:#1B2A41;
      line-height:1.2;
    }

    .popup-box p {
      margin:18px 0 0;
      color:#5f6f81;
      font-size:15px;
      line-height:1.7;
    }

    .popup-close {
      margin-top:26px;
      padding:12px 30px;
      border-radius:999px;
      border:none;
      background:#00B5A0;
      color:#fff;
      font-weight:700;
      cursor:pointer;
      transition:background .25s ease;
    }

    .popup-close:hover {
      background:#009370;
    }

    @media (max-width: 1024px) {
      .dashboard-container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
      }
      .dashboard-widgets {
        grid-template-columns: 1fr;
      }
    }

    /* ---- Panel stat cards (perjanjian/laporan panel) ---- */
    .panel-shell {
      max-width: 760px;
      margin: 0 auto;
      background: linear-gradient(180deg, #f8fffc 0%, #f2fbf8 100%);
      border: 1px solid #dff3ed;
      border-radius: 20px;
      padding: 18px 20px 20px;
      box-shadow: 0 14px 28px rgba(0, 153, 112, 0.08);
    }

    .panel-page-title {
      text-align: center;
      font-size: 16px;
      font-weight: 700;
      color: #3f4a56;
      margin-bottom: 14px;
    }

    .panel-stat-grid {
      margin: 0 auto 14px;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 14px;
    }

    .panel-stat-card {
      background: #fff;
      border-radius: 14px;
      padding: 16px 14px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      text-align: center;
      min-height: 168px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .panel-stat-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.11);
    }

    .panel-stat-number {
      font-size: 46px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 8px;
    }

    .panel-stat-green .panel-stat-number  { color: #009970; }
    .panel-stat-yellow .panel-stat-number { color: #FFA500; }
    .panel-stat-red .panel-stat-number    { color: #DC3545; }
    .panel-stat-blue .panel-stat-number   { color: #2196F3; }

    .panel-stat-label {
      font-size: 14px;
      font-weight: 600;
      color: #666;
      margin-bottom: 12px;
    }

    .panel-stat-btn {
      display: inline-block;
      padding: 8px 20px;
      border-radius: 999px;
      border: none;
      color: #fff;
      font-weight: 700;
      font-size: 13px;
      text-decoration: none;
      cursor: pointer;
      transition: opacity 0.2s ease, transform 0.2s ease;
    }

    .panel-stat-green .panel-stat-btn  { background: #009970; }
    .panel-stat-yellow .panel-stat-btn { background: #FFA500; }
    .panel-stat-red .panel-stat-btn    { background: #DC3545; }
    .panel-stat-blue .panel-stat-btn   { background: #2196F3; }

    .panel-stat-btn:hover {
      opacity: 0.85;
      transform: translateY(-1px);
      color: #fff;
    }

    .panel-actions {
      display: flex;
      justify-content: center;
      gap: 10px;
      flex-wrap: wrap;
      margin-top: 4px;
    }

    .panel-action-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      border-radius: 10px;
      text-decoration: none;
      font-size: 13px;
      font-weight: 700;
      border: 1px solid transparent;
      transition: all 0.2s ease;
    }

    .panel-action-btn.primary {
      background: #00b59a;
      color: #fff;
      border-color: #00b59a;
    }

    .panel-action-btn.secondary {
      background: #fff;
      color: #3b4a5a;
      border-color: #dbe7ee;
    }

    .panel-action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }

    @media (max-width: 860px) {
      .panel-shell {
        padding: 14px;
      }

      .panel-stat-grid {
        grid-template-columns: 1fr;
      }

      .panel-stat-card {
        min-height: 150px;
      }
    }

    /* ---- Laporan Preview Modal items ---- */
    .laporan-preview-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      background: #f8fcfb;
      border-radius: 10px;
      border: 1px solid #e4eeea;
    }

    .laporan-preview-info {
      flex: 1;
      min-width: 0;
    }

    .laporan-preview-name {
      font-size: 14px;
      font-weight: 600;
      color: #1B2A41;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .laporan-preview-meta {
      font-size: 12px;
      color: #667085;
      margin-top: 3px;
    }

    .laporan-preview-right {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .laporan-preview-badge {
      font-size: 11px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 999px;
      white-space: nowrap;
    }

    .laporan-preview-action-btn {
      background: #00B5A0;
      color: #fff;
      padding: 6px 14px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      text-decoration: none;
      transition: opacity 0.2s;
      white-space: nowrap;
    }

    .laporan-preview-action-btn:hover {
      opacity: 0.82;
      color: #fff;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo Rumah Sakit" />
      <span class="header-title">Dashboard Wakil Direktur</span>
    </div>
    <nav>
      <a href="<?php echo e(route('panduan')); ?>">Panduan</a>
      <a href="<?php echo e(route('kontak')); ?>">Kontak</a>
      <a href="<?php echo e(route('tentang')); ?>">Tentang</a>
    </nav>
    <div></div>
  </header>

  <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;">
    <?php echo csrf_field(); ?>
  </form>

  <div class="dashboard-container">
    <?php echo $__env->make('dashboard.partials.wadir-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
      $panel = request()->query('panel');
    ?>

    <main class="main-content">
      <?php if(!$panel): ?>
        <div class="page-header">
          <h1>Selamat Datang, <?php echo e(auth()->user()->nama); ?></h1>
         </div>

        <div class="summary-grid">
          <div class="summary-card">
            <i class="fas fa-file-contract"></i>
            <h4>Perjanjian Kinerja</h4>
            <div class="number"><?php echo e($totalPerjanjian); ?></div>
          </div>
          <div class="summary-card">
            <i class="fas fa-file-alt"></i>
            <h4>Laporan Kinerja</h4>
            <div class="number"><?php echo e($totalLaporan ?? 0); ?></div>
          </div>
          <div class="summary-card">
            <i class="fas fa-check-circle"></i>
            <h4>Validasi Laporan</h4>
            <div class="number"><?php echo e($laporanValidatedCount ?? 0); ?></div>
          </div>
          <div class="summary-card">
            <i class="fas fa-hourglass-half"></i>
            <h4>Menunggu Reviu</h4>
            <div class="number"><?php echo e($laporanWaitingReviewCount ?? 0); ?></div>
          </div>
        </div>

        <div class="dashboard-widgets">
          <div class="chart-card">
            <h5>Basis Kinerja</h5>
            <canvas id="kinerjaChart" height="300"></canvas>
          </div>
          <div class="chart-card">
            <h5>Basis Keuangan</h5>
            <canvas id="keuanganChart" height="300"></canvas>
          </div>
        </div>
      <?php else: ?>
        <?php if($panel === 'perjanjian'): ?>
          
          <div class="page-header">
            <h1>Perjanjian Kinerja</h1>
                      </div>

          <div class="panel-shell">
            <?php
              $dashboardPerjanjianBackParams = ['from' => 'dashboard_wadir_perjanjian'];
              $perjanjianStatusRoutes = [
                'sent' => route('perjanjian.index', array_merge(['status' => 'sent'], $dashboardPerjanjianBackParams)),
                'approved' => route('perjanjian.index', array_merge(['status' => 'approved'], $dashboardPerjanjianBackParams)),
                'rejected' => route('perjanjian.index', array_merge(['status' => 'rejected'], $dashboardPerjanjianBackParams)),
                'waiting' => route('perjanjian.index', array_merge(['status' => 'waiting'], $dashboardPerjanjianBackParams)),
              ];
            ?>
            
            <div class="panel-stat-grid">
              <div class="panel-stat-card panel-stat-green" onclick="openPerjanjianStatusPanel('Terkirim', <?php echo e((int) ($perjanjianSent ?? 0)); ?>, '<?php echo e($perjanjianStatusRoutes['sent']); ?>')">
                <div class="panel-stat-number"><?php echo e($perjanjianSent ?? 0); ?></div>
                <div class="panel-stat-label">Terkirim</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Terkirim', <?php echo e((int) ($perjanjianSent ?? 0)); ?>, '<?php echo e($perjanjianStatusRoutes['sent']); ?>')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-yellow" onclick="openPerjanjianStatusPanel('Disetujui', <?php echo e((int) $perjanjianApproved); ?>, '<?php echo e($perjanjianStatusRoutes['approved']); ?>')">
                <div class="panel-stat-number"><?php echo e($perjanjianApproved); ?></div>
                <div class="panel-stat-label">Disetujui</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Disetujui', <?php echo e((int) $perjanjianApproved); ?>, '<?php echo e($perjanjianStatusRoutes['approved']); ?>')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-red" onclick="openPerjanjianStatusPanel('Ditolak', <?php echo e((int) $perjanjianRejected); ?>, '<?php echo e($perjanjianStatusRoutes['rejected']); ?>')">
                <div class="panel-stat-number"><?php echo e($perjanjianRejected); ?></div>
                <div class="panel-stat-label">Ditolak</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Ditolak', <?php echo e((int) $perjanjianRejected); ?>, '<?php echo e($perjanjianStatusRoutes['rejected']); ?>')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-blue" onclick="openPerjanjianStatusPanel('Menunggu', <?php echo e((int) $perjanjianWaiting); ?>, '<?php echo e($perjanjianStatusRoutes['waiting']); ?>')">
                <div class="panel-stat-number"><?php echo e($perjanjianWaiting); ?></div>
                <div class="panel-stat-label">Menunggu</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Menunggu', <?php echo e((int) $perjanjianWaiting); ?>, '<?php echo e($perjanjianStatusRoutes['waiting']); ?>')" class="panel-stat-btn">Lihat</a>
              </div>
            </div>

            <div class="panel-actions">
              <a href="<?php echo e(route('perjanjian.create')); ?>" class="panel-action-btn primary">
                <i class="fas fa-plus-circle"></i>
                Tambah Perjanjian
              </a>
             
            </div>
          </div>

        <?php elseif($panel === 'laporan'): ?>
          
          <div class="page-header">
            <h1>Laporan Kinerja</h1>
                      </div>

          <div class="panel-shell">

            <div class="panel-stat-grid">
              <?php
                $laporanWadirBase = route('laporan.wadir.index') . '?from=dashboard_wadir_laporan';
              ?>
              <div class="panel-stat-card panel-stat-green" onclick="openLaporanStatusPanel('Terkirim', <?php echo e((int) ($laporanTerkirimCount ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=terkirim')">
                <div class="panel-stat-number"><?php echo e($laporanTerkirimCount ?? 0); ?></div>
                <div class="panel-stat-label">Terkirim</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Terkirim', <?php echo e((int) ($laporanTerkirimCount ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=terkirim')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-yellow" onclick="openLaporanStatusPanel('Disetujui', <?php echo e((int) ($laporanApprovedByPimpinan ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=disetujui')">
                <div class="panel-stat-number"><?php echo e($laporanApprovedByPimpinan ?? 0); ?></div>
                <div class="panel-stat-label">Disetujui</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Disetujui', <?php echo e((int) ($laporanApprovedByPimpinan ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=disetujui')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-red" onclick="openLaporanStatusPanel('Ditolak', <?php echo e((int) ($laporanRejectedCount ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=ditolak')">
                <div class="panel-stat-number"><?php echo e($laporanRejectedCount ?? 0); ?></div>
                <div class="panel-stat-label">Ditolak</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Ditolak', <?php echo e((int) ($laporanRejectedCount ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=ditolak')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-blue" onclick="openLaporanStatusPanel('Menunggu', <?php echo e((int) ($laporanWaitingReviewCount ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=menunggu')">
                <div class="panel-stat-number"><?php echo e($laporanWaitingReviewCount ?? 0); ?></div>
                <div class="panel-stat-label">Menunggu</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Menunggu', <?php echo e((int) ($laporanWaitingReviewCount ?? 0)); ?>, '<?php echo e($laporanWadirBase); ?>&status=menunggu')" class="panel-stat-btn">Lihat</a>
              </div>
            </div>

            <div class="panel-actions">
              <a href="<?php echo e(route('laporan.kinerja', ['section' => 'laporan', 'from' => 'dashboard_wadir_laporan'])); ?>" class="panel-action-btn primary">
                <i class="fas fa-plus-circle"></i>
                Tambah Laporan
              </a>
              
            </div>
          </div>
        <?php elseif($panel === 'profil'): ?>
          <div class="page-header">
            <h1>Profil <?php echo e(auth()->user()->jabatan ?? 'Pengguna'); ?></h1>
          </div>

          <?php echo $__env->make('dashboard.partials.profile-panel', [
            'title' => 'Profil ' . (auth()->user()->jabatan ?? 'Pengguna'),
            'hideDescription' => true,
            'hideSummary' => true,
            'isEditable' => true
          ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
      <?php endif; ?>
    </main>
  </div>

  <footer style="margin-top:0;background:#fff;text-align:center;font-size:12px;font-weight:700;line-height:1.4;padding:10px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

  <?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <!-- Modals for counts -->
  <div id="modalTotalPerjanjian" class="logout-modal" style="display:none;">
    <div class="logout-box">
      <h3>Total Perjanjian</h3>
      <p style="margin-top:8px;">Total: <strong><?php echo e($totalPerjanjian); ?></strong></p>
      <p>Disetujui: <strong><?php echo e($perjanjianApproved); ?></strong></p>
      <p>Menunggu: <strong><?php echo e($perjanjianWaiting); ?></strong></p>
      <div class="logout-buttons">
        <button class="btn-logout" onclick="closeModal('modalTotalPerjanjian')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalPerjanjianApproved" class="logout-modal" style="display:none;">
    <div class="logout-box">
      <h3>Perjanjian Kinerja Disetujui</h3>
      <p style="margin-top:8px;">Jumlah perjanjian yang telah disetujui oleh pimpinan: <strong><?php echo e($perjanjianApproved); ?></strong></p>
      <div class="logout-buttons">
        <button class="btn-logout" onclick="closeModal('modalPerjanjianApproved')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalLaporanKinerja" class="logout-modal" style="display:none;">
    <div class="logout-box">
      <h3>Laporan Kinerja Disetujui</h3>
      <p style="margin-top:8px;">Jumlah laporan kinerja yang telah disetujui oleh pimpinan: <strong><?php echo e($laporanApprovedByPimpinan ?? 0); ?></strong></p>
      <div class="logout-buttons">
        <button class="btn-logout" onclick="closeModal('modalLaporanKinerja')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalValidasiLaporan" class="logout-modal" style="display:none;">
    <div class="logout-box">
      <h3>Validasi Laporan</h3>
      <p style="margin-top:8px;">Jumlah laporan yang telah divalidasi (kesimpulan terisi): <strong><?php echo e($laporanValidatedCount ?? 0); ?></strong></p>
      <div class="logout-buttons">
        <button class="btn-logout" onclick="closeModal('modalValidasiLaporan')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalMenungguReviu" class="logout-modal" style="display:none;">
    <div class="logout-box">
      <h3>Menunggu Reviu</h3>
      <p style="margin-top:8px;">Jumlah laporan yang menunggu reviu: <strong><?php echo e($laporanWaitingReviewCount ?? 0); ?></strong></p>
      <div class="logout-buttons">
        <button class="btn-logout" onclick="closeModal('modalMenungguReviu')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalPerjanjianStatusKosong" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="logout-box" style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:300px;text-align:center;">
      <h3 style="margin-bottom:18px;">Informasi</h3>
      <p id="modalPerjanjianStatusKosongText" style="margin-top:8px;color:#555;line-height:1.7;">Tidak ada data perjanjian untuk status ini.</p>
      <div class="logout-buttons" style="display:flex;gap:16px;justify-content:center;margin-top:18px;">
        <button type="button" onclick="closeModal('modalPerjanjianStatusKosong')" style="background:#eee;color:#333;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Tutup</button>
      </div>
    </div>
  </div>

  <!-- Modal preview list laporan kinerja per-status -->
  <div id="modalLaporanPreview" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.35);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:28px 24px 24px;border-radius:18px;box-shadow:0 30px 60px rgba(0,0,0,0.18);width:min(620px,95vw);max-height:82vh;display:flex;flex-direction:column;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-shrink:0;">
        <h3 id="laporanPreviewTitle" style="font-size:18px;font-weight:700;color:#1B2A41;margin:0;"></h3>
        <button onclick="closeModal('modalLaporanPreview')" style="background:none;border:none;font-size:22px;line-height:1;cursor:pointer;color:#aaa;padding:0 4px;">&times;</button>
      </div>
      <div id="laporanPreviewList" style="overflow-y:auto;flex:1;display:flex;flex-direction:column;gap:10px;padding-right:4px;min-height:60px;"></div>
      <div style="margin-top:18px;text-align:center;flex-shrink:0;">
        <button onclick="closeModal('modalLaporanPreview')" style="background:#00B5A0;color:#fff;padding:10px 32px;border:none;border-radius:999px;font-weight:700;font-size:14px;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#00977f'" onmouseout="this.style.background='#00B5A0'">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalLaporanPdfPicker" class="logout-modal" style="display:none;">
    <div class="logout-box">
      <h3 id="laporanPdfPickerTitle">Pilih Triwulan Dokumen PDF</h3>
      <p id="laporanPdfPickerDescription" style="margin-top:8px;">Klik triwulan yang ingin ditampilkan dalam bentuk dokumen PDF.</p>
      <div class="logout-buttons" style="display:grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap:10px; margin-top:14px;">
        <button class="btn-cancel" type="button" onclick="openTriwulanPdf(1)">Triwulan 1</button>
        <button class="btn-cancel" type="button" onclick="openTriwulanPdf(2)">Triwulan 2</button>
        <button class="btn-cancel" type="button" onclick="openTriwulanPdf(3)">Triwulan 3</button>
        <button class="btn-cancel" type="button" onclick="openTriwulanPdf(4)">Triwulan 4</button>
      </div>
      <div class="logout-buttons" style="margin-top:14px;">
        <button class="btn-logout" onclick="closeModal('modalLaporanPdfPicker')">Tutup</button>
      </div>
    </div>
  </div>

  <script>
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
      logoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        showLogoutModal();
      });
    }
  </script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
  <?php
    $pdfPerjanjianIdValue = $pdfPerjanjianId ?? null;
    $pdfTriwulanAvailabilityValue = $pdfTriwulanAvailability ?? ['1' => false, '2' => false, '3' => false, '4' => false];
    $pdfPreviewBaseUrlValue = (!empty($pdfPerjanjianIdValue))
      ? route('perjanjian.browsershot.preview', ['id' => $pdfPerjanjianIdValue])
      : '';
  ?>
  <script>
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
      Chart.register(ChartDataLabels);
    }

    const PDF_PERJANJIAN_ID = <?php echo json_encode($pdfPerjanjianIdValue, 15, 512) ?>;
    const PDF_TRIWULAN_AVAILABILITY = <?php echo json_encode($pdfTriwulanAvailabilityValue, 15, 512) ?>;
    const PDF_PREVIEW_BASE_URL = <?php echo json_encode($pdfPreviewBaseUrlValue, 15, 512) ?>;

    // Inject server chart data when backend provided
    <?php if(isset($chartData)): ?>
      window.serverChartData = <?php echo json_encode($chartData, 15, 512) ?>;
    <?php endif; ?>
    // Sample data fallback (if backend doesn't provide chart data)
    const sampleKinerjaLabels = ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'];
    const sampleKinerjaRealisasiPersen = [0,0,0,0];
    const sampleAnggaranRealisasiPersen = [0,0,0,0];
    const chartLabels = ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'];
    const sampleTargetKeuangan = [0,0,0,0];
    const sampleRealisasiKeuangan = [0,0,0,0];

    // Try to use server-provided variables when available
    const serverChart = window.serverChartData || null;
    const kinerjaLabels = serverChart?.kinerja_labels || sampleKinerjaLabels;
    const realisasiKinerjaPersen = serverChart?.kinerja_realisasi_kinerja_persen || sampleKinerjaRealisasiPersen;
    const realisasiAnggaranPersen = serverChart?.kinerja_realisasi_anggaran_persen || sampleAnggaranRealisasiPersen;
    const keuanganLabels = serverChart?.keuangan_labels || chartLabels;
    const targetKeuangan = serverChart?.keuangan_targets || sampleTargetKeuangan;
    const realisasiKeuangan = serverChart?.keuangan_realisasi || sampleRealisasiKeuangan;

    const formatIdr = (value) => new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(Number(value || 0));
    const formatPercent = (value) => `${Number(value || 0).toFixed(2)}%`;

    // Render Kinerja chart (line) - Realisasi Kinerja dibanding Realisasi Anggaran (persen)
    (function renderKinerja(){
      const el = document.getElementById('kinerjaChart');
      if (!el) return;
      const datasets = [{
        label: 'Realisasi Kinerja (%)',
        data: realisasiKinerjaPersen,
        borderColor: '#00B5A0',
        backgroundColor: 'rgba(0,181,160,0.12)',
        tension: 0.2,
        pointRadius: 4,
        fill: false,
        datalabels: {
          display: true,
          align: 'top',
          anchor: 'end',
          color: '#0b5563',
          formatter: (value) => formatPercent(value)
        }
      }, {
        label: 'Realisasi Anggaran (%)',
        data: realisasiAnggaranPersen,
        borderColor: '#FF9800',
        backgroundColor: 'rgba(255,152,0,0.12)',
        tension: 0.2,
        pointRadius: 4,
        fill: false,
        datalabels: {
          display: true,
          align: 'top',
          anchor: 'end',
          color: '#7a4200',
          formatter: (value) => formatPercent(value)
        }
      }];

      new Chart(el.getContext('2d'), {
        type: 'line',
        data: { labels: kinerjaLabels, datasets: datasets },
        options: {
          responsive:true,
          maintainAspectRatio:false,
          plugins:{
            legend:{position:'top'},
            datalabels: { clamp: true, clip: false }
          },
          scales:{
            y:{
              beginAtZero:true,
              ticks:{ callback: function(v){ return `${v}%`; } }
            }
          }
        }
      });
    })();

    // Render Keuangan chart (line) - always show target and realisasi values on points
    (function renderKeuangan(){
      const el = document.getElementById('keuanganChart');
      if (!el) return;
      const datasets = [];
      datasets.push({
        label: 'Target Rupiah Triwulan',
        data: targetKeuangan,
        borderColor: '#3F51B5',
        backgroundColor: 'rgba(63,81,181,0.12)',
        tension: 0.2,
        pointRadius: 4,
        fill: false,
        datalabels: {
          display: true,
          align: 'top',
          anchor: 'end',
          color: '#1f2b73',
          formatter: (value) => formatIdr(value)
        }
      });
      datasets.push({
        label: 'Realisasi Rupiah',
        data: realisasiKeuangan,
        borderColor: '#9C27B0',
        backgroundColor: 'rgba(156,39,176,0.12)',
        tension: 0.2,
        pointRadius: 4,
        fill: false,
        datalabels: {
          display: true,
          align: 'bottom',
          anchor: 'end',
          color: '#5f1672',
          formatter: (value) => formatIdr(value)
        }
      });

      new Chart(el.getContext('2d'), {
        type: 'line',
        data: { labels: keuanganLabels, datasets: datasets },
        options: {
          responsive:true,
          maintainAspectRatio:false,
          plugins:{
            legend:{position:'top'},
            datalabels: { clamp: true, clip: false }
          },
          scales: { y: { beginAtZero:true, ticks: { callback: function(v){ return new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(v); } } } }
        }
      });
    })();

    // Modal helpers
    function openModal(id) {
      const el = document.getElementById(id);
      if (!el) return;
      el.style.display = 'flex';
    }
    function closeModal(id) {
      const el = document.getElementById(id);
      if (!el) return;
      el.style.display = 'none';
    }

    function openLaporanPdfPicker(statusLabel) {
      const perjanjianId = Number(PDF_PERJANJIAN_ID);
      if (!perjanjianId) {
        alert('Belum ada perjanjian yang dapat ditampilkan sebagai PDF.');
        return;
      }

      const normalizedStatus = statusLabel || 'Laporan';
      const titleEl = document.getElementById('laporanPdfPickerTitle');
      const descriptionEl = document.getElementById('laporanPdfPickerDescription');

      if (titleEl) {
        titleEl.textContent = 'Pilih Triwulan Dokumen PDF ' + normalizedStatus;
      }

      if (descriptionEl) {
        descriptionEl.textContent = 'Klik triwulan untuk menampilkan dokumen laporan kinerja PDF pada status ' + normalizedStatus + '.';
      }

      openModal('modalLaporanPdfPicker');
    }

    function openPerjanjianStatusPanel(statusLabel, count, targetUrl) {
      if (Number(count || 0) <= 0) {
        const textEl = document.getElementById('modalPerjanjianStatusKosongText');
        if (textEl) {
          textEl.textContent = 'Tidak ada data perjanjian dengan status ' + statusLabel.toLowerCase() + '.';
        }
        openModal('modalPerjanjianStatusKosong');
        return;
      }

      window.location.href = targetUrl;
    }

    function openLaporanStatusPanel(statusLabel, count, targetUrl) {
      if (Number(count || 0) <= 0) {
        const textEl = document.getElementById('modalPerjanjianStatusKosongText');
        if (textEl) {
          textEl.textContent = 'Tidak ada laporan kinerja dengan status ' + statusLabel.toLowerCase() + '.';
        }
        openModal('modalPerjanjianStatusKosong');
        return;
      }

      window.location.href = targetUrl;
    }

    function openTriwulanPdf(tw) {
      const perjanjianId = Number(PDF_PERJANJIAN_ID);
      const availability = PDF_TRIWULAN_AVAILABILITY || {};
      if (!perjanjianId) {
        alert('Data perjanjian tidak ditemukan untuk preview PDF.');
        return;
      }

      if (!availability[String(tw)] && !availability[tw]) {
        alert('Dokumen Triwulan ' + tw + ' belum tersedia karena realisasi belum terisi atau masih 0.');
        return;
      }

      const baseUrl = PDF_PREVIEW_BASE_URL;
      if (!baseUrl) {
        alert('URL preview PDF belum tersedia.');
        return;
      }

      window.open(baseUrl + '?triwulan=' + tw, '_blank');
      closeModal('modalLaporanPdfPicker');
    }

    document.addEventListener('DOMContentLoaded', function () {
      const params = new URLSearchParams(window.location.search);
      const panel = params.get('panel');
      if (panel === 'perjanjian' || panel === 'laporan') {
        if (typeof openActionPanel === 'function') {
          openActionPanel(panel);
        }
      }

      const modalKosong = document.getElementById('modalPerjanjianStatusKosong');
      if (modalKosong) {
        modalKosong.addEventListener('click', function (e) {
          if (e.target === modalKosong) {
            closeModal('modalPerjanjianStatusKosong');
          }
        });
      }

      const modalLaporan = document.getElementById('modalLaporanPreview');
      if (modalLaporan) {
        modalLaporan.addEventListener('click', function (e) {
          if (e.target === modalLaporan) closeModal('modalLaporanPreview');
        });
      }

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          closeModal('modalPerjanjianStatusKosong');
          closeModal('modalLaporanPreview');
        }
      });
    });

    // ---- Laporan preview modal ----
    const LAPORAN_ITEMS = <?php echo json_encode($laporanItems ?? [], 15, 512) ?>;
    const LAPORAN_KINERJA_URL = "<?php echo e(route('laporan.kinerja')); ?>";

    function escapeHtml(str) {
      if (!str) return '';
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    }

    function openLaporanStatusModal(status) {
      const statusMap = {
        terkirim : { label: 'Semua Laporan Kinerja',          bg: '#e3f9f1', color: '#009970', text: 'Terkirim'  },
        disetujui: { label: 'Laporan Kinerja — Disetujui',    bg: '#fff3e0', color: '#FFA500', text: 'Disetujui' },
        ditolak  : { label: 'Laporan Kinerja — Ditolak',      bg: '#fde8e8', color: '#DC3545', text: 'Ditolak'   },
        menunggu : { label: 'Laporan Kinerja — Menunggu Reviu', bg: '#e3f2fd', color: '#2196F3', text: 'Menunggu' },
      };

      const meta = statusMap[status] || statusMap['terkirim'];
      // 'terkirim' card = all items
      const items = (status === 'terkirim')
        ? LAPORAN_ITEMS
        : LAPORAN_ITEMS.filter(function(i){ return i.status === status; });

      if (items.length === 0) {
        const textEl = document.getElementById('modalPerjanjianStatusKosongText');
        if (textEl) textEl.textContent = 'Tidak ada laporan kinerja dengan status ' + meta.text.toLowerCase() + '.';
        openModal('modalPerjanjianStatusKosong');
        return;
      }

      document.getElementById('laporanPreviewTitle').textContent = meta.label;

      const container = document.getElementById('laporanPreviewList');
      container.innerHTML = '';

      items.forEach(function(item) {
        const sc = statusMap[item.status] || statusMap['terkirim'];
        const triwulanText = item.triwulan_aktif ? 'Triwulan ' + item.triwulan_aktif : (item.periode || '-');
        const tahunText = item.tahun ? ' \u2014 ' + item.tahun : '';
        const laporanUrl = LAPORAN_KINERJA_URL
          + '?section=laporan&from=dashboard_wadir_laporan'
          + (item.perjanjian_id ? '&perjanjian_id=' + item.perjanjian_id : '');

        const div = document.createElement('div');
        div.className = 'laporan-preview-item';
        div.innerHTML =
          '<div class="laporan-preview-info">'
            + '<div class="laporan-preview-name">' + escapeHtml(item.uraian_kegiatan || 'Laporan Kinerja') + '</div>'
            + '<div class="laporan-preview-meta">' + escapeHtml(triwulanText) + escapeHtml(tahunText) + '</div>'
          + '</div>'
          + '<div class="laporan-preview-right">'
            + '<span class="laporan-preview-badge" style="background:' + sc.bg + ';color:' + sc.color + ';">' + sc.text + '</span>'
            + '<a href="' + laporanUrl + '" class="laporan-preview-action-btn">Lihat</a>'
          + '</div>';
        container.appendChild(div);
      });

      openModal('modalLaporanPreview');
    }
  </script>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\dashboard\wadir.blade.php ENDPATH**/ ?>