<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profil Saya - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
  <style>
    body { 
      margin: 0; 
      font-family: 'Poppins', sans-serif; 
      background-color: #E3F8F6; 
      min-height: 100vh; 
      color: #222; 
      display: flex; 
      flex-direction: column; 
    }

    header { 
      background:#fff; 
      padding:8px 20px; 
      box-shadow:0 2px 6px rgba(0,0,0,0.05); 
      display:flex; 
      align-items:center; 
      gap:12px; 
      min-height:48px; 
      position:relative; 
    }

    header .back-btn { 
      background:none; 
      border:none; 
      color:#00B5A0; 
      font-size:22px; 
      cursor:pointer; 
      position:absolute; 
      left:20px; 
      top:50%; 
      transform:translateY(-50%); 
    }

    header .title { 
      width:100%; 
      text-align:center; 
      font-size:18px; 
      font-weight:700; 
      display:block; 
    }

    main { 
      flex:1; 
      display:flex; 
      align-items:center; 
      padding: 40px 20px 80px;
    }

    .profile-card { 
      background:#fff; 
      border-radius:16px; 
      box-shadow:0 2px 12px rgba(0,0,0,0.07); 
      padding:40px 32px; 
      max-width: 1000px;
      width:100%; 
      display:flex; 
      gap:48px; 
      align-items:center; 
    }

    .profile-form {
      display: grid;
      grid-template-columns: 180px 1fr;
      gap: 12px 25px;
      align-items: center;
    }

    .profile-form label { 
      text-align:left; 
      font-weight:600; 
      font-size:14px; 
      padding-left:10px; 
    }
    
    .profile-form input[type="text"], 
    .profile-form input[type="email"] { 
      padding:10px; 
      border-radius:8px; 
      border:1px solid #ccc; 
      width: 100%;
      min-width: 250px; 
      font-size:14px; 
      background:#F7F7F7; 
    }

    .input-wrap { 
      width:100%; 
      display:flex; 
    }

    .edit-btn { 
      background:#00B5A0; 
      color:#fff; 
      border:none; 
      padding:12px 0; 
      border-radius:8px; 
      font-weight:600; 
      font-size:16px; 
      cursor:pointer; 
      margin-top:12px; 
      width:180px; 
      align-self:center; 
      grid-column:span 2; 
    }

    .profile-side { 
      width:260px; 
      display:flex; 
      flex-direction:column; 
      align-items:center; 
      gap:24px; 
    }

    .photo { 
      width:120px; 
      height:120px; 
      border-radius:50%; 
      background:#eee; 
      overflow:hidden; 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      font-weight:600; 
      color:#888; 
      font-size:15px; 
      border:3px solid #fff; 
      box-shadow:0 4px 10px rgba(0,0,0,0.1); 
    }

    .photo img { 
      width:100%; 
      height:100%; 
      object-fit:cover; 
    }

    .signature-box { 
      width:100%; 
      height:110px; 
      border-radius:12px; 
      border:2px dashed #bbb; 
      display:flex; 
      align-items:center; 
      justify-content:center; 
      cursor:pointer; 
      background:#fafafa; 
      font-size:15px; 
      color:#888; 
    }

    .signature-box img { 
      max-width:100%; 
      max-height:100%; 
    }

    .upload-btn { 
      background:#00B5A0; 
      color:#fff; 
      border:none; 
      padding:8px 18px; 
      border-radius:8px; 
      font-weight:600; 
      font-size:15px; 
      cursor:pointer; 
      margin-top:8px; 
      width:100%; 
      text-align:center; 
      display:inline-block; 
    }

    footer {
    background:#fff;
    text-align:center;
    font-size:15px;
    font-weight:700;
    padding:15px 0;
    border-top:1px solid #ddd;
    color:#1B2A41;
  }
  </style>
</head>
<body>
  <header>
    <a href="javascript:history.back()" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a>
    <span class="title">Profil Saya</span>
  </header>
  <main>
    <div class="profile-card">
      <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data" class="profile-form" id="profileForm">
        @csrf
        <label for="nama">Nama Pegawai</label>
        <div class="input-wrap"><input type="text" id="nama" name="nama" value="{{ $user->nama }}" readonly></div>
        <label for="id_pegawai">ID Pegawai</label>
        <div class="input-wrap"><input type="text" id="id_pegawai" name="id_pegawai" value="{{ $user->id_pegawai }}" readonly></div>
        <label for="nip">NIP</label>
        <div class="input-wrap"><input type="text" id="nip" name="nip" value="{{ $user->nip }}" readonly></div>
        <label for="emailInput">Email</label><div class="input-wrap"><input type="email" id="emailInput" name="email" value="{{ auth()->user()->email }}" readonly disabled></div>
        <label for="jabatan">Jabatan</label>
        <div class="input-wrap"><input type="text" id="jabatan" name="jabatan" value="{{ $user->jabatan }}" readonly></div>
        <label for="pangkat">Pangkat</label>
        <div class="input-wrap"><input type="text" id="pangkat" name="pangkat" value="{{ $user->pangkat }}" readonly></div>
        <div style="grid-column:span 2;display:flex;justify-content:center;gap:10px;margin-top:25px;">
          <button type="button" id="editBtn" class="edit-btn" style="background:#00B5A0;">Edit Profil</button>
          <button type="submit" id="saveBtn" class="edit-btn" style="background:#009970;display:none;">Simpan Profil</button>
        </div>
      </form>
      <div class="profile-side">
        <div class="photo" id="photoPreview" style="margin-bottom:16px;">
          @if($user->foto_profil && str_starts_with($user->foto_profil, 'http'))
              <img src="{{ $user->foto_profil }}">
          @elseif($user->foto_profil)
              <img src="{{ asset('storage/'.$user->foto_profil) }}">
          @else
              FOTO PROFIL
          @endif
        </div>
        <button type="button" class="upload-btn" id="openFotoModal" style="width:100%;margin-bottom:12px;">Upload Foto</button>
        <!-- Modal Upload Foto -->
        <div id="fotoModal" class="modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
          <form method="POST" action="{{ route('profil.upload_foto') }}" enctype="multipart/form-data" style="background:#fff;padding:30px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.1);text-align:center;position:relative;min-width:350px;">

            @csrf
            <span style="position:absolute;top:10px;right:15px;font-size:22px;cursor:pointer;" onclick="closeFotoModal()">&times;</span>
            <h3 style="margin-bottom:15px;">Upload Foto Profil</h3>
            <input type="file" id="fotoInput" name="foto_profil" accept="image/*" style="margin-bottom:15px;">
            <div id="croppieContainer" style="margin-bottom:10px;"></div>
          <div id="croppie-instruction"
              style="text-align:center;font-size:13px;color:#888;margin-bottom:10px;">
            Geser foto untuk drag, gunakan scroll untuk zoom.
          </div>

            <button type="button" id="rotateBtn" class="upload-btn" style="width:120px;margin-bottom:10px;">Putar</button>
            <button type="submit" id="saveCroppedPhoto" class="upload-btn" style="width:120px;">Simpan</button>
          </form>
        </div>

        <div class="signature-box" id="signatureBox" style="cursor:pointer;margin-bottom:12px;">
          @if($user->tanda_tangan && str_starts_with($user->tanda_tangan, 'http'))
            <img src="{{ $user->tanda_tangan }}">
          @elseif($user->tanda_tangan)
            <img src="{{ asset('storage/'.$user->tanda_tangan) }}">
          @else
            Klik untuk tanda tangan
          @endif
        </div>

        <button type="button" class="upload-btn" id="openTTDModal" style="width:100%;">Upload Tanda Tangan</button>
        <!-- Modal Upload/Tanda Tangan -->
        <div id="ttdModal" class="modal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);justify-content:center;align-items:center;z-index:999;">
          <form method="POST" action="{{ route('profil.upload_ttd') }}" enctype="multipart/form-data" style="background:#fff;padding:30px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.1);text-align:center;position:relative;min-width:350px;">
            @csrf
            <span style="position:absolute;top:10px;right:15px;font-size:22px;cursor:pointer;" onclick="closeTTDModal()">&times;</span>
            <h3 style="margin-bottom:15px;">Upload/Tanda Tangan</h3>
            <input type="file" id="ttdInput" name="tanda_tangan" accept="image/*" style="margin-bottom:15px;">
            <canvas id="ttdCanvas" width="350" height="120" style="border:1px solid #ccc;border-radius:8px;background:#fafafa;"></canvas>
            <div style="margin-top:15px;display:flex;gap:10px;justify-content:center;">
              <button type="button" onclick="clearTTDCanvas()" class="upload-btn" style="width:120px;">Hapus</button>
              <button type="submit" id="saveTTDCanvas" class="upload-btn" style="width:120px;">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
   <script>
document.addEventListener('DOMContentLoaded', function () {

  // ===== EDIT PROFIL =====
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');

  if (editBtn && saveBtn) {
    editBtn.addEventListener('click', function () {
      const inputs = document.querySelectorAll('#profileForm input[type="text"], #profileForm input[type="email"]');
      inputs.forEach(i => {
        if (!['id_pegawai', 'nip', 'email'].includes(i.name)) i.removeAttribute('readonly');
      });
      saveBtn.style.display = 'inline-block';
      editBtn.style.display = 'none';
    });

    saveBtn.addEventListener('click', function (e) {
      e.preventDefault();
      const form = document.getElementById('profileForm');
      if (form) form.submit();
    });
  }

  // ===== MODAL FOTO =====
  const fotoModal = document.getElementById('fotoModal');
  const openFotoModal = document.getElementById('openFotoModal');
  const fotoInput = document.getElementById('fotoInput');
  const croppieContainer = document.getElementById('croppieContainer');
  const rotateBtn = document.getElementById('rotateBtn');

  let croppie = null;

  if (openFotoModal && fotoModal) {
    openFotoModal.onclick = () => fotoModal.style.display = 'flex';
    window.closeFotoModal = () => { fotoModal.style.display = 'none'; };
  }

  if (fotoInput && croppieContainer) {
    fotoInput.onchange = function () {
      croppieContainer.innerHTML = '';
      croppie = new Croppie(croppieContainer, {
        viewport: { width: 140, height: 140, type: 'circle' },
        boundary: { width: 200, height: 200 }
      });
      const reader = new FileReader();
      reader.onload = e => croppie.bind({ url: e.target.result });
      reader.readAsDataURL(this.files[0]);
    };
  }

  if (rotateBtn) rotateBtn.onclick = () => croppie && croppie.rotate(90);

  const fotoForm = fotoModal?.querySelector('form');
  if (fotoForm) {
    fotoForm.addEventListener('submit', function (e) {
      e.preventDefault();
      if (!croppie || !fotoInput.files.length) {
        alert('Silakan pilih foto terlebih dahulu');
        return;
      }
      croppie.result({ type: 'base64', size: 'viewport' }).then(out => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'croppedPhotoData';
        input.value = out;
        this.appendChild(input);
        this.submit();
      });
    });
  }

  // ===== MODAL TTD =====
    const ttdModal = document.getElementById('ttdModal');
    const ttdForm = ttdModal?.querySelector('form'); 
    const canvas = document.getElementById('ttdCanvas');
    const ctx = canvas?.getContext('2d');

    if (openTTDModal && ttdModal) {
      openTTDModal.onclick = () => ttdModal.style.display = 'flex';
      window.closeTTDModal = () => { ttdModal.style.display = 'none'; };
    }

    if (canvas && ctx) {
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.strokeStyle = '#222';

      let drawing = false;

      canvas.onmousedown = e => {
        drawing = true;
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
      };

      canvas.onmousemove = e => {
        if (!drawing) return;
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
      };

      canvas.onmouseup = canvas.onmouseleave = () => drawing = false;
    }

    if (ttdForm && canvas) { 
      ttdForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'signature_data';
        input.value = canvas.toDataURL();
        this.appendChild(input);
        this.submit();
      });
    }

  // ===== CLEAR TTD =====
  window.clearTTDCanvas = function () {
    if (ctx && canvas) ctx.clearRect(0, 0, canvas.width, canvas.height);
  };

});

    </script>
<footer>
    © 2025 RSUD Bangil – Sistem Perjanjian Kinerja
</footer>
</body>
</html>