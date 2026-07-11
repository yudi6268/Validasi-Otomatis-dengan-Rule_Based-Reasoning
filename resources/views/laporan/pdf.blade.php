<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Kinerja - Triwulan {{ $triwulan ?? 1 }} - {{ $perjanjian->tahun ?? date('Y') }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 12pt; color: #000; background: #fff; line-height: 1.15; }
    .page { width: 100%; padding: 25mm 20mm 20mm 30mm; }
    .doc-title-block { text-align: center; margin-bottom: 24pt; }
    .doc-title-block h2 { font-size: 14pt; font-weight: bold; text-transform: uppercase; line-height: 1.4; margin-bottom: 4pt; }
    .bab-title { text-align: center; font-size: 14pt; font-weight: bold; text-transform: uppercase; margin-top: 16pt; margin-bottom: 2pt; }
    .bab-subtitle { text-align: center; font-size: 12pt; font-weight: bold; text-transform: uppercase; margin-bottom: 12pt; }
    .section-heading { font-size: 12pt; font-weight: bold; margin-top: 12pt; margin-bottom: 6pt; }
    .body-text { font-size: 12pt; text-align: justify; line-height: 1.15; text-indent: 2em; margin-bottom: 6pt; }
    .body-text-noindent { font-size: 12pt; text-align: justify; line-height: 1.15; margin-bottom: 4pt; }
    .list-item { font-size: 12pt; padding-left: 2.8em; text-indent: -2.8em; margin-bottom: 4px; line-height: 1.35; text-align: justify; word-break: break-word; }
    .list-row { display:table; width:100%; margin-bottom:3pt; font-size:12pt; line-height:1.15; }
    .list-row .list-num { display:table-cell; width:2.2em; vertical-align:top; white-space:nowrap; }
    .list-row .list-text { display:table-cell; text-align:justify; vertical-align:top; word-break:break-word; overflow-wrap:break-word; }
    .data-table { width: 100%; border-collapse: collapse; margin-bottom: 10pt; font-size: 9pt; table-layout: fixed; }
    .data-table th, .data-table td { border: 1px solid #000; padding: 2px 3px; vertical-align: top; text-align: justify; word-break: break-word; overflow-wrap: break-word; line-height: 1.15; }
    .data-table th { font-weight: bold; text-align: center; background: #fff; font-size: 8.5pt; }
    .data-table td.center { text-align: center; }
    .data-table td.right  { text-align: right; word-break: break-word; }
    .data-table td.bold   { font-weight: bold; }
    .data-table td.indent { padding-left: 10px; }
    .data-table td.indent2 { padding-left: 18px; }
    @page { size: folio portrait; }
    @page landscape-pg { size: folio landscape; margin: 15mm 15mm 15mm 20mm; }
    .page-landscape { page: landscape-pg; }
    .check-table { width: 70%; border-collapse: collapse; margin-bottom: 10pt; font-size: 11pt; }
    .check-table td { border: 1px solid #000; padding: 4px 8px; line-height: 1.15; }
    .check-table td.check-col { width: 70px; text-align: center; }
    .sig-section { margin-top: 20pt; }
    .sig-row { display: table; width: 100%; }
    .sig-col { display: table-cell; width: 50%; vertical-align: top; text-align: center; }
    .sig-img { max-height: 70px; max-width: 150px; display: block; margin: 0 auto 4pt; }
    .sig-name { font-size: 12pt; font-weight: bold; text-decoration: underline; display: inline-block; margin-top: 4pt; }
    .sig-nip  { font-size: 11pt; }
    .page-break { page-break-before: always; }
    @media print {
      body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: #fff !important; }
      .no-print { display: none; }
      .lk-page-wrapper { background: #fff !important; padding: 0 !important; }
      .page { box-shadow: none !important; border-radius: 0 !important; margin: 0 !important; max-width: none !important; }
    }
    @if(isset($for_pdf) && !$for_pdf)
    /* Browser preview: grey background with white page cards */
    body { background: #e6fcfc !important; }
    .lk-page-wrapper {
      width: 100%;
      min-height: 100vh;
      background: #e6fcfc;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 16px 100px;
      box-sizing: border-box;
    }
    .page {
      background: #fff;
      box-shadow: 0 2px 16px rgba(0,0,0,0.10);
      border-radius: 4px;
      max-width: 820px;
      width: 100%;
      margin: 0 auto 24px;
      box-sizing: border-box;
    }
    @endif
  </style>
</head>
<body>
@if(isset($for_pdf) && !$for_pdf)<div class="lk-page-wrapper">@endif
@php
  $tw      = (int) ($triwulan ?? 1);
  $twNames = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
  $twName  = $twNames[$tw] ?? (string)$tw;
  $tahun   = $perjanjian->tahun ?? date('Y');

  $jabatan1 = $laporan->pihak1_jabatan ?? $perjanjian->pihak1_jabatan ?? '';
  $nama1    = $laporan->pihak1_name    ?? $perjanjian->pihak1_name    ?? '';
  $nip1     = $perjanjian->pihak1_nip  ?? '';
  $nama2    = $laporan->pihak2_name    ?? $perjanjian->pihak2_name    ?? '';
  $jabatan2 = $laporan->pihak2_jabatan ?? $perjanjian->pihak2_jabatan ?? '';
  $nip2     = $perjanjian->pihak2_nip  ?? '';

  $tabelA   = is_array($perjanjian->tabelA) ? $perjanjian->tabelA : json_decode($perjanjian->tabelA ?? '[]', true);
  $tabelB   = is_array($perjanjian->tabelB) ? $perjanjian->tabelB : json_decode($perjanjian->tabelB ?? '[]', true);
  $twKey    = 'tw' . $tw;
  $sasar    = $tabelB['sasaran']   ?? [];
  $indik    = $tabelB['indikator'] ?? [];
  $twTgt    = $tabelB[$twKey]      ?? [];
  $tabelC   = is_array($perjanjian->tabelC) ? $perjanjian->tabelC : json_decode($perjanjian->tabelC ?? '[]', true);
  $programs = $tabelC['programs']  ?? [];

  $realisasiRaw  = $laporan->{'realisasi_tb' . $tw} ?? null;
  $realisasiData = is_array($realisasiRaw) ? $realisasiRaw : (json_decode($realisasiRaw ?? 'null', true) ?? []);
  $relRows  = $realisasiData['rows'] ?? [];
  $relByKey = [];
  foreach ($relRows as $r) { $relByKey[$r['row'] ?? ''] = $r; }

  $normalizeNumber = function($v) {
    if ($v === null || $v === '') return null;
    if (is_numeric($v)) return floatval($v);
    $s = (string)$v;
    // remove spaces, percentage signs, and dot thousand separators, convert comma decimal to dot
    $s = preg_replace('/[^\d\,\.\-]/', '', $s);
    if (strpos($s, '.') !== false && strpos($s, ',') !== false) {
        $s = str_replace('.', '', $s);
        $s = str_replace(',', '.', $s);
    } elseif (strpos($s, ',') !== false) {
        $s = str_replace(',', '.', $s);
    }
    // Remove multiple dots
    if (substr_count($s, '.') > 1) {
        $s = str_replace('.', '', $s);
    }
    return is_numeric($s) ? floatval($s) : null;
  };

  $calculatePct = function($target, $realisasi) use ($normalizeNumber) {
    if ($realisasi === null || $realisasi === '') {
      return null;
    }
    
    $targetVal = $normalizeNumber($target);
    $realisasiVal = $normalizeNumber($realisasi);
    if ($realisasiVal === null) {
      return null;
    }
    
    if ($targetVal === null || $targetVal <= 0) {
      return 0.0;
    }

    return round(($realisasiVal / $targetVal) * 100, 2);
  };

  $calculatePerformancePct = function($target, $realisasi, $indicatorType = 'positif', $capaianPct = null) use ($normalizeNumber, $calculatePct) {
    return \App\Services\RuleBasedReasoningService::calculatePerformancePercentage($target, $realisasi, $indicatorType);
  };

  $normalizeIndicatorType = function($indicatorType = 'positif') {
    return \App\Services\RuleBasedReasoningService::normalizeIndicatorType($indicatorType);
  };

  $fmt  = fn($v) => is_numeric($v) ? number_format((float)$v, 0, ',', '.') : ($v ?? '-');
  $fmt2 = fn($v) => is_numeric($v) ? number_format((float)$v, 2, ',', '.') : ($v ?? '-');
  $fmtTarget = function($v) use ($fmt) {
    if ($v === null || $v === '') {
      return '-';
    }
    if (is_numeric($v)) {
      return $fmt($v);
    }
    $s = trim((string)$v);
    if (preg_match('/^(?<int>[0-9]{1,3}(?:[\.\s][0-9]{3})*|[0-9]+)(?:[\.,](?<dec>[0-9]+))?$/', $s, $m)) {
      $integer = str_replace(['.', ' '], '', $m['int']);
      $formattedInt = number_format((int)$integer, 0, ',', '.');
      if (!empty($m['dec'])) {
        $dec = $m['dec'];
        if (strlen($dec) === 1) {
          $dec .= '0';
        } elseif (strlen($dec) > 2) {
          $dec = substr($dec, 0, 2);
        }
        return $formattedInt . ',' . $dec;
      }
      return $formattedInt;
    }
    return $fmt($s);
  };
  $fmtPct = function($v) {
    if ($v === null || $v === '') return '-';
    $v = str_replace('%', '', (string)$v);
    return is_numeric($v) ? number_format((float)$v, 2, ',', '.') : $v;
  };
  $fmtDisplayNo = function($no) {
    $s = trim((string) $no);
    if ($s === '') return '';
    $s = preg_replace('/^(pks|pk|p)(?=\d)/i', '', $s);
    $s = preg_replace('/\.(pks|pk|k|s)(?=\d)/i', '.', $s);
    return trim((string) ($s !== '' ? $s : $no));
  };
  $pihak1Sig  = $laporan->pihak1_signature
    ?: ($perjanjian->pihak1_ttd
    ?: ($perjanjian->pihak1_signature
    ?: ($perjanjian->user->tanda_tangan ?? null)));
  $pihak2Sig  = !empty($laporan->pihak2_signature) ? $laporan->pihak2_signature : null;
  $isApproved = !empty($laporan->pihak2_signature);
  // Convert signature path/URL/base64 to a data URI usable inside wkhtmltopdf
  $sigSrc = function($s) {
    if (!$s) return null;
    if (str_starts_with($s, 'data:image')) return $s;
    if (str_starts_with($s, 'http')) {
      // Fetch remote image via cURL and convert to base64 (required for wkhtmltopdf)
      try {
        if (function_exists('curl_init')) {
          $ch = curl_init($s);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_TIMEOUT, 8);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          $imgData = curl_exec($ch);
          $code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
          curl_close($ch);
          if ($imgData && $code === 200) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->buffer($imgData) ?: 'image/png';
            return 'data:' . $mime . ';base64,' . base64_encode($imgData);
          }
        }
      } catch (\Exception $e) {}
      return $s; // fallback: pass URL directly to img src
    }
    // Try as local storage file
    $paths = [
      storage_path('app/public/' . $s),
      public_path('storage/' . $s),
      public_path($s),
    ];
    foreach ($paths as $p) {
      if (file_exists($p)) {
        $mime = mime_content_type($p);
        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($p));
      }
    }
    // Fallback: treat as raw base64
    return 'data:image/png;base64,' . $s;
  };

  // Extract text/followup from current triwulan's realisasi data
  $babCapaianText = $realisasiData['text']    ?? $laporan->bab_capaian ?? $laporan->bab_kendala ?? '';
  $babRencanaText = $realisasiData['followup'] ?? $laporan->bab_rencana ?? '';

  $location  = $perjanjian->location ?? 'Pasuruan';
  $agreeDate = $laporan->updated_at ? $laporan->updated_at->locale('id')->isoFormat('D MMMM Y') : '';

  // Helper: resolve ket (APBD/BLUD) from stored source or fall back to name-based inference
  $resolveSubKet = function($sub) {
    $s = trim($sub['source'] ?? $sub['keterangan'] ?? $sub['ket'] ?? '');
    if ($s !== '' && $s !== '-') return $s;
    return stripos($sub['name'] ?? '', 'BLUD') !== false ? 'BLUD' : 'APBD';
  };
  $resolveKgKet = function($kg) use ($resolveSubKet) {
    $s = trim($kg['source'] ?? $kg['keterangan'] ?? $kg['ket'] ?? '');
    if ($s !== '' && $s !== '-') return $s;
    $subs = $kg['subKegiatan'] ?? [];
    if (!empty($subs)) {
      $vals = array_unique(array_map($resolveSubKet, $subs));
      return count($vals) === 1 ? $vals[0] : 'APBD/BLUD';
    }
    return stripos($kg['name'] ?? '', 'BLUD') !== false ? 'BLUD' : 'APBD';
  };
  $resolveProgKet = function($prog) use ($resolveKgKet) {
    $s = trim($prog['source'] ?? $prog['keterangan'] ?? $prog['ket'] ?? '');
    if ($s !== '' && $s !== '-') return $s;
    $kgs = $prog['kegiatan'] ?? [];
    if (!empty($kgs)) {
      $vals = array_unique(array_map($resolveKgKet, $kgs));
      return count($vals) === 1 ? $vals[0] : 'APBD/BLUD';
    }
    return stripos($prog['name'] ?? '', 'BLUD') !== false ? 'BLUD' : 'APBD';
  };

  $tanggapanOptions = [
    'kurang_baik'     => 'Laporan kurang baik',
    'sudah_baik'      => 'Laporan sudah baik',
    'diperbaiki'      => 'Laporan diperbaiki',
    'diteliti_ulang'  => 'Laporan diteliti ulang',
    'realisasi_ulang' => 'Realisasi diteliti ulang',
    'capaian_ulang'   => 'Capaian diteliti ulang',
  ];
  $tanggapanVal = $laporan->tanggapan_pimpinan ?? '';

  // Calculate actual performance percentages EARLY so we can use them for section E
  $capValues = [];
  foreach ($sasar as $i => $s) {
    $tgt = $twTgt[$i] ?? null;
    $rel = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
    $indicatorTypeSource = $tabelA['indicator_type'][$i] ?? ($tabelB['indicator_type'][$i] ?? 'positif');
    $indicatorType = $normalizeIndicatorType($indicatorTypeSource);
    
    $pct = $relByKey['kinerja-'.$i]['pct'] ?? null;
    if ($pct === null && $rel !== null && $rel !== '') {
      $pct = $calculatePct($tgt, $rel);
    }
    $performancePct = $calculatePerformancePct($tgt, $rel, $indicatorType, is_numeric($pct) ? floatval($pct) : null);

    if ($performancePct === null) {
      $storedPerformancePct = $relByKey['kinerja-'.$i]['performance_pct'] ?? null;
      if (is_numeric($storedPerformancePct)) {
        $performancePct = floatval($storedPerformancePct);
      }
    }
    
    if ($performancePct !== null) {
      $capValues[] = $performancePct;
    }
  }
  $avgCap = count($capValues) > 0 ? round(array_sum($capValues) / count($capValues), 2) : null;
  
  // Calculate anggaran percentage from sub-kegiatan only
  $totTgt = 0;
  $totRel = 0;
  foreach ($programs as $prog) {
    foreach ($prog['kegiatan'] ?? [] as $kg) {
      foreach ($kg['subKegiatan'] ?? [] as $sub) {
        $sNo = $sub['no'] ?? '';
        $sTgt = $normalizeNumber($sub[$twKey] ?? null) ?? 0;
        $sRel = $normalizeNumber($relByKey['anggaran-'.$sNo]['realisasi'] ?? null) ?? 0;
        $totTgt += $sTgt;
        $totRel += $sRel;
      }
    }
  }
  $avgAng = $totTgt > 0 ? round($totRel / $totTgt * 100, 2) : null;
  $composite = ($avgCap !== null && $avgAng !== null) ? round(($avgCap + $avgAng) / 2, 2) : $avgCap;
  
  // Determine performance category (use composite if available, otherwise use kinerja average)
  $performanceForCategory = $composite ?? $avgCap;
  $performanceCategoryText = $performanceForCategory === null ? 'Belum Terukur' 
    : ($performanceForCategory >= 91 ? 'Sangat Tinggi' 
      : ($performanceForCategory >= 76 ? 'Tinggi' 
        : ($performanceForCategory >= 66 ? 'Sedang' 
          : ($performanceForCategory >= 51 ? 'Rendah' : 'Sangat Rendah'))));

  $validationResult = $laporan->getValidationResult($tw);
  $validationScore = $validationResult['score'] ?? null;
  $validationIssues = $validationResult['issues'] ?? 0;
  $validationWarnings = $validationResult['warnings'] ?? 0;
  $validationSuggestions = $validationResult['suggestions'] ?? 0;
  $validationSummaryText = '';

  if ($validationResult) {
      $compositeScoreText = is_numeric($composite) ? $fmtPct($composite) : ($avgCap !== null ? $fmtPct($avgCap) : '-');
      $narrativeParts = [];
      $narrativeParts[] = "Laporan kinerja Triwulan {$twName} menunjukkan capaian dengan kategori <strong>{$performanceCategoryText}</strong> (skor {$compositeScoreText}%).";
      
      $findings = [];
      if ($validationIssues > 0) {
          $findings[] = "{$validationIssues} masalah";
      }
      if ($validationWarnings > 0) {
          $findings[] = "{$validationWarnings} peringatan";
      }
      if ($validationSuggestions > 0) {
          $findings[] = "{$validationSuggestions} saran perbaikan";
      }
      
      if (!empty($findings)) {
          $findingsText = implode(', ', $findings);
          $narrativeParts[] = "Ditemukan {$findingsText} dari hasil validasi.";
      } else {
          $narrativeParts[] = "Tidak ada temuan kritis pada laporan.";
      }

      $recommendation = '';
      if ($validationIssues > 0) {
          $recommendation = "Direkomendasikan untuk diperbaiki sesuai temuan masalah sebelum disetujui.";
      } elseif ($validationWarnings > 0 || $validationSuggestions > 0) {
          $recommendation = "Direkomendasikan untuk disetujui dengan mempertimbangkan saran perbaikan di periode berikutnya.";
      } else {
          $recommendation = "Direkomendasikan untuk disetujui tanpa perbaikan tambahan.";
      }

      if ($performanceForCategory !== null && $performanceForCategory >= 76) {
          $recommendation .= " Capaian kinerja sudah memenuhi target yang ditetapkan.";
      }
      
      $narrativeParts[] = $recommendation;
      $validationSummaryText = implode(' ', $narrativeParts);
  }
@endphp

{{-- ============================
     HALAMAN 1: JUDUL + BAB I
     ============================ --}}
<div class="page">
  <div class="doc-title-block">
    <h2>LAPORAN KINERJA</h2>
    <h2>{{ strtoupper($jabatan1) }}</h2>
    <h2>TRIBULAN {{ $twName }} TAHUN {{ $tahun }}</h2>
  </div>

  <div class="bab-title">BAB I</div>
  <div class="bab-subtitle">PENDAHULUAN</div>

  @php
    // Helper: decode JSON-array or split plain multiline string into array of items (with item level splitting)
    $toItems = function($val) {
      if (!$val) return [];
      $list = [];
      if (is_array($val)) {
        $list = $val;
      } else {
        $decoded = json_decode($val, true);
        if (is_array($decoded)) {
          $list = $decoded;
        } else {
          $list = [$val];
        }
      }
      $items = [];
      foreach ($list as $item) {
        $str = trim((string)$item);
        if ($str === '') continue;
        $parts = preg_split('/\r\n|\r|\n|;/u', $str);
        foreach ($parts as $part) {
          $partTrimmed = trim($part);
          if ($partTrimmed !== '') {
            $items[] = $partTrimmed;
          }
        }
      }
      return $items;
    };
    // Helper: parse fungsi which may be comma-separated, semicolon-separated, or have 'dan' between items
    $parseFungsi = function($val) use ($toItems) {
      if (!$val) return [];
      if (is_array($val)) return array_values(array_filter($val, 'strlen'));
      $decoded = json_decode($val, true);
      if (is_array($decoded)) return array_values(array_filter($decoded, 'strlen'));
      
      $str = trim((string)$val);
      $parts = array_filter(preg_split('/\r\n|\r|\n|;/u', $str), 'strlen');
      if (count($parts) > 1) {
        return array_values(array_map('trim', $parts));
      }
      
      $str = preg_replace('/\s+dan\s+/u', ', ', $str);
      $items = preg_split('/[,;]/u', $str);
      return array_values(array_filter(array_map('trim', $items), 'strlen'));
    };
    
    $normalizeItem = function($item) {
      if ($item === null) {
        return '';
      }
      $item = trim(preg_replace('/[\r\n]+/', ' ', (string)$item));
      // Strip common list indicators at the start of the item (e.g. 1., a., -, *, •, 1).)
      $item = preg_replace('/^(?:\d+[\.\)]+\s*|[a-zA-Z][\.\)]+\s*|[-*•]\s*)/u', '', $item);
      
      // Strip leading "dan", "dan/atau", "atau" (case-insensitive)
      $item = preg_replace('/^(?:dan\/atau|dan|atau)\b\s*/ui', '', $item);
      
      // Strip trailing "dan", "dan/atau", "atau" (case-insensitive)
      $item = preg_replace('/\s*(?:dan\/atau|dan|atau)\b\s*$/ui', '', $item);
      
      // Strip trailing punctuation
      $item = preg_replace('/[\.;,:]+$/u', '', $item);
      
      $item = preg_replace('/\s{2,}/u', ' ', $item);
      return trim($item);
    };

    // Prioritize lookup of the Jabatan model configured by the admin
    $jabatanCandidates = array_values(array_filter(array_unique([
      $jabatan1,
      $perjanjian->jabatan_pelaksana ?? '',
      $perjanjian->user->jabatan ?? '',
    ])));
    $jabatanModel = null;
    foreach ($jabatanCandidates as $jname) {
      if (!$jname) continue;
      $jabatanModel = \App\Models\Jabatan::where('nama_jabatan', $jname)->first()
        ?? \App\Models\Jabatan::where('nama_jabatan', 'LIKE', '%'.$jname.'%')->first();
      if ($jabatanModel) break;
    }

    $tugasRaw = null;
    $fungsiRaw = null;
    $membawahiRaw = null;

    if ($jabatanModel) {
      $tugasRaw = $jabatanModel->tugas;
      $fungsiRaw = $jabatanModel->fungsi;
      $membawahiRaw = $jabatanModel->membawahi;
    }

    // Fallbacks if not configured in the Jabatan model
    if (!$fungsiRaw) {
      $fungsiRaw = $perjanjian->fungsi_pelaksana ?: ($perjanjian->user->fungsi ?? '');
    }
    if (!$tugasRaw) {
      $tugasRaw = $perjanjian->tugas_pelaksana ?: ($perjanjian->user->tugas ?? '');
    }
    if (!$membawahiRaw) {
      $membawahiRaw = $perjanjian->user->membawahi ?? '';
    }
    
    $extractList = function($val) {
        if (!$val) return [];
        if (is_array($val)) return array_values(array_filter($val, 'strlen'));
        $decoded = json_decode($val, true);
        if (is_array($decoded)) return array_values(array_filter($decoded, 'strlen'));
        $str = trim((string)$val);
        $parts = preg_split("/\r\n|\n|\r/", $str);
        return array_values(array_filter(array_map('trim', $parts), 'strlen'));
    };

    $tugasValue = $tugasRaw;
    if (is_array($tugasValue)) {
        $tugasValue = implode("\n", $tugasValue);
    } elseif (is_string($tugasValue)) {
        $decodedTugas = json_decode($tugasValue, true);
        if (is_array($decodedTugas)) {
            $tugasValue = implode("\n", $decodedTugas);
        }
    }

    $fungsiItems = $extractList($fungsiRaw);
    $membawahiItems = $extractList($membawahiRaw);

    $hasContent = !empty(trim($tugasValue)) || !empty($fungsiItems) || !empty($membawahiItems);
  @endphp
  @if(!empty($laporan->bab_pelaksanaan))
    <div class="body-text">{!! nl2br(e($laporan->bab_pelaksanaan)) !!}</div>
  @elseif($hasContent)
    {{-- 1. Tugas --}}
    @if(!empty(trim($tugasValue)))
      <p class="body-text"><strong>{{ $jabatan1 }}</strong> mempunyai tugas {!! nl2br(e(trim($tugasValue))) !!}</p>
    @endif

    {{-- 2. Fungsi --}}
    @if(!empty($fungsiItems))
      <p class="body-text-noindent">Untuk melaksanakan tugasnya, <strong>{{ $jabatan1 }}</strong> mempunyai fungsi yaitu :</p>
      @if(count($fungsiItems) > 1)
        <ol style="margin: 0 0 10px 0; padding-left: 20px; list-style-type: lower-alpha; line-height: 1.5; text-align: justify;">
          @foreach($fungsiItems as $fi)
            @php
              $cleanFi = preg_replace('/^[a-zA-Z]\.\s*/', '', trim((string)$fi));
            @endphp
            <li style="margin-bottom: 4px;">{{ $cleanFi }}</li>
          @endforeach
        </ol>
      @else
        <p class="body-text-noindent">{!! nl2br(e($fungsiItems[0])) !!}</p>
      @endif
    @endif

    {{-- 3. Membawahi --}}
    @if(!empty($membawahiItems))
      <p class="body-text-noindent"><strong>{{ $jabatan1 }}</strong> membawahi :</p>
      @if(count($membawahiItems) > 1)
        <ol style="margin: 0 0 10px 0; padding-left: 20px; list-style-type: lower-alpha; line-height: 1.5; text-align: justify;">
          @foreach($membawahiItems as $mi)
            @php
              $cleanMi = preg_replace('/^[a-zA-Z]\.\s*/', '', trim((string)$mi));
            @endphp
            <li style="margin-bottom: 4px;">{{ $cleanMi }}</li>
          @endforeach
        </ol>
      @else
        <p class="body-text-noindent">{!! nl2br(e($membawahiItems[0])) !!}</p>
      @endif
    @endif
  @else
    <p class="body-text">Laporan kinerja ini disusun sebagai bentuk pertanggungjawaban pelaksanaan tugas dan fungsi {{ $jabatan1 }} pada Tribulan {{ $twName }} Tahun {{ $tahun }}.</p>
  @endif
</div>

{{-- ============================
     HALAMAN 2: BAB II — A. PERJANJIAN KINERJA
     ============================ --}}
<div class="page page-break">
  <div class="bab-title">BAB II</div>
  <div class="bab-subtitle">AKUNTABILITAS KINERJA JABATAN</div>

  <div class="section-heading">A. Perjanjian Kinerja</div>
  <p class="body-text">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil. Maka adanya perjanjian kinerja sebagai dasar/target yang dicapai dalam pelaksanaan tugas dan fungsinya oleh {{ $jabatan1 }} yaitu :</p>

  {{-- Tabel Sasaran Kinerja --}}
  @if(!empty($sasar))
    @php $satuan = $tabelA['satuan'] ?? []; @endphp
    <table class="data-table">
      <thead>
        <tr>
          <th style="width:5%;">NO</th><th style="width:28%;">Sasaran</th><th style="width:37%;">Indikator Kinerja</th><th style="width:18%;">Satuan</th><th style="width:12%;">Target</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sasar as $i => $s)
          @php
            $annualTgt = '-';
            if (!empty($tabelA) && is_array($tabelA) && isset($tabelA[$i]['target'])) {
              $annualTgt = $tabelA[$i]['target'];
            } else {
              // sum all triwulan targets as annual
              $sum = 0; $hasVal = false;
              for ($t = 1; $t <= 4; $t++) { $v = $tabelB['tw'.$t][$i] ?? null; if (is_numeric($v)) { $sum += (float)$v; $hasVal = true; } }
              $annualTgt = $hasVal ? $fmt($sum) : ($tabelB['tw1'][$i] ?? '-');
            }
          @endphp
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $s ?? '-' }}</td>
            <td>{{ $indik[$i] ?? '-' }}</td>
            <td class="center">{{ $satuan[$i] ?? '-' }}</td>
            <td class="center">{{ $annualTgt }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  {{-- Tabel Program/Anggaran --}}
  @if(!empty($programs))
    <table class="data-table">
      <thead>
        <tr><th style="width:5%;">NO</th><th style="width:58%;">PROGRAM / KEGIATAN</th><th style="width:25%;">ANGGARAN</th><th style="width:12%;">KET</th></tr>
      </thead>
      <tbody>
        @php $totalAnggaran = 0; @endphp
        @foreach($programs as $progIndex => $prog)
          @php
            $pNoRaw = trim((string) ($prog['no'] ?? ''));
            $pNoDisplay = $pNoRaw !== '' ? $fmtDisplayNo($pNoRaw) : (string) ($progIndex + 1);
            $ab = 0; for ($t=1;$t<=4;$t++) $ab += floatval($prog['tw'.$t] ?? 0);
            if ($ab == 0) foreach ($prog['kegiatan'] ?? [] as $kg) { for ($t=1;$t<=4;$t++) $ab += floatval($kg['tw'.$t] ?? 0); }
            $totalAnggaran += $ab;
            $pKet = $resolveProgKet($prog);
          @endphp
          <tr>
            <td class="center bold">{{ $pNoDisplay }}</td>
            <td class="bold">{{ $prog['name'] ?? '-' }}</td>
            <td class="right bold">{{ $ab > 0 ? number_format($ab,0,',','.') : '-' }}</td>
            <td class="center">{{ $pKet }}</td>
          </tr>
          @foreach($prog['kegiatan'] ?? [] as $kgIndex => $kg)
            @php
              $kNoRaw = trim((string) ($kg['no'] ?? ''));
              $kNoDisplay = $kNoRaw !== '' ? $fmtDisplayNo($kNoRaw) : ($pNoDisplay . '.' . ($kgIndex + 1));
              $kb = 0; for ($t=1;$t<=4;$t++) $kb += floatval($kg['tw'.$t] ?? 0);
              if ($kb==0) foreach ($kg['subKegiatan']??[] as $sub) { for($t=1;$t<=4;$t++) $kb+=floatval($sub['tw'.$t]??0); }
              $kKet = $resolveKgKet($kg);
            @endphp
            <tr><td class="center">{{ $kNoDisplay }}</td><td class="indent">{{ $kg['name']??'-' }}</td><td class="right">{{ $kb>0?number_format($kb,0,',','.'):'–' }}</td><td class="center">{{ $kKet }}</td></tr>
            @foreach($kg['subKegiatan'] ?? [] as $subIndex => $sub)
              @php
                $sNoRaw = trim((string) ($sub['no'] ?? ''));
                $sNoDisplay = $sNoRaw !== '' ? $fmtDisplayNo($sNoRaw) : ($kNoDisplay . '.' . ($subIndex + 1));
                $sb=0; for($t=1;$t<=4;$t++) $sb+=floatval($sub['tw'.$t]??0);
                $sKet = $resolveSubKet($sub);
              @endphp
              <tr><td class="center">{{ $sNoDisplay }}</td><td class="indent2">{{ $sub['name']??'-' }}</td><td class="right">{{ $sb>0?number_format($sb,0,',','.'):'–' }}</td><td class="center">{{ $sKet }}</td></tr>
            @endforeach
          @endforeach
        @endforeach
        <tr><td colspan="2" class="center bold">JUMLAH</td><td class="right bold">{{ number_format($totalAnggaran,0,',','.') }}</td><td></td></tr>
      </tbody>
    </table>
  @endif
</div>

{{-- ============================
     HALAMAN 3: B. CAPAIAN KINERJA
     ============================ --}}
<div class="page page-break page-landscape">
  <div class="section-heading">B. Capaian Kinerja</div>
  <p class="body-text">Capaian kinerja {{ $jabatan1 }} pada Tribulan {{ $twName }} Tahun {{ $tahun }} dari hasil pengukuran kinerja ini adalah sebagai berikut:</p>

  @if(!empty($sasar))
    <table class="data-table">
      <colgroup>
        <col style="width:4%;">
        <col style="width:25%;">
        <col style="width:34%;">
        <col style="width:12%;">
        <col style="width:12%;">
        <col style="width:13%;">
      </colgroup>
      <thead>
        <tr>
          <th rowspan="2">NO</th>
          <th rowspan="2">Sasaran</th>
          <th rowspan="2">Indikator Kinerja</th>
          <th colspan="3">Triwulan {{ $twName }}</th>
        </tr>
        <tr>
          <th>Target</th><th>Realisasi</th><th>%</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sasar as $i => $s)
            @php
            $tgtRaw = $twTgt[$i] ?? null;
            $tgtNum = $normalizeNumber($tgtRaw);
            $relRaw = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
            $relNum = $normalizeNumber($relRaw);
            $indicatorTypeSource = $tabelA['indicator_type'][$i] ?? ($tabelB['indicator_type'][$i] ?? 'positif');
            $indicatorType = $normalizeIndicatorType($indicatorTypeSource);

            // Gunakan persentase performa (konsisten dengan form), bukan pct mentah.
            $storedPct = $relByKey['kinerja-'.$i]['pct'] ?? null;

            if ($relRaw !== null && $relRaw !== '') {
              $cap = $calculatePerformancePct(
                $tgtRaw,
                $relRaw,
                $indicatorType,
                is_numeric($storedPct) ? floatval($storedPct) : null
              );

              if ($cap === null) {
                $storedPerformancePct = $relByKey['kinerja-'.$i]['performance_pct'] ?? null;
                if (is_numeric($storedPerformancePct)) {
                  $cap = floatval($storedPerformancePct);
                }
              }
            } else {
              $cap = '-';
            }
          @endphp
          <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $s }}</td>
            <td>{{ $indik[$i] ?? '-' }}</td>
            <td class="center">{{ $tgtRaw !== null ? $fmtTarget($tgtRaw) : '-' }}</td>
            <td class="center">{{ $relNum !== null ? $fmt2($relNum) : ($relRaw !== null ? $fmt2($relRaw) : '-') }}</td>
            <td class="center">{{ is_numeric($cap) ? $fmtPct($cap) : ($cap ?? '-') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  @if(!empty($programs))
    <table class="data-table">
      <colgroup>
        <col style="width:4%;">
        <col style="width:34%;">
        <col style="width:23%;">
        <col style="width:23%;">
        <col style="width:8%;">
        <col style="width:8%;">
      </colgroup>
      <thead>
        <tr>
          <th rowspan="2">NO</th>
          <th rowspan="2">PROGRAM / KEGIATAN</th>
          <th colspan="3">Triwulan {{ $twName }}</th>
          <th rowspan="2">Ket</th>
        </tr>
        <tr><th>Target</th><th>Realisasi</th><th style="white-space: nowrap;">%</th></tr>
      </thead>
      <tbody>
        @php 
          // Calculate totals from sub-kegiatan only (not program/kegiatan)
          $totTgt = 0; 
          $totRel = 0;
          // First pass: collect sub-kegiatan totals
          foreach($programs as $progIndex => $prog) {
            $progNoRaw = trim((string) ($prog['no'] ?? ''));
            $progNo = $progNoRaw !== '' ? $progNoRaw : ('p' . ($progIndex + 1));
            foreach($prog['kegiatan']??[] as $kgIndex => $kg) {
              $kgNoRaw = trim((string) ($kg['no'] ?? ''));
              $kgNo = $kgNoRaw !== '' ? $kgNoRaw : ($progNo . '.k' . ($kgIndex + 1));
              foreach($kg['subKegiatan']??[] as $subIndex => $sub) {
                $sNoRaw = trim((string) ($sub['no'] ?? ''));
                $sNo = $sNoRaw !== '' ? $sNoRaw : ($kgNo . '.s' . ($subIndex + 1));
                $sTgt = $normalizeNumber($sub[$twKey] ?? null) ?? 0;
                $sRel = $normalizeNumber($relByKey['anggaran-'.$sNo]['realisasi'] ?? null) ?? 0;
                $totTgt += $sTgt;
                $totRel += $sRel;
              }
            }
          }
        @endphp
        @foreach($programs as $progIndex => $prog)
          @php
            $pNoRaw = trim((string) ($prog['no'] ?? ''));
            $pNo = $pNoRaw !== '' ? $pNoRaw : ('p' . ($progIndex + 1));
            $pNoDisplay = $pNoRaw !== '' ? $fmtDisplayNo($pNoRaw) : (string) ($progIndex + 1);
            $pTgt = $normalizeNumber($prog[$twKey] ?? null) ?? 0;
            $pRel = $normalizeNumber($relByKey['anggaran-'.$pNo]['realisasi'] ?? null) ?? 0;
            // Use stored pct if available, otherwise recalculate for backward compatibility
            $pPct = $relByKey['anggaran-'.$pNo]['pct'] ?? ($pTgt > 0 ? round($pRel / $pTgt * 100, 2) : '-');
            $pKet2 = $resolveProgKet($prog);
          @endphp
          <tr>
            <td class="center bold">{{ $pNoDisplay }}</td><td class="bold">{{ $prog['name']??'-' }}</td>
            <td class="right" style="font-size: 8pt;">{{ $prog[$twKey] ?? null ? $fmt2($pTgt) : '-' }}</td>
            <td class="right" style="font-size: 8pt;">{{ $fmt2($pRel) }}</td>
            <td class="center" style="white-space: nowrap;">{{ $pPct !== '-' ? $fmtPct($pPct) : '-' }}</td><td class="center">{{ $pKet2 }}</td>
          </tr>
          @foreach($prog['kegiatan']??[] as $kgIndex => $kg)
            @php
              $kNoRaw = trim((string) ($kg['no'] ?? ''));
              $kNo = $kNoRaw !== '' ? $kNoRaw : ($pNo . '.k' . ($kgIndex + 1));
              $kNoDisplay = $kNoRaw !== '' ? $fmtDisplayNo($kNoRaw) : ($pNoDisplay . '.' . ($kgIndex + 1));
              $kTgt = $normalizeNumber($kg[$twKey] ?? null) ?? 0;
              $kRel = $normalizeNumber($relByKey['anggaran-'.$kNo]['realisasi'] ?? null) ?? 0;
              // Use stored pct if available, otherwise recalculate for backward compatibility
              $kPct = $relByKey['anggaran-'.$kNo]['pct'] ?? ($kTgt > 0 ? round($kRel / $kTgt * 100, 2) : '-');
              $kKet2 = $resolveKgKet($kg);
            @endphp
            <tr><td class="center">{{ $kNoDisplay }}</td><td class="indent">{{ $kg['name']??'-' }}</td><td class="right" style="font-size: 8pt;">{{ ($kg[$twKey] ?? null) !== null ? $fmt2($kTgt) : '-' }}</td><td class="right" style="font-size: 8pt;">{{ $fmt2($kRel) }}</td><td class="center" style="white-space: nowrap;">{{ $kPct!=='-'?$fmtPct($kPct):'-' }}</td><td class="center">{{ $kKet2 }}</td></tr>
            @foreach($kg['subKegiatan']??[] as $subIndex => $sub)
              @php
                $sNoRaw = trim((string) ($sub['no'] ?? ''));
                $sNo = $sNoRaw !== '' ? $sNoRaw : ($kNo . '.s' . ($subIndex + 1));
                $sNoDisplay = $sNoRaw !== '' ? $fmtDisplayNo($sNoRaw) : ($kNoDisplay . '.' . ($subIndex + 1));
                $sTgt = $normalizeNumber($sub[$twKey] ?? null) ?? 0;
                $sRel = $normalizeNumber($relByKey['anggaran-'.$sNo]['realisasi'] ?? null) ?? 0;
                // Use stored pct if available, otherwise recalculate for backward compatibility
                $sPct = $relByKey['anggaran-'.$sNo]['pct'] ?? ($sTgt > 0 ? round($sRel / $sTgt * 100, 2) : '-');
                $sKet2 = $resolveSubKet($sub);
              @endphp
              <tr><td class="center">{{ $sNoDisplay }}</td><td class="indent2">{{ $sub['name']??'-' }}</td><td class="right" style="font-size: 8pt;">{{ ($sub[$twKey] ?? null) !== null ? $fmt2($sTgt) : '-' }}</td><td class="right" style="font-size: 8pt;">{{ $fmt2($sRel) }}</td><td class="center" style="white-space: nowrap;">{{ $sPct!=='-'?$fmtPct($sPct):'-' }}</td><td class="center">{{ $sKet2 }}</td></tr>
            @endforeach
          @endforeach
        @endforeach
        @if($totTgt>0||$totRel>0)
          <tr><td colspan="2" class="center bold">JUMLAH</td><td class="right bold" style="font-size: 8pt;">{{ $fmt2($totTgt) }}</td><td class="right bold" style="font-size: 8pt;">{{ $fmt2($totRel) }}</td><td class="center bold" style="white-space: nowrap;">{{ $totTgt>0 ? $fmtPct(round($totRel/$totTgt*100,2)) : '-' }}</td><td></td></tr>
        @endif
      </tbody>
    </table>
  @endif
</div>

{{-- ============================
     HALAMAN 4: C, D, E
     ============================ --}}
<div class="page page-break">
  <div class="section-heading">C. Evaluasi dan Analisis Kinerja</div>
  @if(!empty($babCapaianText))
    @php $capaianParas = array_values(array_filter(preg_split('/\n\s*\n/', $babCapaianText))); @endphp
    @if(count($capaianParas) > 1)
      @foreach($capaianParas as $para)
        <p class="body-text">{!! nl2br(e(trim($para))) !!}</p>
      @endforeach
    @else
      <p class="body-text">{!! nl2br(e(trim($babCapaianText))) !!}</p>
    @endif
  @else
    <p class="body-text">-</p>
  @endif

  <div class="section-heading">D. Rencana Tindak Lanjut</div>
  @if(!empty($babRencanaText))
    @php
      $cleanedBabRencanaText = trim($babRencanaText);
      $cleanedBabRencanaText = preg_replace('/^Adapun\s+Rencana\s+Tindak\s+Lanjut[^:]*:\s*/ui', '', $cleanedBabRencanaText);
      $rLines = array_values(array_filter(preg_split('/\r\n|\r|\n/', trim($cleanedBabRencanaText))));
    @endphp
    @foreach($rLines as $line)
      @if(preg_match('/^(\d+[\.\)])\s+(.*)$/', trim($line), $matches))
        <div class="list-row"><span class="list-num">{{ $matches[1] }}</span><span class="list-text">{!! nl2br(e($matches[2])) !!}</span></div>
      @else
        <p class="body-text">{!! nl2br(e(trim($line))) !!}</p>
      @endif
    @endforeach
  @else
    <p class="body-text">-</p>
  @endif

  <div class="section-heading">E. Tanggapan Atasan Langsung</div>
  @if(!empty($validationSummaryText))
    <p class="body-text" style="font-style:italic; margin-bottom:10px;">{!! $validationSummaryText !!}</p>
  @endif
  <table class="check-table">
    <thead>
      <tr>
        <td class="check-col" style="font-weight:bold;">Tanda (V)</td>
        <td style="font-weight:bold;font-style:italic;text-align:center;">uraian</td>
      </tr>
    </thead>
    <tbody>
      @foreach($tanggapanOptions as $key => $label)
        <tr>
          <td class="check-col">{{ $tanggapanVal === $key ? '√' : '' }}</td>
          <td style="font-style:italic;">{{ $label }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{-- ============================
     HALAMAN 5: BAB III + TTD
     ============================ --}}
<div class="page page-break">
  <div class="bab-title">BAB III</div>
  <div class="bab-subtitle">PENUTUP</div>

  @php
    // Hitung rata-rata capaian kinerja untuk penutup.
    $capValues = [];
    foreach ($sasar as $i => $s) {
      $pct = $relByKey['kinerja-'.$i]['pct'] ?? null;
      $tgt = $twTgt[$i] ?? null;
      $rel = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
      $indicatorTypeSource = $tabelA['indicator_type'][$i] ?? ($tabelB['indicator_type'][$i] ?? 'positif');
      $indicatorType = $normalizeIndicatorType($indicatorTypeSource);
      if ($rel !== null && $rel !== '') {
        if ($pct === null) {
          $pct = $calculatePct($tgt, $rel);
        }
        $performancePct = $calculatePerformancePct($tgt, $rel, $indicatorType, is_numeric($pct) ? floatval($pct) : null);
        if ($performancePct !== null) {
          $capValues[] = $performancePct;
        } else {
          $storedPerformancePct = $relByKey['kinerja-'.$i]['performance_pct'] ?? null;
          if (is_numeric($storedPerformancePct)) {
            $capValues[] = floatval($storedPerformancePct);
          }
        }
      }
    }
    $avgCap = count($capValues) > 0 ? round(array_sum($capValues) / count($capValues), 2) : null;
    $capKategori = $avgCap === null ? '' : ($avgCap >= 91 ? 'Sangat Tinggi' : ($avgCap >= 76 ? 'Tinggi' : ($avgCap >= 66 ? 'Sedang' : ($avgCap >= 51 ? 'Rendah' : 'Sangat Rendah'))));
  @endphp
  @php
    $kesimpulanText = trim($laporan->kesimpulan ?? '');
    // Strip auto-generated "BAB III PENUTUP" heading prefix if present (handles \n and \r\n)
    $kesimpulanText = preg_replace('/^BAB\s+III\s+PENUTUP\s*[\r\n]+/iu', '', $kesimpulanText);
    $kesimpulanText = trim($kesimpulanText);
  @endphp
  @if(!empty($kesimpulanText))
    @php $penutupParas = array_values(array_filter(preg_split('/\n\s*\n|\r\n\s*\r\n/', $kesimpulanText))); @endphp
    @if(count($penutupParas) > 1)
      @foreach($penutupParas as $para)
        <p class="body-text">{!! nl2br(e(trim($para))) !!}</p>
      @endforeach
    @else
      <p class="body-text">{!! nl2br(e($kesimpulanText)) !!}</p>
    @endif
  @else
    @php
      $persentase = $composite ?? $avgCap;
      $compKat = $performanceForCategory !== null ? $performanceCategoryText : $capKategori;
      $twTextArr = [1=>'satu',2=>'dua',3=>'tiga',4=>'empat'];
      $twTxt     = $twTextArr[$tw] ?? (string)$tw;
      $arahFb    = $persentase === null ? '' : ($persentase >= 91
        ? 'upaya tindak lanjut diarahkan untuk menjaga konsistensi mutu, memperluas praktik baik, dan memastikan keberlanjutan kinerja unggul.'
        : ($persentase >= 76
            ? 'upaya tindak lanjut diarahkan untuk menutup celah minor pada indikator tertentu serta memperkuat pengendalian pelaksanaan.'
            : ($persentase >= 51
              ? 'upaya tindak lanjut diarahkan pada akselerasi kinerja melalui penajaman prioritas, penguatan koordinasi, dan monitoring lebih intensif.'
              : 'upaya tindak lanjut diarahkan pada langkah korektif terstruktur, penataan strategi pelaksanaan, serta peningkatan disiplin monitoring dan evaluasi.')));
      $persentaseFormatted = $fmtPct($persentase);
      $labelCapaian = ($avgAng !== null) ? 'capaian komposit indikator kinerja dan anggaran' : 'capaian indikator kinerja';
    @endphp
    <p class="body-text">Berdasarkan hasil pengukuran kinerja Triwulan {{ $twName }} ({{ $twTxt }}) Tahun {{ $tahun }} pada jabatan {{ $jabatan1 }}, {{ $labelCapaian }} mencapai <strong>{{ $persentaseFormatted }}%</strong> dengan predikat <strong>{{ $compKat }}</strong>. Capaian ini menjadi dasar evaluasi untuk memastikan kesinambungan peningkatan kualitas pelaksanaan program pada periode berikutnya.</p>
  @endif

  <div class="sig-section">
    <div class="sig-row">
      {{-- Pihak 2 (Pimpinan/Wadir): nama & jabatan selalu tampil, tanda tangan hanya jika disetujui --}}
      <div class="sig-col">
        <p style="font-size:12pt;">Mengetahui</p>
        <p style="font-size:12pt;">{{ $jabatan2 }}</p>
        <br>
        @if($isApproved && $sigSrc($pihak2Sig))
          <img class="sig-img" src="{{ $sigSrc($pihak2Sig) }}" alt="TTD Atasan">
        @else
          <div style="height:70px;"></div>
        @endif
        <br>
        <span class="sig-name">{{ $nama2 }}</span><br>
        @if($nip2)<span class="sig-nip">NIP. {{ $nip2 }}</span>@endif
      </div>
      {{-- Pihak 1 (Pegawai): selalu tampil, pakai tanda_tangan dari profil user --}}
      <div class="sig-col">
        <p style="font-size:12pt;">{{ $location }}, {{ $agreeDate }}</p>
        <p style="font-size:12pt;">{{ $jabatan1 }}</p>
        <br>
        @if($sigSrc($pihak1Sig))
          <img class="sig-img" src="{{ $sigSrc($pihak1Sig) }}" alt="TTD Pegawai">
        @else
          <div style="height:70px;"></div>
        @endif
        <br>
        <span class="sig-name">{{ $nama1 }}</span><br>
        @if($nip1)<span class="sig-nip">NIP. {{ $nip1 }}</span>@endif
      </div>
    </div>
  </div>
</div>

@if(isset($for_pdf) && !$for_pdf)
@php
  $isValidated     = $laporan->hasValidationForTriwulan($tw);
  $alreadyApproved = !empty($laporan->pihak2_signature);
  $isDirekturView  = isset($isDirektur) && $isDirektur;

  // Auto-suggest tanggapan dari rata-rata persentase capaian
  $autoTanggapan = '';
  if ($isDirekturView && $isValidated && !$alreadyApproved) {
    $rows = $realisasiData['rows'] ?? [];
    $pcts = array_filter(array_map(fn($r) => is_numeric($r['pct'] ?? null) ? (float)$r['pct'] : null, $rows), fn($v) => $v !== null);
    $avg  = count($pcts) > 0 ? array_sum($pcts) / count($pcts) : null;
    if ($avg !== null) {
      $autoTanggapan = $avg >= 76 ? 'sudah_baik' : ($avg >= 66 ? 'diperbaiki' : 'kurang_baik');
    }
  }
  $selectedTanggapan = ($tanggapanVal ?? '') ?: $autoTanggapan;
@endphp

{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@if($isDirekturView)
<style>
  .lk-aksi-container {
    position: fixed;
    top: 88px;
    right: 20px;
    z-index: 1200;
    display: flex;
    gap: 12px;
    align-items: center;
  }
  .lk-aksi-btn {
    padding: 14px 28px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: transform 0.18s cubic-bezier(.4,2,.6,1), box-shadow 0.18s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
  }
  .lk-aksi-btn:hover  { transform: scale(1.06); box-shadow: 0 6px 24px rgba(0,0,0,0.13); }
  .lk-aksi-btn:active { transform: scale(0.96); }
  .lk-aksi-btn.terima { background: #F5E94E; color: #222; }
  .lk-aksi-btn.tolak  { background: #FF2E2E; color: #fff; }
  .lk-modal-overlay {
    display: none;
    position: fixed; top:0; left:0; width:100vw; height:100vh;
    background: rgba(0,0,0,0.3);
    z-index: 9999;
    align-items: center;
    justify-content: center;
  }
  .lk-modal-box {
    background: #fff;
    border-radius: 12px;
    max-width: 480px;
    width: 95vw;
    box-shadow: 0 4px 32px rgba(0,0,0,0.18);
    overflow: hidden;
  }
  .check-tbl { width:80%; border-collapse:collapse; margin-bottom:16px; font-size:13px; }
  .check-tbl th { padding:8px 12px; border:1px solid #d4b84f; background:#f2d46a; text-align:center; }
  .check-tbl th:last-child { text-align:left; }
  .check-tbl td { padding:8px; border:1px solid #e0e0e0; }
  .check-tbl tr.tg-row:hover td { background:#f0faf7; cursor:pointer; }
  .check-tbl tr.tg-selected td { background:#e8f5e9; font-weight:600; }
</style>

@if($alreadyApproved)
  {{-- Already approved banner --}}
  <div class="no-print" style="position:fixed;top:88px;right:20px;z-index:1200;background:#d4edda;border:1px solid #c3e6cb;border-radius:8px;padding:12px 20px;display:flex;align-items:center;gap:10px;box-shadow:0 2px 12px rgba(0,0,0,0.1);">
    <i class="fas fa-check-circle" style="color:#155724;font-size:18px;"></i>
    <span style="color:#155724;font-weight:700;font-size:14px;">Laporan sudah disetujui</span>
  </div>

@else
  {{-- Floating action buttons --}}
  <div class="lk-aksi-container">
    <button class="lk-aksi-btn terima" type="button" onclick="handleSetujui()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
        <path d="M13.485 1.929a1 1 0 0 1 0 1.414l-7.071 7.071a1 1 0 0 1-1.414 0L2.515 8.071a1 1 0 1 1 1.414-1.414l1.071 1.071 6.364-6.364a1 1 0 0 1 1.414 0z"/>
      </svg>
      Terima
    </button>
    <button class="lk-aksi-btn tolak" type="button" onclick="handleTolak()">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
      </svg>
      Tolak
    </button>
  </div>

  {{-- Modal: Belum Tervalidasi --}}
  <div id="modal-belum-validasi" class="lk-modal-overlay no-print">
    <div class="lk-modal-box" style="padding:32px 28px;text-align:center;">
      <div style="font-size:48px;margin-bottom:16px;">⚠️</div>
      <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;color:#856404;">Laporan Belum Tervalidasi</h2>
      <p style="font-size:14px;color:#555;margin-bottom:24px;line-height:1.6;">
        Laporan kinerja ini belum divalidasi oleh Wakil Direktur.<br>
        Aksi hanya dapat dilakukan setelah laporan tervalidasi.
      </p>
      <button type="button" onclick="document.getElementById('modal-belum-validasi').style.display='none'"
        style="background:#6c757d;color:#fff;border:none;padding:10px 32px;border-radius:7px;font-weight:700;font-size:15px;cursor:pointer;">
        Mengerti
      </button>
    </div>
  </div>

  {{-- Modal: Konfirmasi Setujui --}}
  <div id="modal-setujui-laporan" class="lk-modal-overlay no-print">
    <div class="lk-modal-box">
      <div style="background:#009970;padding:18px 24px;">
        <h2 style="color:#fff;font-size:17px;font-weight:700;margin:0;">
          <i class="fas fa-clipboard-check" style="margin-right:8px;"></i>E. Tanggapan Atasan Langsung
        </h2>
      </div>
      <div style="padding:24px;">
        <p style="font-size:13px;color:#555;margin-bottom:16px;">
          Pilih tanggapan berdasarkan hasil capaian kinerja. Sistem otomatis memilih berdasarkan hasil validasi.
        </p>
        @if(!empty($validationSummaryText))
          <div style="background:#e9f7ef;border-left:4px solid #009970;padding:14px 16px;margin-bottom:18px;border-radius:10px;color:#0f5132;font-size:13px;line-height:1.5;">
            {!! $validationSummaryText !!}
          </div>
        @endif
        <table class="check-tbl" id="tanggapanTable">
          <thead>
            <tr>
              <th style="width:50px;">Tanda (√)</th>
              <th>Uraian</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tanggapanOptions as $key => $label)
            <tr class="tg-row {{ $selectedTanggapan === $key ? 'tg-selected' : '' }}"
                onclick="selectTanggapan('{{ $key }}', this)">
              <td style="text-align:center;">
                <input type="radio" name="tanggapan_pimpinan" id="tg_{{ $key }}" value="{{ $key }}"
                  {{ $selectedTanggapan === $key ? 'checked' : '' }}
                  onclick="event.stopPropagation();">
              </td>
              <td>{{ $label }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <button type="button" onclick="document.getElementById('modal-setujui-laporan').style.display='none'"
            style="background:#6c757d;color:#fff;border:none;padding:10px 24px;border-radius:7px;font-weight:700;font-size:14px;cursor:pointer;">
            Batal
          </button>
          <button type="button" id="btn-konfirmasi-setujui" onclick="submitSetujui()"
            style="background:#009970;color:#fff;border:none;padding:10px 24px;border-radius:7px;font-weight:700;font-size:14px;cursor:pointer;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-check"></i> Setujui Laporan
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal: Konfirmasi Tolak --}}
  <div id="modal-tolak-laporan" class="lk-modal-overlay no-print">
    <div class="lk-modal-box" style="padding:32px 28px;text-align:center;">
      <div style="font-size:48px;margin-bottom:16px;">🔁</div>
      <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;color:#DC3545;">Kembalikan Laporan ke Pegawai?</h2>
      <p style="font-size:14px;color:#555;margin-bottom:24px;line-height:1.6;">
        Laporan kinerja akan dikembalikan ke pegawai.<br>
        <strong>Data validasi akan dihapus</strong> dan laporan kembali ke kondisi awal sebelum tervalidasi.
      </p>
      <div style="display:flex;justify-content:center;gap:12px;">
        <button type="button" onclick="document.getElementById('modal-tolak-laporan').style.display='none'"
          style="background:#6c757d;color:#fff;border:none;padding:10px 28px;border-radius:7px;font-weight:700;font-size:15px;cursor:pointer;">
          Batal
        </button>
        <button type="button" id="btn-konfirmasi-tolak" onclick="submitTolak()"
          style="background:#DC3545;color:#fff;border:none;padding:10px 28px;border-radius:7px;font-weight:700;font-size:15px;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="fas fa-undo"></i> Ya, Kembalikan
        </button>
      </div>
    </div>
  </div>

@endif {{-- end !$alreadyApproved --}}

<script>
  var isValidated = {{ $isValidated ? 'true' : 'false' }};

  function handleSetujui() {
    if (!isValidated) {
      document.getElementById('modal-belum-validasi').style.display = 'flex';
    } else {
      document.getElementById('modal-setujui-laporan').style.display = 'flex';
    }
  }

  function handleTolak() {
    if (!isValidated) {
      document.getElementById('modal-belum-validasi').style.display = 'flex';
    } else {
      document.getElementById('modal-tolak-laporan').style.display = 'flex';
    }
  }

  function selectTanggapan(key, row) {
    document.querySelectorAll('#tanggapanTable .tg-row').forEach(r => r.classList.remove('tg-selected'));
    row.classList.add('tg-selected');
    document.getElementById('tg_' + key).checked = true;
  }

  function submitSetujui() {
    const selected = document.querySelector('input[name="tanggapan_pimpinan"]:checked');
    if (!selected) {
      alert('Pilih salah satu tanggapan terlebih dahulu.');
      return;
    }
    const btn = document.getElementById('btn-konfirmasi-setujui');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

    fetch('{{ route('direktur.laporan.approve', $laporan->id) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ tanggapan_pimpinan: selected.value }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        window.location.href = '/dashboard/wadir?panel=laporan';
      } else {
        alert(data.message || 'Terjadi kesalahan.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Setujui Laporan';
      }
    })
    .catch(() => {
      alert('Terjadi kesalahan jaringan.');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-check"></i> Setujui Laporan';
    });
  }

  function submitTolak() {
    const btn = document.getElementById('btn-konfirmasi-tolak');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

    fetch('{{ route('direktur.laporan.reject', $laporan->id) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
      body: JSON.stringify({}),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        window.location.href = '/dashboard/wadir?panel=laporan';
      } else {
        alert(data.message || 'Terjadi kesalahan.');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-undo"></i> Ya, Kembalikan';
      }
    })
    .catch(() => {
      alert('Terjadi kesalahan jaringan.');
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-undo"></i> Ya, Kembalikan';
    });
  }

  // Tutup modal klik di luar
  document.querySelectorAll('.lk-modal-overlay').forEach(function(el) {
    el.addEventListener('click', function(e) {
      if (e.target === el) el.style.display = 'none';
    });
  });
</script>
@endif {{-- end isDirekturView --}}

@else
  {{-- Non-direktur: tombol cetak --}}
  <div class="no-print" style="text-align:center;padding:20px;">
    <button onclick="window.print()" style="background:#00B5A0;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;">
      Cetak PDF
    </button>
  </div>
@endif

@if(isset($for_pdf) && !$for_pdf)</div>@endif
</body>
</html>
