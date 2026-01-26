@extends('layouts.app')

@section('title', 'Edit Perjanjian')

@section('back')
<a href="{{ route('perjanjian.index') }}" style="text-decoration:none; color:#009970; font-size:20px;">
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
        border: 1px solid #ddd;
        text-align: left;     
        background: #fff;
        font-size: 12px;
        box-sizing: border-box;
        padding: 4px;
        border-radius: 3px;
    }

    table td input:focus {
        outline: none;
        border-color: #009970;
        background: #f0fff4;
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
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 3px;
    }

    table td textarea:focus {
        outline: none;
        border-color: #009970;
        background: #f0fff4;
    }

    table td input[type="number"] {
        width: 95%;
        border: 1px solid #ddd;
        text-align: center;
        background: #fff;
        font-size: 12px;
        box-sizing: border-box;
        padding: 4px;
        border-radius: 3px;
    }

    table td input[type="number"]:focus {
        outline: none;
        border-color: #009970;
        background: #f0fff4;
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
</style>

@php
    // Decode existing data
    $tabelA = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
    $tabelB = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
    $tabelC = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
    
    // Convert old flat format to hierarchical format for editing
    if (!empty($tabelC) && isset($tabelC['program']) && is_array($tabelC['program']) && !isset($tabelC['programs'])) {
        // Old format detected: convert to hierarchical
        $programs = $tabelC['program'] ?? [];
        $anggarans = $tabelC['anggaran'] ?? [];
        $keterangans = $tabelC['keterangan'] ?? [];
        
        $hierarchicalPrograms = [];
        foreach ($programs as $idx => $programName) {
            if (!empty($programName) || (isset($anggarans[$idx]) && $anggarans[$idx] > 0)) {
                $hierarchicalPrograms[] = [
                    'name' => $programName ?? '',
                    'amount' => isset($anggarans[$idx]) ? (string)$anggarans[$idx] : '0',
                    'source' => isset($keterangans[$idx]) && $keterangans[$idx] !== '-' ? (string)$keterangans[$idx] : '',
                    'tw1' => '',
                    'tw2' => '',
                    'tw3' => '',
                    'tw4' => '',
                    'kegiatan' => []
                ];
            }
        }
        
        // Replace with hierarchical format
        $tabelC = ['programs' => $hierarchicalPrograms];
        
        // Log conversion for debugging
        \Log::info('Converted old format tabelC to hierarchical', [
            'original_count' => count($programs),
            'converted_count' => count($hierarchicalPrograms)
        ]);
    }
    
    // Log tabelB structure for debugging
    if (!empty($tabelB)) {
        \Log::info('TabelB structure in edit view', [
            'has_sasaran_key' => isset($tabelB['sasaran']),
            'sasaran_count' => isset($tabelB['sasaran']) ? count($tabelB['sasaran']) : 0,
            'keys' => array_keys($tabelB)
        ]);
    } else {
        \Log::info('TabelB is empty in edit view');
    }
    
    // Log tabelC structure for debugging
    if (!empty($tabelC)) {
        \Log::info('TabelC structure in edit view', [
            'has_programs_key' => isset($tabelC['programs']),
            'programs_count' => isset($tabelC['programs']) ? count($tabelC['programs']) : 0,
            'keys' => array_keys($tabelC)
        ]);
    }
@endphp

<script>
// DEFINE ALL FUNCTIONS AT THE TOP - BEFORE FORM
console.log('=== GLOBAL FUNCTIONS LOADING ===');

// Pass data to JavaScript FIRST
window.existingTabelC = @json($tabelC);
window.pihak1Jabatan = @json($perjanjian->pihak1_jabatan);
window.programsData = @json($programs);
window.kegiatansData = @json($kegiatans);
window.subKegiatansData = @json($subKegiatans);

console.log('Data loaded:', window.existingTabelC);

// Fungsi-fungsi handler sudah tidak diperlukan karena menggunakan textarea manual
// Placeholder dihapus: sebelumnya ada potongan kode referensi `select/textarea`
// di luar fungsi yang memicu error saat load dan menghentikan inisialisasi.
// Tidak diperlukan dengan input manual via textarea.

// Add Program function
// Pastikan diekspos ke global window
window.addProgram = function() {
    console.log('addProgram clicked!');
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) {
        console.error('tbody #tabelProgramBody not found!');
        alert('Error: Tabel program tidak ditemukan!');
        return;
    }
    
    const currentPrograms = tbody.querySelectorAll('tr.program-row');
    const newProgramNo = currentPrograms.length + 1;
    console.log('Creating program #', newProgramNo);

    const tr = document.createElement('tr');
    tr.classList.add('program-row');
    tr.dataset.program = newProgramNo;
    tr.dataset.level = 'program';

    tr.innerHTML = `
        <td class="no-col">${newProgramNo}</td>
        <td>
            <textarea name="program_nama[]" class="program-nama-manual" style="width:95%; font-weight:600;" onkeyup="if(window.autoExpand) autoExpand(this); if(window.syncProgramToTabelD) syncProgramToTabelD();"></textarea>
        </td>
        <td><input type="text" name="program_anggaran[]" value="0" style="text-align:right" oninput="if(window.formatRupiah) formatRupiah(this); if(window.calculateTotal) calculateTotal(); if(window.syncProgramToTabelD) syncProgramToTabelD();" /></td>
        <td><textarea name="program_ket[]" onkeyup="if(window.autoExpand) autoExpand(this);"></textarea></td>
        <td>
            <button type="button" class="table-action-btn add-btn" onclick="if(window.addSubRow) addSubRow('${newProgramNo}')" title="Tambah Kegiatan">➕</button>
            <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;

    tbody.appendChild(tr);
    console.log('Program row added successfully');

    if (typeof window.bindAnggaranListeners === 'function') {
        window.bindAnggaranListeners();
        console.log('Listeners bound after addProgram');
    }
    if (window.calculateTotal) {
        window.calculateTotal();
        console.log('calculateTotal called after addProgram');
    }
    
    return tr;
}
    
// Add Sub Row (Kegiatan/Sub-Kegiatan)
window.addSubRow = function(parentNo) {
    console.log('addSubRow called for parent:', parentNo, typeof parentNo);
    const parentNoStr = parentNo.toString();
    const parentLevel = parentNoStr.split('.').length;
    if (parentLevel >= 3) {
        alert('Maksimal 3 level (Program > Kegiatan > Sub-Kegiatan)');
        return;
    }

    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) {
        console.error('tbody not found');
        return;
    }

    // Cari parent row berdasarkan nomor di kolom NO
    console.log('Searching for parent row:', parentNoStr);
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    console.log('Total rows in table:', allRows.length);
    
    const parentRow = allRows.find(row => {
        const rowNo = row.querySelector('.no-col')?.textContent?.trim();
        return rowNo == parentNoStr;
    });

    if (!parentRow) {
        console.error('❌ Parent row not found for:', parentNoStr);
        alert('Baris parent tidak ditemukan! Nomor: ' + parentNoStr);
        return;
    }
    console.log('✓ Found parent row:', parentRow.querySelector('.no-col')?.textContent?.trim());

    const children = Array.from(tbody.querySelectorAll(`tr[data-parent="${parentNoStr}"]`));
    const newIndex = children.length + 1;
    const newNo = `${parentNoStr}.${newIndex}`;
    const dataLevel = parentLevel === 1 ? 'kegiatan' : 'subkegiatan';

    const tr = document.createElement('tr');
    tr.classList.add('subprogram-row');
    tr.dataset.parent = parentNoStr;
    tr.dataset.level = dataLevel;

    // Menggunakan textarea manual input untuk semua level
    const inputStyle = parentLevel === 1 ? 'font-style:italic;' : '';
    const paddingLeft = parentLevel === 1 ? '15px' : '30px';

    tr.innerHTML = `
        <td class="no-col">${newNo}</td>
        <td>
            <textarea name="${dataLevel}_nama[]" class="${dataLevel}-nama-manual" style="width:95%; padding-left:${paddingLeft}; ${inputStyle}" onkeyup="if(window.autoExpand) autoExpand(this)"></textarea>
        </td>
        <td><input type="text" name="${dataLevel}_anggaran[]" value="0" style="text-align:right" oninput="if(window.formatRupiah) formatRupiah(this); if(window.calculateTotal) calculateTotal();"></td>
        <td><textarea name="${dataLevel}_ket[]" onkeyup="if(window.autoExpand) autoExpand(this)"></textarea></td>
        <td>
            ${parentLevel < 2 ? `<button type="button" class="table-action-btn add-btn" onclick="if(window.addSubRow) addSubRow('${newNo}')" title="Tambah Sub Kegiatan">➕</button>` : ''}
            <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;

    // Insert tepat setelah parent row atau setelah semua child dari parent
    let insertAfter = parentRow;
    
    const parentIndex = allRows.indexOf(parentRow);
    console.log('Parent index:', parentIndex, 'Total rows:', allRows.length);
    
    for (let i = parentIndex + 1; i < allRows.length; i++) {
        const currentRow = allRows[i];
        const currentNo = currentRow.querySelector('.no-col')?.textContent;
        
        if (currentNo && currentNo.startsWith(parentNoStr + '.')) {
            insertAfter = currentRow;
        } else {
            break;
        }
    }

    if (insertAfter) {
        insertAfter.after(tr);
    } else {
        console.error('Insert position not found');
    }
    
    // Sinkronisasi tabel keempat
    if (window.syncProgramToTabelD) {
        syncProgramToTabelD();
    }
    
    console.log('Sub-row added:', newNo);
}

// Delete Row
window.deleteRow = function(btn, tableId = null) {
    console.log('deleteRow called', btn, tableId);
    if (!btn) {
        console.error('Button not provided to deleteRow');
        return;
    }
    
    const row = btn.closest('tr');
    if (!row) {
        console.error('Row not found');
        alert('Baris tidak ditemukan!');
        return;
    }
    
    const tbody = row.closest('tbody');
    if (!tbody) {
        console.error('tbody not found');
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
        console.log('Deleting program', programNo, 'and its children');
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
    console.log('Row removed');
    
    // Update nomor urut
    if ((tbody.closest('#tabelProgram')) || (tableId && tableId === 'tabelProgram')) {
        if (window.renumberAllRows) {
            renumberAllRows();
        }
    }
    
    if (window.calculateTotal) calculateTotal();
    if (window.syncProgramToTabelD) syncProgramToTabelD();
    
    console.log('Delete completed');
}

// Fungsi untuk renumber semua rows
window.renumberAllRows = function() {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) return;
    
    // Renumber programs
    const programRows = Array.from(tbody.querySelectorAll('tr.program-row'));
    programRows.forEach((programRow, programIndex) => {
        const newProgramNo = programIndex + 1;
        const oldProgramNo = programRow.dataset.program;
        
        programRow.dataset.program = newProgramNo;
        programRow.querySelector('.no-col').textContent = newProgramNo;
        
        // Update all children of this program
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        allRows.forEach(row => {
            const no = row.querySelector('.no-col')?.textContent;
            if (no && no.startsWith(oldProgramNo + '.')) {
                // Update nomor
                const parts = no.split('.');
                parts[0] = newProgramNo;
                const newNo = parts.join('.');
                row.querySelector('.no-col').textContent = newNo;
                
                // Update data-parent
                if (parts.length === 2) {
                    // Kegiatan
                    row.dataset.parent = newProgramNo;
                } else if (parts.length === 3) {
                    // Sub kegiatan
                    row.dataset.parent = parts[0] + '.' + parts[1];
                }
            }
        });
        
        // Renumber kegiatan dalam program ini
        const kegiatanRows = Array.from(tbody.querySelectorAll(`tr[data-parent="${newProgramNo}"]`));
        kegiatanRows.forEach((kegRow, kegIndex) => {
            const newKegNo = `${newProgramNo}.${kegIndex + 1}`;
            const oldKegNo = kegRow.querySelector('.no-col')?.textContent;
            kegRow.querySelector('.no-col').textContent = newKegNo;
            
            // Update sub kegiatan
            if (oldKegNo) {
                const subKegRows = Array.from(tbody.querySelectorAll(`tr[data-parent="${oldKegNo}"]`));
                subKegRows.forEach(subRow => {
                    subRow.dataset.parent = newKegNo;
                });
                
                tbody.querySelectorAll('tr').forEach(row => {
                    const no = row.querySelector('.no-col')?.textContent;
                    if (no && no.startsWith(oldKegNo + '.')) {
                        const parts = no.split('.');
                        const subIndex = parts[2];
                        const newSubNo = `${newKegNo}.${subIndex}`;
                        row.querySelector('.no-col').textContent = newSubNo;
                        row.dataset.parent = newKegNo;
                    }
                });
            }
        });
    });
}

// Update Program Numbers
window.updateProgramNumbers = function() {
    console.log('updateProgramNumbers called');
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) {
        console.error('tbody not found in updateProgramNumbers');
        return;
    }
    
    const remainingPrograms = tbody.querySelectorAll('tr.program-row');
    console.log('Renumbering', remainingPrograms.length, 'programs');
    
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

// Validasi input hanya angka
window.validateNumericInput = function(input) {
    // Hapus semua karakter non-digit
    input.value = input.value.replace(/[^0-9]/g, '');
}

console.log('=== GLOBAL FUNCTIONS LOADED ===');
console.log('window.addProgram:', typeof window.addProgram);
console.log('window.testAddRow:', typeof window.testAddRow);
</script>

<div class="paper">
<form action="{{ route('perjanjian.update', $perjanjian->id) }}" method="POST">
@method('PUT')
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
            PERJANJIAN KINERJA TAHUN 2025 <br>
            WAKIL DIREKTUR PELAYANAN <br>
            UOBK RSUD BANGIL KABUPATEN PASURUAN
        </h2>
    </div>

    <p style="text-align:justify;">
        Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil,
        kami yang bertanda tangan dibawah ini :
    </p>

    {{-- PIHAK PERTAMA & PIHAK KEDUA --}}
    <div class="flex-row">
        {{-- PIHAK PERTAMA --}}
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak1_name"
                value="{{ old('pihak1_name', $perjanjian->pihak1_name) }}" readonly>

            <input type="text" class="input-box" name="pihak1_jabatan"
                value="{{ old('pihak1_jabatan', $perjanjian->pihak1_jabatan) }}" readonly>

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK PERTAMA</b>.
            </p>
        </div>

        {{-- PIHAK KEDUA --}}
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak2_name" id="pihak2_name" 
                   value="{{ old('pihak2_name', $perjanjian->pihak2_name) }}" readonly>

            <input type="text" class="input-box" name="pihak2_jabatan" id="pihak2_jabatan" 
                   value="{{ old('pihak2_jabatan', $perjanjian->pihak2_jabatan) }}" readonly>

            <input type="hidden" name="pihak2_nip" id="pihak2_nip" 
                   value="{{ old('pihak2_nip', $perjanjian->pihak2_nip) }}">

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK KEDUA</b>.
            </p>
        </div>
    </div>

    {{-- LOCATION AND DATE --}}
    <div class="flex-row">
        <div class="flex-col">
            <input type="text" class="input-box" name="location" placeholder="Tempat" 
                   value="{{ old('location', $perjanjian->location ?? 'Pasuruan') }}">
        </div>
        <div class="flex-col">
            <input type="date" class="input-box" name="agreement_date" 
                   value="{{ old('agreement_date', $perjanjian->agreement_date ? \Carbon\Carbon::parse($perjanjian->agreement_date)->format('Y-m-d') : date('Y-m-d')) }}">
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
                value="{{ old('jabatan_pelaksana', $perjanjian->jabatan_pelaksana ?? $perjanjian->pihak1_jabatan) }}"
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
            >{{ old('tugas_pelaksana', $perjanjian->tugas_pelaksana ?? ($jabatanData ? $jabatanData->tugas : '')) }}</textarea>
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
                @php
                    $fungsiValue = old('fungsi_pelaksana', $perjanjian->fungsi_pelaksana ?? null);
                    if (!$fungsiValue && $jabatanData && $jabatanData->fungsi) {
                        $fungsiValue = $jabatanData->fungsi;
                    }
                    
                    // Check if it's JSON string
                    if (is_string($fungsiValue) && (strpos($fungsiValue, '[') === 0 || strpos($fungsiValue, '{') === 0)) {
                        $fungsiValue = json_decode($fungsiValue, true);
                    }
                @endphp
                
                @if($fungsiValue)
                    @if(is_array($fungsiValue))
                        <ol style="margin: 0; padding-left: 20px;">
                            @foreach($fungsiValue as $fungsi)
                                <li style="margin-bottom: 4px;">{{ $fungsi }}</li>
                            @endforeach
                        </ol>
                    @else
                        {{ $fungsiValue }}
                    @endif
                @endif
            </div>
            <input type="hidden" name="fungsi_pelaksana" id="fungsi_pelaksana" 
                   value="{{ is_array($fungsiValue) ? json_encode($fungsiValue) : $fungsiValue }}">
        </div>
    </div>

{{-- TABEL A --}}
<table id="tabelA">
    <thead>
        <tr>
            <th style="width: 40px;">NO</th>
            <th style="flex: 1; min-width: 300px;">SASARAN</th>
            <th style="min-width: 150px;">INDIKATOR KINERJA</th>
            <th style="width: 60px; max-width: 60px;">SATUAN</th>
            <th style="width: 10ch; max-width: 10ch; min-width: 10ch;">TARGET</th>
            <th style="width: 60px;">AKSI</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($tabelA['sasaran']) && count($tabelA['sasaran']) > 0)
            @foreach($tabelA['sasaran'] as $index => $sasaran)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><textarea name="a_sasaran[]" onkeyup="autoExpand(this); syncTabelAToC();">{{ old('a_sasaran.' . $index, $sasaran) }}</textarea></td>
                    <td><textarea name="a_indikator[]" onkeyup="autoExpand(this); syncTabelAToC();">{{ old('a_indikator.' . $index, $tabelA['indikator'][$index] ?? '') }}</textarea></td>
                    <td><textarea name="a_satuan[]" onkeyup="autoExpand(this)">{{ old('a_satuan.' . $index, $tabelA['satuan'][$index] ?? '') }}</textarea></td>
                    <td><textarea name="a_target[]" onkeyup="autoExpand(this); syncTabelAToC();">{{ old('a_target.' . $index, $tabelA['target'][$index] ?? '') }}</textarea></td>
                    <td>
                        <button type="button" class= "table-action-btn delete-btn" onclick="deleteRowTabelA(this)" title="hapus">🗑</button>
                        <button type="button" class="table-action-btn add-btn" onclick="addRow('tabelA')" title="Tambah">➕</button>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>1</td>
                <td><textarea name="a_sasaran[]" onkeyup="autoExpand(this); syncTabelAToC();"></textarea></td>
                <td><textarea name="a_indikator[]" onkeyup="autoExpand(this); syncTabelAToC();"></textarea></td>
                <td><textarea name="a_satuan[]" onkeyup="autoExpand(this)"></textarea></td>
                <td><textarea name="a_target[]" onkeyup="autoExpand(this); syncTabelAToC();"></textarea></td>
                <td>
                    <button type="button" class= "table-action-btn delete-btn" onclick="deleteRowTabelA(this)" title="hapus">🗑</button>
                    <button type="button" class="table-action-btn add-btn" onclick="addRow('tabelA')" title="Tambah">➕</button>
                </td>
            </tr>
        @endif
    </tbody>
</table>

{{-- TABEL PROGRAM & ANGGARAN (DINAMIS) --}}
<h3 style="margin-top: 30px; margin-bottom: 15px; color: #009970; font-weight: 600; border-bottom: 2px solid #009970; padding-bottom: 8px;">
    📊 TABEL PROGRAM & ANGGARAN (Edit disini untuk mengubah program, kegiatan, dan anggaran)
</h3>
<p style="color: #666; margin-bottom: 15px; padding: 10px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
    💡 <strong>Petunjuk:</strong> Klik pada kolom Nama Program/Kegiatan atau Anggaran untuk mengedit. Perubahan akan otomatis tersimpan saat Anda menyimpan form.
</p>
<div style="overflow-x:auto;">
<table id="tabelProgram"
    style="width:100%; min-width:900px; border-collapse:collapse; table-layout:fixed;">

    <thead>
        <tr style="background:#f2f2f2;">
            <th style="width:40px; border:1px solid #000;">NO</th>
            <th style="flex: 1; min-width: 450px; border:1px solid #000;">PROGRAM</th>
            <th style="width:160px; border:1px solid #000;">ANGGARAN (Rp)</th>
            <th style="width:10ch; max-width:10ch; border:1px solid #000;">KET</th>
            <th style="width:70px; border:1px solid #000;">AKSI</th>
        </tr>
    </thead>

    <tbody id="tabelProgramBody">
        {{-- Data will be populated by JavaScript from tabelC --}}
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
    <button type="button" 
            onclick="window.addProgram(); return false;" 
            id="btnAddProgram"
            style="background:#009970; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; font-weight:600;">
        ➕ Tambah Program Baru
    </button>
</div>
</div>

<script>
// Cleanup: hapus fungsi placeholder yang tidak digunakan

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded - existingTabelC:', window.existingTabelC);

        // Normalize existingTabelC if it comes as a JSON string
        if (typeof window.existingTabelC === 'string') {
            try {
                window.existingTabelC = JSON.parse(window.existingTabelC);
            } catch (e) {
                console.error('Failed to parse existingTabelC string', e);
                window.existingTabelC = {};
            }
        }
    
    // Add click handler to button as backup
    const btnAddProgram = document.getElementById('btnAddProgram');
    if (btnAddProgram) {
        console.log('Attaching click handler to button');
        btnAddProgram.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Button clicked via event listener');
            window.addProgram();
        });
    } else {
        console.error('btnAddProgram not found!');
    }
    
    // Make sure tbody exists
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) {
        console.error('tbody #tabelProgramBody not found!');
        return;
    }
    
    console.log('tbody found, current rows:', tbody.querySelectorAll('tr').length);
    console.log('TabelC data:', window.existingTabelC);
    
    // Log TW data untuk debugging
    if (window.existingTabelC && window.existingTabelC.programs) {
        console.log('=== TW DATA FROM DATABASE ===');
        window.existingTabelC.programs.forEach((program, idx) => {
            console.log(`Program ${idx + 1}:`, program.name, 'TW:', {
                tw1: program.tw1,
                tw2: program.tw2,
                tw3: program.tw3,
                tw4: program.tw4
            });
        });
    }
    
    // Initialize with existing data (always render when programs array is present)
    if (window.existingTabelC && Array.isArray(window.existingTabelC.programs) && window.existingTabelC.programs.length > 0) {
        console.log('Rendering existing budget data with', window.existingTabelC.programs.length, 'programs');
        renderExistingBudgetData(window.existingTabelC);
    } else {
        console.log('No existing data or no programs array, adding empty program row...');
        console.log('existingTabelC:', window.existingTabelC);
        window.addProgram();
    }

    // Initial sync - dengan error handling
    try {
        if (typeof syncTabelAToC === 'function') {
            syncTabelAToC();
        } else {
            console.warn('syncTabelAToC not defined yet');
        }
    } catch(e) {
        console.error('Error in syncTabelAToC:', e);
    }
    
    // Calculate total setelah render selesai
    setTimeout(() => {
        try {
            if (typeof bindAnggaranListeners === 'function') bindAnggaranListeners();
            if (window.calculateTotal) {
                window.calculateTotal();
            }
        } catch(e) {
            console.error('Error in calculateTotal:', e);
        }
    }, 100);
    
    // Panggil lagi dengan delay lebih lama untuk memastikan
    setTimeout(() => {
        if (typeof bindAnggaranListeners === 'function') bindAnggaranListeners();
        if (window.calculateTotal) window.calculateTotal();
    }, 500);

    // Fallback: jika tabel program masih kosong padahal ada data, render ulang
    setTimeout(() => {
        const tbodyProgram = document.querySelector('#tabelProgramBody');
        if (
            tbodyProgram &&
            tbodyProgram.querySelectorAll('tr').length === 0 &&
            window.existingTabelC &&
            Array.isArray(window.existingTabelC.programs) &&
            window.existingTabelC.programs.length > 0
        ) {
            console.warn('Program table empty while data exists, re-rendering...');
            renderExistingBudgetData(window.existingTabelC);
            if (typeof syncProgramToTabelD === 'function') {
                syncProgramToTabelD();
            }
        }
    }, 800);
    
    // Sync tabel D setelah tabel program ter-render
    setTimeout(() => {
        if (typeof syncProgramToTabelD === 'function') {
            console.log('Final sync of Tabel D after program table rendered');
            syncProgramToTabelD();
        }
        if (window.calculateTotal) {
            // Recalculate program total to ensure TOTAL ANGGARAN terisi
            window.calculateTotal();
        }
    }, 1000);

    // Jika tetap tidak ada baris program, tambahkan satu baris kosong (paritas dengan create)
    setTimeout(() => {
        const tbodyProgram = document.querySelector('#tabelProgramBody');
        if (tbodyProgram && tbodyProgram.querySelectorAll('tr').length === 0) {
            console.warn('No program rows after init; adding one empty program row for edit page parity');
            window.addProgram();
            if (window.calculateTotal) window.calculateTotal();
        }
    }, 1200);
    
    // Extra fallback: Force recalculate total setelah semua render dan binding selesai
    setTimeout(() => {
        console.log('Final fallback: forcing calculateTotal');
        if (window.calculateTotal) {
            window.calculateTotal();
            const totalEl = document.getElementById('totalAnggaran');
            console.log('Total Anggaran value:', totalEl ? totalEl.textContent : 'element not found');
        }
        // Re-bind jika ada yang terlewat
        if (typeof bindAnggaranListeners === 'function') bindAnggaranListeners();
    }, 1500);
    
    console.log('After initialization, tbody rows:', tbody.querySelectorAll('tr').length);

// Tambahkan event listener untuk window load sebagai backup
window.addEventListener('load', function() {
    console.log('Window loaded, ensuring total is calculated...');
    setTimeout(() => {
        if (window.calculateTotal) {
            window.calculateTotal();
            console.log('Total recalculated on window load');
        }
    }, 300);

    // Fallback kedua: jika tabel program masih kosong padahal ada data, paksa render
    setTimeout(() => {
        const tbodyProgram = document.querySelector('#tabelProgramBody');
        const hasProgramsData = window.existingTabelC && Array.isArray(window.existingTabelC.programs) && window.existingTabelC.programs.length > 0;
        if (tbodyProgram && tbodyProgram.querySelectorAll('tr').length === 0 && hasProgramsData) {
            console.warn('Fallback (window load): forcing renderExistingBudgetData because tbody is empty');
            renderExistingBudgetData(window.existingTabelC);
            if (typeof syncProgramToTabelD === 'function') {
                syncProgramToTabelD();
            }
        }
    }, 500);
});

// Tambahkan observer untuk input anggaran
function observeAnggaranInputs() {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) return;
    
    // Setup mutation observer untuk mendeteksi perubahan di tbody
    const observer = new MutationObserver((mutations) => {
        // Attach event listeners ke semua input anggaran yang baru ditambahkan
        tbody.querySelectorAll('input[name="program_anggaran[]"], input[name="kegiatan_anggaran[]"], input[name="subkegiatan_anggaran[]"]').forEach(input => {
            if (!input.hasAttribute('data-listener-attached')) {
                input.setAttribute('data-listener-attached', 'true');
                input.addEventListener('input', function() {
                    if (window.calculateTotal) {
                        window.calculateTotal();
                    }
                });
            }
        });
    });
    
    observer.observe(tbody, {
        childList: true,
        subtree: true
    });
    
    // Attach ke input yang sudah ada
    tbody.querySelectorAll('input[name="program_anggaran[]"], input[name="kegiatan_anggaran[]"], input[name="subkegiatan_anggaran[]"]').forEach(input => {
        if (!input.hasAttribute('data-listener-attached')) {
            input.setAttribute('data-listener-attached', 'true');
            input.addEventListener('input', function() {
                if (window.calculateTotal) {
                    window.calculateTotal();
                }
            });
        }
    });
}

// Panggil observer setelah delay
setTimeout(observeAnggaranInputs, 1000);
});

function renderExistingBudgetData(tabelC) {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) {
        console.error('tbody not found in renderExistingBudgetData');
        return;
    }
    tbody.innerHTML = '';
    
    if (!tabelC.programs || tabelC.programs.length === 0) {
        console.log('No programs in tabelC, adding empty row');
        addProgram();
        return;
    }
    
    console.log('Rendering', tabelC.programs.length, 'programs');
    let programCounter = 1;
    let rowsAdded = 0;
    
    tabelC.programs.forEach((program, pIdx) => {
        console.log(`Processing program ${pIdx}:`, program);
        
        // Skip completely empty programs
        const hasName = program.name && program.name.trim() !== '';
        const hasAmount = program.amount && parseInt(program.amount) > 0;
        if (!hasName && !hasAmount) {
            console.log(`Skipping empty program at index ${pIdx}`);
            return; // Skip this iteration
        }
        
        // Add program row
        const programRow = document.createElement('tr');
        programRow.classList.add('program-row');
        programRow.dataset.program = programCounter;
        programRow.dataset.level = 'program';
        
        const programAmount = parseInt(program.amount || 0);
        const programName = program.name || '';
        const programSource = program.source || ''; // Load existing keterangan
        
        // Sanitize anggaran - hapus karakter non-angka dari data lama
        const sanitizedAmount = parseInt((program.amount || '0').toString().replace(/[^\d]/g,'')) || 0;
        
        // Build program dropdown options
        let programOptions = '<option value="">-- Pilih Program --</option>';
        let foundMatch = false;
        window.programsData.forEach(prog => {
            const selected = prog.nama_program === programName ? 'selected' : '';
            if (selected) foundMatch = true;
            programOptions += `<option value="${prog.id}" data-nama="${prog.nama_program}" ${selected}>${prog.nama_program}</option>`;
        });
        const customSelected = (programName && !foundMatch) ? 'selected' : '';
        programOptions += `<option value="custom" style="border-top:2px solid #009970; color:#009970; font-weight:600;" ${customSelected}>✏️ Input Manual</option>`;
        
        const isProgramCustom = (programName && !foundMatch);
        
        programRow.innerHTML = `
            <td class="no-col">${programCounter}</td>
            <td>
                <select class="program-select" name="program_id[]" onchange="if(window.handleProgramChange) handleProgramChange(this, ${programCounter})" style="width:95%; padding:4px; border:1px solid #ddd; border-radius:3px; font-weight:600; display:${isProgramCustom ? 'none' : 'block'};">
                    ${programOptions}
                </select>
                <textarea name="program_nama[]" class="program-nama-manual" style="display:${isProgramCustom ? 'block' : 'none'}; width:95%;" onkeyup="if(window.autoExpand) autoExpand(this); if(window.syncProgramToTabelD) syncProgramToTabelD();">${programName}</textarea>
            </td>
            <td><input type="text" name="program_anggaran[]" value="${sanitizedAmount.toLocaleString('id-ID')}" style="text-align:right" onkeypress="return /[0-9]/.test(event.key)" oninput="if(window.forceNumericAnggaran) forceNumericAnggaran(this);" /></td>
            <td><textarea name="program_ket[]" onkeyup="if(window.autoExpand) autoExpand(this)">${programSource}</textarea></td>
            <td>
                <button type="button" class="table-action-btn add-btn" onclick="if(window.addSubRow) window.addSubRow('${programCounter}')" title="Tambah Kegiatan">➕</button>
                <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
            </td>
        `;
        
        tbody.appendChild(programRow);
        
        // Add kegiatan and sub kegiatan
        if (program.kegiatan && program.kegiatan.length > 0) {
            program.kegiatan.forEach((kegiatan, kIdx) => {
                const kegiatanRow = document.createElement('tr');
                kegiatanRow.classList.add('subprogram-row');
                kegiatanRow.dataset.parent = programCounter;
                kegiatanRow.dataset.level = 'kegiatan';
                
                const kegiatanNo = `${programCounter}.${kIdx + 1}`;
                const kegiatanAmount = parseInt((kegiatan.amount || '0').toString().replace(/[^\d]/g,'')) || 0;
                const kegiatanName = kegiatan.name || '';
                const kegiatanSource = kegiatan.source || ''; // Load existing keterangan
                
                // Build kegiatan dropdown options
                let kegiatanOptions = '<option value="">-- Pilih Kegiatan --</option>';
                let kegiatanFoundMatch = false;
                window.kegiatansData.forEach(keg => {
                    const selected = keg.nama_kegiatan === kegiatanName ? 'selected' : '';
                    if (selected) kegiatanFoundMatch = true;
                    kegiatanOptions += `<option value="${keg.id}" data-nama="${keg.nama_kegiatan}" ${selected}>${keg.nama_kegiatan}</option>`;
                });
                const kegiatanCustomSelected = (kegiatanName && !kegiatanFoundMatch) ? 'selected' : '';
                kegiatanOptions += `<option value="custom" style="border-top:2px solid #009970; color:#009970; font-weight:600;" ${kegiatanCustomSelected}>✏️ Input Manual</option>`;
                
                const isKegiatanCustom = (kegiatanName && !kegiatanFoundMatch);
                
                kegiatanRow.innerHTML = `
                    <td class="no-col">${kegiatanNo}</td>
                    <td>
                        <select class="kegiatan-select" name="kegiatan_id[]" onchange="if(window.handleKegiatanChange) handleKegiatanChange(this, '${kegiatanNo}')" style="width:95%; padding:4px; border:1px solid #ddd; border-radius:3px; font-style:italic; display:${isKegiatanCustom ? 'none' : 'block'};">
                            ${kegiatanOptions}
                        </select>
                        <textarea name="kegiatan_nama[]" class="kegiatan-nama-manual" style="display:${isKegiatanCustom ? 'block' : 'none'}; width:95%; padding-left:15px; font-style:italic;" onkeyup="if(window.autoExpand) autoExpand(this); if(window.syncProgramToTabelD) syncProgramToTabelD();">${kegiatanName}</textarea>
                    </td>
                    <td>
                        <input type="text" name="kegiatan_anggaran[]" value="${kegiatanAmount.toLocaleString('id-ID')}" style="text-align:right" onkeypress="return /[0-9]/.test(event.key)"
                            oninput="if(window.forceNumericAnggaran) forceNumericAnggaran(this);">
                    </td>
                    <td><textarea name="kegiatan_ket[]" onkeyup="if(window.autoExpand) autoExpand(this)">${kegiatanSource}</textarea></td>
                    <td>
                        <button type="button" class="table-action-btn add-btn" onclick="if(window.addSubRow) window.addSubRow('${kegiatanNo}')" title="Tambah Sub Kegiatan">➕</button>
                        <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
                    </td>
                `;
                
                tbody.appendChild(kegiatanRow);
                
                // Add sub kegiatan
                if (kegiatan.subKegiatan && kegiatan.subKegiatan.length > 0) {
                    kegiatan.subKegiatan.forEach((subKegiatan, sIdx) => {
                        const subKegiatanRow = document.createElement('tr');
                        subKegiatanRow.classList.add('subprogram-row');
                        subKegiatanRow.dataset.parent = kegiatanNo;
                        subKegiatanRow.dataset.level = 'subkegiatan';
                        
                        const subKegiatanNo = `${kegiatanNo}.${sIdx + 1}`;
                        const subKegiatanAmount = parseInt((subKegiatan.amount || '0').toString().replace(/[^\d]/g,'')) || 0;
                        const subKegiatanName = subKegiatan.name || '';
                        const subKegiatanSource = subKegiatan.source || ''; // Load existing keterangan
                        
                        // Build sub kegiatan dropdown options
                        let subKegiatanOptions = '<option value="">-- Pilih Sub Kegiatan --</option>';
                        let subKegiatanFoundMatch = false;
                        window.subKegiatansData.forEach(subKeg => {
                            const selected = subKeg.nama_sub_kegiatan === subKegiatanName ? 'selected' : '';
                            if (selected) subKegiatanFoundMatch = true;
                            subKegiatanOptions += `<option value="${subKeg.id}" data-nama="${subKeg.nama_sub_kegiatan}" ${selected}>${subKeg.nama_sub_kegiatan}</option>`;
                        });
                        const subKegiatanCustomSelected = (subKegiatanName && !subKegiatanFoundMatch) ? 'selected' : '';
                        subKegiatanOptions += `<option value="custom" style="border-top:2px solid #009970; color:#009970; font-weight:600;" ${subKegiatanCustomSelected}>✏️ Input Manual</option>`;
                        
                        const isSubKegiatanCustom = (subKegiatanName && !subKegiatanFoundMatch);
                        
                        subKegiatanRow.innerHTML = `
                            <td class="no-col">${subKegiatanNo}</td>
                            <td>
                                <select class="subkegiatan-select" name="subkegiatan_id[]" onchange="if(window.handleSubKegiatanChange) handleSubKegiatanChange(this, '${subKegiatanNo}')" style="width:95%; padding:4px; border:1px solid #ddd; border-radius:3px; display:${isSubKegiatanCustom ? 'none' : 'block'};">
                                    ${subKegiatanOptions}
                                </select>
                                <textarea name="subkegiatan_nama[]" class="subkegiatan-nama-manual" style="display:${isSubKegiatanCustom ? 'block' : 'none'}; width:95%; padding-left:30px;" onkeyup="if(window.autoExpand) autoExpand(this); if(window.syncProgramToTabelD) syncProgramToTabelD();">${subKegiatanName}</textarea>
                            </td>
                            <td>
                                <input type="text" name="subkegiatan_anggaran[]" value="${subKegiatanAmount.toLocaleString('id-ID')}" style="text-align:right" onkeypress="return /[0-9]/.test(event.key)"
                                    oninput="if(window.forceNumericAnggaran) forceNumericAnggaran(this);">
                            </td>
                            <td><textarea name="subkegiatan_ket[]" onkeyup="if(window.autoExpand) autoExpand(this)">${subKegiatanSource}</textarea></td>
                            <td>
                                <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
                            </td>
                        `;
                        
                        tbody.appendChild(subKegiatanRow);
                    });
                }
            });
        }
        
        rowsAdded++;
        programCounter++;
    });
    
    console.log(`Finished rendering: ${rowsAdded} programs added to table`);
    
    // If no rows were added (all programs were empty), add one empty row
    if (rowsAdded === 0) {
        console.log('No valid programs rendered, adding empty program row');
        addProgram();
        return;
    }
    
    // Auto-expand all textareas
    tbody.querySelectorAll('textarea').forEach(ta => {
        if (window.autoExpand) autoExpand(ta);
    });
    
    // Bind listeners SEGERA setelah render
    if (typeof bindAnggaranListeners === 'function') bindAnggaranListeners();
    
    // Trigger calculateTotal dan syncProgramToTabelD untuk update tabel 2
    if (window.calculateTotal) {
        calculateTotal();
        console.log('calculateTotal called after render');
    }
    if (window.syncProgramToTabelD) syncProgramToTabelD();
    
    // Bind listeners SETELAH render selesai
    setTimeout(() => {
        if (typeof bindAnggaranListeners === 'function') bindAnggaranListeners();
    }, 100);
    
    // Pastikan total juga dihitung setelah render dengan delay kecil
    setTimeout(() => {
        if (window.calculateTotal) calculateTotal();
        if (window.syncProgramToTabelD) syncProgramToTabelD();
    }, 200);
}

   window.addSubRow = function (parentNo) {

    const parentLevel = parentNo.toString().split('.').length;
    if (parentLevel >= 3) return; 

    const tbody = document.querySelector('#tabelProgramBody');

    const children = Array.from(
        tbody.querySelectorAll(`tr[data-parent="${parentNo}"]`)
    );

    const newIndex = children.length + 1;
    const newNo = `${parentNo}.${newIndex}`;

    const tr = document.createElement('tr');
    tr.classList.add('subprogram-row');
    tr.dataset.parent = parentNo;

    tr.innerHTML = `
        <td class="no-col">${newNo}</td>
        <td><textarea name="${parentLevel === 1 ? 'kegiatan_nama[]' : 'subkegiatan_nama[]'}" onkeyup="autoExpand(this); syncProgramToTabelD()"></textarea></td>
        <td>
            <input type="text" name="${parentLevel === 1 ? 'kegiatan_anggaran[]' : 'subkegiatan_anggaran[]'}" value="0" style="text-align:right"
                oninput="if(window.forceNumericAnggaran) forceNumericAnggaran(this);">
        </td>
        <td><textarea onkeyup="autoExpand(this)"></textarea></td>
        <td>
            ${
                parentLevel < 2
                ? `<button type="button"
                        class="table-action-btn add-btn"
                        onclick="addSubRow('${newNo}')">➕</button>`
                : ''
            }
            <button type="button"
                class="table-action-btn delete-btn"
                onclick="deleteRow(this)">🗑</button>
        </td>
    `;

    let insertAfter = null;
    Array.from(tbody.querySelectorAll('tr')).forEach(row => {
        if (row.querySelector('.no-col')?.textContent.startsWith(parentNo)) {
            insertAfter = row;
        }
    });

    insertAfter.after(tr);
    if (typeof bindAnggaranListeners === 'function') bindAnggaranListeners();
};

    window.deleteRow = function(btn) {
        const row = btn.closest('tr');
        if (!row) return;
        const tbody = row.closest('tbody');
        if (!tbody) return;

        if (row.classList.contains('program-row')) {
            const programNo = row.dataset.program;
            tbody.querySelectorAll(`tr.subprogram-row[data-parent="${programNo}"]`).forEach(sub => sub.remove());
        }
        row.remove();
        updateProgramNumbers();
        calculateTotal();
        syncProgramToTabelD();
    }

    // Fungsi hitung total hanya dari program utama
    window.calculateTotal = function() {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) return;
    let total = 0;
    tbody.querySelectorAll('tr.program-row').forEach(tr => {
        const input = tr.querySelector('input[name="program_anggaran[]"]');
        if (input) {
            const cleaned = (input.value || '').toString().replace(/[^\d]/g,'');
            total += parseInt(cleaned, 10) || 0;
        }
    });
    const totalElement = document.getElementById('totalAnggaran');
    if (totalElement) {
        totalElement.textContent = total.toLocaleString('id-ID');
    }
}; 

    window.formatRupiah = function(input) {
        let value = input.value.replace(/\D/g, ''); // hapus semua selain angka
        if(value) {
            // format menjadi ribuan
            input.value = parseInt(value).toLocaleString('id-ID');
        } else {
            input.value = '';
        }
    }

    // Paksa input anggaran hanya angka & hitung total
    window.forceNumericAnggaran = function(input) {
        if (!input) return;
        const digits = (input.value || '').toString().replace(/[^\d]/g,'');
        input.value = digits ? parseInt(digits, 10).toLocaleString('id-ID') : '';
        // Hanya panggil calculateTotal, syncProgramToTabelD dipanggil dari tempat lain
        if (window.calculateTotal) window.calculateTotal();
    }

    // Attach listener ke semua input anggaran (program, kegiatan, subkegiatan)
    window.bindAnggaranListeners = function() {
        console.log('bindAnggaranListeners called');
        const inputs = document.querySelectorAll('input[name="program_anggaran[]"], input[name="kegiatan_anggaran[]"], input[name="subkegiatan_anggaran[]"]');
        console.log('Found', inputs.length, 'anggaran inputs');
        
        inputs.forEach(inp => {
            if (inp.dataset.numericBound) {
                console.log('Input already bound, skipping');
                return;
            }
            inp.dataset.numericBound = '1';
            
            // Bind both input and change events untuk memastikan
            inp.addEventListener('input', () => {
                console.log('Input event on anggaran:', inp.value);
                window.forceNumericAnggaran(inp);
                if (window.calculateTotal) window.calculateTotal();
            });
            
            inp.addEventListener('change', () => {
                console.log('Change event on anggaran:', inp.value);
                if (window.calculateTotal) window.calculateTotal();
            });
            
            // Sanitize nilai awal dan hitung total
            window.forceNumericAnggaran(inp);
        });
        
        // Hitung total setelah binding
        if (window.calculateTotal) window.calculateTotal();
    }

    // Jalankan saat load awal - tapi jangan langsung, tunggu DOMContentLoaded
    // bindAnggaranListeners();
    // Jalankan ulang setelah DOMContentLoaded untuk jaga-jaga
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOMContentLoaded - binding anggaran listeners');
        if (window.bindAnggaranListeners) window.bindAnggaranListeners();
        if (window.calculateTotal) window.calculateTotal();
    });

    // Setup MutationObserver untuk auto-bind input baru
    setTimeout(() => {
        const tbody = document.querySelector('#tabelProgramBody');
        if (tbody) {
            const observer = new MutationObserver(() => {
                if (window.bindAnggaranListeners) window.bindAnggaranListeners();
                if (window.calculateTotal) window.calculateTotal();
            });
            observer.observe(tbody, { childList: true, subtree: true });
        }
    }, 100);

    // Panggil calculateTotal setelah window load
    window.addEventListener('load', () => {
        setTimeout(() => {
            if (window.bindAnggaranListeners) window.bindAnggaranListeners();
            if (window.calculateTotal) window.calculateTotal();
        }, 300);
    });
    </script>

{{-- TABEL C --}}
<h3 style="margin-top: 30px; margin-bottom: 15px; color: #009970; font-weight: 600; border-bottom: 2px solid #009970; padding-bottom: 8px;">
    📅 RENCANA AKSI DENGAN TARGET TRIWULAN
</h3>
<table id="tabel2" style="table-layout: fixed;">
    <thead>
        <tr>
            <th rowspan="2" style="width: 25%;">SASARAN</th>
            <th rowspan="2" style="width: 20%;">Indikator Kinerja</th>
            <th rowspan="2" style="width: 10ch; max-width:10ch;">Target</th>
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
        @if(!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0)
            @foreach($tabelB['sasaran'] as $index => $sasaran)
                <tr>
                    <td style="width: 25%;"><textarea name="c_sasaran[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">{{ old('c_sasaran.' . $index, $sasaran) }}</textarea></td>
                    <td style="width: 20%;"><textarea name="c_indikator[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">{{ old('c_indikator.' . $index, $tabelB['indikator'][$index] ?? '') }}</textarea></td>
                    <td style="width: 10ch; max-width:10ch;"><textarea name="c_target[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="autoExpand(this)">{{ old('c_target.' . $index, $tabelB['target'][$index] ?? '') }}</textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw1[]" onkeyup="autoExpand(this)">{{ old('c_tw1.' . $index, $tabelB['tw1'][$index] ?? '') }}</textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw2[]" onkeyup="autoExpand(this)">{{ old('c_tw2.' . $index, $tabelB['tw2'][$index] ?? '') }}</textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw3[]" onkeyup="autoExpand(this)">{{ old('c_tw3.' . $index, $tabelB['tw3'][$index] ?? '') }}</textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw4[]" onkeyup="autoExpand(this)">{{ old('c_tw4.' . $index, $tabelB['tw4'][$index] ?? '') }}</textarea></td>
                    <td style="width: 60px;">
                        <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this, 'tabel2')" title="Hapus" style="visibility: hidden;">🗑</button>
                        <button type="button" class="table-action-btn add-btn" onclick="addRow('tabel2')" title="Tambah" style="visibility: hidden;">➕</button>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{{-- TABEL D: HIERARCHICAL BUDGET (PROGRAM) --}}
<h3 style="margin-top: 30px; margin-bottom: 10px; color: #009970; font-weight: 600; border-bottom: 2px solid #009970; padding-bottom: 8px;">
    💰 RENCANA ANGGARAN DAN TARGET PER TRIWULAN
</h3>
<p style="color: #666; margin-bottom: 15px; padding: 10px; background: #d1ecf1; border-left: 4px solid #0dcaf0; border-radius: 4px;">
    ⚠️ <strong>Catatan:</strong> Nama program dan anggaran otomatis tersinkronisasi dari tabel di atas. <strong style="color:#009970;">Edit target triwulan (TW I-IV) dengan mengetik angka pada kotak input.</strong>
</p>
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
window.syncProgramToTabelD = function() {
    console.log('=== syncProgramToTabelD called ===');
    const tbodyD = document.getElementById('hierarchical-budget-tbody');

    // Load TW data dari database (existingTabelC) TERLEBIH DAHULU - PRIORITAS UTAMA
    const existingTW = {};
    
    if (window.existingTabelC && window.existingTabelC.programs) {
        console.log('Loading TW data from existingTabelC:', window.existingTabelC.programs.length, 'programs');
        window.existingTabelC.programs.forEach((program, pIdx) => {
            const programNo = pIdx + 1;
            const pKey = `program-${programNo}`;
            
            // Load program TW data dari database - PRIORITAS TINGGI
            if (program.tw1 !== undefined || program.tw2 !== undefined || program.tw3 !== undefined || program.tw4 !== undefined) {
                existingTW[pKey] = {
                    tw1: program.tw1 || '',
                    tw2: program.tw2 || '',
                    tw3: program.tw3 || '',
                    tw4: program.tw4 || '',
                };
                console.log(`Loaded program ${programNo} TW from DB:`, existingTW[pKey]);
            }
            
            // Load kegiatan TW data
            if (program.kegiatan && Array.isArray(program.kegiatan)) {
                program.kegiatan.forEach((kegiatan, kIdx) => {
                    const kegiatanNo = `${programNo}.${kIdx + 1}`;
                    const kKey = `sub-${kegiatanNo}`;
                    
                    if (kegiatan.tw1 !== undefined || kegiatan.tw2 !== undefined || kegiatan.tw3 !== undefined || kegiatan.tw4 !== undefined) {
                        existingTW[kKey] = {
                            tw1: kegiatan.tw1 || '',
                            tw2: kegiatan.tw2 || '',
                            tw3: kegiatan.tw3 || '',
                            tw4: kegiatan.tw4 || '',
                        };
                        console.log(`Loaded kegiatan ${kegiatanNo} TW from DB:`, existingTW[kKey]);
                    }
                    
                    // Load sub kegiatan TW data
                    if (kegiatan.subKegiatan && Array.isArray(kegiatan.subKegiatan)) {
                        kegiatan.subKegiatan.forEach((sub, sIdx) => {
                            const subKegiatanNo = `${kegiatanNo}.${sIdx + 1}`;
                            const sKey = `sub-${subKegiatanNo}`;
                            
                            if (sub.tw1 !== undefined || sub.tw2 !== undefined || sub.tw3 !== undefined || sub.tw4 !== undefined) {
                                existingTW[sKey] = {
                                    tw1: sub.tw1 || '',
                                    tw2: sub.tw2 || '',
                                    tw3: sub.tw3 || '',
                                    tw4: sub.tw4 || '',
                                };
                                console.log(`Loaded sub-kegiatan ${subKegiatanNo} TW from DB:`, existingTW[sKey]);
                            }
                        });
                    }
                });
            }
        });
    }
    
    // Kemudian override dengan TW yang sudah di-input user (jika ada perubahan manual)
    tbodyD.querySelectorAll('tr').forEach(tr => {
        if (!tr.dataset.key) return;
        const currentTW = {
            tw1: tr.querySelector('.tw1')?.value || '',
            tw2: tr.querySelector('.tw2')?.value || '',
            tw3: tr.querySelector('.tw3')?.value || '',
            tw4: tr.querySelector('.tw4')?.value || '',
        };
        
        // Hanya override jika ada nilai yang sudah diinput (tidak kosong semua)
        if (currentTW.tw1 || currentTW.tw2 || currentTW.tw3 || currentTW.tw4) {
            // Cek apakah ini perubahan dari user atau data awal dari database
            const dbData = existingTW[tr.dataset.key];
            if (dbData && (currentTW.tw1 !== dbData.tw1 || currentTW.tw2 !== dbData.tw2 || currentTW.tw3 !== dbData.tw3 || currentTW.tw4 !== dbData.tw4)) {
                // Ada perubahan manual dari user, gunakan nilai user
                existingTW[tr.dataset.key] = currentTW;
                console.log(`Override ${tr.dataset.key} with user input:`, currentTW);
            }
        }
    });

    tbodyD.innerHTML = '';

    const tbodyProgram = document.querySelector('#tabelProgramBody');
    const rows = tbodyProgram.querySelectorAll('tr');
    
    console.log(`Found ${rows.length} rows in tabelProgramBody`);

    let totalAnggaran = 0;
    let totalTW1 = 0, totalTW2 = 0, totalTW3 = 0, totalTW4 = 0;

    rows.forEach((row, idx) => {
        const isProgram = row.classList.contains('program-row');
        const isSub = row.classList.contains('subprogram-row');
        if (!isProgram && !isSub) return;

        const no = row.querySelector('.no-col')?.textContent || '';
        console.log(`Row ${idx}: no="${no}", isProgram=${isProgram}, isSub=${isSub}`);
        
        // Get nama dari textarea manual input
        let nama = '';
        if (isProgram) {
            const textarea = row.querySelector('.program-nama-manual');
            nama = textarea ? textarea.value : '';
        } else {
            // Sub-program (kegiatan or sub kegiatan)
            const dataLevel = row.dataset.level;
            const textarea = row.querySelector(`.${dataLevel}-nama-manual`);
            nama = textarea ? textarea.value : '';
        }

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
                    oninput="validateNumericInput(this); hitungTotalTW()">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw2" type="text" value="${tw.tw2 || ''}"
                    oninput="validateNumericInput(this); hitungTotalTW()">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw3" type="text" value="${tw.tw3 || ''}"
                    oninput="validateNumericInput(this); hitungTotalTW()">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw4" type="text" value="${tw.tw4 || ''}"
                    oninput="validateNumericInput(this); hitungTotalTW()">
            </td>
            <td style="border:1px solid #000; text-align:center;">-</td>
        `;

        tbodyD.appendChild(tr);
    });

    document.getElementById('totalAnggaranD').textContent =
        totalAnggaran.toLocaleString('id-ID');

    hitungTotalTW();
}

// 🔹 HITUNG TOTAL TRIWULAN
function hitungTotalTW() {
    let t1 = 0, t2 = 0, t3 = 0, t4 = 0;

    document.querySelectorAll('#hierarchical-budget-tbody tr').forEach(tr => {

        if (!tr.dataset.key || !tr.dataset.key.startsWith('program-')) {
            return;
        }

        t1 += parseInt(tr.querySelector('.tw1')?.value.replace(/[^\d]/g,'') || 0);
        t2 += parseInt(tr.querySelector('.tw2')?.value.replace(/[^\d]/g,'') || 0);
        t3 += parseInt(tr.querySelector('.tw3')?.value.replace(/[^\d]/g,'') || 0);
        t4 += parseInt(tr.querySelector('.tw4')?.value.replace(/[^\d]/g,'') || 0);
    });

    document.getElementById('totalTW1').textContent = t1.toLocaleString('id-ID');
    document.getElementById('totalTW2').textContent = t2.toLocaleString('id-ID');
    document.getElementById('totalTW3').textContent = t3.toLocaleString('id-ID');
    document.getElementById('totalTW4').textContent = t4.toLocaleString('id-ID');
}
</script>

<!-- Hidden inputs for form submission (will store hierarchical data) -->
<input type="hidden" id="hierarchical-budget-json" name="hierarchical_budget_json" value="[]">

    {{-- BUTTON --}}
    <div style="margin-top:25px; text-align:right; display:flex; gap:10px; justify-content:flex-end;">
    <button class="save-btn" type="submit" onclick="saveToSupabase(event)" style="background:#009970;">
        💾 UPDATE
    </button>
</div>
</form>
    <script>
        // Pass existing data to JavaScript
        window.existingTabelC = @json($tabelC);
        window.pihak1Jabatan = @json($perjanjian->pihak1_jabatan);
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
    window.autoExpand = function(textarea) {
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
    const existingSasaran = [];
    const existingIndikator = [];
    const existingTarget = [];
    
    existingRows.forEach(row => {
        const sasaranInput = row.querySelector('textarea[name="c_sasaran[]"]');
        const indikatorInput = row.querySelector('textarea[name="c_indikator[]"]');
        const targetInput = row.querySelector('textarea[name="c_target[]"]');
        const tw1Input = row.querySelector('textarea[name="c_tw1[]"]');
        const tw2Input = row.querySelector('textarea[name="c_tw2[]"]');
        const tw3Input = row.querySelector('textarea[name="c_tw3[]"]');
        const tw4Input = row.querySelector('textarea[name="c_tw4[]"]');
        
        existingSasaran.push(sasaranInput ? sasaranInput.value : '');
        existingIndikator.push(indikatorInput ? indikatorInput.value : '');
        existingTarget.push(targetInput ? targetInput.value : '');
        
        existingData.push({
            tw1: tw1Input ? tw1Input.value : '',
            tw2: tw2Input ? tw2Input.value : '',
            tw3: tw3Input ? tw3Input.value : '',
            tw4: tw4Input ? tw4Input.value : ''
        });
    });
    
    // Copy semua baris dari tabel A ke tabel 2 (tabel C)
    const rowsA = tbodyA.querySelectorAll('tr');
    
    // Jika tabel A kosong, gunakan data yang sudah ada (dari server/database)
    if (rowsA.length === 0) {
        // Jika tidak ada data yang existing, jangan clear dan jangan lakukan apa-apa
        if (existingData.length === 0) {
            return;
        }
        // Jika ada data existing, restore dari server data (sudah ada di HTML)
        // Jangan clear tbody2, biarkan baris-baris yang sudah ada tetap ditampilkan
        return;
    }
    
    // Clear tabel2 tbody hanya jika tabel A tidak kosong
    tbody2.innerHTML = '';
    
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

function calculateTotalBudget() {
    let total = 0;

    document.querySelectorAll('.budget-input').forEach(input => {
        const val = (input.value || '').replace(/[^\d]/g, '');
        total += parseInt(val || 0);
    });

    const totalEl = document.getElementById('totalAnggaran');
    if (totalEl) {
        totalEl.innerText = total.toLocaleString('id-ID');
    }
}

// Alias untuk kompatibilitas script lama
window.calculateTotal = calculateTotalBudget;

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

window.syncTabelAToC = syncTabelAToTabel3;

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
    const tbody = document.getElementById('tabelProgramBody');
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
        
        // Ambil nilai dari textarea
        const nameTextarea = row.querySelector('textarea[name*="_nama"]');
        const name = nameTextarea?.value || '';
        
        // Ambil anggaran dari input pertama (program/kegiatan)
        const anggaranInput = row.querySelector('input[name*="_anggaran"]');
        const amount = (anggaranInput?.value || '0').replace(/[^\d]/g, '');
        
        // Ambil keterangan dari textarea
        const ketTextarea = row.querySelector('textarea[name*="_ket"]');
        const ket = ketTextarea?.value || '';
        
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

    const amountInput = document.querySelector(
        `input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_amount"]`
    );
    const anggaran = parseAmount(amountInput?.value || '0');

    if (anggaran === 0) {
        alert('Anggaran Sub Kegiatan belum diisi!');
        return;
    }

    const dialog = document.getElementById('autoFillDialog');
    if (!dialog) return;

    const dialogBox = dialog.querySelector('.dialog-box');
    const methodSelect = dialog.querySelector('#methodSelect');
    const inputFieldsDiv = dialog.querySelector('#inputFields');
    const applyBtn = dialog.querySelector('#applyAutoFill');

    // =========================
    // METHOD CHANGE (RESET EVENT)
    // =========================
    methodSelect.onchange = function () {
        const method = this.value;

        if (method === 'equal') {
            inputFieldsDiv.innerHTML =
                '<p style="font-size:12px;color:#666;font-style:italic;">Setiap triwulan akan diisi 25% dari total anggaran</p>';

        } else if (method === 'percentage') {
            inputFieldsDiv.innerHTML = `...`;
        } else if (method === 'rupiah') {
            inputFieldsDiv.innerHTML = `...`;

            inputFieldsDiv.querySelectorAll('input[id^="rp"]').forEach(inp => {
                inp.oninput = function () {
                    this.value = formatNumberInput(this.value);
                };
            });
        }
    };

    // Trigger awal
    methodSelect.onchange();

    // =========================
    // APPLY BUTTON (RESET EVENT)
    // =========================
    applyBtn.onclick = function () {
        let tw1, tw2, tw3, tw4;
        const method = methodSelect.value;

        if (method === 'equal') {
            const q = Math.floor(anggaran / 4);
            tw1 = tw2 = tw3 = tw4 = q;
        }
        else if (method === 'percentage') {
            const p1 = +dialogBox.querySelector('#pct1').value || 0;
            const p2 = +dialogBox.querySelector('#pct2').value || 0;
            const p3 = +dialogBox.querySelector('#pct3').value || 0;
            const p4 = +dialogBox.querySelector('#pct4').value || 0;

            if (p1 + p2 + p3 + p4 !== 100) {
                alert('Total persentase harus 100%');
                return;
            }

            tw1 = Math.floor(anggaran * p1 / 100);
            tw2 = Math.floor(anggaran * p2 / 100);
            tw3 = Math.floor(anggaran * p3 / 100);
            tw4 = Math.floor(anggaran * p4 / 100);
        }
        else {
            tw1 = parseAmount(dialogBox.querySelector('#rp1').value);
            tw2 = parseAmount(dialogBox.querySelector('#rp2').value);
            tw3 = parseAmount(dialogBox.querySelector('#rp3').value);
            tw4 = parseAmount(dialogBox.querySelector('#rp4').value);

            if (tw1 + tw2 + tw3 + tw4 > anggaran) {
                alert('Total rupiah melebihi anggaran');
                return;
            }
        }

        document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw1"]`).value = formatNumberInput(tw1.toString());
        document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw2"]`).value = formatNumberInput(tw2.toString());
        document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw3"]`).value = formatNumberInput(tw3.toString());
        document.querySelector(`input[name="hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_tw4"]`).value = formatNumberInput(tw4.toString());

        validateTriwulanSum(pIdx, kIdx, sIdx);
        dialog.remove();
    };
}

// =========================
// VALIDASI TTD SAAT SUBMIT
// =========================
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector("form")?.addEventListener("submit", function (e) {
        const ttdPihak1 = @json(auth()->user()->tanda_tangan);

        if (!ttdPihak1) {
            e.preventDefault();
            document.getElementById("popupTTDKosong").style.display = "flex";
        }
    });
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

@endsection
