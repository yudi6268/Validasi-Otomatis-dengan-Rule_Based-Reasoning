<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $__env->yieldContent('title', 'RSUD Bangil'); ?></title>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #D9F3F5;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #000;
    }

    /* ================= HEADER ================= */
    header {
      background-color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    header h1 {
      font-size: 20px;
      color: #009970;
      font-weight: 700;
      margin: 0;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .header-icon {
      color: #009970;
      font-size: 22px;
      cursor: pointer;
      transition: 0.3s;
    }

    .header-icon:hover {
      color: #007b5e;
    }

    #logout-btn {
      color: #e53e3e;
    }
    #logout-btn:hover {
      color: #c0392b;
    }

    /* ================= PROFILE ================= */
    .profile-icon {
      color: #009970;
      font-size: 22px;
      cursor: pointer;
    }

    .profile-menu {
      position: absolute;
      top: 55px;
      right: 10px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      padding: 10px;
      display: none;
      flex-direction: column;
      gap: 8px;
      min-width: 160px;
      z-index: 1000;
    }

    .profile-menu.show {
      display: flex;
    }

    .profile-menu a {
      text-decoration: none;
      color: #333;
      font-size: 14px;
      font-weight: 500;
      padding: 8px 12px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .profile-menu a:hover {
      background: #f0f0f0;
      color: #009970;
    }

    /* ================= MAIN ================= */
    main {
      flex: 1;
      padding: 35px 0;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    /* ================= FOOTER ================= */
    footer {
      text-align: center;
      padding: 15px;
      background: #fff;
      border-top: 1px solid #ddd;
      font-weight: 600;
      font-size: 13px;
    }
  </style>
</head>

<body>

  
  <header style="display: flex; align-items: center; justify-content: space-between; padding: 15px 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #fff;">
    <div class="header-left" style="display: flex; align-items: center; gap: 15px; min-width: 60px;">
      <?php echo $__env->yieldContent('back'); ?>
    </div>
    <div class="header-center" style="flex:1; display: flex; justify-content: center; align-items: center; pointer-events: none;">
      <?php if(!request()->routeIs('dashboard')): ?>
        <h1 style="font-size: 20px; color: #009970; font-weight: 700; margin: 0; pointer-events: auto;"><?php echo $__env->yieldContent('header_title', 'Perjanjian Kinerja'); ?></h1>
      <?php endif; ?>
    </div>
    <?php if(empty($hideHeaderActions)): ?>
    <div class="header-right" style="display: flex; align-items: center; gap: 15px; min-width: 60px; justify-content: flex-end;">
      <i class="fa-solid fa-user profile-icon" id="profile-icon"></i>
      <div class="profile-menu" id="profile-menu">
        <a href="<?php echo e(route('profil')); ?>"><i class="fa-solid fa-user"></i> Profil Saya</a>
        <a href="<?php echo e(route('settings')); ?>"><i class="fa-solid fa-cog"></i> Pengaturan</a></div>
      <i class="fa-solid fa-right-from-bracket header-icon" id="logout-btn" title="Keluar" onclick="(typeof showLogoutModal === 'function' ? showLogoutModal() : document.getElementById('logoutModal')?.style.display = 'flex')"></i>
    </div>
    <?php endif; ?>
  </header>

  
  <main>
    <?php echo $__env->yieldContent('content'); ?>
  </main>

  
  <footer style="margin-top:40px;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

  <?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle profile menu
      var profileIcon = document.getElementById('profile-icon');
      var profileMenu = document.getElementById('profile-menu');
      if (profileIcon && profileMenu) {
        profileIcon.onclick = function(e) {
          e.stopPropagation();
          profileMenu.classList.toggle('show');
        };
        // Hide menu on click outside
        document.addEventListener('click', function(e) {
          if (!profileMenu.contains(e.target) && e.target !== profileIcon) {
            profileMenu.classList.remove('show');
          }
        });
      }
      // Logout modal
      var logoutBtn = document.getElementById('logout-btn');
      if (logoutBtn) {
        logoutBtn.onclick = function() {
          if (typeof showLogoutModal === 'function') showLogoutModal();
          else if (document.getElementById('logoutModal')) document.getElementById('logoutModal').style.display = 'flex';
        };
      }
    });
  </script>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\layouts\app.blade.php ENDPATH**/ ?>