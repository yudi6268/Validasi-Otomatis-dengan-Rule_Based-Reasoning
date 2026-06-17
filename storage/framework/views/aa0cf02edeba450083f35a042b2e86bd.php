<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #e8f8f6; 
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .register-container {
            background-color: white;
            width: 450px;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .logo-container img {
            width: 70px;
            height: auto;
        }

        .register-title {
            text-align: center;
            font-weight: 700;
            color:  #006b46;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 25px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            background-color: #f9f9f9;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
        }

        .toggle-password:focus {
            outline: none;
        }

        .btn-register {
            background-color: #007e59;
            color: white;
            font-weight: 600;
            padding: 12px;
            width: 100%;
            border-radius: 10px;
            border: none;
            transition: 0.3s;
        }

        .btn-register:hover {
            background-color: #007e59;
        }

        .text-login {
            text-align: center;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .text-login a {
            color:#009668;
            text-decoration: none;
        }

        .text-login a:hover {
            text-decoration: underline;
        }

        select.form-control {
            appearance: auto;
            -webkit-appearance: auto;
            -moz-appearance: auto;
            background-image: none;
            padding-right: 15px; 
            background-position: right 15px center; 
        }
    </style>
</head>
<body>
    <div class="register-container">

        <!-- Logo di atas -->
        <div class="logo-container">
            <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" alt="Logo pemda">
            <img src="<?php echo e(asset('images/logo_rsud.png')); ?>" alt="Logo rsud">
        </div>

        <h4 class="register-title">Daftar Akun</h4>
        <p class="subtitle">Isi data berikut untuk membuat akun pegawai baru.</p>

        <div class="alert alert-info" style="font-size: 0.85rem;">
            <i class="fas fa-info-circle"></i>
            <strong>Catatan:</strong> Akun Anda akan menunggu persetujuan admin sebelum dapat login. Anda akan dihubungi setelah akun disetujui.
        </div>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('register.post')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <input type="text" name="id_pegawai" class="form-control" placeholder="ID Pegawai" required>
            </div>
            <div class="mb-3">
                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" name="nip" class="form-control" placeholder="NIP" required>
            </div>
            <div class="mb-3">
                <select name="jabatan" class="form-control" required>
                    <option value="" disabled selected>Pilih Jabatan</option>
                    <option>Direktur</option>
                    <option>Wakil Direktur Umum, Pendidikan dan Penelitian</option>
                    <option>Wakil Direktur Pelayanan</option>
                    <option>Wakil Direktur Perencanaan dan Keuangan</option>
                    
                </select>
            </div>
            <div class="mb-3">
                <input type="text" name="pangkat" class="form-control" placeholder="Pangkat" required>
            </div>
            <div class="mb-3">
                <input type="text" name="divisi" class="form-control" placeholder="Divisi" required>
            </div>
            <div class="mb-3">
                <div class="password-container">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                    <button type="button" class="toggle-password" data-target="password" aria-label="Tampilkan password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="password-strength mt-2" style="display: none;">
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small class="strength-text mt-1 d-block"></small>
                </div>
                <small class="text-muted">
                    Password harus memiliki minimal 8 karakter, huruf besar, huruf kecil, angka, dan simbol
                </small>
            </div>
            <div class="mb-3">
                <div class="password-container">
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="form-control" placeholder="Konfirmasi Password" required>
                    <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Tampilkan konfirmasi password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="password-match mt-1" style="display: none;">
                    <small class="text-danger">Password tidak cocok</small>
                </div>
            </div>

            <button type="submit" class="btn-register" id="submitBtn" disabled>DAFTAR</button>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const passwordInput = document.getElementById('password');
                const confirmInput = document.getElementById('password_confirmation');
                const submitBtn = document.getElementById('submitBtn');
                const strengthMeter = document.querySelector('.password-strength');
                const progressBar = document.querySelector('.progress-bar');
                const strengthText = document.querySelector('.strength-text');
                const matchText = document.querySelector('.password-match');

                function checkPasswordStrength(password) {
                    let strength = 0;
                    const patterns = {
                        length: password.length >= 8,
                        lowercase: /[a-z]/.test(password),
                        uppercase: /[A-Z]/.test(password),
                        numbers: /\d/.test(password),
                        symbols: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                    };

                    // Calculate strength
                    strength = Object.values(patterns).filter(Boolean).length;

                    // Update UI
                    strengthMeter.style.display = 'block';
                    let percentage = (strength / 5) * 100;
                    progressBar.style.width = percentage + '%';

                    // Update colors and text
                    if (strength <= 2) {
                        progressBar.style.backgroundColor = '#dc3545';
                        strengthText.style.color = '#dc3545';
                        strengthText.textContent = 'Password lemah';
                    } else if (strength <= 3) {
                        progressBar.style.backgroundColor = '#ffc107';
                        strengthText.style.color = '#ffc107';
                        strengthText.textContent = 'Password sedang';
                    } else {
                        progressBar.style.backgroundColor = '#28a745';
                        strengthText.style.color = '#28a745';
                        strengthText.textContent = 'Password kuat';
                    }

                    return strength >= 4;
                }

                function checkPasswordMatch() {
                    const password = passwordInput.value;
                    const confirm = confirmInput.value;
                    
                    if (confirm) {
                        matchText.style.display = 'block';
                        if (password === confirm) {
                            matchText.innerHTML = '<small class="text-success">Password cocok</small>';
                            return true;
                        } else {
                            matchText.innerHTML = '<small class="text-danger">Password tidak cocok</small>';
                            return false;
                        }
                    }
                    return false;
                }

                function updateSubmitButton() {
                    const isStrong = checkPasswordStrength(passwordInput.value);
                    const isMatch = checkPasswordMatch();
                    submitBtn.disabled = !(isStrong && isMatch);
                }

                passwordInput.addEventListener('input', updateSubmitButton);
                confirmInput.addEventListener('input', updateSubmitButton);

                document.querySelectorAll('.toggle-password').forEach(button => {
                    button.addEventListener('click', function() {
                        const targetId = this.dataset.target;
                        const targetInput = document.getElementById(targetId);
                        const icon = this.querySelector('i');
                        if (!targetInput || !icon) return;
                        if (targetInput.type === 'password') {
                            targetInput.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            targetInput.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                });
            });
            </script>
        </form>

        <div class="text-login">
            Sudah punya akun? <a href="<?php echo e(route('login')); ?>">Login di sini</a>
        </div>
    </div>
</body>
</html><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/auth/register.blade.php ENDPATH**/ ?>