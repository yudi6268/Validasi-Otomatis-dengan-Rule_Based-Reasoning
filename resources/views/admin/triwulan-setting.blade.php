@extends('layouts.app')

@section('content')
<style>
  .triwulan-container {
    max-width: 800px;
    margin: 40px auto;
  }

  .triwulan-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .triwulan-title {
    font-size: 24px;
    font-weight: 700;
    color: #1B2A41;
    margin-bottom: 10px;
  }

  .triwulan-subtitle {
    color: #666;
    margin-bottom: 30px;
    font-size: 14px;
  }

  .radio-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .radio-item {
    display: flex;
    align-items: center;
    padding: 16px;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
  }

  .radio-item:hover {
    border-color: #00B5A0;
    background: #F9F9F9;
  }

  .radio-item input[type="radio"] {
    cursor: pointer;
    width: 20px;
    height: 20px;
    margin-right: 16px;
  }

  .radio-item.active {
    border-color: #00B5A0;
    background: #E0F9F7;
  }

  .radio-label {
    flex: 1;
    cursor: pointer;
  }

  .radio-label-title {
    font-weight: 600;
    color: #1B2A41;
    margin-bottom: 4px;
  }

  .radio-label-desc {
    font-size: 12px;
    color: #999;
  }

  .button-group {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    justify-content: flex-end;
  }

  .btn {
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
  }

  .btn-primary {
    background: #00B5A0;
    color: #fff;
  }

  .btn-primary:hover {
    background: #008F7E;
  }

  .btn-secondary {
    background: #ddd;
    color: #1B2A41;
  }

  .btn-secondary:hover {
    background: #ccc;
  }

  .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: none;
  }

  .alert.show {
    display: block;
  }

  .alert-success {
    background: #C8E6C9;
    color: #2E7D32;
    border: 1px solid #81C784;
  }

  .alert-error {
    background: #FFCDD2;
    color: #C62828;
    border: 1px solid #EF5350;
  }
</style>

<div class="triwulan-container">
  <div class="triwulan-card">
    <div class="triwulan-title">
      <i class="fas fa-calendar"></i>
      Pengaturan Triwulan Aktif
    </div>
    <div class="triwulan-subtitle">
      Pilih triwulan mana yang akan aktif untuk pengisian realisasi laporan kinerja
    </div>

    <div id="alertMessage" class="alert"></div>

    <form id="triwulanForm">
      @csrf
      
      <div class="radio-group">
        <label class="radio-item {{ $triwulan === 1 ? 'active' : '' }}">
          <input type="radio" name="triwulan" value="1" {{ $triwulan === 1 ? 'checked' : '' }}>
          <div class="radio-label">
            <div class="radio-label-title">Triwulan 1</div>
            <div class="radio-label-desc">Januari - Maret</div>
          </div>
        </label>

        <label class="radio-item {{ $triwulan === 2 ? 'active' : '' }}">
          <input type="radio" name="triwulan" value="2" {{ $triwulan === 2 ? 'checked' : '' }}>
          <div class="radio-label">
            <div class="radio-label-title">Triwulan 2</div>
            <div class="radio-label-desc">April - Juni</div>
          </div>
        </label>

        <label class="radio-item {{ $triwulan === 3 ? 'active' : '' }}">
          <input type="radio" name="triwulan" value="3" {{ $triwulan === 3 ? 'checked' : '' }}>
          <div class="radio-label">
            <div class="radio-label-title">Triwulan 3</div>
            <div class="radio-label-desc">Juli - September</div>
          </div>
        </label>

        <label class="radio-item {{ $triwulan === 4 ? 'active' : '' }}">
          <input type="radio" name="triwulan" value="4" {{ $triwulan === 4 ? 'checked' : '' }}>
          <div class="radio-label">
            <div class="radio-label-title">Triwulan 4</div>
            <div class="radio-label-desc">Oktober - Desember</div>
          </div>
        </label>
      </div>

      <div class="button-group">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i>
          Kembali
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i>
          Simpan Pengaturan
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Update active class saat radio button di-select
  const radioItems = document.querySelectorAll('.radio-item');
  const radioInputs = document.querySelectorAll('input[type="radio"]');

  radioInputs.forEach(radio => {
    radio.addEventListener('change', function() {
      radioItems.forEach(item => item.classList.remove('active'));
      this.closest('.radio-item').classList.add('active');
    });
  });

  // Form submission
  document.getElementById('triwulanForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const triwulan = document.querySelector('input[name="triwulan"]:checked').value;

    fetch('{{ route("admin.triwulan.setting.update") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      },
      body: JSON.stringify({
        triwulan: triwulan
      })
    })
    .then(response => response.json())
    .then(data => {
      const alertDiv = document.getElementById('alertMessage');
      
      if (data.success) {
        alertDiv.innerHTML = `
          <i class="fas fa-check-circle"></i>
          ${data.message}
        `;
        alertDiv.className = 'alert alert-success show';
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Hide alert after 3 seconds
        setTimeout(() => {
          alertDiv.classList.remove('show');
        }, 3000);
      } else {
        alertDiv.innerHTML = `
          <i class="fas fa-exclamation-circle"></i>
          ${data.message}
        `;
        alertDiv.className = 'alert alert-error show';
      }
    })
    .catch(error => {
      console.error('Error:', error);
      const alertDiv = document.getElementById('alertMessage');
      alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle"></i>
        Terjadi kesalahan saat menyimpan pengaturan
      `;
      alertDiv.className = 'alert alert-error show';
    });
  });
</script>

@endsection
