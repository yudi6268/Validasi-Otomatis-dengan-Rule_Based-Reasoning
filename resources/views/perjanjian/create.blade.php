@php($hideHeaderActions = true)

@extends('layouts.app')

@section('title', 'Form Perjanjian')

@section('back')
<a href="{{ route('perjanjian.index') }}" style="text-decoration:none; color:#009970; font-size:20px;">
    <i class="fa-solid fa-arrow-left"></i>
</a>
@endsection

@section('header_title', 'Form Perjanjian')

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
        <td><textarea name="program_ket[]" onkeyup="autoExpand(this)"></textarea></td>
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
        <td><textarea name="${dataLevel}_ket[]" onkeyup="autoExpand(this)"></textarea></td>
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
    
    // Minimal 1 baris program harus ada
    if (row.classList.contains('program-row')) {
        const remainingPrograms = tbody.querySelectorAll('tr.program-row');
        if (remainingPrograms.length <= 1) {
            alert('Minimal harus ada 1 program!');
            return;
        }
    }
    
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
<form action="{{ route('perjanjian.store') }}" method="POST">
@csrf

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
            PERJANJIAN KINERJA TAHUN <span id="headerTahun">2025</span> <br>
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
                <option value="{{ $year }}">{{ $year }}</option>
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
            headerTahun.textContent = '2025'; // default
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
                value="{{ auth()->user()->nama ?? '' }}" readonly>

            <input type="text" class="input-box" name="pihak1_jabatan"
                value="{{ auth()->user()->jabatan ?? '' }}" readonly>

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK PERTAMA</b>.
            </p>
        </div>

        {{-- PIHAK KEDUA --}}
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak2_name" id="pihak2_name" 
                value="{{ $direktur->nama ?? '' }}" readonly tabindex="-1" style="pointer-events:none;background:#e9ecef;">
            {{-- Tidak tampilkan notifikasi jika direktur tidak ditemukan --}}

            <input type="text" class="input-box" name="pihak2_jabatan" id="pihak2_jabatan" 
                value="Direktur" readonly tabindex="-1" style="pointer-events:none;background:#e9ecef;">

            {{-- Hidden fields untuk pangkat dan NIP direktur --}}
            <input type="hidden" name="pihak2_pangkat" value="{{ $direktur->pangkat ?? '' }}">
            <input type="hidden" name="pihak2_nip" value="{{ $direktur->nip ?? '' }}">

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK KEDUA</b>.
            </p>
        </div>
    </div>

    {{-- LOCATION AND DATE --}}
    <div class="flex-row">
        <div class="flex-col">
            <input type="text" class="input-box" name="location" placeholder="Tempat" value="Pasuruan">
        </div>
        <div class="flex-col">
            <input type="date" class="input-box" name="agreement_date" value="{{ date('Y-m-d') }}">
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
                value="{{ isset($jabatanData) && $jabatanData ? $jabatanData->nama_jabatan : '' }}"
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
            >{{ isset($jabatanData) && $jabatanData ? $jabatanData->tugas : '' }}</textarea>
            {{-- Tidak tampilkan notifikasi jika tugas tidak ditemukan --}}
        </div>

        <div>
            <label style="font-weight: 600;">Fungsi</label>
            <div id="fungsi_container" style="
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 6px;
                background: #e9ecef;
                min-height: 60px;
            ">
                @if(isset($jabatanData) && $jabatanData && $jabatanData->fungsi)
                    @if(is_array($jabatanData->fungsi))
                        <ol style="margin: 0; padding-left: 20px;">
                            @foreach($jabatanData->fungsi as $fungsi)
                                <li style="margin-bottom: 4px;">{{ $fungsi }}</li>
                            @endforeach
                        </ol>
                    @else
                        {{ $jabatanData->fungsi }}
                    @endif
                @endif
            </div>
            <input type="hidden" name="fungsi_pelaksana" id="fungsi_pelaksana" 
                   value="{{ isset($jabatanData) && $jabatanData && is_array($jabatanData->fungsi ?? null) ? json_encode($jabatanData->fungsi) : (isset($jabatanData) && $jabatanData ? $jabatanData->fungsi : '') }}">
        </div>
    </div>

{{-- TABEL A --}}
<table id="tabelA">
    <thead>
        <tr>
            <th>NO</th>
            <th>SASARAN</th>
            <th>INDIKATOR KINERJA</th>
            <th style="width: 80px;">SATUAN</th>
            <th>TARGET</th>
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
            <td><textarea name="program_ket[]" onkeyup="autoExpand(this)"></textarea></td>
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
                <li>Untuk <strong>Sub-Kegiatan</strong>: Klik tombol <span style="background:#00B5A0; color:white; padding:2px 6px; border-radius:3px; font-size:11px;">🎯 Atur</span> untuk mengisi target per bulan</li>
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
                    tw1Sum += parseInt(sub.querySelector('.tw1')?.value.replace(/[^\d]/g, '') || 0);
                    tw2Sum += parseInt(sub.querySelector('.tw2')?.value.replace(/[^\d]/g, '') || 0);
                    tw3Sum += parseInt(sub.querySelector('.tw3')?.value.replace(/[^\d]/g, '') || 0);
                    tw4Sum += parseInt(sub.querySelector('.tw4')?.value.replace(/[^\d]/g, '') || 0);
                });
                
                kegiatanRow.querySelector('.tw1').value = tw1Sum.toLocaleString('id-ID');
                kegiatanRow.querySelector('.tw2').value = tw2Sum.toLocaleString('id-ID');
                kegiatanRow.querySelector('.tw3').value = tw3Sum.toLocaleString('id-ID');
                kegiatanRow.querySelector('.tw4').value = tw4Sum.toLocaleString('id-ID');
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
                    tw1Sum += parseInt(keg.querySelector('.tw1')?.value.replace(/[^\d]/g, '') || 0);
                    tw2Sum += parseInt(keg.querySelector('.tw2')?.value.replace(/[^\d]/g, '') || 0);
                    tw3Sum += parseInt(keg.querySelector('.tw3')?.value.replace(/[^\d]/g, '') || 0);
                    tw4Sum += parseInt(keg.querySelector('.tw4')?.value.replace(/[^\d]/g, '') || 0);
                });
                
                programRow.querySelector('.tw1').value = tw1Sum.toLocaleString('id-ID');
                programRow.querySelector('.tw2').value = tw2Sum.toLocaleString('id-ID');
                programRow.querySelector('.tw3').value = tw3Sum.toLocaleString('id-ID');
                programRow.querySelector('.tw4').value = tw4Sum.toLocaleString('id-ID');
            }
        }
    });
    
    // Step 3: Sum all programs for grand total
    let t1 = 0, t2 = 0, t3 = 0, t4 = 0;
    allRows.forEach(tr => {
        if (!tr.dataset.key || !tr.dataset.key.startsWith('program-')) return;
        
        t1 += parseInt(tr.querySelector('.tw1')?.value.replace(/[^\d]/g,'') || 0);
        t2 += parseInt(tr.querySelector('.tw2')?.value.replace(/[^\d]/g,'') || 0);
        t3 += parseInt(tr.querySelector('.tw3')?.value.replace(/[^\d]/g,'') || 0);
        t4 += parseInt(tr.querySelector('.tw4')?.value.replace(/[^\d]/g,'') || 0);
    });
    
    document.getElementById('totalTW1').textContent = t1.toLocaleString('id-ID');
    document.getElementById('totalTW2').textContent = t2.toLocaleString('id-ID');
    document.getElementById('totalTW3').textContent = t3.toLocaleString('id-ID');
    document.getElementById('totalTW4').textContent = t4.toLocaleString('id-ID');
    
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
        window.pihak1Jabatan = @json(auth()->user()->jabatan);
    </script>

    <script>
    (function () {
        const jabatanPelaksana = document.getElementById('jabatan_pelaksana');

        if (jabatanPelaksana && window.pihak1Jabatan) {
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
    
    // Open modal first
    document.getElementById('targetModal').style.display = 'block';
    
    // Initialize mode and calculate with a small delay to ensure DOM is ready
    setTimeout(() => {
        switchInputMode('nominal');
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
    currentModalData.inputMode = mode;
    
    const btnNominal = document.getElementById('btnNominal');
    const btnProsentase = document.getElementById('btnProsentase');
    
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
        
        const rawValue = input.value.replace(/[^\d]/g, '');
        
        if (mode === 'nominal') {
            input.placeholder = '0';
            // Reformat as rupiah
            if (rawValue) {
                input.value = parseInt(rawValue).toLocaleString('id-ID');
            }
        } else {
            input.placeholder = '0%';
            // Show as plain number for percentage
            input.value = rawValue;
        }
    }
    
    // Recalculate with new mode
    calculateModalTotal();
}

function formatModalInput(input) {
    const mode = currentModalData.inputMode;
    let value = input.value.replace(/[^\d]/g, '');
    
    if (mode === 'nominal') {
        // Format as rupiah for nominal mode
        input.value = value ? parseInt(value).toLocaleString('id-ID') : '';
    } else {
        // For percentage, just keep the number
        input.value = value || '';
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
        
        // Extract numeric value from formatted string
        // Remove all non-digit characters (dots, commas, spaces, etc)
        const cleanValue = input.value.replace(/\./g, '').replace(/[^\d]/g, '');
        let value = parseInt(cleanValue) || 0;
        
        console.log('Bulan ' + i + ':', input.value, '→', cleanValue, '→', value);
        
        // Convert to nominal if in percentage mode
        if (mode === 'prosentase') {
            value = (value / 100) * pagu;
        }
        
        totalNominal += value;
        
        // Group by triwulan
        if (i >= 1 && i <= 3) tw1 += value;
        else if (i >= 4 && i <= 6) tw2 += value;
        else if (i >= 7 && i <= 9) tw3 += value;
        else if (i >= 10 && i <= 12) tw4 += value;
    }
    
    // Calculate percentage and remaining budget
    const sisaPagu = pagu - totalNominal;
    const prosentase = pagu > 0 ? (totalNominal / pagu) * 100 : 0;
    
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
        
        // Remove dots first, then remove other non-digit characters
        const cleanValue = input.value.replace(/\./g, '').replace(/[^\d]/g, '');
        let value = parseInt(cleanValue) || 0;
        
        // Convert to nominal if in percentage mode
        if (mode === 'prosentase') {
            value = (value / 100) * pagu;
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
        
        existingData.push({
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
        const targetInput = rowA.querySelector('textarea[name="a_target[]"]');
        
        const sasaran = sasaranInput ? sasaranInput.value : '';
        const indikator = indikatorInput ? indikatorInput.value : '';
        const target = targetInput ? targetInput.value : '';
        
        // Ambil data Target Triwulan yang sudah ada (jika ada)
        const tw1 = existingData[index]?.tw1 || '';
        const tw2 = existingData[index]?.tw2 || '';
        const tw3 = existingData[index]?.tw3 || '';
        const tw4 = existingData[index]?.tw4 || '';
        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td style="width: 25%;"><textarea name="c_sasaran[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">${sasaran}</textarea></td>
            <td style="width: 20%;"><textarea name="c_indikator[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">${indikator}</textarea></td>
            <td style="width: 12%;"><textarea name="c_target[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">${target}</textarea></td>
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
    
    // Reset values untuk input dan textarea, tapi pertahankan dropdown
    newRow.querySelectorAll("input, textarea").forEach(el => {
        el.value = "";
    });
    
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
        
        structure.programs[0].kegiatan[0].subKegiatan.push({
            name: subKegiatanName,
            amount: target || '0',
            tw1: '0',
            tw2: '0',
            tw3: '0',
            tw4: '0'
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
        const amount = (anggaranInput?.value || '0').replace(/[^\d]/g, '');
        
        // Keterangan (source) - jika ada
        const ket = '';
        
        // Ambil TW data dari tabel keempat
        const twData = twDataMap[no] || { tw1: '', tw2: '', tw3: '', tw4: '' };
        
        if (level === 1) {
            // Level Program
            currentProgram = {
                name: name,
                amount: amount,
                source: ket,
                tw1: twData.tw1,
                tw2: twData.tw2,
                tw3: twData.tw3,
                tw4: twData.tw4,
                kegiatan: []
            };
            structure.programs.push(currentProgram);
        } else if (level === 2 && currentProgram) {
            // Level Kegiatan
            currentKegiatan = {
                name: name,
                amount: amount,
                source: ket,
                tw1: twData.tw1,
                tw2: twData.tw2,
                tw3: twData.tw3,
                tw4: twData.tw4,
                subKegiatan: []
            };
            currentProgram.kegiatan.push(currentKegiatan);
        } else if (level === 3 && currentKegiatan) {
            // Level Sub Kegiatan
            currentKegiatan.subKegiatan.push({
                name: name,
                amount: amount,
                source: ket,
                tw1: twData.tw1,
                tw2: twData.tw2,
                tw3: twData.tw3,
                tw4: twData.tw4
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
    
    const form = document.querySelector("form");
    const formData = new FormData(form);
    
    // Serialize hierarchical budget structure and add to form
    const hierarchicalStructure = getHierarchicalBudgetStructure();
    // Debug: log payload structure (amounts are digits-only strings)
    console.log('Hierarchical payload:', JSON.stringify(hierarchicalStructure, null, 2));
    formData.append('hierarchical_budget_json', JSON.stringify(hierarchicalStructure));
    
    // Tampilkan loading
    const btn = e.target;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = '⏳ Menyimpan...';
    
    fetch('{{ route("perjanjian.save") }}', {
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
                window.location.href = '{{ route("perjanjian.index") }}';
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

// =========================
// POPUP SUCCESS
// =========================
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
</script>

@endsection
