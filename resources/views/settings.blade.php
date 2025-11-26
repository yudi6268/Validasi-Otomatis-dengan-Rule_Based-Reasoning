<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - RSUD Bangil</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #D9F3F5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* HEADER */
    header {
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 8px 25px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .back-icon, .logout-icon {
      color: #009970;
      font-size: 22px;
      cursor: pointer;
      transition: 0.3s;
    }

    .back-icon:hover, .logout-icon:hover {
      color: #007b5e;
    }

    h1 {
      font-size: 20px;
      font-weight: 600;
      color: #333;
    }

    /* MAIN CONTENT */
    main {
      flex: 1;
      padding: 50px 0;
      text-align: center;
    }

    .form-section {
      background-color: #D9F3F5;
      max-width: 800px;
      margin: 0 auto;
      text-align: left;
    }

    .form-group {
      margin-bottom: 30px;
    }

    .form-group h2 {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 16px;
      font-weight: 600;
      color: #222;
      margin-bottom: 15px;
    }

    .form-group h2 i {
      color: #009970;
      font-size: 18px;
    }

    .input-row {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 10px;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      color: #000;
    }

    input::placeholder {
      color: #777;
    }

    button {
      background-color: #009970;
      color: #fff;
      border: none;
      padding: 8px 30px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background-color: #007b5e;
    }

    /* FOOTER */
    footer {
      background: #fff;
      padding: 15px;
      text-align: center;
      border-top: 1px solid #ddd;
      font-size: 13px;
      font-weight: 500;
      color: #000;
    }

    /* MODAL LOGOUT */
    #logoutModal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.2);
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
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-5px);}
      to {opacity: 1; transform: translateY(0);}
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left back-icon"></i></a>
    <h1>Settings</h1>
    <i id="logoutIcon" class="fa-solid fa-right-from-bracket logout-icon"></i>
  </header>

  <!-- MAIN -->
  <main>
    <div class="form-section">

      <!-- GANTI PASSWORD -->
      <div class="form-group">
        <h2><i class="fa-solid fa-key"></i> Ganti Password</h2>
        <div class="input-row">
          <input type="password" placeholder="Password Lama">
          <input type="password" placeholder="Password Baru">
        </div>
        <div class="input-row">
          <input type="password" placeholder="Konfirmasi Password Lama">
          <input type="password" placeholder="Konfirmasi Password Baru">
        </div>
        <button>SIMPAN</button>
      </div>

      <!-- GANTI EMAIL -->
      <div class="form-group">
        <h2><i class="fa-solid fa-envelope"></i> Ganti Email</h2>
        <div class="input-row">
          <input type="email" placeholder="Email Lama">
          <input type="email" placeholder="Email Baru">
        </div>
        <button>SIMPAN</button>
      </div>

    </div>
  </main>

  <!-- FOOTER -->
  <footer>
    © 2025 RSUD Bangil — Sistem Laporan Kinerja
  </footer>

  <!-- MODAL LOGOUT -->
  <div id="logoutModal">
    <div class="logout-box">
      <h3>Apakah Anda ingin keluar?</h3>
      <div class="logout-buttons">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit">YA</button>
        </form>
        <button id="noBtn">TIDAK</button>
      </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
    const logoutIcon = document.getElementById('logoutIcon');
    const logoutModal = document.getElementById('logoutModal');
    const noBtn = document.getElementById('noBtn');

    logoutIcon.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    noBtn.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });
  </script>

</body>
</html>