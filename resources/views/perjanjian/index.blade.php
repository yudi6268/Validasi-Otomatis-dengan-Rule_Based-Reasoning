@extends('layouts.app')

@section('title', 'Perjanjian')
@section('header_title', 'Perjanjian')

@section('back')
<a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left header-icon"></i></a>
@endsection

@section('content')
<style>
    .dashboard-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-title {
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 40px;
        color: #333;
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
    <div class="header-title">DAFTAR DATA FORM PERJANJIAN</div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <!-- Card 1: Laporan Dikirim -->
        <div class="stat-card" data-filter="sent">
            <div class="stat-number" style="color: #009970;">{{ $counts['sent'] ?? 0 }}</div>
            <div class="stat-label">Laporan Dikirim</div>
            <a href="#" class="stat-btn btn-green">Lihat</a>
        </div>

        <!-- Card 2: Disetujui -->
        <div class="stat-card" data-filter="approved">
            <div class="stat-number" style="color: #ffc107;">{{ $counts['approved'] ?? 0 }}</div>
            <div class="stat-label">Disetujui</div>
            <a href="#" class="stat-btn btn-yellow">Lihat</a>
        </div>

        <!-- Card 3: Ditolak -->
        <div class="stat-card" data-filter="rejected">
            <div class="stat-number" style="color: #dc3545;">{{ $counts['rejected'] ?? 0 }}</div>
            <div class="stat-label">Ditolak</div>
            <a href="#" class="stat-btn btn-red">Lihat</a>
        </div>

        <!-- Card 4: Menunggu -->
        <div class="stat-card" data-filter="waiting">
            <div class="stat-number" style="color: #007bff;">{{ $counts['waiting'] ?? 0 }}</div>
            <div class="stat-label">Menunggu</div>
            <a href="#" class="stat-btn btn-blue">Lihat</a>
        </div>
    </div>
    <!-- Data Table Section (hidden until a card is clicked) -->
    <div id="dataSection" class="data-table-section" style="display:none;">
        <div class="table-header">Data Perjanjian Kinerja</div>
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

<button class="btn-add" onclick="window.location='{{ route('perjanjian.create') }}'">
    ➕ Tambah Perjanjian Baru
</button>

<script>
// Fetch and render filtered perjanjian list
function renderRows(items) {
    const tbody = document.querySelector('#perjanjianTable tbody');
    tbody.innerHTML = '';
    if (!items || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:20px;">Tidak ada data untuk status ini.</td></tr>';
        return;
    }

        items.forEach((it, idx) => {
        const tr = document.createElement('tr');
        const viewUrl = `{{ url('perjanjian') }}/${it.id}/print`;
        const pdfUrl = `{{ url('perjanjian') }}/${it.id}/pdf`;
        const fileName = `Perjanjian_Kinerja_${(it.pihak1_name||'perjanjian').replace(/\s+/g,'_')}_${(new Date()).toISOString().slice(0,10)}.pdf`;

        tr.innerHTML = `
            <td>${idx + 1}</td>
            <td>${it.pihak1_name || '-'}</td>
            <td>${it.pihak2_name || '-'}</td>
            <td>${it.jabatan || '-'}</td>
            <td>${it.created_at || '-'}</td>
            <td style="text-align:center;">
                <a href="${viewUrl}" class="action-btn btn-view" title="Lihat" target="_blank">👁 Lihat</a>
                <a href="#" data-pdf="${pdfUrl}" data-fname="${fileName}" class="action-btn btn-download" title="Download PDF">📥 Download</a>
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
    const url = '{{ route('perjanjian.index') }}' + '?filter=' + encodeURIComponent(filter);
    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(json => {
        const items = json.data || [];
        document.getElementById('dataSection').style.display = 'block';
        renderRows(items);
    })
    .catch(err => {
        console.error('Fetch error', err);
        alert('Gagal memuat data. Cek konsol.');
    });
}

// Attach click handlers to stat cards
document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('click', function(e) {
        const filter = this.getAttribute('data-filter');
        fetchFiltered(filter);
    });
});
</script>

@endsection