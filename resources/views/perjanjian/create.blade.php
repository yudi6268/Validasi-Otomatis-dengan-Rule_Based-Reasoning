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
    }

    table th, table td {
        border: 1px solid #000;
        padding: 8px;
        font-size: 12px;
        text-align: center;
    }

    table td input {
        width: 100%;
        border: none;
        text-align: center;
        background: transparent;
        font-size: 12px;
    }

    table thead th {
        background: #efefef;
        font-weight: 600;
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
    
    <h3 style="text-align:center; margin-top:30px; font-size:15px;">INDIKATOR KINERJA INDIVIDU</h3>

    {{-- INPUT LAIN --}}
    <div style="margin-top:15px;">
        <label><strong>Jabatan:</strong></label>
        <input type="text" class="input-box" name="jabatan" placeholder="Masukkan Jabatan">
    </div>

    <div style="margin-top:15px;">
        <label><strong>Tugas:</strong></label>
        <textarea class="input-box" rows="2" name="tugas" placeholder="Masukkan Tugas"></textarea>
    </div>

    <div style="margin-top:15px;">
        <label><strong>Fungsi:</strong></label>
        <textarea class="input-box" rows="4" name="fungsi" placeholder="Masukkan Fungsi"></textarea>
    </div>

    {{-- TABEL A --}}
    <table id="tabelA">
        <thead>
            <tr>
                <th>NO</th>
                <th>SASARAN</th>
                <th>INDIKATOR KINERJA</th>
                <th>TARGET</th>
                <th>FORMULASI HITUNG</th>
                <th>SUMBER DATA</th>
            </tr>
            <tr>
                <th>(1)</th>
                <th>(2)</th>
                <th>(3)</th>
                <th>(4)</th>
                <th>(5)</th>
                <th>(6)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><input name="a_sasaran[]"></td>
                <td><input name="a_indikator[]"></td>
                <td><input name="a_target[]"></td>
                <td><input name="a_formula[]"></td>
                <td><input name="a_sumber[]"></td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL B --}}
    <table id="tabel1">
        <thead>
            <tr>
                <th>NO</th>
                <th>SASARAN</th>
                <th>INDIKATOR KERJA</th>
                <th>SATUAN</th>
                <th>TARGET</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><input name="b_sasaran[]"></td>
                <td><input name="b_indikator[]"></td>
                <td><input name="b_satuan[]"></td>
                <td><input name="b_target[]"></td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL C --}}
    <table id="tabel2">
        <thead>
            <tr>
                <th rowspan="2">SASARAN</th>
                <th rowspan="2">Indikator Kinerja</th>
                <th rowspan="2">Target</th>
                <th colspan="4">Target Triwulan</th>
            </tr>
            <tr>
                <th>I</th><th>II</th><th>III</th><th>IV</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input name="c_sasaran[]"></td>
                <td><input name="c_indikator[]"></td>
                <td><input name="c_target[]"></td>
                <td><input name="c_tw1[]"></td>
                <td><input name="c_tw2[]"></td>
                <td><input name="c_tw3[]"></td>
                <td><input name="c_tw4[]"></td>
            </tr>
        </tbody>
    </table>

    {{-- TABEL D --}}
    <table id="tabel3">
        <thead>
            <tr>
                <th>NO</th>
                <th>PROGRAM</th>
                <th>ANGGARAN</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><input name="d_program[]"></td>
                <td><input name="d_anggaran[]"></td>
                <td><input name="d_keterangan[]"></td>
            </tr>
        </tbody>
    </table>

    {{-- BUTTON --}}
    <div style="margin-top:25px; text-align:right;">
        <button class="save-btn" type="submit">SIMPAN</button>
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
<script>

function autoAddRow(tableId) {
    const table = document.getElementById(tableId).querySelector("tbody");
    const lastRow = table.rows[table.rows.length - 1];
    const inputs = lastRow.querySelectorAll("input");

    let filled = true;
    inputs.forEach(inp => { if (inp.value.trim() === "") filled = false; });

    if (filled) {
        const newRow = lastRow.cloneNode(true);
        const numberCell = newRow.cells[0];

        if (!isNaN(parseInt(numberCell.innerText))) {
            numberCell.innerText = table.rows.length + 1;
        }

        newRow.querySelectorAll("input").forEach(i => i.value = "");
        table.appendChild(newRow);
    }
}

["tabelA", "tabel1", "tabel2", "tabel3"].forEach(id => {
    document.getElementById(id).addEventListener("input", () => autoAddRow(id));
});



// ===== VALIDASI TTD =====
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

@if(session('success'))
<div style="position:fixed; top:20px; right:20px; background:#009970; 
    color:white; padding:12px 20px; border-radius:8px; 
    box-shadow:0 3px 10px rgba(0,0,0,0.2); z-index:9999;">
    {{ session('success') }}
</div>

<script>
    setTimeout(() => {
        document.querySelector('[style*="position:fixed"]').remove();
    }, 3000);
</script>
@endif

</script>

@endsection