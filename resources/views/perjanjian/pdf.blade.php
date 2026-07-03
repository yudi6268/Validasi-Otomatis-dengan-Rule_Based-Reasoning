<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Perjanjian Kinerja - PDF</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        /* F4 = 216mm x 330mm; margin ketat untuk fit content */
        @page {
            size: 216mm 330mm;
            margin: 8mm 8mm 10mm 8mm;
        }

        @page landscape {
            size: 330mm 216mm;
            margin: 8mm 10mm;
        }

        .page, 
        .page-landscape {
            width: 100%;
            margin: 0 auto;
            background: white;
            position: relative;
            padding: 0;
        }

        /* Batasi lebar konten agar tidak melebar melewati kertas F4 */
        .page {
            max-width: 216mm;
            padding: 8mm 6mm;
            width: 100%;
        }

        .page-landscape {
            max-width: 330mm;
            padding: 6mm 8mm;
            width: 100%;
            page-break-before: always;
        }
        
        /* Force landscape orientation for landscape pages */
        .page-landscape {
            size: 330mm 216mm;
        }

        .page-content {
            padding: 0;
        }

        .landscape-inner {
            width: 100%;
            max-width: 305mm;
            margin: 0 auto;
            padding: 0 2mm;
        }
                
        /* Untuk tabel anggaran dengan repeating header */
        @media print {
            body { 
                margin: 0;
                padding: 0;
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9.5px;
            margin: 0;
            color: #333;
            line-height: 1.25;
            background: white;
        }

        /* Rejected Stamp */
        .rejected-stamp {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #DC3545;
            color: white;
            padding: 8px 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            z-index: 100;
        }

        .rejection-notice {
            background: #fff3cd;
            padding: 10px;
            margin: 10px 0;
            page-break-inside: avoid;
        }

        .rejection-notice-title {
            color: #856404;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .rejection-notice-content {
            color: #856404;
            font-size: 8px;
            line-height: 1.4;
            padding: 8px 0;
            margin-top: 5px;
        }

        .rejection-notice-label {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 8mm;
            padding: 0;
        }
        .header-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 8mm;
        }
        .header-logos img {
            height: 65px;
            width: auto;
            display: block;
        }
        .header h2 {
            font-size: 10px;
            font-weight: bold;
            margin: 0;
            line-height: 1.25;
        }

        /* CONTENT */
        .content-intro {
            text-align: justify;
            margin-bottom: 6mm;
            font-size: 9px;
            line-height: 1.25;
        }

        .parties {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .party {
            display: table-cell;
            width: 50%;
            font-size: 9px;
            padding: 0;
            vertical-align: top;
        }
        .party-label {
            margin-top: 4px;
            text-align: center;
            font-size: 9px;
        }

        /* TITLES */
        .section-title {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin: 10px 0 8px 0;
            page-break-inside: avoid;
        }

        /* TABLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 8px;
            page-break-inside: auto;
            table-layout: fixed;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        table th,table td {
            padding: 2px 2px;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
            overflow-wrap: break-word;
            vertical-align: top;
            word-break: break-word;
        }

        table th {
            background-color: transparent;
            font-weight: bold;
            border-bottom: 2px solid #333;
        }

        .no-col {
            width: 20px !important;
            text-align: center;
        }

        .total-row {
            background: #f0f0f0;
            font-weight: bold;
        }

        /* SIGNATURE */
        .signature-block {
            display: block;
            width: 100%;
            page-break-inside: avoid;
            page-break-before: auto;
            margin-top: 6mm;
            margin-bottom: 0;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15mm;
            flex-wrap: nowrap;
        }

        .signature-col {
            flex: 1;
            width: 48%;
            min-width: 45%;
            text-align: center;
            font-size: 9px;
            page-break-inside: avoid;
        }

        /* Pastikan blok yang harus utuh tidak terbelah halaman */
        .keep-together {
            page-break-inside: avoid;
            page-break-before: auto;
        }

        .sig-title {
            margin-bottom: 6px;
            font-size: 10px;
            font-weight: bold;
        }

        .sig-date {
            margin-bottom: 5px;
            font-size: 9px;
        }

        .sig-img {
            height: 35px;
            max-width: 120px;
            margin: 0 auto;
            display: block;
        }

        .sig-space {
            height: 35px;
        }

        .sig-name {
            font-size: 10px;
            display: block;
            margin-top: 4px;
        }

        .sig-nip {
            font-size: 9px;
            line-height: 1.3;
        }

        img {
            max-width: 100%;
            height: auto;
            object-fit: contain;
            display: block;
        }
        
        /* Tabel Anggaran - Optimize untuk landscape */
        .anggaran-table {
            width: 100% !important;
            max-width: 100% !important;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 7px;
        }
        
        .anggaran-table th,
        .anggaran-table td {
            padding: 2px 3px !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 7px !important;
        }
        
        .anggaran-table .no-col {
            width: 6% !important;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- =====================
         PAGE 1 — COVER
         ===================== -->
    <div class="page">
        <div class="page-content">

        <!-- Rejected Stamp -->
        @if(!empty($data->rejected) && $data->rejected == true)
            <div class="rejected-stamp">
                PERJANJIAN KINERJA DITOLAK
            </div>
        @endif

        <div class="header">
            <div class="header-logos">
                @if(!empty($logoPemda))
                    <img src="{{ $logoPemda }}" alt="Logo Pemerintah Kabupaten Pasuruan">
                @endif
                @if(!empty($logoRsud))
                    <img src="{{ $logoRsud }}" alt="Logo RSUD Bangil">
                @endif
            </div>
            <h2>
                PERJANJIAN KINERJA TAHUN 2025<br>
                {{ strtoupper($data->pihak1_jabatan ?? 'WADIR PELAYANAN') }}<br>
                UOBK RSUD BANGIL<br>
                KABUPATEN PASURUAN
            </h2>
        </div>

        <!-- Rejection Notice -->
        @if(!empty($data->rejected) && $data->rejected == true && !empty($data->rejection_reason))
            <div class="rejection-notice">
                <div class="rejection-notice-title">
                    ⚠ CATATAN PENOLAKAN PERJANJIAN KINERJA
                </div>
                <div class="rejection-notice-content">
                    <div class="rejection-notice-label">Alasan Penolakan:</div>
                    <div style="margin-top: 6px; font-style: italic;">
                        "{{ $data->rejection_reason }}"
                    </div>
                </div>
            </div>
        @endif

        <div class="content-intro">
            Dalam rangka mewujudkan manajemen pemerintahan yang efektif,
            transparan dan akuntabel serta berorientasi pada hasil,
            kami yang bertanda tangan dibawah ini :
        </div>

        <div style="margin: 15px 0; font-size: 10px; line-height: 1.8;">
            <div style="margin-bottom: 3px;">
                Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data->pihak1_name ?? '-' }}
            </div>
            <div style="margin-bottom: 3px;">
                Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data->pihak1_jabatan ?? '-' }}
            </div>
            <div style="margin-bottom: 12px; font-size: 9px;">
                Selanjutnya disebut pihak pertama.
            </div>
            
            <div style="margin-bottom: 3px;">
                Nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data->pihak2_name ?? '-' }}
            </div>
            <div style="margin-bottom: 3px;">
                Jabatan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $data->pihak2_jabatan ?? '-' }}
            </div>
            <div style="margin-bottom: 8px; font-size: 9px;">
                Selaku atasan pihak pertama, selanjutnya disebut pihak kedua.
            </div>
        </div>

        <div class="content-intro">
            Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini,
            dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan
            dalam dokumen perencanaan. Keberhasilan dan kegagalan pencapaian target kinerja tersebut menjadi tanggung jawab kami.
        </div>

        <div class="content-intro">
            Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan
            yang diperlukan dalam rangka pemberian penghargaan dan sanksi.
        </div>

       <div class="signature-block">
        <div class="signature-row">
            <div class="signature-col">
                <div class="sig-title">{{ $data->pihak2_jabatan ?? 'Direktur' }}</div>

                @if(!empty($pihak2_ttd_data))
                    <img src="{{ $pihak2_ttd_data }}" class="sig-img" alt="TTD Pihak 2">
                @elseif($data->pihak2_signature)
                    <img src="{{ $data->pihak2_signature }}" class="sig-img" alt="TTD Pihak 2">
                @else
                    <div class="sig-space"></div>
                @endif

                <div class="sig-name"><u>{{ $data->pihak2_name ?? '-' }}</u></div>
                <div class="sig-nip">{{ $data->pihak2_pangkat ?? 'Pembina Utama Muda' }}<br>NIP. {{ $data->pihak2_nip ?? '-' }}</div>
            </div>

            <div class="signature-col">
                <div class="sig-date">
                    {{ $data->location ?? 'Pasuruan' }},
                    {{ isset($data->agreement_date)
                        ? \Carbon\Carbon::parse($data->agreement_date)->format('d F Y')
                        : now()->format('d F Y') }}
                </div>

                <div class="sig-title">{{ $data->pihak1_jabatan ?? 'Wadir Pelayanan' }}</div>

                @if(!empty($pihak1_ttd_data))
                    <img src="{{ $pihak1_ttd_data }}" class="sig-img" alt="TTD Pihak 1">
                @elseif($data->pihak1_ttd)
                    <img src="{{ $data->pihak1_ttd }}" class="sig-img" alt="TTD Pihak 1">
                @else
                    <div class="sig-space"></div>
                @endif

                <div class="sig-name"><u>{{ $data->pihak1_name ?? '-' }}</u></div>
                <div class="sig-nip">{{ $data->pihak1_pangkat ?? 'Pembina' }}<br>NIP. {{ $data->pihak1_nip ?? '-' }}</div>
            </div>
        </div>
    </div>
<div class="page-break"></div>

    <!-- =====================
         PAGE 2 — TUGAS DAN FUNGSI
         ===================== -->
    <div class="page">
        <h3 style="font-size: 11px; margin-bottom: 8px; text-align: center;">INDIKATOR KINERJA INDIVIDU</h3>
        <h3 style="font-size: 11px; margin-bottom: 8px; text-align: center;">UOBK RSUD BANGIL</h3>
        <h3 style="font-size: 11px; margin-bottom: 12px; text-align: center;">TAHUN {{ date('Y') }}</h3>

        @php
            $tugasValue = $data->tugas_pelaksana;
            if ($tugasValue === null || $tugasValue === '') {
                $tugasValue = $data->tugas ?: '-';
            }
            if (is_array($tugasValue)) {
                $tugasValue = implode("\n", $tugasValue);
            } elseif (is_string($tugasValue)) {
                $decodedTugas = json_decode($tugasValue, true);
                if (is_array($decodedTugas)) {
                    $tugasValue = implode("\n", $decodedTugas);
                }
            }
            $fungsiValue = $data->fungsi_pelaksana;
            if ($fungsiValue === null || $fungsiValue === '') {
                $fungsiValue = $data->fungsi ?: '-';
            }
        @endphp

        <!-- TABLE TANPA BORDER UNTUK JABATAN, TUGAS, FUNGSI -->
        <table style="width:100%;border-collapse:collapse;border:0;font-size:9px;margin-bottom:10px;line-height:1.2;">
            <!-- JABATAN -->
            <tr style="border:0;">
                <td style="width:80px;padding:4px 0;font-weight:500;vertical-align:top;border:none;">Jabatan</td>
                <td style="width:10px;padding:4px 4px;text-align:center;border:none;">:</td>
                <td style="padding:4px 0;vertical-align:top;border:none;">{{ $data->pihak1_jabatan ?? '-' }}</td>
            </tr>

            <!-- TUGAS -->
            <tr style="border:0;">
                <td style="width:80px;padding:4px 0;font-weight:500;vertical-align:top;border:none;">Tugas</td>
                <td style="width:10px;padding:4px 4px;text-align:center;border:none;">:</td>
                <td style="padding:4px 0;vertical-align:top;text-align:justify;line-height:1.2;border:none;">{!! nl2br(e($tugasValue)) !!}</td>
            </tr>

            <!-- FUNGSI -->
            <tr style="border:0;">
                <td style="width:80px;padding:4px 0;font-weight:500;vertical-align:top;border:none;">Fungsi</td>
                <td style="width:10px;padding:4px 4px;text-align:center;border:none;">:</td>
                <td style="padding:4px 0;vertical-align:top;line-height:1.2;border:none;">
                    @php
                        $fungsiItems = [];
                        if (is_array($fungsiValue)) {
                            $fungsiItems = $fungsiValue;
                        } elseif (is_string($fungsiValue)) {
                            $decoded = json_decode($fungsiValue, true);
                            if (is_array($decoded)) {
                                $fungsiItems = $decoded;
                            } else {
                                $fungsiItems = preg_split("/\r\n|\n|\r/", $fungsiValue);
                            }
                        }
                        $fungsiItems = array_values(array_filter(array_map(function($s){
                            return is_string($s) ? trim($s) : '';
                        }, (array)$fungsiItems), function($s){ return $s !== ''; }));
                        
                        $rawFungsiStr = is_string($fungsiValue) ? trim($fungsiValue) : '';
                        if ($rawFungsiStr === '-' || (count($fungsiItems) === 1 && $fungsiItems[0] === '-')) {
                            $fungsiItems = [];
                            $fungsiValue = '-';
                        }
                    @endphp
                    @if(!empty($fungsiItems))
                        <ol style="margin:0;padding-left:16px;list-style-type:lower-alpha;line-height:1.2;">
                            @foreach($fungsiItems as $fi)
                                @php
                                    $cleanFi = preg_replace('/^[a-zA-Z]\.\s*/', '', trim((string)$fi));
                                @endphp
                                <li style="margin-bottom:2px;text-align:justify;">{{ $cleanFi }}</li>
                            @endforeach
                        </ol>
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>
    </div>
<div class="page-break"></div>

    <!-- =====================
         PAGE 3 — TABEL ANGGARAN (tanpa target triwulan) - LANDSCAPE
         ===================== -->
    <div class="page-landscape">
        <div class="landscape-inner">
        <div class="page-content">
        <h3 style="font-size: 11px; margin-bottom: 6mm; text-align: center;">RENCANA ANGGARAN</h3>

        @php
            $tabelC = is_array($data->tabelC) ? $data->tabelC : json_decode($data->tabelC, true);
            $totalAnggaran = 0;
            $isHierarchical = isset($tabelC['programs']);
        @endphp

        @if($isHierarchical && count($tabelC['programs']) > 0)
            <table class="anggaran-table" style="font-size: 7px; width: 100%; border-collapse: collapse; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="no-col" style="width: 6%; padding: 2px; border: 1px solid #000; font-size: 7px;">NO</th>
                        <th style="width: 69%; padding: 2px; border: 1px solid #000; font-size: 7px;">PROGRAM / KEGIATAN / SUB KEGIATAN</th>
                        <th style="width: 25%; padding: 2px; border: 1px solid #000; font-size: 7px;">ANGGARAN (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tabelC['programs'] as $p)
                    @php
                        $programNum = $loop->iteration;
                        $programTotal = 0;

                        foreach ($p['kegiatan'] ?? [] as $k) {
                            foreach ($k['subKegiatan'] ?? [] as $s) {
                                $programTotal += (int) ($s['amount'] ?? 0);
                            }
                        }

                        $totalAnggaran += $programTotal;
                    @endphp

                    <!-- PROGRAM -->
                    <tr style="background:white;font-weight:bold;color:#000; border: 1px solid #000;">
                        <td class="no-col" style="padding: 2px; text-align: center; border: 1px solid #000; font-size: 7px;">{{ $programNum }}</td>
                        <td style="font-size: 7px; padding: 2px; border: 1px solid #000;">{{ $p['name'] }}</td>
                        <td style="text-align:right; font-size: 7px; padding: 2px; border: 1px solid #000;">{{ number_format($programTotal,0,',','.') }}</td>
                    </tr>

                    <!-- KEGIATAN -->
                   @foreach(($p['kegiatan'] ?? []) as $kIndex => $k)
                        @php
                            $kTotal = 0;
                            foreach ($k['subKegiatan'] ?? [] as $s) {
                                $kTotal += (int)($s['amount'] ?? 0);
                            }
                            $kegiatanNum = $programNum . '.' . ($kIndex + 1);
                        @endphp

                        <tr style="color:#000; border: 1px solid #000;">
                            <td class="no-col" style="padding: 2px; text-align: center; border: 1px solid #000; font-size: 6.5px;">{{ $kegiatanNum }}</td>
                            <td style="padding-left:15px; font-size: 6.5px; padding: 2px; border: 1px solid #000;">{{ $k['name'] }}</td>
                            <td style="text-align:right; font-size: 6.5px; padding: 2px; border: 1px solid #000;">{{ number_format($kTotal,0,',','.') }}</td>
                        </tr>

                        <!-- SUB KEGIATAN -->
                        @foreach($k['subKegiatan'] as $sIndex => $s)
                            @php
                                $subKegiatanNum = $kegiatanNum . '.' . ($sIndex + 1);
                            @endphp
                            <tr style="color:#000; border: 1px solid #000;">
                                <td class="no-col" style="font-size: 6.5px; padding: 2px; text-align: center; border: 1px solid #000;">{{ $subKegiatanNum }}</td>
                                <td style="padding-left:25px; font-size: 6.5px; padding: 2px; border: 1px solid #000;">{{ $s['name'] }}</td>
                                <td style="text-align:right; font-size: 6.5px; padding: 2px; border: 1px solid #000;">{{ number_format((int)$s['amount'],0,',','.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                @endforeach

                <tr class="total-row" style="border: 1px solid #000;">
                    <td colspan="2" style="text-align:right; font-size: 7px; padding: 2px; border: 1px solid #000;"><strong>TOTAL ANGGARAN :</strong></td>
                    <td style="text-align:right; font-size: 7px; padding: 2px; border: 1px solid #000;"><strong>{{ number_format($totalAnggaran, 0, ',', '.') }}</strong></td>
                </tr>
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 15px; font-size: 9px;">Tidak ada data Rencana Anggaran</div>
        @endif
    
        <!-- SIGNATURES PAGE 2 -->
        <div class="signature-block">
            <div class="signature-row">
                <div class="signature-col">
                    <div class="sig-title">{{ $data->pihak2_jabatan ?? 'Direktur' }}</div>
                    @if(!empty($pihak2_ttd_data))
                        <img src="{{ $pihak2_ttd_data }}" class="sig-img" alt="TTD Pihak 2">
                    @elseif($data->pihak2_signature)
                        <img src="{{ $data->pihak2_signature }}" class="sig-img" alt="TTD Pihak 2">
                    @else
                        <div class="sig-space"></div>
                    @endif
                    <div class="sig-name"><u>{{ $data->pihak2_name ?? '-' }}</u></div>
                    <div class="sig-nip">{{ $data->pihak2_pangkat ?? 'Pembina Utama Muda' }}<br>NIP. {{ $data->pihak2_nip ?? '-' }}</div>
                </div>
                <div class="signature-col">
                    <div class="sig-date">
                        {{ $data->location ?? 'Pasuruan' }}, {{ isset($data->agreement_date) ? \Carbon\Carbon::parse($data->agreement_date)->format('d F Y') : now()->format('d F Y') }}
                    </div>
                    <div class="sig-title">{{ $data->pihak1_jabatan ?? 'Wadir Pelayanan' }}</div>
                    @if(!empty($pihak1_ttd_data))
                        <img src="{{ $pihak1_ttd_data }}" class="sig-img" alt="TTD Pihak 1">
                    @elseif($data->pihak1_ttd)
                        <img src="{{ $data->pihak1_ttd }}" class="sig-img" alt="TTD Pihak 1">
                    @else
                        <div class="sig-space"></div>
                    @endif
                    <div class="sig-name"><u>{{ $data->pihak1_name ?? '-' }}</u></div>
                    <div class="sig-nip">{{ $data->pihak1_pangkat ?? 'Pembina' }}<br>NIP. {{ $data->pihak1_nip ?? '-' }}</div>
                </div>
            </div>
        </div>
        </div>
        </div>
</div>
 <div class="page-break"></div>

    <!-- =====================
         PAGE 3 — TABEL A (Indikator Kinerja) + TABEL B (Rencana Aksi dengan Target Triwulan) - LANDSCAPE
         ===================== -->
    <div class="page-landscape">
        <div class="landscape-inner">
            <div class="page-content">
            <h3 style="font-size: 11px; margin-bottom: 6mm; text-align: center;">INDIKATOR KINERJA & RENCANA AKSI</h3>

        <!-- TABEL A: INDIKATOR KINERJA INDIVIDU -->
        @php
            $tabelA = is_array($data->tabelA) ? $data->tabelA : json_decode($data->tabelA, true);
        @endphp

        @if(!empty($tabelA['indikator']) && count($tabelA['indikator']) > 0)
            <table style="border-collapse: collapse; width: 100%; margin-bottom: 6mm; font-size: 7.5px; table-layout: fixed;">
                <thead>
                    <tr>
                        <th class="no-col" style="width: 5%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 3px; font-size: 7px;">NO</th>
                        <th style="width: 22%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 3px; font-size: 7px;">SASARAN</th>
                        <th style="width: 38%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 3px; font-size: 7px;">INDIKATOR KINERJA</th>
                        <th style="width: 12%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 3px; font-size: 7px;">SATUAN</th>
                        <th style="width: 11%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 3px; font-size: 7px;">TARGET</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tabelA['indikator'] as $idx => $indikator)
                    <tr style="background: white; color: #000;">
                        <td class="no-col" style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $idx + 1 }}</td>
                        <td style="border: 1px solid #000; padding: 2px; font-size: 7px; word-wrap: break-word;">{{ $tabelA['sasaran'][$idx] ?? '-' }}</td>
                        <td style="border: 1px solid #000; padding: 2px; font-size: 7px; word-wrap: break-word;">{{ $indikator ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelA['satuan'][$idx] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelA['target'][$idx] ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 15px; font-size: 9px;">Tidak ada data Indikator Kinerja</div>
        @endif

        <!-- TABEL B: RENCANA AKSI (Target Triwulan) -->
        @php
            $tabelB = is_array($data->tabelB) ? $data->tabelB : json_decode($data->tabelB, true);
        @endphp

        @if(!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0)
           <table style="border-collapse: collapse; width: 100%; table-layout: fixed; font-size: 7.5px;">
                <thead>
                    <tr>
                        <th class="no-col" style="width: 5%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">NO</th>
                        <th style="width: 20%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">SASARAN</th>
                        <th style="width: 30%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">INDIKATOR KINERJA</th>
                        <th style="width: 9%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">TARGET</th>
                        <th style="width: 9%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">TW I</th>
                        <th style="width: 9%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">TW II</th>
                        <th style="width: 9%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">TW III</th>
                        <th style="width: 9%; background: #e0e0e0; color: #000; border: 1px solid #000; padding: 2px; font-size: 7px;">TW IV</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tabelB['sasaran'] as $idx => $sasaran)
                    <tr style="background: white; color: #000;">
                        <td class="no-col" style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $idx + 1 }}</td>
                        <td style="border: 1px solid #000; padding: 2px; font-size: 7px; word-wrap: break-word;">{{ $sasaran ?? '-' }}</td>
                        <td style="border: 1px solid #000; padding: 2px; font-size: 7px; word-wrap: break-word;">{{ $tabelB['indikator'][$idx] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelB['target'][$idx] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelB['tw1'][$idx] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelB['tw2'][$idx] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelB['tw3'][$idx] ?? '-' }}</td>
                        <td style="text-align: center; border: 1px solid #000; padding: 2px; font-size: 7px;">{{ $tabelB['tw4'][$idx] ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 15px; font-size: 9px;">Tidak ada data Rencana Aksi</div>
        @endif

        <!-- SIGNATURES PAGE 3 -->
        <div class="signature-block">
            <div class="signature-row">
                <div class="signature-col">
                    <div style="margin-bottom: 8px; font-size: 10px;">{{ $data->pihak2_jabatan ?? 'Direktur' }}</div>
                    @if(!empty($pihak2_ttd_data))
                        <img src="{{ $pihak2_ttd_data }}" style="height: 35px; max-width: 120px; margin: 0 auto;">
                    @elseif($data->pihak2_signature)
                        <img src="{{ $data->pihak2_signature }}" style="height: 35px; max-width: 120px; margin: 0 auto;">
                    @else
                        <div style="height: 35px;"></div>
                    @endif
                    <br><u style="font-size: 10px;">{{ $data->pihak2_name ?? '-' }}</u><br>
                    <span style="font-size: 9px;">{{ $data->pihak2_pangkat ?? 'Pembina Utama Muda' }}<br>NIP. {{ $data->pihak2_nip ?? '-' }}</span>
                </div>
                <div class="signature-col">
                    <div style="margin-bottom: 5px; font-size: 9px;">
                        {{ $data->location ?? 'Pasuruan' }}, {{ isset($data->agreement_date) ? \Carbon\Carbon::parse($data->agreement_date)->format('d F Y') : now()->format('d F Y') }}
                    </div>
                    <div style="margin-bottom: 8px; font-size: 10px;">{{ $data->pihak1_jabatan ?? 'Wadir Pelayanan' }}</div>
                    @if(!empty($pihak1_ttd_data))
                        <img src="{{ $pihak1_ttd_data }}" style="height: 35px; max-width: 120px; margin: 0 auto;">
                    @elseif($data->pihak1_ttd)
                        <img src="{{ $data->pihak1_ttd }}" style="height: 35px; max-width: 120px; margin: 0 auto;">
                    @else
                        <div style="height: 35px;"></div>
                    @endif
                    <br><u style="font-size: 10px;">{{ $data->pihak1_name ?? '-' }}</u><br>
                    <span style="font-size: 9px;">{{ $data->pihak1_pangkat ?? 'Pembina' }}<br>NIP. {{ $data->pihak1_nip ?? '-' }}</span>
                </div>
            </div>
        </div>
</div>
</div>
</body>
</html>