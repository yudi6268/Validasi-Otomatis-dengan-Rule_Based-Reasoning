@extends('layouts.app')

@php
    // Get status from URL and set appropriate title
    $statusParam = request()->get('status');
    $pageTitle = 'Perjanjian';
    
    if ($statusParam === 'all') {
        $pageTitle = 'Perjanjian Terkirim';
    } elseif ($statusParam === 'approved') {
        $pageTitle = 'Perjanjian Disetujui';
    } elseif ($statusParam === 'rejected') {
        $pageTitle = 'Perjanjian Ditolak';
    } elseif ($statusParam === 'waiting') {
        $pageTitle = 'Menunggu Persetujuan';
    }
@endphp

@section('title', $pageTitle)
@section('header_title', $pageTitle)

@section('back')
<a href="javascript:void(0)" id="headerBackBtn" onclick="handleBackNavigation()"><i class="fa-solid fa-arrow-left header-icon"></i></a>
@endsection

@section('content')
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

    .page-title {
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 30px;
    }

    .stats-grid {
        max-width: 1000px;
        margin: 0 auto 50px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
    }

    @media (min-width: 768px) {
        .stats-grid {
            max-width: 1100px;
            gap: 40px;
        }
        
        .stat-card {
            padding: 50px 40px;
        }
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 40px 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        aspect-ratio: 1 / 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 64px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    @media (min-width: 768px) {
        .stat-number {
            font-size: 80px;
            margin-bottom: 20px;
        }
    }

    .stat-number.terkirim { color: #009970; }
    .stat-number.disetujui { color: #FFA500; }
    .stat-number.ditolak { color: #DC3545; }
    .stat-number.menunggu { color: #2196F3; }

    .stat-label {
        font-size: 16px;
        font-weight: 600;
        color: #666;
        margin-bottom: 20px;
    }

    @media (min-width: 768px) {
        .stat-label {
            font-size: 18px;
            margin-bottom: 25px;
        }
    }

    .stat-btn {
        display: inline-block;
        padding: 10px 30px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        color: white;
        border: none;
        cursor: pointer;
        transition: opacity 0.3s;
    }

    @media (min-width: 768px) {
        .stat-btn {
            padding: 12px 36px;
            font-size: 16px;
        }
    }

    .stat-btn:hover {
        opacity: 0.8;
    }

    .stat-btn.btn-terkirim { background: #009970; }
    .stat-btn.btn-disetujui { background: #FFA500; }
    .stat-btn.btn-ditolak { background: #DC3545; }
    .stat-btn.btn-menunggu { background: #2196F3; }

    .card-list {
        max-width: 900px;
        margin: 0 auto;
        display: none;
    }

    .card-list.active {
        display: block;
    }

    .back-to-stats {
        max-width: 900px;
        margin: 0 auto 20px;
        display: none;
    }

    .back-to-stats.active {
        display: none;
    }

    .back-to-stats button {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid #ddd;
        padding: 15px 0;
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        color: #1B2A41;
        z-index: 100;
    }

    .dashboard-container {
        padding-bottom: 80px;
    }

    .perjanjian-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        gap: 20px;
        align-items: center;
        transition: box-shadow 0.3s;
    }

    .perjanjian-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    @media (min-width: 768px) {
        .perjanjian-card {
            padding: 30px;
            gap: 25px;
        }
    }

    .card-icon {
        background: #2196F3;
        width: 70px;
        height: 70px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .card-icon i {
        color: white;
        font-size: 32px;
    }

    .card-content {
        flex: 1;
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    @media (min-width: 768px) {
        .card-icon {
            width: 80px;
            height: 80px;
        }
        
        .card-icon i {
            font-size: 36px;
        }
        
        .card-title {
            font-size: 20px;
        }
    }

    .card-status {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    @media (min-width: 768px) {
        .card-status {
            padding: 7px 16px;
            font-size: 14px;
        }
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
        font-size: 14px;
        color: #666;
    }

    @media (min-width: 768px) {
        .card-date {
            font-size: 15px;
        }
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
        font-size: 22px;
        padding: 10px;
        transition: opacity 0.3s;
    }

    @media (min-width: 768px) {
        .action-btn {
            font-size: 24px;
            padding: 12px;
        }
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
        bottom: 80px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: #009970;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,153,112,0.3);
        transition: all 0.3s;
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99;
    }

    .btn-add.active {
        display: flex;
    }

    .btn-add:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0,153,112,0.4);
    }

    .add-perjanjian-btn {
        position: fixed;
        bottom: 80px;
        right: 30px;
        background: #009970;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,153,112,0.3);
        transition: all 0.3s;
        z-index: 99;
    }

    .add-perjanjian-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,153,112,0.4);
    }

    @media (min-width: 768px) {
        .add-perjanjian-btn {
            padding: 16px 35px;
            font-size: 15px;
        }
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
        background: linear-gradient(180deg, #E3F8F6 0%, #D5F3EF 100%);
        margin: 15% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .modal-header {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .modal-body {
        padding: 40px 30px;
        text-align: center;
        font-size: 16px;
        color: #333;
        line-height: 1.5;
        font-weight: 500;
    }

    .modal-footer {
        display: flex;
        gap: 15px;
        justify-content: center;
        padding: 20px;
    }

    .modal-btn {
        padding: 10px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        transition: all 0.3s;
        min-width: 100px;
    }

    .modal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .modal-btn-cancel {
        background: #009970;
        color: white;
    }

    .modal-btn-confirm {
        background: #009970;
        color: white;
    }

    /* Success notification modal */
    .success-modal .modal-content {
        max-width: 350px;
    }
</style>

<div class="dashboard-container">
    
    <!-- Statistics Dashboard -->
    <div id="statsDashboard">
        <div class="page-title">DAFTAR DATA FORM PERJANJIAN</div>
        
        <div class="stats-grid">
            
            <div class="stat-card" onclick="showList('terkirim')" style="cursor: pointer;">
                <div class="stat-number terkirim">{{ $counts['total'] ?? 0 }}</div>
                <div class="stat-label">Terkirim</div>
                <button class="stat-btn btn-terkirim" onclick="event.stopPropagation(); showList('terkirim')">Lihat</button>
            </div>
            
            <div class="stat-card" onclick="showList('disetujui')" style="cursor: pointer;">
                <div class="stat-number disetujui">{{ $counts['approved'] ?? 0 }}</div>
                <div class="stat-label">Disetujui</div>
                <button class="stat-btn btn-disetujui" onclick="event.stopPropagation(); showList('disetujui')">Lihat</button>
            </div>
            
            <div class="stat-card" onclick="showList('ditolak')" style="cursor: pointer;">
                <div class="stat-number ditolak">{{ $counts['rejected'] ?? 0 }}</div>
                <div class="stat-label">Ditolak</div>
                <button class="stat-btn btn-ditolak" onclick="event.stopPropagation(); showList('ditolak')">Lihat</button>
            </div>
            
            <div class="stat-card" onclick="showList('menunggu')" style="cursor: pointer;">
                <div class="stat-number menunggu">{{ $counts['waiting'] ?? 0 }}</div>
                <div class="stat-label">Menunggu</div>
                <button class="stat-btn btn-menunggu" onclick="event.stopPropagation(); showList('menunggu')">Lihat</button>
            </div>
        </div>
    </div>

    <!-- Tambah Perjanjian Button (for stats view) -->
    <button class="add-perjanjian-btn" id="addBtnStats" onclick="window.location='{{ route('perjanjian.create') }}'">
        <i class="fas fa-plus"></i> Tambah Perjanjian
    </button>

    <!-- Back Button -->
    <div class="back-to-stats" id="backButton">
        <button onclick="showStats()"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</button>
    </div>

    <!-- Card List -->
    <div class="card-list" id="cardList">
        <!-- List perjanjian -->
        @forelse($items as $perjanjian)
            @php
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
            @endphp
            <div class="perjanjian-card">
                <div class="card-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="card-content">
                    <!-- ...isi card perjanjian... -->
                </div>
            </div>
        @empty
            <div style="text-align:center; padding:40px 0; color:#999;">
                <i class="fas fa-inbox" style="font-size:48px; margin-bottom:10px;"></i>
                <p>Belum ada perjanjian kinerja</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Footer -->
<div class="footer">
    © 2026 RSUD Bangil – Sistem Laporan Kinerja
</div>

<!-- Floating Add Button (for card list view) -->
<button class="btn-add" id="addBtnList" onclick="window.location='{{ route('perjanjian.create') }}'" title="Tambah Perjanjian Baru">
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
        <div class="modal-body">
            Apa anda ingin menghapusnya?
        </div>
        <div class="modal-footer">
            <button class="modal-btn modal-btn-cancel" onclick="deletePerjanjian()">Ya</button>
            <button class="modal-btn modal-btn-confirm" onclick="closeDeleteModal()">Tidak</button>
        </div>
    </div>
</div>

<!-- Modal Notifikasi Sukses -->
<div id="successModal" class="modal success-modal">
    <div class="modal-content">
        <div class="modal-body">
            Perjanjian kinerja berhasil dihapus
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
// Simple script without IIFE
console.log('=== PERJANJIAN INDEX SCRIPT START ===');

// Global variables
var allItems = [];
var currentFilter = '';
var viewOnly = false; // Flag for view-only mode (from direktur dashboard)

try {
    allItems = {!! json_encode($items) !!};
    console.log('All items loaded:', allItems.length);
} catch (e) {
    console.error('Error loading items:', e);
    allItems = [];
}

// Check URL parameters for auto-navigation and view mode
const urlParams = new URLSearchParams(window.location.search);
const statusParam = urlParams.get('status');
const viewOnlyParam = urlParams.get('view_only');

if (viewOnlyParam === '1') {
    viewOnly = true;
    console.log('View-only mode enabled');
}

if (statusParam) {
    console.log('Status parameter detected:', statusParam);
    // Map status from direktur dashboard to perjanjian filter
    const statusMapping = {
        'all': 'terkirim',
        'approved': 'disetujui',
        'rejected': 'ditolak',
        'waiting': 'menunggu'
    };
    const mappedStatus = statusMapping[statusParam] || statusParam;
    console.log('Mapped status:', mappedStatus);
}

console.log('Script initialization complete');

// Make functions globally accessible
window.showStats = function() {
        console.log('=== showStats called ===');
        try {
            document.getElementById('statsDashboard').style.display = 'block';
            document.getElementById('cardList').classList.remove('active');
            document.getElementById('backButton').classList.remove('active');
            document.getElementById('addBtnStats').style.display = 'block';
            document.getElementById('addBtnList').classList.remove('active');
            currentFilter = '';
            
            const headerBackBtn = document.getElementById('headerBackBtn');
            if (headerBackBtn) {
                headerBackBtn.style.display = 'inline-block';
            }
        } catch(e) {
            console.error('Error in showStats:', e);
        }
    };
    
    // Test function to verify script is working
    window.testClick = function() {
        console.log('TEST CLICK WORKING!');
        alert('JavaScript is working!');
    };
    
    console.log('Functions registered');
    console.log('showStats:', typeof window.showStats);
    console.log('testClick:', typeof window.testClick);
    
    window.showList = function(filter) {
        console.log('=== showList called with filter:', filter, '===');
        try {
            currentFilter = filter;
            document.getElementById('statsDashboard').style.display = 'none';
            document.getElementById('backButton').classList.add('active');
            document.getElementById('addBtnStats').style.display = 'none';
            document.getElementById('addBtnList').style.display = 'none';
            
            const cardList = document.getElementById('cardList');
            cardList.classList.add('active');
            
            const headerBackBtn = document.getElementById('headerBackBtn');
            if (headerBackBtn) {
                headerBackBtn.style.display = 'inline-block';
            }
            
            console.log('Rendering cards for filter:', filter);
            renderCards(filter);
        } catch(e) {
            console.error('Error in showList:', e);
        }
    };
    
    console.log('showList:', typeof window.showList);

function updateCounts() {
    const counts = {
        terkirim: 0,
        disetujui: 0,
        ditolak: 0,
        menunggu: 0,
        total: 0
    };
    
    allItems.forEach(item => {
        counts.total++;
        
        if (item.rejected && item.rejected == true) {
            counts.ditolak++;
        } else if (item.pihak2_signature && item.pihak2_signature != '') {
            counts.disetujui++;
        } else if (item.pihak2_name && !item.pihak2_signature && !item.rejected) {
            counts.menunggu++;
        }
    });
    
    // Update stat numbers in dashboard
    const statCards = document.querySelectorAll('.stat-card .stat-number');
    if (statCards.length >= 4) {
        statCards[0].textContent = counts.total; // Terkirim = total
        statCards[1].textContent = counts.disetujui;
        statCards[2].textContent = counts.ditolak;
        statCards[3].textContent = counts.menunggu;
    }
}

window.showList = function(filter) {
    console.log('=== showList called with filter:', filter, '===');
    currentFilter = filter;
    document.getElementById('statsDashboard').style.display = 'none';
    document.getElementById('backButton').classList.add('active');
    document.getElementById('addBtnStats').style.display = 'none';
    document.getElementById('addBtnList').style.display = 'none'; // Hide plus button
    
    const cardList = document.getElementById('cardList');
    cardList.classList.add('active');
    
    // Show header back button
    const headerBackBtn = document.getElementById('headerBackBtn');
    if (headerBackBtn) {
        headerBackBtn.style.display = 'inline-block';
    }
    
    console.log('Rendering cards for filter:', filter);
    // Render filtered cards
    renderCards(filter);
};

console.log('showList function registered:', typeof window.showList);

function renderCards(filter) {
    // Get search value
    const searchValue = (document.getElementById('searchInput')?.value || '').toLowerCase();

    let filteredItems = allItems.filter(item => {
        let status = 'menunggu';
        if (item.rejected && item.rejected == true) {
            status = 'ditolak';
        } else if (item.pihak2_signature && item.pihak2_signature != '') {
            status = 'disetujui';
        } else if (filter === 'terkirim') {
            status = 'terkirim';
        }
        // Map controller status to UI status
        let matchFilter = false;
        if (filter === 'terkirim') {
            matchFilter = item.pihak2_signature || item.rejected || item.pihak2_name;
        } else if (filter === 'disetujui') {
            matchFilter = item.pihak2_signature && item.pihak2_signature != '';
        } else if (filter === 'ditolak') {
            matchFilter = item.rejected && item.rejected == true;
        } else if (filter === 'menunggu') {
            matchFilter = item.pihak2_name && !item.pihak2_signature && !item.rejected;
        }
        // Search by name or status
        let namaPegawai = (item.pihak2_name || '').toLowerCase();
        let statusText = status.toLowerCase();
        let matchSearch = !searchValue || namaPegawai.includes(searchValue) || statusText.includes(searchValue);
        return matchFilter && matchSearch;
    });
    
    // Render filtered cards
    if (filteredItems.length === 0) {
        cardList.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><p>Tidak ada data</p></div>';
    } else {
        let html = '';
        filteredItems.forEach(item => {
            // Determine actual status for display
            let actualStatus = 'menunggu';
            let statusText = 'Menunggu';
            
            if (item.rejected && item.rejected == true) {
                actualStatus = 'ditolak';
                statusText = 'Ditolak';
            } else if (item.pihak2_signature && item.pihak2_signature != '') {
                actualStatus = 'disetujui';
                statusText = 'Disetujui';
            } else if (item.pihak2_name && !item.pihak2_signature && !item.rejected) {
                actualStatus = 'menunggu';
                statusText = 'Menunggu';
            }
            
            // For terkirim view, use actual status colors
            let displayStatusClass = filter === 'terkirim' ? actualStatus : filter;
            let displayStatusText = filter === 'terkirim' ? statusText : (filter.charAt(0).toUpperCase() + filter.slice(1));
            
            html += `
                <div class="perjanjian-card">
                    <div class="card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    
                    <div class="card-content">
                        <div class="card-title">Perjanjian Kinerja ${new Date(item.created_at).getFullYear()}</div>
                        <div class="card-status status-${displayStatusClass}">${displayStatusText}</div>
                        <div class="card-date">${formatDate(item.created_at)}</div>
                    </div>
                    
                    <div class="card-actions">
                        <a href="/perjanjian/${item.id}/print" class="action-btn btn-view" title="Lihat" target="_blank">
                            <i class="fas fa-eye"></i>
                        </a>`;
            
            if (filter === 'terkirim') {
                // Show actual status actions in terkirim view
                if (item.pihak2_signature) {
                    // Disetujui: view + download + delete (hide delete if viewOnly)
                    html += `
                        <a href="/perjanjian/${item.id}/pdf" class="action-btn btn-download" title="Download">
                            <i class="fas fa-download"></i>
                        </a>`;
                    if (!viewOnly) {
                        html += `
                        <button onclick="confirmDelete(${item.id})" class="action-btn btn-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
                    }
                } else if (item.rejected) {
                    // Ditolak: view + alasan
                    html += `
                        <button onclick="showReason('${escapeHtml(item.rejection_reason || 'Tidak ada alasan')}')" class="btn-reason" title="Alasan">
                            Alasan
                        </button>`;
                } else if (item.pihak2_name) {
                    // Menunggu: view + edit + delete (hide edit/delete if viewOnly)
                    if (!viewOnly) {
                        html += `
                        <a href="/perjanjian/${item.id}/edit" class="action-btn btn-edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <button onclick="confirmDelete(${item.id})" class="action-btn btn-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
                    }
                }
            } else if (filter === 'menunggu') {
                // Menunggu: view + edit + delete (hide edit/delete if viewOnly)
                if (!viewOnly) {
                    html += `
                        <a href="/perjanjian/${item.id}/edit" class="action-btn btn-edit" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <button onclick="confirmDelete(${item.id})" class="action-btn btn-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
                }
            } else if (filter === 'ditolak') {
                // Ditolak: view + alasan
                html += `
                        <button onclick="showReason('${escapeHtml(item.rejection_reason || 'Tidak ada alasan')}')" class="btn-reason" title="Alasan">
                            Alasan
                        </button>`;
            } else if (filter === 'disetujui') {
                // Disetujui: view + download + delete (hide delete if viewOnly)
                html += `
                        <a href="/perjanjian/${item.id}/pdf" class="action-btn btn-download" title="Download">
                            <i class="fas fa-download"></i>
                        </a>`;
                if (!viewOnly) {
                    html += `
                        <button onclick="confirmDelete(${item.id})" class="action-btn btn-delete" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>`;
                }
            }
            
            html += `
                    </div>
                </div>`;
        });
        cardList.innerHTML = html;
    }
}

function formatDate(dateString) {
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const date = new Date(dateString);
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function showReason(reason) {
    document.getElementById('reasonText').textContent = reason;
    document.getElementById('reasonModal').style.display = 'block';
}

function closeReasonModal() {
    document.getElementById('reasonModal').style.display = 'none';
}

let deleteId = null;

function confirmDelete(id) {
    deleteId = id;
    console.log('Setting deleteId to:', id);
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
    // Don't reset deleteId here, reset after delete completes
}

function deletePerjanjian() {
    if (!deleteId) {
        console.error('deleteId is null or undefined');
        alert('Terjadi kesalahan: ID perjanjian tidak ditemukan');
        return;
    }
    
    const idToDelete = deleteId;
    console.log('Deleting perjanjian ID:', idToDelete);
    
    // Close confirmation modal first
    document.getElementById('deleteModal').style.display = 'none';
    
    // Send AJAX delete request
    fetch('/perjanjian/' + idToDelete, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('HTTP error! status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        deleteId = null; // Reset after successful response
        
        if (data.success) {
            // Remove item from allItems array
            allItems = allItems.filter(item => item.id !== idToDelete);
            
            // Show success notification modal
            document.getElementById('successModal').style.display = 'block';
            
            // Auto close after 1.5 seconds and update UI
            setTimeout(() => {
                document.getElementById('successModal').style.display = 'none';
                
                // Update counts
                updateCounts();
                
                // If viewing a filtered list, refresh it
                if (currentFilter) {
                    renderCards(currentFilter);
                }
            }, 1500);
        } else {
            alert('Gagal menghapus perjanjian: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        deleteId = null; // Reset on error
        alert('Terjadi kesalahan saat menghapus perjanjian: ' + error.message);
    });
}

// Handle back button navigation - Make it globally accessible
window.handleBackNavigation = function() {
    console.log('handleBackNavigation called');
    
    // Check if we're in view-only mode (from direktur dashboard)
    const urlParams = new URLSearchParams(window.location.search);
    const viewOnlyParam = urlParams.get('view_only');
    
    if (viewOnlyParam === '1') {
        // If from direktur dashboard, go back to dashboard direktur
        console.log('Returning to direktur dashboard');
        window.location.href = '{{ route('dashboard.direktur') }}';
        return;
    }
    
    // Check if currently viewing card list (filtered view)
    const cardListActive = document.getElementById('cardList').classList.contains('active');
    console.log('Card list active:', cardListActive);
    
    if (cardListActive) {
        // If in card list, go back to stats dashboard
        showStats();
    } else {
        // If in stats dashboard, go to home
        window.location.href = '{{ route('home') }}';
    }
};

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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DOM CONTENT LOADED ===');
    console.log('Elements check:');
    console.log('- statsDashboard:', document.getElementById('statsDashboard'));
    console.log('- cardList:', document.getElementById('cardList'));
    console.log('- backButton:', document.getElementById('backButton'));
    console.log('- headerBackBtn:', document.getElementById('headerBackBtn'));

    const headerBackBtn = document.getElementById('headerBackBtn');
    if (headerBackBtn) {
        headerBackBtn.style.display = 'inline-block';
        console.log('Back button set to visible');
    }

    updateCounts();

    // Search input event
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (currentFilter) {
                renderCards(currentFilter);
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const statusParam = urlParams.get('status');

    if (statusParam) {
        const statusMapping = {
            'all': 'terkirim',
            'approved': 'disetujui',
            'rejected': 'ditolak',
            'waiting': 'menunggu'
        };
        const mappedStatus = statusMapping[statusParam] || statusParam;
        console.log('Auto-showing list with status:', mappedStatus);
        showList(mappedStatus);
    } else {
        const cardListActive = document.getElementById('cardList').classList.contains('active');
        console.log('Card list active:', cardListActive);
        if (!cardListActive) {
            console.log('Initializing stats dashboard');
            showStats();
        }
    }
    console.log('=== INITIALIZATION COMPLETE ===');
});

console.log('=== SCRIPT END ===');
</script>
@endsection
