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
      padding: 6px 20px;              
      min-height: 48px;            
      box-shadow: 0 1px 4px rgba(0,0,0,0.08);
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
      margin: 0;
      line-height: 1;
      color: #000;
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
      text-align: center;
      font-size: 13px;
      font-weight: 700;
      padding: 15px 0;
      border-top: 1px solid #ddd;
      color: #1B2A41;
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

    .alert {
      max-width: 800px;
      margin: 0 auto 20px auto;
      padding: 12px 14px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .alert-success {
      background: #E6F6F2;
      color: #0B7A56;
      border-left: 5px solid #0DA45C;
    }

    .alert-danger {
      background: #FFEAEA;
      color: #C62828;
      border-left: 5px solid #E53935;
    }

    .info-text {
      font-size: 12px;
      color: #666;
      margin-top: 8px;
      padding: 8px 12px;
      background: #F0F9FF;
      border-left: 3px solid #009970;
      border-radius: 4px;
      line-height: 1.5;
    }

    .info-text i {
      color: #009970;
      margin-right: 5px;
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
    @if(session('success'))
      <div class="alert alert-success" id="alertSuccess" role="alert">
        <i class="fa-solid fa-circle-check"></i>
        <span>{{ session('success') }}</span>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger" id="alertError" role="alert">
        <i class="fa-solid fa-circle-exclamation"></i>
        <span>
          @if($errors->has('current_password'))
            {{ $errors->first('current_password') }}
          @elseif($errors->has('new_password'))
            {{ $errors->first('new_password') }}
          @elseif($errors->has('current_email'))
            {{ $errors->first('current_email') }}
          @elseif($errors->has('new_email'))
            {{ $errors->first('new_email') }}
          @else
            {{ $errors->first() }}
          @endif
        </span>
      </div>
    @endif

    <div class="form-section">

      <!-- GANTI PASSWORD -->
      <form class="form-group" id="passwordForm" method="POST" action="{{ route('settings.password.update') }}">
        @csrf

        <!-- USER IDENTIFIER (HIDDEN - UNTUK ACCESSIBILITY) -->
        <input
          type="email" name="email" value="{{ auth()->user()->email }}" autocomplete="username" hidden>

        <h2><i class="fa-solid fa-key"></i> Ganti Password</h2>

        <input
          type="password" name="current_password" id="current_password" placeholder="Password Lama" autocomplete="current-password" required style="margin-bottom:10px;">
        <input
          type="password" name="new_password" id="new_password" placeholder="Password Baru (min. 8 karakter)" autocomplete="new-password" required minlength="8" style="margin-bottom:10px;">
        <input
          type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Konfirmasi Password Baru" autocomplete="new-password" required style="margin-bottom:15px;">
        
        <div class="info-text">
          <i class="fa-solid fa-info-circle"></i>
          <strong>Catatan:</strong> Setelah password diubah, Anda akan otomatis logout dan harus login kembali dengan password baru.
        </div>
        
        <button type="submit" style="margin-top:15px;">SIMPAN</button>
      </form>

      <!-- GANTI EMAIL -->
      <form id="emailForm" class="form-group" method="POST" action="{{ route('settings.email.update') }}">
        @csrf

        <h2><i class="fa-solid fa-envelope"></i> Ganti Email</h2>

        <div class="input-row">
          <input type="email" name="current_email" id="current_email" placeholder="Email Lama" value="{{ auth()->user()->email }}" readonly required>
          <input type="email" name="new_email" id="new_email" placeholder="Email Baru" required>
        </div>

        <div class="info-text">
          <i class="fa-solid fa-info-circle"></i>
          <strong>Catatan:</strong> Email baru akan digunakan untuk login dan fitur lupa password. Pastikan email yang Anda masukkan aktif dan dapat diakses.
        </div>

        <button type="submit" style="margin-top:15px;">SIMPAN</button>
      </form>

      <script>
        // Password form validation and confirmation
        const passwordForm = document.getElementById('passwordForm');
        if (passwordForm) {
          passwordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('new_password_confirmation').value;
            
            if (newPassword !== confirmPassword) {
              e.preventDefault();
              alert('Password baru dan konfirmasi password tidak cocok!');
              return false;
            }
            
            if (newPassword.length < 8) {
              e.preventDefault();
              alert('Password baru minimal 8 karakter!');
              return false;
            }
            
            if (!confirm('Anda akan logout otomatis setelah password diubah. Lanjutkan?')) {
              e.preventDefault();
              return false;
            }
          });
        }

        // Email form validation and confirmation
        const emailForm = document.getElementById('emailForm');
        if (emailForm) {
          emailForm.addEventListener('submit', function(e) {
            const currentEmail = document.getElementById('current_email').value;
            const newEmail = document.getElementById('new_email').value;
            
            if (currentEmail === newEmail) {
              e.preventDefault();
              alert('Email baru tidak boleh sama dengan email lama!');
              return false;
            }
            
            if (!confirm('Yakin ingin mengubah email? Email baru akan digunakan untuk login dan reset password.')) {
              e.preventDefault();
              return false;
            }
          });
        }
      </script>
  </main>

  <!-- FOOTER -->
  <footer>
    © 2025 RSUD Bangil – Sistem Perjanjian Kinerja
  </footer>

  <!-- MODAL LOGOUT -->
  <div id="logoutModal">
    <div class="logout-box">
      <h3>Apakah Anda ingin keluar?</h3>
      <div class="logout-buttons">
        <button type="button" id="yesBtn">YA</button>
        <button id="noBtn">TIDAK</button>
      </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
    // Auto-hide alerts after 5 seconds
    const alertSuccess = document.getElementById('alertSuccess');
    const alertError = document.getElementById('alertError');
    
    if (alertSuccess) {
      setTimeout(() => {
        alertSuccess.style.transition = 'opacity 0.5s';
        alertSuccess.style.opacity = '0';
        setTimeout(() => alertSuccess.remove(), 500);
      }, 5000);
    }
    
    if (alertError) {
      setTimeout(() => {
        alertError.style.transition = 'opacity 0.5s';
        alertError.style.opacity = '0';
        setTimeout(() => alertError.remove(), 500);
      }, 7000);
    }

    // Logout modal handlers
    const logoutIcon = document.getElementById('logoutIcon');
    const logoutModal = document.getElementById('logoutModal');
    const noBtn = document.getElementById('noBtn');
    const yesBtn = document.getElementById('yesBtn');

    // Open logout modal
    logoutIcon.addEventListener('click', () => {
      logoutModal.style.display = 'flex';
    });

    // Close modal
    noBtn.addEventListener('click', () => {
      logoutModal.style.display = 'none';
    });

    // Handle logout with AJAX for faster response
    yesBtn.addEventListener('click', async () => {
      try {
        // Disable button to prevent double-click
        yesBtn.disabled = true;
        yesBtn.textContent = 'YA';

        const response = await fetch('{{ route('logout') }}', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        if (response.ok) {
          // Broadcast logout to all tabs/windows
          localStorage.setItem('logout-event', Date.now().toString());
          
          // Redirect to login
          window.location.href = '{{ route('login') }}';
        } else {
          alert('Terjadi kesalahan saat logout. Silakan coba lagi.');
          yesBtn.disabled = false;
          yesBtn.textContent = 'YA';
        }
      } catch (error) {
        console.error('Logout error:', error);
        alert('Terjadi kesalahan saat logout. Silakan coba lagi.');
        yesBtn.disabled = false;
        yesBtn.textContent = 'YA';
      }
    });

    // Listen for logout events from other tabs/windows
    window.addEventListener('storage', (e) => {
      if (e.key === 'logout-event') {
        // Another tab logged out, redirect this tab too
        window.location.href = '{{ route('login') }}';
      }
    });

    // Check if user is still authenticated on focus
    window.addEventListener('focus', async () => {
      try {
        const response = await fetch('{{ route('home') }}', {
          method: 'HEAD',
          credentials: 'same-origin'
        });
        
        // If redirected to login, user session expired
        if (response.redirected && response.url.includes('login')) {
          window.location.href = '{{ route('login') }}';
        }
      } catch (error) {
        console.error('Session check error:', error);
      }
    });
  </script>

</body>
</html>