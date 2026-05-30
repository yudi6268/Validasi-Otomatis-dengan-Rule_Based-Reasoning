<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #DDF4F5;
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Header */
    header {
      background: #fff;
      padding: 15px 25px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    header h1 {
      font-size: 18px;
      font-weight: 600;
      color: #222;
      margin: 0;
    }

    .back-btn {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 20px;
      color: #009970;
    }

    /* Main */
    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 20px;
    }

    .contact-container {
      display: flex;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.08);
      max-width: 900px;
      width: 100%;
      padding: 40px;
      gap: 40px;
    }

    .contact-info {
      flex: 1;
    }

    .contact-info h2 {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 25px;
      color: #222;
    }

    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 18px;
    }

    .info-item i {
      font-size: 20px;
      color: #009970;
      margin-top: 3px;
    }

    .info-item div {
      font-size: 14px;
      color: #333;
      line-height: 1.5;
    }

    .info-item a {
      color: #009970;
      text-decoration: none;
    }

    .info-item a:hover {
      text-decoration: underline;
    }

    /* Form */
    .contact-form {
      flex: 1;
      background: #F8FDFC;
      padding: 25px;
      border-radius: 12px;
      box-shadow: inset 0 0 6px rgba(0,0,0,0.05);
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 10px;
      font-family: 'Poppins', sans-serif;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .contact-form textarea {
      resize: none;
      height: 90px;
    }

    .contact-form button {
      width: 100%;
      background: #009970;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }

    .contact-form button:hover {
      background: #007f5a;
    }

    .success-message {
      margin-top: 10px;
      display: none;
      align-items: center;
      gap: 8px;
      color: #009970;
      font-size: 13px;
    }

    .success-message i {
      background: #E6FFF2;
      border-radius: 50%;
      padding: 5px;
    }

    /* Footer */
    footer {
      text-align: center;
      font-size: 13px;
      color: #333;
      background: #fff;
      padding: 10px 0;
      border-top: 1px solid #ddd;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
    <button class="back-btn" onclick="window.history.back()">
      <i class="fa-solid fa-arrow-left"></i>
    </button>
    <h1>Kontak Kami</h1>
  </header>

  <main>
    <div class="contact-container">
      <div class="contact-info">
        <h2>Informasi Kontak</h2>

        <div class="info-item">
          <i class="fa-solid fa-location-dot"></i>
          <div>
            <strong>Alamat</strong><br>
            Jl. Raya Raci - Bangil, Balungbendo, Masangan,<br>
            Kec. Bangil, Pasuruan, Jawa Timur
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-phone"></i>
          <div>
            <strong>Telephone</strong><br>
            +62 821-4231-6268
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-envelope"></i>
          <div>
            <strong>Email</strong><br>
            magangrsudbangil@gmail.com
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-globe"></i>
          <div>
            <strong>Website</strong><br>
            <a href="https://rsudbangil.pasuruankab.go.id/" target="_blank">
              https://rsudbangil.pasuruankab.go.id/
            </a>
          </div>
        </div>

        <div class="info-item">
          <i class="fa-solid fa-clock"></i>
          <div>
            <strong>Jam Operasional</strong><br>
            Senin - Jumat, 08.00 - 16.00 WIB
          </div>
        </div>
      </div>

      <div class="contact-form">
        <form id="contactForm" action="<?php echo e(route('kontak.kirim')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <input type="text" name="nama" placeholder="Nama Lengkap" required>
          <input type="email" name="email" placeholder="Email" required>
          <textarea name="pesan" placeholder="Pesan" required></textarea>
          <button type="submit">KIRIM PESAN</button>

          <div id="successMsg" class="success-message">
            <i class="fa-solid fa-circle-check"></i>
            <span>Pesan Anda telah terkirim. Terima kasih.</span>
          </div>
          <div id="errorMsg" class="success-message" style="color: #dc3545;">
            <i class="fa-solid fa-circle-xmark"></i>
            <span>Gagal mengirim pesan. Silakan coba lagi.</span>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer style="margin-top:40px;background:#fff;text-align:center;font-size:13px;font-weight:700;line-height:1.4;padding:14px 12px;border-top:1px solid #dbe2ea;color:#1B2A41;font-family:'Segoe UI',Tahoma,sans-serif;">© 2026 RSUD Bangil | Validasi Otomatis Laporan Kinerja RSUD Bangil</footer>

  <script>
    const contactForm = document.getElementById('contactForm');
    const successMsg = document.getElementById('successMsg');
    const errorMsg = document.getElementById('errorMsg');

    contactForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(contactForm);
      const submitBtn = contactForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      
      // Disable button dan ubah text
      submitBtn.disabled = true;
      submitBtn.textContent = 'Mengirim...';
      
      try {
        const response = await fetch('<?php echo e(route("kontak.kirim")); ?>', {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        const result = await response.json();
        
        if (result.success) {
          successMsg.style.display = 'flex';
          errorMsg.style.display = 'none';
          contactForm.reset();
          
          setTimeout(() => {
            successMsg.style.display = 'none';
          }, 5000);
        } else {
          errorMsg.querySelector('span').textContent = result.message || 'Gagal mengirim pesan. Silakan coba lagi.';
          errorMsg.style.display = 'flex';
          successMsg.style.display = 'none';
          
          setTimeout(() => {
            errorMsg.style.display = 'none';
          }, 5000);
        }
      } catch (error) {
        console.error('Error:', error);
        errorMsg.style.display = 'flex';
        successMsg.style.display = 'none';
        
        setTimeout(() => {
          errorMsg.style.display = 'none';
        }, 5000);
      } finally {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
      }
    });
  </script>
</body>
</html><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\kontak.blade.php ENDPATH**/ ?>