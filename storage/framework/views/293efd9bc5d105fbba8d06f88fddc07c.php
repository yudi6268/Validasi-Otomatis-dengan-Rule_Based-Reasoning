<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Akun</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #E9F8F8;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 380px;
            text-align: center;
        }

        .login-container img {
            height: 60px;
            margin: 0 10px;
        }

        h2 {
            color: #006633;
            font-weight: 600;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        .input-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            background-color: #009970;
            border: none;
            color: white;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #007a59;
        }

        .links {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }

        .links a {
            font-size: 13px;
            color: #009970;
            text-decoration: none;
            font-weight: 500;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .alert-box {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.5;
            border-left: 5px solid;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .alert-box i {
            margin-top: 2px;
            font-size: 16px;
        }

        .alert-content {
            text-align: left;
        }

        .alert-content strong {
            display: block;
            margin-bottom: 4px;
        }

        .alert-content small {
            display: block;
            margin-top: 6px;
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logos">
            <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo pemda">
            <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo rsud">
        </div>
        <h2>Masuk ke Akun Anda</h2>

        <?php if(session('success')): ?>
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    <strong>Sukses!</strong>
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        <?php endif; ?>

        <?php if($errors->has('login')): ?>
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div class="alert-content">
                    <strong>Login Gagal!</strong>
                    <?php echo e($errors->first('login')); ?>

                    <small>
                        <i class="fas fa-lightbulb"></i> 
                        Periksa kembali ID Pegawai dan Password Anda.
                    </small>
                </div>
            </div>
        <?php endif; ?>

        <?php if($errors->has('user_id')): ?>
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div class="alert-content">
                    <strong>Data Tidak Lengkap!</strong>
                    <?php echo e($errors->first('user_id')); ?>

                </div>
            </div>
        <?php endif; ?>

        <?php if($errors->has('password')): ?>
            <div class="alert-box alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div class="alert-content">
                    <strong>Data Tidak Lengkap!</strong>
                    <?php echo e($errors->first('password')); ?>

                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login.post')); ?>">
    <?php echo csrf_field(); ?>
    <div class="input-group">
        <label>ID PEGAWAI (bukan email)</label>
        <input type="text" name="user_id" placeholder="Contoh: RS01, ADM001" required style="width:100%;box-sizing:border-box;">
        <small style="color: #666; display: block; margin-top: 5px;">💡 Gunakan ID Pegawai Anda, bukan email</small>
    </div>

    <div class="input-group">
        <label>PASSWORD</label>
        <div style="display:flex;align-items:center;position:relative;width:100%;max-width:100%;">
            <input type="password" name="password" id="loginPasswordInput" placeholder="Masukkan Password" required style="width:100%;box-sizing:border-box;padding-right:38px;min-width:0;">
            <button type="button" id="toggleLoginPassword" tabindex="-1" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);border:none;background:transparent;padding:0;margin:0;outline:none;width:28px;height:28px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-eye" id="iconLoginPassword" style="font-size:1.1rem;color:#888;"></i>
            </button>
        </div>
    </div>

    <button type="submit">MASUK</button>

    <div class="links">
        <a href="/register">Daftar Akun</a>
        <a href="/forgot-password">Lupa Password?</a>
    </div>
        </form>
    </div>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginPasswordInput = document.getElementById('loginPasswordInput');
        const toggleLoginPassword = document.getElementById('toggleLoginPassword');
        const iconLoginPassword = document.getElementById('iconLoginPassword');
        if(toggleLoginPassword) {
            toggleLoginPassword.addEventListener('click', function() {
                const type = loginPasswordInput.type === 'password' ? 'text' : 'password';
                loginPasswordInput.type = type;
                iconLoginPassword.classList.toggle('fa-eye');
                iconLoginPassword.classList.toggle('fa-eye-slash');
            });
        }
    });
</script><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/auth/login.blade.php ENDPATH**/ ?>