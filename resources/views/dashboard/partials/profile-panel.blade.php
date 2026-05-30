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
      <div style="width:112px; height:112px; border-radius:28px; overflow:hidden; background:#e8f8f3; display:grid; place-items:center; color:#009970; font-size:36px; font-weight:800;">
        @if($profilePhotoUrl)
          <img src="{{ $profilePhotoUrl }}?v={{ time() }}" alt="Foto Profil" style="width:100%; height:100%; object-fit:cover;">
        @else
          {{ strtoupper(substr($dashboardUser->nama ?? 'U', 0, 1)) }}
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
<div id="profileEditModal" style="position:fixed; inset:0; background:rgba(15,23,42,0.45); display:none; align-items:center; justify-content:center; z-index:9999; padding:16px;">
  <div style="width:min(760px, 100%); max-height:90vh; overflow:auto; background:#fff; border-radius:16px; box-shadow:0 30px 80px rgba(0,0,0,0.24);">
    <div style="padding:16px 18px; border-bottom:1px solid #e5e7eb; display:flex; justify-content:space-between; align-items:center;">
      <div style="font-size:18px; font-weight:800; color:#1B2A41;">Edit Profil</div>
      <button type="button" id="closeProfileModalBtn" style="border:none; background:transparent; font-size:24px; line-height:1; color:#64748b; cursor:pointer;">&times;</button>
    </div>
    <div style="padding:16px 18px; display:grid; gap:12px;">
      <div>
        <label style="font-size:12px; font-weight:700; color:#8aa29a; text-transform:uppercase; margin-bottom:6px; display:block;">Nama</label>
        <input type="text" id="modalEditNama" value="{{ $dashboardUser->nama ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
      </div>
      <div>
        <label style="font-size:12px; font-weight:700; color:#8aa29a; text-transform:uppercase; margin-bottom:6px; display:block;">Email</label>
        <input type="email" id="modalEditEmail" value="{{ $dashboardUser->email ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
      </div>
      <div>
        <label style="font-size:12px; font-weight:700; color:#8aa29a; text-transform:uppercase; margin-bottom:6px; display:block;">NIP</label>
        <input type="text" id="modalEditNIP" value="{{ $dashboardUser->nip ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
      </div>
      <div>
        <label style="font-size:12px; font-weight:700; color:#8aa29a; text-transform:uppercase; margin-bottom:6px; display:block;">Pangkat</label>
        <input type="text" id="modalEditPangkat" value="{{ $dashboardUser->pangkat ?? '' }}" style="width:100%; padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
      </div>

      <div style="padding:14px; border:1px solid #e4efeb; border-radius:12px; background:#f9fffc; display:grid; gap:10px;">
        <div style="font-size:12px; font-weight:700; color:#8aa29a; text-transform:uppercase;">Edit Tanda Tangan</div>
        <div style="display:grid; gap:8px;">
          <label style="font-size:12px; color:#52606d; font-weight:600;">Upload Gambar Tanda Tangan</label>
          <input type="file" id="modalSignatureUploadInput" accept="image/*" style="font-size:12px;">
        </div>
        <div style="font-size:12px; color:#64748b; font-weight:600;">Atau gambar langsung:</div>
        <div style="border:1px dashed #b0c8c0; border-radius:10px; background:#fff; padding:8px;">
          <canvas id="modalSignatureCanvas" width="520" height="150" style="width:100%; height:150px; touch-action:none; cursor:crosshair; border-radius:8px; background:#fff;"></canvas>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          <button type="button" id="modalClearSignatureBtn" style="padding:8px 12px; border:1px solid #d5dee8; border-radius:8px; background:#fff; color:#334155; font-weight:600; cursor:pointer;">Hapus Gambar</button>
          <button type="button" id="modalUseDrawnSignatureBtn" style="padding:8px 12px; border:none; border-radius:8px; background:#00B5A0; color:#fff; font-weight:600; cursor:pointer;">Gunakan Hasil Gambar</button>
        </div>
        <div style="display:grid; gap:6px;">
          <div style="font-size:12px; color:#64748b; font-weight:600;">Preview Tanda Tangan Baru</div>
          <div style="min-height:90px; border:1px solid #e4efeb; border-radius:10px; background:#fff; display:grid; place-items:center; padding:8px;">
            <img id="modalSignaturePreviewImg" alt="Preview Tanda Tangan" style="max-width:100%; max-height:74px; object-fit:contain; display:none;">
            <span id="modalSignaturePreviewPlaceholder" style="font-size:12px; color:#94a3b8;">Belum ada perubahan tanda tangan</span>
          </div>
        </div>
      </div>
    </div>
    <div style="padding:14px 18px; border-top:1px solid #e5e7eb; display:flex; justify-content:flex-end; gap:10px;">
      <button type="button" id="cancelProfileModalBtn" style="padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#334155; font-weight:700; cursor:pointer;">Batal</button>
      <button type="button" id="saveProfileBtn" style="padding:10px 14px; border:none; border-radius:8px; background:#4caf50; color:#fff; font-weight:700; cursor:pointer;">Simpan Perubahan</button>
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

  const modalSignatureUploadInput = document.getElementById('modalSignatureUploadInput');
  const modalClearSignatureBtn = document.getElementById('modalClearSignatureBtn');
  const modalUseDrawnSignatureBtn = document.getElementById('modalUseDrawnSignatureBtn');
  const modalSignatureCanvas = document.getElementById('modalSignatureCanvas');
  const modalSignaturePreviewImg = document.getElementById('modalSignaturePreviewImg');
  const modalSignaturePreviewPlaceholder = document.getElementById('modalSignaturePreviewPlaceholder');
  const profilePanelCsrfTokenInput = document.getElementById('profilePanelCsrfToken');

  let signatureDataValue = null;
  let isDrawing = false;
  let hasSignatureStroke = false;
  const signatureCtx = modalSignatureCanvas ? modalSignatureCanvas.getContext('2d') : null;

  function openProfileEditModal() {
    if (profileEditModal) profileEditModal.style.display = 'flex';
  }

  function closeProfileEditModal() {
    if (profileEditModal) profileEditModal.style.display = 'none';
  }

  function resolveCsrfToken() {
    const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (metaToken) return metaToken;
    const hiddenToken = profilePanelCsrfTokenInput?.value;
    return hiddenToken || '';
  }

  function setupSignatureCanvas() {
    if (!modalSignatureCanvas || !signatureCtx) return;
    signatureCtx.fillStyle = '#ffffff';
    signatureCtx.fillRect(0, 0, modalSignatureCanvas.width, modalSignatureCanvas.height);
    signatureCtx.lineWidth = 2;
    signatureCtx.lineJoin = 'round';
    signatureCtx.lineCap = 'round';
    signatureCtx.strokeStyle = '#111827';
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

  if (modalSignatureUploadInput) {
    modalSignatureUploadInput.addEventListener('change', function(event) {
      const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function(e) {
        signatureDataValue = e.target && e.target.result ? e.target.result : null;
        setSignaturePreview(signatureDataValue);
      };
      reader.readAsDataURL(file);
    });
  }

  if (modalUseDrawnSignatureBtn) {
    modalUseDrawnSignatureBtn.addEventListener('click', function() {
      if (!hasSignatureStroke) {
        alert('Silakan gambar tanda tangan terlebih dahulu.');
        return;
      }
      signatureDataValue = modalSignatureCanvas.toDataURL('image/png');
      setSignaturePreview(signatureDataValue);
    });
  }

  if (modalClearSignatureBtn) {
    modalClearSignatureBtn.addEventListener('click', function() {
      signatureDataValue = null;
      clearSignatureCanvas();
      setSignaturePreview(null);
      if (modalSignatureUploadInput) modalSignatureUploadInput.value = '';
    });
  }

  if (editProfileBtn) {
    editProfileBtn.addEventListener('click', openProfileEditModal);
  }

  if (closeProfileModalBtn) {
    closeProfileModalBtn.addEventListener('click', closeProfileEditModal);
  }

  if (cancelProfileModalBtn) {
    cancelProfileModalBtn.addEventListener('click', closeProfileEditModal);
  }

  if (profileEditModal) {
    profileEditModal.addEventListener('click', function(event) {
      if (event.target === profileEditModal) {
        closeProfileEditModal();
      }
    });
  }

  if (saveProfileBtn) {
    saveProfileBtn.addEventListener('click', async function() {
      const updateData = {
        nama: modalEditNama.value,
        email: modalEditEmail.value,
        nip: modalEditNIP.value,
        pangkat: modalEditPangkat.value
      };

      if (signatureDataValue) {
        updateData.signature_data = signatureDataValue;
      }

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
          result = {
            success: false,
            message: raw || 'Response bukan JSON'
          };
        }

        if (!response.ok) {
          const validationErrors = result && result.errors
            ? Object.values(result.errors).flat().join('\n')
            : null;
          throw new Error(validationErrors || result.message || `HTTP ${response.status}`);
        }

        if (result.success) {
          displayNama.textContent = updateData.nama;
          displayEmail.textContent = updateData.email;
          displayNIP.textContent = updateData.nip;
          displayPangkat.textContent = updateData.pangkat;

          if (result.data && result.data.tanda_tangan && currentSignatureImg && currentSignaturePlaceholder) {
            currentSignatureImg.src = result.data.tanda_tangan + '?v=' + Date.now();
            currentSignatureImg.style.display = 'block';
            currentSignaturePlaceholder.style.display = 'none';
          } else if (signatureDataValue && currentSignatureImg && currentSignaturePlaceholder) {
            currentSignatureImg.src = signatureDataValue;
            currentSignatureImg.style.display = 'block';
            currentSignaturePlaceholder.style.display = 'none';
          }

          signatureDataValue = null;
          clearSignatureCanvas();
          setSignaturePreview(null);
          if (modalSignatureUploadInput) modalSignatureUploadInput.value = '';
          closeProfileEditModal();

          alert('Profil berhasil diperbarui!');
        } else {
          alert('Gagal memperbarui profil: ' + (result.message || 'Unknown error'));
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan profil: ' + (error.message || 'Unknown error'));
      }
    });
  }
</script>
@endif