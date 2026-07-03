@extends('layouts.app')

@php
    $editBackRoute = request('from') === 'dashboard_wadir_perjanjian'
        ? route('dashboard.wadir', ['panel' => 'perjanjian'])
        : route('perjanjian.index');
@endphp

@section('title', 'Edit Perjanjian')

@section('back')
<a href="{{ $editBackRoute }}" target="_self" style="text-decoration:none; color:#009970; font-size:20px;">
    <i class="fa-solid fa-arrow-left"></i>
</a>
@endsection

@section('header_title', 'Edit Perjanjian')

@section('content')

{{-- Google Font --}}
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; }

    .paper {
        background: #fff;
        width: 100%;
        max-width: 900px;
        margin: auto;
        padding: 40px 50px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        font-size: 13px;
    }

    .input-box {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #dcdcdc;
        background: #f8f8f8;
        font-size: 13px;
        font-weight: 500;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        background: white;
        table-layout: fixed; 
        word-wrap: break-word; 
    }

    table th, table td {
        border: 1px solid #000;
        padding: 8px;
        font-size: 12px;
        text-align: center;
        vertical-align: top; 
    }

    table th:first-child, table td:first-child {
        width: 40px; 
    }

    table td input {
        width: 95%;           
        border: none;
        text-align: left;     
        background: transparent;
        font-size: 12px;
        box-sizing: border-box;
        padding: 4px;
    }

    table td textarea {
        width: 95%;           
        min-height: 35px;    
        resize: none;         
        padding: 4px;
        box-sizing: border-box;
        text-align: left;     
        vertical-align: top;  
        font-size: 12px;
        overflow: hidden;
        font-family: inherit;
    }

    table td input[type="number"] {
        width: 95%;
        border: none;
        text-align: center;
        background: transparent;
        font-size: 12px;
        box-sizing: border-box;
        padding: 4px;
    }

    .table-action-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        padding: 4px 8px;
        color: #009970;
    }

    .table-action-btn:hover {
        opacity: 0.7;
    }

    .delete-btn {
        color: #dc3545;
    }

    .add-btn {
        color: #009970;
    }

    /* Sembunyikan kolom AKSI pada tabel lanjutan (tanpa menyentuh kolom TW IV) */
    #tabel2 thead tr:first-child th:last-child,
    #tabel2 tbody td:last-child,
    #tabel3 tfoot td:last-child {
        display: none;
    }

    .indicator-toggle-wrap {
        display: flex;
        gap: 4px;
        justify-content: center;
        margin-bottom: 4px;
    }

    .indicator-mini-btn {
        border: none;
        border-radius: 4px;
        width: 24px;
        height: 24px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        opacity: 0.35;
    }

    .indicator-mini-btn.active {
        opacity: 1;
    }

    .indicator-plus { background: #009970; }
    .indicator-minus { background: #dc3545; }

    table thead th {
        background: #efefef;
        font-weight: 600;
        word-wrap: break-word;
    }

    .save-btn {
        background:#009970; 
        color:white; 
        padding:11px 30px;
        border:none;
        border-radius:8px;
        font-size:14px;
        font-weight:600;
        cursor:pointer;
        box-shadow:0 3px 5px rgba(0,0,0,0.15);
    }

    .flex-row {
        display:flex;
        gap:40px;
        margin-top:20px;
    }

    .flex-col {
        flex:1;
        display:flex;
        flex-direction:column;
        gap:12px;
    }

    .dropdown-program {
        font-weight: 700 !important;
        color: #000;
        background: #f0f0f0;
    }

    .dropdown-kegiatan {
        font-style: italic !important;
        color: #333;
        padding-left: 15px;
    }

    .dropdown-subkegiatan {
        font-weight: normal !important;
        color: #555;
        padding-left: 30px;
    }

    .dropdown-custom {
        border-top: 2px solid #009970;
        color: #009970;
        font-weight: 500;
    }

    .subprogram-row td {
        background: #fafafa;
    }

    .subprogram-row td:nth-child(2) {
        padding-left: 25px;
        font-style: italic;
    }

    #tabelProgram tr.program-row td {
        font-weight: 600; 
    }

    #tabelProgram tr.program-row textarea,
    #tabelProgram tr.program-row input {
        font-weight: 600;
    }
    
    /* Style untuk kolom NO agar menampilkan hierarchical numbering dengan benar */
    #tabelProgram .no-col {
        width: 60px !important;
        min-width: 60px !important;
        text-align: center;
        font-family: 'Courier New', monospace;
        font-size: 14px;
        letter-spacing: 1px;
    }

    /* Style untuk dropdown select */
    select.program-nama-dropdown,
    select.kegiatan-nama-dropdown,
    select.subkegiatan-nama-dropdown {
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        cursor: pointer;
        font-size: 14px;
    }

    select.program-nama-dropdown {
        font-weight: 600 !important;
    }

    select.kegiatan-nama-dropdown {
        font-style: italic !important;
    }

    select.program-nama-dropdown:focus,
    select.kegiatan-nama-dropdown:focus,
    select.subkegiatan-nama-dropdown:focus {
        outline: none;
        border-color: #00B5A0;
        box-shadow: 0 0 0 2px rgba(0, 181, 160, 0.1);
    }
</style>

<script>
// DEFINE GLOBAL FUNCTIONS FIRST
console.log('=== Loading global functions for create ===');

// Data programs, kegiatans, subKegiatans dari backend (tidak lagi diperlukan untuk dropdown)
window.programsData = @json($programs);
window.kegiatansData = @json($kegiatans);
window.subKegiatansData = @json($subKegiatans);

console.log('Programs with relations:', window.programsData);

// Fungsi untuk store program ID yang dipilih di row
window.handleProgramChange = function(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const programId = selectedOption.getAttribute('data-program-id');
    
    // Store program ID di row untuk filtering nanti
    const programRow = selectElement.closest('tr.program-row');
    if (programRow) {
        programRow.dataset.programId = programId || '';
    }
    
    console.log('Program changed, ID stored:', programId);
    syncProgramToTabelD();
}

// Fungsi untuk filter kegiatan dropdown berdasarkan program
window.filterKegiatanByProgram = function(selectElement) {
    const row = selectElement.closest('tr');
    const programNo = row.dataset.parent; // nomor parent (program)
    
    if (!programNo) return;
    
    // Cari row program parent
    const tbody = row.closest('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    const programRow = allRows.find(r => 
        r.classList.contains('program-row') && 
        r.querySelector('.no-col')?.textContent?.trim() === programNo
    );
    
    if (!programRow || !programRow.dataset.programId) {
        console.log('Program not selected yet');
        return;
    }
    
    const programId = programRow.dataset.programId;
    
    // Filter kegiatan berdasarkan program_id
    const filteredKegiatans = window.kegiatansData.filter(k => k.program_id == programId);
    
    // Rebuild dropdown options
    selectElement.innerHTML = '<option value="">-- Pilih Kegiatan --</option>';
    filteredKegiatans.forEach(keg => {
        const option = document.createElement('option');
        option.value = keg.nama_kegiatan;
        option.setAttribute('data-kode', keg.kode_kegiatan);
        option.setAttribute('data-kegiatan-id', keg.id);
        option.textContent = keg.nama_kegiatan;
        selectElement.appendChild(option);
    });
}

// Fungsi untuk store kegiatan ID dan filter sub kegiatan
window.handleKegiatanChange = function(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const kegiatanId = selectedOption.getAttribute('data-kegiatan-id');
    
    // Store kegiatan ID di row
    const row = selectElement.closest('tr');
    if (row) {
        row.dataset.kegiatanId = kegiatanId || '';
    }
    
    console.log('Kegiatan changed, ID stored:', kegiatanId);
    calculateHierarchicalTotal();
    syncProgramToTabelD();
}

// Fungsi untuk filter sub kegiatan dropdown berdasarkan kegiatan
window.filterSubKegiatanByKegiatan = function(selectElement) {
    const row = selectElement.closest('tr');
    const kegiatanNo = row.dataset.parent; // nomor parent (kegiatan)
    
    if (!kegiatanNo) return;
    
    // Cari row kegiatan parent
    const tbody = row.closest('tbody');
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    const kegiatanRow = allRows.find(r => 
        r.dataset.level === 'kegiatan' && 
        r.querySelector('.no-col')?.textContent?.trim() === kegiatanNo
    );
    
    if (!kegiatanRow || !kegiatanRow.dataset.kegiatanId) {
        console.log('Kegiatan not selected yet');
        return;
    }
    
    const kegiatanId = kegiatanRow.dataset.kegiatanId;
    
    // Filter sub kegiatan berdasarkan kegiatan_id
    const filteredSubKegiatans = window.subKegiatansData.filter(s => s.kegiatan_id == kegiatanId);
    
    // Rebuild dropdown options
    selectElement.innerHTML = '<option value="">-- Pilih Sub Kegiatan --</option>';
    filteredSubKegiatans.forEach(sub => {
        const option = document.createElement('option');
        option.value = sub.nama_sub_kegiatan;
        option.setAttribute('data-kode', sub.kode_sub_kegiatan);
        option.textContent = sub.nama_sub_kegiatan;
        selectElement.appendChild(option);
    });
}

// Fungsi-fungsi handler sudah tidak diperlukan karena menggunakan textarea manual
// Tetapi ditinggalkan sebagai placeholder untuk kompatibilitas

window.addProgram = function() {
    console.log('addProgram called');
    const tbody = document.querySelector('#tabelProgram tbody');
    if (!tbody) {
        alert('Tabel tidak ditemukan!');
        return;
    }
    const currentPrograms = tbody.querySelectorAll('tr.program-row');
    const newProgramNo = currentPrograms.length + 1;

    const tr = document.createElement('tr');
    tr.classList.add('program-row');
    tr.dataset.program = newProgramNo;
    tr.dataset.level = 'program';

    // Generate dropdown options dari data program
    let programOptions = '<option value="">-- Pilih Program --</option>';
    window.programsData.forEach(prog => {
        programOptions += `<option value="${prog.nama_program}" data-kode="${prog.kode_program}" data-program-id="${prog.id}">${prog.nama_program}</option>`;
    });

    tr.innerHTML = `
        <td class="no-col">${newProgramNo}</td>
        <td>
            <select name="program_nama[]" class="program-nama-dropdown" style="width:95%; font-weight:600; padding:8px;" onchange="handleProgramChange(this)" required>
                ${programOptions}
            </select>
        </td>
        <td><input type="text" name="program_anggaran[]" value="0" readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari kegiatan" /></td>
        <td><textarea name="program_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;"></textarea></td>
        <td>
            <button type="button" class="table-action-btn add-btn" onclick="window.addSubRow('${newProgramNo}')" title="Tambah Kegiatan">➕</button>
            <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;

    tbody.appendChild(tr);
    if (typeof syncProgramToTabelD === 'function') syncProgramToTabelD(); 
}

window.addSubRow = function(parentNo) {
    console.log('addSubRow called for:', parentNo, typeof parentNo);
    const parentNoStr = parentNo.toString();
    const parentLevel = parentNoStr.split('.').length;
    if (parentLevel >= 3) {
        alert('Maksimal 3 level (Program > Kegiatan > Sub Kegiatan)');
        return;
    }

    const tbody = document.querySelector('#tabelProgram tbody');
    if (!tbody) {
        console.error('tbody not found');
        return;
    }

    // Cari parent row berdasarkan nomor di kolom NO - cari di SEMUA row
    console.log('Searching for parent row:', parentNoStr);
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    console.log('Total rows in table:', allRows.length);
    
    const parentRow = allRows.find(row => {
        const rowNo = row.querySelector('.no-col')?.textContent?.trim();
        console.log('  Checking row:', rowNo, '(class:', row.className, ') vs target:', parentNoStr);
        return rowNo == parentNoStr;
    });

    if (!parentRow) {
        console.error('❌ Parent row not found for:', parentNoStr);
        console.log('All rows in table:');
        allRows.forEach((r, i) => {
            console.log(`  [${i}] NO: "${r.querySelector('.no-col')?.textContent?.trim()}" - Class: ${r.className}`);
        });
        alert('Baris parent tidak ditemukan! Nomor: ' + parentNoStr + '\nPeriksa console (F12) untuk detail.');
        return;
    }
    console.log('✓ Found parent row:', parentRow.querySelector('.no-col')?.textContent?.trim());

    const children = Array.from(tbody.querySelectorAll(`tr[data-parent="${parentNoStr}"]`));
    const newIndex = children.length + 1;
    const newNo = `${parentNoStr}.${newIndex}`;
    const dataLevel = parentLevel === 1 ? 'kegiatan' : 'subkegiatan';
    
    console.log('Creating new row:');
    console.log('  Parent NO:', parentNoStr);
    console.log('  Existing children:', children.length);
    console.log('  New index:', newIndex);
    console.log('  New NO will be:', newNo);
    console.log('  Data level:', dataLevel);

    const tr = document.createElement('tr');
    tr.classList.add('subprogram-row');
    tr.dataset.parent = parentNoStr;
    tr.dataset.level = dataLevel;

    // Generate dropdown options berdasarkan level (akan di-filter saat onfocus)
    let dropdownOptions = '';
    let onchangeHandler = '';
    let onfocusHandler = '';
    
    if (parentLevel === 1) {
        // Ini level kegiatan
        dropdownOptions = '<option value="">-- Pilih Kegiatan --</option>';
        onchangeHandler = 'handleKegiatanChange(this)';
        onfocusHandler = 'filterKegiatanByProgram(this)';
    } else {
        // Ini level sub-kegiatan
        dropdownOptions = '<option value="">-- Pilih Sub Kegiatan --</option>';
        onfocusHandler = 'filterSubKegiatanByKegiatan(this)';
    }

    const inputStyle = parentLevel === 1 ? 'font-style:italic;' : '';
    const paddingLeft = parentLevel === 1 ? '5px' : '5px';

    const ketCellHtml = parentLevel === 1
        ? `<textarea name="${dataLevel}_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;"></textarea>`
        : `<select name="${dataLevel}_ket[]" style="width:95%; padding:6px;" onchange="updateHierarchicalKet(); syncProgramToTabelD();">
                <option value="">-- Pilih --</option>
                <option value="APBD">APBD</option>
                <option value="BLUD">BLUD</option>
           </select>`;

    tr.innerHTML = `
        <td class="no-col">${newNo}</td>
        <td>
            <select name="${dataLevel}_nama[]" class="${dataLevel}-nama-dropdown" style="width:95%; padding:8px ${paddingLeft}; ${inputStyle}" 
                    ${onchangeHandler ? `onchange="${onchangeHandler}"` : ''}
                    ${onfocusHandler ? `onfocus="${onfocusHandler}"` : ''}
                    required>
                ${dropdownOptions}
            </select>
        </td>
        <td><input type="text" name="${dataLevel}_anggaran[]" value="0" ${parentLevel === 1 ? 'readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari sub kegiatan"' : 'style="text-align:right" oninput="formatRupiah(this); calculateHierarchicalTotal(); syncProgramToTabelD();"'}></td>
        <td>${ketCellHtml}</td>
        <td>
            ${parentLevel < 2 ? `<button type="button" class="table-action-btn add-btn" onclick="window.addSubRow('${newNo}')" title="Tambah Sub Kegiatan">➕</button>` : ''}
            <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;
    
    console.log('✓ tr.innerHTML has been set. Checking .no-col...');
    console.log('  Expected NO:', newNo);
    console.log('  Actual NO in DOM:', tr.querySelector('.no-col')?.textContent);
    console.log('  tr.innerHTML:', tr.innerHTML.substring(0, 100));

    // Insert tepat setelah parent row atau setelah semua child dari parent
    let insertAfter = parentRow;
    
    // Cari child terakhir dari parent ini (allRows sudah di-query di atas)
    const parentIndex = allRows.indexOf(parentRow);
    console.log('Parent index:', parentIndex, 'Total rows:', allRows.length);
    
    for (let i = parentIndex + 1; i < allRows.length; i++) {
        const currentRow = allRows[i];
        const currentNo = currentRow.querySelector('.no-col')?.textContent;
        
        if (currentNo && currentNo.startsWith(parentNoStr + '.')) {
            insertAfter = currentRow;
        } else {
            break; // Sudah keluar dari grup parent ini
        }
    }

    if (insertAfter) {
        insertAfter.after(tr);
        console.log('✓ Row inserted after:', insertAfter.querySelector('.no-col')?.textContent?.trim());
        console.log('✓ New row NO:', tr.querySelector('.no-col')?.textContent?.trim());
        console.log('✓ New row class:', tr.className);
        console.log('✓ New row data-parent:', tr.dataset.parent);
        
        // Sinkronisasi tabel keempat
        if (typeof syncProgramToTabelD === 'function') {
            syncProgramToTabelD();
        }
        
        // Check setelah 100ms apakah ada perubahan
        setTimeout(() => {
            console.log('⏱ After 100ms - New row NO:', tr.querySelector('.no-col')?.textContent?.trim());
        }, 100);
    } else {
        console.error('Insert position not found');
    }
}

window.deleteRow = function(btn, tableId = null) {
    console.log('deleteRow called', btn, tableId);
    const row = btn.closest('tr');
    if (!row) {
        alert('Baris tidak ditemukan!');
        return;
    }
    const tbody = row.closest('tbody');
    if (!tbody) {
        alert('Tabel tidak ditemukan!');
        return;
    }
    
    // Minimal 1 baris program harus ada - disabled to allow empty table
    /*
    if (row.classList.contains('program-row')) {
        const remainingPrograms = tbody.querySelectorAll('tr.program-row');
        if (remainingPrograms.length <= 1) {
            alert('Minimal harus ada 1 program!');
            return;
        }
    }
    */
    
    const rowNo = row.querySelector('.no-col')?.textContent;
    console.log('Deleting row:', rowNo);
    
    // Hapus semua child rows (kegiatan dan sub kegiatan)
    if (row.classList.contains('program-row')) {
        const programNo = row.dataset.program;
        // Hapus semua row yang nomor nya diawali dengan programNo.
        Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
            const no = tr.querySelector('.no-col')?.textContent;
            if (no && no.startsWith(programNo + '.')) {
                tr.remove();
            }
        });
    } else if (row.classList.contains('subprogram-row') && rowNo) {
        // Jika kegiatan (level 2), hapus semua sub kegiatan di bawahnya
        if (rowNo.split('.').length === 2) {
            Array.from(tbody.querySelectorAll('tr')).forEach(tr => {
                const no = tr.querySelector('.no-col')?.textContent;
                if (no && no.startsWith(rowNo + '.')) {
                    tr.remove();
                }
            });
        }
    }
    
    row.remove();
    
    // Update nomor urut di tabel program
    if ((tbody.closest('#tabelProgram')) || (tableId && tableId === 'tabelProgram')) {
        renumberAllRows();
    }
    
    if (window.calculateHierarchicalTotal) window.calculateHierarchicalTotal();
    if (window.syncProgramToTabelD) window.syncProgramToTabelD();
}

// Fungsi untuk renumber semua rows dengan benar
window.renumberAllRows = function() {
    const tbody = document.querySelector('#tabelProgram tbody');
    if (!tbody) return;
    
    console.log('=== RENUMBER START ===');
    
    // Renumber programs
    const programRows = Array.from(tbody.querySelectorAll('tr.program-row'));
    programRows.forEach((programRow, programIndex) => {
        const newProgramNo = programIndex + 1;
        const oldProgramNo = programRow.querySelector('.no-col')?.textContent?.trim();
        
        console.log(`Program ${programIndex}: old="${oldProgramNo}", new="${newProgramNo}"`);
        
        programRow.dataset.program = newProgramNo;
        programRow.querySelector('.no-col').textContent = newProgramNo;
        
        // Renumber kegiatan dalam program ini (HANYA yang data-parent === oldProgramNo)
        const kegiatanRows = Array.from(tbody.querySelectorAll(`tr.subprogram-row[data-parent="${oldProgramNo}"]`));
        console.log(`  Found ${kegiatanRows.length} kegiatan rows with parent="${oldProgramNo}"`);
        
        kegiatanRows.forEach((kegRow, kegIndex) => {
            const oldKegNo = kegRow.querySelector('.no-col')?.textContent?.trim();
            const newKegNo = `${newProgramNo}.${kegIndex + 1}`;
            
            console.log(`    Kegiatan ${kegIndex}: old="${oldKegNo}", new="${newKegNo}"`);
            
            kegRow.dataset.parent = newProgramNo;
            kegRow.querySelector('.no-col').textContent = newKegNo;
            
            // Renumber sub kegiatan dalam kegiatan ini (HANYA yang data-parent === oldKegNo)
            const subKegRows = Array.from(tbody.querySelectorAll(`tr.subprogram-row[data-parent="${oldKegNo}"]`));
            console.log(`      Found ${subKegRows.length} sub kegiatan rows with parent="${oldKegNo}"`);
            
            subKegRows.forEach((subRow, subIndex) => {
                const newSubNo = `${newKegNo}.${subIndex + 1}`;
                console.log(`        Sub ${subIndex}: new="${newSubNo}"`);
                
                subRow.dataset.parent = newKegNo;
                subRow.querySelector('.no-col').textContent = newSubNo;
            });
        });
    });
    
    console.log('=== RENUMBER END ===');
}

console.log('=== Global functions loaded ===');
</script>

<div class="paper">
        <!-- FORM START -->
        <form action="{{ route('perjanjian.update', $perjanjian->id) }}" method="POST" id="perjanjianForm">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="jenis" value="{{ $perjanjian->jenis }}">

    {{-- ERROR MESSAGES --}}
    @if(session('error'))
        <div style="background: #fee; border: 1px solid #fcc; color: #c00; padding: 15px; margin-bottom: 20px; border-radius: 6px;">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div style="background: #fee; border: 1px solid #fcc; color: #c00; padding: 15px; margin-bottom: 20px; border-radius: 6px;">
            <strong>Validation Errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('images/logo_pemda.png') }}" style="width: 70px;">
        <h2 style="font-size:16px; font-weight:600; margin-top:10px;">
            PERJANJIAN KINERJA TAHUN <span id="headerTahun">{{ old('tahun', $perjanjian->tahun ?? '2025') }}</span> <br>
            WAKIL DIREKTUR PELAYANAN <br>
            UOBK RSUD BANGIL KABUPATEN PASURUAN
        </h2>
    </div>

    {{-- PILIH TAHUN --}}
    <div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 2px solid #00B5A0;">
        <label for="tahun" style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">
            <i class="fas fa-calendar-alt" style="color: #00B5A0;"></i> Pilih Tahun Perjanjian <span style="color: red;">*</span>
        </label>
        <select name="tahun" id="tahun" class="input-box" required style="background: white; border: 2px solid #00B5A0;">
            <option value="">-- Pilih Tahun --</option>
            @foreach($availableYears as $year)
                <option value="{{ $year }}" {{ (string) old('tahun', $perjanjian->tahun ?? '') === (string) $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>
        <small style="color: #666; display: block; margin-top: 5px;">
            <i class="fas fa-info-circle"></i> Tahun yang dipilih akan digunakan pada header perjanjian kinerja
        </small>
    </div>
    
    <script>
    // Update header tahun ketika dropdown tahun berubah
    document.getElementById('tahun').addEventListener('change', function() {
        const selectedYear = this.value;
        const headerTahun = document.getElementById('headerTahun');
        if (selectedYear) {
            headerTahun.textContent = selectedYear;
        } else {
            headerTahun.textContent = '{{ old('tahun', $perjanjian->tahun ?? '2025') }}'; // default
        }
    });
    </script>

    <p style="text-align:justify;">
        Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil,
        kami yang bertanda tangan dibawah ini :
    </p>

    {{-- PIHAK PERTAMA & PIHAK KEDUA --}}
    <div class="flex-row">
        {{-- PIHAK PERTAMA --}}
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak1_name"
                value="{{ old('pihak1_name', $perjanjian->pihak1_name ?? (auth()->user()->nama ?? '')) }}" readonly>

            <input type="text" class="input-box" name="pihak1_jabatan"
                value="{{ old('pihak1_jabatan', $perjanjian->pihak1_jabatan ?? (auth()->user()->jabatan ?? '')) }}" readonly>

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK PERTAMA</b>.
            </p>
        </div>

        {{-- PIHAK KEDUA --}}
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak2_name" id="pihak2_name" 
                value="{{ old('pihak2_name', $pihak2User->nama ?? $perjanjian->pihak2_name ?? '') }}" readonly tabindex="-1" style="pointer-events:none;background:#e9ecef;">

            <input type="text" class="input-box" name="pihak2_jabatan" id="pihak2_jabatan" 
                value="{{ old('pihak2_jabatan', $pihak2Jabatan ?? $perjanjian->pihak2_jabatan ?? 'Direktur') }}" readonly tabindex="-1" style="pointer-events:none;background:#e9ecef;">

            <input type="hidden" name="pihak2_pangkat" value="{{ old('pihak2_pangkat', $pihak2User->pangkat ?? $perjanjian->pihak2_pangkat ?? '') }}">
            <input type="hidden" name="pihak2_nip" value="{{ old('pihak2_nip', $pihak2User->nip ?? $perjanjian->pihak2_nip ?? '') }}">

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK KEDUA</b>.
            </p>
        </div>
    </div>

    {{-- LOCATION AND DATE --}}
    <div class="flex-row">
        <div class="flex-col">
            <input type="text" class="input-box" name="location" placeholder="Tempat" value="{{ old('location', $perjanjian->location ?? 'Pasuruan') }}">
        </div>
        <div class="flex-col">
            <input type="date" class="input-box" name="agreement_date" value="{{ old('agreement_date', !empty($perjanjian->agreement_date) ? date('Y-m-d', strtotime($perjanjian->agreement_date)) : date('Y-m-d')) }}">
        </div>
    </div>

    {{-- PENJELASAN --}}
    <p style="text-align:justify; margin-top:18px;"> Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. </p> 
    <p style="text-align:justify;"> Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi. </p> 
    
    {{-- =========================
    JABATAN, TUGAS & FUNGSI (SEBELUM TABEL)
    ========================= --}}
    <div style="
        margin: 25px 0;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #f9fafb;
    ">

        <div style="margin-bottom: 12px;">
            <label style="font-weight: 600;">Jabatan</label>
            <input
                type="text"
                name="jabatan_pelaksana"
                id="jabatan_pelaksana"
                value="{{ old('jabatan_pelaksana', $perjanjian->jabatan_pelaksana ?? (isset($jabatanData) && $jabatanData ? $jabatanData->nama_jabatan : '')) }}"
                readonly
                style="
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    background: #e9ecef;
                "
            >
        </div>

        <div style="margin-bottom: 12px;">
            <label style="font-weight: 600;">Tugas</label>
            @php
                $tugasValue = old('tugas_pelaksana', $perjanjian->tugas_pelaksana ?? (isset($jabatanData) && $jabatanData ? $jabatanData->tugas : ''));
                if ($tugasValue === null) {
                    $tugasValue = '';
                }
                if (is_array($tugasValue)) {
                    $tugasValue = implode("\n", array_values(array_filter($tugasValue, function ($item) {
                        return $item !== null && $item !== '';
                    })));
                } elseif (is_string($tugasValue)) {
                    $decodedTugas = json_decode($tugasValue, true);
                    if (is_array($decodedTugas)) {
                        $tugasValue = implode("\n", array_values(array_filter($decodedTugas, function ($item) {
                            return $item !== null && $item !== '';
                        })));
                    }
                }
            @endphp
            <textarea
                name="tugas_pelaksana"
                id="tugas_pelaksana"
                rows="3"
                readonly
                style="
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    background: #e9ecef;
                "
            >{{ isset($tugasValue) ? trim($tugasValue) : '' }}</textarea>
            {{-- Tidak tampilkan notifikasi jika tugas tidak ditemukan --}}
        </div>

        <div>
            <label style="font-weight: 600;">Fungsi</label>
            @php
                $fungsiValue = old('fungsi_pelaksana', $perjanjian->fungsi_pelaksana ?? (isset($jabatanData) && $jabatanData ? $jabatanData->fungsi : null));
                if (is_string($fungsiValue) && (strpos($fungsiValue, '[') === 0 || strpos($fungsiValue, '{') === 0)) {
                    $decodedFungsi = json_decode($fungsiValue, true);
                    if (is_array($decodedFungsi)) {
                        $fungsiValue = $decodedFungsi;
                    }
                }
                $visibleFungsi = $fungsiValue;
            @endphp
            <div id="fungsi_container" style="
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 6px;
                background: #e9ecef;
                min-height: 60px;
            ">
                @if($visibleFungsi)
                    @if(is_array($visibleFungsi))
                        <ol style="margin: 0; padding-left: 20px;">
                            @foreach($visibleFungsi as $fungsi)
                                <li style="margin-bottom: 4px;">{{ $fungsi }}</li>
                            @endforeach
                        </ol>
                    @else
                        {{ $visibleFungsi }}
                    @endif
                @endif
            </div>
            @php
                if (is_array($fungsiValue)) {
                    $fungsiValue = json_encode(array_values(array_filter($fungsiValue, function ($item) {
                        return $item !== null && $item !== '';
                    })));
                }
            @endphp
            <input type="hidden" name="fungsi_pelaksana" id="fungsi_pelaksana" 
                   value="{{ $fungsiValue }}">
        </div>
    </div>

{{-- TABEL A --}}
<table id="tabelA">
    <thead>
        <tr>
            <th>NO</th>
            <th>SASARAN</th>
            <th>INDIKATOR KINERJA</th>
            <th style="width: 70px;">SATUAN</th>
            <th style="width: 90px;">TARGET</th>
            <th style="width: 60px;">AKSI</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td><textarea name="a_sasaran[]" onkeyup="autoExpand(this)"></textarea></td>
            <td><textarea name="a_indikator[]" onkeyup="autoExpand(this)"></textarea></td>
            <td><textarea name="a_satuan[]" onkeyup="autoExpand(this)"></textarea></td>
            <td><textarea name="a_target[]" onkeyup="autoExpand(this)"></textarea></td>
            <td>
                <input type="hidden" name="a_indicator_type[]" value="positif">
                <div class="indicator-toggle-wrap">
                    <button type="button" class="indicator-mini-btn indicator-plus active" title="Indikator Positif" onclick="setIndicatorType(this, 'positif')">+</button>
                    <button type="button" class="indicator-mini-btn indicator-minus" title="Indikator Negatif" onclick="setIndicatorType(this, 'negatif')">-</button>
                </div>
                <button type="button" class= "table-action-btn delete-btn" onclick="deleteRowTabelA(this)" title="hapus">🗑</button>
                <button type="button" class="table-action-btn add-btn" onclick="addRow('tabelA')" title="Tambah">➕</button>
            </td>
        </tr>
    </tbody>
</table>

{{-- TABEL PROGRAM & ANGGARAN (DINAMIS) --}}
<div style="overflow-x:auto;">
<table id="tabelProgram"
    style="width:100%; min-width:900px; border-collapse:collapse; table-layout:fixed;">

    <thead>
        <tr style="background:#f2f2f2;">
            <th style="width:40px; border:1px solid #000;">NO</th>
            <th style="min-width:300px; border:1px solid #000;">PROGRAM</th>
            <th style="width:160px; border:1px solid #000;">ANGGARAN (Rp)</th>
            <th style="width:100px; border:1px solid #000;">KET</th>
            <th style="width:70px; border:1px solid #000;">AKSI</th>
        </tr>
    </thead>

    <tbody>
        <!-- Baris awal PROGRAM dengan dropdown -->
        <tr class="program-row" data-program="1" data-level="program">
            <td class="no-col">1</td>
            <td>
                <select name="program_nama[]" class="program-nama-dropdown" style="width:95%; font-weight:600; padding:8px;" onchange="handleProgramChange(this)" required>
                    <option value="">-- Pilih Program --</option>
                    @foreach($programs as $prog)
                        <option value="{{ $prog->nama_program }}" data-kode="{{ $prog->kode_program }}" data-program-id="{{ $prog->id }}">{{ $prog->nama_program }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="program_anggaran[]" value="0" readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari kegiatan" /></td>
            <td><textarea name="program_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;"></textarea></td>
            <td>
                <button type="button" class="table-action-btn add-btn" onclick="addSubRow('1')" title="Tambah Kegiatan">➕</button>
                <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
            </td>
        </tr>
    </tbody>

    <!-- Baris total -->
    <tfoot>
        <tr>
            <td colspan="2" style="text-align:right; font-weight:bold; border-top:1px solid #000;">TOTAL ANGGARAN:</td>
            <td id="totalAnggaran" style="font-weight:bold; border-top:1px solid #000;">0</td>
            <td colspan="2" style="border-top:1px solid #000;"></td>
        </tr>
    </tfoot>
</table>

<div style="margin-top:10px;">
    <button type="button" onclick="addProgram()">➕ Tambah Program Baru</button>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fungsi sudah didefinisikan di global scope

    window.updateProgramNumbers = function() {
        console.log('updateProgramNumbers called');
        const tbody = document.querySelector('#tabelProgram tbody');
        if (!tbody) return;
        const remainingPrograms = tbody.querySelectorAll('tr.program-row');
        remainingPrograms.forEach((tr, index) => {
            const newNo = index + 1;
            tr.dataset.program = newNo;
            tr.querySelector('.no-col').textContent = newNo;

            const subs = tbody.querySelectorAll(`tr.subprogram-row[data-parent="${tr.dataset.program}"]`);
            subs.forEach((sub, i) => {
                sub.dataset.parent = newNo;
                sub.querySelector('.no-col').textContent = `${newNo}.${i+1}`;
            });
        });
    }

    // Roll-up KET: sub-kegiatan -> kegiatan -> program
    window.updateHierarchicalKet = function() {
        const tbody = document.querySelector('#tabelProgram tbody');
        if (!tbody) return;

        const allRows = Array.from(tbody.querySelectorAll('tr'));

        function mergeKet(values) {
            const cleaned = values.filter(v => !!v && (v === 'APBD' || v === 'BLUD'));
            const unique = [...new Set(cleaned)];
            if (unique.length === 0) return '';
            if (unique.length === 1) return unique[0];
            return 'APBD/BLUD';
        }

        // Step 1: kegiatan dari sub-kegiatan
        allRows.forEach(row => {
            if (row.dataset.level !== 'kegiatan') return;
            const kegiatanNo = row.querySelector('.no-col')?.textContent?.trim();
            if (!kegiatanNo) return;

            const childSubRows = allRows.filter(r => r.dataset.parent === kegiatanNo && r.dataset.level === 'subkegiatan');
            const ketValues = childSubRows.map(sub => sub.querySelector('select[name="subkegiatan_ket[]"]')?.value || '');
            const merged = mergeKet(ketValues);

            const kegiatanKetInput = row.querySelector('textarea[name="kegiatan_ket[]"]');
            if (kegiatanKetInput) {
                kegiatanKetInput.value = merged;
                autoExpand(kegiatanKetInput);
            }
        });

        // Step 2: program dari kegiatan
        allRows.forEach(row => {
            if (!row.classList.contains('program-row')) return;
            const programNo = row.querySelector('.no-col')?.textContent?.trim();
            if (!programNo) return;

            const childKegiatanRows = allRows.filter(r => r.dataset.parent === programNo && r.dataset.level === 'kegiatan');
            const ketValues = childKegiatanRows.map(keg => keg.querySelector('textarea[name="kegiatan_ket[]"]')?.value || '');
            const merged = mergeKet(ketValues);

            const programKetInput = row.querySelector('textarea[name="program_ket[]"]');
            if (programKetInput) {
                programKetInput.value = merged;
                autoExpand(programKetInput);
            }
        });
    }

    // Fungsi hitung total hanya dari program utama
    // Fungsi hierarchical auto-calculate
    window.calculateHierarchicalTotal = function() {
        console.log('=== calculateHierarchicalTotal called ===');
        const tbody = document.querySelector('#tabelProgram tbody');
        if (!tbody) {
            console.log('tbody not found!');
            return;
        }
        
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        console.log('Total rows:', allRows.length);
        
        // Step 1: Calculate semua SUB KEGIATAN -> KEGIATAN (bottom-up)
        allRows.forEach(row => {
            if (row.dataset.level === 'kegiatan') {
                const kegiatanNo = row.querySelector('.no-col')?.textContent?.trim();
                if (!kegiatanNo) return;
                
                console.log('Processing kegiatan:', kegiatanNo);
                
                // Cari semua sub kegiatan di bawah kegiatan ini
                const subKegiatans = allRows.filter(r => 
                    r.dataset.parent === kegiatanNo && r.dataset.level === 'subkegiatan'
                );
                
                console.log('  Found', subKegiatans.length, 'sub-kegiatan');
                
                let kegiatanTotal = 0;
                subKegiatans.forEach(sub => {
                    const input = sub.querySelector('input[name="subkegiatan_anggaran[]"]');
                    if (input) {
                        const val = input.value.replace(/[^\d]/g, '');
                        const numVal = parseInt(val) || 0;
                        console.log('    Sub-kegiatan value:', input.value, '-> parsed:', numVal);
                        kegiatanTotal += numVal;
                    }
                });
                
                console.log('  Kegiatan total:', kegiatanTotal);
                
                // Update kegiatan anggaran (readonly field)
                const kegiatanInput = row.querySelector('input[name="kegiatan_anggaran[]"]');
                if (kegiatanInput) {
                    kegiatanInput.value = kegiatanTotal.toLocaleString('id-ID');
                    console.log('  Updated kegiatan input to:', kegiatanInput.value);
                }
            }
        });
        
        // Step 2: Calculate semua KEGIATAN -> PROGRAM
        let grandTotal = 0;
        allRows.forEach(row => {
            if (row.classList.contains('program-row')) {
                const programNo = row.querySelector('.no-col')?.textContent?.trim();
                if (!programNo) return;
                
                console.log('Processing program:', programNo);
                
                // Cari semua kegiatan di bawah program ini
                const kegiatans = allRows.filter(r => 
                    r.dataset.parent === programNo && r.dataset.level === 'kegiatan'
                );
                
                console.log('  Found', kegiatans.length, 'kegiatan');
                
                let programTotal = 0;
                kegiatans.forEach(keg => {
                    const input = keg.querySelector('input[name="kegiatan_anggaran[]"]');
                    if (input) {
                        const val = input.value.replace(/[^\d]/g, '');
                        const numVal = parseInt(val) || 0;
                        console.log('    Kegiatan value:', input.value, '-> parsed:', numVal);
                        programTotal += numVal;
                    }
                });
                
                console.log('  Program total:', programTotal);
                
                // Update program anggaran (readonly field)
                const programInput = row.querySelector('input[name="program_anggaran[]"]');
                if (programInput) {
                    programInput.value = programTotal.toLocaleString('id-ID');
                    console.log('  Updated program input to:', programInput.value);
                }
                
                grandTotal += programTotal;
            }
        });
        
        console.log('Grand total:', grandTotal);
        
        // Step 3: Update grand total
        const totalEl = document.getElementById('totalAnggaran');
        if (totalEl) {
            totalEl.textContent = grandTotal.toLocaleString('id-ID');
            console.log('Updated totalAnggaran to:', totalEl.textContent);
        } else {
            console.log('totalAnggaran element not found!');
        }

        // Selalu sinkronkan KET otomatis setelah perhitungan anggaran
        window.updateHierarchicalKet();
    }
    
    // Backward compatibility
    window.calculateTotal = window.calculateHierarchicalTotal;

    // Validasi input hanya angka
    window.validateNumericInput = function(input) {
        // Hapus semua karakter non-digit
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    // Panggil sekali untuk baris awal
    calculateHierarchicalTotal();
    updateHierarchicalKet();
    });

    function formatRupiah(input) {
        let value = input.value.replace(/\D/g, ''); // hapus semua selain angka
        if(value) {
            // format menjadi ribuan
            input.value = parseInt(value).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    }
    </script>

{{-- TABEL C --}}
<table id="tabel2" style="table-layout: fixed;">
    <thead>
        <tr>
            <th rowspan="2" style="width: 25%;">SASARAN</th>
            <th rowspan="2" style="width: 20%;">Indikator Kinerja</th>
            <th rowspan="2" style="width: 12%;">Target</th>
            <th colspan="4" style="width: 40%;">Target Triwulan</th>
            <th rowspan="2" style="width: 60px;">AKSI</th>
        </tr>
        <tr>
            <th style="width: 10%;">I</th>
            <th style="width: 10%;">II</th>
            <th style="width: 10%;">III</th>
            <th style="width: 10%;">IV</th>
        </tr>
    </thead>
    <tbody>
        {{-- Baris akan diisi otomatis dari Tabel A via JavaScript --}}
    </tbody>
</table>

{{-- TABEL D: HIERARCHICAL BUDGET (PROGRAM) --}}

{{-- Info Box --}}
<div style="background:linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding:15px 20px; border-radius:8px; margin:20px 0; border-left:4px solid #00B5A0; box-shadow:0 2px 8px rgba(0,181,160,0.1);">
    <div style="display:flex; align-items:center; gap:12px;">
        <i class="fas fa-info-circle" style="font-size:24px; color:#00B5A0;"></i>
        <div style="flex:1;">
            <strong style="color:#00B5A0; font-size:14px;">Panduan Pengisian Target Triwulan:</strong>
            <ul style="margin:8px 0 0 20px; font-size:12px; color:#555; line-height:1.6;">
                <li>Kolom <strong>Target Triwulan</strong> tidak dapat diisi manual</li>
                <li>Target triwulan pada sub-kegiatan diatur otomatis sesuai konfigurasi target yang sudah diinput</li>
                <li>Target <strong>Kegiatan</strong> dan <strong>Program</strong> akan otomatis menjumlah dari sub-kegiatan di bawahnya</li>
            </ul>
        </div>
    </div>
</div>

<div style="overflow-x: auto;">
    <table id="tabel3" style="width:100%; table-layout:fixed; border-collapse:collapse;">
        <thead>
            <tr>
                <th rowspan="2" style="width:40px; border:1px solid #000;">NO</th>
                <th rowspan="2" style="width:25%; border:1px solid #000;">PROGRAM</th>
                <th rowspan="2" style="width:20%; border:1px solid #000;">ANGGARAN (Rp)</th>
                <th colspan="4" style="width:40%; border:1px solid #000;">TARGET TRIWULAN</th>
                <th rowspan="2" style="width:60px; border:1px solid #000;">AKSI</th>
            </tr>
            <tr>
                <th style="width:10%; border:1px solid #000;">I</th>
                <th style="width:10%; border:1px solid #000;">II</th>
                <th style="width:10%; border:1px solid #000;">III</th>
                <th style="width:10%; border:1px solid #000;">IV</th>
            </tr>
        </thead>
        <tbody id="hierarchical-budget-tbody">
            <!-- Row will be dynamically added via addProgram() -->
        </tbody>
        <tfoot>
        <tr style="background:#f2f2f2; font-weight:bold;">
            <td colspan="2" style="border:1px solid #000; text-align:right;">TOTAL</td>
            <td id="totalAnggaranD" style="border:1px solid #000; text-align:right;">0</td>
            <td id="totalTW1" style="border:1px solid #000; text-align:right;">0</td>
            <td id="totalTW2" style="border:1px solid #000; text-align:right;">0</td>
            <td id="totalTW3" style="border:1px solid #000; text-align:right;">0</td>
            <td id="totalTW4" style="border:1px solid #000; text-align:right;">0</td>
            <td style="border:1px solid #000;"></td>
        </tr>
    </tfoot>
    </table>
</div>
<script>
function syncProgramToTabelD() {
    console.log('=== syncProgramToTabelD called ===');
    const tbodyD = document.getElementById('hierarchical-budget-tbody');

    // simpan TW lama
    const existingTW = {};
    tbodyD.querySelectorAll('tr').forEach(tr => {
        if (!tr.dataset.key) return;
        existingTW[tr.dataset.key] = {
            tw1: tr.querySelector('.tw1')?.value || '',
            tw2: tr.querySelector('.tw2')?.value || '',
            tw3: tr.querySelector('.tw3')?.value || '',
            tw4: tr.querySelector('.tw4')?.value || '',
        };
    });

    tbodyD.innerHTML = '';

    const tbodyProgram = document.querySelector('#tabelProgram tbody');
    const rows = tbodyProgram.querySelectorAll('tr');
    
    console.log(`Found ${rows.length} rows in tabelProgram`);

    let totalAnggaran = 0;
    let totalTW1 = 0, totalTW2 = 0, totalTW3 = 0, totalTW4 = 0;

    rows.forEach((row, idx) => {
        const isProgram = row.classList.contains('program-row');
        const isSub = row.classList.contains('subprogram-row');
        if (!isProgram && !isSub) return;

        const no = row.querySelector('.no-col')?.textContent || '';
        console.log(`Row ${idx}: no="${no}", isProgram=${isProgram}, isSub=${isSub}`);
        
        // Get nama dari select dropdown (ambil text option yang dipilih, bukan value/ID)
        let nama = '';
        if (isProgram) {
            const select = row.querySelector('.program-nama-dropdown');
            nama = select && select.selectedIndex >= 0 ? select.options[select.selectedIndex].text : '';
        } else {
            // Sub-program (kegiatan or sub kegiatan)
            const dataLevel = row.dataset.level;
            const select = row.querySelector(`.${dataLevel}-nama-dropdown`);
            nama = select && select.selectedIndex >= 0 ? select.options[select.selectedIndex].text : '';
        }
        
        console.log(`  nama="${nama}", level=${row.dataset.level}`);

        const anggaranInput = row.querySelector('input[type="text"]');
        const anggaranRaw = anggaranInput
            ? anggaranInput.value.replace(/[^\d]/g,'')
            : '0';
        const anggaran = parseInt(anggaranRaw) || 0;

        if (isProgram) {
            totalAnggaran += anggaran;
        }

        const key = `${isProgram ? 'program' : 'sub'}-${no}`;
        const tw = existingTW[key] || {};

        const tr = document.createElement('tr');
        tr.dataset.key = key;

        tr.innerHTML = `
            <td style="border:1px solid #000; text-align:center;">${no}</td>
            <td style="border:1px solid #000; padding-left:${isSub ? '25px' : '5px'};">
                ${nama}
            </td>
            <td style="border:1px solid #000; text-align:right;">
                ${anggaran.toLocaleString('id-ID')}
            </td>

            <td style="border:1px solid #000;">
                <input class="tw1" type="text" value="${tw.tw1 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${row.dataset.level === 'subkegiatan' ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw2" type="text" value="${tw.tw2 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${row.dataset.level === 'subkegiatan' ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw3" type="text" value="${tw.tw3 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${row.dataset.level === 'subkegiatan' ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw4" type="text" value="${tw.tw4 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${row.dataset.level === 'subkegiatan' ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000; text-align:center;">
                ${row.dataset.level === 'subkegiatan' ? 
                    `<button type="button" onclick="openTargetModal('${no}', '${nama}', ${anggaran}, '${key}')" 
                            style="background:#00B5A0; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer; font-size:11px;"
                            title="Atur Target per Bulan">
                        <i class="fas fa-calendar-alt"></i> Atur
                    </button>` 
                    : '-'}
            </td>
        `;

        tbodyD.appendChild(tr);
    });
    
    console.log(`=== syncProgramToTabelD complete, total rows added: ${tbodyD.children.length}`);

    document.getElementById('totalAnggaranD').textContent =
        totalAnggaran.toLocaleString('id-ID');

    hitungHierarchicalTW(); 
}

// 🔹 HITUNG HIERARCHICAL TOTAL TRIWULAN (Sub-Kegiatan -> Kegiatan -> Program)
function hitungHierarchicalTW() {
    console.log('=== hitungHierarchicalTW called ===');
    const tbody = document.getElementById('hierarchical-budget-tbody');
    if (!tbody) return;
    
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    
    const parseRupiah = (val) => {
        if (!val) return 0;
        // Remove dots (thousands separator), replace comma with dot (decimal separator), then remove non-digits
        const cleanStr = val.toString().replace(/\./g, '').replace(/,/g, '.').replace(/[^\d.-]/g, '');
        return parseFloat(cleanStr) || 0;
    };
    
    // Step 1: Sum sub-kegiatan -> kegiatan
    allRows.forEach(row => {
        const key = row.dataset.key;
        if (!key || !key.startsWith('sub-')) return;
        
        const no = key.replace('sub-', '');
        const parts = no.split('.');
        
        // Cari parent kegiatan (level 2, misal: 1.1)
        if (parts.length === 3) {
            const kegiatanNo = parts[0] + '.' + parts[1];
            const kegiatanKey = 'sub-' + kegiatanNo;
            
            // Cari semua sub-kegiatan dengan parent yang sama
            const siblings = allRows.filter(r => {
                const siblingNo = r.dataset.key?.replace('sub-', '');
                return siblingNo && siblingNo.startsWith(kegiatanNo + '.');
            });
            
            // Update kegiatan dengan sum dari semua sub-kegiatan
            const kegiatanRow = allRows.find(r => r.dataset.key === kegiatanKey);
            if (kegiatanRow && siblings.length > 0) {
                let tw1Sum = 0, tw2Sum = 0, tw3Sum = 0, tw4Sum = 0;
                
                siblings.forEach(sub => {
                    tw1Sum += parseRupiah(sub.querySelector('.tw1')?.value);
                    tw2Sum += parseRupiah(sub.querySelector('.tw2')?.value);
                    tw3Sum += parseRupiah(sub.querySelector('.tw3')?.value);
                    tw4Sum += parseRupiah(sub.querySelector('.tw4')?.value);
                });

                kegiatanRow.querySelector('.tw1').value = Math.round(tw1Sum).toLocaleString('id-ID');
                kegiatanRow.querySelector('.tw2').value = Math.round(tw2Sum).toLocaleString('id-ID');
                kegiatanRow.querySelector('.tw3').value = Math.round(tw3Sum).toLocaleString('id-ID');
                kegiatanRow.querySelector('.tw4').value = Math.round(tw4Sum).toLocaleString('id-ID');
            }
        }
    });
    
    // Step 2: Sum kegiatan -> program
    allRows.forEach(row => {
        const key = row.dataset.key;
        if (!key || !key.startsWith('sub-')) return;
        
        const no = key.replace('sub-', '');
        const parts = no.split('.');
        
        // Level kegiatan (misal: 1.1)
        if (parts.length === 2) {
            const programNo = parts[0];
            const programKey = 'program-' + programNo;
            
            // Cari semua kegiatan dengan parent program yang sama
            const kegiatans = allRows.filter(r => {
                const kegNo = r.dataset.key?.replace('sub-', '');
                return kegNo && kegNo.split('.').length === 2 && kegNo.startsWith(programNo + '.');
            });
            
            // Update program dengan sum dari semua kegiatan
            const programRow = allRows.find(r => r.dataset.key === programKey);
            if (programRow && kegiatans.length > 0) {
                let tw1Sum = 0, tw2Sum = 0, tw3Sum = 0, tw4Sum = 0;
                
                kegiatans.forEach(keg => {
                    tw1Sum += parseRupiah(keg.querySelector('.tw1')?.value);
                    tw2Sum += parseRupiah(keg.querySelector('.tw2')?.value);
                    tw3Sum += parseRupiah(keg.querySelector('.tw3')?.value);
                    tw4Sum += parseRupiah(keg.querySelector('.tw4')?.value);
                });

                programRow.querySelector('.tw1').value = Math.round(tw1Sum).toLocaleString('id-ID');
                programRow.querySelector('.tw2').value = Math.round(tw2Sum).toLocaleString('id-ID');
                programRow.querySelector('.tw3').value = Math.round(tw3Sum).toLocaleString('id-ID');
                programRow.querySelector('.tw4').value = Math.round(tw4Sum).toLocaleString('id-ID');
            }
        }
    });
    
    // Step 3: Sum all programs for grand total
    let t1 = 0, t2 = 0, t3 = 0, t4 = 0;
    allRows.forEach(tr => {
        if (!tr.dataset.key || !tr.dataset.key.startsWith('program-')) return;

        t1 += parseRupiah(tr.querySelector('.tw1')?.value);
        t2 += parseRupiah(tr.querySelector('.tw2')?.value);
        t3 += parseRupiah(tr.querySelector('.tw3')?.value);
        t4 += parseRupiah(tr.querySelector('.tw4')?.value);
    });

    document.getElementById('totalTW1').textContent = Math.round(t1).toLocaleString('id-ID');
    document.getElementById('totalTW2').textContent = Math.round(t2).toLocaleString('id-ID');
    document.getElementById('totalTW3').textContent = Math.round(t3).toLocaleString('id-ID');
    document.getElementById('totalTW4').textContent = Math.round(t4).toLocaleString('id-ID');
    
    console.log('Total TW:', { t1, t2, t3, t4 });
}

// Backward compatibility - keep old function name
function hitungTotalTW() {
    hitungHierarchicalTW();
}
</script>

<!-- Hidden inputs for form submission (will store hierarchical data) -->
<input type="hidden" id="hierarchical-budget-json" name="hierarchical_budget_json" value="[]">

    {{-- BUTTON --}}
    <div style="margin-top:25px; text-align:right; display:flex; gap:10px; justify-content:flex-end;">
    <button class="save-btn" type="submit" onclick="saveToSupabase(event)" style="background:#009970;">
        💾 SIMPAN
    </button>
</div>
</form>
    <script>
        window.pihak1Jabatan = @json(old('jabatan_pelaksana', $perjanjian->jabatan_pelaksana ?? auth()->user()->jabatan));
    </script>

    <script>
    (function () {
        const jabatanPelaksana = document.getElementById('jabatan_pelaksana');

        if (jabatanPelaksana && window.pihak1Jabatan && !jabatanPelaksana.value) {
            jabatanPelaksana.value = window.pihak1Jabatan;
            jabatanPelaksana.readOnly = true;
        }
    })();
    </script>
</div>

@include('perjanjian._modal_target_triwulan')

<script>
let currentModalData = {
    no: '',
    nama: '',
    pagu: 0,
    key: '',
    inputMode: 'nominal' // 'nominal' or 'prosentase'
};

function openTargetModal(no, nama, pagu, key) {
    currentModalData = { no, nama, pagu, key, inputMode: 'nominal' };
    
    document.getElementById('modalNo').textContent = no;
    document.getElementById('modalNama').textContent = nama;
    document.getElementById('modalPagu').textContent = 'Rp ' + pagu.toLocaleString('id-ID');
    
    // Load existing data if any (parse dari format rupiah)
    const row = document.querySelector(`tr[data-key="${key}"]`);
    if (row) {
        // Hapus format rupiah (titik pemisah ribuan) sebelum parsing
        const tw1 = parseInt(row.querySelector('.tw1')?.value.replace(/\./g, '').replace(/[^\d]/g, '') || 0);
        const tw2 = parseInt(row.querySelector('.tw2')?.value.replace(/\./g, '').replace(/[^\d]/g, '') || 0);
        const tw3 = parseInt(row.querySelector('.tw3')?.value.replace(/\./g, '').replace(/[^\d]/g, '') || 0);
        const tw4 = parseInt(row.querySelector('.tw4')?.value.replace(/\./g, '').replace(/[^\d]/g, '') || 0);
        
        // Distribute equally to months (simplified) and format
        if (tw1 > 0) {
            const val1 = Math.round(tw1/3);
            document.getElementById('bln1').value = val1.toLocaleString('id-ID');
            document.getElementById('bln2').value = val1.toLocaleString('id-ID');
            document.getElementById('bln3').value = val1.toLocaleString('id-ID');
        }
        if (tw2 > 0) {
            const val2 = Math.round(tw2/3);
            document.getElementById('bln4').value = val2.toLocaleString('id-ID');
            document.getElementById('bln5').value = val2.toLocaleString('id-ID');
            document.getElementById('bln6').value = val2.toLocaleString('id-ID');
        }
        if (tw3 > 0) {
            const val3 = Math.round(tw3/3);
            document.getElementById('bln7').value = val3.toLocaleString('id-ID');
            document.getElementById('bln8').value = val3.toLocaleString('id-ID');
            document.getElementById('bln9').value = val3.toLocaleString('id-ID');
        }
        if (tw4 > 0) {
            const val4 = Math.round(tw4/3);
            document.getElementById('bln10').value = val4.toLocaleString('id-ID');
            document.getElementById('bln11').value = val4.toLocaleString('id-ID');
            document.getElementById('bln12').value = val4.toLocaleString('id-ID');
        }
    }

    const initialMode = row?.dataset.inputMode === 'prosentase' ? 'prosentase' : 'nominal';
    
    // Open modal first
    document.getElementById('targetModal').style.display = 'block';
    
    // Initialize mode and calculate with a small delay to ensure DOM is ready
    setTimeout(() => {
        switchInputMode(initialMode);
        calculateModalTotal();
    }, 100);
}

function closeTargetModal() {
    document.getElementById('targetModal').style.display = 'none';
    // Reset all inputs
    for (let i = 1; i <= 12; i++) {
        document.getElementById('bln' + i).value = '';
    }
}

function switchInputMode(mode) {
    const previousMode = currentModalData.inputMode;
    currentModalData.inputMode = mode;
    
    const btnNominal = document.getElementById('btnNominal');
    const btnProsentase = document.getElementById('btnProsentase');
    const pagu = currentModalData.pagu;
    
    if (mode === 'nominal') {
        btnNominal.style.background = '#00B5A0';
        btnNominal.style.color = 'white';
        btnProsentase.style.background = 'white';
        btnProsentase.style.color = '#00B5A0';
    } else {
        btnNominal.style.background = 'white';
        btnNominal.style.color = '#00B5A0';
        btnProsentase.style.background = '#00B5A0';
        btnProsentase.style.color = 'white';
    }
    
    // Update all inputs format when switching mode
    for (let i = 1; i <= 12; i++) {
        const input = document.getElementById('bln' + i);
        if (!input) continue;
        
        const rawValue = input.value;
        
        if (mode === 'nominal') {
            input.placeholder = '0';
            if (rawValue) {
                if (previousMode === 'prosentase' && pagu > 0) {
                    let cleanValue = rawValue.replace(/,/g, '.').replace(/[^\d.]/g, '');
                    const parts = cleanValue.split('.');
                    if (parts.length > 2) cleanValue = parts[0] + '.' + parts.slice(1).join('');
                    let percentValue = parseFloat(cleanValue) || 0;
                    input.value = Math.round((percentValue / 100) * pagu).toLocaleString('id-ID');
                } else {
                    const numericValue = parseInt(rawValue.replace(/[^\d]/g, '')) || 0;
                    input.value = numericValue.toLocaleString('id-ID');
                }
            }
        } else {
            input.placeholder = '0%';
            if (rawValue) {
                if (previousMode === 'nominal' && pagu > 0) {
                    const numericValue = parseInt(rawValue.replace(/[^\d]/g, '')) || 0;
                    let percentValue = (numericValue / pagu) * 100;
                    input.value = (Math.round(percentValue * 100) / 100).toString();
                } else {
                    let cleanValue = rawValue.replace(/,/g, '.').replace(/[^\d.]/g, '');
                    const parts = cleanValue.split('.');
                    if (parts.length > 2) cleanValue = parts[0] + '.' + parts.slice(1).join('');
                    input.value = cleanValue;
                }
            }
        }
    }
    
    // Recalculate with new mode
    calculateModalTotal();
}

function formatModalInput(input) {
    const mode = currentModalData.inputMode;
    
    if (mode === 'nominal') {
        let value = input.value.replace(/[^\d]/g, '');
        // Format as rupiah for nominal mode
        input.value = value ? parseInt(value).toLocaleString('id-ID') : '';
    } else {
        // For percentage, allow numbers and decimal point
        let value = input.value.replace(/,/g, '.').replace(/[^\d.]/g, '');
        const parts = value.split('.');
        if (parts.length > 2) {
            value = parts[0] + '.' + parts.slice(1).join('');
        }
        input.value = value;
    }
    
    // Immediately calculate after formatting
    calculateModalTotal();
}

function calculateModalTotal() {
    const pagu = currentModalData.pagu;
    const mode = currentModalData.inputMode;
    
    console.log('calculateModalTotal called - Pagu:', pagu, 'Mode:', mode);
    
    if (!pagu || pagu <= 0) {
        console.error('Invalid pagu:', pagu);
        alert('Error: Pagu tidak valid!');
        return;
    }
    
    let totalNominal = 0;
    let tw1 = 0, tw2 = 0, tw3 = 0, tw4 = 0;
    
    // Loop through all 12 months
    for (let i = 1; i <= 12; i++) {
        const input = document.getElementById('bln' + i);
        if (!input) continue;
        
        let value = 0;
        if (mode === 'prosentase') {
            let cleanValue = input.value.replace(/,/g, '.').replace(/[^\d.]/g, '');
            const parts = cleanValue.split('.');
            if (parts.length > 2) {
                cleanValue = parts[0] + '.' + parts.slice(1).join('');
            }
            let percentValue = parseFloat(cleanValue) || 0;
            value = (percentValue / 100) * pagu;
        } else {
            const cleanValue = input.value.replace(/\./g, '').replace(/[^\d]/g, '');
            value = parseInt(cleanValue) || 0;
        }
        
        console.log('Bulan ' + i + ':', input.value, '→ value:', value);
        
        totalNominal += value;
        
        // Group by triwulan
        if (i >= 1 && i <= 3) tw1 += value;
        else if (i >= 4 && i <= 6) tw2 += value;
        else if (i >= 7 && i <= 9) tw3 += value;
        else if (i >= 10 && i <= 12) tw4 += value;
    }
    
    // Calculate percentage and remaining budget
    totalNominal = Math.round(totalNominal);
    const sisaPagu = pagu - totalNominal;
    let prosentase = pagu > 0 ? (totalNominal / pagu) * 100 : 0;
    // Fix floating point precision
    prosentase = Math.round(prosentase * 100) / 100;
    
    // Update total per triwulan
    const tw1Element = document.getElementById('totalTW1Modal');
    const tw2Element = document.getElementById('totalTW2Modal');
    const tw3Element = document.getElementById('totalTW3Modal');
    const tw4Element = document.getElementById('totalTW4Modal');
    
    if (tw1Element && tw2Element && tw3Element && tw4Element) {
        if (mode === 'prosentase') {
            tw1Element.textContent = ((tw1/pagu)*100).toFixed(1) + '%';
            tw2Element.textContent = ((tw2/pagu)*100).toFixed(1) + '%';
            tw3Element.textContent = ((tw3/pagu)*100).toFixed(1) + '%';
            tw4Element.textContent = ((tw4/pagu)*100).toFixed(1) + '%';
        } else {
            tw1Element.textContent = 'Rp ' + Math.round(tw1).toLocaleString('id-ID');
            tw2Element.textContent = 'Rp ' + Math.round(tw2).toLocaleString('id-ID');
            tw3Element.textContent = 'Rp ' + Math.round(tw3).toLocaleString('id-ID');
            tw4Element.textContent = 'Rp ' + Math.round(tw4).toLocaleString('id-ID');
        }
    } else {
        console.error('Total TW elements not found!');
    }
    
    // Update sisa pagu
    const sisaPaguElement = document.getElementById('sisaPagu');
    if (sisaPaguElement) {
        sisaPaguElement.textContent = 'Rp ' + Math.round(Math.max(0, sisaPagu)).toLocaleString('id-ID');
        
        // Update color based on remaining budget
        if (sisaPagu < 0) {
            sisaPaguElement.style.color = '#dc3545'; // Red if over budget
        } else if (sisaPagu === 0) {
            sisaPaguElement.style.color = '#28a745'; // Green if exactly 100%
        } else {
            sisaPaguElement.style.color = '#856404'; // Yellow/orange if under 100%
        }
    }
    
    // Update total distribution percentage
    const totalDistribusiElement = document.getElementById('totalDistribusi');
    if (totalDistribusiElement) {
        totalDistribusiElement.textContent = prosentase.toFixed(1) + '%';
    }
    
    // Update progress bar
    const progressBarElement = document.getElementById('progressBar');
    if (progressBarElement) {
        progressBarElement.style.width = Math.min(prosentase, 100) + '%';
        
        // Update progress bar color based on percentage
        if (prosentase > 100) {
            progressBarElement.style.background = 'linear-gradient(90deg, #dc3545 0%, #ff6b7a 100%)';
        } else if (prosentase === 100) {
            progressBarElement.style.background = 'linear-gradient(90deg, #28a745 0%, #48d597 100%)';
        } else {
            progressBarElement.style.background = 'linear-gradient(90deg, #00B5A0 0%, #00d4aa 100%)';
        }
    }
    
    // Show warnings
    const warningMessage = document.getElementById('warningMessage');
    const warningText = document.getElementById('warningText');
    const btnSimpan = document.getElementById('btnSimpanTarget');
    
    if (prosentase > 100) {
        warningMessage.style.display = 'block';
        warningMessage.style.background = '#f8d7da';
        warningMessage.style.color = '#721c24';
        warningMessage.style.borderLeftColor = '#f5c6cb';
        warningText.textContent = 'Total distribusi melebihi pagu anggaran! Harap kurangi nilai input.';
        btnSimpan.disabled = true;
        btnSimpan.style.opacity = '0.5';
        btnSimpan.style.cursor = 'not-allowed';
    } else if (prosentase < 100 && prosentase > 0) {
        warningMessage.style.display = 'block';
        warningMessage.style.background = '#fff3cd';
        warningMessage.style.color = '#856404';
        warningMessage.style.borderLeftColor = '#ffc107';
        warningText.textContent = 'Total distribusi belum mencapai 100%. Sisa: ' + (100 - prosentase).toFixed(1) + '%';
        btnSimpan.disabled = false;
        btnSimpan.style.opacity = '1';
        btnSimpan.style.cursor = 'pointer';
    } else if (prosentase === 100) {
        warningMessage.style.display = 'block';
        warningMessage.style.background = '#d4edda';
        warningMessage.style.color = '#155724';
        warningMessage.style.borderLeftColor = '#28a745';
        warningText.textContent = '✓ Distribusi anggaran sudah 100%. Sempurna!';
        btnSimpan.disabled = false;
        btnSimpan.style.opacity = '1';
        btnSimpan.style.cursor = 'pointer';
    } else {
        warningMessage.style.display = 'none';
        btnSimpan.disabled = false;
        btnSimpan.style.opacity = '1';
        btnSimpan.style.cursor = 'pointer';
    }
    
    console.log('Calculation complete - TW:', [tw1, tw2, tw3, tw4], 'Total:', totalNominal, 'Sisa:', sisaPagu, '%:', prosentase);
}

function saveTargetModal() {
    const pagu = currentModalData.pagu;
    const mode = currentModalData.inputMode;
    const key = currentModalData.key;
    
    let tw1 = 0, tw2 = 0, tw3 = 0, tw4 = 0;
    
    for (let i = 1; i <= 12; i++) {
        const input = document.getElementById('bln' + i);
        if (!input) continue;
        
        let value = 0;
        if (mode === 'prosentase') {
            let cleanValue = input.value.replace(/,/g, '.').replace(/[^\d.]/g, '');
            const parts = cleanValue.split('.');
            if (parts.length > 2) {
                cleanValue = parts[0] + '.' + parts.slice(1).join('');
            }
            let percentValue = parseFloat(cleanValue) || 0;
            value = (percentValue / 100) * pagu;
        } else {
            const cleanValue = input.value.replace(/\./g, '').replace(/[^\d]/g, '');
            value = parseInt(cleanValue) || 0;
        }
        
        // Group by triwulan
        if (i >= 1 && i <= 3) tw1 += value;
        else if (i >= 4 && i <= 6) tw2 += value;
        else if (i >= 7 && i <= 9) tw3 += value;
        else if (i >= 10 && i <= 12) tw4 += value;
    }
    
    console.log('Saving target:', { tw1, tw2, tw3, tw4, total: tw1+tw2+tw3+tw4, pagu });
    
    // Update tabel TARGET TRIWULAN - save dengan format rupiah
    const row = document.querySelector(`tr[data-key="${key}"]`);
    if (row) {
        const tw1Input = row.querySelector('.tw1');
        const tw2Input = row.querySelector('.tw2');
        const tw3Input = row.querySelector('.tw3');
        const tw4Input = row.querySelector('.tw4');
        
        // Simpan dengan format rupiah
        if (tw1Input) tw1Input.value = Math.round(tw1).toLocaleString('id-ID');
        if (tw2Input) tw2Input.value = Math.round(tw2).toLocaleString('id-ID');
        if (tw3Input) tw3Input.value = Math.round(tw3).toLocaleString('id-ID');
        if (tw4Input) tw4Input.value = Math.round(tw4).toLocaleString('id-ID');
        row.dataset.inputMode = mode;
        
        // Hitung hierarchical total (sub -> kegiatan -> program)
        hitungHierarchicalTW();
    }
    
    closeTargetModal();
    
    // Show success message
    const successMsg = document.createElement('div');
    successMsg.style.cssText = 'position:fixed; top:20px; right:20px; background:#28a745; color:white; padding:15px 25px; border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,0.2); z-index:10000; font-weight:600;';
    successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Target triwulan berhasil disimpan!';
    document.body.appendChild(successMsg);
    
    setTimeout(() => successMsg.remove(), 3000);
}
</script>

<!-- POPUP GAGAL SIMPAN (jika TTD kosong) -->
<div id="popupTTDKosong" 
     style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(0,0,0,0.2); backdrop-filter:blur(3px); 
     justify-content:center; align-items:center; z-index:9999;">
    
    <div style="background:#fff; padding:25px 30px; border-radius:12px;
        width:330px; box-shadow:0 4px 10px rgba(0,0,0,0.2); text-align:center;">
        
        <p style="font-size:15px; font-weight:600; margin-bottom:8px;">
            Gagal menyimpan data dikarenakan<br>TTD kosong
        </p>

        <p style="font-size:12px; color:#555;">
            *atur TTD anda di menu profil*
        </p>

        <button onclick="closePopupTTD()" 
            style="margin-top:15px; background:#009970; color:white; padding:8px 25px;
            border:none; border-radius:8px; font-size:13px; cursor:pointer;">
            OK
        </button>
    </div>
</div>

{{-- AUTO ADD & VALIDASI TTD --}}
<script>


    // =========================
    // AUTO EXPAND TEXTAREA
    // =========================
    function autoExpand(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 150) + 'px';
    }

    // Initialize auto-expand for all textareas
    document.querySelectorAll('table textarea').forEach(ta => {
        ta.addEventListener('input', function() {
            autoExpand(this);
        });
        autoExpand(ta);
    });

    // Initialize budget calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing budget calculation...');
    
    // Attach listeners to all budget inputs
    document.querySelectorAll(".budget-input").forEach(inp => {
        inp.addEventListener('input', calculateTotal);
    });
    
    // Calculate initial total
    calculateTotal();
    
    // Attach event listeners untuk sync Tabel A ke Tabel C
    const tabelA = document.getElementById('tabelA');
    if (tabelA) {
        tabelA.addEventListener('input', function(e) {
            if (e.target.tagName === 'TEXTAREA') {
                syncTabelAToC();
            }
        });
        
        // Trigger sync saat page load
        syncTabelAToC();
    }
    
    // Trigger sync tabel program ke tabel D (tabel keempat) saat page load
    if (typeof syncProgramToTabelD === 'function') {
        syncProgramToTabelD();
    }
});

// =========================
// SYNC TABEL A KE TABEL C
// =========================
function syncTabelAToC() {
    const tabelA = document.getElementById('tabelA');
    const tabel2 = document.getElementById('tabel2');
    
    if (!tabelA || !tabel2) return;
    
    const tbodyA = tabelA.querySelector('tbody');
    const tbody2 = tabel2.querySelector('tbody');
    
    if (!tbodyA || !tbody2) return;
    
    // Simpan data Target Triwulan yang sudah ada
    const existingData = [];
    const existingRows = tbody2.querySelectorAll('tr');
    existingRows.forEach(row => {
        const tw1Input = row.querySelector('textarea[name="c_tw1[]"]');
        const tw2Input = row.querySelector('textarea[name="c_tw2[]"]');
        const tw3Input = row.querySelector('textarea[name="c_tw3[]"]');
        const tw4Input = row.querySelector('textarea[name="c_tw4[]"]');
        const indicatorTypeInput = row.querySelector('input[name="c_indicator_type[]"]');
        
        existingData.push({
            indicatorType: indicatorTypeInput ? indicatorTypeInput.value : 'positif',
            tw1: tw1Input ? tw1Input.value : '',
            tw2: tw2Input ? tw2Input.value : '',
            tw3: tw3Input ? tw3Input.value : '',
            tw4: tw4Input ? tw4Input.value : ''
        });
    });
    
    // Clear tabel2 tbody
    tbody2.innerHTML = '';
    
    // Copy semua baris dari tabel A ke tabel 2 (tabel C)
    const rowsA = tbodyA.querySelectorAll('tr');
    
    if (rowsA.length === 0) return;
    
    rowsA.forEach((rowA, index) => {
        const sasaranInput = rowA.querySelector('textarea[name="a_sasaran[]"]');
        const indikatorInput = rowA.querySelector('textarea[name="a_indikator[]"]');
        const indicatorTypeInput = rowA.querySelector('input[name="a_indicator_type[]"]');
        const targetInput = rowA.querySelector('textarea[name="a_target[]"]');
        
        const sasaran = sasaranInput ? sasaranInput.value : '';
        const indikator = indikatorInput ? indikatorInput.value : '';
        const indicatorType = indicatorTypeInput ? indicatorTypeInput.value : 'positif';
        const target = targetInput ? targetInput.value : '';
        
        // Ambil data Target Triwulan yang sudah ada (jika ada)
        const tw1 = existingData[index]?.tw1 || '';
        const tw2 = existingData[index]?.tw2 || '';
        const tw3 = existingData[index]?.tw3 || '';
        const tw4 = existingData[index]?.tw4 || '';
        // Prioritaskan pilihan terbaru dari Tabel A agar perubahan positif/negatif tidak tertimpa nilai lama.
        const existingIndicatorType = indicatorType || existingData[index]?.indicatorType || 'positif';
        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td style="width: 25%;"><textarea name="c_sasaran[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">${sasaran}</textarea></td>
            <td style="width: 20%;"><textarea name="c_indikator[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">${indikator}</textarea></td>
            <td style="width: 12%;">
                <input type="hidden" name="c_indicator_type[]" value="${existingIndicatorType}">
                <textarea name="c_target[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">${target}</textarea>
            </td>
            <td style="width: 10%;"><textarea name="c_tw1[]" onkeyup="autoExpand(this)">${tw1}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw2[]" onkeyup="autoExpand(this)">${tw2}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw3[]" onkeyup="autoExpand(this)">${tw3}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw4[]" onkeyup="autoExpand(this)">${tw4}</textarea></td>
            <td style="width: 60px;">
                <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this, 'tabel2')" title="Hapus" style="visibility: hidden;">🗑</button>
                <button type="button" class="table-action-btn add-btn" onclick="addRow('tabel2')" title="Tambah" style="visibility: hidden;">➕</button>
            </td>
        `;
        tbody2.appendChild(newRow);
        
        // Auto expand all textareas
        newRow.querySelectorAll('textarea').forEach(ta => {
            if (ta) autoExpand(ta);
        });
    });
}

// =========================
// TAMBAH BARIS OTOMATIS
// =========================
function addRow(tableId) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    const lastRow = tbody.rows[tbody.rows.length - 1];
    
    // Simpan nilai dropdown sebelum clone (untuk tabel3/anggaran)
    let savedDropdownValue = null;
    if (tableId === 'tabel3') {
        const lastDropdown = lastRow.querySelector('select');
        if (lastDropdown) {
            savedDropdownValue = lastDropdown.value;
        }
    }
    
    const newRow = lastRow.cloneNode(true);
    
    // Reset values untuk input dan textarea, tapi pertahankan dropdown tertentu
    newRow.querySelectorAll("input, textarea").forEach(el => {
        el.value = "";
    });

    if (tableId !== 'tabel3') {
        newRow.querySelectorAll('select').forEach(sel => {
            sel.selectedIndex = 0;
            if (sel.name === 'a_indicator_type[]') {
                sel.value = 'positif';
            }
        });
    }

    if (tableId === 'tabelA') {
        const hiddenType = newRow.querySelector('input[name="a_indicator_type[]"]');
        if (hiddenType) hiddenType.value = 'positif';
        const plusBtn = newRow.querySelector('.indicator-plus');
        const minusBtn = newRow.querySelector('.indicator-minus');
        if (plusBtn) plusBtn.classList.add('active');
        if (minusBtn) minusBtn.classList.remove('active');
    }
    
    // Kembalikan nilai dropdown ke pilihan yang sama - dengan force set selected
    if (tableId === 'tabel3' && savedDropdownValue) {
        const newDropdown = newRow.querySelector('select');
        if (newDropdown) {
            // Set value
            newDropdown.value = savedDropdownValue;
            
            // Force set selected attribute pada option yang benar
            Array.from(newDropdown.options).forEach(option => {
                if (option.value === savedDropdownValue) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            });
            
            // Trigger change event jika ada listener
            const changeEvent = new Event('change', { bubbles: true });
            newDropdown.dispatchEvent(changeEvent);
        }
    }

    // Update NO otomatis (jika ada di cell pertama)
    if (tableId === 'tabel3' && !isNaN(parseInt(newRow.cells[0].innerText))) {
        newRow.cells[0].innerText = tbody.rows.length + 1;
    } else if (tableId !== 'tabel2' && !isNaN(parseInt(newRow.cells[0].innerText))) {
        newRow.cells[0].innerText = tbody.rows.length + 1;
    }

    tbody.appendChild(newRow);

    // Attach event listeners untuk textarea baru
    newRow.querySelectorAll("textarea").forEach(ta => {
        ta.addEventListener('input', function() {
            autoExpand(this);
        });
        autoExpand(ta);
    });

    // Attach event listeners untuk input number (anggaran) - REALTIME
    newRow.querySelectorAll(".budget-input").forEach(inp => {
        inp.addEventListener('input', calculateTotalBudget);
        inp.addEventListener('change', calculateTotalBudget);
    });
    
    // Recalculate total for budget table
    if (tableId === 'tabel3') {
        calculateTotalBudget();
    }
    
    // Sync tabel A ke C jika menambah baris di tabelA
    if (tableId === 'tabelA') {
        syncTabelAToC();
    }
}

function setIndicatorType(btn, type) {
    const row = btn.closest('tr');
    if (!row) return;
    const hiddenType = row.querySelector('input[name="a_indicator_type[]"]');
    if (hiddenType) hiddenType.value = type;

    const plusBtn = row.querySelector('.indicator-plus');
    const minusBtn = row.querySelector('.indicator-minus');
    if (plusBtn) plusBtn.classList.toggle('active', type === 'positif');
    if (minusBtn) minusBtn.classList.toggle('active', type === 'negatif');

    syncTabelAToC();
}

/* =========================
   HAPUS BARIS TABEL A
========================= */
function deleteRowTabelA(btn) {
    const row = btn.closest('tr');
    const tbody = row.closest('tbody');

    if (tbody.rows.length <= 1) {
        alert('Minimal 1 baris');
        return;
    }

    row.remove();

    // Update NO
    tbody.querySelectorAll('tr').forEach((tr, i) => {
        tr.cells[0].innerText = i + 1;
    });

    syncTabelAToC();
}

// =========================
// SYNC TABEL A KE TABEL 3 (HIERARCHICAL BUDGET)
// =========================
function syncTabelAToTabel3() {
    const tabelA = document.getElementById('tabelA');
    if (!tabelA) return;
    
    const tbodyA = tabelA.querySelector('tbody');
    const rowsA = tbodyA.querySelectorAll('tr');
    
    // Ambil struktur yang ada
    let structure = getHierarchicalBudgetStructure();
    
    // Jika struktur belum siap, jangan lanjutkan agar tidak error
    if (!structure.programs || structure.programs.length === 0 || !structure.programs[0].kegiatan || structure.programs[0].kegiatan.length === 0) {
        console.warn('Struktur tabel anggaran belum siap, lewati sync ke tabel 3');
        return;
    }
    
    // Simpan data subkegiatan yang sudah ada sebelumnya
    const existingSubKegiatan = [...(structure.programs[0].kegiatan[0].subKegiatan || [])];
    
    // Clear sub kegiatan yang ada
    structure.programs[0].kegiatan[0].subKegiatan = [];
    
    // Tambahkan sub kegiatan dari Tabel A
    rowsA.forEach((rowA, index) => {
        const sasaran = rowA.querySelector('textarea[name="a_sasaran[]"]')?.value || '';
        const indikator = rowA.querySelector('textarea[name="a_indikator[]"]')?.value || '';
        const target = rowA.querySelector('textarea[name="a_target[]"]')?.value || '';
        
        // Buat nama sub kegiatan dari sasaran dan indikator
        let subKegiatanName = '';
        if (sasaran && indikator) {
            subKegiatanName = `${sasaran} - ${indikator}`;
        } else if (sasaran) {
            subKegiatanName = sasaran;
        } else if (indikator) {
            subKegiatanName = indikator;
        } else {
            subKegiatanName = `Sub Kegiatan ${index + 1}`;
        }
        
        const existingData = existingSubKegiatan[index] || null;
        
        structure.programs[0].kegiatan[0].subKegiatan.push({
            name: subKegiatanName,
            amount: target || '0',
            tw1: existingData ? existingData.tw1 : '0',
            tw2: existingData ? existingData.tw2 : '0',
            tw3: existingData ? existingData.tw3 : '0',
            tw4: existingData ? existingData.tw4 : '0'
        });
    });
    
    // Render ulang tabel
    renderHierarchicalBudgetTable(structure);
    updateHierarchicalTotals();
}

// =========================
// HAPUS BARIS
// =========================
function deleteRow(button, tableId) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    
    // Jangan hapus jika hanya ada 1 baris
    if (tbody.rows.length <= 1) {
        alert("Harus ada minimal 1 baris!");
        return;
    }
    
    const row = button.closest("tr");
    row.remove();
    
    // Update nomor otomatis
    updateRowNumbers(tableId);
    
    // Sync tabel A ke C jika menghapus baris di tabelA
    if (tableId === 'tabelA') {
        syncTabelAToC();
    }
}

// =========================
// UPDATE NOMOR BARIS OTOMATIS
// =========================
function updateRowNumbers(tableId) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    
    tbody.querySelectorAll("tr").forEach((row, index) => {
        if (tableId !== 'tabel2') {
            row.cells[0].innerText = index + 1;
        }
    });
}

// =========================
// GENERATE HIERARCHICAL STRUCTURE
// =========================
function getHierarchicalBudgetStructure() {
    const tbody = document.getElementById('tabelProgram').querySelector('tbody');
    const rows = tbody.querySelectorAll('tr');
    
    const cleanTWVal = (val) => {
        if (!val) return 0;
        const withoutDecimal = val.toString().split(',')[0];
        return parseInt(withoutDecimal.replace(/[^\d]/g, ''), 10) || 0;
    };
    
    // Mapping antara nomor hierarki dengan data TW dari tabel keempat
    const twDataMap = {};
    const tbodyD = document.getElementById('hierarchical-budget-tbody');
    if (tbodyD) {
        tbodyD.querySelectorAll('tr').forEach(row => {
            const no = row.querySelectorAll('td')[0]?.textContent?.trim() || '';
            const tw1 = row.querySelector('.tw1')?.value || '';
            const tw2 = row.querySelector('.tw2')?.value || '';
            const tw3 = row.querySelector('.tw3')?.value || '';
            const tw4 = row.querySelector('.tw4')?.value || '';
            
            if (no) {
                twDataMap[no] = { tw1, tw2, tw3, tw4 };
            }
        });
    }
    
    const structure = {
        programs: []
    };
    
    let currentProgram = null;
    let currentKegiatan = null;
    
    rows.forEach(row => {
        const no = row.querySelector('.no-col')?.textContent?.trim() || '';
        const parts = no.split('.');
        const level = parts.length;
        
        // Ambil nama dari select dropdown (text option yang dipilih)
        let name = '';
        const isProgram = row.classList.contains('program-row');
        const isSub = row.classList.contains('subprogram-row');
        
        if (isProgram) {
            const select = row.querySelector('.program-nama-dropdown');
            name = select && select.selectedIndex >= 0 ? select.options[select.selectedIndex].text : '';
        } else if (isSub) {
            const dataLevel = row.dataset.level;
            const select = row.querySelector(`.${dataLevel}-nama-dropdown`);
            name = select && select.selectedIndex >= 0 ? select.options[select.selectedIndex].text : '';
        }
        
        // Ambil anggaran dari input
        const anggaranInput = row.querySelector('input[type="text"]');
        const amount = anggaranInput ? cleanTWVal(anggaranInput.value) : 0;
        
        // Keterangan (source) - read from form element
        let ket = '';
        if (isProgram) {
            ket = row.querySelector('textarea[name="program_ket[]"]')?.value?.trim() || '';
        } else if (isSub) {
            const dl = row.dataset.level;
            if (dl === 'kegiatan') {
                ket = row.querySelector('textarea[name="kegiatan_ket[]"]')?.value?.trim() || '';
            } else if (dl === 'subkegiatan') {
                ket = row.querySelector('select[name="subkegiatan_ket[]"]')?.value?.trim() || '';
            }
        }
        
        // Ambil TW data dari tabel keempat
        const twData = twDataMap[no] || { tw1: '', tw2: '', tw3: '', tw4: '' };
        
        if (level === 1) {
            // Level Program
            currentProgram = {
                name: name,
                amount: amount,
                source: ket,
                tw1: cleanTWVal(twData.tw1),
                tw2: cleanTWVal(twData.tw2),
                tw3: cleanTWVal(twData.tw3),
                tw4: cleanTWVal(twData.tw4),
                kegiatan: []
            };
            structure.programs.push(currentProgram);
        } else if (level === 2 && currentProgram) {
            // Level Kegiatan
            currentKegiatan = {
                name: name,
                amount: amount,
                source: ket,
                tw1: cleanTWVal(twData.tw1),
                tw2: cleanTWVal(twData.tw2),
                tw3: cleanTWVal(twData.tw3),
                tw4: cleanTWVal(twData.tw4),
                subKegiatan: []
            };
            currentProgram.kegiatan.push(currentKegiatan);
        } else if (level === 3 && currentKegiatan) {
            // Level Sub Kegiatan
            currentKegiatan.subKegiatan.push({
                name: name,
                amount: amount,
                source: ket,
                tw1: cleanTWVal(twData.tw1),
                tw2: cleanTWVal(twData.tw2),
                tw3: cleanTWVal(twData.tw3),
                tw4: cleanTWVal(twData.tw4)
            });
        }
    });
    
    return structure;
}

// =========================
// SAVE TO SUPABASE
// =========================
function saveToSupabase(e) {
    e.preventDefault();
    
    // Serialize hierarchical budget structure and set in hidden input
    const hierarchicalStructure = getHierarchicalBudgetStructure();
    const inputEl = document.getElementById('hierarchical-budget-json');
    if (inputEl) {
        inputEl.value = JSON.stringify(hierarchicalStructure);
    }
    
    const form = document.querySelector("form");
    const formData = new FormData(form);
    
    // Debug: log payload structure (amounts are digits-only strings)
    console.log('Hierarchical payload:', JSON.stringify(hierarchicalStructure, null, 2));
    
    // Tampilkan loading
    const btn = e.target;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = '⏳ Menyimpan...';
    
    fetch('{{ route("perjanjian.update", $perjanjian->id) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        // Try to parse JSON body if present
        const contentType = response.headers.get('content-type') || '';
        let data = {};
        if (contentType.includes('application/json')) {
            data = await response.json();
        } else {
            // If server returned HTML or plain text, read it for debugging
            const text = await response.text();
            throw new Error('Unexpected server response: ' + text);
        }

        // If response not OK, extract message or validation errors
        if (!response.ok) {
            let msg = data.message || 'Gagal menyimpan data.';
            if (data.errors) {
                // collect validation errors
                const errs = [];
                for (const k in data.errors) {
                    errs.push(...data.errors[k]);
                }
                if (errs.length) msg = errs.join(' | ');
            }
            throw new Error(msg);
        }

        return data;
    })
    .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;

        if (data.success) {
            showSuccessMessage(data.message || 'Berhasil disimpan.');
            setTimeout(() => {
                window.location.href = '{{ auth()->check() && auth()->user()->isWadir() ? route("dashboard.wadir", ["panel" => "perjanjian"]) : route("home", ["section" => "dashboard"]) }}';
            }, 1200);
        } else {
            showErrorMessage(data.message || 'Gagal menyimpan data.');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerText = originalText;
        console.error('Save error:', error);
        showErrorMessage(error.message || 'Error saat menyimpan');
    });
}

// =========================
// SHOW SUCCESS MESSAGE
// =========================
function showSuccessMessage(message) {
    const div = document.createElement('div');
    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 9999;
        font-size: 14px;
    `;
    div.innerText = '✓ ' + message;
    document.body.appendChild(div);
    
    setTimeout(() => div.remove(), 3000);
}

// =========================
// SHOW ERROR MESSAGE
// =========================
function showErrorMessage(message) {
    const div = document.createElement('div');
    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 9999;
        font-size: 14px;
    `;
    div.innerText = '✗ ' + message;
    document.body.appendChild(div);
    
    setTimeout(() => div.remove(), 5000);
}

// =========================
// AUTO-FILL NAMA DAN NIP BERDASARKAN JABATAN
// =========================
// Pihak Kedua sudah otomatis terisi dengan data Direktur dari backend
// Event listener tidak diperlukan lagi karena field readonly

// =========================
// AUTO-FILL TARGET TRIWULAN
// =========================
function openAutoFillDialog(pIdx, kIdx, sIdx) {
    const amountInput = document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_amount"]`);
    const anggaran = parseAmount(amountInput?.value || '0');
    
    if (anggaran === 0) {
        alert('Anggaran Sub Kegiatan belum diisi!');
        return;
    }
    
    // Update input fields based on method
    methodSelect.addEventListener('change', function() {
        const method = this.value;
        
        if (method === 'equal') {
            inputFieldsDiv.innerHTML = '<p style="font-size: 12px; color: #666; font-style: italic;">Setiap triwulan akan diisi 25% dari total anggaran</p>';
        } else if (method === 'percentage') {
            inputFieldsDiv.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 12px;">TW I (%):</label>
                        <input type="number" id="pct1" value="25" min="0" max="100" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">TW II (%):</label>
                        <input type="number" id="pct2" value="25" min="0" max="100" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">TW III (%):</label>
                        <input type="number" id="pct3" value="25" min="0" max="100" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">TW IV (%):</label>
                        <input type="number" id="pct4" value="25" min="0" max="100" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                </div>
                <p style="font-size: 11px; color: #f44336; margin-top: 5px; font-style: italic;">Total persentase harus = 100%</p>
            `;
        } else if (method === 'rupiah') {
            inputFieldsDiv.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label style="font-size: 12px;">TW I (Rp):</label>
                        <input type="text" id="rp1" value="0" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; text-align: right;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">TW II (Rp):</label>
                        <input type="text" id="rp2" value="0" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; text-align: right;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">TW III (Rp):</label>
                        <input type="text" id="rp3" value="0" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; text-align: right;">
                    </div>
                    <div>
                        <label style="font-size: 12px;">TW IV (Rp):</label>
                        <input type="text" id="rp4" value="0" style="width: 100%; padding: 6px; border: 1px solid #ddd; border-radius: 4px; text-align: right;">
                    </div>
                </div>
                <p style="font-size: 11px; color: #f44336; margin-top: 5px; font-style: italic;">Total rupiah tidak boleh melebihi anggaran</p>
            `;
            
            // Add number formatting to rupiah inputs
            inputFieldsDiv.querySelectorAll('input[id^="rp"]').forEach(inp => {
                inp.addEventListener('input', function() {
                    this.value = formatNumberInput(this.value);
                });
            });
        }
    });
    
    // Trigger initial render
    methodSelect.dispatchEvent(new Event('change'));
    
    // Apply button handler
    applyBtn.addEventListener('click', function() {
        const method = methodSelect.value;
        let tw1, tw2, tw3, tw4;
        
        if (method === 'equal') {
            const quarter = Math.floor(anggaran / 4);
            tw1 = tw2 = tw3 = tw4 = quarter;
        } else if (method === 'percentage') {
            const pct1 = parseFloat(dialogBox.querySelector('#pct1').value) || 0;
            const pct2 = parseFloat(dialogBox.querySelector('#pct2').value) || 0;
            const pct3 = parseFloat(dialogBox.querySelector('#pct3').value) || 0;
            const pct4 = parseFloat(dialogBox.querySelector('#pct4').value) || 0;
            const totalPct = pct1 + pct2 + pct3 + pct4;
            
            if (totalPct !== 100) {
                alert('Total persentase harus 100%! Saat ini: ' + totalPct + '%');
                return;
            }
            
            tw1 = Math.floor(anggaran * pct1 / 100);
            tw2 = Math.floor(anggaran * pct2 / 100);
            tw3 = Math.floor(anggaran * pct3 / 100);
            tw4 = Math.floor(anggaran * pct4 / 100);
        } else if (method === 'rupiah') {
            tw1 = parseAmount(dialogBox.querySelector('#rp1').value);
            tw2 = parseAmount(dialogBox.querySelector('#rp2').value);
            tw3 = parseAmount(dialogBox.querySelector('#rp3').value);
            tw4 = parseAmount(dialogBox.querySelector('#rp4').value);
            
            const totalRp = tw1 + tw2 + tw3 + tw4;
            if (totalRp > anggaran) {
                alert('Total rupiah (' + formatNumberInput(totalRp.toString()) + ') melebihi anggaran!');
                return;
            }
        }
        
        // Apply values to inputs
        const tw1Input = document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw1"]`);
        const tw2Input = document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw2"]`);
        const tw3Input = document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw3"]`);
        const tw4Input = document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw4"]`);
        
        if (tw1Input) tw1Input.value = formatNumberInput(tw1.toString());
        if (tw2Input) tw2Input.value = formatNumberInput(tw2.toString());
        if (tw3Input) tw3Input.value = formatNumberInput(tw3.toString());
        if (tw4Input) tw4Input.value = formatNumberInput(tw4.toString());
        
        // Validate
        validateTriwulanSum(pIdx, kIdx, sIdx);
        
        // Close dialog
        dialog.remove();
    });
}

// =========================
// VALIDASI TTD SAAT SUBMIT
// =========================
document.querySelector("form").addEventListener("submit", function(e) {
    const ttdPihak1 = @json(auth()->user()->tanda_tangan);

    if (!ttdPihak1) {
        e.preventDefault();
        document.getElementById("popupTTDKosong").style.display = "flex";
    }
});

function closePopupTTD() {
    document.getElementById("popupTTDKosong").style.display = "none";
}

</script>

<!-- =========================
     POPUP SUCCESS
     ========================= -->
@if(session('success'))
<div style="position:fixed; top:20px; right:20px; background:#009970; 
    color:white; padding:12px 20px; border-radius:8px; 
    box-shadow:0 3px 10px rgba(0,0,0,0.2); z-index:9999;">
    {{ session('success') }}
</div>

<script>
    setTimeout(() => {
        document.querySelector('[style*="position:fixed"]')?.remove();
    }, 3000);
</script>
@endif

@endsection
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('Prepopulating data for edit...');

    function safeParse(jsonText, fallback) {
        try {
            let current = jsonText;
            for (let i = 0; i < 3; i++) {
                if (typeof current !== 'string') {
                    return current ?? fallback;
                }
                const trimmed = current.trim();
                if (trimmed === '') {
                    return fallback;
                }
                current = JSON.parse(trimmed);
            }
            return current ?? fallback;
        } catch (e) {
            return fallback;
        }
    }

    function normalizeTabelBData(data, expectedRows = 0) {
        const empty = { sasaran: [], indikator: [], indicator_type: [], target: [], tw1: [], tw2: [], tw3: [], tw4: [] };
        if (!data) return empty;

        if (Array.isArray(data.rows)) {
            const rows = data.rows;
            return {
                sasaran: rows.map(r => r?.sasaran || ''),
                indikator: rows.map(r => r?.indikator || ''),
                indicator_type: rows.map(r => r?.indicator_type || 'positif'),
                target: rows.map(r => r?.target || ''),
                tw1: rows.map(r => r?.tw1 || ''),
                tw2: rows.map(r => r?.tw2 || ''),
                tw3: rows.map(r => r?.tw3 || ''),
                tw4: rows.map(r => r?.tw4 || ''),
            };
        }

        if (Array.isArray(data)) {
            const rows = data;
            return {
                sasaran: rows.map(r => r?.sasaran || ''),
                indikator: rows.map(r => r?.indikator || ''),
                indicator_type: rows.map(r => r?.indicator_type || 'positif'),
                target: rows.map(r => r?.target || ''),
                tw1: rows.map(r => r?.tw1 || ''),
                tw2: rows.map(r => r?.tw2 || ''),
                tw3: rows.map(r => r?.tw3 || ''),
                tw4: rows.map(r => r?.tw4 || ''),
            };
        }

        const result = {
            sasaran: Array.isArray(data.sasaran) ? data.sasaran : [],
            indikator: Array.isArray(data.indikator) ? data.indikator : [],
            indicator_type: Array.isArray(data.indicator_type) ? data.indicator_type : [],
            target: Array.isArray(data.target) ? data.target : [],
            tw1: Array.isArray(data.tw1) ? data.tw1 : (Array.isArray(data.triwulan1) ? data.triwulan1 : []),
            tw2: Array.isArray(data.tw2) ? data.tw2 : (Array.isArray(data.triwulan2) ? data.triwulan2 : []),
            tw3: Array.isArray(data.tw3) ? data.tw3 : (Array.isArray(data.triwulan3) ? data.triwulan3 : []),
            tw4: Array.isArray(data.tw4) ? data.tw4 : (Array.isArray(data.triwulan4) ? data.triwulan4 : []),
        };

        const inferredLength = Math.max(
            expectedRows,
            result.sasaran.length,
            result.indikator.length,
            result.target.length,
            result.tw1.length,
            result.tw2.length,
            result.tw3.length,
            result.tw4.length
        );

        if (inferredLength > 0) {
            ['sasaran', 'indikator', 'indicator_type', 'target', 'tw1', 'tw2', 'tw3', 'tw4'].forEach((key) => {
                while (result[key].length < inferredLength) {
                    result[key].push(key === 'indicator_type' ? 'positif' : '');
                }
            });
        }

        return result;
    }

    function setSelectByTextOrValue(selectEl, value) {
        if (!selectEl || !value) return;
        const normalized = String(value).trim().toLowerCase();
        let found = Array.from(selectEl.options).find(opt => (opt.value || '').trim().toLowerCase() === normalized);
        if (!found) {
            found = Array.from(selectEl.options).find(opt => (opt.text || '').trim().toLowerCase() === normalized);
        }
        if (!found) {
            const opt = document.createElement('option');
            opt.value = value;
            opt.text = value;
            selectEl.appendChild(opt);
            found = opt;
        }
        selectEl.value = found.value;
    }

    function formatNumberInput(value) {
        const digits = String(value ?? '').replace(/[^0-9]/g, '');
        if (!digits) return '0';
        return parseInt(digits, 10).toLocaleString('id-ID');
    }

    function normalizeHierarchyData(data) {
        if (!data) return { programs: [] };
        if (Array.isArray(data)) return { programs: data };
        if (Array.isArray(data.programs)) return data;

        // Legacy flat format fallback
        if (Array.isArray(data.program)) {
            const programs = data.program.map((name, idx) => ({
                name: name || '',
                amount: Array.isArray(data.anggaran) ? (data.anggaran[idx] || '0') : '0',
                source: Array.isArray(data.keterangan) ? (data.keterangan[idx] || '') : '',
                kegiatan: [],
            }));
            return { programs };
        }

        return { programs: [] };
    }

    // Parse Tabel A, Tabel B, Tabel C from Database
    const rawTabelA = `{!! addslashes(is_string($perjanjian->tabelA) ? $perjanjian->tabelA : json_encode($perjanjian->tabelA ?? [])) !!}`;
    const rawTabelB = `{!! addslashes(is_string($perjanjian->tabelB) ? $perjanjian->tabelB : json_encode($perjanjian->tabelB ?? [])) !!}`;
    const rawTabelC = `{!! addslashes(is_string($perjanjian->tabelC) ? $perjanjian->tabelC : json_encode($perjanjian->tabelC ?? [])) !!}`;
    const rawTabelD = `{!! addslashes(is_string($perjanjian->tabelD) ? $perjanjian->tabelD : json_encode($perjanjian->tabelD ?? [])) !!}`;

    let parsedA = safeParse(rawTabelA, { sasaran: [] });
    let parsedB = normalizeTabelBData(safeParse(rawTabelB, {}), Array.isArray(parsedA?.sasaran) ? parsedA.sasaran.length : 0);
    let parsedC = normalizeHierarchyData(safeParse(rawTabelC, { programs: [] }));
    const parsedD = normalizeHierarchyData(safeParse(rawTabelD, { programs: [] }));
    if ((!Array.isArray(parsedC?.programs) || parsedC.programs.length === 0) && Array.isArray(parsedD?.programs) && parsedD.programs.length > 0) {
        parsedC = parsedD;
    }

    // 1) Prepopulate Tabel A
    const tbodyA = document.querySelector('#tabelA tbody');
    const sasaranList = Array.isArray(parsedA?.sasaran) ? parsedA.sasaran : [];
    if (tbodyA && sasaranList.length > 0) {
        // Keep one base row, then add rows as needed.
        const baseRow = tbodyA.querySelector('tr');
        tbodyA.innerHTML = '';
        if (baseRow) {
            tbodyA.appendChild(baseRow.cloneNode(true));
        }

        for (let i = 1; i < sasaranList.length; i++) {
            if (typeof window.addRow === 'function') {
                window.addRow('tabelA');
            }
        }

        const rowsA = Array.from(tbodyA.querySelectorAll('tr'));
        rowsA.forEach((row, index) => {
            const sArea = row.querySelector('textarea[name="a_sasaran[]"]');
            const iArea = row.querySelector('textarea[name="a_indikator[]"]');
            const stArea = row.querySelector('textarea[name="a_satuan[]"]');
            const tArea = row.querySelector('textarea[name="a_target[]"]');
            const indTypeInput = row.querySelector('input[name="a_indicator_type[]"]');

            if (sArea) sArea.value = sasaranList[index] || '';
            if (iArea) iArea.value = Array.isArray(parsedA?.indikator) ? (parsedA.indikator[index] || '') : '';
            if (stArea) stArea.value = Array.isArray(parsedA?.satuan) ? (parsedA.satuan[index] || '') : '';
            if (tArea) tArea.value = Array.isArray(parsedA?.target) ? (parsedA.target[index] || '') : '';

            const indicatorType = Array.isArray(parsedA?.indicator_type) ? (parsedA.indicator_type[index] || 'positif') : 'positif';
            if (indTypeInput) indTypeInput.value = indicatorType;

            const plusBtn = row.querySelector('.indicator-plus');
            const minusBtn = row.querySelector('.indicator-minus');
            if (plusBtn) plusBtn.classList.toggle('active', indicatorType !== 'negatif');
            if (minusBtn) minusBtn.classList.toggle('active', indicatorType === 'negatif');
        });
    }

    // Sync dulu agar Tabel C struktural mengikuti Tabel A
    if (typeof window.syncTabelAToC === 'function') {
        window.syncTabelAToC();
    }

    // 2) Prepopulate Tabel C (id: tabel2) TW data
    const tbody2 = document.querySelector('#tabel2 tbody');
    if (tbody2 && parsedB && Array.isArray(parsedB.tw1) && parsedB.tw1.length > 0) {
        const rows2 = tbody2.querySelectorAll('tr');
        parsedB.tw1.forEach((tw1Val, index) => {
            const row = rows2[index];
            if (row) {
                const t1 = row.querySelector('textarea[name="c_tw1[]"]');
                const t2 = row.querySelector('textarea[name="c_tw2[]"]');
                const t3 = row.querySelector('textarea[name="c_tw3[]"]');
                const t4 = row.querySelector('textarea[name="c_tw4[]"]');
                
                if (t1) t1.value = tw1Val ?? '';
                if (t2) t2.value = Array.isArray(parsedB.tw2) ? (parsedB.tw2[index] ?? '') : '';
                if (t3) t3.value = Array.isArray(parsedB.tw3) ? (parsedB.tw3[index] ?? '') : '';
                if (t4) t4.value = Array.isArray(parsedB.tw4) ? (parsedB.tw4[index] ?? '') : '';
            }
        });
    }

    // 3) Prepopulate Tabel D source (id: tabelProgram) and mirror to tabel3
    if (parsedC && Array.isArray(parsedC.programs) && parsedC.programs.length > 0) {
        const programTbody = document.querySelector('#tabelProgram tbody');
        if (programTbody) {
            programTbody.innerHTML = '';

            const twMap = {};

            parsedC.programs.forEach((prog, pIdx) => {
                if (typeof window.addProgram === 'function') {
                    window.addProgram();
                }
                const programRow = programTbody.querySelectorAll('tr.program-row')[pIdx];
                if (programRow) {
                    const progSelect = programRow.querySelector('select[name="program_nama[]"]');
                    if (progSelect) {
                        setSelectByTextOrValue(progSelect, prog.name || prog.nama_program || '');
                        const selectedProg = progSelect.options[progSelect.selectedIndex];
                        if (selectedProg) {
                            programRow.dataset.programId = selectedProg.getAttribute('data-program-id') || '';
                        }
                    }
                    
                    const angInput = programRow.querySelector('input[name="program_anggaran[]"]');
                    if (angInput) angInput.value = formatNumberInput(String(prog.amount || prog.anggaran || '0').replace(/[^0-9]/g, ''));
                    
                    const ketInput = programRow.querySelector('textarea[name="program_ket[]"]');
                    if (ketInput) ketInput.value = prog.source || prog.keterangan || '';
                }
                
                const currentProgramNo = pIdx + 1;
                const programKey = `program-${currentProgramNo}`;
                twMap[programKey] = {
                    tw1: prog.tw1 || 0,
                    tw2: prog.tw2 || 0,
                    tw3: prog.tw3 || 0,
                    tw4: prog.tw4 || 0,
                };

                const kegiatans = Array.isArray(prog.kegiatan) ? prog.kegiatan : [];
                if (kegiatans.length > 0) {
                    kegiatans.forEach((keg, kIdx) => {
                        if (typeof window.addSubRow === 'function') {
                            window.addSubRow(currentProgramNo.toString(), 'kegiatan');
                        }
                        const kegiatanRow = Array.from(programTbody.querySelectorAll(`tr.subprogram-row[data-level="kegiatan"][data-parent="${currentProgramNo}"]`))[kIdx];
                        
                        if (kegiatanRow) {
                            const kegSelect = kegiatanRow.querySelector('select[name="kegiatan_nama[]"]');
                            if (kegSelect) {
                                setSelectByTextOrValue(kegSelect, keg.name || keg.nama_kegiatan || '');
                                const selectedKeg = kegSelect.options[kegSelect.selectedIndex];
                                if (selectedKeg) {
                                    kegiatanRow.dataset.kegiatanId = selectedKeg.getAttribute('data-kegiatan-id') || '';
                                }
                            }
                            
                            const angInput = kegiatanRow.querySelector('input[name="kegiatan_anggaran[]"]');
                            if (angInput) angInput.value = formatNumberInput(String(keg.amount || keg.anggaran || '0').replace(/[^0-9]/g, ''));
                            
                            const ketInput = kegiatanRow.querySelector('textarea[name="kegiatan_ket[]"]');
                            if (ketInput) ketInput.value = keg.source || keg.keterangan || '';
                        }

                        const currentKegiatanNo = `${currentProgramNo}.${kIdx + 1}`;
                        const kegiatanKey = `sub-${currentKegiatanNo}`;
                        twMap[kegiatanKey] = {
                            tw1: keg.tw1 || 0,
                            tw2: keg.tw2 || 0,
                            tw3: keg.tw3 || 0,
                            tw4: keg.tw4 || 0,
                        };

                        const subList = Array.isArray(keg.subKegiatan) ? keg.subKegiatan : (Array.isArray(keg.subkegiatan) ? keg.subkegiatan : []);
                        if (subList.length > 0) {
                            subList.forEach((sub, sIdx) => {
                                if (typeof window.addSubRow === 'function') {
                                    window.addSubRow(currentKegiatanNo, 'subkegiatan');
                                }
                                const subRow = Array.from(programTbody.querySelectorAll(`tr.subprogram-row[data-level="subkegiatan"][data-parent="${currentKegiatanNo}"]`))[sIdx];
                                
                                if (subRow) {
                                    const subSelect = subRow.querySelector('select[name="subkegiatan_nama[]"]');
                                    if (subSelect) {
                                        setSelectByTextOrValue(subSelect, sub.name || sub.nama_sub_kegiatan || '');
                                    }
                                    
                                    const angInput = subRow.querySelector('input[name="subkegiatan_anggaran[]"]');
                                    if (angInput) angInput.value = formatNumberInput(String(sub.amount || sub.anggaran || '0').replace(/[^0-9]/g, ''));
                                    
                                    const ketInput = subRow.querySelector('select[name="subkegiatan_ket[]"]');
                                    if (ketInput) ketInput.value = sub.source || sub.keterangan || '';
                                }

                                const subKey = `sub-${currentKegiatanNo}.${sIdx + 1}`;
                                twMap[subKey] = {
                                    tw1: sub.tw1 || 0,
                                    tw2: sub.tw2 || 0,
                                    tw3: sub.tw3 || 0,
                                    tw4: sub.tw4 || 0,
                                };
                            });
                        }
                    });
                }
            });
            
            if (typeof window.updateProgramNumbers === 'function') window.updateProgramNumbers();
            if (typeof window.calculateHierarchicalTotal === 'function') window.calculateHierarchicalTotal();
            if (typeof window.syncProgramToTabelD === 'function') window.syncProgramToTabelD();

            // Apply saved TW data to mirrored tabel3 rows.
            Object.keys(twMap).forEach((key) => {
                const tr = document.querySelector(`#hierarchical-budget-tbody tr[data-key="${key}"]`);
                if (!tr) return;
                const tw = twMap[key] || {};
                const setVal = (selector, value) => {
                    const input = tr.querySelector(selector);
                    if (input) input.value = formatNumberInput(String(value || 0));
                };
                setVal('.tw1', tw.tw1);
                setVal('.tw2', tw.tw2);
                setVal('.tw3', tw.tw3);
                setVal('.tw4', tw.tw4);
            });

            if (typeof window.hitungHierarchicalTW === 'function') {
                window.hitungHierarchicalTW();
            }
        }
    } else {
        // Fallback if no parsedC, keep table structures synced.
        if (typeof window.calculateHierarchicalTotal === 'function') window.calculateHierarchicalTotal();
        if (typeof window.syncProgramToTabelD === 'function') window.syncProgramToTabelD();
    }
});
</script>
