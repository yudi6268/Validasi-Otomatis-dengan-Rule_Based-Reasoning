<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panduan Penggunaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', Arial, sans-serif; background: #E3F8F6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); padding: 32px; }
        h1 { color: #0DA45C; font-size: 2rem; margin-bottom: 18px; }
        ul { padding-left: 18px; }
        li { margin-bottom: 12px; font-size: 1.1rem; }
        header { display:flex; align-items:center; padding: 15px 25px; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.1); gap:15px; position:relative; }
        header .title { margin:0; font-size:18px; color:#222; font-weight:600; flex:1; text-align:center; }
        .back-btn { background:none; border:none; font-size:20px; cursor:pointer; color:#009970; transition:0.3s; text-decoration:none; }
        .back-btn:hover { color:#007b5e; }
    </style>
</head>
<body>
        <header>
            <button class="back-btn" onclick="window.history.back();" aria-label="Kembali">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <h1 class="title">Panduan</h1>
        </header>
        <main style="min-height:calc(100vh - 120px);display:flex;align-items:center;justify-content:center;">
            <div class="container" style="max-width:700px;width:100%;margin:40px 0;background:#fff;padding:32px 24px;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.07);">
                <h2 style="text-align:center;font-size:22px;font-weight:700;margin-bottom:24px;">Panduan Penggunaan Aplikasi<br>Sistem Laporan Kinerja</h2>
                <ul style="text-align:left;font-size:16px;line-height:1.7;margin-left:20px;">
                    <li><b>Login ke Sistem</b>
                        <ul>
                            <li>Buka halaman utama aplikasi.</li>
                            <li>Masukkan username (RS_ID) dan password yang telah terdaftar pada sistem.</li>
                            <li>Jika belum memiliki akun, hubungi Administrator atau Tim IT RSUD Bangil untuk pendaftaran akun baru.</li>
                            <li>Pastikan menjaga kerahasiaan akun Anda demi keamanan data pribadi dan kinerja.</li>
                        </ul>
                    </li>
                    <li><b>Mengelola Perjanjian Kinerja</b>
                        <ul>
                            <li>Pilih menu “Perjanjian” pada halaman utama.</li>
                            <li>Klik tombol “Tambah Perjanjian” untuk membuat perjanjian baru.</li>
                            <li>Isi formulir perjanjian sesuai dengan data kinerja yang telah disepakati, seperti:
                                <ul>
                                    <li>Nama dan Jabatan pihak pertama serta pihak kedua,</li>
                                    <li>Judul dan deskripsi perjanjian,</li>
                                    <li>Target dan indikator kinerja.</li>
                                </ul>
                            </li>
                            <li>Setelah semua data lengkap, tekan “Simpan” untuk menyimpan draft atau “Kirim” untuk mengajukan perjanjian.</li>
                            <li>Anda dapat memantau status perjanjian (Menunggu, Disetujui, Ditolak) melalui halaman Daftar Form Perjanjian.</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </main>
        <footer style="background:#fff;text-align:center;font-size:15px;font-weight:700;padding:15px 0;border-top:1px solid #ddd;color:#1B2A41;position:fixed;left:0;bottom:0;width:100vw;z-index:10;">
            © 2026 RSUD Bangil – Sistem Laporan Kinerja
        </footer>
</body>
</html>
