<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laporan Kinerja - RSUD Bangil</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #E3F8F6, #D6F5EF);
      min-height: 100vh;
      color: #1B2A41;
    }
    header {
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 40px;
      box-shadow: 0 4px 12px rgba(0,153,112,0.15);
    }
    .logo-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .logo-container img { height: 60px; }
    nav { display: flex; gap: 20px; }
    nav a { text-decoration: none; color: #1B2A41; font-weight: 600; font-size: 18px; }
    nav a:hover { color: #00B5A0; }
    main { 
      padding: 40px 20px;
      max-width: 1400px;
      margin: 0 auto;
    }
    .page-header {
      margin-bottom: 30px;
      text-align: center;
    }
    .page-title { 
      font-size: 32px; 
      font-weight: 800; 
      margin-bottom: 8px; 
    }
    .page-subtitle { 
      color: #5F6F81; 
      font-size: 14px;
    }
    
    /* Alert messages */
    .alert {
      padding: 16px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .alert-info {
      background: #E0F9F7;
      color: #00796B;
      border: 1px solid #80DEEA;
    }
    
    /* Header Info Card */
    .info-card {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      margin-bottom: 30px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }
    .info-item {
      padding: 12px;
      background: #F5F5F5;
      border-radius: 8px;
    }
    .info-label {
      font-size: 12px;
      color: #666;
      font-weight: 600;
      margin-bottom: 4px;
      text-transform: uppercase;
    }
    .info-value {
      font-size: 15px;
      color: #1B2A41;
      font-weight: 600;
    }
    
    /* Triwulan Tabs */
    .triwulan-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
      flex-wrap: wrap;
    }
    .triwulan-tab-btn {
      padding: 10px 20px;
      border: 2px solid #ddd;
      border-radius: 8px;
      background: #fff;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 600;
      font-size: 14px;
      color: #1B2A41;
    }
    .triwulan-tab-btn:hover {
      border-color: #00B5A0;
      color: #00B5A0;
      background: #F9F9F9;
    }

    .triwulan-tab-btn.disabled,
    .triwulan-tab-btn:disabled {
      cursor: not-allowed;
      opacity: 0.5;
      border-color: #ccc;
      color: #999;
      background: #f7f7f7;
    }
    
    /* Laporan Table */
    .laporan-section {
      background: #fff;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      overflow-x: auto;
    }
    .section-title {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 20px;
      color: #1B2A41;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    
    thead th {
      background: #00B5A0;
      color: #fff;
      padding: 12px 8px;
      text-align: left;
      font-weight: 600;
      border: 1px solid #ddd;
    }
    
    tbody td {
      padding: 12px 8px;
      border: 1px solid #ddd;
      vertical-align: top;
    }
    
    tbody tr:hover {
      background: #F9F9F9;
    }
    
    .no-col { width: 40px; }
    .program-col { min-width: 250px; }
    .target-col { width: 80px; }
    .realisasi-col { width: 120px; }
    .action-col { width: 80px; text-align: center; }
    
    .realisasi-cell {
      position: relative;
    }
    
    .realisasi-text {
      display: block;
      word-wrap: break-word;
      word-break: break-word;
      white-space: pre-wrap;
      max-height: 100px;
      overflow-y: auto;
      font-family: 'Courier New', monospace;
      font-size: 12px;
    }
    
    .edit-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 28px;
      height: 28px;
      background: #00B5A0;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.3s;
    }
    
    .edit-btn:hover {
      background: #008F7E;
    }
    
    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: #999;
    }
    
    .empty-state i {
      font-size: 56px;
      color: #ddd;
      margin-bottom: 12px;
    }
    
    /* Modal Styles */
    .modal-header {
      background: #00B5A0;
      color: #fff;
      border: none;
    }
    
    .modal-header .btn-close {
      filter: brightness(0) invert(1);
    }
    
    .form-label {
      font-weight: 600;
      color: #1B2A41;
      margin-bottom: 8px;
    }
    
    .form-control {
      border: 1px solid #ddd;
      border-radius: 6px;
      padding: 10px 12px;
    }
    
    .form-control:focus {
      border-color: #00B5A0;
      box-shadow: 0 0 0 0.2rem rgba(0, 181, 160, 0.25);
    }
    
    .btn-save {
      background: #00B5A0;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .btn-save:hover {
      background: #008F7E;
      color: #fff;
    }
    
    .btn-secondary-alt {
      background: #E0E0E0;
      color: #1B2A41;
      border: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .btn-secondary-alt:hover {
      background: #D0D0D0;
    }
    
    /* Footer */
    footer { 
      background: #fff; 
      text-align: center; 
      font-size: 13px; 
      font-weight: 700; 
      padding: 15px 0; 
      border-top: 1px solid #ddd;
      margin-top: 40px;
    }
    
    /* Info Badge */
    .badge-triwulan {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      margin-left: 8px;
    }
    
    .badge-active {
      background: #C8E6C9;
      color: #2E7D32;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-container">
      <img src="{{ asset('images/logo_pemda.png') }}" alt="Logo Pemda">
      <img src="{{ asset('images/logo_rsud.png') }}" alt="Logo RSUD">
    </div>
    <nav>
      <a href="{{ route('home') }}">Beranda</a>
      <a href="{{ route('panduan') }}">Panduan</a>
      <a href="{{ route('kontak') }}">Kontak</a>
      <a href="{{ route('tentang') }}">Tentang</a>
    </nav>
  </header>

  <main>
    <div class="page-header">
      <div class="page-title">Laporan Kinerja</div>
      <div class="page-subtitle">Form pengisian realisasi laporan kinerja per triwulan</div>
    </div>

    @if (!$perjanjian || $message)
      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        <span>{{ $message ?? 'Tidak ada perjanjian kinerja yang disetujui' }}</span>
      </div>
      <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('perjanjian.index') }}" class="btn btn-primary">
          <i class="fas fa-file-signature"></i>
          Buat Perjanjian Kinerja
        </a>
      </div>
    @else
      <!-- Info Card -->
      <div class="info-card">
        <h5 style="margin-bottom: 16px; color: #00B5A0;">
          <i class="fas fa-user-circle"></i>
          Data Pegawai & Perjanjian
        </h5>
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Nama Pegawai</div>
            <div class="info-value">{{ $perjanjian->pihak1_name ?? '-' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">NIP</div>
            <div class="info-value">{{ $perjanjian->pihak1_nip ?? '-' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Jabatan</div>
            <div class="info-value">{{ $perjanjian->pihak1_jabatan ?? '-' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Tahun</div>
            <div class="info-value">{{ $perjanjian->tahun ?? '-' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Atasan Langsung</div>
            <div class="info-value">{{ $perjanjian->pihak2_name ?? '-' }}</div>
          </div>
        </div>
      </div>

      <!-- Triwulan Selection -->
      <div class="triwulan-tabs">
        <button type="button" class="triwulan-tab-btn {{ $triwulanAktif !== 1 ? 'disabled' : '' }}" data-triwulan="1" {{ $triwulanAktif !== 1 ? 'disabled' : '' }} onclick="openRealisasiModal(1)">
          <i class="fas fa-calendar"></i> Triwulan 1 (Jan-Mar)
          @if ($triwulanAktif === 1)
            <span class="badge-triwulan badge-active">Aktif</span>
          @endif
        </button>
        <button type="button" class="triwulan-tab-btn {{ $triwulanAktif !== 2 ? 'disabled' : '' }}" data-triwulan="2" {{ $triwulanAktif !== 2 ? 'disabled' : '' }} onclick="openRealisasiModal(2)">
          <i class="fas fa-calendar"></i> Triwulan 2 (Apr-Jun)
          @if ($triwulanAktif === 2)
            <span class="badge-triwulan badge-active">Aktif</span>
          @endif
        </button>
        <button type="button" class="triwulan-tab-btn {{ $triwulanAktif !== 3 ? 'disabled' : '' }}" data-triwulan="3" {{ $triwulanAktif !== 3 ? 'disabled' : '' }} onclick="openRealisasiModal(3)">
          <i class="fas fa-calendar"></i> Triwulan 3 (Jul-Sep)
          @if ($triwulanAktif === 3)
            <span class="badge-triwulan badge-active">Aktif</span>
          @endif
        </button>
        <button type="button" class="triwulan-tab-btn {{ $triwulanAktif !== 4 ? 'disabled' : '' }}" data-triwulan="4" {{ $triwulanAktif !== 4 ? 'disabled' : '' }} onclick="openRealisasiModal(4)">
          <i class="fas fa-calendar"></i> Triwulan 4 (Okt-Des)
          @if ($triwulanAktif === 4)
            <span class="badge-triwulan badge-active">Aktif</span>
          @endif
        </button>
      </div>

      <!-- Laporan Table - Removed, using modal instead -->

        <div style="text-align: center; padding: 40px 20px; color: #999;">
          <i class="fas fa-info-circle" style="font-size: 32px; color: #ddd; margin-bottom: 12px;"></i>
          <p>Pilih triwulan di atas untuk mengisi realisasi laporan kinerja</p>
        </div>
    @endif
  </main>

  <!-- Modal Edit Realisasi -->
  <div class="modal fade" id="realisasiModal" tabindex="-1" aria-labelledby="realisasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="realisasiModalLabel">
            <i class="fas fa-edit"></i>
            Form Pengisian Realisasi Laporan Kinerja
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Info Perjanjian -->
          <div style="background: #F5F5F5; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
            <h6 style="margin: 0 0 12px 0; font-weight: 600; color: #1B2A41;">Informasi Perjanjian Kinerja</h6>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
              <div>
                <div style="color: #666; margin-bottom: 2px;">Nama Pegawai</div>
                <div style="font-weight: 600; color: #1B2A41;" id="modalPegawai">-</div>
              </div>
              <div>
                <div style="color: #666; margin-bottom: 2px;">Jabatan</div>
                <div style="font-weight: 600; color: #1B2A41;" id="modalJabatan">-</div>
              </div>
              <div>
                <div style="color: #666; margin-bottom: 2px;">Atasan Langsung</div>
                <div style="font-weight: 600; color: #1B2A41;" id="modalAtasan">-</div>
              </div>
              <div>
                <div style="color: #666; margin-bottom: 2px;">Triwulan</div>
                <div style="font-weight: 600; color: #00B5A0;" id="modalTriwulan">-</div>
              </div>
            </div>
          </div>

          @php
            $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
            $activeTriwulan = $triwulanAktif ?? 1;
            $activeTwKey = 'tw' . $activeTriwulan;
          @endphp
          <div style="margin-bottom: 20px;">
            <h6 style="font-weight: 700; color: #1B2A41; margin-bottom: 12px;">Rencana Aksi dari Perjanjian Kinerja</h6>
            @if(!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0)
              <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                  <thead>
                    <tr>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">No</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">Sasaran</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">Indikator Kinerja</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">Target</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">TW I</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">TW II</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">TW III</th>
                      <th style="padding: 10px; border: 1px solid #ddd; background: #f5f5f5;">TW IV</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($tabelB['sasaran'] as $index => $sasaran)
                      <tr>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $index + 1 }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $sasaran ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $tabelB['indikator'][$index] ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $tabelB['target'][$index] ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $tabelB['tw1'][$index] ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $tabelB['tw2'][$index] ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $tabelB['tw3'][$index] ?? '-' }}</td>
                        <td style="padding: 10px; border: 1px solid #ddd; vertical-align: top;">{{ $tabelB['tw4'][$index] ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div style="padding: 16px; border: 1px solid #ddd; border-radius: 8px; background: #fff; color: #666;">
                Tidak ada data rencana aksi yang tersimpan pada perjanjian kinerja ini.
              </div>
            @endif
          </div>

          <form id="realisasiForm">
            @csrf
            <input type="hidden" id="perjanjianId" name="perjanjian_id" value="{{ $perjanjian ? $perjanjian->id : '' }}">
            <input type="hidden" id="triwulanEdit" name="triwulan" value="1">

            <div class="mb-3">
              <label class="form-label">
                <i class="fas fa-file-alt"></i>
                Uraian Realisasi
                <span style="color: #e74c3c;">*</span>
              </label>
              <textarea class="form-control" id="realisasiInput" name="realisasi" rows="8" 
                        placeholder="Jelaskan secara detail realisasi/capaian laporan kinerja Anda pada triwulan ini. Tuliskan pencapaian, hambatan, dan rencana ke depan..." required></textarea>
              <small class="text-muted">
                <i class="fas fa-lightbulb"></i>
                Minimal 50 karakter, jelaskan dengan detail dan spesifik
              </small>
            </div>

            <div class="d-flex gap-2 justify-content-end">
              <button type="button" class="btn-secondary-alt" data-bs-dismiss="modal">
                <i class="fas fa-times"></i>
                Batal
              </button>
              <button type="submit" class="btn-save">
                <i class="fas fa-save"></i>
                Simpan Realisasi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <footer>© 2026 RSUD Bangil – Sistem Laporan Kinerja</footer>

  <!-- Hidden data container untuk semua realisasi -->
  <div id="realisasiData" style="display: none;">
    @if (!empty($laporans))
      @foreach ($laporans as $laporan)
        <div data-laporan-id="{{ $laporan->id }}">
          <div data-tb="1" data-content="{{ $laporan->realisasi_tb1 ?? '' }}"></div>
          <div data-tb="2" data-content="{{ $laporan->realisasi_tb2 ?? '' }}"></div>
          <div data-tb="3" data-content="{{ $laporan->realisasi_tb3 ?? '' }}"></div>
          <div data-tb="4" data-content="{{ $laporan->realisasi_tb4 ?? '' }}"></div>
        </div>
      @endforeach
    @endif
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const triwulanAktif = {{ $triwulanAktif ?? 1 }};
    const perjanjianData = {
      id: {{ $perjanjian ? $perjanjian->id : 'null' }},
      nama: "{{ $perjanjian ? $perjanjian->pihak1_name : '' }}",
      jabatan: "{{ $perjanjian ? $perjanjian->pihak1_jabatan : '' }}",
      atasan: "{{ $perjanjian ? $perjanjian->pihak2_name : '' }}",
    };
    
    // Simpan semua data realisasi dalam object
    const laporanRealisasi = {};
    document.querySelectorAll('#realisasiData > div').forEach(container => {
      const laporanId = container.dataset.laporanId;
      laporanRealisasi[laporanId] = {};
      container.querySelectorAll('[data-tb]').forEach(div => {
        const tb = div.dataset.tb;
        const content = div.dataset.content || '';
        laporanRealisasi[laporanId][tb] = content;
      });
    });
    
    // Fungsi untuk membuka modal realisasi
    function openRealisasiModal(triwulan) {
      const modal = document.getElementById('realisasiModal');
      
      document.getElementById('perjanjianId').value = perjanjianData.id;
      document.getElementById('triwulanEdit').value = triwulan;
      document.getElementById('modalTriwulan').textContent = 'Triwulan ' + triwulan;
      document.getElementById('modalPegawai').textContent = perjanjianData.nama;
      document.getElementById('modalJabatan').textContent = perjanjianData.jabatan;
      document.getElementById('modalAtasan').textContent = perjanjianData.atasan;
      
      // Load existing realisasi jika ada
      const triwulanKey = 'realisasi_tb' + triwulan;
      const existingContent = localStorage.getItem('realisasi_' + perjanjianData.id + '_' + triwulan) || '';
      document.getElementById('realisasiInput').value = existingContent;
      
      // Show modal
      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    }
    
    // Form submission
    document.getElementById('realisasiForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const perjanjianId = document.getElementById('perjanjianId').value;
      const triwulan = document.getElementById('triwulanEdit').value;
      const realisasi = document.getElementById('realisasiInput').value;
      
      if (realisasi.trim().length < 50) {
        alert('Realisasi minimal harus 50 karakter');
        return;
      }
      
      fetch(`/api/realisasi/perjanjian`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('#realisasiForm input[name="_token"]').value
        },
        body: JSON.stringify({
          perjanjian_id: perjanjianId,
          triwulan: triwulan,
          realisasi: realisasi
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Save to localStorage for display
          localStorage.setItem('realisasi_' + perjanjianId + '_' + triwulan, realisasi);
          
          // Close modal
          bootstrap.Modal.getInstance(document.getElementById('realisasiModal')).hide();
          
          // Show success message
          showAlert('Realisasi berhasil disimpan!', 'success');
          
          // Clear form
          document.getElementById('realisasiInput').value = '';
        } else {
          showAlert('Terjadi kesalahan: ' + data.message, 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menyimpan', 'error');
      });
    });
    
    // Helper function untuk show alert
    function showAlert(message, type) {
      const alertDiv = document.createElement('div');
      alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 20px;
        border-radius: 8px;
        font-weight: 600;
        z-index: 9999;
        animation: slideIn 0.3s ease-in-out;
      `;
      
      if (type === 'success') {
        alertDiv.style.background = '#C8E6C9';
        alertDiv.style.color = '#2E7D32';
        alertDiv.style.border = '1px solid #81C784';
      } else {
        alertDiv.style.background = '#FFCDD2';
        alertDiv.style.color = '#C62828';
        alertDiv.style.border = '1px solid #EF5350';
      }
      
      alertDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
      document.body.appendChild(alertDiv);
      
      setTimeout(() => {
        alertDiv.remove();
      }, 3000);
    }
    
    // Add animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes slideIn {
        from {
          transform: translateX(400px);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>


