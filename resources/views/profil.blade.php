<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #D8F3F1;
    }

    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: #fff;
      padding: 15px 40px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      position: sticky;
      top: 0;
    }

    main {
      display: flex;
      justify-content: center;
      padding: 50px 0;
    }

    .profile-container {
      display: flex;
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      gap: 70px;
      width: 90%;
      max-width: 1000px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    form {
      flex: 1;
      display: grid;
      grid-template-columns: 150px 1fr;
      gap: 12px 20px;
      align-items: center; 
    }

    label {
      text-align: left;  
      font-weight: 600;
      font-size: 14px;
      padding-left: 10px; 
    }

    input[type="text"], input[type="email"] {
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      width: 100%;
      font-size: 14px;
    }

    .input-wrap {
      width: 100%;
      display: flex;
    }

    .edit-btn, .save-btn {
      grid-column: span 2;
      margin-top: 25px;
      padding: 12px 18px;     
      border: none;
      border-radius: 8px;
      background: #009970;
      font-size: 15px;        
      color: white;
      cursor: pointer;
      font-weight: 600;
      height: 40px;          
    }

    .right-section {
      width: 280px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 25px;
    }

    .photo {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      background: #eee;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: 3px solid #fff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .photo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .btn-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
      width: 100%;
    }

    .btn { padding: 10px; border: none; border-radius: 8px; width: 100%; font-weight: 600; cursor: pointer; }
    .btn-upload { background: #009970; color: white; }
    .btn-delete { background: #d9534f; color: white; }

    .signature-box {
      width: 100%;
      height: 160px;
      border-radius: 12px;
      border: 2px dashed #bbb;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      background: #fafafa;
    }

    .signature-box img {
      max-width: 100%;
      max-height: 100%;
    }

    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      position: relative;
      text-align: center;
    }

    .close-modal {
      position: absolute;
      right: 15px; top: 10px;
      font-size: 20px;
      cursor: pointer;
    }

    .signature-actions {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 15px;
    }

    .ttd-button-group {
      display: flex;
      justify-content: center;
      gap: 15px; 
      margin-bottom: 20px; 
    }

    footer {
      text-align: center;
      padding: 15px;
      font-weight: 600;
      background: #fff;
      border-top: 1px solid #ddd;
    }

    .photo-actions {
      margin-top: 15px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .photo-actions .btn {
      padding: 8px 18px;
      font-size: 13px;
    }

    .header-title {
      font-weight: 700;
      flex-grow: 1;
      text-align: center;
      margin-right: 35px; 
    }

        .upload-preview {
        text-align: center;
    }

    .upload-box {
        width: 380px;
        height: 200px;
        border: 2px dashed #ccc;
        border-radius: 12px;
        background: #fafafa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px auto;
    }

    .upload-box img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
    }
  </style>
</head>
<body>

<header>
  <i class="fa-solid fa-arrow-left"
   onclick="history.back()"
   style="cursor:pointer; font-size: 20px; color:#009970;">
  </i>
  <div class="header-title">Profil Saya</div>
  <div style="width:20px;"></div>
</header>

<main>
  <div class="profile-container">

    <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
      @csrf

      <label>Nama Pegawai</label>
      <div class="input-wrap">
      <input type="text" name="nama" value="{{ $user->nama }}" readonly>
    </div>

      <label>ID Pegawai</label>
      <div class="input-wrap">
      <input type="text" name="id_pegawai" value="{{ $user->id_pegawai }}" readonly>
    </div>

      <label>NIP</label>
      <div class="input-wrap">
      <input type="text" name="nip" value="{{ $user->nip }}" readonly>
    </div>

      <label>Email</label>
      <div class="input-wrap">
      <input type="text" name="email" value="{{ $user->email }}" readonly>
    </div>

      <label>Jabatan</label>
      <div class="input-wrap">
      <input type="text" name="jabatan" value="{{ $user->jabatan }}" readonly>
    </div>

      <label>Pangkat</label>
      <div class="input-wrap">
      <input type="text" name="pangkat" value="{{ $user->pangkat }}" readonly>
    </div>

      <label>Divisi</label>
      <div class="input-wrap">
      <input type="text" name="divisi" value="{{ $user->divisi }}" readonly>
    </div>

      <input type="hidden" name="signature_data" id="signatureData">
      <input type="hidden" name="croppedPhotoData" id="croppedPhotoData">

      <button type="button" id="editBtn" class="edit-btn">Edit Profil</button>
      <button type="submit" id="saveBtn" class="save-btn" style="display:none;">Simpan Perubahan</button>
    </form>

    <div class="right-section">

      <div class="photo" id="photoPreview">
        @if($user->foto_profil && str_starts_with($user->foto_profil, 'http'))
            <img src="{{ $user->foto_profil }}">
        @elseif($user->foto_profil)
            <img src="{{ asset('storage/'.$user->foto_profil) }}">
        @else
            FOTO PROFIL
        @endif
    </div>

      <div class="btn-group">
        <button class="btn btn-upload" id="uploadBtn">Upload Foto</button>
        <button class="btn btn-delete" id="deletePhotoBtn">Hapus Foto</button>
      </div>

      <div class="signature-box" id="signatureBox">
        @if($user->tanda_tangan && str_starts_with($user->tanda_tangan, 'http'))
            <img src="{{ $user->tanda_tangan }}">
        @elseif($user->tanda_tangan)
            <img src="{{ asset('storage/'.$user->tanda_tangan) }}">
        @else
            Klik untuk tanda tangan
        @endif
      </div>
    </div>
  </div>
</main>

<footer>
  © 2025 RSUD Bangil | Dikelola oleh Tim IT
</footer>

<!-- Modal Foto Profil -->
<div class="modal" id="photoModal">
  <div class="modal-content">
    <span class="close-modal" onclick="closePhotoModal()">&times;</span>
    <h3>Edit Foto Profil</h3>

    <div id="croppieContainer"></div>

    <div class="photo-actions">
      <button class="btn btn-upload" id="rotateBtn">Putar</button>
      <button class="btn btn-upload" id="saveCroppedPhoto">Simpan</button>
    </div>
  </div>
</div>

<!-- Modal Tanda Tangan -->
<div class="modal" id="signatureModal">
  <div class="modal-content">
    <span class="close-modal" onclick="closeSignatureModal()">&times;</span>
    <h3>Tanda Tangan</h3>

<div class="ttd-button-group">
  <button class="btn btn-upload" onclick="openSignatureCanvas()">Buat TTD</button>
  <button class="btn btn-upload" onclick="openSignatureUpload()">Upload TTD</button>
</div>

<div id="signatureCanvasContainer" style="display:none;">
  <canvas id="signaturePad" width="400" height="200" style="border:1px solid #ccc;border-radius:8px;"></canvas>

  <div class="signature-actions">
    <button class="btn btn-upload" id="clearBtn">Hapus</button>
    <button class="btn btn-upload" id="saveSignature">Simpan</button>
<input type="file" id="signatureUploadInput" accept="image/*" style="display:none;">
  </div>
</div>

<div id="signatureUploadPreview" class="upload-preview" style="display:none;">
    <h4 style="margin-bottom:10px; font-weight:600;">Upload TTD</h4>

    <div class="upload-box">
        <img id="uploadedSignatureImg">
    </div>

    <button class="btn btn-upload" style="margin-top:15px;" onclick="saveUploadedSignature()">
        Simpan
    </button>
</div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
let croppie = null;
let signaturePad = null;

/* === EDIT MODE === */
document.getElementById('editBtn').addEventListener('click', () => {
  document.querySelectorAll("input").forEach(i => i.removeAttribute("readonly"));
  document.getElementById('editBtn').style.display = "none";
  document.getElementById('saveBtn').style.display = "block";
  document.getElementById('saveBtn').style.marginTop = "25px";
});

/* === FILE INPUT === */
const fotoInput = document.createElement("input");
fotoInput.type = "file";
fotoInput.accept = "image/*";
fotoInput.style.display = "none";
document.body.appendChild(fotoInput);

/* === OPEN FILE === */
document.getElementById("photoPreview").onclick =
document.getElementById("uploadBtn").onclick = () => fotoInput.click();

/* === OPEN CROPPY MODAL === */
fotoInput.addEventListener("change", () => {
  document.getElementById("photoModal").style.display = "flex";

  const container = document.getElementById("croppieContainer");
  container.innerHTML = "";

  croppie = new Croppie(container, {
    viewport: { width: 180, height: 180, type: 'circle' },
    boundary: { width: 280, height: 280 },
    enableOrientation: true
  });

  let reader = new FileReader();
  reader.onload = e => croppie.bind({ url: e.target.result });
  reader.readAsDataURL(fotoInput.files[0]);
});

/* === ROTATE === */
document.getElementById("rotateBtn").onclick = () => croppie.rotate(90);

/* === SAVE CROPPED === */
document.getElementById("saveCroppedPhoto").onclick = () => {
  croppie.result({ type: "base64", size: "viewport" }).then(out => {
    document.getElementById("photoPreview").innerHTML = `<img src="${out}">`;
    document.getElementById("croppedPhotoData").value = out;
    closePhotoModal();
  });
};

function closePhotoModal(){
  document.getElementById("photoModal").style.display = "none";
}

/* === DELETE PHOTO === */
document.getElementById("deletePhotoBtn").onclick = () => {
  if(confirm("Hapus foto profil?")){
    document.getElementById("croppedPhotoData").value = "__DELETE_PHOTO__";
    document.getElementById("saveBtn").click();
  }
};

/* === SIGNATURE === */
document.getElementById("signatureBox").onclick = () => {
  document.getElementById("signatureModal").style.display = "flex";
  initSignaturePad();
};

function initSignaturePad(){
  const canvas = document.getElementById("signaturePad");
  signaturePad = new SignaturePad(canvas, { penColor: "blue" });
}

document.getElementById("clearBtn").onclick = () => signaturePad.clear();

document.getElementById("saveSignature").onclick = () => {
  if(signaturePad.isEmpty()){
    alert("Silahkan tanda tangan dulu.");
    return;
  }

  let dataURL = signaturePad.toDataURL();
  document.getElementById("signatureData").value = dataURL;
  document.getElementById("signatureBox").innerHTML = `<img src="${dataURL}">`;
  closeSignatureModal();
};

function closeSignatureModal(){
  document.getElementById("signatureModal").style.display = "none";
}

function openSignatureCanvas(){
  document.getElementById("signatureCanvasContainer").style.display = "block";
  document.getElementById("signatureUploadPreview").style.display = "none";
  initSignaturePad();
}

function openSignatureUpload(){
  document.getElementById("signatureUploadInput").click();
}

document.getElementById("signatureUploadInput").addEventListener("change", function(){
  let file = this.files[0];
  if(!file) return;

  let reader = new FileReader();
  reader.onload = e => {
    document.getElementById("uploadedSignatureImg").src = e.target.result;
    document.getElementById("signatureUploadPreview").style.display = "block";
    document.getElementById("signatureCanvasContainer").style.display = "none";
  };
  reader.readAsDataURL(file);
});

function saveUploadedSignature(){
  let imgSrc = document.getElementById("uploadedSignatureImg").src;
  document.getElementById("signatureData").value = imgSrc;
  document.getElementById("signatureBox").innerHTML = `<img src="${imgSrc}">`;
  closeSignatureModal();
}

const uploadTTDInput = document.getElementById("signatureUploadInput");

function openSignatureUpload(){
    uploadTTDInput.click();
}

uploadTTDInput.addEventListener("change", function() {
    let file = this.files[0];
    if(!file) return;

    let reader = new FileReader();
    reader.onload = e => {
        document.getElementById("uploadedSignatureImg").src = e.target.result;
        document.getElementById("signatureUploadPreview").style.display = "block";
        document.getElementById("signatureCanvasContainer").style.display = "none";
    };
    reader.readAsDataURL(file);
});

        </script>
    </body>
</html>