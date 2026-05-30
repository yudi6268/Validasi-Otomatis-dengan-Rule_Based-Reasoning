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
    .list-item { font-size: 12pt; margin-left: 1.5em; margin-bottom: 3pt; line-height: 1.15; text-align: justify; }
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
    @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } .no-print { display: none; } }
  </style>
</head>
<body>
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

  $fmt = fn($v) => is_numeric($v) ? number_format((float)$v, 0, ',', '.') : ($v ?? '-');

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
  $babCapaianText = $realisasiData['text']    ?? $laporan->bab_kendala ?? $laporan->bab_capaian ?? '';
  $babRencanaText = $realisasiData['followup'] ?? $laporan->bab_rencana ?? '';

  $location  = $perjanjian->location ?? 'Pasuruan';
  $agreeDate = $laporan->updated_at ? $laporan->updated_at->locale('id')->isoFormat('D MMMM Y') : '';

  $tanggapanOptions = [
    'kurang_baik'     => 'Laporan kurang baik',
    'sudah_baik'      => 'Laporan sudah baik',
    'diperbaiki'      => 'Laporan diperbaiki',
    'diteliti_ulang'  => 'Laporan diteliti ulang',
    'realisasi_ulang' => 'Realisasi diteliti ulang',
    'capaian_ulang'   => 'Capaian diteliti ulang',
  ];
  $tanggapanVal = $laporan->tanggapan_pimpinan ?? '';
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
      return array_values(array_filter(preg_split('/\r\n|\r|\n/', trim($val)), 'strlen'));
    };
    // Use ?: so empty strings fall through to the next source
    $fungsiRaw    = $perjanjian->fungsi_pelaksana ?: ($perjanjian->user->fungsi ?? '');
    $tugasRaw     = $perjanjian->tugas_pelaksana  ?: ($perjanjian->user->tugas  ?? '');
    $membawahiRaw = $perjanjian->user->membawahi ?? '';
    // Fallback: look up Jabatan model using multiple name candidates + LIKE
    if (!$fungsiRaw || !$tugasRaw) {
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
        if (!$fungsiRaw) $fungsiRaw = $jabatanModel->fungsi; // already decoded array by model cast
        if (!$tugasRaw)  $tugasRaw  = $jabatanModel->tugas ?? '';
      }
    }
    $fungsiItems    = $toItems($fungsiRaw);
    $tugasItems     = $toItems($tugasRaw);
    $membawahiItems = $toItems($membawahiRaw);
  ?>
  <?php if(!empty($laporan->bab_pelaksanaan)): ?>
    <div class="body-text"><?php echo nl2br(e($laporan->bab_pelaksanaan)); ?></div>
  <?php elseif(!empty($fungsiItems)): ?>
    <?php if(count($fungsiItems) === 1): ?>
      <p class="body-text"><strong><?php echo e($jabatan1); ?></strong> mempunyai fungsi <?php echo e($fungsiItems[0]); ?></p>
    <?php else: ?>
      <p class="body-text-noindent"><strong><?php echo e($jabatan1); ?></strong> mempunyai fungsi :</p>
      <?php $__currentLoopData = $fungsiItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <p class="list-item"><?php echo e(chr(97+$i)); ?>.&nbsp;&nbsp;<?php echo e(trim($f)); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php if(!empty($tugasItems)): ?>
      <p class="body-text-noindent">Untuk melaksanakan fungsinya, <strong><?php echo e($jabatan1); ?></strong> mempunyai tugas yaitu :</p>
      <?php $__currentLoopData = $tugasItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <p class="list-item"><?php echo e(chr(97+$i)); ?>.  <?php echo e(trim($tugas)); ?></p>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <?php if(!empty($membawahiItems)): ?>
      <p class="body-text-noindent"><strong><?php echo e($jabatan1); ?></strong> membawahi :</p>
      <?php $__currentLoopData = $membawahiItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <p class="list-item"><?php echo e(chr(97+$i)); ?>.  <?php echo e(trim($unit)); ?></p>
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
    <table class="data-table">
      <thead>
        <tr>
          <th style="width:5%;">NO</th><th style="width:35%;">Sasaran</th><th style="width:45%;">Indikator Kinerja</th><th style="width:15%;">Target</th>
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
            $pKet = $prog['source'] ?? $prog['keterangan'] ?? $prog['ket'] ?? '';
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
              $kKet = $kg['source'] ?? $kg['keterangan'] ?? $kg['ket'] ?? '';
            ?>
            <tr><td class="center"><?php echo e($kg['no']??''); ?></td><td class="indent"><?php echo e($kg['name']??'-'); ?></td><td class="right"><?php echo e($kb>0?number_format($kb,0,',','.'):'–'); ?></td><td class="center"><?php echo e($kKet); ?></td></tr>
            <?php $__currentLoopData = $kg['subKegiatan'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php
                $sb=0; for($t=1;$t<=4;$t++) $sb+=floatval($sub['tw'.$t]??0);
                $sKet = $sub['source'] ?? $sub['keterangan'] ?? $sub['ket'] ?? '';
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
            $tgt = $twTgt[$i] ?? '-';
            $rel = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
            $cap = ($rel !== null && is_numeric($tgt) && floatval($tgt) > 0)
              ? round(floatval($rel) / floatval($tgt) * 100, 0) : ($rel !== null ? 100 : '-');
          ?>
          <tr>
            <td class="center"><?php echo e($i + 1); ?></td>
            <td><?php echo e($s); ?></td>
            <td><?php echo e($indik[$i] ?? '-'); ?></td>
            <td class="center"><?php echo e(is_numeric($tgt) ? $fmt($tgt) : $tgt); ?></td>
            <td class="center"><?php echo e($rel !== null ? $fmt($rel) : '-'); ?></td>
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
        <?php $totTgt=0; $totRel=0; ?>
        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $pNo=$prog['no']??''; $pTgt=floatval($prog[$twKey]??0);
            $pRel=floatval($relByKey['anggaran-'.$pNo]['realisasi']??0);
            $totTgt+=$pTgt; $totRel+=$pRel;
            $pPct=$pTgt>0?round($pRel/$pTgt*100,2):'-';
            $pKet2 = $prog['source'] ?? $prog['keterangan'] ?? $prog['ket'] ?? '';
          ?>
          <tr>
            <td class="center bold"><?php echo e($pNo); ?></td><td class="bold"><?php echo e($prog['name']??'-'); ?></td>
            <td class="right"><?php echo e($pTgt>0?$fmt($pTgt):'-'); ?></td>
            <td class="right"><?php echo e($fmt($pRel)); ?></td>
            <td class="center"><?php echo e($pPct!=='-'?$pPct:'-'); ?></td><td class="center"><?php echo e($pKet2); ?></td>
          </tr>
          <?php $__currentLoopData = $prog['kegiatan']??[]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $kNo=$kg['no']??''; $kTgt=floatval($kg[$twKey]??0); $kRel=floatval($relByKey['anggaran-'.$kNo]['realisasi']??0); $kPct=$kTgt>0?round($kRel/$kTgt*100,2):'-'; $kKet2=$kg['source']??$kg['keterangan']??$kg['ket']??''; ?>
            <tr><td class="center"><?php echo e($kNo); ?></td><td class="indent"><?php echo e($kg['name']??'-'); ?></td><td class="right"><?php echo e($kTgt>0?$fmt($kTgt):'-'); ?></td><td class="right"><?php echo e($fmt($kRel)); ?></td><td class="center"><?php echo e($kPct!=='-'?$kPct:'-'); ?></td><td class="center"><?php echo e($kKet2); ?></td></tr>
            <?php $__currentLoopData = $kg['subKegiatan']??[]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <?php $sNo=$sub['no']??''; $sTgt=floatval($sub[$twKey]??0); $sRel=floatval($relByKey['anggaran-'.$sNo]['realisasi']??0); $sPct=$sTgt>0?round($sRel/$sTgt*100,2):'-'; $sKet2=$sub['source']??$sub['keterangan']??$sub['ket']??''; ?>
              <tr><td class="center"><?php echo e($sNo); ?></td><td class="indent2"><?php echo e($sub['name']??'-'); ?></td><td class="right"><?php echo e($sTgt>0?$fmt($sTgt):'-'); ?></td><td class="right"><?php echo e($fmt($sRel)); ?></td><td class="center"><?php echo e($sPct!=='-'?$sPct:'-'); ?></td><td class="center"><?php echo e($sKet2); ?></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($totTgt>0||$totRel>0): ?>
          <tr><td colspan="2" class="center bold">JUMLAH</td><td class="right bold"><?php echo e($fmt($totTgt)); ?></td><td class="right bold"><?php echo e($fmt($totRel)); ?></td><td class="center bold"><?php echo e($totTgt>0?round($totRel/$totTgt*100,2):'-'); ?></td><td></td></tr>
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
        <?php if(trim($line)): ?><p class="list-item"><?php echo e($idx+1); ?>.&nbsp;&nbsp;<?php echo e(trim($line)); ?></p><?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
      <p class="body-text"><?php echo nl2br(e(trim($babRencanaText))); ?></p>
    <?php endif; ?>
  <?php else: ?>
    <p class="body-text">-</p>
  <?php endif; ?>

  <div class="section-heading">E. Tanggapan Atasan Langsung</div>
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
    // Hitung rata-rata capaian kinerja untuk penutup
    $capValues = [];
    foreach ($sasar as $i => $s) {
      $tgt = $twTgt[$i] ?? null;
      $rel = $relByKey['kinerja-'.$i]['realisasi'] ?? null;
      if ($rel !== null && is_numeric($tgt) && floatval($tgt) > 0)
        $capValues[] = round(floatval($rel) / floatval($tgt) * 100, 0);
      elseif ($rel !== null)
        $capValues[] = 100;
    }
    $avgCap = count($capValues) > 0 ? round(array_sum($capValues) / count($capValues), 0) : null;
    $capKategori = $avgCap === null ? '' : ($avgCap >= 90 ? 'Sangat Baik' : ($avgCap >= 75 ? 'Baik' : ($avgCap >= 60 ? 'Cukup' : 'Kurang')));
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
    <p class="body-text">Demikian Laporan Kinerja Tribulan <?php echo e($twName); ?> Tahun <?php echo e($tahun); ?> <?php echo e($jabatan1); ?> dibuat sebagai bentuk pertanggungjawaban kinerja<?php echo $avgCap !== null ? ' dengan rata-rata capaian kinerja sebesar <strong>'.$avgCap.'%</strong> ('.$capKategori.')' : ''; ?>.</p>
    <p class="body-text">Laporan ini diharapkan dapat memberikan gambaran mengenai pelaksanaan kegiatan dan anggaran yang telah dicapai dalam rangka pencapaian target yang telah ditetapkan. Semoga laporan ini bermanfaat dan dapat dijadikan bahan evaluasi dalam rangka peningkatan kinerja ke depan.</p>
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
  <div class="no-print" style="text-align:center; padding:20px;">
    <button onclick="window.print()" style="background:#00B5A0;color:#fff;border:none;padding:10px 28px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;">
      Cetak PDF
    </button>
  </div>
<?php endif; ?>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/laporan/pdf.blade.php ENDPATH**/ ?>