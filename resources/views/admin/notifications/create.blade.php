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
</style>

<div class="paper">
<form action="{{ route('perjanjian.store') }}" method="POST">
@csrf

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
                value="{{ auth()->user()->nama ?? '' }}" readonly>

            <input type="text" class="input-box" name="pihak1_jabatan"
                value="{{ auth()->user()->jabatan ?? '' }}" readonly>

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK PERTAMA</b>.
            </p>
        </div>

        {{-- PIHAK KEDUA --}}
        <div class="flex-col">
            <input type="text" class="input-box" name="pihak2_name" id="pihak2_name" placeholder="Nama lengkap" readonly>

            <select class="input-box" name="pihak2_jabatan" id="pihak2_jabatan">
                <option disabled selected>Pilih Jabatan</option>
                <option>Direktur</option>
                <option>Wadir Umum dan Keuangan</option>
                <option>Wadir Pelayanan</option>
            </select>

            <input type="hidden" name="pihak2_nip" id="pihak2_nip">

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
            <input type="date" class="input-box" name="agreement_date">
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
                rows="3"
                placeholder="Uraikan tugas sesuai jabatan..."
                style="
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                "
            ></textarea>
        </div>

        <div>
            <label style="font-weight: 600;">Fungsi</label>
            <textarea
                name="fungsi_pelaksana"
                rows="3"
                placeholder="Uraikan fungsi sesuai jabatan..."
                style="
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                "
            ></textarea>
        </div>
    </div>

{{-- TABEL A --}}
<table id="tabelA">
    <thead>
        <tr>
            <th>NO</th>
            <th>SASARAN</th>
            <th>INDIKATOR KINERJA</th>
            <th>SATUAN</th>
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
            <th style="border:1px solid #000;">KET</th>
            <th style="width:70px; border:1px solid #000;">AKSI</th>
        </tr>
    </thead>

    <tbody>
        <!-- Baris awal PROGRAM -->
        <tr class="program-row" data-program="1">
            <td class="no-col">1</td>
            <td><textarea name="program_nama[]" onkeyup="autoExpand(this); syncProgramToTabelD()"></textarea></td>
            <td><input type="text" name="program_anggaran[]" value="0" style="text-align:right" oninput="formatRupiah(this); calculateTotal(); syncProgramToTabelD();" /></td>
            <td><textarea name="program_ket[]" onkeyup="autoExpand(this)"></textarea></td>
            <td>
                <button type="button" class="table-action-btn add-btn" onclick="addSubRow(1)">➕</button>
                <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this)">🗑</button>
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

    window.addProgram = function() {
        const tbody = document.querySelector('#tabelProgram tbody');
        const currentPrograms = tbody.querySelectorAll('tr.program-row');
        const newProgramNo = currentPrograms.length + 1;

        const tr = document.createElement('tr');
        tr.classList.add('program-row');
        tr.dataset.program = newProgramNo;

        tr.innerHTML = `
            <td class="no-col">${newProgramNo}</td>
            <td><textarea name="program_nama[]"onkeyup="autoExpand(this); syncProgramToTabelD()"></textarea></td>
            <td><input type="text" name="program_anggaran[]" value="0" style="text-align:right" oninput="formatRupiah(this); calculateTotal(); syncProgramToTabelD();" /></td>
            <td><textarea name="program_ket[]" onkeyup="autoExpand(this)"></textarea></td>
            <td>
                <button type="button" class="table-action-btn add-btn" onclick="addSubRow(${newProgramNo})">➕</button>
                <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this)">🗑</button>
            </td>
        `;

        tbody.appendChild(tr);
        calculateTotal();
        syncProgramToTabelD(); 
    }

   window.addSubRow = function (parentNo) {

    const parentLevel = parentNo.toString().split('.').length;
    if (parentLevel >= 3) return; 

    const tbody = document.querySelector('#tabelProgram tbody');

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
        <td><textarea onkeyup="autoExpand(this)"></textarea></td>
        <td>
            <input type="text" value="0" style="text-align:right"
                oninput="formatRupiah(this); calculateTotal();">
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

    function updateProgramNumbers() {
        const tbody = document.querySelector('#tabelProgram tbody');
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
    window.calculateTotal = function() {
        const tbody = document.querySelector('#tabelProgram tbody');
        let total = 0;
        tbody.querySelectorAll('tr.program-row').forEach(tr => {
            const input = tr.querySelector('input[name="program_anggaran[]"]');
            if (input) {
                // hapus format rupiah dan konversi ke angka
                const val = input.value.replace(/[^\d]/g,'');
                total += parseInt(val) || 0;
            }
        });
        document.getElementById('totalAnggaran').textContent = total.toLocaleString('id-ID');
    }

    // Panggil sekali untuk baris awal
    calculateTotal();
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

    let totalAnggaran = 0;
    let totalTW1 = 0, totalTW2 = 0, totalTW3 = 0, totalTW4 = 0;

    rows.forEach(row => {
        const isProgram = row.classList.contains('program-row');
        const isSub = row.classList.contains('subprogram-row');
        if (!isProgram && !isSub) return;

        const no = row.querySelector('.no-col')?.textContent || '';
        const nama = row.querySelector('textarea')?.value || '';

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
                    oninput="hitungTotalTW()">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw2" type="text" value="${tw.tw2 || ''}"
                    oninput="hitungTotalTW()">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw3" type="text" value="${tw.tw3 || ''}"
                    oninput="hitungTotalTW()">
            </td>
            <td style="border:1px solid #000;">
                <input class="tw4" type="text" value="${tw.tw4 || ''}"
                    oninput="hitungTotalTW()">
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
    const tbody = document.getElementById('hierarchical-budget-tbody');
    const rows = tbody.querySelectorAll('tr');
    
    const structure = {
        programs: []
    };
    
    let currentProgram = null;
    let currentKegiatan = null;
    
    rows.forEach(row => {
        const level = row.dataset.level || 'program';
        // Cari select (dropdown) atau textarea untuk nama
        const nameInput = row.querySelector('select[name*="_name"]') || row.querySelector('textarea[name*="_name"]');
        const amountInput = row.querySelector('input[name*="_amount"]');
        
        if (level === 'program') {
            currentProgram = {
                name: nameInput?.value || '',
                amount: (amountInput?.value || '0').replace(/\D/g, ''),
                kegiatan: []
            };
            structure.programs.push(currentProgram);
        } else if (level === 'kegiatan' && currentProgram) {
            currentKegiatan = {
                name: nameInput?.value || '',
                amount: (amountInput?.value || '0').replace(/\D/g, ''),
                subKegiatan: []
            };
            currentProgram.kegiatan.push(currentKegiatan);
        } else if (level === 'subkegiatan' && currentKegiatan) {
            const tw1Input = row.querySelector('input[name*="_tw1"]');
            const tw2Input = row.querySelector('input[name*="_tw2"]');
            const tw3Input = row.querySelector('input[name*="_tw3"]');
            const tw4Input = row.querySelector('input[name*="_tw4"]');
            
            currentKegiatan.subKegiatan.push({
                name: nameInput?.value || '',
                amount: (amountInput?.value || '0').replace(/\D/g, ''),
                tw1: (tw1Input?.value || '0').replace(/\D/g, ''),
                tw2: (tw2Input?.value || '0').replace(/\D/g, ''),
                tw3: (tw3Input?.value || '0').replace(/\D/g, ''),
                tw4: (tw4Input?.value || '0').replace(/\D/g, '')
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
document.getElementById('pihak2_jabatan').addEventListener('change', function() {
    const jabatan = this.value;
    const nameInput = document.getElementById('pihak2_name');
    const nipInput = document.getElementById('pihak2_nip');
    
    if (!jabatan || jabatan === 'Pilih Jabatan') {
        nameInput.value = '';
        nipInput.value = '';
        return;
    }
    
    // Fetch data user berdasarkan jabatan
    fetch(`/api/user-by-jabatan/${encodeURIComponent(jabatan)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                nameInput.value = data.data.nama;
                nipInput.value = data.data.nip;
            } else {
                nameInput.value = '';
                nipInput.value = '';
                showErrorMessage('Data user dengan jabatan "' + jabatan + '" tidak ditemukan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            nameInput.value = '';
            nipInput.value = '';
            showErrorMessage('Terjadi kesalahan saat mengambil data user');
        });
});

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
