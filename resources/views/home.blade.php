<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Beranda - RSUD Bangil</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
      box-shadow: 0 4px 12px rgba(0, 153, 112, 0.15);
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
      margin-left: 500px;
    }

    nav a {
      text-decoration: none;
      color: #000;
      font-weight: 520;
      font-size: 20px;
      letter-spacing: 0.3px;
      margin: 0 10px;
      position: relative;
      transition: color 0.3s, text-shadow 0.3s;
    }

    nav a:hover {
      color: #009970;
      text-shadow: 0 0 8px rgba(0, 153, 112, 0.5);
    }

    .icons {
      display: flex;
      align-items: center;
      gap: 15px;
      position: relative;
    }

    .icons i {
      background-color: #E6F6F2;
      color: #009970;
      font-size: 20px;
      border-radius: 50%;
      padding: 8px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .icons i:hover {
      background-color: #CFF2E9;
      box-shadow: 0 0 10px rgba(0, 153, 112, 0.4);
      transform: scale(1.1);
    }

    /* DROPDOWN PROFIL */
    .profile-menu {
      position: absolute;
      top: 70px;
      right: 30px;
      background-color: #E6F6F2;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 160px;
      padding: 15px;
      display: none;
      flex-direction: column;
      gap: 15px;
      animation: fadeIn 0.3s ease;
    }

    .profile-item {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #000;
      font-size: 14px;
      font-weight: 500;
      cursor: pointer;
      transition: 0.3s;
      text-decoration: none;
    }

    .profile-item:hover {
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-5px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* MAIN */
    main {
      text-align: center;
      padding: 70px 20px;
      flex: 1;
    }

    main h1 {
      color: #333;
      font-weight: 800;
      font-size: 28px;
      line-height: 1.6;
      margin-bottom: 15px;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.15);
    }

    main p {
      font-size: 14px;
      color: #555;
      margin-bottom: 45px;
    }

    .menu-container {
      display: flex;
      justify-content: center;
      gap: 50px;
      flex-wrap: wrap;
    }

    .card {
      background: rgba(255, 255, 255, 0.72);
      backdrop-filter: blur(6px);
      border: 1px solid rgba(255, 255, 255, 0.4);

      width: 210px;
      padding: 30px 20px;
      border-radius: 18px;
      box-shadow: 0 4px 12px rgba(0, 153, 112, 0.1);
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 18px rgba(0, 153, 112, 0.22);
    }

    .card img {
      width: 70px;
      margin-bottom: 10px;
    }

    .card h3 {
      color: #222;
      font-size: 15px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .card button {
      background-color: #009970;
      color: #fff;
      border: none;
      padding: 8px 25px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      transition: 0.3s ease;
    }

    .card button:hover {
      background-color: #007b5e;
      box-shadow: 0 0 10px rgba(0, 153, 112, 0.4);
      transform: translateY(-2px);
    }

    /* FOOTER */
    footer {
      background: #fff;
      text-align: center;
      font-size: 13px;
      color: #000;
      font-weight: 700;
      padding: 15px 0;
      border-top: 1px solid #ddd;
      box-shadow: 0 -2px 12px rgba(0, 153, 112, 0.1);
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
      text-align: center;
      width: 320px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      animation: fadeIn 0.3s ease-in-out;
    }

    .logout-box h3 {
      color: #222;
      margin-bottom: 20px;
    }

    .logout-buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .logout-buttons button {
      background: #009970;
      color: #fff;
      padding: 10px 30px;
      border: none;
      border-radius: 30px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .logout-buttons button:hover {
      background: #007b5e;
      box-shadow: 0 0 10px rgba(0, 153, 112, 0.4);
    }
  </style>
</head>

<body>

  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
      <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
    </div>

    <nav>
      <a href="#">Panduan</a>
      <a href="{{ route('kontak') }}">Kontak</a>
      <a href="{{ route('tentang') }}">Tentang</a>
    </nav>

    <div class="icons">
      <i id="profileIcon" class="fa-solid fa-user"></i>
      <i id="logoutIcon" class="fa-solid fa-right-from-bracket"></i>

      <div id="profileMenu" class="profile-menu">
        <a href="{{ route('profil') }}" class="profile-item">
          <i class="fa-solid fa-user"></i> Profil Saya
        </a>
        <a href="{{ route('settings') }}" class="profile-item">
          <i class="fa-solid fa-gear"></i> Settings
        </a>
      </div>
    </div>
  </header>

  <main>
    <h1>Selamat datang di Sistem<br>Laporan Kinerja RSUD Bangil.</h1>
    <p>Pilih menu di bawah untuk melanjutkan</p>

    <div class="menu-container">
      <div class="card">
        <img src="{{ asset('images/icon_perjanjian.png') }}" alt="Perjanjian">
        <h3>Perjanjian</h3>
        <a href="{{ route('perjanjian.index') }}">
          <button>Buka</button>
        </a>
      </div>

      <div class="card">
        <img src="{{ asset('images/icon_kinerja.png') }}" alt="Laporan Kinerja">
        <h3>Laporan Kinerja</h3>
        <a href="{{ route('laporan.index') }}">
          <button>Buka</button>
        </a>
      </div>
    </div>
  </main>

  <footer>
    © 2025 RSUD Bangil | <span>Dikelola oleh Tim IT RSUD Bangil</span>
  </footer>

  <!-- Modal Logout -->
  <div id="logoutModal">
    <div class="logout-box">
      <h3>Apa anda ingin keluar?</h3>
      <div class="logout-buttons">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit">YA</button>
        </form>
        <button id="noBtn">TIDAK</button>
      </div>
    </div>
  </div>

  <script>
    const profileIcon = document.getElementById('profileIcon');
    const profileMenu = document.getElementById('profileMenu');
    const logoutIcon = document.getElementById('logoutIcon');
    const logoutModal = document.getElementById('logoutModal');
    const noBtn = document.getElementById('noBtn');

    profileIcon.addEventListener('click', () => {
      profileMenu.style.display =
        profileMenu.style.display === 'flex' ? 'none' : 'flex';
    });

    document.addEventListener('click', (e) => {
      if (!profileIcon.contains(e.target) && !profileMenu.contains(e.target)) {
        profileMenu.style.display = 'none';
      }
    });

    logoutIcon.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    noBtn.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });
  </script>
</body>
</html>