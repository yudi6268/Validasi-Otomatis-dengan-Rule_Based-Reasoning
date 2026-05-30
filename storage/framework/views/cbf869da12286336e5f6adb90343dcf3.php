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
      background: #f5f5f5;
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
    .logo-container img { height: 55px; }
    .header-title {
      font-weight: 700;
      color: #009970;
      font-size: 18px;
    }
    nav { display: flex; gap: 25px; }
    nav a { text-decoration: none; color: #555; font-weight: 600; font-size: 15px; transition: 0.3s; }
    nav a:hover { color: #00B5A0; }
    .dashboard-container {
      display: flex;
      flex: 1;
      gap: 0;
    }
    .sidebar {
      width: 260px;
      background: #fff;
      padding: 25px 20px;
      box-shadow: 2px 0 8px rgba(0,0,0,0.05);
    }
    .sidebar h3 {
      font-size: 13px;
      font-weight: 700;
      color: #999;
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
      gap: 10px;
      padding: 12px 15px;
      background: #f9f9f9;
      border-radius: 8px;
      text-decoration: none;
      color: #333;
      font-weight: 600;
      font-size: 14px;
      border-left: 4px solid transparent;
      transition: 0.3s;
    }
    .sidebar-menu a:hover,
    .sidebar-menu a.active {
      background: #E6F6F2;
      border-left-color: #00B5A0;
      color: #00B5A0;
    }
    .sidebar-menu i {
      width: 18px;
      color: #00B5A0;
    }
    .main-content {
      flex: 1;
      padding: 30px 40px;
      overflow-y: auto;
    }
    main {
      margin: 0;
      max-width: none;
    }
    @media (max-width: 1024px) {
      .dashboard-container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
      }
      .main-content {
        width: 100%;
      }
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
    /* ---- Triwulan card grid ---- */
    .triwulan-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px;
      margin-bottom: 28px;
    }

    @media (max-width: 900px) {
      .triwulan-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 500px) {
      .triwulan-grid { grid-template-columns: 1fr 1fr; }
    }

    .tw-card {
      background: #fff;
      border-radius: 14px;
      padding: 20px 16px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.07);
      border-top: 5px solid #d0d7de;
      cursor: pointer;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      text-align: center;
      position: relative;
      user-select: none;
    }

    .tw-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 24px rgba(0,0,0,0.12);
    }

    .tw-card.tw-active {
      border-top-color: #00B5A0;
      background: linear-gradient(160deg, #f0fdf9 0%, #fff 60%);
    }

    .tw-card.tw-1 { border-top-color: #5C6BC0; }
    .tw-card.tw-2 { border-top-color: #26A69A; }
    .tw-card.tw-3 { border-top-color: #EF6C00; }
    .tw-card.tw-4 { border-top-color: #AD1457; }

    .tw-card.tw-active.tw-1 { border-top-color: #5C6BC0; background: linear-gradient(160deg, #ede7f6 0%, #fff 60%); }
    .tw-card.tw-active.tw-2 { border-top-color: #26A69A; background: linear-gradient(160deg, #e0f2f1 0%, #fff 60%); }
    .tw-card.tw-active.tw-3 { border-top-color: #EF6C00; background: linear-gradient(160deg, #fff3e0 0%, #fff 60%); }
    .tw-card.tw-active.tw-4 { border-top-color: #AD1457; background: linear-gradient(160deg, #fce4ec 0%, #fff 60%); }

    .tw-card.tw-inactive {
      border-top-color: #cfd8dc;
      background: linear-gradient(180deg, #f6f8f9 0%, #ffffff 100%);
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }

    .tw-card.tw-inactive:hover {
      transform: none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .tw-card-badge {
      display: inline-block;
      font-size: 10px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 99px;
      margin-bottom: 10px;
      letter-spacing: 0.5px;
      text-transform: uppercase;
    }

    .tw-badge-active { background: #C8E6C9; color: #2E7D32; }
    .tw-badge-inactive { background: #ECEFF1; color: #607D8B; }
    .tw-badge-validated { background: #e8f5e9; color: #2e7d32; }

    .tw-card.tw-validated {
      border-top-color: #2e7d32;
      background: linear-gradient(160deg, #eef8ef 0%, #fff 60%);
      box-shadow: 0 8px 22px rgba(46, 125, 50, 0.16);
    }

    .tw-card-num {
      font-size: 36px;
      font-weight: 900;
      line-height: 1;
      margin-bottom: 6px;
    }

    .tw-card.tw-1 .tw-card-num { color: #5C6BC0; }
    .tw-card.tw-2 .tw-card-num { color: #26A69A; }
    .tw-card.tw-3 .tw-card-num { color: #EF6C00; }
    .tw-card.tw-4 .tw-card-num { color: #AD1457; }

    .tw-card.tw-inactive .tw-card-num,
    .tw-card.tw-inactive .tw-card-title,
    .tw-card.tw-inactive .tw-card-period {
      color: #8a98a8;
    }

    .tw-card-title {
      font-size: 13px;
      font-weight: 700;
      color: #1B2A41;
      margin-bottom: 4px;
    }

    .tw-card-period {
      font-size: 11px;
      color: #888;
      margin-bottom: 14px;
    }

    .tw-card-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      font-weight: 700;
      padding: 7px 16px;
      border-radius: 99px;
      border: none;
      cursor: pointer;
      transition: opacity 0.2s;
      color: #fff;
    }

    .tw-card.tw-1 .tw-card-btn { background: #5C6BC0; }
    .tw-card.tw-2 .tw-card-btn { background: #26A69A; }
    .tw-card.tw-3 .tw-card-btn { background: #EF6C00; }
    .tw-card.tw-4 .tw-card-btn { background: #AD1457; }

    .tw-card.tw-inactive .tw-card-btn {
      background: #90a4ae;
      color: #fff;
    }

    .tw-card-btn:hover { opacity: 0.85; }

    .tw-card-btn-secondary {
      margin-top: 8px;
      background: #ffffff;
      color: #334155;
      border: 1px solid #cbd5e1;
    }

    .tw-card.tw-1 .tw-card-btn-secondary,
    .tw-card.tw-2 .tw-card-btn-secondary,
    .tw-card.tw-3 .tw-card-btn-secondary,
    .tw-card.tw-4 .tw-card-btn-secondary {
      background: #ffffff;
      color: #334155;
      border: 1px solid #cbd5e1;
    }

    .tw-card.tw-inactive .tw-card-btn-secondary {
      background: #f8fafc;
      color: #64748b;
      border: 1px solid #cbd5e1;
    }

    .tw-validasi-row {
      margin-bottom: 24px;
      text-align: right;
    }

    .triwulan-tabs {
      display: none; /* legacy, replaced by grid */
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
    
    /* ---- Ringkasan Validasi summary cards ---- */
    .val-summary-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    @media (max-width: 860px) {
      .val-summary-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (max-width: 560px) {
      .val-summary-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .val-summary-card {
      background: #fff;
      border-radius: 12px;
      padding: 14px 12px;
      text-align: center;
      box-shadow: 0 4px 14px rgba(0,0,0,0.07);
      border-top: 4px solid transparent;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .val-summary-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.1);
    }

    .val-summary-card.total   { border-top-color: #5C6BC0; }
    .val-summary-card.valid   { border-top-color: #4CAF50; }
    .val-summary-card.invalid { border-top-color: #f44336; }
    .val-summary-card.revisi  { border-top-color: #FF9800; }
    .val-summary-card.warning { border-top-color: #2196F3; }

    .val-summary-num {
      font-size: 36px;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 6px;
    }

    .val-summary-card.total   .val-summary-num { color: #5C6BC0; }
    .val-summary-card.valid   .val-summary-num { color: #4CAF50; }
    .val-summary-card.invalid .val-summary-num { color: #f44336; }
    .val-summary-card.revisi  .val-summary-num { color: #FF9800; }
    .val-summary-card.warning .val-summary-num { color: #2196F3; }

    .val-summary-label {
      font-size: 12px;
      font-weight: 600;
      color: #667085;
    }

    .val-spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(0,0,0,0.1);
      border-top-color: currentColor;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      vertical-align: middle;
      opacity: 0.5;
    }

    /* ---- Detail section header ---- */
    .val-detail-header {
      font-size: 15px;
      font-weight: 700;
      color: #1B2A41;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      padding-bottom: 10px;
      border-bottom: 2px solid #eee;
    }

    .validation-actions {
      display: flex;
      gap: 12px;
      margin-top: 20px;
      padding-top: 16px;
      border-top: 1px solid #eee;
      flex-wrap: wrap;
    }
    
    .btn-validate {
      background: linear-gradient(135deg, #00B5A0, #008F7E);
      color: #fff;
      border: none;
      padding: 10px 22px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
    }
    
    .btn-validate:hover {
      background: linear-gradient(135deg, #008F7E, #00695C);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 181, 160, 0.3);
    }
    
    .btn-validate:disabled {
      opacity: 1;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    .btn-validate.btn-validated {
      background: linear-gradient(135deg, #e8f5e9, #dcedc8);
      color: #2e7d32;
      border: 1px solid #a5d6a7;
    }

    .btn-validate.btn-validated:hover {
      background: linear-gradient(135deg, #dcedc8, #c5e1a5);
      box-shadow: 0 4px 10px rgba(76, 175, 80, 0.25);
    }

    .validation-triwulan-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 12px;
    }

    .btn-choose-triwulan {
      border: 1px solid #d6ebe4;
      border-radius: 12px;
      background: #fff;
      color: #1B2A41;
      text-align: left;
      padding: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
      min-height: 88px;
    }

    .btn-choose-triwulan.tw-color-1 { border-top: 4px solid #1aa260; }
    .btn-choose-triwulan.tw-color-2 { border-top: 4px solid #f59f00; }
    .btn-choose-triwulan.tw-color-3 { border-top: 4px solid #e63946; }
    .btn-choose-triwulan.tw-color-4 { border-top: 4px solid #1e88e5; }

    .btn-choose-triwulan .tw-title {
      font-size: 14px;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .btn-choose-triwulan .tw-period {
      font-size: 12px;
      color: #667085;
      margin-bottom: 6px;
    }

    .btn-choose-triwulan .tw-status {
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #00B5A0;
    }

    .btn-choose-triwulan .tw-check-badge {
      display: none;
      margin-top: 8px;
      font-size: 11px;
      font-weight: 700;
      color: #2e7d32;
      background: #e8f5e9;
      border: 1px solid #a5d6a7;
      border-radius: 999px;
      padding: 2px 8px;
      align-items: center;
      gap: 5px;
      width: fit-content;
    }

    .btn-choose-triwulan.tw-color-1 .tw-check-badge {
      color: #1b5e20;
      background: #e8f5e9;
      border-color: #81c784;
    }

    .btn-choose-triwulan.tw-color-2 .tw-check-badge {
      color: #e65100;
      background: #fff3e0;
      border-color: #ffb74d;
    }

    .btn-choose-triwulan.tw-color-3 .tw-check-badge {
      color: #b71c1c;
      background: #ffebee;
      border-color: #ef9a9a;
    }

    .btn-choose-triwulan.tw-color-4 .tw-check-badge {
      color: #0d47a1;
      background: #e3f2fd;
      border-color: #90caf9;
    }

    .btn-choose-triwulan.is-selected {
      border-color: #00B5A0;
      box-shadow: 0 0 0 3px rgba(0, 181, 160, 0.15);
      background: #f1fffb;
    }

    .btn-choose-triwulan.is-disabled {
      background: #f8fafc;
      color: #667085;
      cursor: pointer;
      opacity: 0.95;
    }

    .btn-choose-triwulan.is-active-ready {
      border-color: #80cbc4;
      background: #f0fffc;
    }

    .btn-choose-triwulan.is-validated {
      border-color: #a5d6a7;
      background: #f1f8e9;
    }

    .btn-choose-triwulan.is-validated .tw-status {
      color: #2e7d32;
    }

    .btn-choose-triwulan.is-disabled .tw-status {
      color: #667085;
    }

    .validation-mini-summary {
      margin-top: 14px;
      border: 1px solid #d6ebe4;
      border-radius: 12px;
      background: linear-gradient(180deg, #ffffff, #f8fffd);
      padding: 12px;
    }

    .validation-mini-summary h6 {
      margin: 0 0 8px 0;
      font-size: 13px;
      font-weight: 700;
      color: #1B2A41;
    }

    .validation-mini-summary p {
      margin: 0;
      font-size: 12px;
      color: #52606d;
      line-height: 1.6;
    }

    .validation-mini-metrics {
      margin-top: 10px;
      display: grid;
      grid-template-columns: repeat(4, minmax(0, 1fr));
      gap: 8px;
    }

    .validation-mini-metric {
      border: 1px solid #e4efeb;
      border-radius: 8px;
      background: #fff;
      padding: 8px;
      text-align: center;
    }

    .validation-mini-metric .num {
      font-size: 14px;
      font-weight: 800;
      color: #1B2A41;
      line-height: 1;
    }

    .validation-mini-metric .label {
      margin-top: 4px;
      font-size: 10px;
      text-transform: uppercase;
      color: #7c8b95;
      letter-spacing: 0.03em;
      font-weight: 700;
    }

    @media (max-width: 768px) {
      .validation-triwulan-grid {
        grid-template-columns: 1fr;
      }

      .validation-mini-metrics {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
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
<?php
    $isWadir = auth()->check() && auth()->user()->isWadir();
    $activeSection = $activeSection ?? 'laporan';
  $sourceFrom = request()->get('from');
  $laporanBackRoute = $isWadir
    ? route('dashboard.wadir', ['panel' => 'laporan'])
    : route('home', ['section' => 'dashboard']);
?>
<body>
  <header>
    <div class="logo-container">
      <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo Pemda">
      <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD">
      <span class="header-title"><?php echo e($isWadir ? 'Dashboard Wakil Direktur' : 'Dashboard Pimpinan'); ?></span>
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
    <?php if($isWadir): ?>
      <?php echo $__env->make('dashboard.partials.wadir-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
      <?php echo $__env->make('dashboard.partials.pimpinan-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <main class="main-content">
    <div class="page-header">
      <div class="page-title"><?php echo e($activeSection === 'validasi' ? 'Validasi Laporan' : 'Laporan Kinerja'); ?></div>
      <?php if($activeSection !== 'validasi'): ?>
      <div class="page-subtitle">
        <?php echo e(($viewMode ?? 'form') === 'list' ? 'Daftar laporan kinerja per triwulan' : 'Form pengisian realisasi laporan kinerja per triwulan'); ?>

      </div>
      <?php endif; ?>
      <?php if($sourceFrom === 'dashboard_wadir_laporan'): ?>
      <div style="margin-top: 12px;">
        <a href="<?php echo e($laporanBackRoute); ?>" style="display:inline-flex;align-items:center;gap:8px;padding:9px 14px;border-radius:8px;border:1px solid #cfd8dc;background:#fff;color:#1B2A41;font-weight:700;text-decoration:none;">
          <i class="fas fa-arrow-left"></i>
          Kembali ke Dashboard
        </a>
      </div>
      <?php endif; ?>
    </div>

    <?php if(!$perjanjian || $message): ?>
      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span><?php echo e($message ?? 'Tidak ada perjanjian kinerja yang disetujui'); ?></span>
      </div>
      <div style="text-align: center; margin-top: 40px;">
        <a href="<?php echo e(route('perjanjian.index')); ?>" target="_self" class="btn btn-primary">
          <i class="fas fa-file-signature"></i>
          Buat Perjanjian Kinerja
        </a>
      </div>
    <?php else: ?>

      
      <?php if(($viewMode ?? 'form') === 'list'): ?>
        <?php
          $twColors  = [1 => '#5C6BC0', 2 => '#26A69A', 3 => '#EF6C00', 4 => '#AD1457'];
          $twPeriods = [1 => 'Jan – Mar', 2 => 'Apr – Jun', 3 => 'Jul – Sep', 4 => 'Okt – Des'];
          $laporan0  = $laporans->first(); // usually one laporan per perjanjian
          $tahunPk   = $perjanjian->tahun ?? date('Y');
        ?>

        
        <div style="background:#fff;border-radius:14px;padding:16px 20px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.07);display:flex;gap:20px;flex-wrap:wrap;align-items:center;">
          <div style="display:flex;align-items:center;gap:10px;">
            <i class="fas fa-user-circle" style="font-size:28px;color:#00B5A0;"></i>
            <div>
              <div style="font-weight:700;font-size:15px;color:#1B2A41;"><?php echo e($perjanjian->pihak1_name ?? '-'); ?></div>
              <div style="font-size:12px;color:#667085;"><?php echo e($perjanjian->pihak1_jabatan ?? '-'); ?></div>
            </div>
          </div>
          <div style="margin-left:auto;font-size:13px;color:#5f6f81;font-weight:600;">
            Perjanjian Kinerja <?php echo e($tahunPk); ?>

          </div>
        </div>

        
        <div style="display:flex;flex-direction:column;gap:14px;max-width:680px;margin:0 auto;">
          <?php for($tw = 1; $tw <= 4; $tw++): ?>
            <?php
              $color   = $twColors[$tw];
              $period  = $twPeriods[$tw];
              $realisasiKey  = 'realisasi_tb' . $tw;
              $realisasiData = optional($laporan0)->{$realisasiKey};
              $hasData       = !empty($realisasiData);

              // Compute status
              $statusLabel = 'Belum Diisi';
              $statusBg    = '#ECEFF1';
              $statusColor = '#607D8B';
              if ($laporan0 && !empty($laporan0->pihak2_signature) && $hasData) {
                $statusLabel = 'Disetujui'; $statusBg = '#E8F5E9'; $statusColor = '#2E7D32';
              } elseif ($laporan0 && !empty($laporan0->tanggapan_pimpinan) && empty($laporan0->kesimpulan) && $hasData) {
                $statusLabel = 'Ditolak'; $statusBg = '#FFEBEE'; $statusColor = '#C62828';
              } elseif ($laporan0 && !empty($laporan0->kesimpulan) && $hasData) {
                $statusLabel = 'Divalidasi'; $statusBg = '#E8F5E9'; $statusColor = '#2E7D32';
              } elseif ($hasData) {
                $statusLabel = 'Sudah Diisi'; $statusBg = '#E3F2FD'; $statusColor = '#1565C0';
              }

              $pdfUrl = $laporan0
                ? route('laporan.pdf.preview', ['id' => $laporan0->id, 'triwulan' => $tw])
                : null;
            ?>
            <div style="background:#fff;border-radius:16px;padding:18px 20px;box-shadow:0 2px 12px rgba(0,0,0,0.07);display:flex;align-items:center;gap:16px;border-left:5px solid <?php echo e($color); ?>;">
              
              <div style="width:52px;height:52px;background:<?php echo e($color); ?>;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-file-alt" style="color:#fff;font-size:22px;"></i>
              </div>
              
              <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:15px;color:#1B2A41;margin-bottom:4px;">
                  Laporan Kinerja <?php echo e($tahunPk); ?>

                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                  <span style="background:<?php echo e($statusBg); ?>;color:<?php echo e($statusColor); ?>;font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;">
                    <?php echo e($statusLabel); ?>

                  </span>
                  <span style="font-size:12px;color:#8f9ba5;">
                    Triwulan <?php echo e($tw); ?> &bull; <?php echo e($period); ?> <?php echo e($tahunPk); ?>

                  </span>
                </div>
              </div>
              
              <?php if($pdfUrl && $hasData): ?>
                <a href="<?php echo e($pdfUrl); ?>" target="_blank"
                   title="Preview PDF Laporan Triwulan <?php echo e($tw); ?>"
                   style="width:40px;height:40px;border-radius:50%;background:#E3F2FD;display:flex;align-items:center;justify-content:center;text-decoration:none;transition:background .2s;flex-shrink:0;"
                   onmouseover="this.style.background='#BBDEFB'" onmouseout="this.style.background='#E3F2FD'">
                  <i class="fas fa-eye" style="color:#1565C0;font-size:17px;"></i>
                </a>
              <?php else: ?>
                <div title="Realisasi belum diisi"
                     style="width:40px;height:40px;border-radius:50%;background:#F5F5F5;display:flex;align-items:center;justify-content:center;flex-shrink:0;cursor:default;">
                  <i class="fas fa-eye" style="color:#ccc;font-size:17px;"></i>
                </div>
              <?php endif; ?>
            </div>
          <?php endfor; ?>

          
          <?php if(!$laporan0): ?>
            <div style="text-align:center;padding:40px;color:#aaa;">
              <i class="fas fa-file-medical" style="font-size:48px;margin-bottom:12px;color:#ddd;display:block;"></i>
              <p style="font-size:14px;">Belum ada laporan kinerja untuk perjanjian ini.</p>
            </div>
          <?php endif; ?>
        </div>

      <?php else: ?>
      
      <?php if($activeSection !== 'validasi' && !(isset($editLaporanId) && $editLaporanId)): ?>
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
      <?php endif; ?>

      <?php if($activeSection === 'laporan'): ?>
        <?php if(isset($editLaporanId) && $editLaporanId): ?>
          
          <div id="editModeLoader" style="text-align:center;padding:80px 20px;color:#8f9ba5;">
            <i class="fas fa-spinner fa-spin" style="font-size:36px;margin-bottom:16px;display:block;color:#00B5A0;"></i>
            <p style="font-size:15px;font-weight:600;color:#1B2A41;">Memuat form edit laporan kinerja...</p>
          </div>
        <?php else: ?>
        
        <div class="triwulan-grid">
          <?php
            $twData = [
              1 => ['period' => 'Januari – Maret',   'color' => 'tw-1'],
              2 => ['period' => 'April – Juni',       'color' => 'tw-2'],
              3 => ['period' => 'Juli – September',   'color' => 'tw-3'],
              4 => ['period' => 'Oktober – Desember', 'color' => 'tw-4'],
            ];
          ?>
          <?php $__currentLoopData = $twData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $isActive = ($triwulanAktif == $tw); ?>
            <div class="tw-card <?php echo e($info['color']); ?> <?php echo e($isActive ? 'tw-active' : 'tw-inactive'); ?>" data-triwulan="<?php echo e($tw); ?>" role="button" tabindex="0" onclick="openTriwulanCard(<?php echo e($tw); ?>)">
              <div class="tw-card-badge <?php echo e($isActive ? 'tw-badge-active' : 'tw-badge-inactive'); ?>">
                <?php echo e($isActive ? 'Triwulan Aktif' : 'Tidak Aktif'); ?>

              </div>
              <div class="tw-card-num"><?php echo e($tw); ?></div>
              <div class="tw-card-title">Triwulan <?php echo e($tw); ?></div>
              <div class="tw-card-period"><?php echo e($info['period']); ?></div>
              <button type="button" class="tw-card-btn" data-triwulan="<?php echo e($tw); ?>" onclick="event.stopPropagation(); openTriwulanCard(<?php echo e($tw); ?>)">
                <i class="fas fa-eye"></i>
                <?php echo e($isActive ? 'Isi Laporan' : 'Lihat PDF'); ?>

              </button>
              <button type="button" class="tw-card-btn tw-card-btn-secondary" data-download-triwulan="<?php echo e($tw); ?>" onclick="event.stopPropagation(); downloadTriwulanPdf(<?php echo e($tw); ?>)">
                <i class="fas fa-download"></i>
                Download PDF
              </button>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?> 
      <?php endif; ?> 

      <?php if($activeSection === 'validasi'): ?>
        <div class="validation-panel show" id="validationPanel">
      <?php else: ?>
        <div class="validation-panel" id="validationPanel">
      <?php endif; ?>

          <div class="validation-header" style="justify-content:center;">
            <div class="validation-title" style="font-size:22px;">
              <i class="fas fa-shield-alt"></i>
              Validasi Laporan Per Triwulan
            </div>
          </div>

          <?php if($activeSection === 'validasi'): ?>
          <div class="triwulan-grid" style="margin-top:18px; margin-bottom:6px;">
            <?php
              $twData = [
                1 => ['period' => 'Januari – Maret',   'color' => 'tw-1'],
                2 => ['period' => 'April – Juni',       'color' => 'tw-2'],
                3 => ['period' => 'Juli – September',   'color' => 'tw-3'],
                4 => ['period' => 'Oktober – Desember', 'color' => 'tw-4'],
              ];
            ?>
            <?php $__currentLoopData = $twData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tw => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $isActive = ($triwulanAktif == $tw); ?>
              <div class="tw-card <?php echo e($info['color']); ?> <?php echo e($isActive ? 'tw-active' : 'tw-inactive'); ?>" data-validation-panel-tw="<?php echo e($tw); ?>" role="button" tabindex="0" onclick="handleValidationPanelTriwulanClick(<?php echo e($tw); ?>)">
                <div class="tw-card-badge <?php echo e($isActive ? 'tw-badge-active' : 'tw-badge-inactive'); ?>" data-panel-badge="<?php echo e($tw); ?>">
                  <?php echo e($isActive ? 'Triwulan Aktif' : 'Belum Aktif'); ?>

                </div>
                <div class="tw-card-num"><?php echo e($tw); ?></div>
                <div class="tw-card-title">Triwulan <?php echo e($tw); ?></div>
                <div class="tw-card-period"><?php echo e($info['period']); ?></div>
                <button type="button" class="tw-card-btn" data-panel-btn="<?php echo e($tw); ?>" onclick="event.stopPropagation(); handleValidationPanelTriwulanClick(<?php echo e($tw); ?>)">
                  <i class="fas fa-shield-alt"></i>
                  Status Validasi
                </button>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <div class="validation-actions" style="justify-content:center; border-top:none; margin-top:10px; padding-top:10px;">
            <button type="button" class="btn-validate" id="btnRunValidation" onclick="openValidationTriwulanModal()">
              <i class="fas fa-magic"></i> Validasi Laporan
            </button>
          </div>

      </div>

      <?php if($activeSection !== 'validasi'): ?>
        
    <?php endif; ?>
      <?php endif; ?> 
    <?php endif; ?> 
    </main>
  </div>

  <!-- Modal Pilih Triwulan Validasi -->
  <div class="modal fade" id="validationTriwulanModal" tabindex="-1" aria-labelledby="validationTriwulanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="validationTriwulanModalLabel">
            <i class="fas fa-calendar-check"></i>
            Pilih Triwulan Validasi
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
              <div class="validation-triwulan-grid" id="validationTriwulanGrid">
            <button type="button" class="btn-choose-triwulan tw-color-1" data-validate-tw="1">
              <div class="tw-title">Triwulan 1</div>
              <div class="tw-period">Januari - Maret</div>
              <div class="tw-status">Tidak Aktif</div>
              <div class="tw-check-badge"><i class="fas fa-check-circle"></i> Tervalidasi</div>
            </button>
            <button type="button" class="btn-choose-triwulan tw-color-2" data-validate-tw="2">
              <div class="tw-title">Triwulan 2</div>
              <div class="tw-period">April - Juni</div>
              <div class="tw-status">Tidak Aktif</div>
              <div class="tw-check-badge"><i class="fas fa-check-circle"></i> Tervalidasi</div>
            </button>
            <button type="button" class="btn-choose-triwulan tw-color-3" data-validate-tw="3">
              <div class="tw-title">Triwulan 3</div>
              <div class="tw-period">Juli - September</div>
              <div class="tw-status">Tidak Aktif</div>
              <div class="tw-check-badge"><i class="fas fa-check-circle"></i> Tervalidasi</div>
            </button>
            <button type="button" class="btn-choose-triwulan tw-color-4" data-validate-tw="4">
              <div class="tw-title">Triwulan 4</div>
              <div class="tw-period">Oktober - Desember</div>
              <div class="tw-status">Tidak Aktif</div>
              <div class="tw-check-badge"><i class="fas fa-check-circle"></i> Tervalidasi</div>
            </button>
          </div>
        </div>
        <div class="modal-footer" style="display:flex;gap:8px;justify-content:flex-end;">
          <button type="button" class="btn-secondary-alt" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="validationSummaryModal" tabindex="-1" aria-labelledby="validationSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="validationSummaryModalLabel">
            <i class="fas fa-file-circle-check"></i>
            Ringkasan Validasi
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div style="display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:10px; margin-bottom:12px;">
            <div class="validation-mini-metric"><div class="num" id="summaryScore">-</div><div class="label">Skor</div></div>
            <div class="validation-mini-metric"><div class="num" id="summaryIssues">0</div><div class="label">Issues</div></div>
            <div class="validation-mini-metric"><div class="num" id="summaryWarnings">0</div><div class="label">Warning</div></div>
            <div class="validation-mini-metric"><div class="num" id="summarySuggestions">0</div><div class="label">Saran</div></div>
          </div>
          <div class="validation-mini-summary" style="display:block; margin-top:0;">
            <h6 id="summaryTitle">Ringkasan Triwulan</h6>
            <p id="summaryText">Belum ada ringkasan validasi.</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit Realisasi -->
  <div class="modal fade" id="realisasiModal" tabindex="-1" aria-labelledby="realisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="realisasiModalLabel">
            <i class="fas fa-edit"></i>
            Form Pengisian Realisasi – <span id="modalTriwulanTitle">Triwulan</span>
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
                          $indicatorTypeRaw = strtolower($tabelB['indicator_type'][$index] ?? 'positif');
                          $indicatorType = in_array($indicatorTypeRaw, ['positif', 'negatif']) ? $indicatorTypeRaw : 'positif';
                        ?>
                        <tr>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($index + 1); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($sasaran ?? '-'); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">
                            <?php echo e($tabelB['indikator'][$index] ?? '-'); ?>

                            <div style="margin-top:4px; font-size:11px; color:#667085;">
                              Jenis: <strong><?php echo e(ucfirst($indicatorType)); ?></strong>
                            </div>
                          </td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><?php echo e($targetValue ?? '-'); ?></td>
                          <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">
                            <input type="text" inputmode="decimal" class="form-control row-realisasi-input" data-row="kinerja-<?php echo e($index); ?>" data-target="<?php echo e($targetValueNormalized); ?>" data-indicator-type="<?php echo e($indicatorType); ?>" placeholder="Realisasi" />
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
                              <input type="text" inputmode="decimal" class="form-control row-realisasi-input" data-row="anggaran-<?php echo e($programNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" />
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
                                <input type="text" inputmode="decimal" class="form-control row-realisasi-input" data-row="anggaran-<?php echo e($kegiatanNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" />
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
                              <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;"><input type="text" inputmode="decimal" class="form-control row-realisasi-input" data-row="anggaran-<?php echo e($subNo); ?>" data-target="<?php echo e($targetValueNormalized); ?>" placeholder="Realisasi" /></td>
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
              <div style="border:1px solid #e2e8f0; border-radius:10px; padding:12px; background:#fcfdff; margin-bottom:10px;">
                <div style="font-size:13px; font-weight:600; color:#1B2A41; margin-bottom:8px;">
                  Skala Ordinal Tingkat Capaian Kinerja dan Anggaran
                </div>
                <div style="overflow-x:auto;">
                  <table style="width:100%; border-collapse:collapse; font-size:12px;">
                    <thead>
                      <tr style="background:#f2d46a; color:#1B2A41;">
                        <th style="padding:8px; border:1px solid #d4b84f; text-align:center; width:44px;">No</th>
                        <th style="padding:8px; border:1px solid #d4b84f; text-align:center;">Interval Tingkat Capaian Kinerja dan Anggaran</th>
                        <th style="padding:8px; border:1px solid #d4b84f; text-align:center; width:210px;">Predikat</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">(1)</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">91% - 100% (atau lebih)</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">Sangat Tinggi (ST)</td>
                      </tr>
                      <tr>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">(2)</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">76% - 90%</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">Tinggi (T)</td>
                      </tr>
                      <tr>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">(3)</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">66% - 75%</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">Sedang (S)</td>
                      </tr>
                      <tr>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">(4)</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">51% - 65%</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">Rendah (R)</td>
                      </tr>
                      <tr>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">(5)</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">0% - 50%</td>
                        <td style="padding:7px; border:1px solid #e5e7eb; text-align:center;">Sangat Rendah (SR)</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <textarea class="form-control" id="realisasiInput" name="realisasi" rows="8" 
                        placeholder="Uraikan secara akademik hasil evaluasi capaian indikator kinerja dan realisasi anggaran berdasarkan skala predikat, termasuk analisis faktor pendukung/penghambat serta arah tindak lanjut perbaikan." required></textarea>
              <small class="text-muted">
                <i class="fas fa-lightbulb"></i>
                Minimal 50 karakter; sistem akan menyusun narasi evaluatif akademik berdasarkan capaian kinerja dan anggaran.
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
              </small>
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

  <!-- Modal BAB III Penutup -->
  <div class="modal fade" id="kesimpulanModal" tabindex="-1" aria-labelledby="kesimpulanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="kesimpulanModalLabel">
            <i class="fas fa-check-circle"></i>
            BAB III Penutup
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
                BAB III Penutup
                <span style="color: #e74c3c;">*</span>
              </label>
              <textarea class="form-control" id="kesimpulanInput" name="kesimpulan" rows="8"
                        placeholder="Tuliskan narasi penutup secara ringkas, objektif, dan operasional berdasarkan hasil kinerja triwulan berjalan." required></textarea>
              <small class="text-muted">
                <i class="fas fa-lightbulb"></i>
                Sistem menyusun narasi penutup otomatis berbasis hasil capaian triwulan berjalan.
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn-secondary-alt" onclick="backToRencanaFromKesimpulan()">
                <i class="fas fa-arrow-left"></i>
                Kembali
              </button>
              <button type="submit" class="btn-save">
                <i class="fas fa-check"></i>
                Simpan Laporan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer style="margin-top:40px;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

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
    function openTriwulanCard(triwulan) {
      const tw = Number(triwulan);
      if (tw === Number(triwulanAktif)) {
        openRealisasiModal(tw);
        return;
      }

      const twData = getStoredTriwulanData(tw);
      if (!twData || !hasNonZeroRealisasiRows(twData)) {
        showAlert('Dokumen PDF Triwulan ' + tw + ' belum tersedia karena realisasi masih 0 atau belum diisi.', 'error');
        return;
      }

      const pdfUrl = buildPerjanjianPdfPreviewUrl(tw);
      window.open(pdfUrl, '_blank');
    }

    function downloadTriwulanPdf(triwulan) {
      const tw = Number(triwulan);
      const twData = getStoredTriwulanData(tw);
      if (!twData || !hasNonZeroRealisasiRows(twData)) {
        showAlert('Dokumen PDF Triwulan ' + tw + ' belum tersedia karena realisasi masih 0 atau belum diisi.', 'error');
        return;
      }

      const downloadUrl = buildPerjanjianPdfDownloadUrl(tw);
      window.open(downloadUrl, '_blank');
    }

    function buildPerjanjianPdfPreviewUrl(triwulan) {
      const base = <?php echo json_encode(route('perjanjian.browsershot.preview', ['id' => $perjanjian->id ?? 0]), 512) ?>;
      const separator = base.includes('?') ? '&' : '?';
      return `${base}${separator}triwulan=${triwulan}`;
    }

    function buildPerjanjianPdfDownloadUrl(triwulan) {
      const base = <?php echo json_encode(route('perjanjian.browsershot.download', ['id' => $perjanjian->id ?? 0]), 512) ?>;
      const separator = base.includes('?') ? '&' : '?';
      return `${base}${separator}triwulan=${triwulan}`;
    }

    function getStoredTriwulanData(triwulan) {
      const store = Object.values(laporanRealisasi || {})[0] || {};
      const raw = store[String(triwulan)] || store[triwulan] || '';
      if (!raw) return null;
      try {
        return typeof raw === 'string' ? JSON.parse(raw) : raw;
      } catch (e) {
        return null;
      }
    }

    function hasNonZeroRealisasiRows(parsedData) {
      if (!parsedData || !Array.isArray(parsedData.rows)) return false;
      return parsedData.rows.some(row => {
        const value = Number(row && row.realisasi);
        return Number.isFinite(value) && value > 0;
      });
    }

    function openRealisasiModal(triwulan) {
      if (Number(triwulan) !== Number(triwulanAktif)) {
        showAlert('Input realisasi hanya dapat dilakukan pada Triwulan aktif yang ditetapkan admin.', 'error');
        return;
      }

      const modal = document.getElementById('realisasiModal');
      if (!modal) {
        console.error('Modal realisasi tidak ditemukan.');
        return;
      }

      if (!window.bootstrap || !bootstrap.Modal) {
        console.error('Bootstrap modal belum tersedia.');
        return;
      }

      if (!perjanjianData || !perjanjianData.id) {
        alert('Data perjanjian tidak ditemukan. Silakan refresh halaman.');
        return;
      }

      document.getElementById('perjanjianId').value = perjanjianData.id;
      document.getElementById('triwulanEdit').value = triwulan;

      const twPeriods = { 1:'Januari – Maret', 2:'April – Juni', 3:'Juli – September', 4:'Oktober – Desember' };
      document.getElementById('modalTriwulan').textContent = 'Triwulan ' + triwulan + ' (' + (twPeriods[triwulan] || '') + ')';
      const titleEl = document.getElementById('modalTriwulanTitle');
      if (titleEl) titleEl.textContent = 'Triwulan ' + triwulan + ' (' + (twPeriods[triwulan] || '') + ')';

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
      // If editing a specific laporan (edit mode), load from that laporan's data
      const laporanData = (EDIT_LAPORAN_ID !== null && laporanRealisasi[String(EDIT_LAPORAN_ID)])
        ? laporanRealisasi[String(EDIT_LAPORAN_ID)]
        : (Object.values(laporanRealisasi || {})[0] || {});
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
        input.addEventListener('blur', function(event) {
          const field = event.target;
          if ((field.dataset.row || '').startsWith('kinerja-')) {
            formatRealisasiSasaranInput(field, true);
            updatePercentage({ target: field });
            refreshComputedAnggaranRows();
            updateEvaluasiText();
            updateRencanaText();
            updateTanggapanAtasan();
          }
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

    const triwulanAktif   = Number(<?php echo e($triwulanAktif ?? 1); ?>);
    const EDIT_LAPORAN_ID = <?php echo e(isset($editLaporanId) ? $editLaporanId : 'null'); ?>;
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
    function calculatePercentage(target, realisasi, indicatorType = 'positif') {
      if (isNaN(target) || target === 0 || isNaN(realisasi)) {
        return null;
      }

      const type = String(indicatorType || 'positif').toLowerCase();
      if (type === 'negatif') {
        // Indikator negatif: ((Rencana - (Realisasi - Rencana)) / Rencana) * 100
        return ((target - (realisasi - target)) / target) * 100;
      }

      // Indikator positif: (Realisasi / Target) * 100
      return (realisasi / target) * 100;
    }

    function parseNumberValue(value) {
      if (value === null || value === undefined || value === '') {
        return NaN;
      }

      const raw = String(value).trim();
      if (raw === '') {
        return NaN;
      }

      if (raw.includes(',')) {
        return parseFloat(raw.replace(/\./g, '').replace(',', '.'));
      }

      if (raw.includes('.')) {
        const dotCount = (raw.match(/\./g) || []).length;
        if (dotCount > 1) {
          return parseFloat(raw.replace(/\./g, ''));
        }

        const [, decimalPart = ''] = raw.split('.');
        if (decimalPart.length > 0 && decimalPart.length <= 2) {
          return parseFloat(raw);
        }

        return parseFloat(raw.replace(/\./g, ''));
      }

      return parseFloat(raw);
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

    function splitIdNumericParts(raw, allowSingleDotAsDecimal = false) {
      const sanitized = String(raw || '').replace(/[^0-9.,]/g, '');
      if (sanitized === '') {
        return { integerPart: '', decimalPart: '', hasValue: false };
      }

      const hasComma = sanitized.includes(',');
      const dotCount = (sanitized.match(/\./g) || []).length;

      let separatorIndex = -1;
      if (hasComma) {
        separatorIndex = sanitized.lastIndexOf(',');
      } else if (allowSingleDotAsDecimal && dotCount === 1) {
        const dotIndex = sanitized.lastIndexOf('.');
        const decimals = sanitized.slice(dotIndex + 1).replace(/[^0-9]/g, '');
        if (decimals.length > 0 && decimals.length <= 2) {
          separatorIndex = dotIndex;
        }
      }

      let integerPart = '';
      let decimalPart = '';

      if (separatorIndex > -1) {
        integerPart = sanitized.slice(0, separatorIndex).replace(/[^0-9]/g, '');
        decimalPart = sanitized.slice(separatorIndex + 1).replace(/[^0-9]/g, '').slice(0, 2);
      } else {
        integerPart = sanitized.replace(/[^0-9]/g, '');
      }

      return { integerPart, decimalPart, hasValue: true };
    }

    function formatRealisasiAnggaranInput(input) {
      if (!input) return;
      const parts = splitIdNumericParts(input.value, false);
      if (!parts.hasValue) {
        input.value = '';
        return;
      }

      const integerPart = parts.integerPart === '' ? '0' : parts.integerPart;
      const formattedInt = parseInt(integerPart, 10).toLocaleString('id-ID');
      input.value = parts.decimalPart !== '' ? `${formattedInt},${parts.decimalPart}` : formattedInt;
    }

    function formatRealisasiSasaranInput(input, forceTwoDecimals = false) {
      if (!input) return;
      const rawSanitized = String(input.value || '').replace(/[^0-9.,]/g, '');
      const endsWithSeparator = /[.,]$/.test(rawSanitized);
      const parts = splitIdNumericParts(input.value, true);
      if (!parts.hasValue) {
        input.value = '';
        return;
      }

      const integerPart = parts.integerPart === '' ? '0' : parts.integerPart;
      const formattedInt = parseInt(integerPart, 10).toLocaleString('id-ID');
      let decimalPart = parts.decimalPart;
      if (forceTwoDecimals && decimalPart === '') {
        decimalPart = '00';
      }

      if (!forceTwoDecimals && decimalPart === '' && endsWithSeparator) {
        input.value = `${formattedInt},`;
        return;
      }

      input.value = decimalPart !== '' ? `${formattedInt},${decimalPart}` : formattedInt;
    }

    function setPercentageForRow(rowId, value, target, indicatorType = 'positif') {
      const percentageCell = document.getElementById('percentage-' + rowId);
      if (!percentageCell) return;
      const percentage = calculatePercentage(target, value, indicatorType);
      percentageCell.textContent = percentage === null ? '-' : percentage.toFixed(2) + '%';
    }

    function updatePercentage(event) {
      const input = event.target;
      if ((input.dataset.row || '').startsWith('anggaran-')) {
        formatRealisasiAnggaranInput(input);
      } else if ((input.dataset.row || '').startsWith('kinerja-')) {
        formatRealisasiSasaranInput(input, false);
      }
      const target = parseNumberValue(input.dataset.target || '0');
      const realisasi = parseNumberValue(input.value || '0');
      const indicatorType = (input.dataset.indicatorType || 'positif').toLowerCase();
      setPercentageForRow(input.dataset.row, realisasi, target, indicatorType);
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

    function getOrdinalScaleInfo(percentage) {
      const pct = parseFloat(percentage);
      if (isNaN(pct)) {
        return {
          label: 'Belum Terukur',
          code: '-',
          interval: 'Data belum mencukupi',
          interpretation: 'Data realisasi belum memadai untuk menghasilkan simpulan evaluatif yang andal.',
        };
      }

      if (pct >= 91) {
        return {
          label: 'Sangat Tinggi',
          code: 'ST',
          interval: '91% - 100% (atau lebih)',
          interpretation: 'Kinerja menunjukkan ketercapaian sasaran yang sangat optimal dengan efektivitas pelaksanaan program yang tinggi.',
        };
      }
      if (pct >= 76) {
        return {
          label: 'Tinggi',
          code: 'T',
          interval: '76% - 90%',
          interpretation: 'Kinerja berada pada tingkat baik dengan ketercapaian mayoritas sasaran, meskipun masih terdapat ruang penguatan pada beberapa indikator.',
        };
      }
      if (pct >= 66) {
        return {
          label: 'Sedang',
          code: 'S',
          interval: '66% - 75%',
          interpretation: 'Kinerja berada pada tingkat menengah dan memerlukan akselerasi implementasi agar target strategis dapat dicapai secara lebih konsisten.',
        };
      }
      if (pct >= 51) {
        return {
          label: 'Rendah',
          code: 'R',
          interval: '51% - 65%',
          interpretation: 'Kinerja relatif rendah sehingga dibutuhkan perbaikan manajerial, prioritisasi program, dan pengendalian pelaksanaan yang lebih ketat.',
        };
      }

      return {
        label: 'Sangat Rendah',
        code: 'SR',
        interval: '0% - 50%',
        interpretation: 'Kinerja sangat rendah dan memerlukan tindakan korektif segera melalui penataan strategi, sumber daya, dan mekanisme monitoring.',
      };
    }

    function setTanggapanAtasan(percentage) {
      const scale = getOrdinalScaleInfo(percentage);
      let tanggapanValue = '';

      if (scale.code === 'ST' || scale.code === 'T') {
        tanggapanValue = 'Laporan sudah baik';
      } else if (scale.code === 'S') {
        tanggapanValue = 'Laporan diperbaiki';
      } else if (scale.code === 'R') {
        tanggapanValue = 'Laporan diteliti ulang';
      } else {
        tanggapanValue = 'Capaian diteliti ulang';
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
      const triwulan = parseInt((document.getElementById('triwulanEdit') || {}).value || '1', 10);
      const triwulanText = triwulan === 1 ? 'triwulan pertama' :
                          triwulan === 2 ? 'triwulan kedua' :
                          triwulan === 3 ? 'triwulan ketiga' : 'triwulan keempat';
      const jabatanText = perjanjianData?.jabatan ? `pada jabatan ${perjanjianData.jabatan}` : '';

      const validKinerja = kinerjaInputs.map(input => {
        const target = parseNumberValue(input.dataset.target);
        const actual = parseNumberValue(input.value);
        const indicatorType = (input.dataset.indicatorType || 'positif').toLowerCase();
        const percentage = calculatePercentage(target, actual, indicatorType);
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
      const overallScale = getOrdinalScaleInfo(averagePercentage);
      const kinerjaScale = getOrdinalScaleInfo(averageKinerjaPercentage);
      const anggaranScale = getOrdinalScaleInfo(anggaranPercentage);
      const indicatorCountText = `${kinerjaCount}`;
      const measuredIndicatorCountText = `${validKinerja.length}`;
      const averageText = formatPercentageIDR(averageKinerjaPercentage);
      const totalAnggaranText = formatCurrencyIDR(totalAnggaranActual);
      const anggaranPercentText = formatPercentageIDR(anggaranPercentage);

      return `Berdasarkan hasil evaluasi capaian pada ${triwulanText} ${jabatanText}, pengukuran terhadap ${measuredIndicatorCountText} dari ${indicatorCountText} indikator sasaran kinerja menunjukkan rerata ketercapaian sebesar ${averageText}% dengan predikat ${kinerjaScale.label} (${kinerjaScale.code}). Dari sisi anggaran, realisasi tercatat sebesar ${totalAnggaranText} atau ${anggaranPercentText}% terhadap target, yang berada pada predikat ${anggaranScale.label} (${anggaranScale.code}). Secara komposit, rata-rata capaian kinerja dan anggaran adalah ${averagePercentageText}% dengan predikat ${overallScale.label} (${overallScale.code}). Temuan ini mengindikasikan bahwa ${overallScale.interpretation} Oleh karena itu, diperlukan penguatan tindak lanjut yang terukur, terutama pada indikator yang belum mencapai target, agar konsistensi kinerja pada periode berikutnya semakin meningkat.`;
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
      const triwulan = parseInt((document.getElementById('triwulanEdit') || {}).value || '1', 10);
      const triwulanText = triwulan === 1 ? 'triwulan pertama' :
                          triwulan === 2 ? 'triwulan kedua' :
                          triwulan === 3 ? 'triwulan ketiga' : 'triwulan keempat';

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
        const indicatorType = (input.dataset.indicatorType || 'positif').toLowerCase();
        const percentage = calculatePercentage(target, actual, indicatorType);
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

      const averagePercentage = (averageKinerjaPercentage + anggaranPercentage) / 2;
      const overallScale = getOrdinalScaleInfo(averagePercentage);
      const kinerjaScale = getOrdinalScaleInfo(averageKinerjaPercentage);
      const anggaranScale = getOrdinalScaleInfo(anggaranPercentage);

      const kinerjaGapTo91 = Math.max(0, 91 - averageKinerjaPercentage);
      const anggaranGapTo91 = Math.max(0, 91 - anggaranPercentage);

      const averageKinerjaText = formatPercentageIDR(averageKinerjaPercentage);
      const anggaranPercentageText = formatPercentageIDR(anggaranPercentage);
      const averageCompositeText = formatPercentageIDR(averagePercentage);
      const kinerjaGapText = formatPercentageIDR(kinerjaGapTo91);
      const anggaranGapText = formatPercentageIDR(anggaranGapTo91);

      const items = [];

      if (overallScale.code === 'ST') {
        items.push('Mempertahankan kinerja pada level sangat tinggi melalui standardisasi praktik baik, pemantauan periodik berbasis data, serta diseminasi pembelajaran kepada unit terkait.');
      } else if (overallScale.code === 'T') {
        items.push('Mengonsolidasikan capaian yang sudah tinggi melalui penguatan kontrol mutu pelaksanaan program dan perbaikan minor pada indikator yang belum stabil.');
      } else if (overallScale.code === 'S') {
        items.push('Melakukan akselerasi pencapaian melalui penajaman prioritas indikator utama, penguatan koordinasi lintas fungsi, serta evaluasi mingguan terhadap progres pelaksanaan.');
      } else if (overallScale.code === 'R') {
        items.push('Melaksanakan rencana pemulihan kinerja secara terstruktur dengan fokus pada akar masalah, penyesuaian strategi implementasi, dan pengendalian ketat atas target antara.');
      } else {
        items.push('Menetapkan tindakan korektif prioritas tinggi secara segera, termasuk restrukturisasi rencana kerja, optimalisasi sumber daya, serta pendampingan intensif pelaksanaan.');
      }

      if (kinerjaScale.code !== 'ST') {
        items.push(`Meningkatkan capaian indikator kinerja dari ${averageKinerjaText}% (predikat ${kinerjaScale.label}) dengan menutup gap ${kinerjaGapText}% menuju batas minimal predikat Sangat Tinggi (91%), melalui target mingguan yang terukur pada setiap indikator prioritas.`);
      } else {
        items.push('Menjaga konsistensi ketercapaian indikator kinerja pada predikat Sangat Tinggi melalui mekanisme early warning terhadap potensi deviasi capaian.');
      }

      if (totalAnggaranTarget > 0) {
        if (anggaranScale.code !== 'ST') {
          items.push(`Meningkatkan kualitas dan ketepatan realisasi anggaran dari ${anggaranPercentageText}% (predikat ${anggaranScale.label}) dengan menutup gap ${anggaranGapText}% menuju batas 91%, melalui percepatan proses belanja, sinkronisasi jadwal kegiatan, dan mitigasi hambatan administratif.`);
        } else {
          items.push('Mempertahankan efektivitas realisasi anggaran pada predikat Sangat Tinggi dengan tetap memastikan keselarasan antara output kegiatan dan pemanfaatan anggaran.');
        }
      }

      items.push('Memperkuat tata kelola tindak lanjut melalui forum evaluasi berkala pimpinan-unit kerja, penetapan penanggung jawab per indikator, serta pelaporan progres berbasis bukti untuk menjamin keberlanjutan peningkatan kinerja.');

      const listText = items.map((item, index) => `${index + 1}. ${item}`).join('\n');
      return `Berdasarkan hasil evaluasi Form C pada ${triwulanText}, dengan capaian komposit ${averageCompositeText}% (predikat ${overallScale.label}), rencana tindak lanjut ditetapkan sebagai berikut:\n${listText}`;
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
        const indicatorType = (input.dataset.indicatorType || 'positif').toLowerCase();
        const percentage = calculatePercentage(target, actual, indicatorType);
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
          if ((input.dataset.row || '').startsWith('anggaran-')) {
            formatRealisasiAnggaranInput(input);
          } else if ((input.dataset.row || '').startsWith('kinerja-')) {
            formatRealisasiSasaranInput(input, true);
          }
          setPercentageForRow(
            row.row,
            parseNumberValue(input.value),
            parseNumberValue(input.dataset.target || '0'),
            (input.dataset.indicatorType || 'positif').toLowerCase()
          );
        }
        const computed = document.querySelector('.computed-realisasi-value[data-row="' + row.row + '"]');
        if (computed && row.realisasi !== undefined) {
          computed.value = formatNumberValue(row.realisasi);
          setPercentageForRow(row.row, parseNumberValue(row.realisasi), parseNumberValue(computed.dataset.target || '0'), 'positif');
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
          const indicatorType = (input.dataset.indicatorType || 'positif').toLowerCase();
          const percentage = calculatePercentage(target, actual, indicatorType);
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

    function hideModalSafely(modalId, onHidden) {
      const modalEl = document.getElementById(modalId);
      if (!modalEl) return;

      const active = document.activeElement;
      if (active && modalEl.contains(active) && typeof active.blur === 'function') {
        active.blur();
      }

      const handleHidden = () => {
        modalEl.removeEventListener('hidden.bs.modal', handleHidden);
        if (typeof onHidden === 'function') {
          onHidden();
        }
      };

      modalEl.addEventListener('hidden.bs.modal', handleHidden, { once: true });
      const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
      instance.hide();
    }

    function showModal(modalId) {
      const modalEl = document.getElementById(modalId);
      if (!modalEl) return;
      const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
      instance.show();
    }

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

      // Set data in second modal
      document.getElementById('rencanaPerjanjianId').value = tempFormData.perjanjianId;
      document.getElementById('rencanaTrjulanEdit').value = tempFormData.triwulan;

      hideModalSafely('realisasiModal', () => showModal('rencanaTindakLanjutModal'));
    }

    function backToEvaluasi() {
      hideModalSafely('rencanaTindakLanjutModal', () => showModal('realisasiModal'));
    }

    // Fungsi untuk format angka Indonesia (pakai koma)
    function formatIndonesianNumber(value) {
      if (value === null || value === undefined || isNaN(value)) {
        return '0';
      }
      return value.toFixed(1).replace('.', ',');
    }

    // Fungsi untuk generate BAB III Penutup otomatis dari Form C dan D
    function generatePenutupSummary() {
      const triwulan = parseInt((document.getElementById('triwulanEdit') || {}).value || triwulanAktif, 10);
      const tahun = <?php echo e($perjanjian->tahun ?? date('Y')); ?>;
      const jabatan = perjanjianData.jabatan || 'jabatan terkait';
      
      // Hitung rata-rata kinerja
      const kinerjaInputs = Array.from(document.querySelectorAll('.row-realisasi-input[data-row^="kinerja-"]'));
      const validKinerja = kinerjaInputs.map(input => {
        const target = parseNumberValue(input.dataset.target);
        const actual = parseNumberValue(input.value);
        const indicatorType = (input.dataset.indicatorType || 'positif').toLowerCase();
        const percentage = calculatePercentage(target, actual, indicatorType);
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
      
      const overallScale = getOrdinalScaleInfo(overallPercentage);

      // Format triwulan teks
      const triwulanText = triwulan === 1 ? 'pertama' : triwulan === 2 ? 'kedua' : triwulan === 3 ? 'ketiga' : 'keempat';

      // Format persentase dengan koma
      const persentaseFormatted = formatPercentageIDR(overallPercentage);
      const arahPerbaikan = overallScale.code === 'ST'
        ? 'upaya tindak lanjut diarahkan untuk menjaga konsistensi mutu, memperluas praktik baik, dan memastikan keberlanjutan kinerja unggul.'
        : overallScale.code === 'T'
          ? 'upaya tindak lanjut diarahkan untuk menutup celah minor pada indikator tertentu serta memperkuat pengendalian pelaksanaan.'
          : overallScale.code === 'S'
            ? 'upaya tindak lanjut diarahkan pada akselerasi kinerja melalui penajaman prioritas, penguatan koordinasi, dan monitoring lebih intensif.'
            : 'upaya tindak lanjut diarahkan pada langkah korektif terstruktur, penataan strategi pelaksanaan, serta peningkatan disiplin monitoring dan evaluasi.';

      const kesimpulan = `BAB III PENUTUP\n\nBerdasarkan hasil pengukuran kinerja triwulan ${triwulan} (${triwulanText}) Tahun ${tahun} pada jabatan ${jabatan}, capaian komposit indikator kinerja dan anggaran mencapai ${persentaseFormatted}% dengan predikat ${overallScale.label} (${overallScale.code}). Capaian ini menjadi dasar evaluasi untuk memastikan kesinambungan peningkatan kualitas pelaksanaan program pada periode berikutnya.\n\nDengan demikian, ${arahPerbaikan} Pelaksanaan rencana perbaikan diharapkan mampu meningkatkan kualitas capaian pada periode berikutnya secara konsisten, akuntabel, dan berorientasi hasil.`;

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

      // Generate BAB III Penutup otomatis
      const kesimpulan = generatePenutupSummary();
      document.getElementById('kesimpulanInput').value = kesimpulan;
      lastAutoGeneratedText = kesimpulan;

      hideModalSafely('rencanaTindakLanjutModal', () => showModal('kesimpulanModal'));
    }

    function backToRencanaFromKesimpulan() {
      hideModalSafely('kesimpulanModal', () => showModal('rencanaTindakLanjutModal'));
    }

    // Final submission from BAB III Penutup
    document.getElementById('kesimpulanForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const rencanaTindakLanjut = document.getElementById('rencanaTindakLanjutInput').value;
      const tanggapanAtasan = document.querySelector('input[name="tanggapan_atasan"]:checked')?.value || '';
      const kesimpulan = document.getElementById('kesimpulanInput')?.value || '';
      
      if (!tempFormData.perjanjianId) {
        alert('Data tidak lengkap, silakan coba kembali');
        return;
      }

      if (kesimpulan.trim().length < 50) {
        alert('BAB III Penutup minimal harus 50 karakter');
        return;
      }
      
      fetch(`/api/realisasi/perjanjian`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('#kesimpulanForm input[name="_token"]').value
        },
        body: JSON.stringify({
          perjanjian_id: tempFormData.perjanjianId,
          triwulan: tempFormData.triwulan,
          realisasi: tempFormData.realisasi,
          realisasi_rows: tempFormData.rowRealisasi,
          rencana_tindak_lanjut: rencanaTindakLanjut,
          tanggapan_atasan: tanggapanAtasan,
          kesimpulan: kesimpulan,
          finalize: false,
        })
      })
      .then(async response => {
        const raw = await response.text();
        let data;
        try {
          data = raw ? JSON.parse(raw) : {};
        } catch (err) {
          data = { success: false, message: raw || 'Respons server tidak valid' };
        }

        if (!response.ok) {
          throw new Error(data.message || `HTTP ${response.status}`);
        }

        return data;
      })
      .then(data => {
        if (data.success) {
          // Close modal penutup
          hideModalSafely('kesimpulanModal');
          showAlert('Laporan kinerja berhasil disimpan.', 'success');
          
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

          setTimeout(() => {
            window.location.href = '<?php echo e(route("laporan.kinerja", ["section" => "laporan"])); ?>';
          }, 1200);
        } else {
          showAlert('Terjadi kesalahan: ' + data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menyimpan: ' + (error.message || 'Unknown error'), 'error');
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

    async function resolveLaporanId() {
      if (currentLaporanId) return currentLaporanId;
      let laporanId = getLaporanId();
      const perjanjianId = getPerjanjianId();

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

      currentLaporanId = laporanId || null;
      return currentLaporanId;
    }

    function getValidationStateKey(laporanId, triwulan) {
      const perjanjianId = getPerjanjianId() || 'unknown';
      const safeLaporanId = laporanId || 'none';
      return `validation_done:${perjanjianId}:${safeLaporanId}:${triwulan}`;
    }

    function getValidationSummaryKey(laporanId, triwulan) {
      const perjanjianId = getPerjanjianId() || 'unknown';
      const safeLaporanId = laporanId || 'none';
      return `validation_summary:${perjanjianId}:${safeLaporanId}:${triwulan}`;
    }

    function markValidationCompleted(laporanId, triwulan) {
      try {
        localStorage.setItem(getValidationStateKey(laporanId, triwulan), '1');
      } catch (e) {
        console.log('localStorage unavailable');
      }
    }

    function saveValidationSummary(laporanId, triwulan, validation) {
      try {
        const payload = {
          summary: validation.summary || '',
          score: Number(validation.score || 0),
          issues: Array.isArray(validation.issues) ? validation.issues.length : 0,
          warnings: Array.isArray(validation.warnings) ? validation.warnings.length : 0,
          suggestions: Array.isArray(validation.suggestions) ? validation.suggestions.length : 0,
          updatedAt: new Date().toISOString(),
        };
        localStorage.setItem(getValidationSummaryKey(laporanId, triwulan), JSON.stringify(payload));
      } catch (e) {
        console.log('Unable to store summary');
      }
    }

    function getValidationSummary(laporanId, triwulan) {
      try {
        const raw = localStorage.getItem(getValidationSummaryKey(laporanId, triwulan));
        if (!raw) return null;
        return JSON.parse(raw);
      } catch (e) {
        return null;
      }
    }

    function hasValidationCompleted(laporanId, triwulan) {
      try {
        return localStorage.getItem(getValidationStateKey(laporanId, triwulan)) === '1';
      } catch (e) {
        return false;
      }
    }

    async function updateValidationButtonState() {
      const btn = document.getElementById('btnRunValidation');
      if (!btn) return;

      const laporanId = await resolveLaporanId();
      const done = hasValidationCompleted(laporanId, triwulanAktif);

      btn.disabled = false;
      btn.classList.toggle('btn-validated', done);
      btn.innerHTML = `<i class="fas fa-magic"></i> Validasi Laporan`;

      refreshValidationPanelCards(laporanId);
    }

    function refreshValidationPanelCards(laporanId) {
      const cards = document.querySelectorAll('[data-validation-panel-tw]');
      if (!cards.length) return;

      cards.forEach(card => {
        const tw = Number(card.dataset.validationPanelTw);
        const badge = card.querySelector(`[data-panel-badge="${tw}"]`);
        const btn = card.querySelector(`[data-panel-btn="${tw}"]`);
        const isActive = tw === Number(triwulanAktif);
        const isValidated = hasValidationCompleted(laporanId, tw);

        card.classList.remove('tw-active', 'tw-inactive', 'tw-validated');
        if (isValidated) {
          card.classList.add('tw-validated');
          if (badge) {
            badge.className = 'tw-card-badge tw-badge-validated';
            badge.textContent = 'Sudah Tervalidasi';
          }
          if (btn) {
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Lihat Ringkasan';
          }
          return;
        }

        if (isActive) {
          card.classList.add('tw-active');
          if (badge) {
            badge.className = 'tw-card-badge tw-badge-active';
            badge.textContent = 'Triwulan Aktif';
          }
          if (btn) {
            btn.innerHTML = '<i class="fas fa-magic"></i> Siap Divalidasi';
          }
        } else {
          card.classList.add('tw-inactive');
          if (badge) {
            badge.className = 'tw-card-badge tw-badge-inactive';
            badge.textContent = 'Belum Aktif';
          }
          if (btn) {
            btn.innerHTML = '<i class="fas fa-clock"></i> Belum Aktif';
          }
        }
      });
    }

    async function handleValidationPanelTriwulanClick(triwulan) {
      const laporanId = await resolveLaporanId();
      const tw = Number(triwulan);

      if (hasValidationCompleted(laporanId, tw)) {
        await openValidationSummaryModal(laporanId, tw);
        return;
      }

      if (tw !== Number(triwulanAktif)) {
        showAlert(`Triwulan ${tw} belum aktif, belum dapat divalidasi.`, 'error');
        return;
      }

      openValidationTriwulanModal();
    }

    window.handleValidationPanelTriwulanClick = handleValidationPanelTriwulanClick;

    async function fetchValidationSummaryFromServer(laporanId, triwulan) {
      if (!laporanId) return null;
      try {
        const response = await fetch(`/api/laporan/${laporanId}/smart-validate`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            triwulan_aktif: Number(triwulan),
          })
        });

        const data = await response.json();
        if (data && data.success && data.validation) {
          markValidationCompleted(laporanId, Number(triwulan));
          saveValidationSummary(laporanId, Number(triwulan), data.validation);
          return getValidationSummary(laporanId, Number(triwulan));
        }
      } catch (e) {
        console.log('Unable to fetch validation summary from server');
      }
      return null;
    }

    async function openValidationSummaryModal(laporanId, triwulan) {
      let summary = getValidationSummary(laporanId, triwulan);
      const title = document.getElementById('summaryTitle');
      const text = document.getElementById('summaryText');
      const score = document.getElementById('summaryScore');
      const issues = document.getElementById('summaryIssues');
      const warnings = document.getElementById('summaryWarnings');
      const suggestions = document.getElementById('summarySuggestions');

      if (!title || !text || !score || !issues || !warnings || !suggestions) return;

      if (!summary) {
        summary = await fetchValidationSummaryFromServer(laporanId, triwulan);
        refreshValidationTriwulanOptions(laporanId);
        refreshValidationPanelCards(laporanId);
      }

      title.textContent = `Ringkasan Validasi Triwulan ${triwulan}`;
      if (!summary) {
        text.textContent = 'Data ringkasan validasi belum tersedia untuk triwulan ini.';
        score.textContent = '-';
        issues.textContent = '0';
        warnings.textContent = '0';
        suggestions.textContent = '0';
      } else {
        text.textContent = summary.summary || 'Ringkasan tidak tersedia.';
        score.textContent = `${summary.score || 0}/100`;
        issues.textContent = String(summary.issues || 0);
        warnings.textContent = String(summary.warnings || 0);
        suggestions.textContent = String(summary.suggestions || 0);
      }

      const showSummaryModal = () => {
        const modalEl = document.getElementById('validationSummaryModal');
        if (modalEl && window.bootstrap) {
          bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
      };

      const chooserModalEl = document.getElementById('validationTriwulanModal');
      if (chooserModalEl && window.bootstrap && chooserModalEl.classList.contains('show')) {
        chooserModalEl.addEventListener('hidden.bs.modal', showSummaryModal, { once: true });
        bootstrap.Modal.getOrCreateInstance(chooserModalEl).hide();
      } else {
        showSummaryModal();
      }
    }

    function refreshValidationTriwulanOptions(laporanId) {
      const options = document.querySelectorAll('.btn-choose-triwulan[data-validate-tw]');

      options.forEach(option => {
        const tw = Number(option.dataset.validateTw);
        const statusEl = option.querySelector('.tw-status');
        const checkEl = option.querySelector('.tw-check-badge');
        const isActive = tw === Number(triwulanAktif);
        const isValidated = hasValidationCompleted(laporanId, tw);

        option.classList.remove('is-selected', 'is-disabled', 'is-validated', 'is-active-ready');
        option.dataset.validationState = '';
        if (checkEl) checkEl.style.display = 'none';

        if (!isActive) {
          option.disabled = false;
          option.classList.add('is-disabled');
          option.dataset.validationState = 'inactive';
          if (statusEl) statusEl.textContent = 'Belum Aktif';
        } else if (isValidated) {
          option.disabled = false;
          option.classList.add('is-validated');
          option.dataset.validationState = 'validated';
          if (statusEl) statusEl.textContent = 'Sudah Divalidasi';
          if (checkEl) checkEl.style.display = 'inline-flex';
        } else {
          option.disabled = false;
          option.classList.add('is-active-ready');
          option.dataset.validationState = 'ready';
          if (statusEl) statusEl.textContent = 'Siap Divalidasi';
        }
      });

      refreshValidationPanelCards(laporanId);
    }

    async function openValidationTriwulanModal() {
      const modalEl = document.getElementById('validationTriwulanModal');
      if (!modalEl || !window.bootstrap) return;

      const laporanId = await resolveLaporanId();
      refreshValidationTriwulanOptions(laporanId);

      const instance = bootstrap.Modal.getOrCreateInstance(modalEl);
      instance.show();
    }

    window.openValidationTriwulanModal = openValidationTriwulanModal;

    async function handleValidationTriwulanClick(optionEl) {
      if (!optionEl) return;
      const triwulan = Number(optionEl.dataset.validateTw);
      const state = optionEl.dataset.validationState;
      const laporanId = await resolveLaporanId();

      if (state === 'inactive') {
        showAlert(`Triwulan ${triwulan} belum aktif, belum dapat divalidasi.`, 'error');
        return;
      }

      if (state === 'validated') {
        await openValidationSummaryModal(laporanId, triwulan);
        return;
      }

      const originalStatus = optionEl.querySelector('.tw-status')?.textContent || '';
      const statusEl = optionEl.querySelector('.tw-status');
      optionEl.disabled = true;
      if (statusEl) statusEl.textContent = 'Memvalidasi...';
      await runSmartValidation(triwulan);
      optionEl.disabled = false;
      if (statusEl && optionEl.dataset.validationState === 'ready') {
        statusEl.textContent = originalStatus;
      }
    }
    
    async function runSmartValidation(selectedTriwulan = triwulanAktif) {
      const triwulanToValidate = Number(selectedTriwulan || triwulanAktif);
      if (triwulanToValidate !== Number(triwulanAktif)) {
        showAlert('Hanya triwulan aktif yang dapat divalidasi.', 'error');
        return;
      }

      const laporanId = await resolveLaporanId();
      
      if (!laporanId) {
        showAlert('Belum ada data laporan yang bisa divalidasi. Isi laporan kinerja terlebih dahulu.', 'error');
        return;
      }
      
      try {
        const response = await fetch(`/api/laporan/${laporanId}/smart-validate`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            triwulan_aktif: triwulanToValidate,
          })
        });
        
        const data = await response.json();
        
        if (data.success) {
          markValidationCompleted(laporanId, triwulanToValidate);
          saveValidationSummary(laporanId, triwulanToValidate, data.validation || {});
          refreshValidationTriwulanOptions(laporanId);
          await updateValidationButtonState();
          showAlert(`Validasi Triwulan ${triwulanToValidate} selesai.`, 'success');
          await openValidationSummaryModal(laporanId, triwulanToValidate);
        } else {
          showAlert(data.message || 'Terjadi kesalahan saat validasi', 'error');
        }
      } catch (error) {
        showAlert('Terjadi kesalahan koneksi: ' + error.message, 'error');
      }
    }
    
    function displayValidationResults(validation) {
      const results = document.getElementById('validationResults');
      const scoreSection = document.getElementById('validationScore');
      const scoreValue = document.getElementById('scoreValue');
      const scoreCircle = document.getElementById('scoreCircle');

      // ── Populate summary cards ──
      const summaryGrid = document.getElementById('valSummaryGrid');
      const detailHeader = document.getElementById('valDetailHeader');
      if (summaryGrid) {
        const totalIssues     = (validation.issues      ? validation.issues.length      : 0);
        const totalWarnings   = (validation.warnings    ? validation.warnings.length    : 0);
        const totalSuggestions= (validation.suggestions ? validation.suggestions.length : 0);
        const totalItems      = totalIssues + totalWarnings + totalSuggestions;
        const highIssues      = validation.issues ? validation.issues.filter(i => i.severity === 'high').length : 0;
        const validItems      = Math.max(0, totalItems - totalIssues);

        document.getElementById('sumTotal').textContent   = totalItems;
        document.getElementById('sumValid').textContent   = validItems;
        document.getElementById('sumInvalid').textContent = highIssues;
        document.getElementById('sumRevisi').textContent  = totalSuggestions;
        document.getElementById('sumWarning').textContent = totalWarnings;
      }
      if (detailHeader) detailHeader.style.display = 'flex';
      
      // Show score
      scoreSection.style.display = 'flex';
      scoreValue.textContent = validation.score + '/100';
      
      // Set score circle color
      scoreCircle.className = 'score-circle';
      if (validation.score >= 90) {
        scoreCircle.classList.add('score-excellent');
      } else if (validation.score >= 75) {
        scoreCircle.classList.add('score-good');
      } else if (validation.score >= 60) {
        scoreCircle.classList.add('score-warning');
      } else {
        scoreCircle.classList.add('score-danger');
      }
      scoreCircle.textContent = '';
      
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

    document.addEventListener('DOMContentLoaded', function () {
      // Ensure triwulan cards and inner buttons always open the realisasi modal.
      document.addEventListener('click', function (event) {
        const trigger = event.target.closest('[data-triwulan]');
        if (!trigger) {
          return;
        }
        const card = trigger.classList.contains('tw-card') ? trigger : trigger.closest('.tw-card');
        if (!card) {
          return;
        }
        const triwulan = parseInt(trigger.dataset.triwulan || card.dataset.triwulan, 10);
        if (!Number.isNaN(triwulan)) {
          openRealisasiModal(triwulan);
        }
      });

      document.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter' && event.key !== ' ') {
          return;
        }
        if (!event.target.classList || !event.target.classList.contains('tw-card')) {
          return;
        }
        const card = event.target.closest('.tw-card[data-triwulan]');
        if (!card) {
          return;
        }
        event.preventDefault();
        const triwulan = parseInt(card.dataset.triwulan, 10);
        if (!Number.isNaN(triwulan)) {
          openRealisasiModal(triwulan);
        }
      });

      document.querySelectorAll('.btn-choose-triwulan[data-validate-tw]').forEach(option => {
        option.addEventListener('click', function () {
          handleValidationTriwulanClick(option);
        });
      });

      updateValidationButtonState();

      if (window.location.search.includes('section=validasi') || window.location.hash === '#validasi-laporan') {
        document.getElementById('validationPanel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }

      // Edit mode: auto-open the realisasi modal pre-filled with existing data
      if (EDIT_LAPORAN_ID !== null) {
        setTimeout(function () {
          openRealisasiModal(triwulanAktif);
        }, 400);

        // When realisasiModal closes in edit mode, redirect — BUT only if no
        // laporan-entry modal (rencana / penutup) opened as continuation.
        var realisasiModalEl = document.getElementById('realisasiModal');
        if (realisasiModalEl) {
          realisasiModalEl.addEventListener('hidden.bs.modal', function () {
            // Delay to allow the next modal (rencanaTindakLanjutModal / kesimpulanModal)
            // to become visible before we decide to redirect.
            setTimeout(function () {
              var anyOpen = document.querySelector(
                '#realisasiModal.show, #rencanaTindakLanjutModal.show, #kesimpulanModal.show'
              );
              if (!anyOpen) {
                window.location.href = '<?php echo e(route("laporan.wadir.index", ["from" => "dashboard_wadir_laporan"])); ?>';
              }
            }, 700);
          });
        }
      }
    });
  </script>
</body>
</html>








<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/laporan-kinerja.blade.php ENDPATH**/ ?>