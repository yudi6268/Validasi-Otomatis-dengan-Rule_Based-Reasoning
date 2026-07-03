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
      min-height: 0;
      overflow: hidden;
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

    .sidebar-menu a.logout-link { color: #e53e3e; }
    .sidebar-menu a.logout-link i { color: #e53e3e; }
    .sidebar-menu a.logout-link:hover { background: #fff5f5; border-inline-start-color: #e53e3e; color: #e53e3e; }

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
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      cursor: pointer;
    }

    .summary-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 28px 56px rgba(0,0,0,0.08);
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
      cursor: pointer;
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

    /* ---- Validasi Drawer (right-side panel) ---- */
    .validasi-drawer-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9998;
      background: rgba(0,0,0,0.28);
    }
    .validasi-drawer-overlay.open { display: block; }
    .validasi-drawer {
      position: fixed;
      top: 0;
      right: -420px;
      width: min(420px, 95vw);
      height: 100vh;
      background: #fff;
      box-shadow: -4px 0 32px rgba(0,0,0,0.18);
      z-index: 9999;
      transition: right 0.28s cubic-bezier(.4,0,.2,1);
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }
    .validasi-drawer.open { right: 0; }
    .validasi-drawer-header {
      padding: 20px 22px 16px;
      border-bottom: 1px solid #e8eff4;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }
    .validasi-drawer-title {
      font-size: 17px;
      font-weight: 700;
      color: #1B2A41;
    }
    .validasi-drawer-close {
      background: none;
      border: none;
      font-size: 22px;
      line-height: 1;
      cursor: pointer;
      color: #aaa;
      padding: 0 4px;
      transition: color .15s;
    }
    .validasi-drawer-close:hover { color: #333; }
    .validasi-drawer-body {
      flex: 1;
      overflow-y: auto;
      padding: 20px 22px;
    }
    .validasi-drawer-status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 5px 16px;
      border-radius: 999px;
      font-size: 13px;
      font-weight: 700;
      margin-bottom: 18px;
    }
    .validasi-drawer-status-badge.validated { background: #e8f5e9; color: #2e7d32; }
    .validasi-drawer-status-badge.not-validated { background: #f3f8f7; color: #607d8b; }
    .validasi-drawer-score-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-bottom: 18px;
    }
    .validasi-drawer-metric {
      background: #f8fcfb;
      border: 1px solid #e4eeea;
      border-radius: 10px;
      padding: 12px 14px;
      text-align: center;
    }
    .validasi-drawer-metric .num {
      font-size: 24px;
      font-weight: 800;
      color: #00B5A0;
    }
    .validasi-drawer-metric .lbl {
      font-size: 11px;
      font-weight: 600;
      color: #667085;
      margin-top: 2px;
    }
    .validasi-drawer-summary {
      background: #f8fcfb;
      border: 1px solid #e4eeea;
      border-radius: 10px;
      padding: 14px 16px;
      font-size: 13px;
      color: #444;
      line-height: 1.7;
      margin-bottom: 18px;
    }
    .validasi-drawer-footer {
      padding: 14px 22px 18px;
      border-top: 1px solid #e8eff4;
      display: flex;
      gap: 10px;
      flex-shrink: 0;
    }
    .validasi-drawer-btn-primary {
      flex: 1;
      background: #00B5A0;
      color: #fff;
      border: none;
      border-radius: 999px;
      padding: 10px 0;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      transition: background .2s;
    }
    .validasi-drawer-btn-primary:hover { background: #00977f; color: #fff; }
    .validasi-drawer-btn-secondary {
      background: #f0f0f0;
      color: #555;
      border: none;
      border-radius: 999px;
      padding: 10px 20px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: background .2s;
    }
    .validasi-drawer-btn-secondary:hover { background: #e0e0e0; }
    .panel-stat-card.validasi-done { background: linear-gradient(135deg, #e8f5e9 0%, #f0fbf7 100%); border: 1.5px solid #a5d6a7; }
    .panel-stat-card.validasi-done .panel-stat-number { color: #2e7d32; }
    .panel-stat-card.validasi-pending { background: linear-gradient(135deg, #f3f8f7 0%, #edf2f4 100%); border: 1.5px solid #cfd8dc; }
    .panel-stat-card.validasi-pending .panel-stat-number { color: #607d8b; }

    .notice-box {
      background: #fff;
      width: min(420px, 92vw);
      border-radius: 18px;
      box-shadow: 0 20px 56px rgba(16, 24, 40, 0.2);
      border: 1px solid #e8eef2;
      padding: 22px 24px 20px;
      text-align: center;
    }

    .notice-icon {
      width: 48px;
      height: 48px;
      margin: 0 auto 12px;
      border-radius: 999px;
      display: grid;
      place-items: center;
      font-size: 20px;
      background: linear-gradient(135deg, #e8fffa 0%, #dff6ff 100%);
      color: #009a86;
      border: 1px solid #c9ece5;
    }

    .notice-title {
      margin: 0 0 10px;
      font-size: 31px;
      font-weight: 700;
      color: #1b2a41;
      line-height: 1.3;
    }

    .notice-message {
      margin: 0;
      font-size: 16px;
      color: #4b5565;
      line-height: 1.7;
    }

    .notice-actions {
      margin-top: 18px;
      display: flex;
      justify-content: center;
      gap: 12px;
    }

    .notice-close-btn {
      border: none;
      border-radius: 10px;
      background: #00b5a0;
      color: #fff;
      padding: 9px 26px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 6px 14px rgba(0, 181, 160, 0.26);
      transition: transform .15s ease, opacity .15s ease;
    }

    .notice-close-btn:hover {
      transform: translateY(-1px);
      opacity: 0.92;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo Rumah Sakit" />
      <span class="header-title">Dashboard Wakil Direktur</span>
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

  <div class="dashboard-container">
    @include('dashboard.partials.wadir-sidebar')

    @php
      $panel = request()->query('panel');
    @endphp

    <main class="main-content">
      @if (!$panel)
        <div class="page-header">
          <h1>Selamat Datang, {{ auth()->user()->nama }}</h1>
         </div>

        <div class="summary-grid">
          <div class="summary-card" onclick="openModal('modalTotalPerjanjian')">
            <i class="fas fa-file-contract"></i>
            <h4>Perjanjian Kinerja</h4>
            <div class="number">{{ $totalPerjanjian }}</div>
          </div>
          <div class="summary-card" onclick="openModal('modalLaporanKinerja')">
            <i class="fas fa-file-alt"></i>
            <h4>Laporan Kinerja</h4>
            <div class="number">{{ $totalLaporan ?? 0 }}</div>
          </div>
          <div class="summary-card" onclick="openModal('modalValidasiLaporan')">
            <i class="fas fa-check-circle"></i>
            <h4>Validasi Laporan</h4>
            <div class="number">{{ $laporanValidatedCount ?? 0 }}</div>
          </div>
          <div class="summary-card" onclick="openModal('modalMenungguReviu')">
            <i class="fas fa-hourglass-half"></i>
            <h4>Menunggu Reviu</h4>
            <div class="number">{{ $laporanWaitingReviewCount ?? 0 }}</div>
          </div>
        </div>

        @if(isset($chartData) && !empty($chartData['hasData']))
        <div class="dashboard-widgets">
          <div class="chart-card">
            <h5>Persentase Realisasi Basis Kinerja vs Basis Anggaran</h5>
            <canvas id="kinerjaChart" height="300"></canvas>
          </div>
          <div class="chart-card">
            <h5>Perbandingan Target dan Realisasi Basis Anggaran</h5>
            <canvas id="keuanganChart" height="300"></canvas>
          </div>
        </div>
        @else
        <div class="dashboard-widgets">
          <div class="chart-card" style="grid-column: 1 / -1; min-height: 220px; display:flex; align-items:center; justify-content:center; text-align:center;">
            <div>
              <h5 style="margin-bottom: 10px;">Grafik belum tersedia</h5>
              <p style="margin: 0; color: #667085;">Belum ada laporan kinerja milik akun ini, jadi grafik belum ditampilkan.</p>
            </div>
          </div>
        </div>
        @endif
      @else
        @if ($panel === 'perjanjian')
          {{-- ==================== PANEL PERJANJIAN KINERJA ==================== --}}
          <div class="page-header">
            <h1>Perjanjian Kinerja</h1>
                      </div>

          <div class="panel-shell">

            <div class="panel-stat-grid">
              <div class="panel-stat-card panel-stat-green" onclick="openPerjanjianStatusPanel('Terkirim', {{ (int) ($perjanjianSent ?? 0) }})">
                <div class="panel-stat-number">{{ $perjanjianSent ?? 0 }}</div>
                <div class="panel-stat-label">Terkirim</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Terkirim', {{ (int) ($perjanjianSent ?? 0) }})" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-yellow" onclick="openPerjanjianStatusPanel('Disetujui', {{ (int) $perjanjianApproved }})">
                <div class="panel-stat-number">{{ $perjanjianApproved }}</div>
                <div class="panel-stat-label">Disetujui</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Disetujui', {{ (int) $perjanjianApproved }})" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-red" onclick="openPerjanjianStatusPanel('Ditolak', {{ (int) $perjanjianRejected }})">
                <div class="panel-stat-number">{{ $perjanjianRejected }}</div>
                <div class="panel-stat-label">Ditolak</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Ditolak', {{ (int) $perjanjianRejected }})" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-blue" onclick="openPerjanjianStatusPanel('Menunggu', {{ (int) $perjanjianWaiting }})">
                <div class="panel-stat-number">{{ $perjanjianWaiting }}</div>
                <div class="panel-stat-label">Menunggu</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openPerjanjianStatusPanel('Menunggu', {{ (int) $perjanjianWaiting }})" class="panel-stat-btn">Lihat</a>
              </div>
            </div>

            <div class="panel-actions">
              <a href="{{ route('perjanjian.create') }}" class="panel-action-btn primary">
                <i class="fas fa-plus-circle"></i>
                Tambah Perjanjian
              </a>
             
            </div>
          </div>

        @elseif ($panel === 'laporan')
          {{-- ==================== PANEL LAPORAN KINERJA ==================== --}}
          <div class="page-header">
            <h1>Laporan Kinerja</h1>
                      </div>

          <div class="panel-shell">

            <div class="panel-stat-grid">
              @php
                $laporanWadirBase = route('laporan.wadir.index') . '?from=dashboard_wadir_laporan';
              @endphp
              <div class="panel-stat-card panel-stat-green" onclick="openLaporanStatusPanel('Terkirim', {{ (int) ($laporanTerkirimCount ?? 0) }}, '{{ $laporanWadirBase }}&status=terkirim')">
                <div class="panel-stat-number">{{ $laporanTerkirimCount ?? 0 }}</div>
                <div class="panel-stat-label">Terkirim</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Terkirim', {{ (int) ($laporanTerkirimCount ?? 0) }}, '{{ $laporanWadirBase }}&status=terkirim')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-yellow" onclick="openLaporanStatusPanel('Disetujui', {{ (int) ($laporanApprovedByPimpinan ?? 0) }}, '{{ $laporanWadirBase }}&status=disetujui')">
                <div class="panel-stat-number">{{ $laporanApprovedByPimpinan ?? 0 }}</div>
                <div class="panel-stat-label">Disetujui</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Disetujui', {{ (int) ($laporanApprovedByPimpinan ?? 0) }}, '{{ $laporanWadirBase }}&status=disetujui')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-red" onclick="openLaporanStatusPanel('Ditolak', {{ (int) ($laporanRejectedCount ?? 0) }}, '{{ $laporanWadirBase }}&status=ditolak')">
                <div class="panel-stat-number">{{ $laporanRejectedCount ?? 0 }}</div>
                <div class="panel-stat-label">Ditolak</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Ditolak', {{ (int) ($laporanRejectedCount ?? 0) }}, '{{ $laporanWadirBase }}&status=ditolak')" class="panel-stat-btn">Lihat</a>
              </div>
              <div class="panel-stat-card panel-stat-blue" onclick="openLaporanStatusPanel('Menunggu', {{ (int) ($laporanWaitingReviewCount ?? 0) }}, '{{ $laporanWadirBase }}&status=menunggu')">
                <div class="panel-stat-number">{{ $laporanWaitingReviewCount ?? 0 }}</div>
                <div class="panel-stat-label">Menunggu</div>
                <a href="#" onclick="event.preventDefault(); event.stopPropagation(); openLaporanStatusPanel('Menunggu', {{ (int) ($laporanWaitingReviewCount ?? 0) }}, '{{ $laporanWadirBase }}&status=menunggu')" class="panel-stat-btn">Lihat</a>
              </div>
            </div>

            <div class="panel-actions">
              @php $twAktifDash = (int) (\App\Models\Setting::where('key','triwulan_aktif')->value('value') ?? 1); @endphp
              <button type="button" class="panel-action-btn primary" onclick="handleTambahLaporan({{ $twAktifDash }})">
                <i class="fas fa-plus-circle"></i>
                Tambah Laporan
              </button>
              
            </div>
          </div>
        @elseif ($panel === 'validasi')
          {{-- ==================== PANEL VALIDASI LAPORAN ==================== --}}
          <div class="page-header">
            <h1>Validasi Laporan</h1>
          </div>

          <div class="panel-shell">
            <div class="panel-stat-grid" id="validasiStatGrid">
              <div class="panel-stat-card panel-stat-green" id="validasiCard1" style="cursor:pointer;" onclick="openValidasiDrawer(1)">
                <div class="panel-stat-number" id="validasiNum1">—</div>
                <div class="panel-stat-label">Triwulan I</div>
                <button type="button" id="validasiBtn1" class="panel-stat-btn" onclick="event.preventDefault();event.stopPropagation();openValidasiDrawer(1)" style="border:none;cursor:pointer;">Lihat</button>
              </div>
              <div class="panel-stat-card panel-stat-yellow" id="validasiCard2" style="cursor:pointer;" onclick="openValidasiDrawer(2)">
                <div class="panel-stat-number" id="validasiNum2">—</div>
                <div class="panel-stat-label">Triwulan II</div>
                <button type="button" id="validasiBtn2" class="panel-stat-btn" onclick="event.preventDefault();event.stopPropagation();openValidasiDrawer(2)" style="border:none;cursor:pointer;">Lihat</button>
              </div>
              <div class="panel-stat-card panel-stat-red" id="validasiCard3" style="cursor:pointer;" onclick="openValidasiDrawer(3)">
                <div class="panel-stat-number" id="validasiNum3">—</div>
                <div class="panel-stat-label">Triwulan III</div>
                <button type="button" id="validasiBtn3" class="panel-stat-btn" onclick="event.preventDefault();event.stopPropagation();openValidasiDrawer(3)" style="border:none;cursor:pointer;">Lihat</button>
              </div>
              <div class="panel-stat-card panel-stat-blue" id="validasiCard4" style="cursor:pointer;" onclick="openValidasiDrawer(4)">
                <div class="panel-stat-number" id="validasiNum4">—</div>
                <div class="panel-stat-label">Triwulan IV</div>
                <button type="button" id="validasiBtn4" class="panel-stat-btn" onclick="event.preventDefault();event.stopPropagation();openValidasiDrawer(4)" style="border:none;cursor:pointer;">Lihat</button>
              </div>
            </div>

            <div class="panel-actions" style="margin-top:16px;">
              <button type="button" id="openValidasiBtn" class="panel-action-btn primary"
                @if($isActiveTrwValidated)
                  disabled style="opacity:0.5;cursor:not-allowed;"
                @else
                  onclick="openValidasiSelectModal()"
                @endif
              >
                <i class="fas fa-shield-alt"></i>
                {{ $isActiveTrwValidated ? 'Sudah Divalidasi' : 'Validasi Laporan' }}
              </button>
            </div>

          </div>

        @elseif ($panel === 'profil')
          <div class="page-header">
            <h1>Profil {{ auth()->user()->jabatan ?? 'Pengguna' }}</h1>
          </div>

          @include('dashboard.partials.profile-panel', [
            'title' => 'Profil ' . (auth()->user()->jabatan ?? 'Pengguna'),
            'hideDescription' => true,
            'hideSummary' => true,
            'isEditable' => true
          ])
        @endif
      @endif
    </main>
  </div>

  <footer style="margin-top:0;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;flex-shrink:0;">© {{ date('Y') }} RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

  @include('components.logout-modal')

  <!-- Modals for counts -->
  <div id="modalTotalPerjanjian" class="logout-modal" style="display:none;position:fixed;inset:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;z-index:9999;padding:16px;">
    <div class="notice-box">
      <div class="notice-icon"><i class="fas fa-file-contract"></i></div>
      <h3 class="notice-title">Total Perjanjian</h3>
      <p class="notice-message">Total: <strong>{{ $totalPerjanjian }}</strong><br>Terkirim: <strong>{{ $perjanjianSent }}</strong><br>Disetujui: <strong>{{ $perjanjianApproved }}</strong><br>Ditolak: <strong>{{ $perjanjianRejected }}</strong><br>Menunggu: <strong>{{ $perjanjianWaiting }}</strong></p>
      <div class="notice-actions">
        <button class="notice-close-btn" onclick="closeModal('modalTotalPerjanjian')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalPerjanjianApproved" class="logout-modal" style="display:none;position:fixed;inset:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;z-index:9999;padding:16px;">
    <div class="notice-box">
      <div class="notice-icon"><i class="fas fa-circle-check"></i></div>
      <h3 class="notice-title">Perjanjian Disetujui</h3>
      <p class="notice-message">Jumlah perjanjian yang telah disetujui oleh pimpinan: <strong>{{ $perjanjianApproved }}</strong></p>
      <div class="notice-actions">
        <button class="notice-close-btn" onclick="closeModal('modalPerjanjianApproved')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalLaporanKinerja" class="logout-modal" style="display:none;position:fixed;inset:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;z-index:9999;padding:16px;">
    <div class="notice-box">
      <div class="notice-icon"><i class="fas fa-file-circle-check"></i></div>
      <h3 class="notice-title">Laporan Kinerja</h3>
      <p class="notice-message">Jumlah laporan kinerja yang telah disetujui oleh pimpinan: <strong>{{ $laporanApprovedByPimpinan ?? 0 }}</strong></p>
      <div class="notice-actions">
        <button class="notice-close-btn" onclick="closeModal('modalLaporanKinerja')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalValidasiLaporan" class="logout-modal" style="display:none;position:fixed;inset:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;z-index:9999;padding:16px;">
    <div class="notice-box">
      <div class="notice-icon"><i class="fas fa-shield-check"></i></div>
      <h3 class="notice-title">Validasi Laporan</h3>
      <p class="notice-message">Jumlah laporan yang telah divalidasi (kesimpulan terisi): <strong>{{ $laporanValidatedCount ?? 0 }}</strong></p>
      <div class="notice-actions">
        <button class="notice-close-btn" onclick="closeModal('modalValidasiLaporan')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalMenungguReviu" class="logout-modal" style="display:none;position:fixed;inset:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;z-index:9999;padding:16px;">
    <div class="notice-box">
      <div class="notice-icon"><i class="fas fa-hourglass-half"></i></div>
      <h3 class="notice-title">Menunggu Reviu</h3>
      <p class="notice-message">Jumlah laporan yang menunggu reviu: <strong>{{ $laporanWaitingReviewCount ?? 0 }}</strong></p>
      <div class="notice-actions">
        <button class="notice-close-btn" onclick="closeModal('modalMenungguReviu')">Tutup</button>
      </div>
    </div>
  </div>

  <!-- Validasi Drawer Overlay -->
  <div id="validasiDrawerOverlay" class="validasi-drawer-overlay" onclick="closeValidasiDrawer()"></div>

  <!-- Validasi Drawer Panel (shared, populated by JS) -->
  <div id="validasiDrawer" class="validasi-drawer">
    <div class="validasi-drawer-header">
      <div class="validasi-drawer-title" id="validasiDrawerTitle">Triwulan I</div>
      <button class="validasi-drawer-close" onclick="closeValidasiDrawer()" aria-label="Tutup">&times;</button>
    </div>
    <div class="validasi-drawer-body">
      <div id="validasiDrawerStatusBadge" class="validasi-drawer-status-badge not-validated">
        <i class="fas fa-circle-xmark"></i> <span id="validasiDrawerStatusText">Belum Divalidasi</span>
      </div>
      <div id="validasiDrawerScoreSection" style="display:none;">
        <div class="validasi-drawer-score-grid">
          <div class="validasi-drawer-metric"><div class="num" id="vdScore">-</div><div class="lbl">Skor</div></div>
          <div class="validasi-drawer-metric"><div class="num" id="vdIssues">0</div><div class="lbl">Issues</div></div>
          <div class="validasi-drawer-metric"><div class="num" id="vdWarnings">0</div><div class="lbl">Peringatan</div></div>
          <div class="validasi-drawer-metric"><div class="num" id="vdSuggestions">0</div><div class="lbl">Saran</div></div>
        </div>
        <div class="validasi-drawer-summary" id="vdSummaryText">-</div>
        <p id="vdUpdatedAt" style="font-size:11px;color:#aaa;margin-bottom:0;"></p>
      </div>
      <div id="validasiDrawerEmptyNote" style="color:#777;font-size:13px;line-height:1.7;display:none;">
        Laporan triwulan ini belum divalidasi. Buka halaman Validasi Laporan untuk menjalankan validasi.
      </div>
    </div>
    <div class="validasi-drawer-footer">
      <button id="pdfPreviewBtn" class="validasi-drawer-btn-primary" style="flex:1;display:none;" onclick="var tw = currentTwForDrawer; if (tw) openPdfPreviewModal(tw);"><i class="fas fa-file-pdf" style="margin-right:8px;"></i> Lihat Ringkasan</button>
      <button class="validasi-drawer-btn-secondary" style="flex:1;" onclick="closeValidasiDrawer()">Tutup</button>
    </div>
  </div>

  <!-- PDF Preview Modal -->
  <div id="modalValidasiSelect" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="logout-box" style="background:#fff;padding:28px 28px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:320px;max-width:420px;text-align:left;">
      <h3 style="margin-bottom:16px;font-size:16px;font-weight:600;color:#1a2a25;">Pilih Triwulan untuk Validasi</h3>
      <div id="validasiSelectContent" style="display:flex;flex-direction:column;gap:12px;margin-bottom:18px;">
        <p style="color:#999;margin:0;">Memuat daftar triwulan...</p>
      </div>
      <div class="logout-buttons" style="display:flex;justify-content:flex-end;">
        <button class="btn-logout" style="background:#eef0f3;color:#333;padding:10px 22px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;" onclick="closeModal('modalValidasiSelect')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="pdfPreviewModal" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.5);align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.25);width:min(920px,95vw);max-height:90vh;display:flex;flex-direction:column;">
      <!-- Header -->
      <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #e0e0e0;flex-shrink:0;">
        <div>
          <h3 id="pdfModalTitle" style="font-size:18px;font-weight:700;color:#1B2A41;margin:0;">Preview Laporan Kinerja</h3>
          <p id="pdfModalSubtitle" style="font-size:12px;color:#999;margin:4px 0 0 0;">Ringkasan Validasi</p>
        </div>
        <button onclick="closePdfPreviewModal()" style="background:none;border:none;font-size:24px;line-height:1;cursor:pointer;color:#aaa;padding:0 4px;">&times;</button>
      </div>
      
      <!-- Body -->
      <div style="flex:1;overflow-y:auto;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;background:#f5f5f5;padding:20px;">
        <div id="pdfPreviewContent" style="background:#fff;border-radius:8px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.1);max-width:800px;width:100%;">
          <div style="text-align:center;padding:20px;color:#999;">
            <p style="font-size:14px;margin:0;">Memuat ringkasan validasi...</p>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div style="display:flex;gap:10px;padding:20px 24px;border-top:1px solid #e0e0e0;flex-shrink:0;justify-content:flex-end;">
        <button onclick="closePdfPreviewModal()" style="background:#eee;color:#333;padding:10px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalPerjanjianStatusKosong" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="notice-box">
      <div class="notice-icon"><i class="fas fa-circle-info"></i></div>
      <h3 class="notice-title">Informasi</h3>
      <p id="modalPerjanjianStatusKosongText" class="notice-message">Tidak ada data perjanjian untuk status ini.</p>
      <div class="notice-actions">
        <button type="button" class="notice-close-btn" onclick="closeModal('modalPerjanjianStatusKosong')">Tutup</button>
      </div>
    </div>
  </div>

  <div id="modalPerjanjianPreview" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.35);align-items:center;justify-content:center;">
    <div style="background:#fff;padding:28px 24px 24px;border-radius:18px;box-shadow:0 30px 60px rgba(0,0,0,0.18);width:min(680px,95vw);max-height:82vh;display:flex;flex-direction:column;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-shrink:0;">
        <h3 id="perjanjianPreviewTitle" style="font-size:18px;font-weight:700;color:#1B2A41;margin:0;"></h3>
        <button onclick="closeModal('modalPerjanjianPreview')" style="background:none;border:none;font-size:22px;line-height:1;cursor:pointer;color:#aaa;padding:0 4px;">&times;</button>
      </div>
      <div id="perjanjianPreviewList" style="overflow-y:auto;flex:1;display:flex;flex-direction:column;gap:10px;padding-right:4px;min-height:60px;"></div>
      <div style="margin-top:18px;text-align:center;flex-shrink:0;">
        <button onclick="closeModal('modalPerjanjianPreview')" style="background:#00B5A0;color:#fff;padding:10px 32px;border:none;border-radius:999px;font-weight:700;font-size:14px;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='#00977f'" onmouseout="this.style.background='#00B5A0'">Tutup</button>
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
  @php
    $pdfPerjanjianIdValue = $pdfPerjanjianId ?? null;
    $pdfTriwulanAvailabilityValue = $pdfTriwulanAvailability ?? ['1' => false, '2' => false, '3' => false, '4' => false];
    $pdfPreviewBaseUrlValue = (!empty($pdfPerjanjianIdValue))
      ? route('perjanjian.browsershot.preview', ['id' => $pdfPerjanjianIdValue])
      : '';
  @endphp
  <script>
    if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
      Chart.register(ChartDataLabels);
    }

    const PDF_PERJANJIAN_ID = @json($pdfPerjanjianIdValue);
    const PDF_TRIWULAN_AVAILABILITY = @json($pdfTriwulanAvailabilityValue);
    const PDF_PREVIEW_BASE_URL = @json($pdfPreviewBaseUrlValue);

    // Inject server chart data when backend provided
    @if(isset($chartData) && !empty($chartData['hasData']))
      window.serverChartData = @json($chartData);
    @endif
    // Sample data fallback (if backend doesn't provide chart data)
    const sampleKinerjaLabels = ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'];
    const sampleKinerjaRealisasiPersen = [0,0,0,0];
    const sampleAnggaranRealisasiPersen = [0,0,0,0];
    const chartLabels = ['Triwulan 1','Triwulan 2','Triwulan 3','Triwulan 4'];
    const sampleTargetKeuangan = [0,0,0,0];
    const sampleRealisasiKeuangan = [0,0,0,0];

    // Try to use server-provided variables when available
    const serverChart = window.serverChartData || null;
    if (!serverChart) {
      const kinerjaEl = document.getElementById('kinerjaChart');
      const keuanganEl = document.getElementById('keuanganChart');
      if (kinerjaEl) kinerjaEl.closest('.chart-card')?.remove();
      if (keuanganEl) keuanganEl.closest('.chart-card')?.remove();
    }
    const kinerjaLabels = serverChart?.kinerja_labels || sampleKinerjaLabels;
    const toNumber = (value) => {
      const num = Number(value);
      return Number.isFinite(num) ? num : 0;
    };
    const toNumberArray = (arr, fallback) => {
      if (!Array.isArray(arr) || arr.length === 0) return fallback.slice();
      return arr.map(toNumber);
    };

    const realisasiKinerjaPersen = toNumberArray(serverChart?.kinerja_realisasi_kinerja_persen, sampleKinerjaRealisasiPersen);
    const realisasiAnggaranPersen = toNumberArray(serverChart?.kinerja_realisasi_anggaran_persen, sampleAnggaranRealisasiPersen);
    const keuanganLabels = serverChart?.keuangan_labels || chartLabels;
    const targetKeuangan = toNumberArray(serverChart?.keuangan_targets, sampleTargetKeuangan);
    const realisasiKeuangan = toNumberArray(serverChart?.keuangan_realisasi, sampleRealisasiKeuangan);

    const formatIdr = (value) => new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(Number(value || 0));
    const formatPercent = (value) => `${Number(value || 0).toFixed(2)}%`;

    // Render Kinerja chart (line) - Realisasi Kinerja dibanding Realisasi Anggaran (persen)
    (function renderKinerja(){
      const el = document.getElementById('kinerjaChart');
      if (!el || !serverChart) return;
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
            datalabels: { clamp: true, clip: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return context.dataset.label + ': ' + formatPercent(context.parsed.y);
                }
              }
            }
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
      if (!el || !serverChart) return;
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
            datalabels: { clamp: true, clip: false },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return (context.dataset.label || '') + ': ' + formatIdr(context.parsed.y);
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero:true,
              ticks: {
                callback: function(v){
                  return new Intl.NumberFormat('id-ID', { style:'currency', currency:'IDR', maximumFractionDigits:0 }).format(v);
                }
              }
            }
          }
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

    function openPerjanjianStatusPanel(statusLabel, count) {
      if (Number(count || 0) <= 0) {
        const textEl = document.getElementById('modalPerjanjianStatusKosongText');
        if (textEl) {
          textEl.textContent = 'Tidak ada data perjanjian dengan status ' + statusLabel.toLowerCase() + '.';
        }
        openModal('modalPerjanjianStatusKosong');
        return;
      }

      const statusKeyMap = {
        'terkirim': 'terkirim',
        'disetujui': 'disetujui',
        'ditolak': 'ditolak',
        'menunggu': 'menunggu'
      };
      const mappedStatus = statusKeyMap[String(statusLabel || '').toLowerCase()] || 'terkirim';
      openPerjanjianStatusModal(mappedStatus);
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

      const modalPerjanjian = document.getElementById('modalPerjanjianPreview');
      if (modalPerjanjian) {
        modalPerjanjian.addEventListener('click', function (e) {
          if (e.target === modalPerjanjian) closeModal('modalPerjanjianPreview');
        });
      }

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          closeModal('modalPerjanjianStatusKosong');
          closeModal('modalLaporanPreview');
          closeModal('modalPerjanjianPreview');
        }
      });
    });

    // ---- Laporan preview modal ----
    const LAPORAN_ITEMS = @json($laporanItems ?? []);
    const PERJANJIAN_ITEMS = @json($perjanjianItems ?? []);
    const LAPORAN_KINERJA_URL = "{{ route('laporan.kinerja') }}";
    const OWN_LAPORAN_TRIWULANS = @json($ownLaporanTriwulans ?? []);
    const VALIDASI_ITEMS = @json($validasiLaporanItems ?? []);

    // ---- Validasi Drawer ----
    const VALIDASI_TW_LABELS = { 1: 'Triwulan I', 2: 'Triwulan II', 3: 'Triwulan III', 4: 'Triwulan IV' };
    const VALIDASI_TW_PERIODS = { 1: 'Januari – Maret', 2: 'April – Juni', 3: 'Juli – September', 4: 'Oktober – Desember' };

    function getValidationStateKeyDashboard(perjanjianId, laporanId, tw) {
      return 'validation_done:' + perjanjianId + ':' + (laporanId || 'none') + ':' + tw;
    }
    function getValidationSummaryKeyDashboard(perjanjianId, laporanId, tw) {
      return 'validation_summary:' + perjanjianId + ':' + (laporanId || 'none') + ':' + tw;
    }

    function findLaporanForTw(tw) {
      // Only use the scoped validation list so we never pull another user's laporan.
      return VALIDASI_ITEMS.find(function(i) { return Number(i.triwulan_aktif) === Number(tw); }) || null;
    }

    function isValidatedForTw(tw) {
      var laporan = findLaporanForTw(tw);
      if (!laporan) return false;
      try {
        var key = getValidationStateKeyDashboard(laporan.perjanjian_id, laporan.id, tw);
        return localStorage.getItem(key) === '1';
      } catch(e) { return false; }
    }

    function getSummaryForTw(tw) {
      var laporan = findLaporanForTw(tw);
      if (!laporan) return null;
      try {
        var key = getValidationSummaryKeyDashboard(laporan.perjanjian_id, laporan.id, tw);
        var raw = localStorage.getItem(key);
        return raw ? JSON.parse(raw) : null;
      } catch(e) { return null; }
    }

    function formatDateTimeWib(value) {
      if (!value) return '';
      var d = new Date(value);
      if (isNaN(d.getTime())) return '';
      return d.toLocaleString('id-ID', {
        timeZone: 'Asia/Jakarta',
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false,
      }) + ' WIB';
    }

    // Open triwulan selection modal for validation (shows validated state)
    function openValidasiSelectModal() {
      var modal = document.getElementById('modalValidasiSelect');
      var contentEl = document.getElementById('validasiSelectContent');
      if (!modal || !contentEl) return;
      contentEl.innerHTML = '<p style="color:#999;margin:0;">Memuat daftar triwulan...</p>';

      var twList = [1,2,3,4];
      var checks = twList.map(function(tw) {
        return new Promise(function(resolve) {
          var laporan = findLaporanForTw(tw);
          if (!laporan || !laporan.id) {
            resolve({ tw: tw, available: false });
            return;
          }
          fetch('/api/validasi-laporan/' + laporan.id + '/' + tw)
            .then(function(r){ return r.json(); })
            .then(function(data){
              resolve({ tw: tw, available: true, validated: !!data.validated });
            })
            .catch(function(){ resolve({ tw: tw, available: true, validated: false }); });
        });
      });

      Promise.all(checks).then(function(results) {
          var html = '<div style="display:flex;flex-direction:column;gap:12px;">';
        results.forEach(function(r) {
          html += '<div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid #eef2f7;">';
          html += '<div><strong style="font-size:14px;color:#111827;">Triwulan ' + r.tw + '</strong><div style="font-size:13px;color:#6b7280;margin-top:4px;">';
          if (!r.available) html += 'Tidak ada laporan untuk triwulan ini.';
          else if (r.validated) html += '<span style="color:#16a34a;">Tervalidasi</span>';
          else html += '<span style="color:#374151;">Belum divalidasi</span>';
          html += '</div></div>';

          if (!r.available) {
            html += '<button class="btn-logout" disabled style="background:#f3f4f6;color:#9ca3af;padding:10px 18px;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:not-allowed;">Tidak tersedia</button>';
          } else if (r.validated) {
            html += '<button class="btn-logout" disabled style="background:#dcfce7;color:#166534;padding:10px 18px;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:not-allowed;">Tervalidasi</button>';
          } else {
            html += '<button class="btn-logout" style="background:#0f766e;color:#fff;padding:10px 18px;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:pointer;" onclick="window.location.href=\'/laporan-kinerja/validasi-summary?tw=' + r.tw + '\';">Validasi</button>';
          }

          html += '</div>';
        });
        html += '</div>';
        contentEl.innerHTML = html;
        openModal('modalValidasiSelect');
      });
    }

    function updateValidasiCards() {
      for (var tw = 1; tw <= 4; tw++) {
        var card = document.getElementById('validasiCard' + tw);
        var numEl = document.getElementById('validasiNum' + tw);
        var btnEl = document.getElementById('validasiBtn' + tw);
        if (!card || !numEl) continue;
        var done = isValidatedForTw(tw);
        var summary = done ? getSummaryForTw(tw) : null;
        var displayVal = '—';
        var btnText = 'Lihat';
        if (done) {
          var capaian = (summary && summary.capaian_pct != null) ? Number(summary.capaian_pct) : 0;
          displayVal = capaian.toFixed(2) + '%';
          btnText = 'Tervalidasi';
        }
        numEl.textContent = displayVal;
        if (btnEl) btnEl.textContent = btnText;
      }
    }

    function openValidasiDrawer(tw) {
      var drawer = document.getElementById('validasiDrawer');
      var overlay = document.getElementById('validasiDrawerOverlay');
      if (!drawer) return;

      // Set title
      var titleEl = document.getElementById('validasiDrawerTitle');
      if (titleEl) titleEl.textContent = (VALIDASI_TW_LABELS[tw] || 'Triwulan ' + tw) + ' — ' + (VALIDASI_TW_PERIODS[tw] || '');

      var done = isValidatedForTw(tw);
      var summary = getSummaryForTw(tw);

      // Status badge
      var badge = document.getElementById('validasiDrawerStatusBadge');
      var statusText = document.getElementById('validasiDrawerStatusText');
      if (badge) {
        badge.className = 'validasi-drawer-status-badge ' + (done ? 'validated' : 'not-validated');
        badge.querySelector('i').className = done ? 'fas fa-circle-check' : 'fas fa-circle-xmark';
      }
      if (statusText) statusText.textContent = done ? 'Tervalidasi' : 'Belum Divalidasi';

      // Score section
      var scoreSection = document.getElementById('validasiDrawerScoreSection');
      var emptyNote = document.getElementById('validasiDrawerEmptyNote');
      if (done && summary) {
        if (scoreSection) scoreSection.style.display = '';
        if (emptyNote) emptyNote.style.display = 'none';
        var el = function(id) { return document.getElementById(id); };
        el('vdScore').textContent = summary.score != null ? summary.score : '-';
        el('vdIssues').textContent = summary.issues != null ? summary.issues : '0';
        el('vdWarnings').textContent = summary.warnings != null ? summary.warnings : '0';
        el('vdSuggestions').textContent = summary.suggestions != null ? summary.suggestions : '0';
        el('vdSummaryText').textContent = summary.summary || 'Tidak ada ringkasan.';
        var updatedAt = el('vdUpdatedAt');
        if (updatedAt) {
          if (summary.updatedAt) {
            var formatted = formatDateTimeWib(summary.updatedAt);
            updatedAt.textContent = formatted ? ('Divalidasi: ' + formatted) : '';
          } else {
            updatedAt.textContent = '';
          }
        }
      } else if (done) {
        if (scoreSection) scoreSection.style.display = 'none';
        if (emptyNote) { emptyNote.style.display = ''; emptyNote.textContent = 'Laporan triwulan ini sudah divalidasi, namun ringkasan tidak tersedia.'; }
      } else {
        if (scoreSection) scoreSection.style.display = 'none';
        if (emptyNote) { emptyNote.style.display = ''; emptyNote.textContent = 'Laporan triwulan ini belum divalidasi. Buka halaman Validasi Laporan untuk menjalankan validasi.'; }
      }

      // Open drawer
      if (overlay) overlay.classList.add('open');
      drawer.classList.add('open');
    }

    function closeValidasiDrawer() {
      var drawer = document.getElementById('validasiDrawer');
      var overlay = document.getElementById('validasiDrawerOverlay');
      if (drawer) drawer.classList.remove('open');
      if (overlay) overlay.classList.remove('open');
    }

    document.addEventListener('DOMContentLoaded', function() {
      updateValidasiCards();
      updateValidasiCardsAsync();
    });

    // === ASYNC VALIDATION FUNCTIONS ===
    var validationCache = {};
    var validationCacheTime = {};

    function getCachedValidation(tw) {
      var now = Date.now();
      if (validationCache[tw] && validationCacheTime[tw] && (now - validationCacheTime[tw]) < 5000) {
        return validationCache[tw];
      }
      return null;
    }

    function setCachedValidation(tw, data) {
      validationCache[tw] = data;
      validationCacheTime[tw] = Date.now();
    }

    function fetchValidationFromDB(tw) {
      var laporan = findLaporanForTw(tw);
      if (!laporan || !laporan.id) {
        return Promise.resolve(null);
      }

      var cached = getCachedValidation(tw);
      if (cached) {
        return Promise.resolve(cached);
      }

      return fetch('/api/validasi-laporan/' + laporan.id + '/' + tw)
        .then(r => r.json())
        .then(data => {
          if (data.validated) {
            setCachedValidation(tw, {
              score: data.score,
              issues: data.issues,
              warnings: data.warnings,
              suggestions: data.suggestions,
              updatedAt: data.validated_at,
              summary: 'Validasi Triwulan ' + tw + ' selesai',
              capaian_pct: data.capaian_pct
            });
            try {
              var laporan = findLaporanForTw(tw);
              if (laporan) {
                var key = getValidationStateKeyDashboard(laporan.perjanjian_id, laporan.id, tw);
                var summaryKey = getValidationSummaryKeyDashboard(laporan.perjanjian_id, laporan.id, tw);
                localStorage.setItem(key, '1');
                localStorage.setItem(summaryKey, JSON.stringify({
                  score: data.score,
                  issues: data.issues,
                  warnings: data.warnings,
                  suggestions: data.suggestions,
                  summary: 'Validasi Triwulan ' + tw + ' selesai',
                  updatedAt: data.validated_at,
                  capaian_pct: data.capaian_pct
                }));
              }
            } catch(e) {}
            return validationCache[tw];
          }

          // Jika backend menyatakan belum tervalidasi, bersihkan cache lokal agar status lama tidak muncul.
          try {
            var currentLaporan = findLaporanForTw(tw);
            if (currentLaporan) {
              var doneKey = getValidationStateKeyDashboard(currentLaporan.perjanjian_id, currentLaporan.id, tw);
              var sumKey = getValidationSummaryKeyDashboard(currentLaporan.perjanjian_id, currentLaporan.id, tw);
              localStorage.removeItem(doneKey);
              localStorage.removeItem(sumKey);
            }
          } catch (e) {}
          delete validationCache[tw];
          delete validationCacheTime[tw];
          return null;
        })
        .catch(e => {
          console.warn('Fetch validation error:', e);
          return null;
        });
    }

    function updateValidasiCardsAsync() {
      for (var tw = 1; tw <= 4; tw++) {
        (function(twNum) {
          fetchValidationFromDB(twNum).then(summary => {
            var numEl = document.getElementById('validasiNum' + twNum);
            var btnEl = document.getElementById('validasiBtn' + twNum);
            if (!numEl) return;
            var displayVal = '—';
            var btnText = 'Lihat';
            if (summary) {
              var capaian = (summary.capaian_pct != null) ? Number(summary.capaian_pct) : 0;
              displayVal = capaian.toFixed(2) + '%';
              btnText = 'Tervalidasi';
            }
            numEl.textContent = displayVal;
            if (btnEl) btnEl.textContent = btnText;
          });
        })(tw);
      }
    }

    function openValidasiDrawerAsync(tw) {
      var drawer = document.getElementById('validasiDrawer');
      var overlay = document.getElementById('validasiDrawerOverlay');
      if (!drawer) return;

      // Store current tw for PDF preview button
      window.currentTwForDrawer = tw;

      fetchValidationFromDB(tw).then(data => {
        var titleEl = document.getElementById('validasiDrawerTitle');
        if (titleEl) titleEl.textContent = (VALIDASI_TW_LABELS[tw] || 'Triwulan ' + tw) + ' — ' + (VALIDASI_TW_PERIODS[tw] || '');

        var done = data !== null;
        var summary = data;

        // Show/hide PDF preview button
        var pdfBtn = document.getElementById('pdfPreviewBtn');
        if (pdfBtn) {
          pdfBtn.style.display = done ? '' : 'none';
        }

        var badge = document.getElementById('validasiDrawerStatusBadge');
        var statusText = document.getElementById('validasiDrawerStatusText');
        if (badge) {
          badge.className = 'validasi-drawer-status-badge ' + (done ? 'validated' : 'not-validated');
          badge.querySelector('i').className = done ? 'fas fa-circle-check' : 'fas fa-circle-xmark';
        }
        if (statusText) statusText.textContent = done ? 'Tervalidasi' : 'Belum Divalidasi';

        var scoreSection = document.getElementById('validasiDrawerScoreSection');
        var emptyNote = document.getElementById('validasiDrawerEmptyNote');
        if (done && summary) {
          if (scoreSection) scoreSection.style.display = '';
          if (emptyNote) emptyNote.style.display = 'none';
          var el = function(id) { return document.getElementById(id); };
          el('vdScore').textContent = summary.score != null ? summary.score : '-';
          el('vdIssues').textContent = summary.issues != null ? summary.issues : '0';
          el('vdWarnings').textContent = summary.warnings != null ? summary.warnings : '0';
          el('vdSuggestions').textContent = summary.suggestions != null ? summary.suggestions : '0';
          el('vdSummaryText').textContent = summary.summary || 'Tidak ada ringkasan.';
          var updatedAt = el('vdUpdatedAt');
          if (updatedAt) {
            if (summary.updatedAt) {
              var formatted = formatDateTimeWib(summary.updatedAt);
              updatedAt.textContent = formatted ? ('Divalidasi: ' + formatted) : '';
            } else {
              updatedAt.textContent = '';
            }
          }
        } else if (done) {
          if (scoreSection) scoreSection.style.display = 'none';
          if (emptyNote) { emptyNote.style.display = ''; emptyNote.textContent = 'Laporan triwulan ini sudah divalidasi, namun ringkasan tidak tersedia.'; }
        } else {
          if (scoreSection) scoreSection.style.display = 'none';
          if (emptyNote) { emptyNote.style.display = ''; emptyNote.textContent = 'Laporan triwulan ini belum divalidasi. Buka halaman Validasi Laporan untuk menjalankan validasi.'; }
        }

        if (overlay) overlay.classList.add('open');
        drawer.classList.add('open');
      });
    }

    // Override openValidasiDrawer to use async version
    window.openValidasiDrawer = function(tw) {
      openValidasiDrawerAsync(tw);
    };

    // ===== PDF Preview Modal Functions =====
    function openPdfPreviewModal(tw) {
      var modal = document.getElementById('pdfPreviewModal');
      var titleEl = document.getElementById('pdfModalTitle');
      var subtitleEl = document.getElementById('pdfModalSubtitle');
      var contentEl = document.getElementById('pdfPreviewContent');
      
      if (!modal) return;
      
      // Set title
      if (titleEl) titleEl.textContent = 'Ringkasan Validasi Laporan';
      
      // Fetch validation data for this triwulan
      var laporan = findLaporanForTw(tw);
      if (!laporan || !laporan.id) {
        contentEl.innerHTML = '<p style="text-align:center;color:#999;">Laporan tidak ditemukan.</p>';
        modal.style.display = 'flex';
        return;
      }
      
      // Fetch from API
      fetch('/api/validasi-laporan/' + laporan.id + '/' + tw)
        .then(r => r.json())
        .then(data => {
          if (data.validated) {
            // Build validation summary HTML
            var html = '<div style="text-align:left;">' +
              '<div style="margin-bottom:18px;text-align:center;">' +
                '<div style="display:inline-flex;align-items:center;gap:8px;background:#e8f5e9;color:#2e7d32;padding:8px 16px;border-radius:8px;font-weight:600;">' +
                  '<i class="fas fa-circle-check"></i> Tervalidasi' +
                '</div>' +
              '</div>' +
              '<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:18px;">' +
                '<div style="background:#f8fcfb;border:1px solid #e4eeea;border-radius:8px;padding:12px;text-align:center;">' +
                  '<div style="font-size:24px;font-weight:800;color:#00B5A0;">' + (data.score || '0') + '</div>' +
                  '<div style="font-size:12px;font-weight:600;color:#667085;margin-top:4px;">Skor</div>' +
                '</div>' +
                '<div style="background:#f8fcfb;border:1px solid #e4eeea;border-radius:8px;padding:12px;text-align:center;">' +
                  '<div style="font-size:24px;font-weight:800;color:#00B5A0;">' + (data.issues || '0') + '</div>' +
                  '<div style="font-size:12px;font-weight:600;color:#667085;margin-top:4px;">Issues</div>' +
                '</div>' +
                '<div style="background:#f8fcfb;border:1px solid #e4eeea;border-radius:8px;padding:12px;text-align:center;">' +
                  '<div style="font-size:24px;font-weight:800;color:#00B5A0;">' + (data.warnings || '0') + '</div>' +
                  '<div style="font-size:12px;font-weight:600;color:#667085;margin-top:4px;">Peringatan</div>' +
                '</div>' +
                '<div style="background:#f8fcfb;border:1px solid #e4eeea;border-radius:8px;padding:12px;text-align:center;">' +
                  '<div style="font-size:24px;font-weight:800;color:#00B5A0;">' + (data.suggestions || '0') + '</div>' +
                  '<div style="font-size:12px;font-weight:600;color:#667085;margin-top:4px;">Saran</div>' +
                '</div>' +
              '</div>' +
              '<div style="background:#f9f9f9;border-left:4px solid #00B5A0;padding:12px 14px;border-radius:4px;margin-bottom:18px;">' +
                '<p style="margin:0;font-size:13px;color:#333;">Laporan triwulan telah melewati proses validasi otomatis dengan hasil yang memuaskan.</p>' +
              '</div>';
            
            if (data.validated_at) {
              var formatted = formatDateTimeWib(data.validated_at);
              if (formatted) {
                html += '<p style="font-size:12px;color:#999;margin:0;text-align:right;">Divalidasi: ' + formatted + '</p>';
              }
            }
            
            html += '</div>';
            contentEl.innerHTML = html;
          } else {
            contentEl.innerHTML = '<p style="text-align:center;color:#999;">Laporan belum divalidasi.</p>';
          }
          modal.style.display = 'flex';
        })
        .catch(err => {
          console.error('Error fetching validation:', err);
          contentEl.innerHTML = '<p style="text-align:center;color:#999;">Gagal memuat data validasi.</p>';
          modal.style.display = 'flex';
        });
    }

    function closePdfPreviewModal() {
      var modal = document.getElementById('pdfPreviewModal');
      if (modal) modal.style.display = 'none';
    }


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

    function openPerjanjianStatusModal(status) {
      const statusMap = {
        terkirim : { label: 'Perjanjian Kinerja — Terkirim',  bg: '#e3f9f1', color: '#009970', text: 'Terkirim'  },
        disetujui: { label: 'Perjanjian Kinerja — Disetujui', bg: '#fff3e0', color: '#FFA500', text: 'Disetujui' },
        ditolak  : { label: 'Perjanjian Kinerja — Ditolak',   bg: '#fde8e8', color: '#DC3545', text: 'Ditolak'   },
        menunggu : { label: 'Perjanjian Kinerja — Menunggu',  bg: '#e3f2fd', color: '#2196F3', text: 'Menunggu' },
      };

      const meta = statusMap[status] || statusMap['terkirim'];
      const items = PERJANJIAN_ITEMS.filter(function(i){ return i.status === status; });

      if (items.length === 0) {
        const textEl = document.getElementById('modalPerjanjianStatusKosongText');
        if (textEl) textEl.textContent = 'Tidak ada dokumen perjanjian dengan status ' + meta.text.toLowerCase() + '.';
        openModal('modalPerjanjianStatusKosong');
        return;
      }

      const titleEl = document.getElementById('perjanjianPreviewTitle');
      const container = document.getElementById('perjanjianPreviewList');
      if (!titleEl || !container) return;

      titleEl.textContent = meta.label;
      container.innerHTML = '';

      items.forEach(function(item) {
        const sc = statusMap[item.status] || statusMap['terkirim'];
        const nomor = item.nomor_perjanjian || ('ID #' + item.id);
        const tanggal = item.agreement_date || item.created_at || '-';

        const div = document.createElement('div');
        div.className = 'laporan-preview-item';
        div.innerHTML =
          '<div class="laporan-preview-info">'
            + '<div class="laporan-preview-name">' + escapeHtml(nomor) + '</div>'
            + '<div class="laporan-preview-meta">' + escapeHtml((item.pihak1_name || '-') + ' • ' + tanggal) + '</div>'
          + '</div>'
          + '<div class="laporan-preview-right">'
            + '<span class="laporan-preview-badge" style="background:' + sc.bg + ';color:' + sc.color + ';">' + sc.text + '</span>'
            + '<a href="' + escapeHtml(item.document_url || '#') + '" target="_blank" rel="noopener noreferrer" class="laporan-preview-action-btn">Lihat Dokumen</a>'
          + '</div>';
        container.appendChild(div);
      });

      openModal('modalPerjanjianPreview');
    }
  </script>

  <!-- Modal: Laporan Triwulan Sudah Ada (dashboard check) -->
  <div id="dashAlreadyLaporanModal" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
    <div class="logout-box" style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:300px;max-width:420px;text-align:center;">
      <h3 style="margin-bottom:12px;"><i class="fas fa-info-circle" style="color:#FFA500;margin-right:8px;"></i> Laporan Sudah Ada</h3>
      <p style="margin:0 0 6px 0; color:#444;">Laporan kinerja <strong id="dashAlreadyLaporanTwLabel">Triwulan ini</strong> sudah pernah diisi.</p>
      <p style="margin:0 0 20px 0; color:#666; font-size:13px;">Gunakan tombol <strong>Edit</strong> pada tabel laporan untuk mengubah data yang sudah ada.</p>
      <div class="logout-buttons" style="display:flex;gap:16px;justify-content:center;">
        <button type="button" onclick="closeModal('dashAlreadyLaporanModal')" style="background:#eee;color:#333;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">Tutup</button>
      </div>
    </div>
  </div>

  <script>
    function handleTambahLaporan(twAktif) {
      var twNames = { 1: 'Triwulan I', 2: 'Triwulan II', 3: 'Triwulan III', 4: 'Triwulan IV' };
      var alreadyExists = OWN_LAPORAN_TRIWULANS.some(function(tw) {
        return Number(tw) === Number(twAktif);
      });
      if (alreadyExists) {
        var lbl = document.getElementById('dashAlreadyLaporanTwLabel');
        if (lbl) lbl.textContent = twNames[twAktif] || ('Triwulan ' + twAktif);
        openModal('dashAlreadyLaporanModal');
      } else {
        window.location.href = LAPORAN_KINERJA_URL + '?section=laporan&tambah=1';
      }
    }
  </script>
</body>
</html>
