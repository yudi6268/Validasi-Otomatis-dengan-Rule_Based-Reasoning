

<?php $__env->startSection('title', 'Edit Perjanjian'); ?>

<?php $__env->startSection('back'); ?>
<a href="<?php echo e(route('perjanjian.index')); ?>" target="_self" style="text-decoration:none; color:#009970; font-size:20px;">
    <i class="fa-solid fa-arrow-left"></i>
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_title', 'Edit Perjanjian'); ?>

<?php $__env->startSection('content'); ?>


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

<?php
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
?>

<script>
// DEFINE ALL FUNCTIONS AT THE TOP - BEFORE FORM
console.log('=== GLOBAL FUNCTIONS LOADING ===');

// Pass data to JavaScript FIRST
window.existingTabelC = <?php echo json_encode($tabelC, 15, 512) ?>;
window.pihak1Jabatan = <?php echo json_encode($perjanjian->pihak1_jabatan, 15, 512) ?>;
window.programsData = <?php echo json_encode($programs, 15, 512) ?>;
window.kegiatansData = <?php echo json_encode($kegiatans, 15, 512) ?>;
window.subKegiatansData = <?php echo json_encode($subKegiatans, 15, 512) ?>;

console.log('=== DATA LOADED ===');
console.log('existingTabelC:', window.existingTabelC);
console.log('programsData:', window.programsData);
console.log('Data loaded:', window.existingTabelC);
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
    if (window.syncProgramToTabelD) window.syncProgramToTabelD();
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
    if (window.calculateHierarchicalTotal) window.calculateHierarchicalTotal();
    if (window.syncProgramToTabelD) window.syncProgramToTabelD();
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

// Fungsi helper untuk menambah kegiatan otomatis
window.addAutoKegiatan = function(parentNo, kegiatanNo, kegiatanData) {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) return;
    
    const tr = document.createElement('tr');
    tr.classList.add('subprogram-row');
    tr.dataset.parent = parentNo;
    tr.dataset.level = 'kegiatan';
    
    tr.innerHTML = `
        <td class="no-col">${kegiatanNo}</td>
        <td>
            <select name="kegiatan_nama[]" class="kegiatan-nama-dropdown" style="width:95%; padding:8px; font-style:italic;" required>
                <option value="${kegiatanData.nama_kegiatan}" selected>${kegiatanData.nama_kegiatan}</option>
            </select>
        </td>
        <td><input type="text" name="kegiatan_anggaran[]" value="0" readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari sub kegiatan"></td>
        <td><textarea name="kegiatan_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;"></textarea></td>
        <td>
            <button type="button" class="table-action-btn add-btn" onclick="if(window.addSubRow) window.addSubRow('${kegiatanNo}')" title="Tambah Sub Kegiatan">➕</button>
            <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;
    
    // Insert setelah parent row atau setelah sibling terakhir
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    const parentRow = allRows.find(r => r.querySelector('.no-col')?.textContent?.trim() === parentNo);
    
    if (parentRow) {
        let insertAfter = parentRow;
        
        // Cari sibling terakhir dengan parent yang sama
        for (let i = allRows.indexOf(parentRow) + 1; i < allRows.length; i++) {
            if (allRows[i].dataset.parent === parentNo) {
                insertAfter = allRows[i];
            } else {
                break;
            }
        }
        
        insertAfter.after(tr);
    }
}

// Fungsi helper untuk menambah sub kegiatan otomatis
window.addAutoSubKegiatan = function(parentNo, subNo, subKegiatanData) {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) return;
    
    const tr = document.createElement('tr');
    tr.classList.add('subprogram-row');
    tr.dataset.parent = parentNo;
    tr.dataset.level = 'subkegiatan';
    
    tr.innerHTML = `
        <td class="no-col">${subNo}</td>
        <td>
            <select name="subkegiatan_nama[]" class="subkegiatan-nama-dropdown" style="width:95%; padding:8px;" required>
                <option value="${subKegiatanData.nama_sub_kegiatan}" selected>${subKegiatanData.nama_sub_kegiatan}</option>
            </select>
        </td>
        <td><input type="text" name="subkegiatan_anggaran[]" value="0" style="text-align:right" oninput="if(window.formatRupiah) window.formatRupiah(this); if(window.calculateHierarchicalTotal) window.calculateHierarchicalTotal(); if(window.syncProgramToTabelD) window.syncProgramToTabelD();"></td>
        <td>
            <select name="subkegiatan_ket[]" style="width:95%; padding:6px;" onchange="if(window.updateHierarchicalKet) window.updateHierarchicalKet(); if(window.syncProgramToTabelD) window.syncProgramToTabelD();">
                <option value="">-- Pilih --</option>
                <option value="APBD">APBD</option>
                <option value="BLUD">BLUD</option>
            </select>
        </td>
        <td>
            <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow) window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;
    
    // Insert setelah parent row atau setelah sibling terakhir
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    const parentRow = allRows.find(r => r.querySelector('.no-col')?.textContent?.trim() === parentNo);
    
    if (parentRow) {
        let insertAfter = parentRow;
        
        // Cari sibling terakhir dengan parent yang sama
        for (let i = allRows.indexOf(parentRow) + 1; i < allRows.length; i++) {
            if (allRows[i].dataset.parent === parentNo) {
                insertAfter = allRows[i];
            } else {
                break;
            }
        }
        
        insertAfter.after(tr);
    }
}

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

    // Generate dropdown options dari data program
    let programOptions = '<option value="">-- Pilih Program --</option>';
    window.programsData.forEach(prog => {
        programOptions += `<option value="${prog.nama_program}" data-kode="${prog.kode_program}" data-program-id="${prog.id}">${prog.nama_program}</option>`;
    });

    tr.innerHTML = `
        <td class="no-col">${newProgramNo}</td>
        <td>
            <select name="program_nama[]" class="program-nama-dropdown" style="width:95%; font-weight:600; padding:8px;" onchange="window.handleProgramChange(this)" required>
                ${programOptions}
            </select>
        </td>
        <td><input type="text" name="program_anggaran[]" value="0" readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari kegiatan" /></td>
        <td><textarea name="program_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;"></textarea></td>
        <td>
            <button type="button" class="table-action-btn add-btn" onclick="window.addSubRow('${newProgramNo}', 'kegiatan')" title="Tambah Kegiatan">➕</button>
            <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
        </td>
    `;

    tbody.appendChild(tr);
    console.log('Program row added successfully');

    if (typeof window.bindAnggaranListeners === 'function') {
        window.bindAnggaranListeners();
        console.log('Listeners bound after addProgram');
    }
    if (window.syncProgramToTabelD) {
        window.syncProgramToTabelD();
    }
    
    return tr;
}
console.log('✓ window.addProgram defined');
    
// Add Sub Row (Kegiatan/Sub-Kegiatan)
window.addSubRow = function(parentNo, dataLevel) {
    console.log('addSubRow called for parent:', parentNo, 'level:', dataLevel);
    const parentNoStr = parentNo.toString();
    const parentLevel = parentNoStr.split('.').length;
    
    // Auto-detect level jika tidak diberikan
    if (!dataLevel) {
        dataLevel = parentLevel === 1 ? 'kegiatan' : 'subkegiatan';
    }
    
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

    const tr = document.createElement('tr');
    tr.classList.add('subprogram-row');
    tr.dataset.parent = parentNoStr;
    tr.dataset.level = dataLevel;

    // Generate dropdown options berdasarkan level (akan di-filter saat onfocus)
    let dropdownOptions = '';
    let onchangeHandler = '';
    let onfocusHandler = '';
    
    if (dataLevel === 'kegiatan') {
        // Ini level kegiatan
        dropdownOptions = '<option value="">-- Pilih Kegiatan --</option>';
        if (window.kegiatansData && Array.isArray(window.kegiatansData)) {
            window.kegiatansData.forEach(keg => {
                dropdownOptions += `<option value="${keg.nama_kegiatan}" data-kode="${keg.kode_kegiatan}">${keg.nama_kegiatan}</option>`;
            });
        }
        onchangeHandler = 'window.handleKegiatanChange(this)';
        onfocusHandler = 'window.filterKegiatanByProgram(this)';
    } else {
        // Ini level sub-kegiatan
        dropdownOptions = '<option value="">-- Pilih Sub Kegiatan --</option>';
        if (window.subKegiatansData && Array.isArray(window.subKegiatansData)) {
            window.subKegiatansData.forEach(subKeg => {
                dropdownOptions += `<option value="${subKeg.nama_sub_kegiatan}" data-kode="${subKeg.kode_sub_kegiatan}">${subKeg.nama_sub_kegiatan}</option>`;
            });
        }
        onfocusHandler = 'window.filterSubKegiatanByKegiatan(this)';
    }

    const inputStyle = dataLevel === 'kegiatan' ? 'font-style:italic;' : '';
    const paddingLeft = '5px';

    const ketCellHtml = dataLevel === 'kegiatan'
        ? `<textarea name="${dataLevel}_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;"></textarea>`
        : `<select name="${dataLevel}_ket[]" style="width:95%; padding:6px;" onchange="window.updateHierarchicalKet(); window.syncProgramToTabelD();">
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
        <td><input type="text" name="${dataLevel}_anggaran[]" value="0" ${dataLevel === 'kegiatan' ? 'readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari sub kegiatan"' : 'style="text-align:right" oninput="window.formatRupiah(this); window.calculateHierarchicalTotal(); window.syncProgramToTabelD();"'}></td>
        <td>${ketCellHtml}</td>
        <td>
            ${dataLevel === 'kegiatan' ? `<button type="button" class="table-action-btn add-btn" onclick="window.addSubRow('${newNo}', 'subkegiatan')" title="Tambah Sub Kegiatan">➕</button>` : ''}
            <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
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
            window.renumberAllRows();
        }
    }
    
    if (window.calculateTotal) window.calculateTotal();
    if (window.syncProgramToTabelD) window.syncProgramToTabelD();
    
    console.log('Delete completed');
}
console.log('✓ window.deleteRow defined');

// Add Row (untuk Tabel A dan Tabel lainnya)
window.addRow = function(tableId) {
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
            newDropdown.value = savedDropdownValue;
            // Force set selected attribute
            const selectedOption = newDropdown.querySelector(`option[value="${savedDropdownValue}"]`);
            if (selectedOption) {
                selectedOption.selected = true;
            }
        }
    }
    
    // Update nomor urut (hanya kolom pertama)
    if (newRow.cells[0]) {
        newRow.cells[0].innerText = tbody.rows.length + 1;
    }
    
    tbody.appendChild(newRow);
    
    // Auto-expand textareas in new row
    if (typeof window.autoExpand === 'function') {
        newRow.querySelectorAll('textarea').forEach(ta => window.autoExpand(ta));
    }
    
    // Sync data if needed
    if (tableId === 'tabelA') {
        if (typeof window.syncTabelAAll === 'function') {
            window.syncTabelAAll();
        } else if (typeof window.syncTabelAToC === 'function') {
            window.syncTabelAToC();
        }
    }
}
console.log('✓ window.addRow defined');

// Delete Row Tabel A
window.deleteRowTabelA = function(btn) {
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

    if (typeof window.syncTabelAAll === 'function') {
        window.syncTabelAAll();
    } else if (typeof window.syncTabelAToC === 'function') {
        window.syncTabelAToC();
    }
}
console.log('✓ window.deleteRowTabelA defined');

window.setIndicatorType = function(btn, type) {
    const row = btn.closest('tr');
    if (!row) return;

    const hiddenType = row.querySelector('input[name="a_indicator_type[]"]');
    if (hiddenType) hiddenType.value = type;

    const plusBtn = row.querySelector('.indicator-plus');
    const minusBtn = row.querySelector('.indicator-minus');
    if (plusBtn) plusBtn.classList.toggle('active', type === 'positif');
    if (minusBtn) minusBtn.classList.toggle('active', type === 'negatif');

    if (typeof window.syncTabelAAll === 'function') {
        window.syncTabelAAll();
    } else if (typeof window.syncTabelAToC === 'function') {
        window.syncTabelAToC();
    }
}
console.log('✓ window.setIndicatorType defined');

// Auto Expand Textarea
window.autoExpand = function(textarea) {
    if (!textarea) return;
    textarea.style.height = 'auto';
    textarea.style.height = Math.min(textarea.scrollHeight, 150) + 'px';
}
console.log('✓ window.autoExpand defined');

// Fungsi untuk renumber semua rows
// Fungsi untuk renumber semua rows dengan benar
window.renumberAllRows = function() {
    const tbody = document.querySelector('#tabelProgramBody');
    if (!tbody) return;
    
    console.log('=== RENUMBER START (EDIT) ===');
    
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
    
    console.log('=== RENUMBER END (EDIT) ===');
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

// Sync Tabel A ke Tabel C (Target Triwulan)
window.syncTabelAToC = function() {
    const tabelA = document.getElementById('tabelA');
    const tabel2 = document.getElementById('tabel2');
    
    if (!tabelA || !tabel2) {
        console.warn('syncTabelAToC: tabelA or tabel2 not found');
        return;
    }
    
    const tbodyA = tabelA.querySelector('tbody');
    const tbody2 = tabel2.querySelector('tbody');
    
    if (!tbodyA || !tbody2) {
        console.warn('syncTabelAToC: tbody not found');
        return;
    }
    
    console.log('=== syncTabelAToC START ===');
    
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
    
    console.log('Existing TW data saved:', existingData.length, 'rows');
    
    // Copy semua baris dari tabel A ke tabel 2 (tabel C)
    const rowsA = tbodyA.querySelectorAll('tr');
    
    // Jika tabel A kosong, jangan lakukan apa-apa
    if (rowsA.length === 0) {
        console.log('Tabel A empty, skipping sync');
        return;
    }
    
    // Clear tabel2 tbody
    tbody2.innerHTML = '';
    console.log('Cleared tabel2, syncing', rowsA.length, 'rows from tabelA');
    
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
        const existingIndicatorType = existingData[index]?.indicatorType || indicatorType;
        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td style="width: 25%;"><textarea name="c_sasaran[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)">${sasaran}</textarea></td>
            <td style="width: 20%;"><textarea name="c_indikator[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)">${indikator}</textarea></td>
            <td style="width: 12%;">
                <input type="hidden" name="c_indicator_type[]" value="${existingIndicatorType}">
                <textarea name="c_target[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)">${target}</textarea>
            </td>
            <td style="width: 10%;"><textarea name="c_tw1[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw1}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw2[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw2}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw3[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw3}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw4[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw4}</textarea></td>
            <td style="width: 60px;">
                <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow)window.deleteRow(this, 'tabel2')" title="Hapus" style="visibility: hidden;">🗑</button>
                <button type="button" class="table-action-btn add-btn" onclick="if(window.addRow)window.addRow('tabel2')" title="Tambah" style="visibility: hidden;">➕</button>
            </td>
        `;
        tbody2.appendChild(newRow);
        
        // Auto expand all textareas
        newRow.querySelectorAll('textarea').forEach(ta => {
            if (ta && window.autoExpand) window.autoExpand(ta);
        });
    });
    
    console.log('=== syncTabelAToC END - synced', rowsA.length, 'rows ===');
}

// Sync Tabel A All (memanggil semua sync)
window.syncTabelAAll = function() {
    console.log('=== syncTabelAAll called ===');
    try {
        window.syncTabelAToC();
    } catch (e) {
        console.error('Sync tabel A -> tabel C gagal:', e);
    }
}
console.log('✓ window.syncTabelAToC defined');
console.log('✓ window.syncTabelAAll defined');

console.log('=== GLOBAL FUNCTIONS LOADED ===');
console.log('window.addProgram:', typeof window.addProgram);
console.log('window.testAddRow:', typeof window.testAddRow);
</script>

<div class="paper">
<form action="<?php echo e(route('perjanjian.update', $perjanjian->id)); ?>" method="POST">
<?php echo method_field('PUT'); ?>
<?php echo csrf_field(); ?>
    <input type="hidden" name="from" value="<?php echo e(request('from', 'dashboard_wadir_perjanjian')); ?>">
    <?php if(session('error')): ?>
        <div style="background: #fee; border: 1px solid #fcc; color: #c00; padding: 15px; margin-bottom: 20px; border-radius: 6px;">
            <strong>Error:</strong> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <?php if($errors->any()): ?>
        <div style="background: #fee; border: 1px solid #fcc; color: #c00; padding: 15px; margin-bottom: 20px; border-radius: 6px;">
            <strong>Validation Errors:</strong>
            <ul style="margin: 10px 0 0 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    
    <div style="text-align: center; margin-bottom: 20px;">
        <img src="<?php echo e(asset('images/logo_pemda.png')); ?>" style="width: 70px;">
        <h2 style="font-size:16px; font-weight:600; margin-top:10px;">
            PERJANJIAN KINERJA TAHUN <span id="headerTahun"><?php echo e($perjanjian->tahun ?? '2025'); ?></span> <br>
            WAKIL DIREKTUR PELAYANAN <br>
            UOBK RSUD BANGIL KABUPATEN PASURUAN
        </h2>
    </div>

    
    <div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 2px solid #00B5A0;">
        <label for="tahun" style="font-weight: 600; color: #333; margin-bottom: 8px; display: block;">
            <i class="fas fa-calendar-alt" style="color: #00B5A0;"></i> Pilih Tahun Perjanjian <span style="color: red;">*</span>
        </label>
        <select name="tahun" id="tahun" class="input-box" required style="background: white; border: 2px solid #00B5A0;">
            <option value="">-- Pilih Tahun --</option>
            <?php $__currentLoopData = $availableYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($year); ?>" <?php echo e(old('tahun', $perjanjian->tahun) == $year ? 'selected' : ''); ?>>
                    <?php echo e($year); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            headerTahun.textContent = '<?php echo e($perjanjian->tahun ?? "2025"); ?>'; // default dari database
        }
    });
    </script>

    <p style="text-align:justify;">
        Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil,
        kami yang bertanda tangan dibawah ini :
    </p>

    
    <div class="flex-row">
        
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak1_name"
                value="<?php echo e(old('pihak1_name', $perjanjian->pihak1_name)); ?>" readonly>

            <input type="text" class="input-box" name="pihak1_jabatan"
                value="<?php echo e(old('pihak1_jabatan', $perjanjian->pihak1_jabatan)); ?>" readonly>

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK PERTAMA</b>.
            </p>
        </div>

        
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak2_name" id="pihak2_name" 
                   value="<?php echo e(old('pihak2_name', $perjanjian->pihak2_name)); ?>" readonly>

            <input type="text" class="input-box" name="pihak2_jabatan" id="pihak2_jabatan" 
                   value="<?php echo e(old('pihak2_jabatan', $perjanjian->pihak2_jabatan)); ?>" readonly>

            <input type="hidden" name="pihak2_nip" id="pihak2_nip" 
                   value="<?php echo e(old('pihak2_nip', $perjanjian->pihak2_nip)); ?>">

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK KEDUA</b>.
            </p>
        </div>
    </div>

    
    <div class="flex-row">
        <div class="flex-col">
            <input type="text" class="input-box" name="location" placeholder="Tempat" 
                   value="<?php echo e(old('location', $perjanjian->location ?? 'Pasuruan')); ?>">
        </div>
        <div class="flex-col">
            <input type="date" class="input-box" name="agreement_date" 
                   value="<?php echo e(old('agreement_date', $perjanjian->agreement_date ? \Carbon\Carbon::parse($perjanjian->agreement_date)->format('Y-m-d') : date('Y-m-d'))); ?>">
        </div>
    </div>

    
    <p style="text-align:justify; margin-top:18px;"> Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. </p> 
    <p style="text-align:justify;"> Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi. </p> 
    
    
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
                value="<?php echo e(old('jabatan_pelaksana', $perjanjian->jabatan_pelaksana ?? $perjanjian->pihak1_jabatan)); ?>"
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
            <?php
                $tugasValue = old('tugas_pelaksana');
                if ($tugasValue === null) {
                    $tugasValue = !empty($perjanjian->tugas_pelaksana)
                        ? $perjanjian->tugas_pelaksana
                        : ($jabatanData ? $jabatanData->tugas : '');
                }
                if (is_array($tugasValue)) {
                    $tugasValue = implode("\n", $tugasValue);
                } elseif (is_string($tugasValue)) {
                    $decodedTugas = json_decode($tugasValue, true);
                    if (is_array($decodedTugas)) {
                        $tugasValue = implode("\n", $decodedTugas);
                    }
                }
            ?>
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
            ><?php echo e(trim($tugasValue)); ?></textarea>
        </div>

        <div>
            <label style="font-weight: 600;">Fungsi</label>
            <?php
                $fungsiValue = old('fungsi_pelaksana');
                if ($fungsiValue === null) {
                    $fungsiValue = !empty($perjanjian->fungsi_pelaksana)
                        ? $perjanjian->fungsi_pelaksana
                        : ($jabatanData ? $jabatanData->fungsi : null);
                }
                if (is_string($fungsiValue) && (strpos($fungsiValue, '[') === 0 || strpos($fungsiValue, '{') === 0)) {
                    $decodedFungsi = json_decode($fungsiValue, true);
                    if (is_array($decodedFungsi)) {
                        $fungsiValue = $decodedFungsi;
                    }
                }
                $visibleFungsi = $fungsiValue;
            ?>
            <div id="fungsi_container" style="
                width: 100%;
                padding: 8px;
                border: 1px solid #ccc;
                border-radius: 6px;
                background: #e9ecef;
                min-height: 60px;
            ">
                <?php if($visibleFungsi): ?>
                    <?php if(is_array($visibleFungsi)): ?>
                        <ol style="margin: 0; padding-left: 20px;">
                            <?php $__currentLoopData = $visibleFungsi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fungsi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li style="margin-bottom: 4px;"><?php echo e($fungsi); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ol>
                    <?php else: ?>
                        <?php echo e($visibleFungsi); ?>

                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <input type="hidden" name="fungsi_pelaksana" id="fungsi_pelaksana" 
                   value="<?php echo e(is_array($fungsiValue) ? json_encode($fungsiValue) : $fungsiValue); ?>">
        </div>
    </div>


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
        <?php if(!empty($tabelA['sasaran']) && count($tabelA['sasaran']) > 0): ?>
            <?php $__currentLoopData = $tabelA['sasaran']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sasaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $indicatorType = old('a_indicator_type.' . $index, $tabelA['indicator_type'][$index] ?? 'positif'); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><textarea name="a_sasaran[]" onkeyup="if(window.autoExpand)window.autoExpand(this);if(window.syncTabelAAll)window.syncTabelAAll();"><?php echo e(old('a_sasaran.' . $index, $sasaran)); ?></textarea></td>
                    <td><textarea name="a_indikator[]" onkeyup="if(window.autoExpand)window.autoExpand(this);if(window.syncTabelAAll)window.syncTabelAAll();"><?php echo e(old('a_indikator.' . $index, $tabelA['indikator'][$index] ?? '')); ?></textarea></td>
                    <td><textarea name="a_satuan[]" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('a_satuan.' . $index, $tabelA['satuan'][$index] ?? '')); ?></textarea></td>
                    <td><textarea name="a_target[]" onkeyup="if(window.autoExpand)window.autoExpand(this);if(window.syncTabelAAll)window.syncTabelAAll();"><?php echo e(old('a_target.' . $index, $tabelA['target'][$index] ?? '')); ?></textarea></td>
                    <td>
                        <input type="hidden" name="a_indicator_type[]" value="<?php echo e($indicatorType); ?>">
                        <div class="indicator-toggle-wrap">
                            <button type="button" class="indicator-mini-btn indicator-plus <?php echo e($indicatorType === 'positif' ? 'active' : ''); ?>" title="Indikator Positif" onclick="window.setIndicatorType(this, 'positif')">+</button>
                            <button type="button" class="indicator-mini-btn indicator-minus <?php echo e($indicatorType === 'negatif' ? 'active' : ''); ?>" title="Indikator Negatif" onclick="window.setIndicatorType(this, 'negatif')">-</button>
                        </div>
                        <button type="button" class= "table-action-btn delete-btn" onclick="window.deleteRowTabelA(this)" title="hapus">🗑</button>
                        <button type="button" class="table-action-btn add-btn" onclick="window.addRow('tabelA')" title="Tambah">➕</button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <tr>
                <td>1</td>
                <td><textarea name="a_sasaran[]" onkeyup="if(window.autoExpand)window.autoExpand(this);if(window.syncTabelAAll)window.syncTabelAAll();"></textarea></td>
                <td><textarea name="a_indikator[]" onkeyup="if(window.autoExpand)window.autoExpand(this);if(window.syncTabelAAll)window.syncTabelAAll();"></textarea></td>
                <td><textarea name="a_satuan[]" onkeyup="if(window.autoExpand)window.autoExpand(this)"></textarea></td>
                <td><textarea name="a_target[]" onkeyup="if(window.autoExpand)window.autoExpand(this);if(window.syncTabelAAll)window.syncTabelAAll();"></textarea></td>
                <td>
                    <input type="hidden" name="a_indicator_type[]" value="positif">
                    <div class="indicator-toggle-wrap">
                        <button type="button" class="indicator-mini-btn indicator-plus active" title="Indikator Positif" onclick="window.setIndicatorType(this, 'positif')">+</button>
                        <button type="button" class="indicator-mini-btn indicator-minus" title="Indikator Negatif" onclick="window.setIndicatorType(this, 'negatif')">-</button>
                    </div>
                    <button type="button" class= "table-action-btn delete-btn" onclick="window.deleteRowTabelA(this)" title="hapus">🗑</button>
                    <button type="button" class="table-action-btn add-btn" onclick="window.addRow('tabelA')" title="Tambah">➕</button>
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


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

    <tbody id="tabelProgramBody">
        
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
    console.log('=== EDIT PAGE LOADED ===');
    console.log('DOMContentLoaded - existingTabelC:', window.existingTabelC);
    console.log('TabelA data:', <?php echo json_encode($tabelA, 15, 512) ?>);
    console.log('TabelB data:', <?php echo json_encode($tabelB, 15, 512) ?>);
    console.log('Functions available:', {
        addRow: typeof window.addRow,
        deleteRow: typeof window.deleteRow,
        deleteRowTabelA: typeof window.deleteRowTabelA,
        addProgram: typeof window.addProgram,
        syncProgramToTabelD: typeof window.syncProgramToTabelD
    });

        // Normalize existingTabelC if it comes as a JSON string
        if (typeof window.existingTabelC === 'string') {
            try {
                window.existingTabelC = JSON.parse(window.existingTabelC);
            } catch (e) {
                console.error('Failed to parse existingTabelC string', e);
                window.existingTabelC = {};
            }
        }
    
    // Tombol sudah menggunakan onclick inline, tidak perlu addEventListener lagi
    // const btnAddProgram = document.getElementById('btnAddProgram');
    // if (btnAddProgram) {
    //     console.log('Attaching click handler to button');
    //     btnAddProgram.addEventListener('click', function(e) {
    //         e.preventDefault();
    //         console.log('Button clicked via event listener');
    //         window.addProgram();
    //     });
    // } else {
    //     console.error('btnAddProgram not found!');
    // }
    
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
        if (typeof window.syncTabelAAll === 'function') {
            window.syncTabelAAll();
        } else if (typeof window.syncTabelAToC === 'function') {
            // Fallback lama
            window.syncTabelAToC();
        } else {
            console.warn('syncTabelAAll/syncTabelAToC not defined yet');
        }
    } catch(e) {
        console.error('Error in syncTabelAAll:', e);
    }
    
    // Auto-expand semua textarea yang sudah ada di halaman
    setTimeout(() => {
        if (typeof window.autoExpand === 'function') {
            document.querySelectorAll('table textarea').forEach(ta => {
                if (ta && ta.value) {
                    window.autoExpand(ta);
                }
            });
            console.log('Auto-expanded all existing textareas');
        }
    }, 50);
    
    // Calculate total setelah render selesai
    setTimeout(() => {
        try {
            if (typeof window.bindAnggaranListeners === 'function') window.bindAnggaranListeners();
            if (window.calculateTotal) {
                window.calculateTotal();
            }
            // Sync ulang untuk memastikan
            if (typeof window.syncTabelAAll === 'function') {
                window.syncTabelAAll();
            }
        } catch(e) {
            console.error('Error in calculateTotal:', e);
        }
    }, 100);
    
    // Panggil lagi dengan delay lebih lama untuk memastikan
    setTimeout(() => {
        if (typeof window.bindAnggaranListeners === 'function') window.bindAnggaranListeners();
        if (window.calculateTotal) window.calculateTotal();
        
        // Final sync untuk memastikan semua tabel sinkron
        if (typeof window.syncTabelAAll === 'function') {
            window.syncTabelAAll();
            console.log('Final syncTabelAAll executed');
        }
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
            if (typeof window.syncProgramToTabelD === 'function') {
                window.syncProgramToTabelD();
            }
        }
        
        // Sync ulang Tabel A -> C jika fungsi sudah tersedia
        if (typeof window.syncTabelAAll === 'function') {
            console.log('Late sync: syncTabelAAll available, executing...');
            window.syncTabelAAll();
        }
    }, 800);
    
    // Sync tabel D setelah tabel program ter-render
    setTimeout(() => {
        if (typeof window.syncProgramToTabelD === 'function') {
            console.log('Final sync of Tabel D after program table rendered');
            window.syncProgramToTabelD();
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
        if (window.addProgram) window.addProgram();
        return;
    }
    
    console.log('Rendering', tabelC.programs.length, 'programs');
    let programCounter = 1;
    let rowsAdded = 0;
    
    tabelC.programs.forEach((program, pIdx) => {
        console.log(`Processing program ${pIdx}:`, program);
        
        // Support berbagai format key nama: name, nama, program
        const programName = program.name || program.nama || program.program || '';
        const programAmount = parseInt((program.amount || '0').toString().replace(/[^\d]/g,'')) || 0;
        const programSource = program.source || program.keterangan || program.ket || '';
        
        // Skip completely empty programs
        const hasName = programName && programName.trim() !== '';
        const hasAmount = programAmount > 0;
        if (!hasName && !hasAmount) {
            console.log(`Skipping empty program at index ${pIdx}`);
            return; // Skip this iteration
        }
        
        // Add program row
        const programRow = document.createElement('tr');
        programRow.classList.add('program-row');
        programRow.dataset.program = programCounter;
        programRow.dataset.level = 'program';
        
        // Build program dropdown options with selected value
        let programOptions = '<option value="">-- Pilih Program --</option>';
        if (window.programsData && Array.isArray(window.programsData)) {
            window.programsData.forEach(prog => {
                const selected = prog.nama_program === programName ? 'selected' : '';
                programOptions += `<option value="${prog.nama_program}" data-kode="${prog.kode_program}" data-program-id="${prog.id}" ${selected}>${prog.nama_program}</option>`;
            });
        }
        
        programRow.innerHTML = `
            <td class="no-col">${programCounter}</td>
            <td>
                <select name="program_nama[]" class="program-nama-dropdown" style="width:95%; font-weight:600; padding:8px;" onchange="window.handleProgramChange(this)" required>
                    ${programOptions}
                </select>
            </td>
            <td><input type="text" name="program_anggaran[]" value="${programAmount.toLocaleString('id-ID')}" readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari kegiatan" /></td>
            <td><textarea name="program_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;">${programSource}</textarea></td>
            <td>
                <button type="button" class="table-action-btn add-btn" onclick="window.addSubRow('${programCounter}', 'kegiatan')" title="Tambah Kegiatan">➕</button>
                <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
            </td>
        `;
        
        tbody.appendChild(programRow);
        rowsAdded++;
        
        // Add kegiatan and sub kegiatan
        if (program.kegiatan && program.kegiatan.length > 0) {
            program.kegiatan.forEach((kegiatan, kIdx) => {
                const kegiatanRow = document.createElement('tr');
                kegiatanRow.classList.add('subprogram-row');
                kegiatanRow.dataset.parent = programCounter;
                kegiatanRow.dataset.level = 'kegiatan';
                
                const kegiatanNo = `${programCounter}.${kIdx + 1}`;
                const kegiatanName = kegiatan.name || kegiatan.nama || kegiatan.kegiatan || '';
                const kegiatanAmount = parseInt((kegiatan.amount || '0').toString().replace(/[^\d]/g,'')) || 0;
                const kegiatanSource = kegiatan.source || kegiatan.keterangan || kegiatan.ket || '';
                
                // Build kegiatan dropdown options with selected value
                let kegiatanOptions = '<option value="">-- Pilih Kegiatan --</option>';
                if (window.kegiatansData && Array.isArray(window.kegiatansData)) {
                    window.kegiatansData.forEach(keg => {
                        const selected = keg.nama_kegiatan === kegiatanName ? 'selected' : '';
                        kegiatanOptions += `<option value="${keg.nama_kegiatan}" data-kode="${keg.kode_kegiatan}" ${selected}>${keg.nama_kegiatan}</option>`;
                    });
                }
                
                kegiatanRow.innerHTML = `
                    <td class="no-col">${kegiatanNo}</td>
                    <td>
                        <select name="kegiatan_nama[]" class="kegiatan-nama-dropdown" style="width:95%; padding:8px; font-style:italic;" required>
                            ${kegiatanOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" name="kegiatan_anggaran[]" value="${kegiatanAmount.toLocaleString('id-ID')}" readonly style="text-align:right; background:#f0f0f0; cursor:not-allowed;" placeholder="Auto" title="Total otomatis dari sub kegiatan">
                    </td>
                    <td><textarea name="kegiatan_ket[]" readonly style="background:#f0f0f0; cursor:not-allowed;">${kegiatanSource}</textarea></td>
                    <td>
                        <button type="button" class="table-action-btn add-btn" onclick="window.addSubRow('${kegiatanNo}', 'subkegiatan')" title="Tambah Sub Kegiatan">➕</button>
                        <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
                    </td>
                `;
                
                tbody.appendChild(kegiatanRow);
                rowsAdded++;
                
                // Add sub kegiatan
                if (kegiatan.subKegiatan && kegiatan.subKegiatan.length > 0) {
                    kegiatan.subKegiatan.forEach((subKegiatan, sIdx) => {
                        const subKegiatanRow = document.createElement('tr');
                        subKegiatanRow.classList.add('subprogram-row');
                        subKegiatanRow.dataset.parent = kegiatanNo;
                        subKegiatanRow.dataset.level = 'subkegiatan';
                        
                        const subKegiatanNo = `${kegiatanNo}.${sIdx + 1}`;
                        const subKegiatanName = subKegiatan.name || subKegiatan.nama || subKegiatan.subkegiatan || '';
                        const subKegiatanAmount = parseInt((subKegiatan.amount || '0').toString().replace(/[^\d]/g,'')) || 0;
                        const subKegiatanSource = subKegiatan.source || subKegiatan.keterangan || subKegiatan.ket || '';
                        const selectedApbd = subKegiatanSource === 'APBD' ? 'selected' : '';
                        const selectedBlud = subKegiatanSource === 'BLUD' ? 'selected' : '';
                        
                        // Build sub kegiatan dropdown options with selected value
                        let subKegiatanOptions = '<option value="">-- Pilih Sub Kegiatan --</option>';
                        if (window.subKegiatansData && Array.isArray(window.subKegiatansData)) {
                            window.subKegiatansData.forEach(subKeg => {
                                const selected = subKeg.nama_sub_kegiatan === subKegiatanName ? 'selected' : '';
                                subKegiatanOptions += `<option value="${subKeg.nama_sub_kegiatan}" data-kode="${subKeg.kode_sub_kegiatan}" ${selected}>${subKeg.nama_sub_kegiatan}</option>`;
                            });
                        }
                        
                        subKegiatanRow.innerHTML = `
                            <td class="no-col">${subKegiatanNo}</td>
                            <td>
                                <select name="subkegiatan_nama[]" class="subkegiatan-nama-dropdown" style="width:95%; padding:8px;" required>
                                    ${subKegiatanOptions}
                                </select>
                            </td>
                            <td>
                                <input type="text" name="subkegiatan_anggaran[]" value="${subKegiatanAmount.toLocaleString('id-ID')}" style="text-align:right" onkeypress="return /[0-9]/.test(event.key)"
                                    oninput="window.forceNumericAnggaran(this);">
                            </td>
                            <td>
                                <select name="subkegiatan_ket[]" style="width:95%; padding:6px;" onchange="window.updateHierarchicalKet(); window.syncProgramToTabelD();">
                                    <option value="">-- Pilih --</option>
                                    <option value="APBD" ${selectedApbd}>APBD</option>
                                    <option value="BLUD" ${selectedBlud}>BLUD</option>
                                </select>
                            </td>
                            <td>
                                <button type="button" class="table-action-btn delete-btn" onclick="window.deleteRow(this, 'tabelProgram')" title="Hapus">🗑</button>
                            </td>
                        `;
                        
                        tbody.appendChild(subKegiatanRow);
                        rowsAdded++;
                    });
                }
            });
        }
        
        programCounter++;
    });
    
    console.log(`Finished rendering: ${rowsAdded} total rows added to table (programs, kegiatan, subkegiatan)`);
    
    // If no rows were added (all programs were empty), add one empty row
    if (rowsAdded === 0) {
        console.log('No valid programs rendered, adding empty program row');
        window.addProgram();
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

   // Fungsi addSubRow sudah didefinisikan di atas (window.addSubRow di baris ~647)
   // Tidak perlu definisi ulang yang akan menimpa fungsi yang sudah ada

    // Fungsi deleteRow sudah didefinisikan di atas (window.deleteRow di baris ~754)
    // Tidak perlu definisi ulang di sini

        // Roll-up KET: sub-kegiatan -> kegiatan -> program (paritas dengan create)
        window.updateHierarchicalKet = function() {
            const tbody = document.querySelector('#tabelProgramBody');
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
                    if (window.autoExpand) window.autoExpand(kegiatanKetInput);
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
                    if (window.autoExpand) window.autoExpand(programKetInput);
                }
            });
        }

    // Fungsi hitung total hanya dari program utama
    // Fungsi hierarchical auto-calculate
    window.calculateHierarchicalTotal = function() {
        const tbody = document.querySelector('#tabelProgramBody');
        if (!tbody) return;
        
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        
        // Step 1: Calculate semua SUB KEGIATAN -> KEGIATAN (bottom-up)
        allRows.forEach(row => {
            if (row.dataset.level === 'kegiatan') {
                const kegiatanNo = row.querySelector('.no-col')?.textContent?.trim();
                if (!kegiatanNo) return;
                
                // Cari semua sub kegiatan di bawah kegiatan ini
                const subKegiatans = allRows.filter(r => 
                    r.dataset.parent === kegiatanNo && r.dataset.level === 'subkegiatan'
                );
                
                let kegiatanTotal = 0;
                subKegiatans.forEach(sub => {
                    const input = sub.querySelector('input[name="subkegiatan_anggaran[]"]');
                    if (input) {
                        const val = (input.value || '').toString().replace(/[^\d]/g, '');
                        kegiatanTotal += parseInt(val) || 0;
                    }
                });
                
                // Update kegiatan anggaran (readonly field)
                const kegiatanInput = row.querySelector('input[name="kegiatan_anggaran[]"]');
                if (kegiatanInput) {
                    kegiatanInput.value = kegiatanTotal.toLocaleString('id-ID');
                }
            }
        });
        
        // Step 2: Calculate semua KEGIATAN -> PROGRAM
        let grandTotal = 0;
        allRows.forEach(row => {
            if (row.classList.contains('program-row')) {
                const programNo = row.querySelector('.no-col')?.textContent?.trim();
                if (!programNo) return;
                
                // Cari semua kegiatan di bawah program ini
                const kegiatans = allRows.filter(r => 
                    r.dataset.parent === programNo && r.dataset.level === 'kegiatan'
                );
                
                let programTotal = 0;
                kegiatans.forEach(keg => {
                    const input = keg.querySelector('input[name="kegiatan_anggaran[]"]');
                    if (input) {
                        const val = (input.value || '').toString().replace(/[^\d]/g, '');
                        programTotal += parseInt(val) || 0;
                    }
                });
                
                // Update program anggaran (readonly field)
                const programInput = row.querySelector('input[name="program_anggaran[]"]');
                if (programInput) {
                    programInput.value = programTotal.toLocaleString('id-ID');
                }
                
                grandTotal += programTotal;
            }
        });
        
        // Step 3: Update grand total
        const totalElement = document.getElementById('totalAnggaran');
        if (totalElement) {
            totalElement.textContent = grandTotal.toLocaleString('id-ID');
        }

        // Selalu sinkronkan KET otomatis setelah perhitungan anggaran
        if (window.updateHierarchicalKet) window.updateHierarchicalKet();
    };
    
    // Backward compatibility
    window.calculateTotal = window.calculateHierarchicalTotal; 

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
        if (window.calculateHierarchicalTotal) window.calculateHierarchicalTotal();
        if (window.syncProgramToTabelD) window.syncProgramToTabelD();
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
                if (window.syncProgramToTabelD) window.syncProgramToTabelD();
            });
            
            inp.addEventListener('change', () => {
                console.log('Change event on anggaran:', inp.value);
                if (window.calculateTotal) window.calculateTotal();
                if (window.syncProgramToTabelD) window.syncProgramToTabelD();
            });
            
            // Sanitize nilai awal dan hitung total
            window.forceNumericAnggaran(inp);
        });
        
        // Hitung total setelah binding
        if (window.calculateTotal) window.calculateTotal();
        if (window.syncProgramToTabelD) window.syncProgramToTabelD();
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
        <?php if(!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0): ?>
            <?php $__currentLoopData = $tabelB['sasaran']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sasaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="width: 25%;"><textarea name="c_sasaran[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_sasaran.' . $index, $sasaran)); ?></textarea></td>
                    <td style="width: 20%;"><textarea name="c_indikator[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_indikator.' . $index, $tabelB['indikator'][$index] ?? '')); ?></textarea></td>
                    <td style="width: 12%;">
                        <input type="hidden" name="c_indicator_type[]" value="<?php echo e(old('c_indicator_type.' . $index, $tabelB['indicator_type'][$index] ?? 'positif')); ?>">
                        <textarea name="c_target[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_target.' . $index, $tabelB['target'][$index] ?? '')); ?></textarea>
                    </td>
                    <td style="width: 10%;"><textarea name="c_tw1[]" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_tw1.' . $index, $tabelB['tw1'][$index] ?? '')); ?></textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw2[]" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_tw2.' . $index, $tabelB['tw2'][$index] ?? '')); ?></textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw3[]" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_tw3.' . $index, $tabelB['tw3'][$index] ?? '')); ?></textarea></td>
                    <td style="width: 10%;"><textarea name="c_tw4[]" onkeyup="if(window.autoExpand)window.autoExpand(this)"><?php echo e(old('c_tw4.' . $index, $tabelB['tw4'][$index] ?? '')); ?></textarea></td>
                    <td style="width: 60px;">
                        <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this, 'tabel2')" title="Hapus" style="visibility: hidden;">🗑</button>
                        <button type="button" class="table-action-btn add-btn" onclick="window.addRow('tabel2')" title="Tambah" style="visibility: hidden;">➕</button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </tbody>
</table>



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
        const isSubKegiatan = row.dataset.level === 'subkegiatan' || (no && no.split('.').length === 3);

        const tr = document.createElement('tr');
        tr.dataset.key = key;
        tr.dataset.no = no;  // TAMBAHKAN INI - Set dataset.no untuk parsing saat save

        tr.innerHTML = `
            <td style="border:1px solid #000; text-align:center;">${no}</td>
            <td style="border:1px solid #000; padding-left:${isSub ? '25px' : '5px'};" class="anggaran-cell">
                ${nama}
            </td>
            <td style="border:1px solid #000; text-align:right;" class="anggaran-cell">
                ${anggaran.toLocaleString('id-ID')}
            </td>

            <td style="border:1px solid #000;">
                <input class="tw1" type="text" value="${tw.tw1 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${isSubKegiatan ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw2" type="text" value="${tw.tw2 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${isSubKegiatan ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw3" type="text" value="${tw.tw3 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${isSubKegiatan ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw4" type="text" value="${tw.tw4 || ''}"
                    readonly style="background:#f0f0f0; cursor:not-allowed; text-align:right;"
                    title="${isSubKegiatan ? 'Klik tombol Atur untuk mengisi target' : 'Total otomatis dari sub-kegiatan'}">
            </td>
            <td style="border:1px solid #000; text-align:center;">
                ${isSubKegiatan ? 
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

    document.getElementById('totalAnggaranD').textContent =
        totalAnggaran.toLocaleString('id-ID');

    hitungHierarchicalTW();
    
    // ===== UPDATE HIDDEN INPUT DENGAN DATA HIERARCHICAL =====
    // Kumpulkan semua data dari Tabel D ke struktur hierarchical
    const hierarchicalStructure = {
        programs: []
    };
    
    const tbodyDRows = tbodyD.querySelectorAll('tr');
    let currentProgram = null;
    let currentKegiatan = null;
    
    tbodyDRows.forEach(tr => {
        const key = tr.dataset.key;
        const no = tr.dataset.no;  // Sudah di-set di atas
        
        // Get name from column 2
        const nameCell = tr.querySelector('td:nth-child(2)');
        const name = nameCell?.textContent?.trim() || '';
        
        // Get amount from column 3 (with anggaran-cell class)
        const amountCells = tr.querySelectorAll('.anggaran-cell');
        const amountCell = amountCells[1]; // Index 1 karena [0] adalah nama, [1] adalah anggaran
        const amountText = amountCell?.textContent?.trim() || '0';
        const amount = parseInt(amountText.replace(/\D/g, '')) || 0;
        
        // Parse TW values - remove dots (thousands separator) then parse
        const tw1Val = tr.querySelector('.tw1')?.value?.trim() || '0';
        const tw2Val = tr.querySelector('.tw2')?.value?.trim() || '0';
        const tw3Val = tr.querySelector('.tw3')?.value?.trim() || '0';
        const tw4Val = tr.querySelector('.tw4')?.value?.trim() || '0';
        
        const tw1 = parseInt(tw1Val.replace(/\./g, '').replace(/[^\d]/g, '')) || 0;
        const tw2 = parseInt(tw2Val.replace(/\./g, '').replace(/[^\d]/g, '')) || 0;
        const tw3 = parseInt(tw3Val.replace(/\./g, '').replace(/[^\d]/g, '')) || 0;
        const tw4 = parseInt(tw4Val.replace(/\./g, '').replace(/[^\d]/g, '')) || 0;
        
        // Debug log for TW values
        if (key && (tw1 > 0 || tw2 > 0 || tw3 > 0 || tw4 > 0)) {
            console.log(`TW Data for ${key} (${no}):`, { tw1, tw2, tw3, tw4 });
        }
        
        if (key && key.startsWith('program-')) {
            // Ini adalah Program
            currentProgram = {
                no: parseInt(no) || hierarchicalStructure.programs.length + 1,
                name: name,
                amount: amount,
                tw1: tw1,
                tw2: tw2,
                tw3: tw3,
                tw4: tw4,
                kegiatan: []
            };
            hierarchicalStructure.programs.push(currentProgram);
            currentKegiatan = null;
        } else if (key && key.startsWith('sub-')) {
            const parts = no ? no.split('.') : [];
            if (parts.length === 2) {
                // Ini adalah Kegiatan
                currentKegiatan = {
                    no: no,
                    name: name,
                    amount: amount,
                    tw1: tw1,
                    tw2: tw2,
                    tw3: tw3,
                    tw4: tw4,
                    subKegiatan: []
                };
                if (currentProgram) {
                    currentProgram.kegiatan.push(currentKegiatan);
                }
            } else if (parts.length === 3) {
                // Ini adalah Sub Kegiatan
                const subKegiatan = {
                    no: no,
                    name: name,
                    amount: amount,
                    tw1: tw1,
                    tw2: tw2,
                    tw3: tw3,
                    tw4: tw4
                };
                if (currentKegiatan) {
                    currentKegiatan.subKegiatan.push(subKegiatan);
                }
            }
        }
    });
    
    // Update hidden input dengan JSON
    const hiddenInput = document.getElementById('hierarchical-budget-json');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(hierarchicalStructure);
        console.log('✅ Updated hierarchical-budget-json with', hierarchicalStructure.programs.length, 'programs');
        console.log('Full structure:', hierarchicalStructure);
    }
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

    
    <div style="margin-top:25px; text-align:right; display:flex; gap:10px; justify-content:flex-end;">
    <button class="save-btn" type="submit" onclick="saveToSupabase(event)" style="background:#009970;">
        💾 UPDATE
    </button>
</div>
</form>

<?php echo $__env->make('perjanjian._modal_target_triwulan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
        
        const rawValue = input.value.replace(/[^\d]/g, '');
        const numericValue = parseInt(rawValue) || 0;
        
        if (mode === 'nominal') {
            input.placeholder = '0';
            if (rawValue) {
                if (previousMode === 'prosentase' && pagu > 0) {
                    input.value = Math.round((numericValue / 100) * pagu).toLocaleString('id-ID');
                } else {
                    input.value = numericValue.toLocaleString('id-ID');
                }
            }
        } else {
            input.placeholder = '0%';
            if (rawValue) {
                if (previousMode === 'nominal' && pagu > 0) {
                    input.value = Math.round((numericValue / pagu) * 100).toString();
                } else {
                    input.value = rawValue;
                }
            }
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
        return;
    }
    
    let totalNominal = 0;
    let tw1 = 0, tw2 = 0, tw3 = 0, tw4 = 0;
    
    // Loop through all 12 months
    for (let i = 1; i <= 12; i++) {
        const input = document.getElementById('bln' + i);
        if (!input) continue;
        
        // Extract numeric value from formatted string
        const cleanValue = input.value.replace(/\./g, '').replace(/[^\d]/g, '');
        let value = parseInt(cleanValue) || 0;
        
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
    }
    
    // Update sisa pagu
    const sisaPaguElement = document.getElementById('sisaPagu');
    if (sisaPaguElement) {
        sisaPaguElement.textContent = 'Rp ' + Math.round(Math.max(0, sisaPagu)).toLocaleString('id-ID');
        
        if (sisaPagu < 0) {
            sisaPaguElement.style.color = '#dc3545';
        } else if (sisaPagu === 0) {
            sisaPaguElement.style.color = '#28a745';
        } else {
            sisaPaguElement.style.color = '#856404';
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

    <script>
        // Pass existing data to JavaScript
        window.existingTabelC = <?php echo json_encode($tabelC, 15, 512) ?>;
        window.pihak1Jabatan = <?php echo json_encode($perjanjian->pihak1_jabatan, 15, 512) ?>;
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


<script>

    /* MOVED TO TOP - window.autoExpand now defined before HTML render
    // =========================
    // AUTO EXPAND TEXTAREA
    // =========================
    window.autoExpand = function(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 150) + 'px';
    }
    */

    // Initialize auto-expand for all textareas (tetap diperlukan untuk event listener)
    document.querySelectorAll('table textarea').forEach(ta => {
        ta.addEventListener('input', function() {
            if (window.autoExpand) window.autoExpand(this);
        });
        if (window.autoExpand) window.autoExpand(ta);
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
                if (typeof syncTabelAAll === 'function') {
                    syncTabelAAll();
                }
            }
        });
        
        // Trigger sync saat page load
        if (typeof syncTabelAAll === 'function') {
            syncTabelAAll();
        }
    }
    
    // Trigger sync tabel program ke tabel D (tabel keempat) saat page load
    if (typeof syncProgramToTabelD === 'function') {
        syncProgramToTabelD();
    }
});

/* MOVED TO TOP - syncTabelAToC now defined before HTML render
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
        const indicatorTypeInput = row.querySelector('input[name="c_indicator_type[]"]');
        
        existingSasaran.push(sasaranInput ? sasaranInput.value : '');
        existingIndikator.push(indikatorInput ? indikatorInput.value : '');
        existingTarget.push(targetInput ? targetInput.value : '');
        
        existingData.push({
            indicatorType: indicatorTypeInput ? indicatorTypeInput.value : 'positif',
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
        const existingIndicatorType = existingData[index]?.indicatorType || indicatorType;
        
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td style="width: 25%;"><textarea name="c_sasaran[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)">${sasaran}</textarea></td>
            <td style="width: 20%;"><textarea name="c_indikator[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)">${indikator}</textarea></td>
            <td style="width: 12%;">
                <input type="hidden" name="c_indicator_type[]" value="${existingIndicatorType}">
                <textarea name="c_target[]" readonly style="background: #f5f5f5; cursor: not-allowed;" onkeyup="if(window.autoExpand)window.autoExpand(this)">${target}</textarea>
            </td>
            <td style="width: 10%;"><textarea name="c_tw1[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw1}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw2[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw2}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw3[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw3}</textarea></td>
            <td style="width: 10%;"><textarea name="c_tw4[]" onkeyup="if(window.autoExpand)window.autoExpand(this)">${tw4}</textarea></td>
            <td style="width: 60px;">
                <button type="button" class="table-action-btn delete-btn" onclick="if(window.deleteRow)window.deleteRow(this, 'tabel2')" title="Hapus" style="visibility: hidden;">🗑</button>
                <button type="button" class="table-action-btn add-btn" onclick="if(window.addRow)window.addRow('tabel2')" title="Tambah" style="visibility: hidden;">➕</button>
            </td>
        `;
        tbody2.appendChild(newRow);
        
        // Auto expand all textareas
        newRow.querySelectorAll('textarea').forEach(ta => {
            if (ta && window.autoExpand) window.autoExpand(ta);
        });
    });
}
*/

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

/* MOVED TO TOP - window.addRow now defined before HTML render
window.addRow = function(tableId) {
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
    
    // Sync tabel A ke semua turunan jika menambah baris di tabelA
    if (tableId === 'tabelA') {
        if (typeof syncTabelAAll === 'function') {
            syncTabelAAll();
        } else if (typeof syncTabelAToC === 'function') {
            syncTabelAToC();
        }
    }
}
*/

/* MOVED TO TOP - window.deleteRowTabelA now defined before HTML render
/* =========================
   HAPUS BARIS TABEL A
========================= */
window.deleteRowTabelA = function(btn) {
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

    if (typeof syncTabelAAll === 'function') {
        syncTabelAAll();
    } else if (typeof syncTabelAToC === 'function') {
        syncTabelAToC();
    }
}
*/

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

/* MOVED TO TOP - syncTabelAAll now defined before HTML render
function syncTabelAAll() {
    try {
        syncTabelAToC();
    } catch (e) {
        console.error('Sync tabel A -> tabel C gagal:', e);
    }

    try {
        syncTabelAToTabel3();
    } catch (e) {
        console.error('Sync tabel A -> tabel 3 gagal:', e);
    }
}

window.syncTabelAToC = syncTabelAToC;
window.syncTabelAToTabel3 = syncTabelAToTabel3;
window.syncTabelAAll = syncTabelAAll;
*/

// =========================
// HAPUS BARIS
// =========================
// HAPUS FUNGSI DELETEROW DUPLIKAT - GUNAKAN YANG DI WINDOW SCOPE SAJA
// function deleteRow sudah didefinisikan sebagai window.deleteRow di atas
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
    
    fetch('<?php echo e(route("perjanjian.update", $perjanjian->id)); ?>', {
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
                window.location.href = '<?php echo e(route("perjanjian.index")); ?>';
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
        const ttdPihak1 = <?php echo json_encode(auth()->user()->tanda_tangan, 15, 512) ?>;

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
<?php if(session('success')): ?>
<div style="position:fixed; top:20px; right:20px; background:#009970; 
    color:white; padding:12px 20px; border-radius:8px; 
    box-shadow:0 3px 10px rgba(0,0,0,0.2); z-index:9999;">
    <?php echo e(session('success')); ?>

</div>

<script>
    setTimeout(() => {
        document.querySelector('[style*="position:fixed"]')?.remove();
    }, 3000);
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\perjanjian\edit.blade.php ENDPATH**/ ?>