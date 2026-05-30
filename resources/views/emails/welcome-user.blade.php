<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Telah Dibuat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
        }
        .email-body h2 {
            color: #667eea;
            margin-top: 0;
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box strong {
            color: #667eea;
        }
        .credential-box {
            background-color: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        .credential-box .label {
            font-size: 12px;
            color: #856404;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .credential-box .value {
            font-size: 20px;
            font-weight: bold;
            color: #856404;
            font-family: 'Courier New', monospace;
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            margin-top: 5px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .warning-box strong {
            color: #856404;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Selamat Datang!</h1>
            <p style="margin: 10px 0 0 0;">Akun Anda Telah Dibuat</p>
        </div>

        <div class="email-body">
            <h2>Halo, {{ $user->nama }}!</h2>
            
            <p>Akun Anda untuk Sistem Perjanjian Kinerja RSUD telah berhasil dibuat oleh Administrator.</p>

            <div class="info-box">
                <strong>Informasi Akun:</strong><br>
                <strong>Nama:</strong> {{ $user->nama }}<br>
                <strong>NIP:</strong> {{ $user->nip }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Jabatan:</strong> {{ $user->jabatan }}<br>
                <strong>Role:</strong> {{ ucfirst($user->role) }}
            </div>

            <p><strong>Kredensial Login Anda:</strong></p>

            <div class="credential-box">
                <div class="label">Email Login</div>
                <div class="value">{{ $user->email }}</div>
            </div>

            <div class="credential-box">
                <div class="label">Password</div>
                <div class="value">{{ $password }}</div>
            </div>

            <div class="warning-box">
                <strong>⚠️ Penting:</strong><br>
                • Simpan password ini dengan aman<br>
                • Segera login dan ubah password Anda di menu Settings<br>
                • Jangan bagikan password kepada siapa pun<br>
                • Jika Anda lupa password, hubungi Administrator
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Login Sekarang</a>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6c757d;">
                <strong>Langkah Selanjutnya:</strong><br>
                1. Klik tombol "Login Sekarang" di atas<br>
                2. Masukkan email dan password yang diberikan<br>
                3. Ubah password Anda di menu Settings > Pengaturan Akun<br>
                4. Lengkapi profil Anda jika diperlukan
            </p>
        </div>

        <div class="email-footer">
            <p>Email ini dikirim secara otomatis oleh sistem.<br>
            Jika Anda memiliki pertanyaan, silakan hubungi Administrator.</p>
            <p style="margin-top: 10px;">
                <strong>| Validasi Otomatis Laporan Kinerja RSUD Bangil</strong><br>
               © 2026 RSUD Bangil
            </p>
        </div>
    </div>
</body>
</html>
