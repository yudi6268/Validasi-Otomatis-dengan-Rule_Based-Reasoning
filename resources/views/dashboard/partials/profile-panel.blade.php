@php
  $dashboardUser = auth()->user();
  $profileTitle = $title ?? 'Profil Saya';
  $profileDescription = $description ?? 'Informasi akun aktif pada dashboard saat ini.';
  $hideDescription = $hideDescription ?? false;
  $hideSummary = $hideSummary ?? false;
  $isEditable = $isEditable ?? false;
  $profilePhotoUrl = null;
  $signatureUrl = null;

  if (!empty($dashboardUser?->foto_profil)) {
      $profilePhotoUrl = str_starts_with($dashboardUser->foto_profil, 'http')
          ? $dashboardUser->foto_profil
          : asset('storage/' . $dashboardUser->foto_profil);
  }

  if (!empty($dashboardUser?->tanda_tangan)) {
      $signatureUrl = str_starts_with($dashboardUser->tanda_tangan, 'http')
          ? $dashboardUser->tanda_tangan
          : asset('storage/' . $dashboardUser->tanda_tangan);
  }
@endphp

<div id="profilePanelContainer" style="display:grid; grid-template-columns:minmax(260px, 320px) minmax(0, 1fr); gap:20px; align-items:start;">
  <input type="hidden" id="profilePanelCsrfToken" value="{{ csrf_token() }}">
  <section style="background:#fff; border-radius:24px; box-shadow:0 18px 40px rgba(0,0,0,0.06); padding:24px; display:grid; gap:18px;">
    <div style="display:grid; justify-items:center; gap:14px; text-align:center;">
      <div id="mainProfilePhotoCircle" style="width:112px; height:112px; border-radius:28px; overflow:hidden; background:#e8f8f3; display:grid; place-items:center; color:#009970; font-size:36px; font-weight:800;">
        @if($profilePhotoUrl)
          <img id="mainProfilePhotoImg" src="{{ $profilePhotoUrl }}?v={{ time() }}" alt="Foto Profil" style="width:100%; height:100%; object-fit:cover;">
        @else
          <span id="mainProfilePhotoPlaceholder">{{ strtoupper(substr($dashboardUser->nama ?? 'U', 0, 1)) }}</span>
        @endif
      </div>
      <div>
        <div style="font-size:24px; font-weight:800; color:#1B2A41;" id="displayNama">{{ $dashboardUser->nama ?? '-' }}</div>
        <div style="font-size:14px; color:#64748b; margin-top:4px;">{{ $dashboardUser->jabatan ?? 'Pengguna Sistem' }}</div>
      </div>
    </div>

    <div id="displayReadOnlySection" style="display:grid; gap:12px; pointer-events:none; cursor:not-allowed; opacity:0.8;">
      <div style="padding:14px 16px; border-radius:16px; background:#f8fcfb;" id="displayEmailBox">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:6px;">Email</div>
        <div style="font-size:14px; font-weight:600; color:#243746; word-break:break-word;" id="displayEmail">{{ $dashboardUser->email ?? '-' }}</div>
      </div>
      <div style="padding:14px 16px; border-radius:16px; background:#f8fcfb;" id="displayNIPBox">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:6px;">NIP</div>
        <div style="font-size:14px; font-weight:600; color:#243746;" id="displayNIP">{{ $dashboardUser->nip ?? '-' }}</div>
      </div>
      <div style="padding:14px 16px; border-radius:16px; background:#f8fcfb;" id="displayPangkatBox">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:6px;">Pangkat</div>
        <div style="font-size:14px; font-weight:600; color:#243746;" id="displayPangkat">{{ $dashboardUser->pangkat ?? '-' }}</div>
      </div>
    </div>
  </section>

  <section style="background:#fff; border-radius:24px; box-shadow:0 18px 40px rgba(0,0,0,0.06); padding:24px; display:grid; gap:18px;">
    <div>
      <h2 style="font-size:28px; font-weight:800; color:#1B2A41; margin-bottom:8px;">{{ $profileTitle }}</h2>
      @if (!$hideDescription)
        <p style="font-size:14px; line-height:1.7; color:#64748b;">{{ $profileDescription }}</p>
      @endif
    </div>

    @if ($isEditable)
      <div style="display:flex; gap:10px;">
        <button type="button" id="editProfileBtn" class="btn btn-primary" style="flex:1; padding:12px 18px; background:#00B5A0; color:#fff; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Edit Profile</button>
      </div>
    @endif

    <div style="display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); gap:16px;">
      <div style="padding:18px 20px; border-radius:20px; background:linear-gradient(180deg, #ffffff 0%, #f7fffb 100%); border:1px solid #ebf5f2;">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:8px;">ID Pegawai</div>
        <div style="font-size:18px; font-weight:700; color:#1B2A41;">{{ $dashboardUser->id_pegawai ?? '-' }}</div>
      </div>
      <div style="padding:18px 20px; border-radius:20px; background:linear-gradient(180deg, #ffffff 0%, #f7fffb 100%); border:1px solid #ebf5f2;">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:8px;">Role</div>
        <div style="font-size:18px; font-weight:700; color:#1B2A41; text-transform:capitalize;">{{ $dashboardUser->role ?? 'user' }}</div>
      </div>
      <div style="padding:18px 20px; border-radius:20px; background:linear-gradient(180deg, #ffffff 0%, #f7fffb 100%); border:1px solid #ebf5f2;">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:8px;">Status Akun</div>
        <div style="font-size:18px; font-weight:700; color:#1B2A41; text-transform:capitalize;">{{ $dashboardUser->status ?? 'aktif' }}</div>
      </div>
      <div style="padding:18px 20px; border-radius:20px; background:linear-gradient(180deg, #ffffff 0%, #f7fffb 100%); border:1px solid #ebf5f2;">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:8px;">Terakhir Diperbarui</div>
        <div style="font-size:18px; font-weight:700; color:#1B2A41;">{{ optional($dashboardUser->updated_at)->format('d M Y') ?? '-' }}</div>
      </div>
    </div>

    <div style="padding:16px; border:1px dashed #d6ebe4; border-radius:16px; background:#f8fcfb;">
      <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:10px;">Tanda Tangan Saat Ini</div>
      <div style="min-height:110px; border-radius:12px; background:#fff; border:1px solid #e4efeb; display:grid; place-items:center; padding:10px;">
        @if($signatureUrl)
          <img id="currentSignatureImg" src="{{ $signatureUrl }}?v={{ time() }}" alt="Tanda Tangan" style="max-width:100%; max-height:90px; object-fit:contain;">
          <span id="currentSignaturePlaceholder" style="display:none; font-size:13px; color:#7b8b95;">Belum ada tanda tangan</span>
        @else
          <img id="currentSignatureImg" alt="Tanda Tangan" style="display:none; max-width:100%; max-height:90px; object-fit:contain;">
          <span id="currentSignaturePlaceholder" style="font-size:13px; color:#7b8b95;">Belum ada tanda tangan</span>
        @endif
      </div>
    </div>

    @if (!$hideSummary)
    <div style="display:grid; grid-template-columns:minmax(0, 1fr) 220px; gap:18px; align-items:start;">
      <div style="padding:20px; border-radius:20px; background:#f8fcfb; border:1px dashed #d6ebe4;">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a; margin-bottom:10px;">Ringkasan</div>
        <p style="font-size:14px; line-height:1.8; color:#52606d;">
          Panel profil ini menampilkan informasi akun aktif langsung di dalam dashboard, sehingga navigasi tetap berada pada halaman yang sama tanpa membuka halaman profil terpisah.
        </p>
      </div>
      <div style="padding:20px; border-radius:20px; background:#f8fcfb; border:1px dashed #d6ebe4; display:grid; gap:10px; justify-items:center; text-align:center; min-height:180px;">
        <div style="font-size:12px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#8aa29a;">Tanda Tangan</div>
        <div style="width:100%; min-height:110px; border-radius:16px; background:#fff; border:1px solid #e4efeb; display:grid; place-items:center; padding:10px;">
          @if($signatureUrl)
            <img src="{{ $signatureUrl }}?v={{ time() }}" alt="Tanda Tangan" style="max-width:100%; max-height:90px; object-fit:contain;">
          @else
            <span style="font-size:13px; color:#7b8b95;">Belum ada tanda tangan</span>
          @endif
        </div>
      </div>
    </div>
    @endif
  </section>
</div>

@if ($isEditable)
<style>
  @keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.96) translateY(8px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }
  
  .edit-modal-box {
    animation: modalFadeIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  .modal-input-group {
    position: relative;
    display: flex;
    align-items: center;
  }
  .modal-input-group i {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 14px;
    transition: color 0.2s ease;
  }
  .modal-input-group input {
    width: 100%;
    padding: 12px 14px 12px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    background: #fafdfc;
    color: #1b2a41;
    outline: none;
    transition: all 0.25s ease;
    font-family: inherit;
  }
  .modal-input-group input:focus {
    border-color: #00B5A0;
    box-shadow: 0 0 0 4px rgba(0, 181, 160, 0.12);
    background: #fff;
  }
  .modal-input-group input:focus + i {
    color: #00B5A0;
  }
  
  .modal-edit-card {
    background: #fff;
    border: 1px solid #ebf5f2;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 153, 112, 0.03);
  }
  
  .modal-btn {
    padding: 12px 22px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    border: none;
  }
  .modal-btn-primary {
    background: linear-gradient(135deg, #00b59a 0%, #009970 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 153, 112, 0.2);
  }
  .modal-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(0, 153, 112, 0.3);
  }
  .modal-btn-secondary {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
  }
  .modal-btn-secondary:hover {
    background: #e2e8f0;
    color: #1e293b;
  }
  .modal-btn-danger {
    background: #fff5f5;
    color: #e53e3e;
    border: 1px solid #fed7d7;
  }
  .modal-btn-danger:hover {
    background: #e53e3e;
    color: #fff;
    border-color: #e53e3e;
  }
  
  .media-preview-box {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    background: #f8fafc;
    padding: 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 12px;
  }
</style>

<div id="profileEditModal" style="position:fixed; inset:0; background:rgba(15,23,42,0.6); backdrop-filter: blur(8px); display:none; align-items:center; justify-content:center; z-index:9999; padding:16px;">
  <div class="edit-modal-box" style="width:min(900px, 100%); max-height:92vh; overflow-y:auto; background:#fff; border-radius:24px; box-shadow:0 30px 80px rgba(0,0,0,0.24); border:1px solid rgba(255,255,255,0.8); display:flex; flex-direction:column;">
    <div style="padding:20px 24px; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; background:linear-gradient(135deg, #f8fcfb 0%, #ffffff 100%); border-top-left-radius:24px; border-top-right-radius:24px;">
      <div>
        <div style="font-size:20px; font-weight:800; color:#1B2A41; display:flex; align-items:center; gap:8px;">
          <i class="fas fa-user-edit" style="color:#00B5A0;"></i> Edit Profil Pengguna
        </div>
        <div style="font-size:13px; color:#64748b; margin-top:2px;">Perbarui data akun, foto profil, dan tanda tangan Anda</div>
      </div>
      <button type="button" id="closeProfileModalBtn" style="border:none; background:rgba(0,0,0,0.03); width:36px; height:36px; border-radius:50%; display:grid; place-items:center; font-size:20px; line-height:1; color:#64748b; cursor:pointer; transition:all 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.1)'; this.style.color='#ef4444';" onmouseout="this.style.background='rgba(0,0,0,0.03)'; this.style.color='#64748b';">&times;</button>
    </div>
    
    <div style="padding:24px; display:grid; grid-template-columns:1.2fr 1.8fr; gap:24px; overflow-y:auto;">
      
      <div style="display:grid; gap:20px; align-content:start;">
        <div class="modal-edit-card">
          <div style="font-size:13px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
            <i class="fas fa-camera" style="color:#00B5A0;"></i> Foto Profil
          </div>
          
          <div class="media-preview-box">
            <div style="position:relative; width:110px; height:110px; border-radius:28px; overflow:hidden; background:#e8f8f3; border:3px solid #fff; box-shadow:0 4px 15px rgba(0,0,0,0.08); display:grid; place-items:center;">
              <img id="modalPhotoPreviewImg" alt="Pratinjau Foto" style="width:100%; height:100%; object-fit:cover; display:none;">
              <span id="modalPhotoPreviewPlaceholder" style="font-size:36px; font-weight:800; color:#009970;"></span>
            </div>
            
            <div style="font-size:11px; color:#64748b; font-weight:500;" id="photoInfoText">Pilih foto format persegi, maks 2MB</div>
            
            <div style="display:flex; gap:8px; width:100%; justify-content:center; margin-top:4px;">
              <input type="file" id="modalPhotoUploadInput" accept="image/*" style="display:none;">
              <button type="button" class="modal-btn modal-btn-secondary" style="padding:8px 16px; font-size:12px; flex:1;" onclick="document.getElementById('modalPhotoUploadInput').click()">
                <i class="fas fa-upload"></i> Upload Foto
              </button>
              <button type="button" id="modalDeletePhotoBtn" class="modal-btn modal-btn-danger" style="padding:8px 12px; font-size:12px; display:none;">
                <i class="fas fa-trash-alt"></i> Hapus
              </button>
            </div>
          </div>
        </div>

        <div class="modal-edit-card">
          <div style="font-size:13px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
            <i class="fas fa-signature" style="color:#00B5A0;"></i> Tanda Tangan
          </div>
          
          <div class="media-preview-box" style="margin-bottom:12px;">
            <div style="font-size:11px; font-weight:700; color:#64748b; width:100%; text-align:left; border-bottom:1px solid #e2e8f0; padding-bottom:4px; margin-bottom:6px;">Tanda Tangan Saat Ini</div>
            <div style="width:100%; min-height:80px; display:grid; place-items:center; background:#fff; border-radius:8px; border:1px solid #e2e8f0; padding:6px;">
              <img id="modalSignaturePreviewImg" alt="Pratinjau Tanda Tangan" style="max-width:100%; max-height:70px; object-fit:contain; display:none;">
              <span id="modalSignaturePreviewPlaceholder" style="font-size:12px; color:#94a3b8;">Belum ada tanda tangan</span>
            </div>
            <button type="button" id="modalDeleteSignatureBtn" class="modal-btn modal-btn-danger" style="padding:6px 12px; font-size:11px; width:100%; display:none; margin-top:6px;">
              <i class="fas fa-trash-alt"></i> Hapus Tanda Tangan
            </button>
          </div>

          <div style="display:grid; gap:10px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:11px; color:#64748b; font-weight:700;">Ganti Tanda Tangan</span>
              <label style="font-size:11px; color:#00B5A0; font-weight:700; cursor:pointer;" onclick="document.getElementById('modalSignatureUploadInput').click()">
                <i class="fas fa-file-image"></i> Upload Gambar
              </label>
              <input type="file" id="modalSignatureUploadInput" accept="image/*" style="display:none;">
            </div>
            
            <div style="border:1px dashed #b0c8c0; border-radius:12px; background:#fff; padding:6px; overflow:hidden; position:relative;">
              <div style="position:absolute; top:6px; left:8px; font-size:9px; color:#94a3b8; font-weight:500; pointer-events:none; opacity:0.7;">Gambar tanda tangan di bawah ini:</div>
              <canvas id="modalSignatureCanvas" width="520" height="130" style="width:100%; height:130px; touch-action:none; cursor:crosshair; border-radius:8px; background:#fff; margin-top:10px;"></canvas>
            </div>
            
            <div style="display:flex; gap:8px;">
              <button type="button" id="modalClearSignatureBtn" class="modal-btn modal-btn-secondary" style="padding:8px 12px; font-size:12px; flex:1;">
                <i class="fas fa-eraser"></i> Bersihkan
              </button>
              <button type="button" id="modalUseDrawnSignatureBtn" class="modal-btn modal-btn-primary" style="padding:8px 12px; font-size:12px; flex:1;">
                <i class="fas fa-check"></i> Gunakan Hasil Gambar
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-edit-card" style="display:grid; gap:16px; align-content:start;">
        <div style="font-size:13px; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px; display:flex; align-items:center; gap:6px;">
          <i class="fas fa-id-card" style="color:#00B5A0;"></i> Data Personal
        </div>
        
        <div>
          <label style="font-size:12px; font-weight:700; color:#64748b; display:block; margin-bottom:6px;">Nama Lengkap</label>
          <div class="modal-input-group">
            <input type="text" id="modalEditNama" value="{{ $dashboardUser->nama ?? '' }}" placeholder="Nama Lengkap">
            <i class="fas fa-user"></i>
          </div>
        </div>

        <div>
          <label style="font-size:12px; font-weight:700; color:#64748b; display:block; margin-bottom:6px;">Alamat Email</label>
          <div class="modal-input-group">
            <input type="email" id="modalEditEmail" value="{{ $dashboardUser->email ?? '' }}" placeholder="Email">
            <i class="fas fa-envelope"></i>
          </div>
        </div>

        <div>
          <label style="font-size:12px; font-weight:700; color:#64748b; display:block; margin-bottom:6px;">NIP (Nomor Induk Pegawai)</label>
          <div class="modal-input-group">
            <input type="text" id="modalEditNIP" value="{{ $dashboardUser->nip ?? '' }}" placeholder="NIP">
            <i class="fas fa-id-badge"></i>
          </div>
        </div>

        <div>
          <label style="font-size:12px; font-weight:700; color:#64748b; display:block; margin-bottom:6px;">Pangkat / Golongan</label>
          <div class="modal-input-group">
            <input type="text" id="modalEditPangkat" value="{{ $dashboardUser->pangkat ?? '' }}" placeholder="Pangkat/Golongan">
            <i class="fas fa-award"></i>
          </div>
        </div>
        
        <div style="background:#fdfaf2; border:1px solid #faeed1; border-radius:12px; padding:12px 14px; display:flex; gap:10px; margin-top:10px;">
          <i class="fas fa-info-circle" style="color:#b78103; font-size:16px; margin-top:2px;"></i>
          <span style="font-size:12px; color:#715102; line-height:1.5;">
            Pastikan NIP dan Pangkat sudah sesuai dengan dokumen resmi untuk keperluan penandatanganan perjanjian kinerja digital.
          </span>
        </div>
      </div>
    </div>

    <div style="padding:16px 24px; border-top:1px solid #f1f5f9; display:flex; justify-content:flex-end; gap:12px; background:#fafdfc; border-bottom-left-radius:24px; border-bottom-right-radius:24px;">
      <button type="button" id="cancelProfileModalBtn" class="modal-btn modal-btn-secondary">Batal</button>
      <button type="button" id="saveProfileBtn" class="modal-btn modal-btn-primary">
        <i class="fas fa-save"></i> Simpan Perubahan
      </button>
    </div>
  </div>
</div>

<script>
  const editProfileBtn = document.getElementById('editProfileBtn');
  const saveProfileBtn = document.getElementById('saveProfileBtn');
  const profileEditModal = document.getElementById('profileEditModal');
  const closeProfileModalBtn = document.getElementById('closeProfileModalBtn');
  const cancelProfileModalBtn = document.getElementById('cancelProfileModalBtn');

  const displayNama = document.getElementById('displayNama');
  const displayEmail = document.getElementById('displayEmail');
  const displayNIP = document.getElementById('displayNIP');
  const displayPangkat = document.getElementById('displayPangkat');
  const currentSignatureImg = document.getElementById('currentSignatureImg');
  const currentSignaturePlaceholder = document.getElementById('currentSignaturePlaceholder');

  const modalEditNama = document.getElementById('modalEditNama');
  const modalEditEmail = document.getElementById('modalEditEmail');
  const modalEditNIP = document.getElementById('modalEditNIP');
  const modalEditPangkat = document.getElementById('modalEditPangkat');

  const modalPhotoUploadInput = document.getElementById('modalPhotoUploadInput');
  const modalPhotoPreviewImg = document.getElementById('modalPhotoPreviewImg');
  const modalPhotoPreviewPlaceholder = document.getElementById('modalPhotoPreviewPlaceholder');
  const modalDeletePhotoBtn = document.getElementById('modalDeletePhotoBtn');

  const modalSignatureUploadInput = document.getElementById('modalSignatureUploadInput');
  const modalSignaturePreviewImg = document.getElementById('modalSignaturePreviewImg');
  const modalSignaturePreviewPlaceholder = document.getElementById('modalSignaturePreviewPlaceholder');
  const modalDeleteSignatureBtn = document.getElementById('modalDeleteSignatureBtn');

  const modalClearSignatureBtn = document.getElementById('modalClearSignatureBtn');
  const modalUseDrawnSignatureBtn = document.getElementById('modalUseDrawnSignatureBtn');
  const modalSignatureCanvas = document.getElementById('modalSignatureCanvas');
  const profilePanelCsrfTokenInput = document.getElementById('profilePanelCsrfToken');

  let signatureDataValue = null;
  let croppedPhotoDataValue = null;
  let isDrawing = false;
  let hasSignatureStroke = false;
  
  let currentPhotoUrl = @json($profilePhotoUrl);
  let currentSignatureUrl = @json($signatureUrl);

  const signatureCtx = modalSignatureCanvas ? modalSignatureCanvas.getContext('2d') : null;

  function openProfileEditModal() {
    if (profileEditModal) profileEditModal.style.display = 'flex';
    
    signatureDataValue = null;
    croppedPhotoDataValue = null;

    modalEditNama.value = displayNama ? displayNama.textContent.trim() : '';
    modalEditEmail.value = displayEmail ? displayEmail.textContent.trim() : '';
    modalEditNIP.value = displayNIP ? displayNIP.textContent.trim() : '';
    modalEditPangkat.value = displayPangkat ? displayPangkat.textContent.trim() : '';

    if (currentPhotoUrl) {
      if (modalPhotoPreviewImg) {
        modalPhotoPreviewImg.src = currentPhotoUrl + '?v=' + Date.now();
        modalPhotoPreviewImg.style.display = 'block';
      }
      if (modalPhotoPreviewPlaceholder) modalPhotoPreviewPlaceholder.style.display = 'none';
      if (modalDeletePhotoBtn) modalDeletePhotoBtn.style.display = 'inline-flex';
    } else {
      if (modalPhotoPreviewImg) {
        modalPhotoPreviewImg.style.display = 'none';
        modalPhotoPreviewImg.removeAttribute('src');
      }
      if (modalPhotoPreviewPlaceholder) {
        modalPhotoPreviewPlaceholder.textContent = (modalEditNama.value || 'U').substring(0, 1).toUpperCase();
        modalPhotoPreviewPlaceholder.style.color = '#009970';
        modalPhotoPreviewPlaceholder.style.display = 'block';
      }
      if (modalDeletePhotoBtn) modalDeletePhotoBtn.style.display = 'none';
    }

    if (currentSignatureUrl) {
      if (modalSignaturePreviewImg) {
        modalSignaturePreviewImg.src = currentSignatureUrl + '?v=' + Date.now();
        modalSignaturePreviewImg.style.display = 'block';
      }
      if (modalSignaturePreviewPlaceholder) modalSignaturePreviewPlaceholder.style.display = 'none';
      if (modalDeleteSignatureBtn) modalDeleteSignatureBtn.style.display = 'inline-flex';
    } else {
      if (modalSignaturePreviewImg) {
        modalSignaturePreviewImg.style.display = 'none';
        modalSignaturePreviewImg.removeAttribute('src');
      }
      if (modalSignaturePreviewPlaceholder) {
        modalSignaturePreviewPlaceholder.textContent = 'Belum ada tanda tangan';
        modalSignaturePreviewPlaceholder.style.color = '#94a3b8';
        modalSignaturePreviewPlaceholder.style.display = 'block';
      }
      if (modalDeleteSignatureBtn) modalDeleteSignatureBtn.style.display = 'none';
    }

    clearSignatureCanvas();
    if (modalSignatureUploadInput) modalSignatureUploadInput.value = '';
    if (modalPhotoUploadInput) modalPhotoUploadInput.value = '';
  }

  function closeProfileEditModal() {
    if (profileEditModal) profileEditModal.style.display = 'none';
  }

  function resolveCsrfToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) return metaToken;
    return profilePanelCsrfTokenInput?.value || '';
  }

  function setupSignatureCanvas() {
    if (!modalSignatureCanvas || !signatureCtx) return;
    signatureCtx.fillStyle = '#ffffff';
    signatureCtx.fillRect(0, 0, modalSignatureCanvas.width, modalSignatureCanvas.height);
    signatureCtx.lineWidth = 2.5;
    signatureCtx.lineJoin = 'round';
    signatureCtx.lineCap = 'round';
    signatureCtx.strokeStyle = '#1e293b';
  }

  function getCanvasPos(event) {
    const rect = modalSignatureCanvas.getBoundingClientRect();
    const touch = event.touches && event.touches[0] ? event.touches[0] : null;
    const clientX = touch ? touch.clientX : event.clientX;
    const clientY = touch ? touch.clientY : event.clientY;
    return {
      x: ((clientX - rect.left) * modalSignatureCanvas.width) / rect.width,
      y: ((clientY - rect.top) * modalSignatureCanvas.height) / rect.height,
    };
  }

  function startDraw(event) {
    if (!modalSignatureCanvas || !signatureCtx) return;
    isDrawing = true;
    const pos = getCanvasPos(event);
    signatureCtx.beginPath();
    signatureCtx.moveTo(pos.x, pos.y);
    event.preventDefault();
  }

  function drawMove(event) {
    if (!isDrawing || !signatureCtx) return;
    const pos = getCanvasPos(event);
    signatureCtx.lineTo(pos.x, pos.y);
    signatureCtx.stroke();
    hasSignatureStroke = true;
    event.preventDefault();
  }

  function endDraw() {
    isDrawing = false;
  }

  function clearSignatureCanvas() {
    if (!modalSignatureCanvas || !signatureCtx) return;
    signatureCtx.clearRect(0, 0, modalSignatureCanvas.width, modalSignatureCanvas.height);
    signatureCtx.fillStyle = '#ffffff';
    signatureCtx.fillRect(0, 0, modalSignatureCanvas.width, modalSignatureCanvas.height);
    hasSignatureStroke = false;
  }

  function setSignaturePreview(dataUrl) {
    if (!modalSignaturePreviewImg || !modalSignaturePreviewPlaceholder) return;
    if (dataUrl) {
      modalSignaturePreviewImg.src = dataUrl;
      modalSignaturePreviewImg.style.display = 'block';
      modalSignaturePreviewPlaceholder.style.display = 'none';
    } else {
      modalSignaturePreviewImg.style.display = 'none';
      modalSignaturePreviewImg.removeAttribute('src');
      modalSignaturePreviewPlaceholder.style.display = 'block';
    }
  }

  setupSignatureCanvas();
  if (modalSignatureCanvas) {
    modalSignatureCanvas.addEventListener('mousedown', startDraw);
    modalSignatureCanvas.addEventListener('mousemove', drawMove);
    modalSignatureCanvas.addEventListener('mouseup', endDraw);
    modalSignatureCanvas.addEventListener('mouseleave', endDraw);
    modalSignatureCanvas.addEventListener('touchstart', startDraw, { passive: false });
    modalSignatureCanvas.addEventListener('touchmove', drawMove, { passive: false });
    modalSignatureCanvas.addEventListener('touchend', endDraw);
  }

  if (modalPhotoUploadInput) {
    modalPhotoUploadInput.addEventListener('change', function(event) {
      const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
      if (!file) return;
      
      if (file.size > 2 * 1024 * 1024) {
        alert('File gambar terlalu besar. Maksimum ukuran adalah 2MB.');
        modalPhotoUploadInput.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        croppedPhotoDataValue = e.target.result;
        if (modalPhotoPreviewImg) {
          modalPhotoPreviewImg.src = croppedPhotoDataValue;
          modalPhotoPreviewImg.style.display = 'block';
        }
        if (modalPhotoPreviewPlaceholder) modalPhotoPreviewPlaceholder.style.display = 'none';
        if (modalDeletePhotoBtn) modalDeletePhotoBtn.style.display = 'inline-flex';
      };
      reader.readAsDataURL(file);
    });
  }

  if (modalSignatureUploadInput) {
    modalSignatureUploadInput.addEventListener('change', function(event) {
      const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
      if (!file) return;
      
      if (file.size > 2 * 1024 * 1024) {
        alert('File gambar terlalu besar. Maksimum ukuran adalah 2MB.');
        modalSignatureUploadInput.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        signatureDataValue = e.target.result;
        setSignaturePreview(signatureDataValue);
        if (modalDeleteSignatureBtn) modalDeleteSignatureBtn.style.display = 'inline-flex';
      };
      reader.readAsDataURL(file);
    });
  }

  if (modalUseDrawnSignatureBtn) {
    modalUseDrawnSignatureBtn.addEventListener('click', function() {
      if (!hasSignatureStroke) {
        alert('Silakan gambar tanda tangan terlebih dahulu pada canvas.');
        return;
      }
      signatureDataValue = modalSignatureCanvas.toDataURL('image/png');
      setSignaturePreview(signatureDataValue);
      if (modalDeleteSignatureBtn) modalDeleteSignatureBtn.style.display = 'inline-flex';
    });
  }

  if (modalClearSignatureBtn) {
    modalClearSignatureBtn.addEventListener('click', function() {
      clearSignatureCanvas();
    });
  }

  if (modalDeletePhotoBtn) {
    modalDeletePhotoBtn.addEventListener('click', function() {
      if (confirm('Apakah Anda yakin ingin menghapus foto profil ini dari akun Anda?')) {
        croppedPhotoDataValue = '__DELETE_PHOTO__';
        if (modalPhotoPreviewImg) modalPhotoPreviewImg.style.display = 'none';
        if (modalPhotoPreviewPlaceholder) {
          modalPhotoPreviewPlaceholder.textContent = 'Hapus';
          modalPhotoPreviewPlaceholder.style.color = '#ef4444';
          modalPhotoPreviewPlaceholder.style.display = 'block';
        }
        modalDeletePhotoBtn.style.display = 'none';
        if (modalPhotoUploadInput) modalPhotoUploadInput.value = '';
      }
    });
  }

  if (modalDeleteSignatureBtn) {
    modalDeleteSignatureBtn.addEventListener('click', function() {
      if (confirm('Apakah Anda yakin ingin menghapus tanda tangan ini dari akun Anda?')) {
        signatureDataValue = '__DELETE_SIGNATURE__';
        if (modalSignaturePreviewImg) modalSignaturePreviewImg.style.display = 'none';
        if (modalSignaturePreviewPlaceholder) {
          modalSignaturePreviewPlaceholder.textContent = 'Tanda tangan akan dihapus';
          modalSignaturePreviewPlaceholder.style.color = '#ef4444';
          modalSignaturePreviewPlaceholder.style.display = 'block';
        }
        modalDeleteSignatureBtn.style.display = 'none';
        if (modalSignatureUploadInput) modalSignatureUploadInput.value = '';
        clearSignatureCanvas();
      }
    });
  }

  if (editProfileBtn) editProfileBtn.addEventListener('click', openProfileEditModal);
  if (closeProfileModalBtn) closeProfileModalBtn.addEventListener('click', closeProfileEditModal);
  if (cancelProfileModalBtn) cancelProfileModalBtn.addEventListener('click', closeProfileEditModal);
  if (profileEditModal) {
    profileEditModal.addEventListener('click', function(event) {
      if (event.target === profileEditModal) closeProfileEditModal();
    });
  }

  if (saveProfileBtn) {
    saveProfileBtn.addEventListener('click', async function() {
      saveProfileBtn.disabled = true;
      saveProfileBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

      const updateData = {
        nama: modalEditNama.value.trim(),
        email: modalEditEmail.value.trim(),
        nip: modalEditNIP.value.trim(),
        pangkat: modalEditPangkat.value.trim()
      };

      if (croppedPhotoDataValue) updateData.croppedPhotoData = croppedPhotoDataValue;
      if (signatureDataValue) updateData.signature_data = signatureDataValue;

      try {
        const response = await fetch('{{ route("profil.update") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': resolveCsrfToken()
          },
          body: JSON.stringify(updateData)
        });

        const raw = await response.text();
        let result = {};
        try {
          result = raw ? JSON.parse(raw) : {};
        } catch (e) {
          result = { success: false, message: raw || 'Respon server tidak valid' };
        }

        if (!response.ok) {
          const validationErrors = result && result.errors ? Object.values(result.errors).flat().join('\n') : null;
          throw new Error(validationErrors || result.message || `HTTP ${response.status}`);
        }

        if (result.success) {
          if (displayNama) displayNama.textContent = updateData.nama;
          if (displayEmail) displayEmail.textContent = updateData.email;
          if (displayNIP) displayNIP.textContent = updateData.nip;
          if (displayPangkat) displayPangkat.textContent = updateData.pangkat;

          const mainCircle = document.getElementById('mainProfilePhotoCircle');
          if (mainCircle) {
            if (result.data && result.data.foto_profil) {
              currentPhotoUrl = result.data.foto_profil;
              mainCircle.innerHTML = `<img id="mainProfilePhotoImg" src="${currentPhotoUrl}?v=${Date.now()}" alt="Foto Profil" style="width:100%; height:100%; object-fit:cover;">`;
            } else if (croppedPhotoDataValue === '__DELETE_PHOTO__') {
              currentPhotoUrl = null;
              const initial = (updateData.nama || 'U').substring(0, 1).toUpperCase();
              mainCircle.innerHTML = `<span id="mainProfilePhotoPlaceholder">${initial}</span>`;
            }
          }

          if (currentSignatureImg && currentSignaturePlaceholder) {
            if (result.data && result.data.tanda_tangan) {
              currentSignatureUrl = result.data.tanda_tangan;
              currentSignatureImg.src = currentSignatureUrl + '?v=' + Date.now();
              currentSignatureImg.style.display = 'block';
              currentSignaturePlaceholder.style.display = 'none';
            } else if (signatureDataValue === '__DELETE_SIGNATURE__') {
              currentSignatureUrl = null;
              currentSignatureImg.style.display = 'none';
              currentSignatureImg.removeAttribute('src');
              currentSignaturePlaceholder.style.display = 'block';
            }
          }

          closeProfileEditModal();
          alert('Profil berhasil diperbarui!');
        } else {
          alert('Gagal memperbarui profil: ' + (result.message || 'Error tidak diketahui'));
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan profil: ' + (error.message || 'Error tidak diketahui'));
      } finally {
        saveProfileBtn.disabled = false;
        saveProfileBtn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan';
      }
    });
  }
</script>
@endif