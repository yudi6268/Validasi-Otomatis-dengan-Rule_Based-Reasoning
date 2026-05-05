<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>Laporan Kinerja - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #E3F8F6, #D6F5EF);
      min-height: 100vh;
      color: #1B2A41;
    }
    header {
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 40px;
      box-shadow: 0 4px 12px rgba(0,153,112,0.15);
    }
    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .logo-container img { height: 60px; }
    nav { display: flex; gap: 20px; }
    nav a { text-decoration: none; color: #1B2A41; font-weight: 600; font-size: 18px; }
    nav a:hover { color: #00B5A0; }
    main { 
      padding: 40px 20px;
      max-width: 1400px;
      margin: 0 auto;
    }
    .page-header {
      margin-bottom: 30px;
      text-align: center;
    }
    .page-title { 
      font-size: 32px; 
      font-weight: 800; 
      margin-bottom: 8px; 
    }
    .page-subtitle { 
      color: #5F6F81; 
      font-size: 14px;
    }
    
    /* Alert messages */
    .alert {
      padding: 16px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .alert-info {
      background: #E0F9F7;
      color: #00796B;
      border: 1px solid #80DEEA;
    }
    
    /* Header Info Card */
    .info-card {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }
    .info-item {
      padding: 12px;
      background: #F5F5F5;
      border-radius: 8px;
    }
    .info-label {
      font-size: 12px;
      color: #666;
      font-weight: 600;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .info-value {
      font-size: 15px;
      color: #1B2A41;
      font-weight: 600;
    }
    
    /* Triwulan Tabs */
    .triwulan-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }
    .triwulan-tab-btn {
      padding: 10px 20px;
      border: 2px solid #ddd;
      border-radius: 8px;
      background: #fff;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 600;
      font-size: 14px;
      color: #1B2A41;
    }
    .triwulan-tab-btn:hover {
      border-color: #00B5A0;
      color: #00B5A0;
      background: #F9F9F9;
    }

    .triwulan-tab-btn.disabled,
    .triwulan-tab-btn:disabled {
      cursor: not-allowed;
      opacity: 0.5;
      border-color: #ccc;
      color: #999;
      background: #f7f7f7;
    }
    
    /* Laporan Table */
    .laporan-section {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      overflow-x: auto;
    }
    .section-title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #1B2A41;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    
    thead th {
      background: #00B5A0;
      color: #fff;
      padding: 12px 8px;
      text-align: left;
      font-weight: 600;
      border: 1px solid #ddd;
    }
    
    tbody td {
      padding: 12px 8px;
      border: 1px solid #ddd;
      vertical-align: top;
    }
    
    tbody tr:hover {
      background: #F9F9F9;
    }
    
    .no-col { width: 40px; }
    .program-col { min-width: 250px; }
    .target-col { width: 80px; }
    .realisasi-col { width: 120px; }
    .action-col { width: 80px; text-align: center; }
    
    .realisasi-cell {
      position: relative;
    }
    
    .realisasi-text {
      display: block;
      word-wrap: break-word;
      word-break: break-word;
      white-space: pre-wrap;
      max-height: 100px;
      overflow-y: auto;
      font-family: 'Courier New', monospace;
      font-size: 12px;
    }
    
    .edit-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      background: #00B5A0;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s;
    }
    
    .edit-btn:hover {
      background: #008F7E;
    }
    
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #999;
    }
    
    .empty-state i {
      font-size: 56px;
      color: #ddd;
      margin-bottom: 12px;
    }
    
    /* Modal Styles */
    .modal-header {
      background: #00B5A0;
      color: #fff;
      border: none;
    }
    
    .modal-header .btn-close {
      filter: brightness(0) invert(1);
    }
    
    .form-label {
      font-weight: 600;
      color: #1B2A41;
      margin-bottom: 8px;
    }
    
    .form-control {
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 10px 12px;
    }
    
    .form-control:focus {
      border-color: #00B5A0;
      box-shadow: 0 0 0 0.2rem rgba(0, 181, 160, 0.25);
    }
    
    .btn-save {
      background: #00B5A0;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .btn-save:hover {
      background: #008F7E;
      color: #fff;
    }
    
    .btn-secondary-alt {
      background: #E0E0E0;
      color: #1B2A41;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .btn-secondary-alt:hover {
      background: #D0D0D0;
    }
    
    /* Footer */
    footer { 
      background: #fff; 
      text-align: center; 
      font-size: 13px; 
      font-weight: 700; 
      padding: 15px 0; 
      border-top: 1px solid #ddd;
      margin-top: 40px;
    }
    
    /* Info Badge */
    .badge-triwulan {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      margin-left: 8px;
    }
    
    .badge-active {
      background: #C8E6C9;
      color: #2E7D32;
    }
    
    /* Smart Validation Styles */
    .validation-panel {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      display: none;
    }
    
    .validation-panel.show {
      display: block;
    }
    
    .validation-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 2px solid #eee;
    }
    
    .validation-title {
      font-size: 18px;
      font-weight: 700;
      color: #1B2A41;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .validation-score {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .score-circle {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      font-weight: 700;
      color: #fff;
    }
    
    .score-excellent { background: linear-gradient(135deg, #4CAF50, #2E7D32); }
    .score-good { background: linear-gradient(135deg, #8BC34A, #558B2F); }
    .score-warning { background: linear-gradient(135deg, #FF9800, #E65100); }
    .score-danger { background: linear-gradient(135deg, #f44336, #c62828); }
    
    .score-label {
      font-size: 12px;
      color: #666;
      font-weight: 600;
    }
    
    .validation-summary {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }
    
    .summary-excellent { background: #E8F5E9; color: #2E7D32; border: 1px solid #A5D6A7; }
    .summary-good { background: #F1F8E9; color: #558B2F; border: 1px solid #C5E1A5; }
    .summary-warning { background: #FFF3E0; color: #E65100; border: 1px solid #FFCC80; }
    .summary-danger { background: #FFEBEE; color: #C62828; border: 1px solid #EF9A9A; }
    
    .validation-section {
      margin-bottom: 20px;
    }
    
    .validation-section-title {
      font-size: 14px;
      font-weight: 700;
      color: #1B2A41;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .validation-item {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 8px;
      border-left: 4px solid;
    }
    
    .validation-item.issue {
      background: #FFEBEE;
      border-color: #f44336;
    }
    
    .validation-item.warning {
      background: #FFF3E0;
      border-color: #FF9800;
    }
    
    .validation-item.suggestion {
      background: #E3F2FD;
      border-color: #2196F3;
    }
    
    .validation-item.success {
      background: #E8F5E9;
      border-color: #4CAF50;
    }
    
    .validation-item-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 6px;
    }
    
    .validation-item-title {
      font-weight: 600;
      font-size: 13px;
      color: #1B2A41;
    }
    
    .validation-item-severity {
      font-size: 10px;
      padding: 2px 8px;
      border-radius: 4px;
      font-weight: 600;
      text-transform: uppercase;
    }
    
    .severity-high { background: #f44336; color: #fff; }
    .severity-medium { background: #FF9800; color: #fff; }
    .severity-low { background: #2196F3; color: #fff; }
    
    .validation-item-message {
      font-size: 12px;
      color: #666;
      margin-bottom: 8px;
    }
    
    .validation-item-fix {
      font-size: 11px;
      color: #00B5A0;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .validation-actions {
      display: flex;
      gap: 12px;
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid #eee;
    }
    
    .btn-validate {
      background: linear-gradient(135deg, #00B5A0, #008F7E);
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .btn-validate:hover {
      background: linear-gradient(135deg, #008F7E, #00695C);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 181, 160, 0.3);
    }
    
    .btn-validate:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }
    
    .validation-loading {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      color: #666;
    }
    
    .spinner {
      width: 24px;
      height: 24px;
      border: 3px solid #ddd;
      border-top-color: #00B5A0;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-right: 12px;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo Pemda">
      <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD">
    </div>
    <nav>
      <a href="<?php echo e(route('home')); ?>">Beranda</a>
      <a href="<?php echo e(route('panduan')); ?>">Panduan</a>
      <a href="<?php echo e(route('kontak')); ?>">Kontak</a>
      <a href="<?php echo e(route('tentang')); ?>">Tentang</a>
    </nav>
  </header>

  <main>
    <div class="page-header">
      <div class="page-title">Laporan Kinerja</div>
      <div class="page-subtitle">Form pengisian realisasi laporan kinerja per triwulan</div>
    </div>

    <?php if(!$perjanjian || $message): ?>
      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span><?php echo e($message ?? 'Tidak ada perjanjian kinerja yang disetujui'); ?></span>
      </div>
      <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo e(route('perjanjian.index')); ?>" class="btn btn-primary">
          <i class="fas fa-file-signature"></i>
          Buat Perjanjian Kinerja
        </a>
      </div>
    <?php else: ?>
      <!-- Info Card -->
      <div class="info-card">
        <h5 style="margin-bottom: 16px; color: #00B5A0;">
          <i class="fas fa-user-circle"></i>
          Data Pegawai & Perjanjian
        </h5>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Nama Pegawai</div>
            <div class="info-value"><?php echo e($perjanjian->pihak1_name ?? '-'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">NIP</div>
            <div class="info-value"><?php echo e($perjanjian->pihak1_nip ?? '-'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Jabatan</div>
            <div class="info-value"><?php echo e($perjanjian->pihak1_jabatan ?? '-'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Tahun</div>
            <div class="info-value"><?php echo e($perjanjian->tahun ?? '-'); ?></div>
          </div>
          <div class="info-item">
            <div class="info-label">Atasan Langsung</div>
            <div class="info-value"><?php echo e($perjanjian->pihak2_name ?? '-'); ?></div>
          </div>
        </div>
      </div>

      <!-- Triwulan Selection -->
      <div class="triwulan-tabs">
        <button type="button" class="triwulan-tab-btn" data-triwulan="1" onclick="openRealisasiModal(1)">
          <i class="fas fa-calendar"></i> Triwulan 1 (Jan-Mar)
          <?php if($triwulanAktif == 1): ?>
            <span class="badge-triwulan badge-active">Aktif</span>
          <?php endif; ?>
        </button>
        <button type="button" class="triwulan-tab-btn" data-triwulan="2" onclick="openRealisasiModal(2)">
          <i class="fas fa-calendar"></i> Triwulan 2 (Apr-Jun)
          <?php if($triwulanAktif == 2): ?>
            <span class="badge-triwulan badge-active">Aktif</span>
          <?php endif; ?>
        </button>
        <button type="button" class="triwulan-tab-btn" data-triwulan="3" onclick="openRealisasiModal(3)">
          <i class="fas fa-calendar"></i> Triwulan 3 (Jul-Sep)
          <?php if($triwulanAktif == 3): ?>
            <span class="badge-triwulan badge-active">Aktif</span>
          <?php endif; ?>
        </button>
        <button type="button" class="triwulan-tab-btn" data-triwulan="4" onclick="openRealisasiModal(4)">
          <i class="fas fa-calendar"></i> Triwulan 4 (Okt-Des)
          <?php if($triwulanAktif == 4): ?>
            <span class="badge-triwulan badge-active">Aktif</span>
          <?php endif; ?>
        </button>
        <button type="button" class="btn-validate" onclick="runSmartValidation()" id="btnSmartValidate">
          <i class="fas fa-magic"></i> Smart Validation
        </button>
      </div>

      <!-- Smart Validation Panel -->
      <div class="validation-panel" id="validationPanel">
        <div class="validation-header">
          <div class="validation-title">
            <i class="fas fa-shield-alt"></i>
            Hasil Smart Validation
          </div>
          <div class="validation-score" id="validationScore" style="display: none;">
            <div>
              <div class="score-label">Skor Kualitas</div>
              <div id="scoreValue" style="font-size: 24px; font-weight: 700; color: #1B2A41;">-</div>
            </div>
            <div class="score-circle" id="scoreCircle">-</div>
          </div>
        </div>
        
        <div id="validationContent">
          <div class="validation-loading" id="validationLoading" style="display: none;">
            <div class="spinner"></div>
            <span>Memvalidasi laporan...</span>
          </div>
          
          <div id="validationResults"></div>
        </div>
        
        <div class="validation-actions">
          <button type="button" class="btn-validate" onclick="runSmartValidation()">
            <i class="fas fa-sync"></i> Validasi Ulang
          </button>
          <button type="button" class="btn-secondary-alt" onclick="closeValidationPanel()">
            <i class="fas fa-times"></i> Tutup
          </button>
        </div>
      </div>

      <!-- Laporan Table - Removed, using modal instead -->

        <div style="text-align: center; padding: 40px 20px; color: #999;">
          <i class="fas fa-info-circle" style="font-size: 32px; color: #ddd; margin-bottom: 12px;"></i>
          <p>Pilih triwulan di atas untuk mengisi realisasi laporan kinerja</p>
        </div>
    <?php endif; ?>
  </main>

  <!-- Modal Edit Realisasi -->
  <div class="modal fade" id="realisasiModal" tabindex="-1" aria-labelledby="realisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="realisasiModalLabel">
            <i class="fas fa-edit"></i>
            Form Pengisian Realisasi Laporan Kinerja
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Info Perjanjian -->
          <div style="background: #F5F5F5; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <h6 style="margin: 0 0 12px 0; font-weight: 600; color: #1B2A41;">Informasi Perjanjian Kinerja</h6>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
              <div>
                <div style="color: #666; margin-bottom: 2px;">Nama Pegawai</div>
                <div style="font-weight: 600; color: #1B2A41;" id="modalPegawai">-</div>
              </div>
              <div>
                <div style="color: #666; margin-bottom: 2px;">Jabatan</div>
                <div style="font-weight: 600; color: #1B2A41;" id="modalJabatan">-</div>
              </div>
              <div>
                <div style="color: #666; margin-bottom: 2px;">Atasan Langsung</div>
                <div style="font-weight: 600; color: #1B2A41;" id="modalAtasan">-</div>
              </div>
              <div>
                <div style="color: #666; margin-bottom: 2px;">Triwulan</div>
                <div style="font-weight: 600; color: #00B5A0;" id="modalTriwulan">-</div>
              </div>
            </div>
          </div>

          <?php
            $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
            $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
            $activeTriwulan = $triwulanAktif ?? 1;
            $activeTwKey = 'tw' . $activeTriwulan;
          ?>
          <div style="margin-bottom: 20px;">
            <h6 style="font-weight: 700; color: #1B2A41; margin-bottom: 12px;">Rencana Aksi dari Perjanjian Kinerja</h6>
            <?php if(!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0): ?>
              <div style="margin-bottom: 16px;">
                <div style="font-weight: 700; color: #000; margin-bottom: 8px;">Tabel Sasaran Kinerja</div>
                <div style="overflow-x: auto;">
                  <table class="table-black-header" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                      <tr>
                        <th style="padding: 10px; border: 1px solid #ddd;">No</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Sasaran</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Indikator Kinerja</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Target TW <?php echo e($activeTriwulan); ?></th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Realisasi</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Persentase</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $tabelB['sasaran']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sasaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                          $targetValue = $tabelB[$activeTwKey][$index] ?? '';
                          $targetValueNormalized = is_string($targetValue) ? str_replace(',', '.', $targetValue) : $targetValue;
                        ?>
                        <tr>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($index + 1); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($sasaran ?? '-'); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($tabelB['indikator'][$index] ?? '-'); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($targetValue ?? '-'); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">
                            <input type="number" step="any" class="form-control row-realisasi-input" data-row="kinerja-<?php echo e($index); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" />
                          </td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; text-align: center;" id="percentage-kinerja-<?php echo e($index); ?>">-</td>
                        </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endif; ?>

            <?php if(!empty($tabelC['programs']) && count($tabelC['programs']) > 0): ?>
              <div style="margin-bottom: 16px;">
                <div style="font-weight: 700; color: #000; margin-bottom: 8px;">Tabel Anggaran</div>
                <div style="overflow-x: auto;">
                  <table class="table-black-header" style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <thead>
                      <tr>
                        <th style="padding: 10px; border: 1px solid #ddd;">No</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Program / Kegiatan / Sub Kegiatan</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Target TW <?php echo e($activeTriwulan); ?></th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Realisasi</th>
                        <th style="padding: 10px; border: 1px solid #ddd;">Persentase</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $tabelC['programs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                          $programNo = $program['no'] ?? '';
                          $targetValue = $program[$activeTwKey] ?? '';
                          $targetValueNormalized = is_string($targetValue) ? str_replace(',', '.', $targetValue) : $targetValue;
                          $hasKegiatan = !empty($program['kegiatan'] ?? []);
                        ?>
                        <tr style="background: #f7f9fa;">
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($programNo); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; font-weight: 700;"><?php echo e($program['name'] ?? '-'); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e(is_numeric($targetValue) ? number_format($targetValue, 0, ',', '.') : ($targetValue ?? '-')); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">
                            <?php if($hasKegiatan): ?>
                              <input type="text" readonly disabled class="form-control computed-realisasi-value" data-row="anggaran-<?php echo e($programNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" value="-" />
                            <?php else: ?>
                              <input type="number" step="any" class="form-control row-realisasi-input" data-row="anggaran-<?php echo e($programNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" />
                            <?php endif; ?>
                          </td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; text-align: center;" id="percentage-anggaran-<?php echo e($programNo); ?>">-</td>
                        </tr>
                        <?php $__currentLoopData = $program['kegiatan'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <?php
                            $kegiatanNo = $kegiatan['no'] ?? '';
                            $targetValue = $kegiatan[$activeTwKey] ?? '';
                            $targetValueNormalized = is_string($targetValue) ? str_replace(',', '.', $targetValue) : $targetValue;
                            $hasSubKegiatan = !empty($kegiatan['subKegiatan'] ?? []);
                          ?>
                          <tr>
                            <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($kegiatanNo); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; padding-left: 16px;"><?php echo e($kegiatan['name'] ?? '-'); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e(is_numeric($targetValue) ? number_format($targetValue, 0, ',', '.') : ($targetValue ?? '-')); ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">
                              <?php if($hasSubKegiatan): ?>
                                <input type="text" readonly disabled class="form-control computed-realisasi-value" data-row="anggaran-<?php echo e($kegiatanNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" value="-" />
                              <?php else: ?>
                                <input type="number" step="any" class="form-control row-realisasi-input" data-row="anggaran-<?php echo e($kegiatanNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" />
                              <?php endif; ?>
                            </td>
                            <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; text-align: center;" id="percentage-anggaran-<?php echo e($kegiatanNo); ?>">-</td>
                          </tr>
                          <?php $__currentLoopData = $kegiatan['subKegiatan'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                              $subNo = $sub['no'] ?? '';
                              $targetValue = $sub[$activeTwKey] ?? '';
                              $targetValueNormalized = is_string($targetValue) ? str_replace(',', '.', $targetValue) : $targetValue;
                            ?>
                            <tr>
                              <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($subNo); ?></td>
                              <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; padding-left: 32px;"><?php echo e($sub['name'] ?? '-'); ?></td>
                              <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e(is_numeric($targetValue) ? number_format($targetValue, 0, ',', '.') : ($targetValue ?? '-')); ?></td>
                              <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><input type="number" step="any" class="form-control row-realisasi-input" data-row="anggaran-<?php echo e($subNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" /></td>
                              <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top; text-align: center;" id="percentage-anggaran-<?php echo e($subNo); ?>">-</td>
                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                  </table>
                </div>
              </div>
            <?php endif; ?>

            <?php if(empty($tabelB['sasaran']) && empty($tabelC['programs'])): ?>
              <div style="padding: 16px; border: 1px solid #ddd; border-radius: 8px; background: #fff; color: #666;">
                Tidak ada data rencana aksi yang tersimpan pada perjanjian kinerja ini.
              </div>
            <?php endif; ?>
          </div>

          <form id="realisasiForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="perjanjianId" name="perjanjian_id" value="<?php echo e($perjanjian ? $perjanjian->id : ''); ?>">
            <input type="hidden" id="triwulanEdit" name="triwulan" value="1">

            <div class="mb-3">
              <label class="form-label">
                <i class="fas fa-file-alt"></i>
                C. Evaluasi dan Analisis Kinerja
                <span style="color: #e74c3c;">*</span>
              </label>
              <textarea class="form-control" id="realisasiInput" name="realisasi" rows="8" 
                        placeholder="Berdasarkan capaian kinerja triwulan aktif, jelaskan kesimpulan dari pencapaian semua indikator kinerja, realisasi anggaran, hambatan, dan rencana perbaikan ke depan." required></textarea>
              <small class="text-muted">
                <i class="fas fa-lightbulb"></i>
                Minimal 50 karakter; uraikan evaluasi dan analisis capaian kinerja serta rekomendasi tindak lanjut.
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn-secondary-alt" data-bs-dismiss="modal">
                <i class="fas fa-times"></i>
                Batal
              </button>
              <button type="button" class="btn-save" onclick="proceedToRencana()">
                <i class="fas fa-arrow-right"></i>
                Selanjutnya
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Rencana Tindak Lanjut -->
  <div class="modal fade" id="rencanaTindakLanjutModal" tabindex="-1" aria-labelledby="rencanaTindakLanjutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rencanaTindakLanjutModalLabel">
            <i class="fas fa-list-ul"></i>
            D. Rencana Tindak Lanjut
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="rencanaTindakLanjutForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="rencanaPerjanjianId" name="perjanjian_id">
            <input type="hidden" id="rencanaTrjulanEdit" name="triwulan">
            
            <div class="mb-3">
              <label class="form-label">
                <i class="fas fa-list-ul"></i>
                D. Rencana Tindak Lanjut
                <span style="color: #e74c3c;">*</span>
              </label>
              <textarea class="form-control" id="rencanaTindakLanjutInput" name="rencana_tindak_lanjut" rows="8"
                        placeholder="Rencana Tindak Lanjut dari hasil capaian kinerja adalah sebagai berikut: 1. Mempertahankan capaian realisasi kinerja. 2. Meningkatkan kerja sama tim pada bagian dan lintas Bidang dalam mencapai hasil kinerja yang baik." required></textarea>
              <small class="text-muted">
                <i class="fas fa-lightbulb"></i>
                System pintar 
            </div>

            <div class="mb-3">
              <label class="form-label">
                <i class="fas fa-clipboard-check"></i>
                E. Tanggapan Atasan Langsung
              </label>
              <div style="background: #f8f9fa; padding: 16px; border-radius: 8px; border: 1px solid #dee2e6;">
                <table style="width: 100%; border-collapse: collapse;">
                  <thead>
                    <tr style="border-bottom: 2px solid #dee2e6;">
                      <th style="padding: 12px; text-align: center; width: 60px;"><strong>Tanda (âˆš)</strong></th>
                      <th style="padding: 12px; text-align: left;"><strong>Uraian</strong></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                      <td style="padding: 12px; text-align: center;">
                        <input type="radio" name="tanggapan_atasan" value="Laporan kurang baik" id="tanggapan1" disabled>
                      </td>
                      <td style="padding: 12px;">
                        <label for="tanggapan1" style="margin: 0; cursor: default; color: #666;">Laporan kurang baik</label>
                      </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                      <td style="padding: 12px; text-align: center;">
                        <input type="radio" name="tanggapan_atasan" value="Laporan sudah baik" id="tanggapan2" disabled>
                      </td>
                      <td style="padding: 12px;">
                        <label for="tanggapan2" style="margin: 0; cursor: default; color: #666;">Laporan sudah baik</label>
                      </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                      <td style="padding: 12px; text-align: center;">
                        <input type="radio" name="tanggapan_atasan" value="Laporan diperbaiki" id="tanggapan3" disabled>
                      </td>
                      <td style="padding: 12px;">
                        <label for="tanggapan3" style="margin: 0; cursor: default; color: #666;">Laporan diperbaiki</label>
                      </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                      <td style="padding: 12px; text-align: center;">
                        <input type="radio" name="tanggapan_atasan" value="Laporan diteliti ulang" id="tanggapan4" disabled>
                      </td>
                      <td style="padding: 12px;">
                        <label for="tanggapan4" style="margin: 0; cursor: default; color: #666;">Laporan diteliti ulang</label>
                      </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #dee2e6;">
                      <td style="padding: 12px; text-align: center;">
                        <input type="radio" name="tanggapan_atasan" value="Realisasi diteliti ulang" id="tanggapan5" disabled>
                      </td>
                      <td style="padding: 12px;">
                        <label for="tanggapan5" style="margin: 0; cursor: default; color: #666;">Realisasi diteliti ulang</label>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding: 12px; text-align: center;">
                        <input type="radio" name="tanggapan_atasan" value="Capaian diteliti ulang" id="tanggapan6" disabled>
                      </td>
                      <td style="padding: 12px;">
                        <label for="tanggapan6" style="margin: 0; cursor: default; color: #666;">Capaian diteliti ulang</label>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <small class="text-muted" style="display: block; margin-top: 8px;">
                <i class="fas fa-lightbulb"></i>
                Otomatis terisi berdasarkan hasil capaian kinerja dan anggaran.
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn-secondary-alt" onclick="backToEvaluasi()">
                <i class="fas fa-arrow-left"></i>
                Kembali
              </button>
              <button type="button" class="btn-save" onclick="proceedToKesimpulan()">
                <i class="fas fa-arrow-right"></i>
                Selanjutnya
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Kesimpulan -->
  <div class="modal fade" id="kesimpulanModal" tabindex="-1" aria-labelledby="kesimpulanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="kesimpulanModalLabel">
            <i class="fas fa-check-circle"></i>
            E. Kesimpulan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="kesimpulanForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="kesimpulanPerjanjianId" name="perjanjian_id">
            <input type="hidden" id="kesimpulanTriwulanEdit" name="triwulan">
            
            <div class="mb-3">
              <label class="form-label">
                <i class="fas fa-check-circle"></i>
                E. Kesimpulan
                <span style="color: #e74c3c;">*</span>
              </label>
              <textarea class="form-control" id="kesimpulanInput" name="kesimpulan" rows="8"
                        placeholder="Demikian laporan kinerja triwulan ini yang menunjukkan hasil capaian kinerja..." required></textarea>
              <small class="text-muted">
                <i class="fas fa-lightbulb"></i>
                Sistem akan menghasilkan kesimpulan otomatis berdasarkan data kinerja
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn-secondary-alt" onclick="backToRencanaFromKesimpulan()">
                <i class="fas fa-arrow-left"></i>
                Kembali
              </button>
              <button type="button" class="btn-save" onclick="proceedToPenutup()">
                <i class="fas fa-arrow-right"></i>
                Selanjutnya
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Penutup dan Tanda Tangan -->
  <div class="modal fade" id="penutupModal" tabindex="-1" aria-labelledby="penutupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="penutupModalLabel">
            <i class="fas fa-file-signature"></i>
            Penutup dan Persetujuan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="penutupForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" id="penutupPerjanjianId" name="perjanjian_id">
            <input type="hidden" id="penutupTriwulanEdit" name="triwulan">

            <div style="background: #f5f5f5; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
              <h6 style="margin: 0 0 12px 0; font-weight: 600; color: #1B2A41;">Informasi Laporan</h6>
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                <div>
                  <div style="color: #666; margin-bottom: 2px;">Nama Pegawai</div>
                  <div style="font-weight: 600; color: #1B2A41;" id="penutupModalPegawai">-</div>
                </div>
                <div>
                  <div style="color: #666; margin-bottom: 2px;">Jabatan</div>
                  <div style="font-weight: 600; color: #1B2A41;" id="penutupModalJabatan">-</div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <h6 style="font-weight: 700; color: #1B2A41; margin-bottom: 16px;">
                <i class="fas fa-signature"></i> Tanda Tangan dan Persetujuan
              </h6>
              
              <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Pihak Pertama (Pegawai) -->
                <div style="border: 1px solid #ddd; padding: 16px; border-radius: 8px; text-align: center;">
                  <div style="margin-bottom: 60px; min-height: 80px; border: 1px dashed #ccc; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #999; font-size: 12px;">Tanda Tangan Pihak Pertama</span>
                  </div>
                  <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 4px;">Pegawai</label>
                    <input type="text" id="ttdPertamaNama" placeholder="Nama" class="form-control form-control-sm" readonly style="background: #f5f5f5;">
                  </div>
                  <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 4px;">Tanggal</label>
                    <input type="date" id="ttdPertamaTanggal" name="ttd_pertama_tanggal" class="form-control form-control-sm">
                  </div>
                </div>

                <!-- Pihak Kedua (Atasan) -->
                <div style="border: 1px solid #ddd; padding: 16px; border-radius: 8px; text-align: center;">
                  <div style="margin-bottom: 60px; min-height: 80px; border: 1px dashed #ccc; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: #999; font-size: 12px;">Tanda Tangan Pihak Kedua</span>
                  </div>
                  <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 4px;">Atasan Langsung</label>
                    <input type="text" id="ttdKeduaNama" placeholder="Nama" class="form-control form-control-sm" readonly style="background: #f5f5f5;">
                  </div>
                  <div>
                    <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 4px;">Tanggal</label>
                    <input type="date" id="ttdKeduaTanggal" name="ttd_kedua_tanggal" class="form-control form-control-sm">
                  </div>
                </div>
              </div>

              <small class="text-muted" style="display: block; margin-top: 12px;">
                <i class="fas fa-info-circle"></i>
                Silakan isi tanggal tanda tangan untuk kedua pihak.
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn-secondary-alt" onclick="backToRencana()">
                <i class="fas fa-arrow-left"></i>
                Kembali
              </button>
              <button type="submit" class="btn-save">
                <i class="fas fa-check"></i>
                Simpan Laporan Kinerja
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer>&copy; 2026 RSUD Bangil &ndash; Sistem Laporan Kinerja</footer>

  <!-- Hidden data container untuk semua realisasi -->
  <div id="realisasiData" style="display: none;">
    <?php if(!empty($laporans)): ?>
      <?php $__currentLoopData = $laporans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laporan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div data-laporan-id="<?php echo e($laporan->id); ?>">
          <div data-tb="1" data-content="<?php echo e($laporan->realisasi_tb1 ?? ''); ?>"></div>
          <div data-tb="2" data-content="<?php echo e($laporan->realisasi_tb2 ?? ''); ?>"></div>
          <div data-tb="3" data-content="<?php echo e($laporan->realisasi_tb3 ?? ''); ?>"></div>
          <div data-tb="4" data-content="<?php echo e($laporan->realisasi_tb4 ?? ''); ?>"></div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // DEKLARASIKAN FUNGSI SEBELUM SEMUA KODE LAINNYA - VERSI GABUNGAN
    function openRealisasiModal(triwulan) {
      const modal = document.getElementById('realisasiModal');
      document.getElementById('perjanjianId').value = perjanjianData.id;
      document.getElementById('triwulanEdit').value = triwulan;
      document.getElementById('modalTriwulan').textContent = 'Triwulan ' + triwulan;
      document.getElementById('modalPegawai').textContent = perjanjianData.nama;
      document.getElementById('modalJabatan').textContent = perjanjianData.jabatan;
      document.getElementById('modalAtasan').textContent = perjanjianData.atasan;

      document.querySelectorAll('.row-realisasi-input').forEach(input => {
        input.value = '';
        const percentageCell = document.getElementById('percentage-' + input.dataset.row);
        if (percentageCell) percentageCell.textContent = '-';
      });
      document.querySelectorAll('.computed-realisasi-value').forEach(element => {
        element.value = '-';
        const percentageCell = document.getElementById('percentage-' + element.dataset.row);
        if (percentageCell) percentageCell.textContent = '-';
      });

      const triwulanKey = 'realisasi_tb' + triwulan;
      const laporanData = laporanRealisasi[perjanjianData.id] || {};
      const rawContent = laporanData[triwulan] || '';
      let existingContent = '';
      let existingRows = [];
      let existingFollowUp = '';
      if (rawContent) {
        try {
          const parsed = JSON.parse(rawContent);
          if (parsed && typeof parsed === 'object') {
            existingContent = parsed.text || '';
            existingRows = Array.isArray(parsed.rows) ? parsed.rows : [];
            existingFollowUp = parsed.followup || parsed.rencana_tindak_lanjut || '';
          } else {
            existingContent = rawContent;
          }
        } catch (err) {
          existingContent = rawContent;
        }
      }
      document.getElementById('realisasiInput').value = existingContent;
      document.getElementById('rencanaTindakLanjutInput').value = existingFollowUp;
      populateRowRealisasi(existingRows);

      lastAutoGeneratedText = '';
      lastAutoGeneratedFollowUpText = '';

      document.querySelectorAll('.row-realisasi-input').forEach(input => {
        input.removeEventListener('input', updatePercentage);
        input.addEventListener('input', function(event) {
          updatePercentage(event);
          refreshComputedAnggaranRows();
          updateEvaluasiText();
          updateRencanaText();
          updateTanggapanAtasan();
        });
      });

      const textarea = document.getElementById('realisasiInput');
      const generatedText = generateEvaluasiSummary();
      
      if (!textarea.value.trim() || textarea.value.trim() === lastAutoGeneratedText.trim() || textarea.value.trim() === generatedText.trim()) {
        textarea.value = generatedText;
        lastAutoGeneratedText = generatedText;
      }

      textarea.removeEventListener('input', textareaManualEditListener);
      textarea.addEventListener('input', textareaManualEditListener);

      const followUpTextarea = document.getElementById('rencanaTindakLanjutInput');
      const generatedFollowUp = generateRencanaTindakLanjut();
      
      if (!followUpTextarea.value.trim() || followUpTextarea.value.trim() === lastAutoGeneratedFollowUpText.trim() || followUpTextarea.value.trim() === generatedFollowUp.trim()) {
        followUpTextarea.value = generatedFollowUp;
        lastAutoGeneratedFollowUpText = generatedFollowUp;
      }

      followUpTextarea.removeEventListener('input', followUpTextareaManualEditListener);
      followUpTextarea.addEventListener('input', followUpTextareaManualEditListener);

      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    }
    // Expose function globally for onclick handlers
    window.openRealisasiModal = openRealisasiModal;

    const triwulanAktif = Number(<?php echo e($triwulanAktif ?? 1); ?>);
    const perjanjianData = {
      id: <?php echo e($perjanjian ? $perjanjian->id : 'null'); ?>,
      nama: "<?php echo e($perjanjian ? $perjanjian->pihak1_name : ''); ?>",
      jabatan: "<?php echo e($perjanjian ? $perjanjian->pihak1_jabatan : ''); ?>",
      atasan: "<?php echo e($perjanjian ? $perjanjian->pihak2_name : ''); ?>",
    };
    
    // Simpan semua data realisasi dalam object
    const laporanRealisasi = {};
    document.querySelectorAll('#realisasiData > div').forEach(container => {
      const laporanId = container.dataset.laporanId;
      laporanRealisasi[laporanId] = {};
      container.querySelectorAll('[data-tb]').forEach(div => {
        const tb = div.dataset.tb;
        const content = div.dataset.content || '';
        laporanRealisasi[laporanId][tb] = content;
      });
    });
    
    // Fungsi untuk menghitung persentase otomatis
    function calculatePercentage(target, realisasi) {
      if (isNaN(target) || target === 0 || isNaN(realisasi)) {
        return null;
      }

      // Formula standard: persentase = (realisasi / target) * 100
      return (realisasi / target) * 100;
    }

    function parseNumberValue(value) {
      if (value === null || value === undefined || value === '') {
        return NaN;
      }
      const normalized = String(value).replace(/\./g, '').replace(/,/g, '.');
      return parseFloat(normalized);
    }

    function formatNumberValue(value) {
      if (value === null || value === undefined || isNaN(value)) {
        return '-';
      }
      return Number(value).toLocaleString('id-ID', {
        maximumFractionDigits: 2,
        minimumFractionDigits: 0,
      });
    }

    function setPercentageForRow(rowId, value, target) {
      const percentageCell = document.getElementById('percentage-' + rowId);
      if (!percentageCell) return;
      const percentage = calculatePercentage(target, value);
      percentageCell.textContent = percentage === null ? '-' : percentage.toFixed(2) + '%';
    }

    function updatePercentage(event) {
      const input = event.target;
      const target = parseFloat(input.dataset.target || '0');
      const realisasi = parseFloat(input.value || '0');
      setPercentageForRow(input.dataset.row, realisasi, target);
    }

    function computeAggregateForRow(rowId) {
      const children = Array.from(document.querySelectorAll('.row-realisasi-input')).filter(input => {
        const row = input.dataset.row || '';
        return row.startsWith(rowId + '.') && row !== rowId;
      });
      if (children.length === 0) {
        return null;
      }
      return children.reduce((sum, input) => {
        const value = parseNumberValue(input.value);
        return sum + (isNaN(value) ? 0 : value);
      }, 0);
    }

    function refreshComputedAnggaranRows() {
      const computedRows = Array.from(document.querySelectorAll('.computed-realisasi-value'));
      const sortedComputed = computedRows.sort((a, b) => {
        return (b.dataset.row || '').split('.').length - (a.dataset.row || '').split('.').length;
      });
      sortedComputed.forEach(element => {
        const rowId = element.dataset.row;
        const computedValue = computeAggregateForRow(rowId);
        if (computedValue !== null) {
          element.value = formatNumberValue(computedValue);
          const target = parseFloat(element.dataset.target || '0');
          setPercentageForRow(rowId, computedValue, target);
        } else {
          element.value = '-';
          setPercentageForRow(rowId, NaN, parseFloat(element.dataset.target || '0'));
        }
      });
    }

    let lastAutoGeneratedText = '';
    let manualTextEdit = false;
    let lastAutoGeneratedFollowUpText = '';
    let manualFollowUpEdit = false;

    function formatCurrencyIDR(value) {
      if (value === null || value === undefined || isNaN(value)) {
        return 'Rp0';
      }
      return 'Rp' + Number(value).toLocaleString('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      });
    }

    function formatPercentageIDR(value) {
      if (value === null || value === undefined || isNaN(value)) {
        return '0,00';
      }
      return Number(value).toLocaleString('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    }

    function getCategory(percentage) {
      const pct = parseFloat(percentage);
      if (pct >= 85) return 'Sangat Baik';
      if (pct >= 70) return 'Baik';
      if (pct >= 55) return 'Cukup';
      return 'Perlu Perbaikan';
    }

    function setTanggapanAtasan(percentage) {
      const pct = parseFloat(percentage);
      let tanggapanValue = '';
      
      if (pct >= 85) {
        tanggapanValue = 'Laporan sudah baik';
      } else if (pct >= 70) {
        tanggapanValue = 'Laporan sudah baik';
      } else if (pct >= 55) {
        tanggapanValue = 'Laporan diperbaiki';
      } else {
        tanggapanValue = 'Laporan kurang baik';
      }
      
      // Set radio button yang sesuai
      const radioButton = document.querySelector(`input[name="tanggapan_atasan"][value="${tanggapanValue}"]`);
      if (radioButton) {
        radioButton.checked = true;
      }
    }

    function generateEvaluasiSummary() {
      const kinerjaInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="kinerja-"]'));
      const anggaranInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="anggaran-"]'));

      const validKinerja = kinerjaInputs.map(input => {
        const target = parseNumberValue(input.dataset.target);
        const actual = parseNumberValue(input.value);
        const percentage = calculatePercentage(target, actual);
        return {
          target,
          actual,
          percentage,
          hasValue: !isNaN(actual) && input.value !== '' && input.value !== null,
        };
      }).filter(item => item.hasValue && !isNaN(item.percentage));

      const kinerjaCount = kinerjaInputs.length;
      const averageKinerjaPercentage = validKinerja.length > 0
        ? validKinerja.reduce((sum, item) => sum + item.percentage, 0) / validKinerja.length
        : 0;

      const totalAnggaranActual = anggaranInputs.reduce((sum, input) => {
        const value = parseNumberValue(input.value);
        return sum + (isNaN(value) ? 0 : value);
      }, 0);

      const totalAnggaranTarget = anggaranInputs.reduce((sum, input) => {
        const target = parseNumberValue(input.dataset.target);
        return sum + (isNaN(target) ? 0 : target);
      }, 0);

      const anggaranPercentage = totalAnggaranTarget > 0
        ? (totalAnggaranActual / totalAnggaranTarget) * 100
        : 0;

      const averagePercentage = (averageKinerjaPercentage + anggaranPercentage) / 2;
      const averagePercentageText = formatPercentageIDR(averagePercentage);
      const overallCategory = getCategory(averagePercentage);

      return `Berdasarkan capaian kinerja ${triwulanText} ${jabatanText}, dapat disimpulkan bahwa dari ${indicatorCountText} sasaran mencapai ${averageText}%. Untuk capaian realisasi anggaran tercapai ${totalAnggaranText} (${anggaranPercentText}%). Sehingga dari prosentase capaian indikator kinerja dan anggaran sebesar ${averagePercentageText}% maka dinyatakan laporan ini ${overallCategory}.`;
    }

    function updateEvaluasiText() {
      const textarea = document.getElementById('realisasiInput');
      if (!textarea) return;
      
      // Hanya update jika textarea masih sama dengan lastAutoGeneratedText (belum diedit manual)
      if (textarea.value.trim() !== lastAutoGeneratedText.trim()) {
        return;
      }
      
      const generatedText = generateEvaluasiSummary();
      textarea.value = generatedText;
      lastAutoGeneratedText = generatedText;
    }

    function generateRencanaTindakLanjut() {
      const kinerjaInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="kinerja-"]'));
      const anggaranInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="anggaran-"]'));

      const totalAnggaranActual = anggaranInputs.reduce((sum, input) => {
        const value = parseNumberValue(input.value);
        return sum + (isNaN(value) ? 0 : value);
      }, 0);

      const totalAnggaranTarget = anggaranInputs.reduce((sum, input) => {
        const target = parseNumberValue(input.dataset.target);
        return sum + (isNaN(target) ? 0 : target);
      }, 0);

      const anggaranPercentage = totalAnggaranTarget > 0
        ? (totalAnggaranActual / totalAnggaranTarget) * 100
        : 0;

      const validKinerja = kinerjaInputs.map(input => {
        const target = parseNumberValue(input.dataset.target);
        const actual = parseNumberValue(input.value);
        const percentage = calculatePercentage(target, actual);
        return {
          target,
          actual,
          percentage,
          hasValue: !isNaN(actual) && input.value !== '' && input.value !== null,
        };
      }).filter(item => item.hasValue && !isNaN(item.percentage));

      const averageKinerjaPercentage = validKinerja.length > 0
        ? validKinerja.reduce((sum, item) => sum + item.percentage, 0) / validKinerja.length
        : 0;

      const items = [];
      
      // Rekomendasi berdasarkan capaian kinerja
      if (validKinerja.length > 0) {
        if (averageKinerjaPercentage >= 90) {
          items.push('Mempertahankan capaian realisasi kinerja yang telah mencapai target.');
        } else if (averageKinerjaPercentage >= 70) {
          items.push('Meningkatkan upaya pelaksanaan untuk mencapai target kinerja yang lebih optimal.');
        } else {
          items.push('Meningkatkan kualitas pelaksanaan dan pemantauan untuk mencapai capaian realisasi kinerja yang lebih optimal.');
        }
      }

      // Rekomendasi berdasarkan realisasi anggaran
      if (totalAnggaranTarget > 0) {
        if (anggaranPercentage >= 100) {
          items.push('Efisiensi penggunaan anggaran untuk menjaga agar alokasi anggaran tetap selaras dengan rencana.');
        } else if (anggaranPercentage >= 75) {
          items.push('Mempercepat realisasi anggaran agar tercapai target yang telah ditetapkan.');
        } else {
          items.push('Meningkatkan realisasi anggaran agar dapat mencapai target yang telah direncanakan.');
        }
      }

      items.push('Meningkatkan kerja sama tim pada bagian dan lintas Bidang dalam mencapai hasil kinerja yang baik.');

      const listText = items.map((item, index) => `${index + 1}. ${item}`).join('\n');
      return Rencana Tindak Lanjut dari hasil capaian kinerja adalah sebagai berikut:\n;
    }

    function updateRencanaText() {
      const textarea = document.getElementById('rencanaTindakLanjutInput');
      if (!textarea) return;
      
      // Hanya update jika textarea masih sama dengan lastAutoGeneratedFollowUpText (belum diedit manual)
      if (textarea.value.trim() !== lastAutoGeneratedFollowUpText.trim()) {
        return;
      }
      
      const generatedText = generateRencanaTindakLanjut();
      textarea.value = generatedText;
      lastAutoGeneratedFollowUpText = generatedText;
    }

    function updateTanggapanAtasan() {
      const kinerjaInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="kinerja-"]'));
      const anggaranInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="anggaran-"]'));

      const validKinerja = kinerjaInputs.map(input => {
        const target = parseNumberValue(input.dataset.target);
        const actual = parseNumberValue(input.value);
        const percentage = calculatePercentage(target, actual);
        return {
          percentage,
          hasValue: !isNaN(actual) && input.value !== '' && input.value !== null,
        };
      }).filter(item => item.hasValue && !isNaN(item.percentage));

      const averageKinerjaPercentage = validKinerja.length > 0
        ? validKinerja.reduce((sum, item) => sum + item.percentage, 0) / validKinerja.length
        : 0;

      const totalAnggaranActual = anggaranInputs.reduce((sum, input) => {
        const value = parseNumberValue(input.value);
        return sum + (isNaN(value) ? 0 : value);
      }, 0);

      const totalAnggaranTarget = anggaranInputs.reduce((sum, input) => {
        const target = parseNumberValue(input.dataset.target);
        return sum + (isNaN(target) ? 0 : target);
      }, 0);

      const anggaranPercentage = totalAnggaranTarget > 0
        ? (totalAnggaranActual / totalAnggaranTarget) * 100
        : 0;

      const averagePercentage = (averageKinerjaPercentage + anggaranPercentage) / 2;
      setTanggapanAtasan(averagePercentage);
    }

    function populateRowRealisasi(existingRows) {
      if (!Array.isArray(existingRows)) return;
      existingRows.forEach(row => {
        const input = document.querySelector('.row-realisasi-input[data-row="' + row.row + '"]');
        if (input && row.realisasi !== undefined) {
          input.value = row.realisasi;
          setPercentageForRow(row.row, parseNumberValue(row.realisasi), parseFloat(input.dataset.target || '0'));
        }
        const computed = document.querySelector('.computed-realisasi-value[data-row="' + row.row + '"]');
        if (computed && row.realisasi !== undefined) {
          computed.value = formatNumberValue(row.realisasi);
          setPercentageForRow(row.row, parseNumberValue(row.realisasi), parseFloat(computed.dataset.target || '0'));
        }
      });
      refreshComputedAnggaranRows();
      // Update summary otomatis setelah data di-load
      setTimeout(() => {
        updateEvaluasiText();
        updateRencanaText();
        // Set tanggapan atasan berdasarkan persentase capaian
        const kinerjaInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="kinerja-"]'));
        const anggaranInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="anggaran-"]'));
        
        const validKinerja = kinerjaInputs.map(input => {
          const target = parseNumberValue(input.dataset.target);
          const actual = parseNumberValue(input.value);
          const percentage = calculatePercentage(target, actual);
          return {
            percentage,
            hasValue: !isNaN(actual) && input.value !== '' && input.value !== null,
          };
        }).filter(item => item.hasValue && !isNaN(item.percentage));

        const averageKinerjaPercentage = validKinerja.length > 0
          ? validKinerja.reduce((sum, item) => sum + item.percentage, 0) / validKinerja.length
          : 0;

        const totalAnggaranActual = anggaranInputs.reduce((sum, input) => {
          const value = parseNumberValue(input.value);
          return sum + (isNaN(value) ? 0 : value);
        }, 0);

        const totalAnggaranTarget = anggaranInputs.reduce((sum, input) => {
          const target = parseNumberValue(input.dataset.target);
          return sum + (isNaN(target) ? 0 : target);
        }, 0);

        const anggaranPercentage = totalAnggaranTarget > 0
          ? (totalAnggaranActual / totalAnggaranTarget) * 100
          : 0;

        const averagePercentage = (averageKinerjaPercentage + anggaranPercentage) / 2;
        setTanggapanAtasan(averagePercentage);
      }, 50);
    }

    function textareaManualEditListener(event) {
      const textarea = event.target;
      // Tandai sebagai manual edit jika nilai berbeda dari generated text
      if (textarea.value.trim() !== lastAutoGeneratedText.trim()) {
        // User telah mengubah text secara manual
      }
    }

    function followUpTextareaManualEditListener(event) {
      const textarea = event.target;
      // Tandai sebagai manual edit jika nilai berbeda dari generated text
      if (textarea.value.trim() !== lastAutoGeneratedFollowUpText.trim()) {
        // User telah mengubah text secara manual
      }
    }

    // Store data temporarily for two-step form
    let tempFormData = {};

    function proceedToRencana() {
      const realisasi = document.getElementById('realisasiInput').value;
      
      if (realisasi.trim().length < 50) {
        alert('Evaluasi dan Analisis Kinerja minimal harus 50 karakter');
        return;
      }

      // Store form data
      tempFormData.perjanjianId = document.getElementById('perjanjianId').value;
      tempFormData.triwulan = document.getElementById('triwulanEdit').value;
      tempFormData.realisasi = realisasi;
      tempFormData.rowRealisasi = [];

      document.querySelectorAll('.row-realisasi-input, .computed-realisasi-value').forEach(element => {
        const rowId = element.dataset.row || null;
        if (!rowId) {
          return;
        }
        const rawValue = element.value;
        const realisasiValue = parseNumberValue(rawValue);
        tempFormData.rowRealisasi.push({
          row: rowId,
          realisasi: isNaN(realisasiValue) ? null : realisasiValue,
          target: element.dataset.target ? parseFloat(element.dataset.target) : null,
        });
      });

      // Hide first modal
      bootstrap.Modal.getInstance(document.getElementById('realisasiModal')).hide();

      // Set data in second modal
      document.getElementById('rencanaPerjanjianId').value = tempFormData.perjanjianId;
      document.getElementById('rencanaTrjulanEdit').value = tempFormData.triwulan;

      // Show second modal
      setTimeout(() => {
        const rencanaModal = new bootstrap.Modal(document.getElementById('rencanaTindakLanjutModal'));
        rencanaModal.show();
      }, 300);
    }

    function backToEvaluasi() {
      // Hide second modal
      bootstrap.Modal.getInstance(document.getElementById('rencanaTindakLanjutModal')).hide();

      // Show first modal
      setTimeout(() => {
        const realisasiModal = new bootstrap.Modal(document.getElementById('realisasiModal'));
        realisasiModal.show();
      }, 300);
    }

    // Fungsi untuk format angka Indonesia (pakai koma)
    function formatIndonesianNumber(value) {
      if (value === null || value === undefined || isNaN(value)) {
        return '0';
      }
      return value.toFixed(1).replace('.', ',');
    }

    // Fungsi untuk generate kesimpulan otomatis
    function generateKesimpulan() {
      const triwulan = triwulanAktif;
      const tahun = <?php echo e($perjanjian->tahun ?? date('Y')); ?>;
      const jabatan = perjanjianData.jabatan || 'jabatan terkait';
      
      // Hitung rata-rata kinerja
      const kinerjaInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="kinerja-"]'));
      const validKinerja = kinerjaInputs.map(input => {
        const target = parseNumberValue(input.dataset.target);
        const actual = parseNumberValue(input.value);
        const percentage = calculatePercentage(target, actual);
        return {
          target,
          actual,
          percentage,
          hasValue: !isNaN(actual) && input.value !== '' && input.value !== null,
        };
      }).filter(item => item.hasValue && !isNaN(item.percentage));

      const averageKinerja = validKinerja.length > 0
        ? validKinerja.reduce((sum, item) => sum + item.percentage, 0) / validKinerja.length
        : 0;

      // Hitung total anggaran
      const anggaranInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="anggaran-"]'));
      const totalAnggaranActual = anggaranInputs.reduce((sum, input) => {
        const value = parseNumberValue(input.value);
        return sum + (isNaN(value) ? 0 : value);
      }, 0);

      const totalAnggaranTarget = anggaranInputs.reduce((sum, input) => {
        const target = parseNumberValue(input.dataset.target);
        return sum + (isNaN(target) ? 0 : target);
      }, 0);

      const anggaranPercentage = totalAnggaranTarget > 0
        ? (totalAnggaranActual / totalAnggaranTarget) * 100
        : 0;

      // Rata-rata keseluruhan
      const overallPercentage = (averageKinerja + anggaranPercentage) / 2;
      
      // Tentukan status capaian
      let statusCapaian = '';
      if (overallPercentage >= 85) {
        statusCapaian = 'sangat baik';
      } else if (overallPercentage >= 70) {
        statusCapaian = 'baik';
      } else if (overallPercentage >= 55) {
        statusCapaian = 'cukup';
      } else {
        statusCapaian = 'perlu peningkatan';
      }

      // Format triwulan teks
      const triwulanText = triwulan === 1 ? 'pertama' : triwulan === 2 ? 'kedua' : triwulan === 3 ? 'ketiga' : 'keempat';

      // Format persentase dengan koma
      const persentaseFormatted = formatIndonesianNumber(overallPercentage);

      const kesimpulan = `Demikian laporan kinerja triwulan ${triwulan} (${triwulanText}) Tahun ${tahun} ${jabatan} yang menunjukkan hasil capaian kinerja tercapai ${statusCapaian} dimana indikator sasaran kinerja dan anggaran mencapai ${persentaseFormatted}%. Ke depan perlu meningkatkan pelaksanaan kegiatan dan koordinasi dengan lintas bidang untuk mencapai target kinerja yang optimal. Diharapkan laporan ini berguna dan bermanfaat bagi peningkatan kinerja organisasi.`;

      return kesimpulan;
    }

    function proceedToKesimpulan() {
      const rencanaTindakLanjut = document.getElementById('rencanaTindakLanjutInput').value;
      
      if (rencanaTindakLanjut.trim().length < 20) {
        alert('Rencana Tindak Lanjut minimal harus 20 karakter');
        return;
      }

      // Simpan data rencana ke temp
      tempFormData.rencanaTindakLanjut = rencanaTindakLanjut;
      tempFormData.tanggapanAtasan = document.querySelector('input[name="tanggapan_atasan"]:checked')?.value || '';

      // Generate kesimpulan otomatis
      const kesimpulan = generateKesimpulan();
      document.getElementById('kesimpulanInput').value = kesimpulan;
      lastAutoGeneratedText = kesimpulan;

      // Hide rencana modal
      bootstrap.Modal.getInstance(document.getElementById('rencanaTindakLanjutModal')).hide();

      // Show kesimpulan modal
      setTimeout(() => {
        const kesimpulanModal = new bootstrap.Modal(document.getElementById('kesimpulanModal'));
        kesimpulanModal.show();
      }, 300);
    }

    function backToRencanaFromKesimpulan() {
      // Hide kesimpulan modal
      bootstrap.Modal.getInstance(document.getElementById('kesimpulanModal')).hide();

      // Show rencana modal
      setTimeout(() => {
        const rencanaModal = new bootstrap.Modal(document.getElementById('rencanaTindakLanjutModal'));
        rencanaModal.show();
      }, 300);
    }

    // Form submission for Rencana Tindak Lanjut (modal 2)
    document.getElementById('rencanaTindakLanjutForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const rencanaTindakLanjut = document.getElementById('rencanaTindakLanjutInput').value;
      const tanggapanAtasan = document.querySelector('input[name="tanggapan_atasan"]:checked')?.value || '';
      
      if (!tempFormData.perjanjianId) {
        alert('Data tidak lengkap, silakan coba kembali');
        return;
      }

      // Ambil kesimpulan dari modal kesimpulan jika ada
      const kesimpulan = document.getElementById('kesimpulanInput')?.value || '';
      
      fetch(`/api/realisasi/perjanjian`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('#rencanaTindakLanjutForm input[name="_token"]').value
        },
        body: JSON.stringify({
          perjanjian_id: tempFormData.perjanjianId,
          triwulan: tempFormData.triwulan,
          realisasi: tempFormData.realisasi,
          realisasi_rows: tempFormData.rowRealisasi,
          rencana_tindak_lanjut: rencanaTindakLanjut,
          tanggapan_atasan: tanggapanAtasan,
          kesimpulan: kesimpulan,
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Close modal
          bootstrap.Modal.getInstance(document.getElementById('rencanaTindakLanjutModal')).hide();
          
          // Show success message
          showAlert('Laporan kinerja berhasil disimpan!', 'success');
          
          // Clear forms and temp data
          tempFormData = {};
          document.getElementById('realisasiInput').value = '';
          document.getElementById('rencanaTindakLanjutInput').value = '';
          document.getElementById('kesimpulanInput').value = '';
          document.querySelectorAll('.row-realisasi-input').forEach(input => {
            input.value = '';
            const percentageCell = document.getElementById('percentage-' + input.dataset.row);
            if (percentageCell) percentageCell.textContent = '-';
          });
        } else {
          showAlert('Terjadi kesalahan: ' + data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menyimpan', 'error');
      });
    });
    
    // Helper function untuk show alert
    function showAlert(message, type) {
      const alertDiv = document.createElement('div');
      alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 20px;
        border-radius: 8px;
        font-weight: 600;
        z-index: 9999;
        animation: slideIn 0.3s ease-in-out;
      `;
      
      if (type === 'success') {
        alertDiv.style.background = '#C8E6C9';
        alertDiv.style.color = '#2E7D32';
        alertDiv.style.border = '1px solid #81C784';
      } else {
        alertDiv.style.background = '#FFCDD2';
        alertDiv.style.color = '#C62828';
        alertDiv.style.border = '1px solid #EF5350';
      }
      
      alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
      document.body.appendChild(alertDiv);
      
      setTimeout(() => {
        alertDiv.remove();
      }, 3000);
    }
    
    // Add animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideIn {
        from {
          transform: translateX(400px);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
    `;
    document.head.appendChild(style);
    
    // ==================== SMART VALIDATION ====================
    
    let currentLaporanId = null;
    
    function getLaporanId() {
      // Ambil laporan ID dari data yang ada
      const laporanDiv = document.querySelector('#realisasiData > div');
      if (laporanDiv) {
        return laporanDiv.dataset.laporanId;
      }
      return null;
    }
    
    function getPerjanjianId() {
      return perjanjianData.id;
    }
    
    async function runSmartValidation() {
      let laporanId = getLaporanId();
      const perjanjianId = getPerjanjianId();
      
      // Jika belum ada laporan, coba cari atau buat berdasarkan perjanjian
      if (!laporanId && perjanjianId) {
        try {
          const response = await fetch(`/api/laporan/by-perjanjian/${perjanjianId}`, {
            method: 'GET',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
          });
          const data = await response.json();
          if (data.success && data.laporan_id) {
            laporanId = data.laporan_id;
          }
        } catch (e) {
          console.log('Could not fetch laporan ID');
        }
      }
      
      if (!laporanId) {
        alert('Belum ada laporan. Silakan klik tombol Triwulan untuk membuat laporan pertama.');
        return;
      }
      
      // Show loading
      const panel = document.getElementById('validationPanel');
      const loading = document.getElementById('validationLoading');
      const results = document.getElementById('validationResults');
      const scoreSection = document.getElementById('validationScore');
      
      panel.classList.add('show');
      loading.style.display = 'flex';
      results.innerHTML = '';
      scoreSection.style.display = 'none';
      
      // Scroll to panel
      panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
      
      try {
        const response = await fetch(`/api/laporan/${laporanId}/smart-validate`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          }
        });
        
        const data = await response.json();
        
        loading.style.display = 'none';
        
        if (data.success) {
          displayValidationResults(data.validation);
        } else {
          results.innerHTML = `
            <div class="validation-item issue">
              <div class="validation-item-header">
                <span class="validation-item-title">Error</span>
              </div>
              <div class="validation-item-message">${data.message || 'Terjadi kesalahan saat validasi'}</div>
            </div>
          `;
        }
      } catch (error) {
        loading.style.display = 'none';
        results.innerHTML = `
          <div class="validation-item issue">
            <div class="validation-item-header">
              <span class="validation-item-title">Error</span>
            </div>
            <div class="validation-item-message">Terjadi kesalahan koneksi: ${error.message}</div>
          </div>
        `;
      }
    }
    
    function displayValidationResults(validation) {
      const results = document.getElementById('validationResults');
      const scoreSection = document.getElementById('validationScore');
      const scoreValue = document.getElementById('scoreValue');
      const scoreCircle = document.getElementById('scoreCircle');
      
      // Show score
      scoreSection.style.display = 'flex';
      scoreValue.textContent = validation.score + '/100';
      
      // Set score circle color
      scoreCircle.className = 'score-circle';
      if (validation.score >= 90) {
        scoreCircle.classList.add('score-excellent');
        scoreCircle.textContent = 'âœ“';
      } else if (validation.score >= 75) {
        scoreCircle.classList.add('score-good');
        scoreCircle.textContent = 'âœ“';
      } else if (validation.score >= 60) {
        scoreCircle.classList.add('score-warning');
        scoreCircle.textContent = '!';
      } else {
        scoreCircle.classList.add('score-danger');
        scoreCircle.textContent = 'âœ—';
      }
      
      let html = '';
      
      // Summary
      const summaryClass = validation.score >= 90 ? 'summary-excellent' : 
                          validation.score >= 75 ? 'summary-good' : 
                          validation.score >= 60 ? 'summary-warning' : 'summary-danger';
      html += `<div class="validation-summary ${summaryClass}">${validation.summary}</div>`;
      
      // Issues
      if (validation.issues && validation.issues.length > 0) {
        html += `
          <div class="validation-section">
            <div class="validation-section-title">
              <i class="fas fa-exclamation-circle" style="color: #f44336;"></i>
              Issues (${validation.issues.length})
            </div>
        `;
        validation.issues.forEach(issue => {
          html += `
            <div class="validation-item issue">
              <div class="validation-item-header">
                <span class="validation-item-title">${issue.message}</span>
                <span class="validation-item-severity severity-${issue.severity}">${issue.severity}</span>
              </div>
              <div class="validation-item-fix">
                <i class="fas fa-lightbulb"></i>
                ${issue.fix || 'Perbaiki data ini'}
              </div>
            </div>
          `;
        });
        html += '</div>';
      }
      
      // Warnings
      if (validation.warnings && validation.warnings.length > 0) {
        html += `
          <div class="validation-section">
            <div class="validation-section-title">
              <i class="fas fa-exclamation-triangle" style="color: #FF9800;"></i>
              Warnings (${validation.warnings.length})
            </div>
        `;
        validation.warnings.forEach(warning => {
          html += `
            <div class="validation-item warning">
              <div class="validation-item-header">
                <span class="validation-item-title">${warning.message}</span>
                <span class="validation-item-severity severity-${warning.severity}">${warning.severity}</span>
              </div>
              ${warning.fix ? `<div class="validation-item-fix"><i class="fas fa-lightbulb"></i> ${warning.fix}</div>` : ''}
            </div>
          `;
        });
        html += '</div>';
      }
      
      // Suggestions
      if (validation.suggestions && validation.suggestions.length > 0) {
        html += `
          <div class="validation-section">
            <div class="validation-section-title">
              <i class="fas fa-info-circle" style="color: #2196F3;"></i>
              Saran Perbaikan (${validation.suggestions.length})
            </div>
        `;
        validation.suggestions.forEach(suggestion => {
          html += `
            <div class="validation-item suggestion">
              <div class="validation-item-message">${suggestion.message}</div>
            </div>
          `;
        });
        html += '</div>';
      }
      
      // Success message if no issues
      if (validation.issues.length === 0 && validation.warnings.length === 0) {
        html += `
          <div class="validation-item success">
            <div class="validation-item-header">
              <span class="validation-item-title">Laporan Lengkap!</span>
            </div>
            <div class="validation-item-message">Semua data laporan telah lengkap dan valid.</div>
          </div>
        `;
      }
      
      results.innerHTML = html;
    }
    
    function closeValidationPanel() {
      const panel = document.getElementById('validationPanel');
      panel.classList.remove('show');
    }
  </script>
</body>
</html>








<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/laporan-kinerja.blade.php ENDPATH**/ ?>