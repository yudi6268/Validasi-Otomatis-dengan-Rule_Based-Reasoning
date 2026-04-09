<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda - RSUD Bangil</title>
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

    /* HEADER */
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

    /* NAV DITENGAH */
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

    /* DROPDOWN PROFILE */
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

    /* MAIN CONTENT */
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
      margin-bottom: 20px; 
    }

    .menu-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      flex-wrap: wrap;
      margin-top: 30px; 
    }

    /* DESAIN CARD SESUAI REFERENSI KAMU */
    .card {
      background: #FFFFFF;
      width: 400px;  
      padding: 35px 30px;
      border-radius: 18px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.06);
      text-align: center;
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0,181,160,0.15);
    }

    .card i {
      font-size: 42px;
      background: #00B5A0;
      color: #fff;
      padding: 16px;
      border-radius: 14px;
      margin-bottom: 18px;
    }

    .card h3 {
      font-size: 22px;
      font-weight: 800;
      color: #1B2A41;
      margin: 0 0 8px 0;
    }

    .card p {
      font-size: 14px;
      font-weight: 500;
      color: #5F6F81;
      margin: 0 0 22px 0;
      line-height: 1.6;
    }

    .card button {
      width: 100%;
      background: #00B5A0;
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    
    .card button:hover {
      background: #008F7E;
      box-shadow: 0 4px 12px rgba(0,181,160,0.3);
    }

    /* FOOTER */
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
      background: #00B5A0;
    }

  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
      <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
    </div>

    <nav>
      <a href="{{ route('panduan') }}">Panduan</a>
      <a href="{{ route('kontak') }}">Kontak</a>
      <a href="{{ route('tentang') }}">Tentang</a>
    </nav>

    <div class="icons">
      <i id="profileIcon" class="fa-solid fa-user"></i>
      <i id="logoutIcon" class="fa-solid fa-right-from-bracket"></i>

      <div id="profileMenu" class="profile-menu">
        <a href="{{ route('profil') }}"><i class="fa-solid fa-user"></i>Profil Saya</a>
        <a href="{{ route('settings') }}"><i class="fa-solid fa-gear"></i>Settings</a>
      </div>
    </div>
  </header>

  <!-- MAIN -->
  <main> 
    <div class="welcome-text">Selamat datang di Sistem</div> 
    <div class="hospital-title">Perjanjian Kinerja RSUD Bangil</div> 
    <p>Pilih menu di bawah untuk melanjutkan</p>

    <div class="menu-container">

      <!-- CARD 1 -->
      <div class="card">
        <i class="fa-solid fa-file-signature"></i>
        <h3>Perjanjian Kinerja</h3>
        <p>Kelola dan lihat dokumen perjanjian kinerja</p>
        <a href="{{ route('perjanjian.index') }}">
          <button>Buka</button>
        </a>
      </div>

      <!-- CARD 2 -->
      <div class="card">
        <i class="fa-solid fa-chart-line"></i>
        <h3>Laporan Kinerja</h3>
        <p>Kelola Laporan Kinerja</p>
        <a href="{{ route('laporan.kinerja') }}">
          <button>Buka</button>
        </a>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer>© 2025 RSUD Bangil – Sistem Perjanjian Kinerja</footer>

  @include('components.logout-modal')

  <script>
    const profileIcon = document.getElementById('profileIcon');
    const profileMenu = document.getElementById('profileMenu');
    const logoutIcon = document.getElementById('logoutIcon');

    if (profileIcon && profileMenu) {
      profileIcon.addEventListener('click', () => {
        profileMenu.style.display = profileMenu.style.display === 'flex' ? 'none' : 'flex';
      });
      document.addEventListener('click', (e) => {
        if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
          profileMenu.style.display = 'none';
        }
      });
    }
    // Pastikan tombol logout memanggil showLogoutModal()
    if (logoutIcon) {
      logoutIcon.addEventListener('click', function(e) {
        e.preventDefault();
        showLogoutModal();
      });
    }
  </script>
</body>
</html>