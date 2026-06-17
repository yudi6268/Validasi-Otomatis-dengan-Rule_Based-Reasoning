

<?php $__env->startSection('title', 'Perjanjian'); ?>
<?php $__env->startSection('header_title', 'Dikirim'); ?>

<?php $__env->startSection('back'); ?>
<a href="<?php echo e(route('home')); ?>"><i class="fa-solid fa-arrow-left header-icon"></i></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
    body {
        background: #E3F8F6;
    }

    .dashboard-container {
        max-width: 100%;
        margin: 0;
        padding: 20px;
        min-height: 100vh;
    }

    .card-list {
        max-width: 700px;
        margin: 0 auto;
    }

    .perjanjian-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        gap: 15px;
        align-items: center;
        transition: box-shadow 0.3s;
    }

    .perjanjian-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .card-icon {
        background: #2196F3;
        width: 60px;
        height: 60px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-icon i {
        color: white;
        font-size: 28px;
    }

    .card-content {
        flex: 1;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .card-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .status-terkirim {
        background: #d4edda;
        color: #009970;
    }

    .status-menunggu {
        background: #fff3cd;
        color: #FFA500;
    }

    .status-ditolak {
        background: #f8d7da;
        color: #DC3545;
    }

    .status-disetujui {
        background: #d4edda;
        color: #009970;
    }

    .card-date {
        font-size: 13px;
        color: #666;
    }

    .card-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .action-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 20px;
        padding: 8px;
        transition: opacity 0.3s;
    }

    .action-btn:hover {
        opacity: 0.7;
    }

    .btn-view {
        color: #2196F3;
    }

    .btn-edit {
        color: #333;
    }

    .btn-delete {
        color: #DC3545;
    }

    .btn-download {
        color: #009970;
    }

    .btn-reason {
        background: #6c757d;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #999;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        color: #ddd;
    }

    .btn-add {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: #009970;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 28px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,153,112,0.3);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0,153,112,0.4);
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
    }

    .modal-header {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .modal-body {
        margin-bottom: 20px;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .modal-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .modal-btn-cancel {
        background: #6c757d;
        color: white;
    }

    .modal-btn-confirm {
        background: #DC3545;
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .stat-number {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .stat-label {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }

    .stat-btn {
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: opacity 0.3s;
    }

    .stat-btn:hover {
        opacity: 0.85;
    }

    .btn-green {
        background: #009970;
        color: white;
    }

    .btn-yellow {
        background: #ffc107;
        color: #333;
    }

    .btn-red {
        background: #dc3545;
        color: white;
    }

    .btn-blue {
        background: #007bff;
        color: white;
    }

    .data-table-section {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        overflow: auto;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 50px auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 1100px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: #009970;
        color: white;
        padding: 20px 30px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
    }

    .close {
        color: white;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
        transition: color 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #ffeb3b;
    }

    .modal-body {
        padding: 25px 30px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .action-btn-edit {
        background: #17a2b8;
        color: white;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        display: inline-block;
        margin-right: 5px;
        transition: opacity 0.3s;
    }

    .action-btn-edit:hover {
        opacity: 0.8;
        color: white;
    }

    .stat-card {
        cursor: pointer;
    }

    .data-table-section
        margin-top: 30px;
    }

    .table-header {
        background: #009970;
        color: white;
        padding: 20px;
        font-weight: 600;
        font-size: 16px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .data-table th, .data-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .data-table th {
        background: #009970;
        color: white;
        font-weight: 600;
    }

    .data-table tbody tr:hover {
        background: #f5f5f5;
    }

    .action-btn {
        display: inline-block;
        padding: 6px 12px;
        margin: 0 3px;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .btn-view {
        background: #007bff;
        color: white;
    }

    .btn-download {
        background: #28a745;
        color: white;
    }

    .btn-pdf {
        background: #dc3545;
        color: white;
    }

    .btn-pdf:hover {
        opacity: 0.8;
    }

    .btn-view {
        background: #009970;
        color: white;
    }

    .btn-view:hover {
        opacity: 0.8;
    }

    .btn-add {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 15px 30px;
        background: #009970;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        transition: all 0.3s;
    }

    .btn-add:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-container">
    <div class="card-list">
        <?php $__empty_1 = true; $__currentLoopData = $perjanjians; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perjanjian): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Tentukan status
                $status = 'menunggu';
                $statusText = 'Terkirim';
                
                if (!empty($perjanjian->rejected) && $perjanjian->rejected == true) {
                    $status = 'ditolak';
                    $statusText = 'Terkirim';
                } elseif (!empty($perjanjian->pihak2_signature)) {
                    $status = 'disetujui';
                    $statusText = 'Terkirim';
                } else {
                    $statusText = 'Terkirim';
                }
            ?>
            
            <div class="perjanjian-card">
                <div class="card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                
                <div class="card-content">
                    <div class="card-title">Perjanjian Kinerja <?php echo e(date('Y', strtotime($perjanjian->created_at))); ?></div>
                    <div class="card-status status-<?php echo e($status); ?>"><?php echo e($statusText); ?></div>
                    <div class="card-date"><?php echo e($perjanjian->created_at->format('d F Y')); ?></div>
                </div>
                
                <div class="card-actions">
                    <!-- View Button -->
                    <a href="<?php echo e(route('perjanjian.show', $perjanjian->id)); ?>" class="action-btn btn-view" title="Lihat">
                        <i class="fas fa-eye"></i>
                    </a>
                    
                    <?php if($status === 'menunggu'): ?>
                        <!-- Edit Button (Menunggu) -->
                        <a href="<?php echo e(route('perjanjian.edit', $perjanjian->id)); ?>" class="action-btn btn-edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <!-- Delete Button (Menunggu) -->
                        <button onclick="confirmDelete(<?php echo e($perjanjian->id); ?>)" class="action-btn btn-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    <?php elseif($status === 'ditolak'): ?>
                        <!-- Alasan Button (Ditolak) -->
                        <button onclick="showReason('<?php echo e(addslashes($perjanjian->rejection_reason ?? 'Tidak ada alasan')); ?>')" class="btn-reason" title="Alasan">
                            Alasan
                        </button>
                    <?php elseif($status === 'disetujui'): ?>
                        <!-- Download Button (Disetujui) -->
                        <a href="<?php echo e(route('perjanjian.download', $perjanjian->id)); ?>" class="action-btn btn-download" title="Download">
                            <i class="fas fa-download"></i>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Delete Button (Terkirim & Disetujui) -->
                    <?php if($status === 'terkirim' || $status === 'disetujui'): ?>
                        <button onclick="confirmDelete(<?php echo e($perjanjian->id); ?>)" class="action-btn btn-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada perjanjian kinerja</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Floating Add Button -->
<button class="btn-add" onclick="window.location='<?php echo e(route('perjanjian.create')); ?>'" title="Tambah Perjanjian Baru">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal Alasan Penolakan -->
<div id="reasonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Alasan Penolakan</div>
        <div class="modal-body">
            <p id="reasonText"></p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" onclick="closeReasonModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">Konfirmasi Hapus</div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus perjanjian ini?</p>
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <button class="modal-btn modal-btn-confirm" onclick="deletePerjanjian()">Hapus</button>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
let deleteId = null;

function showReason(reason) {
    document.getElementById('reasonText').textContent = reason;
    document.getElementById('reasonModal').style.display = 'block';
}

function closeReasonModal() {
    document.getElementById('reasonModal').style.display = 'none';
}

function confirmDelete(id) {
    deleteId = id;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    deleteId = null;
}

function deletePerjanjian() {
    if (deleteId) {
        const form = document.getElementById('deleteForm');
        form.action = '/perjanjian/' + deleteId;
        form.submit();
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const reasonModal = document.getElementById('reasonModal');
    const deleteModal = document.getElementById('deleteModal');
    
    if (event.target == reasonModal) {
        closeReasonModal();
    }
    if (event.target == deleteModal) {
        closeDeleteModal();
    }
}
</script>
<?php $__env->stopSection(); ?>
        <!-- Card 1: Perjanjian Terkirim (Total semua) -->
        <div class="stat-card" data-filter="all">
            <div class="stat-number" style="color: #009970;"><?php echo e(($counts['approved'] ?? 0) + ($counts['rejected'] ?? 0) + ($counts['waiting'] ?? 0)); ?></div>
            <div class="stat-label">Perjanjian Terkirim</div>
            <button class="stat-btn btn-green" onclick="openModal('all')">Lihat</button>
        </div>

        <!-- Card 2: Disetujui -->
        <div class="stat-card" data-filter="approved">
            <div class="stat-number" style="color: #ffc107;"><?php echo e($counts['approved'] ?? 0); ?></div>
            <div class="stat-label">Disetujui</div>
            <button class="stat-btn btn-yellow" onclick="openModal('approved')">Lihat</button>
        </div>

        <!-- Card 3: Ditolak -->
        <div class="stat-card" data-filter="rejected">
            <div class="stat-number" style="color: #dc3545;"><?php echo e($counts['rejected'] ?? 0); ?></div>
            <div class="stat-label">Ditolak</div>
            <button class="stat-btn btn-red" onclick="openModal('rejected')">Lihat</button>
        </div>

        <!-- Card 4: Menunggu -->
        <div class="stat-card" data-filter="waiting">
            <div class="stat-number" style="color: #007bff;"><?php echo e($counts['waiting'] ?? 0); ?></div>
            <div class="stat-label">Menunggu</div>
            <button class="stat-btn btn-blue" onclick="openModal('waiting')">Lihat</button>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan data -->
<div id="dataModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Data Perjanjian Kinerja</h2>
            <span class="close" onclick="closeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <table id="perjanjianTable" class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pihak Pertama</th>
                        <th>Pihak Kedua</th>
                        <th>Jabatan</th>
                        <th>Tanggal Dibuat</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- rows will be rendered by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<button class="btn-add" onclick="window.location='<?php echo e(route('perjanjian.create')); ?>'">
    ➕ Tambah Perjanjian Baru
</button>

<script>
let currentFilter = '';

// Open Modal
function openModal(filter) {
    currentFilter = filter;
    const modal = document.getElementById('dataModal');
    const modalTitle = document.getElementById('modalTitle');
    
    // Set modal title
    const titles = {
        'all': 'Perjanjian Terkirim',
        'approved': 'Perjanjian Disetujui',
        'rejected': 'Perjanjian Ditolak',
        'waiting': 'Perjanjian Menunggu'
    };
    modalTitle.textContent = titles[filter] || 'Data Perjanjian Kinerja';
    
    modal.style.display = 'block';
    fetchFiltered(filter);
}

// Close Modal
function closeModal() {
    const modal = document.getElementById('dataModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('dataModal');
    if (event.target == modal) {
        closeModal();
    }
}

// Fetch and render filtered perjanjian list
function renderRows(items, filter) {
    const tbody = document.querySelector('#perjanjianTable tbody');
    tbody.innerHTML = '';
    if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:20px;">Tidak ada data untuk status ini.</td></tr>';
        return;
    }

    items.forEach((it, idx) => {
        const tr = document.createElement('tr');
        const viewUrl = `<?php echo e(url('perjanjian')); ?>/${it.id}/print`;
        const pdfUrl = `<?php echo e(url('perjanjian')); ?>/${it.id}/pdf`;
        const editUrl = `<?php echo e(url('perjanjian')); ?>/${it.id}/edit`;
        const fileName = `Perjanjian_Kinerja_${(it.pihak1_name||'perjanjian').replace(/\s+/g,'_')}_${(new Date()).toISOString().slice(0,10)}.pdf`;

        // Tentukan apakah bisa diedit (hanya jika status waiting dan belum ada tanda tangan pihak kedua)
        const canEdit = (filter === 'waiting' && !it.pihak2_signature);
        
        let actionButtons = `
            <a href="${viewUrl}" class="action-btn btn-view" title="Lihat" target="_blank">👁 Lihat</a>
            <a href="#" data-pdf="${pdfUrl}" data-fname="${fileName}" class="action-btn btn-download" title="Download PDF">📥 Download</a>
        `;
        
        // Tambahkan tombol Edit hanya untuk perjanjian menunggu yang belum ditandatangani
        if (canEdit) {
            actionButtons = `
                <a href="${editUrl}" class="action-btn-edit" title="Edit">✏️ Edit</a>
                ${actionButtons}
            `;
        }
        
        tr.innerHTML = `
            <td>${idx + 1}</td>
            <td>${it.pihak1_name || '-'}</td>
            <td>${it.pihak2_name || '-'}</td>
            <td>${it.jabatan || '-'}</td>
            <td>${it.created_at || '-'}</td>
            <td style="text-align:center;">
                ${actionButtons}
            </td>
        `;

        tbody.appendChild(tr);
    });
}

// Download handler: fetch PDF as blob and force save
async function downloadPdf(url, filename){
    try{
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
        if(!res.ok){
            // try to parse JSON error
            const ct = res.headers.get('content-type') || '';
            if(ct.includes('application/json')){
                const j = await res.json();
                throw new Error(j.message || 'Server error saat membuat PDF');
            }
            throw new Error('Gagal mengunduh PDF (HTTP ' + res.status + ')');
        }

        const ct = res.headers.get('content-type') || '';
        if(!ct.includes('application/pdf')){
            // server returned HTML or JSON; don't save as PDF
            if(ct.includes('application/json')){
                const j = await res.json();
                throw new Error(j.message || 'Server returned JSON instead of PDF');
            } else {
                const txt = await res.text();
                console.error('Non-pdf response:', txt);
                throw new Error('Server returned non-PDF response. Cek log aplikasi.');
            }
        }

        const blob = await res.blob();
        const blobUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename || 'perjanjian.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(blobUrl);
    } catch(err){
        console.error(err);
        alert('Gagal mendownload PDF: ' + (err.message || 'Cek konsol.'));
    }
}

// delegate download clicks
document.addEventListener('click', function(e){
    const el = e.target.closest && e.target.closest('.btn-download');
    if(el){
        e.preventDefault();
        const url = el.getAttribute('data-pdf');
        const fname = el.getAttribute('data-fname');
        downloadPdf(url, fname);
    }
});

function fetchFiltered(filter) {
    const url = '<?php echo e(route('perjanjian.index')); ?>' + '?filter=' + encodeURIComponent(filter);
    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(json => {
        const items = json.data || [];
        renderRows(items, filter);
    })
    .catch(err => {
        console.error('Fetch error', err);
        alert('Gagal memuat data. Cek konsol.');
    });
}
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\perjanjian\index_old_backup.blade.php ENDPATH**/ ?>