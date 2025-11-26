<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tentang Aplikasi - RSUD Bangil</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #E3F8F6;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #000;
    }

    /* HEADER */
    header {
      display: flex;
      align-items: center;
      padding: 15px 25px;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      gap: 15px;
    }

    header h1 {
      margin: 0;
      font-size: 18px;
      color: #222;
      font-weight: 600;
    }

    .back-btn {
      background: none;
      border: none;
      font-size: 20px;
      cursor: pointer;
      color: #009970;
      transition: 0.3s;
    }

    .back-btn:hover {
      color: #007b5e;
    }

    /* CONTENT */
    .content {
      background-color: #fff;
      width: 85%;
      max-width: 750px;
      margin: 60px auto;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: justify;
      line-height: 1.6;
    }

    .content img {
      display: block;
      margin: 0 auto 15px;
      width: 70px;
    }

    .content h2 {
      text-align: center;
      font-size: 20px;
      margin-bottom: 20px;
      color: #222;
    }

    .content p,
    .content ol {
      font-size: 14px;
      color: #000;
    }

    .content ol {
      margin-left: 20px;
    }

    .dev-info {
      text-align: center;
      margin-top: 20px;
      font-weight: 600;
    }

    /* FOOTER */
    footer {
      text-align: center;
      padding: 15px;
      background: #fff;
      border-top: 1px solid #ddd;
      font-weight: 600;
      font-size: 13px;
      margin-top: auto;
    }

    header {
      position: relative;
    }

    header h1 {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <button class="back-btn" onclick="window.history.back();">
      <i class="fa-solid fa-arrow-left"></i>
    </button>
    <h1>Tentang Aplikasi</h1>
  </header>

  <!-- CONTENT -->
  <div class="content">
    <img src="{{ asset('images/icon_logo.png') }}" alt="Logo">

    <h2>Sistem Laporan Kinerja</h2>

    <p>
      Sistem Laporan Kinerja merupakan aplikasi berbasis web yang dikembangkan untuk mendukung proses administrasi dan pelaporan kinerja pegawai di lingkungan Rumah Sakit Umum Daerah (RSUD) Bangil.
    </p>

    <p><strong>Aplikasi ini bertujuan untuk:</strong></p>
    <ol>
      <li>Meningkatkan efisiensi dalam pengelolaan data perjanjian dan laporan kinerja pegawai.</li>
      <li>Memfasilitasi proses pelaporan kinerja secara digital, akurat, dan terintegrasi.</li>
      <li>Menyediakan sarana pemantauan capaian kinerja pegawai secara transparan dan berkesinambungan.</li>
      <li>Mendukung implementasi tata kelola pemerintahan yang baik melalui sistem informasi yang efektif.</li>
    </ol>

    <p>
      Dengan hadirnya aplikasi ini, diharapkan seluruh pegawai dapat melakukan pelaporan kinerja dengan lebih mudah, cepat, dan terdokumentasi dengan baik sesuai standar administrasi RSUD Bangil.
    </p>

    <p class="dev-info">
      <strong>Dikembangkan oleh:</strong><br>
      Tim IT RSUD Bangil<br>
      Versi: 1.0.0<br>
      Tahun: 2025
    </p>
  </div>

  <!-- FOOTER -->
  <footer>
    © 2025 RSUD Bangil — Sistem Laporan Kinerja
  </footer>

</body>
</html>