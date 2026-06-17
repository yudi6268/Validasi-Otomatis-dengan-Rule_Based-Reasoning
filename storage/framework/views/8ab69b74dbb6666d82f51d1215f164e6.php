<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Kinerja - Triwulan <?php echo e($triwulan ?? 1); ?> - <?php echo e($perjanjian->tahun ?? date('Y')); ?></title>
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
    <?php if(isset($for_pdf) && !$for_pdf): ?>
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
    <?php endif; ?>
  </style>
</head>
<body>
<?php if(isset($for_pdf) && !$for_pdf): ?><div class="lk-page-wrapper"><?php endif; ?>
<?php
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
    // remove spaces and dot thousand separators, convert comma decimal to dot
    $s = preg_replace('/[\s\.]/', '', $s);
    $s = str_replace(',', '.', $s);
    return is_numeric($s) ? floatval($s) : null;
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
  $fmtPct = fn($v) => is_numeric($v) ? number_format((float)$v, 2, ',', '.') : ($v ?? '-');

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
    if ($rel !== null && is_numeric($tgt) && floatval($tgt) > 0)
      $capValues[] = round(floatval($rel) / floatval($tgt) * 100, 2);
    elseif ($rel !== null)
      $capValues[] = 100;
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
?>


<div class="page">
  <div class="doc-title-block">
    <h2>LAPORAN KINERJA</h2>
    <h2><?php echo e(strtoupper($jabatan1)); ?></h2>
    <h2>TRIBULAN <?php echo e($twName); ?> TAHUN <?php echo e($tahun); ?></h2>
  </div>

  <div class="bab-title">BAB I</div>
  <div class="bab-subtitle">PENDAHULUAN</div>

  <?php
    // Helper: decode JSON-array or split plain multiline string into array of items
    $toItems = function($val) {
      if (!$val) return [];
      if (is_array($val)) return array_values(array_filter($val, 'strlen'));
      $decoded = json_decode($val, true);
      if (is_array($decoded)) return array_values(array_filter($decoded, 'strlen'));
      $str = trim((string)$val);
      $parts = preg_split('/\r\n|\r|\n|;/u', $str);
      return array_values(array_filter(array_map('trim', $parts), 'strlen'));
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
    // Use ?: so empty strings fall through to the next source
    $fungsiRaw    = $perjanjian->fungsi_pelaksana ?: ($perjanjian->user->fungsi ?? '');
    $tugasRaw     = $perjanjian->tugas_pelaksana  ?: ($perjanjian->user->tugas  ?? '');
    $membawahiRaw = $perjanjian->user->membawahi ?? '';
    
    $normalizeItem = function($item) {
      if ($item === null) {
        return '';
      }
      $item = trim(preg_replace('/[\r\n]+/', ' ', (string)$item));
      // Reduce repeated punctuation at end to a single char (preserve one semicolon/period/comma/colon)
      $item = preg_replace('/([\.;,:]){2,}$/u', '$1', $item);
      $item = preg_replace('/\s{2,}/u', ' ', $item);
      return trim($item);
    };
    // Fallback: look up Jabatan model using multiple name candidates + LIKE
    if (!$fungsiRaw || !$tugasRaw || !$membawahiRaw) {
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
      if ($jabatanModel) {
        if (!$fungsiRaw) $fungsiRaw = $jabatanModel->fungsi;
        if (!$tugasRaw)  $tugasRaw  = $jabatanModel->tugas ?? '';
        if (!$membawahiRaw) $membawahiRaw = $jabatanModel->membawahi ?? '';
      }
    }
    
    // Parse tugas and membawahi as arrays
    $tugasItems     = array_values(array_filter(array_map($normalizeItem, $toItems($tugasRaw)), 'strlen'));
    $membawahiItems = array_values(array_filter(array_map($normalizeItem, $toItems($membawahiRaw)), 'strlen'));

    // For fungsi: keep as-is if it's a complete sentence (long text)
    // Otherwise parse as array
    if (is_string($fungsiRaw) && strlen($fungsiRaw) > 80) {
      $fungsiSentence = $normalizeItem($fungsiRaw);
    } else {
      $fungsiArray = $parseFungsi($fungsiRaw);
      $fungsiArray = array_map(function($f) {
        $f = trim((string)$f);
        $f = preg_replace('/\s*[;,]\s*dan\s*$/iu', '', $f);
        $f = preg_replace('/\s+dan\s*$/iu', '', $f);
        $f = preg_replace('/[\.;,:]+$/u', '', trim($f));
        return trim($f);
      }, $fungsiArray);
      $fungsiArray = array_values(array_filter($fungsiArray, 'strlen'));
      if (count($fungsiArray) > 1) {
        $last = array_pop($fungsiArray);
        $fungsiSentence = implode(', ', $fungsiArray) . ' dan ' . $last;
      } else {
        $fungsiSentence = $fungsiArray[0] ?? $normalizeItem($fungsiRaw);
      }
    }
  ?>
  <?php if(!empty($laporan->bab_pelaksanaan)): ?>
    <div class="body-text"><?php echo nl2br(e($laporan->bab_pelaksanaan)); ?></div>
  <?php elseif(!empty($fungsiSentence)): ?>
    
    <p class="body-text"><strong><?php echo e($jabatan1); ?></strong> mempunyai fungsi <?php echo e($fungsiSentence); ?></p>
    <?php if(!empty($tugasItems)): ?>
      <p class="body-text-noindent">Untuk melaksanakan fungsinya, <strong><?php echo e($jabatan1); ?></strong> mempunyai tugas yaitu :</p>
      <?php $__currentLoopData = $tugasItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $t = trim($tugas);
          if (!preg_match('/[;.]$/', $t)) {
            $t = $t . ';';
          }
        ?>
        <p class="list-item"><?php echo e($i + 1); ?>).&nbsp;&nbsp;<?php echo e($t); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php if(!empty($membawahiItems)): ?>
      <p class="body-text-noindent"><strong><?php echo e($jabatan1); ?></strong> membawahi :</p>
      <?php $__currentLoopData = $membawahiItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $u = trim($unit);
          if (!preg_match('/[;.]$/', $u)) {
            $u = $u . ';';
          }
        ?>
        <p class="list-item"><?php echo e($i + 1); ?>).&nbsp;&nbsp;<?php echo e($u); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  <?php else: ?>
    <p class="body-text">Laporan kinerja ini disusun sebagai bentuk pertanggungjawaban pelaksanaan tugas dan fungsi <?php echo e($jabatan1); ?> pada Tribulan <?php echo e($twName); ?> Tahun <?php echo e($tahun); ?>.</p>
  <?php endif; ?>
</div>


<div class="page page-break">
  <div class="bab-title">BAB II</div>
  <div class="bab-subtitle">AKUNTABILITAS KINERJA JABATAN</div>

  <div class="section-heading">A. Perjanjian Kinerja</div>
  <p class="body-text">Dalam rangka mewujudkan manajemen pemerintahan yang efektif, transparan dan akuntabel serta berorientasi pada hasil. Maka adanya perjanjian kinerja sebagai dasar/target yang dicapai dalam pelaksanaan tugas dan fungsinya oleh <?php echo e($jabatan1); ?> yaitu :</p>

  
  <?php if(!empty($sasar)): ?>
    <?php $satuan = $tabelA['satuan'] ?? []; ?>
    <table class="data-table">
      <thead>
        <tr>
          <th style="width:5%;">NO</th><th style="width:28%;">Sasaran</th><th style="width:37%;">Indikator Kinerja</th><th style="width:18%;">Satuan</th><th style="width:12%;">Target</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $sasar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $annualTgt = '-';
            if (!empty($tabelA) && is_array($tabelA) && isset($tabelA[$i]['target'])) {
              $annualTgt = $tabelA[$i]['target'];
            } else {
              // sum all triwulan targets as annual
              $sum = 0; $hasVal = false;
              for ($t = 1; $t <= 4; $t++) { $v = $tabelB['tw'.$t][$i] ?? null; if (is_numeric($v)) { $sum += (float)$v; $hasVal = true; } }
              $annualTgt = $hasVal ? $fmt($sum) : ($tabelB['tw1'][$i] ?? '-');
            }
          ?>
          <tr>
            <td class="center"><?php echo e($i + 1); ?></td>
            <td><?php echo e($s ?? '-'); ?></td>
            <td><?php echo e($indik[$i] ?? '-'); ?></td>
            <td class="center"><?php echo e($satuan[$i] ?? '-'); ?></td>
            <td class="center"><?php echo e($annualTgt); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  <?php endif; ?>

  
  <?php if(!empty($programs)): ?>
    <table class="data-table">
      <thead>
        <tr><th style="width:5%;">NO</th><th style="width:58%;">PROGRAM / KEGIATAN</th><th style="width:25%;">ANGGARAN</th><th style="width:12%;">KET</th></tr>
      </thead>
      <tbody>
        <?php $totalAnggaran = 0; ?>
        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $ab = 0; for ($t=1;$t<=4;$t++) $ab += floatval($prog['tw'.$t] ?? 0);
            if ($ab == 0) foreach ($prog['kegiatan'] ?? [] as $kg) { for ($t=1;$t<=4;$t++) $ab += floatval($kg['tw'.$t] ?? 0); }
            $totalAnggaran += $ab;
            $pKet = $resolveProgKet($prog);
          ?>
          <tr>
            <td class="center bold"><?php echo e($prog['no'] ?? ''); ?></td>
            <td class="bold"><?php echo e($prog['name'] ?? '-'); ?></td>
            <td class="right bold"><?php echo e($ab > 0 ? number_format($ab,0,',','.') : '-'); ?></td>
            <td class="center"><?php echo e($pKet); ?></td>
          </tr>
          <?php $__currentLoopData = $prog['kegiatan'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $kb = 0; for ($t=1;$t<=4;$t++) $kb += floatval($kg['tw'.$t] ?? 0);
              if ($kb==0) foreach ($kg['subKegiatan']??[] as $sub) { for($t=1;$t<=4;$t++) $kb+=floatval($sub['tw'.$t]??0); }
              $kKet = $resolveKgKet($kg);
            ?>
            <tr><td class="center"><?php echo e($kg['no']??''); ?></td><td class="indent"><?php echo e($kg['name']??'-'); ?></td><td class="right"><?php echo e($kb>0?number_format($kb,0,',','.'):'–'); ?></td><td class="center"><?php echo e($kKet); ?></td></tr>
            <?php $__currentLoopData = $kg['subKegiatan'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $sb=0; for($t=1;$t<=4;$t++) $sb+=floatval($sub['tw'.$t]??0);
                $sKet = $resolveSubKet($sub);
              ?>
              <tr><td class="center"><?php echo e($sub['no']??''); ?></td><td class="indent2"><?php echo e($sub['name']??'-'); ?></td><td class="right"><?php echo e($sb>0?number_format($sb,0,',','.'):'–'); ?></td><td class="center"><?php echo e($sKet); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr><td colspan="2" class="center bold">JUMLAH</td><td class="right bold"><?php echo e(number_format($totalAnggaran,0,',','.')); ?></td><td></td></tr>
      </tbody>
    </table>
  <?php endif; ?>
</div>


<div class="page page-break page-landscape">
  <div class="section-heading">B. Capaian Kinerja</div>
  <p class="body-text">Capaian kinerja <?php echo e($jabatan1); ?> pada Tribulan <?php echo e($twName); ?> Tahun <?php echo e($tahun); ?> dari hasil pengukuran kinerja ini adalah sebagai berikut:</p>

  <?php if(!empty($sasar)): ?>
    <table class="data-table">
      <thead>
        <tr>
          <th rowspan="2" style="width:4%;">NO</th>
          <th rowspan="2" style="width:27%;">Sasaran</th>
          <th rowspan="2" style="width:34%;">Indikator Kinerja</th>
          <th colspan="3">Triwulan <?php echo e($twName); ?></th>
          <th rowspan="2" style="width:7%;">Ket</th>
        </tr>
        <tr>
          <th style="width:12%;">Target</th><th style="width:12%;">Realisasi</th><th style="width:4%;">%</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $sasar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $tgtRaw = $twTgt[$i] ?? null;
            $tgtNum = $normalizeNumber($tgtRaw);
            $relRaw = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
            $relNum = $normalizeNumber($relRaw);
            // Use stored pct if available, otherwise recalculate for backward compatibility
            $cap = $relByKey['kinerja-'.$i]['pct'] ?? null;
            if ($cap === null) {
              $cap = '-';
              if ($relNum !== null && $tgtNum !== null && $tgtNum > 0) {
                $cap = round($relNum / $tgtNum * 100, 2);
              }
            }
          ?>
          <tr>
            <td class="center"><?php echo e($i + 1); ?></td>
            <td><?php echo e($s); ?></td>
            <td><?php echo e($indik[$i] ?? '-'); ?></td>
            <td class="center"><?php echo e($tgtRaw !== null ? $fmtTarget($tgtRaw) : '-'); ?></td>
            <td class="center"><?php echo e($relNum !== null ? $fmt2($relNum) : ($relRaw !== null ? $fmt2($relRaw) : '-')); ?></td>
            <td class="center"><?php echo e($cap !== '-' ? $cap : '-'); ?></td>
            <td></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  <?php endif; ?>

  <?php if(!empty($programs)): ?>
    <table class="data-table">
      <thead>
        <tr>
          <th rowspan="2" style="width:5%;">NO</th>
          <th rowspan="2" style="width:40%;">PROGRAM / KEGIATAN</th>
          <th colspan="3">Triwulan <?php echo e($twName); ?></th>
          <th rowspan="2" style="width:12%;">Ket</th>
        </tr>
        <tr><th style="width:16%;">Target</th><th style="width:16%;">Realisasi</th><th style="width:11%;">%</th></tr>
      </thead>
      <tbody>
        <?php 
          // Calculate totals from sub-kegiatan only (not program/kegiatan)
          $totTgt = 0; 
          $totRel = 0;
          // First pass: collect sub-kegiatan totals
          foreach($programs as $prog) {
            foreach($prog['kegiatan']??[] as $kg) {
              foreach($kg['subKegiatan']??[] as $sub) {
                $sNo = $sub['no'] ?? '';
                $sTgt = $normalizeNumber($sub[$twKey] ?? null) ?? 0;
                $sRel = $normalizeNumber($relByKey['anggaran-'.$sNo]['realisasi'] ?? null) ?? 0;
                $totTgt += $sTgt;
                $totRel += $sRel;
              }
            }
          }
        ?>
        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $pNo = $prog['no'] ?? '';
            $pTgt = $normalizeNumber($prog[$twKey] ?? null) ?? 0;
            $pRel = $normalizeNumber($relByKey['anggaran-'.$pNo]['realisasi'] ?? null) ?? 0;
            // Use stored pct if available, otherwise recalculate for backward compatibility
            $pPct = $relByKey['anggaran-'.$pNo]['pct'] ?? ($pTgt > 0 ? round($pRel / $pTgt * 100, 2) : '-');
            $pKet2 = $resolveProgKet($prog);
          ?>
          <tr>
            <td class="center bold"><?php echo e($pNo); ?></td><td class="bold"><?php echo e($prog['name']??'-'); ?></td>
            <td class="right"><?php echo e($prog[$twKey] ?? null ? $fmtTarget($prog[$twKey]) : '-'); ?></td>
            <td class="right"><?php echo e($fmt2($pRel)); ?></td>
            <td class="center"><?php echo e($pPct !== '-' ? $pPct : '-'); ?></td><td class="center"><?php echo e($pKet2); ?></td>
          </tr>
          <?php $__currentLoopData = $prog['kegiatan']??[]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
              $kNo = $kg['no'] ?? '';
              $kTgt = $normalizeNumber($kg[$twKey] ?? null) ?? 0;
              $kRel = $normalizeNumber($relByKey['anggaran-'.$kNo]['realisasi'] ?? null) ?? 0;
              // Use stored pct if available, otherwise recalculate for backward compatibility
              $kPct = $relByKey['anggaran-'.$kNo]['pct'] ?? ($kTgt > 0 ? round($kRel / $kTgt * 100, 2) : '-');
              $kKet2 = $resolveKgKet($kg);
            ?>
            <tr><td class="center"><?php echo e($kNo); ?></td><td class="indent"><?php echo e($kg['name']??'-'); ?></td><td class="right"><?php echo e(($kg[$twKey] ?? null) !== null ? $fmtTarget($kg[$twKey]) : '-'); ?></td><td class="right"><?php echo e($fmt2($kRel)); ?></td><td class="center"><?php echo e($kPct!=='-'?$kPct:'-'); ?></td><td class="center"><?php echo e($kKet2); ?></td></tr>
            <?php $__currentLoopData = $kg['subKegiatan']??[]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $sNo = $sub['no'] ?? '';
                $sTgt = $normalizeNumber($sub[$twKey] ?? null) ?? 0;
                $sRel = $normalizeNumber($relByKey['anggaran-'.$sNo]['realisasi'] ?? null) ?? 0;
                // Use stored pct if available, otherwise recalculate for backward compatibility
                $sPct = $relByKey['anggaran-'.$sNo]['pct'] ?? ($sTgt > 0 ? round($sRel / $sTgt * 100, 2) : '-');
                $sKet2 = $resolveSubKet($sub);
              ?>
              <tr><td class="center"><?php echo e($sNo); ?></td><td class="indent2"><?php echo e($sub['name']??'-'); ?></td><td class="right"><?php echo e(($sub[$twKey] ?? null) !== null ? $fmtTarget($sub[$twKey]) : '-'); ?></td><td class="right"><?php echo e($fmt2($sRel)); ?></td><td class="center"><?php echo e($sPct!=='-'?$sPct:'-'); ?></td><td class="center"><?php echo e($sKet2); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($totTgt>0||$totRel>0): ?>
          <tr><td colspan="2" class="center bold">JUMLAH</td><td class="right bold"><?php echo e($fmt($totTgt)); ?></td><td class="right bold"><?php echo e($fmt($totRel)); ?></td><td class="center bold"><?php echo e($totTgt>0 ? round($totRel/$totTgt*100,2) : '-'); ?></td><td></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>


<div class="page page-break">
  <div class="section-heading">C. Evaluasi dan Analisis Kinerja</div>
  <?php if(!empty($babCapaianText)): ?>
    <?php $capaianParas = array_values(array_filter(preg_split('/\n\s*\n/', $babCapaianText))); ?>
    <?php if(count($capaianParas) > 1): ?>
      <?php $__currentLoopData = $capaianParas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $para): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <p class="body-text"><?php echo nl2br(e(trim($para))); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
      <p class="body-text"><?php echo nl2br(e(trim($babCapaianText))); ?></p>
    <?php endif; ?>
  <?php else: ?>
    <p class="body-text">-</p>
  <?php endif; ?>

  <div class="section-heading">D. Rencana Tindak Lanjut</div>
  <?php if(!empty($babRencanaText)): ?>
    <?php
      $rLines = array_values(array_filter(preg_split('/\r\n|\r|\n/', trim($babRencanaText))));
      // Fallback: also try splitting by "; " if no newlines found
      if (count($rLines) < 2) {
        $rAlt = array_values(array_filter(preg_split('/;\s+/', trim($babRencanaText))));
        if (count($rAlt) >= 2) $rLines = $rAlt;
      }
    ?>
    <?php if(count($rLines) > 1): ?>
      <p class="body-text-noindent">Adapun Rencana Tindak Lanjut dari hasil capaian kinerja :</p>
      <?php $__currentLoopData = $rLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          // Strip any existing leading number/bullet (e.g. "1.", "2.1.", "a)") so
          // the sequential counter we add is always clean: 1, 2, 3 …
          $cleanLine = preg_replace('/^\s*[\d][\d\.]*[\.)\s]+/', '', trim($line));
          $cleanLine = $cleanLine ?: trim($line);
        ?>
        <?php if(trim($line)): ?><div class="list-row"><span class="list-num"><?php echo e($idx+1); ?>.</span><span class="list-text"><?php echo e($cleanLine); ?></span></div><?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
      <p class="body-text"><?php echo nl2br(e(trim($babRencanaText))); ?></p>
    <?php endif; ?>
  <?php else: ?>
    <p class="body-text">-</p>
  <?php endif; ?>

  <div class="section-heading">E. Tanggapan Atasan Langsung</div>
  <?php if(!empty($validationSummaryText)): ?>
    <p class="body-text" style="font-style:italic; margin-bottom:10px;"><?php echo $validationSummaryText; ?></p>
  <?php endif; ?>
  <table class="check-table">
    <thead>
      <tr>
        <td class="check-col" style="font-weight:bold;">Tanda (V)</td>
        <td style="font-weight:bold;font-style:italic;text-align:center;">uraian</td>
      </tr>
    </thead>
    <tbody>
      <?php $__currentLoopData = $tanggapanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td class="check-col"><?php echo e($tanggapanVal === $key ? '√' : ''); ?></td>
          <td style="font-style:italic;"><?php echo e($label); ?></td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
  </table>
</div>


<div class="page page-break">
  <div class="bab-title">BAB III</div>
  <div class="bab-subtitle">PENUTUP</div>

  <?php
    // Hitung rata-rata capaian kinerja untuk penutup - gunakan stored pct jika ada
    $capValues = [];
    foreach ($sasar as $i => $s) {
      // Use stored pct if available
      $pct = $relByKey['kinerja-'.$i]['pct'] ?? null;
      if ($pct !== null) {
        $capValues[] = $pct;
      } else {
        // Fallback: recalculate for backward compatibility
        $tgt = $twTgt[$i] ?? null;
        $rel = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
        if ($rel !== null && is_numeric($tgt) && floatval($tgt) > 0)
          $capValues[] = round(floatval($rel) / floatval($tgt) * 100, 2);
        elseif ($rel !== null)
          $capValues[] = 100;
      }
    }
    $avgCap = count($capValues) > 0 ? round(array_sum($capValues) / count($capValues), 2) : null;
    $capKategori = $avgCap === null ? '' : ($avgCap >= 91 ? 'Sangat Tinggi' : ($avgCap >= 76 ? 'Tinggi' : ($avgCap >= 66 ? 'Sedang' : ($avgCap >= 51 ? 'Rendah' : 'Sangat Rendah'))));
  ?>
  <?php
    $kesimpulanText = trim($laporan->kesimpulan ?? '');
    // Strip auto-generated "BAB III PENUTUP" heading prefix if present (handles \n and \r\n)
    $kesimpulanText = preg_replace('/^BAB\s+III\s+PENUTUP\s*[\r\n]+/iu', '', $kesimpulanText);
    $kesimpulanText = trim($kesimpulanText);
  ?>
  <?php if(!empty($kesimpulanText)): ?>
    <?php $penutupParas = array_values(array_filter(preg_split('/\n\s*\n|\r\n\s*\r\n/', $kesimpulanText))); ?>
    <?php if(count($penutupParas) > 1): ?>
      <?php $__currentLoopData = $penutupParas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $para): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <p class="body-text"><?php echo nl2br(e(trim($para))); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
      <p class="body-text"><?php echo nl2br(e($kesimpulanText)); ?></p>
    <?php endif; ?>
  <?php else: ?>
    <?php
      $persentase = $composite ?? $avgCap;
      $compKat = $performanceForCategory !== null ? $performanceCategoryText : $capKategori;
      $twTextArr = [1=>'pertama',2=>'kedua',3=>'ketiga',4=>'keempat'];
      $twTxt     = $twTextArr[$tw] ?? (string)$tw;
      $arahFb    = $persentase === null ? '' : ($persentase >= 91
        ? 'upaya tindak lanjut diarahkan untuk menjaga konsistensi mutu, memperluas praktik baik, dan memastikan keberlanjutan kinerja unggul.'
        : ($persentase >= 76
            ? 'upaya tindak lanjut diarahkan untuk menutup celah minor pada indikator tertentu serta memperkuat pengendalian pelaksanaan.'
            : ($persentase >= 51
              ? 'upaya tindak lanjut diarahkan pada akselerasi kinerja melalui penajaman prioritas, penguatan koordinasi, dan monitoring lebih intensif.'
              : 'upaya tindak lanjut diarahkan pada langkah korektif terstruktur, penataan strategi pelaksanaan, serta peningkatan disiplin monitoring dan evaluasi.')));
      $persentaseFormatted = $fmtPct($persentase);
    ?>
    <p class="body-text">Berdasarkan hasil pengukuran kinerja Triwulan <?php echo e($twName); ?> (<?php echo e($twTxt); ?>) Tahun <?php echo e($tahun); ?> pada jabatan <?php echo e($jabatan1); ?>, capaian komposit indikator kinerja dan anggaran mencapai <strong><?php echo e($persentaseFormatted); ?>%</strong> dengan predikat <strong><?php echo e($compKat); ?></strong>. Capaian ini menjadi dasar evaluasi untuk memastikan kesinambungan peningkatan kualitas pelaksanaan program pada periode berikutnya.</p>
  <?php endif; ?>

  <div class="sig-section">
    <div class="sig-row">
      
      <div class="sig-col">
        <p style="font-size:12pt;">Mengetahui</p>
        <p style="font-size:12pt;"><?php echo e($jabatan2); ?></p>
        <br>
        <?php if($isApproved && $sigSrc($pihak2Sig)): ?>
          <img class="sig-img" src="<?php echo e($sigSrc($pihak2Sig)); ?>" alt="TTD Atasan">
        <?php else: ?>
          <div style="height:70px;"></div>
        <?php endif; ?>
        <br>
        <span class="sig-name"><?php echo e($nama2); ?></span><br>
        <?php if($nip2): ?><span class="sig-nip">NIP. <?php echo e($nip2); ?></span><?php endif; ?>
      </div>
      
      <div class="sig-col">
        <p style="font-size:12pt;"><?php echo e($location); ?>, <?php echo e($agreeDate); ?></p>
        <p style="font-size:12pt;"><?php echo e($jabatan1); ?></p>
        <br>
        <?php if($sigSrc($pihak1Sig)): ?>
          <img class="sig-img" src="<?php echo e($sigSrc($pihak1Sig)); ?>" alt="TTD Pegawai">
        <?php else: ?>
          <div style="height:70px;"></div>
        <?php endif; ?>
        <br>
        <span class="sig-name"><?php echo e($nama1); ?></span><br>
        <?php if($nip1): ?><span class="sig-nip">NIP. <?php echo e($nip1); ?></span><?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php if(isset($for_pdf) && !$for_pdf): ?>
<?php
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
?>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<?php if($isDirekturView): ?>
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

<?php if($alreadyApproved): ?>
  
  <div class="no-print" style="position:fixed;top:88px;right:20px;z-index:1200;background:#d4edda;border:1px solid #c3e6cb;border-radius:8px;padding:12px 20px;display:flex;align-items:center;gap:10px;box-shadow:0 2px 12px rgba(0,0,0,0.1);">
    <i class="fas fa-check-circle" style="color:#155724;font-size:18px;"></i>
    <span style="color:#155724;font-weight:700;font-size:14px;">Laporan sudah disetujui</span>
  </div>

<?php else: ?>
  
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
        <?php if(!empty($validationSummaryText)): ?>
          <div style="background:#e9f7ef;border-left:4px solid #009970;padding:14px 16px;margin-bottom:18px;border-radius:10px;color:#0f5132;font-size:13px;line-height:1.5;">
            <?php echo $validationSummaryText; ?>

          </div>
        <?php endif; ?>
        <table class="check-tbl" id="tanggapanTable">
          <thead>
            <tr>
              <th style="width:50px;">Tanda (√)</th>
              <th>Uraian</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $tanggapanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="tg-row <?php echo e($selectedTanggapan === $key ? 'tg-selected' : ''); ?>"
                onclick="selectTanggapan('<?php echo e($key); ?>', this)">
              <td style="text-align:center;">
                <input type="radio" name="tanggapan_pimpinan" id="tg_<?php echo e($key); ?>" value="<?php echo e($key); ?>"
                  <?php echo e($selectedTanggapan === $key ? 'checked' : ''); ?>

                  onclick="event.stopPropagation();">
              </td>
              <td><?php echo e($label); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php endif; ?> 

<script>
  var isValidated = <?php echo e($isValidated ? 'true' : 'false'); ?>;

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

    fetch('<?php echo e(route('direktur.laporan.approve', $laporan->id)); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'Accept': 'application/json',
      },
      body: JSON.stringify({ tanggapan_pimpinan: selected.value }),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        window.location.reload();
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

    fetch('<?php echo e(route('direktur.laporan.reject', $laporan->id)); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
        'Accept': 'application/json',
      },
      body: JSON.stringify({}),
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        window.location.reload();
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
<?php endif; ?> 

<?php else: ?>
  
  <div class="no-print" style="text-align:center;padding:20px;">
    <button onclick="window.print()" style="background:#00B5A0;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;">
      Cetak PDF
    </button>
  </div>
<?php endif; ?>

<?php if(isset($for_pdf) && !$for_pdf): ?></div><?php endif; ?>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\laporan\pdf.blade.php ENDPATH**/ ?>