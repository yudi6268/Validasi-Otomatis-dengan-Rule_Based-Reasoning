<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Perjanjian Kinerja - {{ $perjanjian->nama ?? 'Dokumen' }}</title>
    <style>


        @page {
            size: folio {{ $orientation ?? 'portrait' }};
            margin: {{ ($orientation ?? 'portrait') === 'landscape' ? '8mm 10mm' : '15mm 15mm' }};
        }

        * {
            margin: 3cm 2.5cm 3cm 2.5cm;
            padding: 0;
            box-sizing: border-box;
       }
body {
    font-family: "Times New Roman", serif;
    font-size: 12pt;
    line-height: 1.5;
}
</style>
        
        .header-simple {
            text-align: center;
            margin-bottom: {{ ($orientation ?? 'portrait') === 'landscape' ? '8px' : '12px' }};
            border-bottom: 2px solid #000;
            padding-bottom: {{ ($orientation ?? 'portrait') === 'landscape' ? '6px' : '8px' }};
        }
        
        .header-simple h1 {
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '10pt' : '13pt' }};
            font-weight: bold;
            margin: 2px 0;
        }
        
        .header-simple h2 {
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '9pt' : '11pt' }};
            font-weight: bold;
            margin: 2px 0;
        }
        
        .doc-title {
            text-align: center;
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '9pt' : '11pt' }};
            font-weight: bold;
            margin: {{ ($orientation ?? 'portrait') === 'landscape' ? '6px 0' : '10px 0' }};
            text-decoration: underline;
        }
        
        .info-compact {
            margin: {{ ($orientation ?? 'portrait') === 'landscape' ? '6px 0' : '10px 0' }};
            padding: {{ ($orientation ?? 'portrait') === 'landscape' ? '4px 6px' : '6px 8px' }};
            background: #f5f5f5;
            border-left: 3px solid #333;
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '7pt' : '8pt' }};
        }
        
        .info-compact span {
            display: inline-block;
            margin-right: 15px;
        }
        
        .info-compact strong {
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: {{ ($orientation ?? 'portrait') === 'landscape' ? '4px 0' : '8px 0' }};
        }
        
        thead {
            display: table-header-group;
        }
        
        tfoot {
            display: table-footer-group;
        }
        
        table th {
            background-color: #d0d0d0;
            font-weight: bold;
            text-align: center;
            border: 1px solid #666;
            padding: {{ ($orientation ?? 'portrait') === 'landscape' ? '3px 2px' : '5px 4px' }};
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '6.5pt' : '8pt' }};
            word-wrap: break-word;
            vertical-align: middle;
        }
        
        table td {
            border: 1px solid #999;
            padding: {{ ($orientation ?? 'portrait') === 'landscape' ? '2px 3px' : '4px 5px' }};
            text-align: left;
            vertical-align: top;
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '6.5pt' : '8pt' }};
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        table tr:nth-child(even) {
            background-color: #fafafa;
        }
        
        tbody tr {
            page-break-inside: avoid;
        }
        
        /* Keep last 3 rows with signature on same page */
        tbody tr:nth-last-child(-n+3) {
            page-break-inside: avoid;
            page-break-after: avoid;
        }
        
        .text-center {
            text-align: center;
        }
        
        .signature-section {
            margin-top: {{ ($orientation ?? 'portrait') === 'landscape' ? '15px' : '25px' }};
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-top: {{ ($orientation ?? 'portrait') === 'landscape' ? '8px' : '12px' }};
        }
        
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '7pt' : '8pt' }};
        }
        
        .signature-col.left {
            text-align: left;
            padding-right: 10px;
        }
        
        .signature-col.right {
            text-align: right;
            padding-left: 10px;
        }
        
        .signature-name {
            margin-top: {{ ($orientation ?? 'portrait') === 'landscape' ? '40px' : '50px' }};
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 3px;
            display: inline-block;
            min-width: 150px;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header-simple">
        <h1>PEMERINTAH KABUPATEN PASURUAN - PERJANJIAN KINERJA TAHUN {{ $perjanjian->tahun ?? date('Y') }}</h1>
        <h2>UOBK RSUD BANGIL</h2>
    </div>
    
    @if(isset($perjanjian->deskripsi) && !empty($perjanjian->deskripsi))
    <div style="text-align: justify; margin: {{ ($orientation ?? 'portrait') === 'landscape' ? '6px 0' : '10px 0' }}; padding: {{ ($orientation ?? 'portrait') === 'landscape' ? '4px' : '6px' }}; background: #f9f9f9; font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '7pt' : '8pt' }};">
        {{ $perjanjian->deskripsi }}
    </div>
    @endif
    
    <div class="info-compact">
        @if(isset($perjanjian->nama))<span><strong>Nama:</strong> {{ $perjanjian->nama }}</span>@endif
        @if(isset($perjanjian->jabatan))<span><strong>Jabatan:</strong> {{ $perjanjian->jabatan }}</span>@endif
        @if(isset($perjanjian->periode))<span><strong>Periode:</strong> {{ $perjanjian->periode }}</span>@endif
    </div>

    {{-- Tabel Detail Perjanjian Kinerja --}}
    {{-- Tabel Detail Perjanjian Kinerja --}}
    @if($perjanjian->detail && $perjanjian->detail->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: {{ ($orientation ?? 'portrait') === 'landscape' ? '3%' : '4%' }};">NO</th>
                @php
                    $firstDetail = $perjanjian->detail->first();
                    $columns = array_diff(
                        array_keys($firstDetail->getAttributes()),
                        ['id', 'perjanjian_kinerja_id', 'created_at', 'updated_at']
                    );
                @endphp
                
                @foreach($columns as $column)
                    <th>{{ strtoupper(str_replace('_', ' ', $column)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($perjanjian->detail as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                @foreach($columns as $column)
                    <td>
                        @if(in_array($column, ['target', 'realisasi', 'anggaran']))
                            {{ number_format($detail->$column ?? 0, 0, ',', '.') }}
                        @elseif($column == 'capaian' || $column == 'persentase')
                            {{ number_format($detail->$column ?? 0, 2, ',', '.') }}%
                        @else
                            {{ $detail->$column ?? '-' }}
                        @endif
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    {{-- Tanda Tangan --}}
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-col left">
                <div style="margin-bottom: 3px;">Mengetahui,</div>
                <div style="font-weight: bold;">Pihak Pertama</div>
                <div class="signature-name">
                    {{ $perjanjian->pihak_pertama ?? 'Direktur RSUD Bangil' }}<br>
                    <span style="font-weight: normal; font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '6pt' : '7pt' }};">NIP. ________________</span>
                </div>
            </div>
            <div class="signature-col right">
                <div style="margin-bottom: 3px;">Bangil, {{ date('d F Y') }}</div>
                <div style="font-weight: bold;">Pihak Kedua</div>
                <div class="signature-name">
                    {{ $perjanjian->pihak_kedua ?? $perjanjian->jabatan ?? '_______________' }}<br>
                    <span style="font-weight: normal; font-size: {{ ($orientation ?? 'portrait') === 'landscape' ? '6pt' : '7pt' }};">NIP. ________________</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>