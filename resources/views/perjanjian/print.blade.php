<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perjanjian Kinerja</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 12px;
        }
        body {
            background: #f5f5f5;
            margin: 0;
            padding: 10px;
        }
        .page {
            width: 210mm;
            height: 330mm;
            margin: 10mm auto;
            background: white;
            padding: 25mm 20mm;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            page-break-after: always;
            page-break-inside: avoid;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #009970;
            padding-bottom: 15px;
        }
        .logo {
            height: 60px;
            margin-bottom: 8px;
        }
        .header h1 {
            font-size: 12px;
            font-weight: 600;
            margin: 3px 0;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            text-align: center;
            line-height: 1.5;
        }
        .header p {
            font-size: 12px;
            margin: 2px 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .content-section {
            margin-top: 15px;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .section-title {
            font-size: 12px;
            font-weight: 600;
            background: #009970;
            color: white;
            padding: 8px 12px;
            margin-bottom: 10px;
            margin-top: 10px;
            font-family: Arial, sans-serif;
            text-align: center;
            border-radius: 0;
        }
        .parties {
            display: flex;
            gap: 40px;
            margin-bottom: 25px;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .party {
            flex: 1;
            font-size: 12px;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }
        .party strong {
            display: block;
            margin-bottom: 3px;
        }
        .party-name {
            font-weight: 600;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            margin-top: 10px;
            font-size: 12px;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table th {
            background: #009970;
            color: white;
            font-weight: 600;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        table td {
            vertical-align: top;
            line-height: 1.5;
        }
        .no-data {
            text-align: center;
            padding: 15px;
            color: #999;
            font-style: italic;
            font-size: 12px;
        }
        p {
            text-align: justify;
            margin: 10px 0;
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h3 {
            text-align: center;
            margin: 15px 0 10px 0;
            font-size: 12px;
            font-weight: 600;
            font-family: Arial, sans-serif;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            font-size: 12px;
        }
        .signature-block {
            text-align: center;
            width: 45%;
        }
        .signature-block .sig-line {
            height: 80px;
            margin: 20px 0;
            border-bottom: 1px solid #000;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .signature-block .sig-line img {
            max-height: 70px;
            max-width: 100%;
        }
        .signature-block .name {
            font-weight: 600;
            margin-top: 5px;
        }
        .page-break {
            page-break-after: always;
            margin-top: 40px;
        }
        @media print {
            * {
                margin: 0;
                padding: 0;
            }
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            .page {
                width: 210mm;
                height: 330mm;
                margin: 0;
                padding: 25mm 20mm;
                box-shadow: none;
                page-break-after: always;
                page-break-inside: avoid;
            }
            /* Ensure signature elements display correctly in PDF */
            .sig-nama {
                font-weight: 600;
                font-size: 12px;
                margin-bottom: 2px;
                word-wrap: break-word;
                display: inline-block;
                max-width: 100%;
            }
            .sig-garis {
                border-bottom: 1px solid #000;
                margin: 2px 0 3px 0;
                display: block;
                min-width: 80px;
                height: 0;
            }
        }
        @page {
            size: 210mm 330mm;
            margin: 0;
            padding: 0;
        }
        /* Signature styling for proper alignment and garis width */
        .sig-nama {
            font-weight: 600;
            font-size: 12px;
            margin: 0 auto 1px auto; /* 1px spacing under name */
            word-wrap: break-word;
            text-align: center;
            max-width: 100%;
            display: block;
            line-height: 1.0; /* tighten spacing so garis sits right under nama */
        }
        .sig-garis {
            border-bottom: 1px solid #000;
            margin: 0 auto 1px auto; /* 1px gap */
            display: block;
            width: auto;
            min-width: 60px;
            max-width: 220px; /* allow longer names to have wider garis */
            height: 0;
        }
    </style>
</head>
<body>
    @php
        use Illuminate\Support\Str;
        $perjanjian = $data;
        $tabelA = json_decode($perjanjian->tabelA ?? '[]', true);
        $tabelB = json_decode($perjanjian->tabelB ?? '[]', true);
        $tabelC = json_decode($perjanjian->tabelC ?? '[]', true);
    @endphp

    <!-- PAGE 1: HEADER & PARTIES -->
    <div class="page">
        <div class="header">
            @php
                // prefer inline base64 for PDF if available
                $logoSrc = null;
                if(!empty($logo_data)){
                    $logoSrc = $logo_data;
                } else {
                    if(!empty($for_pdf) && file_exists(public_path('images/logo_pemda.png'))){
                        $logoSrc = public_path('images/logo_pemda.png');
                    } elseif(file_exists(public_path('images/logo_pemda.png'))){
                        $logoSrc = asset('images/logo_pemda.png');
                    }
                }
            @endphp
            @if(!empty($logoSrc))
                <img src="{{ $logoSrc }}" class="logo" alt="Logo">
            @endif
            <h1>PERJANJIAN KINERJA TAHUN 2025</h1>
            <h1>WAKIL DIREKTUR PELAYANAN</h1>
            <p>UOBK RSUD BANGIL KABUPATEN PASURUAN</p>
        </div>

        <p style="text-align: justify; font-size: 12px; line-height: 1.6;">
            Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil,
            kami yang bertanda tangan dibawah ini :
        </p>

        <!-- PARTIES: 2-COLUMN TABLE FORMAT -->
        <table style="width: 100%; border: none; margin: 20px 0; font-size: 12px;">
            <tr>
                <td style="border: none; width: 50%; padding: 0; vertical-align: top; font-weight: 600;">
                    PIHAK PERTAMA
                </td>
                <td style="border: none; width: 50%; padding: 0; vertical-align: top; font-weight: 600;">
                    PIHAK KEDUA
                </td>
            </tr>
            <tr>
                <td style="border: none; padding: 0; vertical-align: top;">
                    Nama: <br>
                    <strong>{{ $perjanjian->pihak1_name ?? '-' }}</strong><br><br>
                    Jabatan: <br>
                    <strong>{{ $perjanjian->pihak1_jabatan ?? '-' }}</strong>
                </td>
                <td style="border: none; padding: 0; vertical-align: top;">
                    Nama: <br>
                    <strong>{{ $perjanjian->pihak2_name ?? '-' }}</strong><br><br>
                    Jabatan: <br>
                    <strong>{{ $perjanjian->pihak2_jabatan ?? '-' }}</strong>
                </td>
            </tr>
        </table>

        <p style="text-align: justify; font-size: 12px; line-height: 1.6;">
            Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan.
        </p>

        <p style="text-align: justify; font-size: 12px; line-height: 1.6;">
            Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.
        </p>

        <!-- TABEL A: INDIKATOR KINERJA INDIVIDU -->
        <div class="content-section">
            <div class="section-title">INDIKATOR KINERJA INDIVIDU</div>
            @if(!empty($tabelA['indikator']) && count($tabelA['indikator']) > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30px;">NO</th>
                            <th>INDIKATOR KINERJA</th>
                            <th style="width: 80px;">SATUAN</th>
                            <th style="width: 80px;">TARGET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tabelA['indikator'] as $idx => $indikator)
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ $indikator ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelA['satuan'][$idx] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelA['target'][$idx] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada data Indikator Kinerja</div>
            @endif
        </div>
    </div>

    <!-- PAGE 2: TABEL B & C -->
    <div class="page page-break">
        <!-- TABEL B: RENCANA AKSI -->
        <div class="content-section">
            <div class="section-title">RENCANA AKSI</div>
            @if(!empty($tabelB['sasaran']) && count($tabelB['sasaran']) > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30px;">NO</th>
                            <th>SASARAN</th>
                            <th>INDIKATOR KINERJA</th>
                            <th style="width: 60px;">TARGET</th>
                            <th style="width: 50px;">TW 1</th>
                            <th style="width: 50px;">TW 2</th>
                            <th style="width: 50px;">TW 3</th>
                            <th style="width: 50px;">TW 4</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tabelB['sasaran'] as $idx => $sasaran)
                            <tr>
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                                <td>{{ $sasaran ?? '-' }}</td>
                                <td>{{ $tabelB['indikator'][$idx] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelB['target'][$idx] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelB['tw1'][$idx] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelB['tw2'][$idx] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelB['tw3'][$idx] ?? '-' }}</td>
                                <td style="text-align: center;">{{ $tabelB['tw4'][$idx] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada data Rencana Aksi</div>
            @endif
        </div>

        <!-- TABEL C: RENCANA ANGGARAN -->
        <div class="content-section">
            <div class="section-title">RENCANA ANGGARAN</div>
            @if(is_array($tabelC) && isset($tabelC['programs']) && count($tabelC['programs']) > 0)
                {{-- Hierarchical structure: programs > kegiatan > subKegiatan --}}
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">NO</th>
                            <th>PROGRAM / KEGIATAN / SUB KEGIATAN</th>
                            <th style="width: 150px;">ANGGARAN (Rp)</th>
                            <th style="width: 120px;">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($tabelC['programs'] as $pIdx => $program)
                            <!-- PROGRAM ROW -->
                            <tr style="background: #f9f9f9; font-weight: 600;">
                                <td style="text-align: center;">{{ $no }}</td>
                                <td style="padding-left: 8px;">{{ $program['name'] ?? 'Program ' . ($pIdx + 1) }}</td>
                                <td style="text-align: right;">{{ number_format($program['amount'] ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $program['keterangan'] ?? '-' }}</td>
                            </tr>
                            @php $no++; @endphp

                            {{-- KEGIATAN ROWS --}}
                            @if(isset($program['kegiatan']) && is_array($program['kegiatan']))
                                @foreach($program['kegiatan'] as $kIdx => $kegiatan)
                                    <tr style="background: #fcfcfc;">
                                        <td style="text-align: center;">{{ ($pIdx + 1) }}.{{ ($kIdx + 1) }}</td>
                                        <td style="padding-left: 30px;">{{ $kegiatan['name'] ?? 'Kegiatan ' . ($kIdx + 1) }}</td>
                                        <td style="text-align: right;">{{ number_format($kegiatan['amount'] ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $kegiatan['keterangan'] ?? '-' }}</td>
                                    </tr>

                                    {{-- SUB-KEGIATAN ROWS --}}
                                    @if(isset($kegiatan['subKegiatan']) && is_array($kegiatan['subKegiatan']))
                                        @foreach($kegiatan['subKegiatan'] as $sIdx => $subKegiatan)
                                            <tr>
                                                <td style="text-align: center;">{{ ($pIdx + 1) }}.{{ ($kIdx + 1) }}.{{ ($sIdx + 1) }}</td>
                                                <td style="padding-left: 50px;">{{ $subKegiatan['name'] ?? 'Sub-Kegiatan ' . ($sIdx + 1) }}</td>
                                                <td style="text-align: right;">{{ number_format($subKegiatan['amount'] ?? 0, 0, ',', '.') }}</td>
                                                <td>{{ $subKegiatan['keterangan'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            @elseif(is_array($tabelC) && (isset($tabelC['program']) || isset($tabelC['anggaran'])))
                {{-- Fallback: flat structure (legacy format) --}}
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">NO</th>
                            <th>PROGRAM</th>
                            <th style="width: 150px;">ANGGARAN (Rp)</th>
                            <th style="width: 120px;">KETERANGAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($tabelC['program']) && is_array($tabelC['program']))
                            @foreach($tabelC['program'] as $idx => $prog)
                                <tr>
                                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                                    <td>{{ $prog ?? '-' }}</td>
                                    <td style="text-align: right;">{{ isset($tabelC['anggaran'][$idx]) ? number_format($tabelC['anggaran'][$idx], 0, ',', '.') : '0' }}</td>
                                    <td>{{ $tabelC['keterangan'][$idx] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            @else
                <div class="no-data">Tidak ada data Tabel C</div>
            @endif
        </div>

        <!-- SIGNATURES WITH DATE, PLACE, AND NIP -->
        <div style="margin-top: 50px; font-size: 12px; font-family: Arial, sans-serif; line-height: 1.5;">
            <!-- Signature blocks with job titles and NIP -->
            <table style="width: 100%; border: none; border-collapse: collapse; table-layout: fixed;">
                <!-- place/date will be placed proportionally inside the right (Pihak Pertama) cell; left cell keeps a small placeholder to preserve alignment -->
                <tr style="vertical-align: top;">
                    <!-- Pihak Kedua (Left) -->
                    <td style="border: none; text-align: center; width: 50%; font-size: 12px; padding: 0 10px; font-family: Arial, sans-serif;">
                        <!-- placeholder to balance place/date in right cell -->
                        <div style="height:20px; margin-bottom:4px;"></div>
                        <!-- Jabatan -->
                        <div style="font-weight: 600; margin-bottom: 60px; min-height: 16px; line-height: 1.4;">{{ $perjanjian->pihak2_jabatan ?? 'PIHAK KEDUA' }}</div>
                        <!-- Tanda Tangan -->
                        <div style="height: 80px; display: flex; align-items: flex-end; justify-content: center; margin-bottom: 8px;">
                            @if($perjanjian->pihak2_signature)
                                @php
                                    // prefer controller-provided base64 data for PDF
                                    if(!empty($pihak2_ttd_data)){
                                        $sig2src = $pihak2_ttd_data;
                                    } else {
                                        $sig2 = $perjanjian->pihak2_signature;
                                        $sig2src = $sig2;
                                        if(!empty($for_pdf)){
                                            if(Str::startsWith($sig2, 'data:')){
                                                $sig2src = $sig2;
                                            } elseif(file_exists(public_path($sig2))){
                                                $sig2src = public_path($sig2);
                                            } elseif(file_exists(public_path('storage/' . ltrim($sig2, '/')))){
                                                $sig2src = public_path('storage/' . ltrim($sig2, '/'));
                                            }
                                        }
                                    }
                                @endphp
                                <img src="{{ $sig2src }}" alt="TTD Pihak 2" style="max-height: 80px; max-width: 85%;">
                            @endif
                        </div>
                        <!-- Nama + Garis wrapper -->
                        <div style="display: inline-block; text-align: center; margin: 0 auto;">
                            <!-- Nama -->
                            <div class="sig-nama">{{ $perjanjian->pihak2_name ?? '-' }}</div>
                            <!-- Garis sesuai nama -->
                            <div class="sig-garis"></div>
                        </div>
                        <!-- NIP -->
                        <div style="font-size: 12px; margin-top: 3px;">NIP. {{ $perjanjian->pihak2_nip ?? '-' }}</div>
                    </td>
                    <!-- Pihak Pertama (Right) -->
                    <td style="border: none; text-align: center; width: 50%; font-size: 12px; padding: 0 10px; font-family: Arial, sans-serif; position: relative;">
                        <!-- place/date positioned inside right cell so it's proportional to Pihak Pertama -->
                        <div style="text-align: right; margin-bottom: 6px; font-size: 12px;">{{ $perjanjian->location ?? 'Pasuruan' }}, {{ isset($perjanjian->agreement_date) ? \Carbon\Carbon::parse($perjanjian->agreement_date)->format('d F Y') : \Carbon\Carbon::now()->format('d F Y') }}</div>
                        <!-- Jabatan -->
                        <div style="font-weight: 600; margin-bottom: 60px; min-height: 16px; line-height: 1.4;">{{ $perjanjian->pihak1_jabatan ?? 'PIHAK PERTAMA' }}</div>
                        <!-- Tanda Tangan -->
                        <div style="height: 80px; display: flex; align-items: flex-end; justify-content: center; margin-bottom: 8px;">
                            @if($perjanjian->pihak1_ttd)
                                @php
                                    if(!empty($pihak1_ttd_data)){
                                        $sig1src = $pihak1_ttd_data;
                                    } else {
                                        $sig1 = $perjanjian->pihak1_ttd;
                                        $sig1src = $sig1;
                                        if(!empty($for_pdf)){
                                            if(Str::startsWith($sig1, 'data:')){
                                                $sig1src = $sig1;
                                            } elseif(file_exists(public_path($sig1))){
                                                $sig1src = public_path($sig1);
                                            } elseif(file_exists(public_path('storage/' . ltrim($sig1, '/')))){
                                                $sig1src = public_path('storage/' . ltrim($sig1, '/'));
                                            }
                                        }
                                    }
                                @endphp
                                <img src="{{ $sig1src }}" alt="TTD Pihak 1" style="max-height: 80px; max-width: 85%;">
                            @endif
                        </div>
                        <!-- Nama + Garis wrapper -->
                        <div style="display: inline-block; text-align: center; margin: 0 auto;">
                            <!-- Nama -->
                            <div class="sig-nama">{{ $perjanjian->pihak1_name ?? '-' }}</div>
                            <!-- Garis sesuai nama -->
                            <div class="sig-garis"></div>
                        </div>
                        <!-- NIP -->
                        <div style="font-size: 12px; margin-top: 3px;">NIP. {{ $perjanjian->pihak1_nip ?? '-' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>