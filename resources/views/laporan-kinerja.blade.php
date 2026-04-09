<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Kinerja - RSUD Bangil</title>
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
    main { flex: 1; text-align: center; padding: 60px 20px; }
    .page-title { font-size: 36px; font-weight: 800; margin-bottom: 8px; }
    .page-subtitle { color: #5F6F81; margin-bottom: 40px; }
    .content-box {
      max-width: 760px;
      margin: 0 auto;
      background: #fff;
      border-radius: 22px;
      padding: 45px 35px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .content-box i {
      font-size: 56px;
      color: #00B5A0;
      margin-bottom: 24px;
    }
    .content-box h2 { margin: 0 0 18px; font-size: 30px; }
    .content-box p { margin: 0 0 24px; color: #4A5A6E; line-height: 1.7; }
    .content-box .buttons { display: flex; justify-content: center; gap: 16px; flex-wrap: wrap; }
    .content-box .buttons a {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #00B5A0;
      color: #fff;
      padding: 14px 26px;
      border-radius: 14px;
      text-decoration: none;
      font-weight: 700;
      transition: 0.3s;
    }
    .content-box .buttons a:hover { background: #008F7E; }
    footer { background: #fff; text-align: center; font-size: 13px; font-weight: 700; padding: 15px 0; border-top: 1px solid #ddd; }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
      <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
    </div>
    <nav>
      <a href="{{ route('home') }}">Beranda</a>
      <a href="{{ route('panduan') }}">Panduan</a>
      <a href="{{ route('kontak') }}">Kontak</a>
      <a href="{{ route('tentang') }}">Tentang</a>
    </nav>
  </header>
  <main>
    <div class="page-title">Laporan Kinerja</div>
    <div class="page-subtitle">Halaman laporan kinerja untuk pengguna yang bukan Direktur/Administrator.</div>
    <div class="content-box">
      <i class="fas fa-chart-line"></i>
      <h2>Menu Laporan Kinerja</h2>
      <p>Di halaman ini Anda dapat mengakses laporan kinerja yang berisi hasil input indikator dan target periode triwulan. Fitur detail dapat disesuaikan lebih lanjut jika dibutuhkan.</p>
      <div class="buttons">
        <a href="{{ route('perjanjian.index') }}"><i class="fas fa-file-signature"></i>Perjanjian Kinerja</a>
        <a href="{{ route('home') }}"><i class="fas fa-arrow-left"></i>Kembali</a>
      </div>
    </div>
  </main>
  <footer>© 2025 RSUD Bangil – Sistem Perjanjian Kinerja</footer>
</body>
</html>
