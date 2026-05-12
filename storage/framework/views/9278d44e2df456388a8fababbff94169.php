<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Perjanjian Kinerja RSUD Bangil</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Tambahan agar sesuai gambar referensi */
.box {
  background: #fff;
  border: 1.5px solid #bbb;
  padding: 20px;
  border-radius: 8px;
  margin-bottom: 10px;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
}
th {
  background: #f7f7f7;
  font-weight: bold;
  color: #222;
  border-bottom: 2px solid #bbb;
  padding: 10px 0;
}
td {
  border: none;
  font-size: 16px;
  padding: 8px 0;
}
.status[data-status="proses"] {
  background: #4C9CF0;
  color: #fff;
  font-weight: bold;
  padding: 4px 14px;
  border-radius: 6px;
  font-size: 15px;
}
.status[data-status="menunggu"] {
  background: #4C9CF0;
  color: #fff;
  font-weight: bold;
  padding: 4px 14px;
  border-radius: 6px;
  font-size: 15px;
}
.status[data-status="setuju"] {
  background: #F5E94E;
  color: #222;
  font-weight: bold;
  padding: 4px 14px;
  border-radius: 6px;
  font-size: 15px;
}
.status[data-status="tolak"] {
  background: #FF2E2E;
  color: #fff;
  font-weight: bold;
  padding: 4px 14px;
  border-radius: 6px;
  font-size: 15px;
}
/* Tombol default */
.btn {
  background: #9E9E9E;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 6px 18px;
  font-size: 15px;
  font-weight: bold;
  cursor: pointer;
  margin: 0 2px;
  text-decoration: none;
  display: inline-block;
}
/* Animasi hover dan klik pada card */
.stat {
  transition: transform 0.18s cubic-bezier(.4,2,.6,1), box-shadow 0.18s;
  cursor: pointer;
}
.stat:hover, .stat:focus {
  transform: scale(1.06);
  box-shadow: 0 6px 24px rgba(0,0,0,0.13);
  z-index: 2;
}
.stat:active {
  transform: scale(0.96);
  box-shadow: 0 2px 8px rgba(0,0,0,0.10);
}
.box h3 {
  color: #777;
  margin-bottom: 15px;
  font-weight: bold;
  font-size: 18px;
}
.more-link {
  color: #E74C3C;
  text-align: center;
  font-weight: bold;
  font-size: 18px;
  margin-top: 10px;
  display: block;
}
*{margin:0;padding:0;box-sizing:border-box}
body{
  font-family:'Poppins',sans-serif;
  background:#E6FAF7;
}

/* HEADER */
header{
  background:#fff;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:10px 40px;
  box-shadow:0 4px 12px rgba(0,153,112,.15);
}
.logo-container{display:flex;align-items:center;gap:10px}
.logo-container img{height:55px}
nav a{
  font-size:22px;
  font-weight:800;
  color:#0DA45C;
  text-decoration:none;
}
.icons i{
  background:#E6F6F2;
  color:#00B5A0;
  padding:8px;
  border-radius:50%;
  margin-left:10px;
}

/* MAIN */
main{padding:40px 20px}
.container{max-width:1100px;margin:auto;text-align:center}

/* SEARCH */
.search-box{
  max-width:600px;
  margin:0 auto 30px;
  position:relative;
}
.search-box input{
  width:100%;
  padding:14px 50px 14px 20px;
  border-radius:30px;
  border:1px solid #ccc;
  font-size:16px;
}
.search-box i{
  position:absolute;
  right:20px;
  top:50%;
  transform:translateY(-50%);
  color:#777;
}

/* CARD STATUS */
.stats{
  display:flex;
  gap:20px;
  margin-bottom:35px;
}
.stat{
  flex:1;
  padding:20px;
  border-radius:18px;
  color:#fff;
  font-weight:800;
  cursor:pointer;
}
.stat span{
  display:block;
  font-size:42px;
  margin-top:10px;
}
.stat.active{
  outline:3px solid #0DA45C;
}
.green{background:#0DA45C}
.yellow{background:#F5E94E;color:#222}
.red{background:#FF2E2E}
.blue{background:#4C9CF0}

/* TABLE */
.box{
  background:#FFF5F5;
  padding:20px;
  border-radius:16px;
  text-align:left;
}
.box h3{
  color:#777;
  margin-bottom:15px;
}
table{
  width:100%;
  border-collapse:collapse;
}
th,td{padding:10px 0}
.status{
  padding:6px 14px;
  border-radius:8px;
  font-size:13px;
  font-weight:700;
  color:#fff;
}
.status[data-status="proses"]{background:#4C9CF0}
.status[data-status="setuju"]{background:#F5E94E;color:#222}
.status[data-status="tolak"]{background:#FF2E2E}
.btn{
  background:#9E9E9E;
  border:none;
  color:#fff;
  padding:6px 14px;
  border-radius:6px;
}

@media(max-width:900px){
  .stats{flex-direction:column}
}
</style>
</head>

<body>

<header>
  <div class="logo-container">
    <img src="<?php echo e(asset('images/logo_pemda.png')); ?>">
    <img src="<?php echo e(asset('images/logo_rsud.png')); ?>">
  </div>
  <nav>
    <a>SISTEM PERJANJIAN KINERJA RSUD BANGIL</a>
  </nav>

  <div class="icons" style="display:flex;align-items:center;gap:12px;">
    <i class="fa-solid fa-user" id="openProfileModal" title="Profil Saya" style="cursor:pointer;background:#E6F6F2;padding:8px;border-radius:50%;"></i>
    <i class="fa-solid fa-right-from-bracket" id="logout-btn" style="cursor:pointer;background:#E6F6F2;padding:8px;border-radius:50%;"></i>
  </div>
</header>

<!-- MODAL PROFIL (Dropdown) -->
<div id="profileModal" style="display:none;position:absolute;top:62px;right:48px;background:#E6FAF7;border-left:6px solid #FFB6B6;z-index:99999;box-shadow:0 2px 16px rgba(0,0,0,0.08);border-radius:14px;padding:18px 0 8px 0;min-width:210px;max-width:260px;">
  <div style="display:flex;flex-direction:column;gap:16px;align-items:flex-start;padding-left:20px;padding-right:20px;">
    <a href="<?php echo e(route('profil')); ?>" style="display:flex;align-items:center;gap:12px;color:#0DA45C;font-size:16px;font-weight:600;text-decoration:none;margin-bottom:4px;"><i class="fa-solid fa-user" style="font-size:22px;"></i> Profil Saya</a>
    <a href="<?php echo e(route('kontak')); ?>" style="display:flex;align-items:center;gap:12px;color:#0DA45C;font-size:16px;font-weight:600;text-decoration:none;margin-bottom:4px;"><i class="fa-solid fa-phone" style="font-size:22px;"></i> Kontak</a>
    <a href="<?php echo e(route('panduan')); ?>" style="display:flex;align-items:center;gap:12px;color:#0DA45C;font-size:16px;font-weight:600;text-decoration:none;margin-bottom:4px;"><i class="fa-solid fa-book" style="font-size:22px;"></i> Panduan</a>
    <a href="<?php echo e(route('tentang')); ?>" style="display:flex;align-items:center;gap:12px;color:#0DA45C;font-size:16px;font-weight:600;text-decoration:none;margin-bottom:4px;"><i class="fa-solid fa-circle-info" style="font-size:22px;"></i> Tentang Aplikasi</a>
    <a href="<?php echo e(route('settings')); ?>" style="display:flex;align-items:center;gap:12px;color:#0DA45C;font-size:16px;font-weight:600;text-decoration:none;margin-bottom:4px;"><i class="fa-solid fa-gear" style="font-size:22px;"></i> Settings</a>
  </div>
  <button onclick="document.getElementById('profileModal').style.display='none'" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:18px;color:#0DA45C;cursor:pointer;">&times;</button>
</div>
<script>
document.getElementById('openProfileModal').onclick = function(e) {
  e.preventDefault();
  var modal = document.getElementById('profileModal');
  if(modal.style.display === 'block') {
    modal.style.display = 'none';
  } else {
    modal.style.display = 'block';
  }
};
// Tutup modal jika klik di luar
document.addEventListener('mousedown', function(event) {
  var modal = document.getElementById('profileModal');
  var btn = document.getElementById('openProfileModal');
  if (modal.style.display === 'block' && !modal.contains(event.target) && !btn.contains(event.target)) {
    modal.style.display = 'none';
  }
});
</script>

<main>
<?php echo $__env->make('components.logout-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="container">

  <!-- SEARCH -->
  <div class="search-box">
    <input type="text" id="searchInput" placeholder="Cari nama, tanggal, status">
    <i class="fa fa-search"></i>
  </div>

  <!-- CARD STATUS -->
  <div class="stats">
    <div class="stat green active" data-filter="all" tabindex="0">
      Total Perjanjian Kinerja<span><?php echo e($counts['all'] ?? 0); ?></span>
    </div>
    <div class="stat yellow" data-filter="setuju" tabindex="0">
      Disetujui<span><?php echo e($counts['approved'] ?? 0); ?></span>
    </div>
    <div class="stat red" data-filter="tolak" tabindex="0">
      Ditolak<span><?php echo e($counts['rejected'] ?? 0); ?></span>
    </div>
    <div class="stat blue" data-filter="menunggu" tabindex="0">
      Perjanjian Menunggu<span><?php echo e($counts['waiting'] ?? 0); ?></span>
    </div>
  </div>

  <!-- TABLE -->
  <div class="box">
    <h3 id="tabel-perjanjian">LAPORAN TERBARU</h3>
    <table>
      <thead>
        <tr>
          <th style="width:5%;min-width:40px;">No</th>
          <th style="width:35%;min-width:160px;">Nama Pegawai</th>
          <th style="width:20%;min-width:100px;text-align:center;">Tanggal</th>
          <th style="width:15%;min-width:90px;text-align:center;">Status</th>
          <th style="width:15%;min-width:90px;text-align:center;">Aksi</th>
        </tr>
      </thead>
      <tbody id="dataTable">
        <?php $__currentLoopData = $perjanjians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $perjanjian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td style="font-weight:bold; text-align:center;"><?php echo e($i+1); ?></td>
          <td style="font-weight:bold;"><?php echo e($perjanjian->pihak1_name); ?></td>
          <td style="font-weight:bold; text-align:center;"><?php echo e(\Carbon\Carbon::parse($perjanjian->created_at)->format('d-m-y')); ?></td>
          <td style="text-align:center; vertical-align:middle;">
            <?php if($perjanjian->rejected === true): ?>
              <span class="status" data-status="tolak">Ditolak</span>
            <?php elseif(!empty($perjanjian->pihak2_signature)): ?>
              <span class="status" data-status="setuju">Disetujui</span>
            <?php else: ?>
              <span class="status" data-status="menunggu">Menunggu</span>
            <?php endif; ?>
          </td>
          <td style="text-align:center; vertical-align:middle;">
            <a href="<?php echo e(route('perjanjian.print', $perjanjian->id)); ?>" class="btn" target="_blank">Lihat</a>
          </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
    <?php if(method_exists($perjanjians, 'hasPages') && $perjanjians->hasPages()): ?>
      <span class="more-link">Lebih Banyak</span>
    <?php endif; ?>
  </div>

</div>
</main>

<script>
// Logout modal konsisten
document.addEventListener('DOMContentLoaded', function() {
  var logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    logoutBtn.onclick = function() {
      if (typeof showLogoutModal === 'function') showLogoutModal();
      else if (document.getElementById('logoutModal')) document.getElementById('logoutModal').style.display = 'flex';
    };
  }
});
let rows = document.querySelectorAll('#dataTable tr');
const searchInput = document.getElementById('searchInput');
const cards = document.querySelectorAll('.stat');

let activeStatus = 'all';

function renderTable() {
  const keyword = searchInput.value.toLowerCase();

  // RESET: sembunyikan semua baris
  rows.forEach(row => row.style.display = 'none');

  rows.forEach(row => {
    const statusEl = row.querySelector('.status');
    if (!statusEl) return;

    const rowStatus = statusEl.dataset.status;
    const rowText = row.innerText.toLowerCase();

    const statusMatch =
      activeStatus === 'all' || rowStatus === activeStatus;

    const searchMatch = rowText.includes(keyword);

    if (statusMatch && searchMatch) {
      row.style.display = '';
    }
  });
}

// Klik card: animasi, filter, dan scroll ke tabel
cards.forEach(card => {
  card.addEventListener('click', () => {
    cards.forEach(c => c.classList.remove('active'));
    card.classList.add('active');

    activeStatus = card.dataset.filter;
    searchInput.value = '';
    renderTable();
    // Scroll ke tabel
    const tabel = document.getElementById('tabel-perjanjian');
    if (tabel) {
      tabel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
  // Aksesibilitas: enter/space
  card.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      card.click();
    }
  });
});

// Search
searchInput.addEventListener('keyup', renderTable);

// Load awal
renderTable();

// === Realtime refresh data & counts ===
async function refreshDashboardData() {
  try {
    const res = await fetch('<?php echo e(route('direktur.perjanjian')); ?>', {
      method: 'GET',
      cache: 'no-store',
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    });
    if (!res.ok) return;
    const payload = await res.json();
    if (!payload || !payload.data || !payload.counts) return;

    // Update counts on cards
    const countMap = {
      all: payload.counts.all || 0,
      setuju: payload.counts.approved || 0,
      tolak: payload.counts.rejected || 0,
      menunggu: payload.counts.waiting || 0,
    };
    document.querySelectorAll('.stat').forEach(card => {
      const filter = card.dataset.filter;
      const span = card.querySelector('span');
      if (span && countMap[filter] !== undefined) {
        span.textContent = countMap[filter];
      }
    });

    // Rebuild table
    const tbody = document.getElementById('dataTable');
    if (!tbody) return;
    tbody.innerHTML = '';
    const statusLabel = {
      approved: { text: 'Disetujui', cls: 'setuju' },
      rejected: { text: 'Ditolak', cls: 'tolak' },
      waiting: { text: 'Menunggu', cls: 'menunggu' },
    };
    payload.data.forEach((item, idx) => {
      const rowStatus = statusLabel[item.status] || statusLabel.waiting;
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td style="font-weight:bold; text-align:center;">${idx + 1}</td>
        <td style="font-weight:bold;">${item.pihak1_name || ''}</td>
        <td style="font-weight:bold; text-align:center;">${item.created_at || ''}</td>
        <td style="text-align:center; vertical-align:middle;">
          <span class="status" data-status="${rowStatus.cls}">${rowStatus.text}</span>
        </td>
        <td style="text-align:center; vertical-align:middle;">
          <a href="/perjanjian/${item.id}/print" class="btn" target="_blank">Lihat</a>
        </td>`;
      tbody.appendChild(tr);
    });

    // Refresh rows reference and re-render with current filter/search
    rows = document.querySelectorAll('#dataTable tr');
    renderTable();
  } catch (e) {
    console.warn('Refresh dashboard failed', e);
  }
}

// Jalankan saat load dan interval berkala
refreshDashboardData();
setTimeout(refreshDashboardData, 3000); // refresh cepat setelah load
setInterval(refreshDashboardData, 10000); // interval lebih sering
window.addEventListener('focus', refreshDashboardData); // refresh ketika tab kembali aktif

// Dengarkan notifikasi dari tab/preview untuk refresh instan
window.addEventListener('message', function(event) {
  try {
    if (event.origin !== window.location.origin) return;
    if (event.data && event.data.type === 'PERJANJIAN_STATUS_CHANGED') {
      refreshDashboardData();
    }
  } catch (_) {}
});
</script>

</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/dashboard/direktur.blade.php ENDPATH**/ ?>