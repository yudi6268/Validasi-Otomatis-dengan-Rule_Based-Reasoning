<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Perjanjian Kinerja</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .page {
            width: 100%;
            padding: 40px 50px;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            height: 70px;
            margin-bottom: 10px;
        }
        .header h2 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .content-intro {
            text-align: justify;
            margin-bottom: 20px;
            font-size: 11px;
            line-height: 1.6;
        }
        .parties {
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
        }
        .party {
            flex: 1;
            font-size: 11px;
            line-height: 1.8;
        }
        .party-label {
            text-align: center;
            font-size: 10px;
            margin-top: 5px;
        }
        .section-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .input-field {
            margin-bottom: 15px;
            font-size: 11px;
        }
        .input-field label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        .input-value {
            padding: 5px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .no-col {
            width: 30px;
            text-align: center;
        }
        .action-col {
            width: 50px;
            text-align: center;
        }
        .page-break {
            page-break-after: always;
        }
        .signature-block {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .signature {
            text-align: center;
            width: 45%;
            font-size: 10px;
        }
        .signature-date {
            text-align: right;
            margin-top: 15px;
            font-size: 10px;
        }
        .total-row {
            background: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- PAGE 1: COVER -->
    <div class="page">
        <div class="header">
            <img src="{{ public_path('images/logo_pemda.png') }}" alt="Logo">
            <h2>PERJANJIAN KINERJA TAHUN 2025<br>WAKIL DIREKTUR PELAYANAN<br>UOBK RSUD BANGIL KABUPATEN PASURUAN</h2>
        </div>

        <div class="content-intro">
            Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil, kami yang bertanda tangan dibawah ini:
        </div>

        <div class="parties">
            <div class="party">
                <strong>PIHAK PERTAMA:</strong><br><br>
                Nama: {{ $data->pihak1_name ?? '-' }}<br>
                Jabatan: {{ $data->pihak1_jabatan ?? '-' }}<br>
                <div class="party-label">Selanjutnya disebut PIHAK PERTAMA</div>
            </div>
            <div class="party">
                <strong>PIHAK KEDUA:</strong><br><br>
                Nama: {{ $data->pihak2_name ?? '-' }}<br>
                Jabatan: {{ $data->pihak2_jabatan ?? '-' }}<br>
                <div class="party-label">Selanjutnya disebut PIHAK KEDUA</div>
            </div>
        </div>

        <div class="content-intro">
            Pihak pertama berjanji akan mewujudkan target kinerja yang seharusnya sesuai lampiran perjanjian ini, dalam rangka mencapai target kinerja jangka menengah seperti yang telah ditetapkan dalam dokumen perencanaan.
        </div>
        
        <div class="content-intro">
            Pihak kedua akan melakukan evaluasi terhadap capaian kinerja dari perjanjian ini dan mengambil tindakan yang diperlukan dalam rangka pemberian penghargaan dan sanksi.
        </div>

        <div class="input-field">
            <label>Jabatan:</label>
            <div class="input-value">{{ $data->jabatan ?? '-' }}</div>
        </div>

        <div class="signature-date">
            Pasuruan, {{ now()->format('d F Y') }}
        </div>

        <div class="signature-block">
            <div class="signature">
                PIHAK KEDUA<br><br><br><br>
                ...............................<br>
                {{ $data->pihak2_name ?? '-' }}
            </div>
            <div class="signature">
                PIHAK PERTAMA<br><br>
                @if($data->pihak1_ttd)
                    <img src="{{ $data->pihak1_ttd }}" alt="TTD" style="height: 60px;">
                @else
                    <br><br><br>
                @endif
                ...............................<br>
                {{ $data->pihak1_name ?? '-' }}
            </div>
        </div>
    </div>

    <!-- PAGE 2: TABEL A -->
    <div class="page page-break">
        <h3 class="section-title">INDIKATOR KINERJA INDIVIDU - TABEL A</h3>
        
        <table>
            <thead>
                <tr>
                    <th class="no-col">NO</th>
                    <th>SASARAN</th>
                    <th>INDIKATOR KINERJA</th>
                    <th>SATUAN</th>
                    <th>TARGET</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tabelA = json_decode($data->tabelA, true);
                    $maxRows = max(
                        count($tabelA['sasaran'] ?? []),
                        count($tabelA['indikator'] ?? []),
                        count($tabelA['satuan'] ?? []),
                        count($tabelA['target'] ?? [])
                    );
                @endphp
                
                @for($i = 0; $i < $maxRows; $i++)
                    <tr>
                        <td class="no-col">{{ $i + 1 }}</td>
                        <td>{{ $tabelA['sasaran'][$i] ?? '' }}</td>
                        <td>{{ $tabelA['indikator'][$i] ?? '' }}</td>
                        <td>{{ $tabelA['satuan'][$i] ?? '' }}</td>
                        <td>{{ $tabelA['target'][$i] ?? '' }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- PAGE 3: TABEL C (TRIWULAN) -->
    <div class="page page-break">
        <h3 class="section-title">INDIKATOR KINERJA INDIVIDU - TABEL C (TARGET TRIWULAN)</h3>
        
        <table>
            <thead>
                <tr>
                    <th>SASARAN</th>
                    <th>INDIKATOR KINERJA</th>
                    <th>TARGET</th>
                    <th colspan="4">Target Triwulan</th>
                </tr>
                <tr>
                    <th colspan="3"></th>
                    <th>I</th>
                    <th>II</th>
                    <th>III</th>
                    <th>IV</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tabelB = json_decode($data->tabelB, true);
                    $maxRows = max(
                        count($tabelB['sasaran'] ?? []),
                        count($tabelB['indikator'] ?? []),
                        count($tabelB['target'] ?? []),
                        count($tabelB['tw1'] ?? []),
                        count($tabelB['tw2'] ?? []),
                        count($tabelB['tw3'] ?? []),
                        count($tabelB['tw4'] ?? [])
                    );
                @endphp
                
                @for($i = 0; $i < $maxRows; $i++)
                    <tr>
                        <td>{{ $tabelB['sasaran'][$i] ?? '' }}</td>
                        <td>{{ $tabelB['indikator'][$i] ?? '' }}</td>
                        <td>{{ $tabelB['target'][$i] ?? '' }}</td>
                        <td>{{ $tabelB['tw1'][$i] ?? '' }}</td>
                        <td>{{ $tabelB['tw2'][$i] ?? '' }}</td>
                        <td>{{ $tabelB['tw3'][$i] ?? '' }}</td>
                        <td>{{ $tabelB['tw4'][$i] ?? '' }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- PAGE 4: TABEL D (ANGGARAN) - HIERARCHICAL or FLAT -->
    <div class="page page-break">
        <h3 class="section-title">ANGGARAN & PROGRAM</h3>
        
        <table>
            <thead>
                <tr>
                    <th class="no-col">NO</th>
                    <th>PROGRAM / KEGIATAN / SUB KEGIATAN</th>
                    <th style="width: 150px;">ANGGARAN (Rp)</th>
                    <th style="width: 100px;">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $tabelC = json_decode($data->tabelC, true);
                    $totalAnggaran = 0;
                    
                    // Check if hierarchical structure
                    $isHierarchical = isset($tabelC['programs']) && is_array($tabelC['programs']);
                @endphp
                
                @if($isHierarchical)
                    {{-- HIERARCHICAL RENDERING --}}
                    @php
                        $programs = $tabelC['programs'] ?? [];
                        $rowNum = 1;
                    @endphp
                    
                    @foreach($programs as $pIdx => $program)
                        @php
                            $programTotal = 0;
                            $kegiatan = $program['kegiatan'] ?? [];
                        @endphp
                        
                        <!-- PROGRAM ROW -->
                        <tr style="font-weight: bold; background: #f9f9f9;">
                            <td class="no-col">{{ $rowNum }}</td>
                            <td><strong>{{ $program['name'] ?? '' }}</strong></td>
                            <td style="text-align: right;">
                                @php
                                    // Calculate program total from kegiatan
                                    foreach ($kegiatan as $keg) {
                                        foreach ($keg['subKegiatan'] ?? [] as $subKeg) {
                                            $amt = (int)($subKeg['amount'] ?? 0);
                                            $programTotal += $amt;
                                        }
                                    }
                                    $totalAnggaran += $programTotal;
                                @endphp
                                <strong>Rp {{ number_format($programTotal, 0, ',', '.') }}</strong>
                            </td>
                            <td>{{ $program['keterangan'] ?? '' }}</td>
                        </tr>
                        
                        @php $rowNum++; @endphp
                        
                        <!-- KEGIATAN ROWS -->
                        @foreach($kegiatan as $kIdx => $keg)
                            @php
                                $kegiatanTotal = 0;
                                $subKegiatan = $keg['subKegiatan'] ?? [];
                            @endphp
                            
                            <tr style="background: #fafafa;">
                                <td class="no-col">{{ $rowNum - 1 }}.{{ $kIdx + 1 }}</td>
                                <td style="padding-left: 40px;">{{ $keg['name'] ?? '' }}</td>
                                <td style="text-align: right;">
                                    @php
                                        foreach ($subKegiatan as $subKeg) {
                                            $amt = (int)($subKeg['amount'] ?? 0);
                                            $kegiatanTotal += $amt;
                                        }
                                    @endphp
                                    <strong>Rp {{ number_format($kegiatanTotal, 0, ',', '.') }}</strong>
                                </td>
                                <td>{{ $keg['keterangan'] ?? '' }}</td>
                            </tr>
                            
                            @php $rowNum++; @endphp
                            
                            <!-- SUB-KEGIATAN ROWS -->
                            @foreach($subKegiatan as $sIdx => $subKeg)
                                <tr>
                                    <td class="no-col">{{ $rowNum - 2 }}.{{ $kIdx + 1 }}.{{ $sIdx + 1 }}</td>
                                    <td style="padding-left: 60px;">{{ $subKeg['name'] ?? '' }}</td>
                                    <td style="text-align: right;">
                                        @php
                                            $amt = (int)($subKeg['amount'] ?? 0);
                                        @endphp
                                        Rp {{ number_format($amt, 0, ',', '.') }}
                                    </td>
                                    <td>{{ $subKeg['keterangan'] ?? '' }}</td>
                                </tr>
                                @php $rowNum++; @endphp
                            @endforeach
                        @endforeach
                    @endforeach
                @else
                    {{-- FLAT RENDERING (FALLBACK FOR OLD DATA) --}}
                    @php
                        $programs = $tabelC['program'] ?? [];
                        $anggaran = $tabelC['anggaran'] ?? [];
                        $keterangan = $tabelC['keterangan'] ?? [];
                        
                        $maxRows = max(
                            count($programs),
                            count($anggaran),
                            count($keterangan)
                        );
                    @endphp
                    
                    @for($i = 0; $i < $maxRows; $i++)
                        @php
                            $angg = (int)($anggaran[$i] ?? 0);
                            $totalAnggaran += $angg;
                        @endphp
                        <tr>
                            <td class="no-col">{{ $i + 1 }}</td>
                            <td>{{ $programs[$i] ?? '' }}</td>
                            <td style="text-align: right;">Rp {{ number_format($angg, 0, ',', '.') }}</td>
                            <td>{{ $keterangan[$i] ?? '' }}</td>
                        </tr>
                    @endfor
                @endif
                
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;"><strong>TOTAL ANGGARAN:</strong></td>
                    <td style="text-align: right;"><strong>Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
