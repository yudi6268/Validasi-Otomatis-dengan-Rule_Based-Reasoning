<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Validasi LapKin SAKIP RSUD Bangil</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #e7fbf3 0%, #e4f4f0 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .page-wrapper {
            width: 100%;
            max-width: 980px;
        }

        .card {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.14);
            background: rgba(255,255,255,0.98);
        }

        .hero-panel,
        .form-panel {
            padding: 60px 45px;
        }

        .hero-panel {
            background: linear-gradient(180deg, #ecf8f6 0%, #e3f3ee 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            gap: 28px;
        }

        .hero-panel .brand {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid rgba(0, 181, 160, 0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.08);
        }

        .hero-panel .brand img {
            width: 110px;
            height: auto;
        }

        .hero-panel h1 {
            font-size: 30px;
            font-weight: 800;
            color: #007b60;
            line-height: 1.05;
        }

        .hero-panel p {
            color: #4e6b63;
            font-size: 15px;
            line-height: 1.8;
            max-width: 320px;
        }

        .form-panel h2 {
            color: #0f4d3d;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .form-panel .subtitle {
            color: #6b7c78;
            font-size: 14px;
            margin-bottom: 32px;
            line-height: 1.7;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .input-group input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1.5px solid #dde6e3;
            font-size: 14px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #00b39d;
            box-shadow: 0 0 0 4px rgba(0, 179, 157, 0.12);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            color: #8b9a92;
            font-size: 18px;
            padding: 6px;
        }

        .toggle-password:hover {
            color: #009970;
        }

        .helper-text {
            margin-top: 8px;
            color: #7a8d86;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px 16px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, #009970, #00b39d);
            color: white;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 22px rgba(0, 155, 124, 0.24);
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 4px;
            margin-bottom: 20px;
        }

        .form-checkbox input {
            width: 16px;
            height: 16px;
            accent-color: #009970;
        }

        .form-checkbox label {
            color: #4d6f63;
            font-size: 13px;
            font-weight: 600;
        }

        .links {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .links a {
            color: #009970;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: color 0.2s;
        }

        .links a:hover {
            color: #007b60;
            text-decoration: underline;
        }

        .alert-box {
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 13px;
            line-height: 1.7;
        }

        .alert-success {
            background: #e7f8ec;
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .alert-error {
            background: #f8e6e9;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }

        .alert-box i {
            margin-top: 2px;
            font-size: 18px;
        }

        .alert-box ul {
            margin: 0;
            padding-left: 18px;
        }

        @media (max-width: 920px) {
            .card {
                grid-template-columns: 1fr;
            }

            .hero-panel,
            .form-panel {
                padding: 40px 30px;
            }
        }

        @media (max-width: 650px) {
            .hero-panel {
                display: none;
            }

            .form-panel {
                padding: 30px 20px;
            }

            .links {
                flex-direction: column;
            }

            .links a {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="card">
            <div class="hero-panel">
                <div class="brand">
                    <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo RSUD Bangil">
                </div>
                <h1>Validasi LapKin SAKIP RSUD Bangil</h1>
                <p>Validasi Otomatis LapKin SAKIP RSUD Bangil Menggunakan Rule-Based Reasoning.</p>
            </div>

            <div class="form-panel">
                <h2>Selamat Datang!</h2>
                <p class="subtitle">Masuk menggunakan ID Pegawai dan kata sandi Anda untuk melanjutkan.</p>

                <?php if(session('success')): ?>
                    <div class="alert-box alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div class="alert-content">
                            <strong>Sukses!</strong>
                            <?php echo e(session('success')); ?>

                        </div>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert-box alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="alert-content">
                            <strong>Terjadi kesalahan</strong>
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('login.post')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="input-group">
                        <label for="user_id">ID Pegawai</label>
                        <input id="user_id" type="text" name="user_id" placeholder="Contoh: RS01, ADM001" value="<?php echo e(old('user_id')); ?>" required autocomplete="username" />
                        <div class="helper-text">
                            <i class="fas fa-info-circle"></i> Gunakan ID Pegawai Anda, bukan email.
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password" />
                            <button type="button" class="toggle-password" id="toggleLoginPassword" tabindex="-1">
                                <i class="fas fa-eye" id="iconLoginPassword"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-checkbox">
                        <input id="remember" type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?> />
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit">Masuk</button>

                    <div class="links">
                        <a href="<?php echo e(route('forgot.form')); ?>">Lupa Password?</a>
                        <a href="<?php echo e(route('register.form')); ?>">Buat Akun</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggleLoginPassword');
            const icon = document.getElementById('iconLoginPassword');

            if (toggleButton) {
                toggleButton.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\auth\login.blade.php ENDPATH**/ ?>