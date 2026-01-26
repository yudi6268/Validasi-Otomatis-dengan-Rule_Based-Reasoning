@extends('layouts.app')

@section('title', 'Form Perjanjian')

@section('back')
<a href="{{ url()->previous() }}" style="text-decoration:none; color:#009970; font-size:20px;">
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

    table th:first-child,
table td:first-child {
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
            <input type="text" class="input-box" name="pihak2_name" placeholder="Nama lengkap">

            <select class="input-box" name="pihak2_jabatan">
                <option disabled selected>Pilih Jabatan</option>
                <option>Direktur</option>
                <option>Wadir Umum dan Keuangan</option>
                <option>Wadir Pelayanan</option>
            </select>

            <p style="text-align:center; font-size:12px; margin-top:3px;">
                Selanjutnya disebut <b>PIHAK KEDUA</b>.
            </p>
        </div>
    </div>

    {{-- PENJELASAN --}}
    <p style="text-align:justify; margin-top:18px;"> Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan. </p> 
    <p style="text-align:justify;"> Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi. </p> 
    
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
                <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this, 'tabelA')" title="Hapus">🗑</button>
                <button type="button" class="table-action-btn add-btn" onclick="addRow('tabelA')" title="Tambah">➕</button>
            </td>
        </tr>
    </tbody>
</table>

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
        <tr>
            <td style="width: 25%;"><textarea name="c_sasaran[]"></textarea></td>
            <td style="width: 20%;"><textarea name="c_indikator[]"></textarea></td>
            <td style="width: 12%;"><textarea name="c_target[]"></textarea></td>
            <td style="width: 10%;"><textarea name="c_tw1[]"></textarea></td>
            <td style="width: 10%;"><textarea name="c_tw2[]"></textarea></td>
            <td style="width: 10%;"><textarea name="c_tw3[]"></textarea></td>
            <td style="width: 10%;"><textarea name="c_tw4[]"></textarea></td>
            <td style="width: 60px;">
                <button type="button" class="table-action-btn delete-btn" onclick="deleteRow(this, 'tabel2')" title="Hapus">🗑</button>
                <button type="button" class="table-action-btn add-btn" onclick="addRow('tabel2')" title="Tambah">➕</button>
            </td>
        </tr>
    </tbody>
</table>

{{-- TABEL D: HIERARCHICAL BUDGET (PROGRAM -> KEGIATAN -> SUB KEGIATAN) --}}

<table id="tabel3" style="table-layout: fixed; width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 50px; border: 1px solid #000; padding: 8px; text-align: center;">NO</th>
            <th style="border: 1px solid #000; padding: 8px; text-align: center;">PROGRAM / KEGIATAN / SUB KEGIATAN</th>
            <th style="width: 180px; border: 1px solid #000; padding: 8px; text-align: center;">ANGGARAN (Rp)</th>
            <th style="width: 140px; border: 1px solid #000; padding: 8px; text-align: center;">KETERANGAN</th>
            <th style="width: 90px; border: 1px solid #000; padding: 8px; text-align: center;">AKSI</th>
        </tr>
    </thead>
    <tbody id="hierarchical-budget-tbody">
        <!-- Row will be dynamically added via addProgram() -->
    </tbody>
</table>

<!-- Total Budget Summary -->
<div style="margin-top: 15px; padding: 15px; background: #e8f5e9; border-radius: 8px; border-left: 4px solid #009970;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <label style="font-weight: 600; font-size: 14px;">TOTAL ANGGARAN KESELURUHAN:</label>
        <input type="text" id="totalBudget" readonly style="background: transparent; border: none; font-weight: bold; font-size: 16px; color: #009970; text-align: right; width: 250px; padding: 4px;">
    </div>
</div>

<!-- Hidden inputs for form submission (will store hierarchical data) -->
<input type="hidden" id="hierarchical-budget-json" name="hierarchical_budget_json" value="[]">

    {{-- BUTTON --}}
    <div style="margin-top:25px; text-align:right; display:flex; gap:10px; justify-content:space-between;">
        <button type="button" class="save-btn" onclick="addProgram()" style="background:#17a2b8;">
            ➕ TAMBAH PROGRAM
        </button>
        <button class="save-btn" type="submit" onclick="saveToSupabase(event)" style="background:#009970;">
            💾 SIMPAN
        </button>
    </div>
    </form>
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
<script src="{{ asset('js/hierarchical-budget.js') }}"></script>

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

// =========================
// TAMBAH BARIS OTOMATIS
// =========================
function addRow(tableId) {
    const table = document.getElementById(tableId);
    const tbody = table.querySelector("tbody");
    const lastRow = tbody.rows[tbody.rows.length - 1];
    const newRow = lastRow.cloneNode(true);
    
    // Reset values
    newRow.querySelectorAll("input, textarea").forEach(el => {
        el.value = "";
    });

    // Update NO otomatis
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
