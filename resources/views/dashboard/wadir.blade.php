<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Wadir - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #E3F8F6, #D6F5EF);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 40px;
      box-shadow: 0 4px 12px rgba(0,153,112,0.15);
      position: relative;
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .logo-container img {
      height: 60px;
    }

    nav {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 20px;
    }

    nav a {
      text-decoration: none;
      color: #1B2A41;
      font-weight: 600;
      font-size: 20px;
      letter-spacing: 0.3px;
      transition: 0.3s ease;
    }

    nav a:hover {
      color: #00B5A0;
      text-shadow: 0 0 8px rgba(0,181,160,0.4);
    }

    .icons {
      display: flex;
      align-items: center;
      gap: 15px;
      position: relative;
    }

    .icons i {
      background: #E6F6F2;
      color: #00B5A0;
      font-size: 20px;
      border-radius: 50%;
      padding: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .icons i:hover {
      background: #CFF2E9;
      color: #008F7E;
      box-shadow: 0 0 10px rgba(0,181,160,0.3);
      transform: scale(1.1);
    }

    .profile-menu {
      position: absolute;
      top: 75px;
      right: 10px;
      background: #E6F6F2;
      border-radius: 14px;
      width: 150px;
      padding: 12px;
      display: none;
      flex-direction: column;
      gap: 10px;
      animation: fadeIn 0.3s ease;
    }

    .profile-menu a {
      text-decoration: none;
      color: #1B2A41;
      font-size: 14px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: 0.3s;
    }
    .profile-menu a:hover {
      color: #00B5A0;
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-5px);}
      to {opacity: 1; transform: translateY(0);}
    }

    main {
      flex: 1;
      text-align: center;
      padding: 60px 20px;
    }

    .welcome-text { 
      font-size: 36px; 
      font-weight: 800; color: #222; 
      margin-bottom: -2px; 
    } 
    
    .hospital-title { 
      font-size: 40px; 
      font-weight: 800; 
      background: linear-gradient(135deg, #009970, #006f51); 
      -webkit-background-clip: text; 
      -webkit-text-fill-color: transparent; 
      text-shadow: 0 0 10px rgba(0,153,112,0.25); 
      margin-top: -5px; 
      margin-bottom: 10px; 
    }

    .role-text {
      font-size: 18px;
      color: #5F6F81;
      font-weight: 600;
      margin-bottom: 30px;
    }

    .stats-container {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
      margin-bottom: 40px;
    }

    .stat-card {
      background: #FFFFFF;
      width: 200px;
      padding: 25px 20px;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 16px rgba(0,181,160,0.15);
    }

    .stat-card i {
      font-size: 32px;
      margin-bottom: 10px;
    }

    .stat-card.total i { color: #00B5A0; }
    .stat-card.approved i { color: #00B050; }
    .stat-card.waiting i { color: #FFA500; }
    .stat-card.rejected i { color: #FF4444; }

    .stat-card h4 {
      font-size: 14px;
      font-weight: 600;
      color: #5F6F81;
      margin: 8px 0;
    }

    .stat-card .number {
      font-size: 32px;
      font-weight: 800;
      color: #1B2A41;
    }

    .menu-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-top: 30px; 
    }

    .menu-item {
      background: #FFFFFF;
      width: 180px;
      padding: 30px 20px;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: 0.3s;
      text-align: center;
      text-decoration: none;
      color: #1B2A41;
    }

    .menu-item:hover {
      transform: translateY(-6px);
      box-shadow: 0 6px 20px rgba(0,181,160,0.2);
      cursor: pointer;
    }

    .menu-item i {
      font-size: 48px;
      margin-bottom: 15px;
      color: #00B5A0;
    }

    .menu-item p {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }

    footer {
      background: #fff;
      text-align: center;
      font-size: 13px;
      font-weight: 700;
      padding: 15px 0;
      border-top: 1px solid #ddd;
      color: #1B2A41;
    }

    /* MODAL LOGOUT */
    #logoutModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.25);
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .logout-box {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      width: 320px;
      text-align: center;
      box-shadow: 0 4px 14px rgba(0,0,0,0.1);
      animation: fadeIn 0.3s ease;
    }

    .logout-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    .logout-buttons button {
      padding: 10px 28px;
      border-radius: 30px;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }
    .logout-buttons form button {
      background: #00B5A0;
      color: #fff;
    }
    .logout-buttons form button:hover {
      background: #008F7E;
      box-shadow: 0 4px 10px rgba(0,181,160,0.3);
    }

    #noBtn {
      background: #00B5A0;
      color: #fff;
    }
    #noBtn:hover {
      background: #008F7E;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_rsud2.png') }}" alt="Logo Rumah Sakit" />
    </div>
    <nav>
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ url('/tentang') }}">Tentang</a>
    </nav>
    <div class="icons">
      <i class="fa-solid fa-user" onclick="toggleMenu()"></i>
      <div id="profileMenu" class="profile-menu">
        <a href="{{ route('profile') }}"><i class="fa-solid fa-user-pen"></i> Profil</a>
        <a href="#" id="logoutLink">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
  </header>

  <main>
    <h2 class="welcome-text">Selamat Datang di</h2>
    <h1 class="hospital-title">SISTEM LAPORAN KINERJA RSUD BANGIL</h1>
    <p class="role-text">Dashboard Wakil Direktur</p>

    <!-- Statistik -->
    <div class="stats-container">
      <div class="stat-card total">
        <i class="fa-solid fa-file-contract"></i>
        <h4>Total Perjanjian</h4>
        <div class="number">{{ $totalPerjanjian }}</div>
      </div>
      <div class="stat-card approved">
        <i class="fa-solid fa-circle-check"></i>
        <h4>Disetujui</h4>
        <div class="number">{{ $perjanjianApproved }}</div>
      </div>
      <div class="stat-card waiting">
        <i class="fa-solid fa-clock"></i>
        <h4>Menunggu</h4>
        <div class="number">{{ $perjanjianWaiting }}</div>
      </div>
      <div class="stat-card rejected">
        <i class="fa-solid fa-circle-xmark"></i>
        <h4>Ditolak</h4>
        <div class="number">{{ $perjanjianRejected }}</div>
      </div>
    </div>

    <!-- Menu Akses -->
    <div class="menu-container">
      <a href="{{ route('perjanjian.index') }}" class="menu-item">
        <i class="fa-solid fa-file-contract"></i>
        <p>Perjanjian Kinerja</p>
      </a>
    </div>
  </main>

  <footer>
    © 2026 RSUD Bangil – Sistem Laporan Kinerja
  </footer>

  @include('components.logout-modal')

  <script>
    function toggleMenu() {
      const menu = document.getElementById('profileMenu');
      menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    }

    document.addEventListener('click', function(event) {
      const menu = document.getElementById('profileMenu');
      const icon = event.target.closest('.fa-user');
      if (!icon && menu.style.display === 'flex') {
        menu.style.display = 'none';
      }
    });

    // Pastikan tombol logout memanggil showLogoutModal()
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
      logoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        showLogoutModal();
      });
    }
  </script>
</body>
</html>
