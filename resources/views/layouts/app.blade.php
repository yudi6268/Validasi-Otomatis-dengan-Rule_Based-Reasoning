<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'RSUD Bangil')</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #D9F3F5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #000;
    }

    /* Header */
    header {
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    header h1 {
      font-size: 20px;
      color: #222;
      font-weight: 600;
      margin: 0;
    }

    .header-icon {
      color: #009970;
      font-size: 22px;
      cursor: pointer;
      transition: 0.3s;
    }

    .header-icon:hover {
      color: #007b5e;
    }

    /* Main Content */
    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding: 35px 0;
      position: relative;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 15px;
      background: #fff;
      border-top: 1px solid #ddd;
      font-weight: 600;
      font-size: 13px;
      color: #000;
    }

    /* Cards */
    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 25px;
      justify-content: center;
      width: 90%;
      max-width: 700px;
    }

    .status-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }

    .status-card:hover {
      transform: translateY(-3px);
    }

    .status-card h2 {
      font-size: 42px; 
      margin-bottom: 8px;
      font-weight: 700; 
      color: #000;
    }

    .status-card p {
      margin: 0 0 15px;
      font-weight: 700;     
      font-size: 17px;      
      color: #111;          
    }

    /* Buttons */
    .btn-view {
      border: none;
      padding: 10px 24px; 
      border-radius: 10px;
      font-weight: 700; 
      font-size: 15px; 
      color: #fff;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .btn-view:hover {
      transform: scale(1.05);
    }

    .btn-green { background: #009970; }
    .btn-yellow { background: #f2c200; color: #000; }
    .btn-red { background: #d9534f; }
    .btn-blue { background: #3f8cff; }

    .btn-add {
      background: #009970;
      color: #fff;
      border: none;
      padding: 12px 24px;
      border-radius: 10px;
      position: absolute;
      right: 80px; /* tidak terlalu pojok */
      bottom: 50px;
      font-weight: 700;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-add:hover {
      background: #007b5e;
      transform: scale(1.05);
    }

    /* Logout Confirmation Popup */
    .logout-confirm {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      display: none;
    }

    .logout-box {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .logout-box h3 {
      margin-bottom: 20px;
      color: #333;
      font-weight: 600;
    }

    .logout-box button {
      margin: 0 10px;
      padding: 8px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-yes { background: #009970; color: #fff; }
    .btn-no { background: #009970; color: #fff; }

    .btn-yes:hover { background: #009970; }
    .btn-no:hover { background: #009970; }
  </style>
</head>

<body>
  <header>
    @yield('back')
    <h1>@yield('header_title')</h1>
    <i class="fa-solid fa-right-from-bracket header-icon" id="logout-btn"></i>
  </header>

  <main>
    @yield('content')
  </main>

  <footer>
    © 2025 RSUD Bangil – Sistem Laporan Kinerja
  </footer>

  {{-- Logout Confirmation Popup --}}
  <div class="logout-confirm" id="logout-popup">
    <div class="logout-box">
      <h3>Apa anda ingin keluar?</h3>
      <button class="btn-yes" onclick="window.location='{{ route('logout') }}'">Iya</button>
      <button class="btn-no" onclick="closeLogout()">Tidak</button>
    </div>
  </div>

  <script>
    const logoutBtn = document.getElementById('logout-btn');
    const logoutPopup = document.getElementById('logout-popup');

    logoutBtn.addEventListener('click', () => {
      logoutPopup.style.display = 'flex';
    });

    function closeLogout() {
      logoutPopup.style.display = 'none';
    }
  </script>
</body>
</html>