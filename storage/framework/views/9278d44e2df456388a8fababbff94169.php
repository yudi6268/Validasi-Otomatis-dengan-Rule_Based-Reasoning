<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard Direktur - RSUD Bangil</title>
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

        /* ===== NEW PANEL SYSTEM ===== */
        body { overflow: auto; }
        .sidebar-menu a { cursor: pointer; }
        .sidebar-menu a.logout-link { color: #e53e3e; }
        .sidebar-menu a.logout-link i { color: #e53e3e; }
        .sidebar-menu a.logout-link:hover { background: #fff5f5; border-left-color: #e53e3e; color: #e53e3e; }

        /* Summary panels */
        .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .summary-section { background: #fff; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .summary-section .ss-title { font-size: 14px; font-weight: 800; color: #1B2A41; margin-bottom: 14px; display: flex; align-items: center; gap: 8px; }
        .summary-stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; }
        .ss-card { background: #f8fafb; border-radius: 10px; padding: 12px; text-align: center; border-top: 3px solid #e0e0e0; }
        .ss-card.green { border-top-color: #00b59a; }
        .ss-card.yellow { border-top-color: #f5a623; }
        .ss-card.blue { border-top-color: #4c9cf0; }
        .ss-card.red { border-top-color: #e53e3e; }
        .ss-num { font-size: 22px; font-weight: 800; color: #1B2A41; }
        .ss-lbl { font-size: 11px; color: #888; font-weight: 600; margin-top: 2px; }

        /* Stat cards - panel level */
        .panel-stat-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 20px; }
        .panel-stat-card { background: #fff; border-radius: 12px; padding: 18px 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); text-align: center; cursor: pointer; transition: all 0.2s; border-top: 4px solid #e0e0e0; }
        .panel-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .panel-stat-card.green { border-top-color: #00b59a; }
        .panel-stat-card.yellow { border-top-color: #f5a623; }
        .panel-stat-card.blue { border-top-color: #4c9cf0; }
        .panel-stat-card.red { border-top-color: #e53e3e; }
        .panel-stat-num { font-size: 30px; font-weight: 800; color: #1B2A41; }
        .panel-stat-lbl { font-size: 12px; color: #888; font-weight: 600; margin-top: 4px; }
        .panel-stat-btn { display: inline-block; margin-top: 8px; font-size: 11px; font-weight: 700; color: #00b59a; background: #e8f7f4; padding: 3px 12px; border-radius: 6px; }

        /* Dashboard tables */
        .dash-section { background: #fff; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 18px; }
        .dash-section-hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }
        .dash-section-hdr h3 { font-size: 15px; font-weight: 800; color: #1B2A41; }
        .dash-see-all { font-size: 12px; font-weight: 700; color: #00b59a; text-decoration: none; }
        .dash-table { width: 100%; border-collapse: collapse; }
        .dash-table th { background: #f8fafb; padding: 9px 12px; text-align: left; font-size: 12px; font-weight: 700; color: #444; border-bottom: 2px solid #e8e8e8; }
        .dash-table td { padding: 9px 12px; border-bottom: 1px solid #f0f0f0; font-size: 13px; color: #555; vertical-align: middle; }
        .dash-table tr:last-child td { border-bottom: none; }
        .dash-table tr:hover td { background: #fafcff; }
        .dash-table .empty td { text-align: center; padding: 24px; color: #aaa; }

        /* Charts */
        .chart-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 18px; }
        .chart-panel { background: #fff; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .chart-panel h3 { font-size: 14px; font-weight: 800; color: #1B2A41; margin-bottom: 14px; }
        .chart-box { position: relative; height: 220px; }

        /* Status badge */
        .s-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; }
        .s-green { background: #e8f7f4; color: #009970; }
        .s-yellow { background: #fff3e0; color: #e07b00; }
        .s-blue { background: #e3f0fd; color: #2176d2; }
        .s-red { background: #fde8e8; color: #c0392b; }
        .s-gray { background: #f0f0f0; color: #666; }

        /* Buttons */
        .btn-xs { display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; border-radius: 7px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; transition: all 0.18s; }
        .btn-teal { background: #00b59a; color: #fff; } .btn-teal:hover { background: #009980; }
        .btn-danger { background: #e53e3e; color: #fff; } .btn-danger:hover { background: #c0392b; }
        .btn-neutral { background: #f0f0f0; color: #555; } .btn-neutral:hover { background: #e0e0e0; }

        /* Modal system */
        .dir-modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 2000; align-items: center; justify-content: center; }
        .dir-modal.open { display: flex !important; }
        .dir-mbox { background: #fff; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.18); width: min(720px, 94vw); max-height: 86vh; display: flex; flex-direction: column; overflow: hidden; }
        .dir-mbox.sm { width: min(480px, 94vw); }
        .dir-mbox.xl { width: min(1000px, 96vw); height: 88vh; }
        .dir-mhdr { display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; border-bottom: 1px solid #f0f0f0; flex-shrink: 0; }
        .dir-mhdr h3 { font-size: 16px; font-weight: 800; color: #1B2A41; margin: 0; }
        .dir-mbody { padding: 16px 20px; overflow-y: auto; flex: 1; }
        .dir-mftr { padding: 12px 20px; border-top: 1px solid #f0f0f0; display: flex; justify-content: flex-end; gap: 10px; flex-shrink: 0; }
        .dir-close { border: none; background: #f0f0f0; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; font-size: 13px; color: #666; }
        .item-row { display: flex; align-items: flex-start; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f5f5f5; gap: 14px; }
        .item-row:last-child { border-bottom: none; }
        .item-name { font-size: 14px; font-weight: 700; color: #1B2A41; }
        .item-meta { font-size: 12px; color: #888; margin-top: 2px; }
        .item-actions { display: flex; gap: 6px; flex-shrink: 0; margin-top: 2px; }

        @media (max-width: 900px) { .summary-grid, .chart-grid { grid-template-columns: 1fr; } .panel-stat-grid { grid-template-columns: repeat(2,1fr); } .summary-stat-row { grid-template-columns: repeat(2,1fr); } }

        /* ===== DASHBOARD PANEL - WELCOME & KPI ===== */
        .dir-welcome-banner { background: linear-gradient(135deg, #00b59a 0%, #00927c 60%, #007360 100%); border-radius: 16px; padding: 22px 26px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; color: #fff; box-shadow: 0 6px 20px rgba(0,181,154,0.28); }
        .dir-welcome-left h2 { font-size: 20px; font-weight: 900; margin: 0 0 4px; }
        .dir-welcome-left p { font-size: 13px; opacity: .82; margin: 0; }
        .dir-alert-pill { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 30px; font-size: 13px; font-weight: 700; border: 1.5px solid rgba(255,255,255,.4); background: rgba(255,255,255,.15); backdrop-filter: blur(4px); }
        .dir-alert-pill.ok { border-color: rgba(255,255,255,.3); background: rgba(255,255,255,.1); }
        .dir-alert-pill i { font-size: 16px; }

        /* KPI row */
        .dir-kpi-block { margin-bottom: 14px; }
        .dir-kpi-label { font-size: 11px; font-weight: 800; color: #888; letter-spacing: .07em; text-transform: uppercase; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .dir-kpi-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; }
        .dir-kpi-card { background: #fff; border-radius: 14px; padding: 16px 14px; box-shadow: 0 2px 10px rgba(0,0,0,.06); display: flex; align-items: center; gap: 14px; text-decoration: none; color: inherit; transition: transform .18s, box-shadow .18s; position: relative; overflow: hidden; border-left: 4px solid #e0e0e0; }
        .dir-kpi-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.11); }
        .dir-kpi-card.c-teal  { border-left-color: #00b59a; }
        .dir-kpi-card.c-green { border-left-color: #38a169; }
        .dir-kpi-card.c-amber { border-left-color: #f5a623; }
        .dir-kpi-card.c-red   { border-left-color: #e53e3e; }
        .dir-kpi-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .c-teal  .dir-kpi-icon { background: #e6f9f5; color: #00b59a; }
        .c-green .dir-kpi-icon { background: #e8f5ef; color: #38a169; }
        .c-amber .dir-kpi-icon { background: #fff8ec; color: #f5a623; }
        .c-red   .dir-kpi-icon { background: #fde8e8; color: #e53e3e; }
        .dir-kpi-num { font-size: 26px; font-weight: 900; color: #1B2A41; line-height: 1; }
        .dir-kpi-lbl { font-size: 11px; font-weight: 700; color: #999; margin-top: 3px; }
        .dir-kpi-pulse { position: absolute; top: 10px; right: 10px; width: 9px; height: 9px; border-radius: 50%; background: #f5a623; animation: kpiPulse 1.7s infinite; }
        @keyframes kpiPulse { 0%,100%{box-shadow:0 0 0 0 rgba(245,166,35,.5);} 55%{box-shadow:0 0 0 7px rgba(245,166,35,0);} }

        /* Preview lists (no actions) */
        .dir-preview-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px; }
        .dir-preview-list { display: flex; flex-direction: column; }
        .dir-preview-item { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f5f5f5; }
        .dir-preview-item:last-child { border-bottom: none; }
        .dir-avatar { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg,#00b59a,#007360); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:800; flex-shrink:0; }
        .dir-avatar.blue { background: linear-gradient(135deg,#4c9cf0,#2176d2); }
        .dir-preview-body { flex:1; min-width:0; }
        .dir-preview-name { font-size:13px; font-weight:700; color:#1B2A41; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .dir-preview-meta { font-size:11px; color:#aaa; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .dir-preview-side { display:flex; flex-direction:column; align-items:flex-end; gap:3px; flex-shrink:0; }
        .dir-preview-date { font-size:10px; color:#bbb; }
        .dir-empty-ok { display:flex; align-items:center; justify-content:center; gap:8px; padding:26px; color:#38a169; font-size:13px; font-weight:600; background:#f0fff8; border-radius:10px; }
        .dir-empty-ok i { font-size:18px; }

        @media (max-width: 900px) { .dir-kpi-row { grid-template-columns: repeat(2,1fr); } .dir-preview-grid { grid-template-columns: 1fr; } .dir-welcome-banner { flex-direction: column; gap: 14px; } }

        /* ===== WADIR-STYLE PANEL SHELL (perjanjian & laporan panels) ===== */
        .dir-panel-shell { max-width: 760px; margin: 0 auto; background: linear-gradient(180deg,#f8fffc 0%,#f2fbf8 100%); border: 1px solid #dff3ed; border-radius: 20px; padding: 18px 20px 24px; box-shadow: 0 14px 28px rgba(0,153,112,.08); }
        .dir-panel-shell .panel-stat-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; margin-bottom: 14px; }
        .dir-psc { background: #fff; border-radius: 14px; padding: 20px 14px 18px; box-shadow: 0 4px 14px rgba(0,0,0,.08); text-align: center; min-height: 168px; display: flex; flex-direction: column; justify-content: center; align-items: center; cursor: pointer; transition: transform .2s, box-shadow .2s; }
        .dir-psc:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(0,0,0,.11); }
        .dir-psc-num { font-size: 46px; font-weight: 800; line-height: 1; margin-bottom: 8px; }
        .dir-psc-lbl { font-size: 14px; font-weight: 600; color: #666; margin-bottom: 12px; }
        .dir-psc-btn { display: inline-block; padding: 7px 20px; border-radius: 999px; color: #fff; font-weight: 700; font-size: 13px; text-decoration: none; cursor: pointer; transition: opacity .2s; border: none; }
        .dir-psc-btn:hover { opacity: .85; color: #fff; }
        .dir-psc.d-green .dir-psc-num { color: #009970; } .dir-psc.d-green .dir-psc-btn { background: #009970; }
        .dir-psc.d-yellow .dir-psc-num { color: #FFA500; } .dir-psc.d-yellow .dir-psc-btn { background: #FFA500; }
        .dir-psc.d-blue .dir-psc-num { color: #2196F3; } .dir-psc.d-blue .dir-psc-btn { background: #2196F3; }
        .dir-psc.d-red .dir-psc-num { color: #DC3545; } .dir-psc.d-red .dir-psc-btn { background: #DC3545; }
        @media (max-width: 860px) { .dir-panel-shell { padding: 14px; } .dir-panel-shell .panel-stat-grid { grid-template-columns: 1fr; } .dir-psc { min-height: 140px; } }

        /* ===== LIST CARD MODAL ITEMS ===== */
        .dir-list-wrap { background: #E3F8F6; padding: 10px 0; min-height: 60px; }
        .dir-list-card { background: #fff; border-radius: 12px; padding: 20px 22px; margin-bottom: 14px; display: flex; align-items: center; gap: 18px; box-shadow: 0 2px 8px rgba(0,0,0,.08); transition: box-shadow .2s; }
        .dir-list-card:last-child { margin-bottom: 0; }
        .dir-list-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.13); }
        .dir-doc-icon { width: 64px; height: 64px; background: #2196F3; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .dir-doc-icon i { color: #fff; font-size: 28px; }
        .dir-doc-icon.lk { background: #2196F3; }
        .dir-list-info { flex: 1; min-width: 0; }
        .dir-list-name { font-size: 16px; font-weight: 700; color: #222; margin-bottom: 4px; }
        .dir-list-badge { display: inline-block; margin: 4px 0 4px; padding: 5px 14px; border-radius: 12px; font-size: 13px; font-weight: 600; }
        .dlb-menunggu { background: #fff3cd; color: #FFA500; }
        .dlb-disetujui{ background: #d4edda; color: #009970; }
        .dlb-ditolak  { background: #f8d7da; color: #DC3545; }
        .dlb-terkirim { background: #e2e8f0; color: #475569; }
        .dlb-proses   { background: #fff3cd; color: #FFA500; }
        .dlb-setuju   { background: #d4edda; color: #009970; }
        .dlb-tolak    { background: #f8d7da; color: #DC3545; }
        .dir-list-date { font-size: 13px; color: #888; margin-top: 2px; }
        .dir-list-action { flex-shrink: 0; }
        .dir-view-btn { background: none; border: none; cursor: pointer; font-size: 24px; color: #2196F3; padding: 8px; transition: opacity .2s; line-height: 1; }
        .dir-view-btn:hover { opacity: .7; }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <div class="logo-container">
            <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo Pemda">
            <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD">
            <span class="header-title">Dashboard Direktur</span>
        </div>
        <div></div>
    </header>

    <form id="logoutForm" action="<?php echo e(route('logout')); ?>" method="POST" style="display:none;"><?php echo csrf_field(); ?></form>

    <!-- MAIN DASHBOARD -->
    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php $activePanel = request()->query('panel', 'dashboard'); ?>
        <aside class="sidebar">
            <h3>Menu</h3>
            <div class="sidebar-menu">
                <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'dashboard'])); ?>"
                   class="<?php echo e($activePanel === 'dashboard' ? 'active' : ''); ?>">
                    <i class="fas fa-th-large"></i> Dashboard
                </a>
                <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>"
                   class="<?php echo e($activePanel === 'perjanjian' ? 'active' : ''); ?>">
                    <i class="fas fa-file-contract"></i> Perjanjian Kinerja
                </a>
                <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'laporan'])); ?>"
                   class="<?php echo e($activePanel === 'laporan' ? 'active' : ''); ?>">
                    <i class="fas fa-chart-bar"></i> Laporan Kinerja
                </a>
                <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'profil'])); ?>"
                   class="<?php echo e($activePanel === 'profil' ? 'active' : ''); ?>">
                    <i class="fas fa-user"></i> Profil
                </a>
                <a href="#" class="logout-link"
                   onclick="event.preventDefault(); document.getElementById('dirLogoutModal').style.display='flex';">
                    <i class="fas fa-right-from-bracket"></i> Keluar
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <?php $panel = request()->query('panel', 'dashboard'); ?>

            
            <?php if($panel === 'dashboard'): ?>
                <?php $totalMenunggu = $perjanjianCounts['menunggu'] + $laporanCounts['menunggu']; ?>

                <!-- Welcome Banner -->
                <div class="dir-welcome-banner">
                    <div class="dir-welcome-left">
                        <h2>Selamat Datang, <?php echo e(auth()->user()->nama); ?></h2>
                        <p><?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></p>
                    </div>
                    <div>
                        <?php if($totalMenunggu > 0): ?>
                            <div class="dir-alert-pill">
                                <i class="fas fa-bell"></i>
                                <?php echo e($totalMenunggu); ?> item menunggu tindakan
                            </div>
                        <?php else: ?>
                            <div class="dir-alert-pill ok">
                                <i class="fas fa-check-circle"></i>
                                Semua sudah ditindaklanjuti
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- KPI – Perjanjian Kinerja -->
                <div class="dir-kpi-block">
                    <div class="dir-kpi-label"><i class="fas fa-file-contract"></i> Perjanjian Kinerja</div>
                    <div class="dir-kpi-row">
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>" class="dir-kpi-card c-teal">
                            <div class="dir-kpi-icon"><i class="fas fa-layer-group"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($perjanjianCounts['total']); ?>">0</div>
                                <div class="dir-kpi-lbl">Total</div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>" class="dir-kpi-card c-green">
                            <div class="dir-kpi-icon"><i class="fas fa-check-double"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($perjanjianCounts['disetujui']); ?>">0</div>
                                <div class="dir-kpi-lbl">Disetujui</div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>" class="dir-kpi-card c-amber">
                            <div class="dir-kpi-icon"><i class="fas fa-hourglass-half"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($perjanjianCounts['menunggu']); ?>">0</div>
                                <div class="dir-kpi-lbl">Menunggu</div>
                            </div>
                            <?php if($perjanjianCounts['menunggu'] > 0): ?><span class="dir-kpi-pulse"></span><?php endif; ?>
                        </a>
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>" class="dir-kpi-card c-red">
                            <div class="dir-kpi-icon"><i class="fas fa-times-circle"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($perjanjianCounts['ditolak']); ?>">0</div>
                                <div class="dir-kpi-lbl">Ditolak</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- KPI – Laporan Kinerja -->
                <div class="dir-kpi-block">
                    <div class="dir-kpi-label"><i class="fas fa-chart-bar"></i> Laporan Kinerja</div>
                    <div class="dir-kpi-row">
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'laporan'])); ?>" class="dir-kpi-card c-teal">
                            <div class="dir-kpi-icon"><i class="fas fa-layer-group"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($laporanCounts['total']); ?>">0</div>
                                <div class="dir-kpi-lbl">Total</div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'laporan'])); ?>" class="dir-kpi-card c-green">
                            <div class="dir-kpi-icon"><i class="fas fa-check-double"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($laporanCounts['disetujui']); ?>">0</div>
                                <div class="dir-kpi-lbl">Disetujui</div>
                            </div>
                        </a>
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'laporan'])); ?>" class="dir-kpi-card c-amber">
                            <div class="dir-kpi-icon"><i class="fas fa-hourglass-half"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($laporanCounts['menunggu']); ?>">0</div>
                                <div class="dir-kpi-lbl">Menunggu</div>
                            </div>
                            <?php if($laporanCounts['menunggu'] > 0): ?><span class="dir-kpi-pulse"></span><?php endif; ?>
                        </a>
                        <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'laporan'])); ?>" class="dir-kpi-card c-red">
                            <div class="dir-kpi-icon"><i class="fas fa-times-circle"></i></div>
                            <div>
                                <div class="dir-kpi-num" data-count="<?php echo e($laporanCounts['ditolak']); ?>">0</div>
                                <div class="dir-kpi-lbl">Ditolak</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Preview (no actions) -->
                <div class="dir-preview-grid">
                    <div class="dash-section">
                        <div class="dash-section-hdr">
                            <h3><i class="fas fa-hourglass-half" style="color:#f5a623;margin-right:6px;"></i> PK Menunggu Persetujuan</h3>
                            <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'perjanjian'])); ?>" class="dash-see-all">Lihat Semua →</a>
                        </div>
                        <?php $waitingPk = collect($perjanjianItems)->where('status', 'menunggu')->take(5); ?>
                        <?php if($waitingPk->isEmpty()): ?>
                            <div class="dir-empty-ok"><i class="fas fa-check-circle"></i> Tidak ada yang menunggu</div>
                        <?php else: ?>
                            <div class="dir-preview-list">
                                <?php $__currentLoopData = $waitingPk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="dir-preview-item">
                                        <div class="dir-avatar"><?php echo e(mb_strtoupper(mb_substr($pk['pihak1_name'], 0, 1))); ?></div>
                                        <div class="dir-preview-body">
                                            <div class="dir-preview-name"><?php echo e($pk['pihak1_name']); ?></div>
                                            <div class="dir-preview-meta"><?php echo e($pk['pihak1_jabatan']); ?> &bull; <?php echo e($pk['periode']); ?></div>
                                        </div>
                                        <div class="dir-preview-side">
                                            <span class="s-badge s-blue"><i class="fas fa-clock" style="font-size:9px;"></i>&nbsp;Menunggu</span>
                                            <div class="dir-preview-date"><?php echo e($pk['tanggal']); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="dash-section">
                        <div class="dash-section-hdr">
                            <h3><i class="fas fa-hourglass-half" style="color:#4c9cf0;margin-right:6px;"></i> LK Menunggu Reviu</h3>
                            <a href="<?php echo e(route('dashboard.direktur', ['panel' => 'laporan'])); ?>" class="dash-see-all">Lihat Semua →</a>
                        </div>
                        <?php $waitingLk = collect($laporanItems)->where('status', 'menunggu')->take(5); ?>
                        <?php if($waitingLk->isEmpty()): ?>
                            <div class="dir-empty-ok"><i class="fas fa-check-circle"></i> Tidak ada yang menunggu</div>
                        <?php else: ?>
                            <div class="dir-preview-list">
                                <?php $__currentLoopData = $waitingLk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="dir-preview-item">
                                        <div class="dir-avatar blue"><?php echo e(mb_strtoupper(mb_substr($lk['pihak1_name'], 0, 1))); ?></div>
                                        <div class="dir-preview-body">
                                            <div class="dir-preview-name"><?php echo e($lk['pihak1_name']); ?></div>
                                            <div class="dir-preview-meta"><?php echo e($lk['pihak1_jabatan']); ?> &bull; Triwulan <?php echo e($lk['triwulan']); ?></div>
                                        </div>
                                        <div class="dir-preview-side">
                                            <span class="s-badge s-blue"><i class="fas fa-clock" style="font-size:9px;"></i>&nbsp;Menunggu</span>
                                            <div class="dir-preview-date"><?php echo e($lk['tahun']); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Charts -->
                <div class="chart-grid">
                    <div class="chart-panel">
                        <h3><i class="fas fa-file-contract" style="color:#00b59a; margin-right:6px;"></i> Grafik Perjanjian Kinerja</h3>
                        <div class="chart-box"><canvas id="chartPerjanjian"></canvas></div>
                    </div>
                    <div class="chart-panel">
                        <h3><i class="fas fa-chart-bar" style="color:#4c9cf0; margin-right:6px;"></i> Grafik Laporan Kinerja</h3>
                        <div class="chart-box"><canvas id="chartLaporan"></canvas></div>
                    </div>
                </div>

            
            <?php elseif($panel === 'perjanjian'): ?>
                <?php $pkStatus = request()->query('status'); ?>

                <?php if(!$pkStatus): ?>
                    
                    <div class="page-header">
                        <h1>Perjanjian Kinerja</h1>
                    </div>
                    <div class="dir-panel-shell">
                        <div class="panel-stat-grid">
                            <div class="dir-psc d-green" onclick="location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'total'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($perjanjianCounts['total']); ?></div>
                                <div class="dir-psc-lbl">Terkirim</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'total'])); ?>'">Lihat</button>
                            </div>
                            <div class="dir-psc d-yellow" onclick="location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'disetujui'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($perjanjianCounts['disetujui']); ?></div>
                                <div class="dir-psc-lbl">Disetujui</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'disetujui'])); ?>'">Lihat</button>
                            </div>
                            <div class="dir-psc d-red" onclick="location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'ditolak'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($perjanjianCounts['ditolak']); ?></div>
                                <div class="dir-psc-lbl">Ditolak</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'ditolak'])); ?>'">Lihat</button>
                            </div>
                            <div class="dir-psc d-blue" onclick="location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'menunggu'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($perjanjianCounts['menunggu']); ?></div>
                                <div class="dir-psc-lbl">Menunggu</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.perjanjian.list', ['status'=>'menunggu'])); ?>'">Lihat</button>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    
                    <?php
                        $pkStatusLabels = ['total'=>'Semua','disetujui'=>'Disetujui','ditolak'=>'Ditolak','menunggu'=>'Menunggu'];
                        $pkFiltered = $pkStatus === 'total'
                            ? collect($perjanjianItems)
                            : collect($perjanjianItems)->where('status', $pkStatus);
                    ?>
                    <div style="position:relative;display:flex;align-items:center;justify-content:center;margin-bottom:24px;padding:0 48px;">
                        <a href="<?php echo e(route('dashboard.direktur', ['panel'=>'perjanjian'])); ?>" style="position:absolute;left:0;color:#333;font-size:20px;text-decoration:none;padding:4px 8px;">&larr;</a>
                        <h2 style="font-size:18px;font-weight:700;color:#222;text-align:center;"><?php echo e($pkStatusLabels[$pkStatus] === 'Semua' ? 'Semua Perjanjian Kinerja' : ($pkStatusLabels[$pkStatus] === 'Menunggu' ? 'Menunggu Persetujuan' : 'Perjanjian Kinerja ' . $pkStatusLabels[$pkStatus])); ?></h2>
                    </div>
                    <div class="dir-list-wrap">
                        <?php $__empty_1 = true; $__currentLoopData = $pkFiltered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="dir-list-card">
                                <div class="dir-doc-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="dir-list-info">
                                    <div class="dir-list-name">Perjanjian Kinerja <?php echo e($pk['periode']); ?></div>
                                    <?php
                                        $pkBadgeClass = ['disetujui'=>'dlb-disetujui','menunggu'=>'dlb-menunggu','ditolak'=>'dlb-ditolak'][$pk['status']] ?? 'dlb-terkirim';
                                        $pkBadgeLabel = ['disetujui'=>'Disetujui','menunggu'=>'Menunggu','ditolak'=>'Ditolak'][$pk['status']] ?? ucfirst($pk['status']);
                                    ?>
                                    <span class="dir-list-badge <?php echo e($pkBadgeClass); ?>"><?php echo e($pkBadgeLabel); ?></span>
                                    <div class="dir-list-date"><?php echo e($pk['tanggal']); ?></div>
                                    <?php if(!empty($pk['rejection_reason'])): ?>
                                        <div style="font-size:12px;color:#DC3545;margin-top:3px;">Alasan: <?php echo e($pk['rejection_reason']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="dir-list-action">
                                    <button class="dir-view-btn" title="Lihat"
                                        onclick="window.open('<?php echo e(url('/dashboard/direktur/perjanjian')); ?>/<?php echo e($pk['id']); ?>/print','_blank')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div style="text-align:center;padding:40px;color:#aaa;"><i class="fas fa-inbox" style="font-size:36px;display:block;margin-bottom:12px;"></i>Tidak ada data.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            
            <?php elseif($panel === 'laporan'): ?>
                <?php $lkStatus = request()->query('status'); ?>

                <?php if(!$lkStatus): ?>
                    
                    <div class="page-header">
                        <h1>Laporan Kinerja</h1>
                    </div>
                    <div class="dir-panel-shell">
                        <div class="panel-stat-grid">
                            <div class="dir-psc d-green" onclick="location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'total'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($laporanCounts['total']); ?></div>
                                <div class="dir-psc-lbl">Terkirim</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'total'])); ?>'">Lihat</button>
                            </div>
                            <div class="dir-psc d-yellow" onclick="location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'disetujui'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($laporanCounts['disetujui']); ?></div>
                                <div class="dir-psc-lbl">Disetujui</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'disetujui'])); ?>'">Lihat</button>
                            </div>
                            <div class="dir-psc d-red" onclick="location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'ditolak'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($laporanCounts['ditolak']); ?></div>
                                <div class="dir-psc-lbl">Ditolak</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'ditolak'])); ?>'">Lihat</button>
                            </div>
                            <div class="dir-psc d-blue" onclick="location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'menunggu'])); ?>'" style="cursor:pointer;">
                                <div class="dir-psc-num"><?php echo e($laporanCounts['menunggu']); ?></div>
                                <div class="dir-psc-lbl">Menunggu</div>
                                <button class="dir-psc-btn" onclick="event.stopPropagation();location.href='<?php echo e(route('direktur.laporan.list', ['status'=>'menunggu'])); ?>'">Lihat</button>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    
                    <?php
                        $lkStatusLabels = ['total'=>'Semua','disetujui'=>'Disetujui','ditolak'=>'Ditolak','menunggu'=>'Menunggu'];
                        $lkFiltered = $lkStatus === 'total'
                            ? collect($laporanItems)
                            : collect($laporanItems)->where('status', $lkStatus);
                    ?>
                    <div style="position:relative;display:flex;align-items:center;justify-content:center;margin-bottom:24px;padding:0 48px;">
                        <a href="<?php echo e(route('dashboard.direktur', ['panel'=>'laporan'])); ?>" style="position:absolute;left:0;color:#333;font-size:20px;text-decoration:none;padding:4px 8px;">&larr;</a>
                        <h2 style="font-size:18px;font-weight:700;color:#222;text-align:center;"><?php echo e($lkStatusLabels[$lkStatus] === 'Semua' ? 'Semua Laporan Kinerja' : ($lkStatusLabels[$lkStatus] === 'Menunggu' ? 'Menunggu Reviu' : 'Laporan Kinerja ' . $lkStatusLabels[$lkStatus])); ?></h2>
                    </div>
                    <div class="dir-list-wrap">
                        <?php $__empty_1 = true; $__currentLoopData = $lkFiltered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="dir-list-card">
                                <div class="dir-doc-icon lk"><i class="fas fa-file-alt"></i></div>
                                <div class="dir-list-info">
                                    <div class="dir-list-name">Laporan Kinerja Triwulan <?php echo e($lk['triwulan']); ?> <?php echo e($lk['tahun']); ?></div>
                                    <?php
                                        $lkBadgeClass = ['disetujui'=>'dlb-disetujui','menunggu'=>'dlb-menunggu','ditolak'=>'dlb-ditolak','terkirim'=>'dlb-terkirim'][$lk['status']] ?? 'dlb-terkirim';
                                        $lkBadgeLabel = ['disetujui'=>'Disetujui','menunggu'=>'Menunggu','ditolak'=>'Ditolak','terkirim'=>'Terkirim'][$lk['status']] ?? ucfirst($lk['status']);
                                    ?>
                                    <span class="dir-list-badge <?php echo e($lkBadgeClass); ?>"><?php echo e($lkBadgeLabel); ?></span>
                                    <div class="dir-list-date"><?php echo e($lk['pihak1_name']); ?></div>
                                </div>
                                <div class="dir-list-action">
                                    <button class="dir-view-btn" title="Lihat"
                                        onclick="window.open('<?php echo e(route('laporan.kinerja')); ?>?perjanjian_id=<?php echo e($lk['perjanjian_id']); ?>','_blank')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div style="text-align:center;padding:40px;color:#aaa;"><i class="fas fa-inbox" style="font-size:36px;display:block;margin-bottom:12px;"></i>Tidak ada data.</div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            
            <?php elseif($panel === 'profil'): ?>
                <div class="page-header">
                    <h1>Profil Direktur</h1>
                </div>
                <?php echo $__env->make('dashboard.partials.profile-panel', [
                    'title'           => 'Profil Direktur',
                    'hideDescription' => true,
                    'hideSummary'     => true,
                    'isEditable'      => true,
                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
        </main>
    </div>

    <!-- FOOTER -->
    <footer style="margin-top:40px;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© <?php echo e(date('Y')); ?> RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

    

    <!-- Logout Modal -->
    <div id="dirLogoutModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.3);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:300px;max-width:380px;text-align:center;">
            <h3 style="margin-bottom:18px;">Apa anda ingin keluar?</h3>
            <div style="display:flex;gap:16px;justify-content:center;">
                <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?>
                    <button type="submit" style="background:#00B5A0;color:#fff;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">YA</button>
                </form>
                <button type="button" onclick="document.getElementById('dirLogoutModal').style.display='none';" style="background:#eee;color:#333;padding:8px 24px;border:none;border-radius:6px;font-weight:600;cursor:pointer;">TIDAK</button>
            </div>
        </div>
    </div>

    <!-- Perjanjian Status Modal -->
    <div id="modalPkStatus" class="dir-modal">
        <div class="dir-mbox">
            <div class="dir-mhdr">
                <h3 id="modalPkTitle">Perjanjian Kinerja</h3>
                <button class="dir-close" onclick="closePkModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="dir-mbody" id="modalPkBody"></div>
            <div class="dir-mftr">
                <button class="btn-xs btn-neutral" onclick="closePkModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Laporan Status Modal -->
    <div id="modalLkStatus" class="dir-modal">
        <div class="dir-mbox">
            <div class="dir-mhdr">
                <h3 id="modalLkTitle">Laporan Kinerja</h3>
                <button class="dir-close" onclick="closeLkModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="dir-mbody" id="modalLkBody"></div>
            <div class="dir-mftr">
                <button class="btn-xs btn-neutral" onclick="closeLkModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Preview Modal (iframe) -->
    <div id="previewModal" class="dir-modal">
        <div class="dir-mbox xl" style="display:flex;flex-direction:column;">
            <div class="dir-mhdr">
                <h3>Preview Perjanjian</h3>
                <button class="dir-close" onclick="closePreviewModal()"><i class="fas fa-times"></i></button>
            </div>
            <iframe id="previewIframe" src="" style="flex:1;border:none;min-height:0;"></iframe>
            <div class="dir-mftr">
                <button id="btnApprovePreview" class="btn-xs btn-teal" onclick="approvePerjanjian(currentPreviewId)"><i class="fas fa-check"></i> Setujui</button>
                <button id="btnRejectPreview" class="btn-xs btn-danger" onclick="closePreviewModal(); openRejectModal(currentPreviewId)"><i class="fas fa-times"></i> Tolak</button>
                <button class="btn-xs btn-neutral" onclick="closePreviewModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="dir-modal">
        <div class="dir-mbox sm">
            <div class="dir-mhdr">
                <h3>Tolak Perjanjian</h3>
                <button class="dir-close" onclick="closeRejectModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="dir-mbody">
                <p style="font-size:13px;color:#666;margin-bottom:12px;">Isi alasan penolakan (minimal 10 karakter):</p>
                <textarea id="rejectionReason" style="width:100%;min-height:120px;border:1px solid #d9d9d9;border-radius:8px;padding:12px;font-size:14px;resize:vertical;color:#333;font-family:inherit;" placeholder="Tulis alasan penolakan..."></textarea>
            </div>
            <div class="dir-mftr">
                <button class="btn-xs btn-danger" onclick="submitReject()"><i class="fas fa-times-circle"></i> Kirim Penolakan</button>
                <button class="btn-xs btn-neutral" onclick="closeRejectModal()">Batal</button>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const CSRF_TOKEN     = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const REVIEW_BASE    = "<?php echo e(url('/dashboard/direktur/perjanjian')); ?>";
        const LAPORAN_URL    = "<?php echo e(route('laporan.kinerja')); ?>";

        const PERJANJIAN_ITEMS = <?php echo json_encode($perjanjianItems ?? [], 15, 512) ?>;
        const LAPORAN_ITEMS    = <?php echo json_encode($laporanItems ?? [], 15, 512) ?>;
        const CHART_DATA       = <?php echo json_encode($chartData ?? [], 15, 512) ?>;

        let currentPreviewId = null;
        let currentRejectId  = null;
        let currentPreviewStatus = null;

        // ---- Modal helpers ----
        function openDirModal(id)  { const el = document.getElementById(id); if (el) el.classList.add('open'); }
        function closeDirModal(id) { const el = document.getElementById(id); if (el) el.classList.remove('open'); }

        // ---- Escape HTML ----
        function esc(s) {
            if (!s && s !== 0) return '';
            return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // ---- Badge helper ----
        const PK_BADGE_CLASS = { disetujui:'dlb-setuju', menunggu:'dlb-proses', ditolak:'dlb-tolak' };
        const PK_BADGE_LABEL = { disetujui:'Disetujui', menunggu:'Diproses', ditolak:'Ditolak' };
        const LK_BADGE_CLASS = { disetujui:'dlb-setuju', menunggu:'dlb-proses', ditolak:'dlb-tolak', terkirim:'dlb-terkirim' };
        const LK_BADGE_LABEL = { disetujui:'Disetujui', menunggu:'Diproses', ditolak:'Ditolak', terkirim:'Terkirim' };
        function pkBadge(s)  { return `<span class="dir-list-badge ${PK_BADGE_CLASS[s]||'dlb-terkirim'}">${PK_BADGE_LABEL[s]||s}</span>`; }
        function lkBadge(s)  { return `<span class="dir-list-badge ${LK_BADGE_CLASS[s]||'dlb-terkirim'}">${LK_BADGE_LABEL[s]||s}</span>`; }

        // ---- Perjanjian Status Modal ----
        const PK_MAP = {
            total:     { title: 'Semua Perjanjian Kinerja',                  filter: null },
            disetujui: { title: 'Perjanjian Kinerja — Disetujui',          filter: 'disetujui' },
            menunggu:  { title: 'Perjanjian Kinerja — Menunggu Persetujuan', filter: 'menunggu' },
            ditolak:   { title: 'Perjanjian Kinerja — Ditolak',             filter: 'ditolak' },
        };

        function openPkModal(status) {
            const meta  = PK_MAP[status] || PK_MAP.total;
            const items = meta.filter ? PERJANJIAN_ITEMS.filter(i => i.status === meta.filter) : PERJANJIAN_ITEMS;
            document.getElementById('modalPkTitle').textContent = meta.title;
            const body = document.getElementById('modalPkBody');
            if (!items.length) {
                body.innerHTML = '<div style="text-align:center;padding:40px;color:#aaa;"><i class="fas fa-inbox" style="font-size:36px;margin-bottom:12px;display:block;"></i>Tidak ada data.</div>';
            } else {
                const cards = items.map(pk => {
                    const reason = pk.rejection_reason
                        ? `<div style="font-size:11px;color:#b91c1c;margin-top:2px;">Alasan: ${esc(pk.rejection_reason)}</div>` : '';
                    return `<div class="dir-list-card">
                        <div class="dir-doc-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="dir-list-info">
                            <div class="dir-list-name">${esc(pk.pihak1_name)}</div>
                            ${pkBadge(pk.status)}
                            <div class="dir-list-date">${esc(pk.tanggal)}</div>
                            ${reason}
                        </div>
                        <div class="dir-list-action">
                            <button class="btn-xs btn-teal" onclick="openPreviewModal(${pk.id},'${pk.status}')"><i class="fas fa-eye"></i> Lihat</button>
                        </div>
                    </div>`;
                });
                body.innerHTML = `<div class="dir-list-wrap">${cards.join('')}</div>`;
            }
            openDirModal('modalPkStatus');
        }
        function closePkModal() { closeDirModal('modalPkStatus'); }

        // ---- Laporan Status Modal ----
        const LK_MAP = {
            total:     { title: 'Semua Laporan Kinerja',           filter: null },
            disetujui: { title: 'Laporan Kinerja — Disetujui',     filter: 'disetujui' },
            menunggu:  { title: 'Laporan Kinerja — Menunggu Reviu', filter: 'menunggu' },
            ditolak:   { title: 'Laporan Kinerja — Ditolak',        filter: 'ditolak' },
        };

        function openLkModal(status) {
            const meta  = LK_MAP[status] || LK_MAP.total;
            const items = meta.filter ? LAPORAN_ITEMS.filter(i => i.status === meta.filter) : LAPORAN_ITEMS;
            document.getElementById('modalLkTitle').textContent = meta.title;
            const body = document.getElementById('modalLkBody');
            if (!items.length) {
                body.innerHTML = '<div style="text-align:center;padding:40px;color:#aaa;"><i class="fas fa-inbox" style="font-size:36px;margin-bottom:12px;display:block;"></i>Tidak ada data.</div>';
            } else {
                const cards = items.map(lk => {
                    const viewUrl = `${LAPORAN_URL}?perjanjian_id=${lk.perjanjian_id}`;
                    return `<div class="dir-list-card">
                        <div class="dir-doc-icon lk"><i class="fas fa-chart-bar"></i></div>
                        <div class="dir-list-info">
                            <div class="dir-list-name">${esc(lk.pihak1_name)}</div>
                            ${lkBadge(lk.status)}
                            <div class="dir-list-date">Triwulan ${lk.triwulan} &bull; ${esc(lk.tahun)}</div>
                        </div>
                        <div class="dir-list-action">
                            <a href="${viewUrl}" target="_blank" class="btn-xs btn-teal"><i class="fas fa-eye"></i> Lihat</a>
                        </div>
                    </div>`;
                });
                body.innerHTML = `<div class="dir-list-wrap">${cards.join('')}</div>`;
            }
            openDirModal('modalLkStatus');
        }
        function closeLkModal() { closeDirModal('modalLkStatus'); }

        // ---- Preview Modal ----
        function openPreviewModal(id, status) {
            currentPreviewId     = id;
            currentPreviewStatus = status || 'menunggu';
            document.getElementById('previewIframe').src = REVIEW_BASE + '/' + id;
            const canAct = (currentPreviewStatus === 'menunggu');
            document.getElementById('btnApprovePreview').style.display = canAct ? '' : 'none';
            document.getElementById('btnRejectPreview').style.display  = canAct ? '' : 'none';
            openDirModal('previewModal');
        }
        function closePreviewModal() {
            closeDirModal('previewModal');
            document.getElementById('previewIframe').src = '';
            currentPreviewId = null;
            currentPreviewStatus = null;
        }

        // ---- Inline Reject (from PK list page) ----
        function openInlineReject(id) {
            currentRejectId = id;
            document.getElementById('rejectionReason').value = '';
            openDirModal('rejectModal');
        }

        // ---- Approve ----
        function approvePerjanjian(id) {
            if (!id) return;
            if (!confirm('Yakin ingin menyetujui perjanjian ini?')) return;
            fetch(REVIEW_BASE + '/' + id + '/approve', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
            }).then(async r => {
                const res = await r.json();
                alert(res.message || 'Operasi selesai');
                if (res.success) { closePreviewModal(); location.reload(); }
            }).catch(err => alert('Error: ' + err.message));
        }

        // ---- Reject Modal ----
        function openRejectModal(id) {
            currentRejectId = id;
            document.getElementById('rejectionReason').value = '';
            openDirModal('rejectModal');
        }
        function closeRejectModal() {
            closeDirModal('rejectModal');
            document.getElementById('rejectionReason').value = '';
            currentRejectId = null;
        }
        function submitReject() {
            if (!currentRejectId) return;
            const reason = document.getElementById('rejectionReason').value.trim();
            if (reason.length < 10) { alert('Alasan penolakan minimal 10 karakter.'); return; }
            fetch(REVIEW_BASE + '/' + currentRejectId + '/reject', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ rejection_reason: reason })
            }).then(async r => {
                const res = await r.json();
                alert(res.message || 'Operasi selesai');
                if (res.success) { closeRejectModal(); location.reload(); }
            }).catch(err => alert('Error: ' + err.message));
        }

        // ---- Backdrop close ----
        ['modalPkStatus','modalLkStatus','previewModal','rejectModal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('click', e => { if (e.target === el) closeDirModal(id); });
        });
        const dirLogout = document.getElementById('dirLogoutModal');
        if (dirLogout) dirLogout.addEventListener('click', e => { if (e.target === dirLogout) dirLogout.style.display = 'none'; });

        // ---- Escape ----
        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            closePkModal(); closeLkModal(); closePreviewModal(); closeRejectModal();
            const dl = document.getElementById('dirLogoutModal'); if (dl) dl.style.display = 'none';
        });

        // ---- Count-up animation ----
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.dir-kpi-num[data-count]').forEach(function (el) {
                const target = parseInt(el.dataset.count, 10);
                if (!target) { el.textContent = '0'; return; }
                const dur = 700, step = 16, inc = target / (dur / step);
                let cur = 0;
                const t = setInterval(function () {
                    cur = Math.min(cur + inc, target);
                    el.textContent = Math.floor(cur);
                    if (cur >= target) clearInterval(t);
                }, step);
            });
        });

        // ---- Charts ----
        document.addEventListener('DOMContentLoaded', function () {
            const months = CHART_DATA.months || [];

            const pkCanvas = document.getElementById('chartPerjanjian');
            if (pkCanvas && months.length) {
                new Chart(pkCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [
                            { label: 'Disetujui', data: CHART_DATA.pkDisetujui || [], backgroundColor: 'rgba(0,181,144,0.75)', borderRadius: 4 },
                            { label: 'Menunggu',  data: CHART_DATA.pkMenunggu  || [], backgroundColor: 'rgba(76,156,240,0.75)', borderRadius: 4 },
                            { label: 'Ditolak',   data: CHART_DATA.pkDitolak   || [], backgroundColor: 'rgba(229,62,62,0.75)',  borderRadius: 4 },
                        ]
                    },
                    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top' } }, scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } } }
                });
            }

            const lkCanvas = document.getElementById('chartLaporan');
            if (lkCanvas && months.length) {
                new Chart(lkCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [
                            { label: 'Disetujui', data: CHART_DATA.lkDisetujui || [], backgroundColor: 'rgba(0,181,144,0.75)', borderRadius: 4 },
                            { label: 'Menunggu',  data: CHART_DATA.lkMenunggu  || [], backgroundColor: 'rgba(76,156,240,0.75)', borderRadius: 4 },
                        ]
                    },
                    options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'top' } }, scales:{ y:{ beginAtZero:true, ticks:{ stepSize:1 } } } }
                });
            }
        });
    </script>

</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/dashboard/direktur.blade.php ENDPATH**/ ?>