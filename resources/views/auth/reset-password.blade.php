<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #E3F8F6;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 35px 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 20px rgba(0,0,0,0.12);
            text-align: center;
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        h3 {
            color: #006C52;
            font-weight: 600;
            margin: 10px 0 8px;
            font-size: 22px;
        }

        p.subtext {
            color: #666;
            font-size: 12px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
            padding: 12px 45px 12px 12px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }

        .password-container input:focus {
            outline: none;
            border-color: #00966B;
        }

        .password-container input.error {
            border-color: #dc3545;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            user-select: none;
            padding: 5px;
            line-height: 1;
        }

        .toggle-password:hover {
            opacity: 0.7;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 12px;
            line-height: 1.5;
            text-align: left;
        }

        .alert ul {
            margin: 5px 0 0;
            padding-left: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
        }

        button {
            width: 100%;
            background-color: #00966B;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #007a57;
        }

        button:active {
            transform: scale(0.98);
        }

        .back {
            margin-top: 18px;
        }

        .back a {
            text-decoration: none;
            font-size: 13px;
            color: #00966B;
            font-weight: 500;
        }

        .back a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }

            .logo img {
                width: 45px;
                height: 45px;
            }

            h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- LOGO -->
        <div class="logo">
            <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
            <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
        </div>

        <!-- JUDUL -->
        <h3>Buat Password Baru</h3>
        <p class="subtext">Gunakan password yang kuat - minimal 8 karakter.</p>

        <!-- ALERT ERROR SESSION -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>⚠️ Ada kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('reset.post') }}">
            @csrf
            <div class="form-group">
                <div class="password-container">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Password Baru" 
                        required 
                        class="@error('password') error @enderror"
                    >
                    <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                </div>
                @error('password')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <div class="password-container">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Konfirmasi Password" 
                        required
                    >
                    <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
                </div>
            </div>

            <div class="alert alert-warning">
                <strong>💡 Tips:</strong> Gunakan ikon mata (👁️) untuk melihat password yang Anda ketik sebelum menyimpan.
            </div>

            <button type="submit">SIMPAN</button>
        </form>

        <!-- LINK -->
        <div class="back">
            <a href="/login">Kembali ke Login</a>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
</body>
</html>