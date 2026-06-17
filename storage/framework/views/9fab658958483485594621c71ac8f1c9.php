<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Perjanjian Kinerja - <?php echo e($perjanjian->nama ?? 'Dokumen'); ?></title>
    <style>


        @page {
            size: folio <?php echo e($orientation ?? 'portrait'); ?>;
            margin: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '8mm 10mm' : '15mm 15mm'); ?>;
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
            margin-bottom: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '8px' : '12px'); ?>;
            border-bottom: 2px solid #000;
            padding-bottom: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6px' : '8px'); ?>;
        }
        
        .header-simple h1 {
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '10pt' : '13pt'); ?>;
            font-weight: bold;
            margin: 2px 0;
        }
        
        .header-simple h2 {
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '9pt' : '11pt'); ?>;
            font-weight: bold;
            margin: 2px 0;
        }
        
        .doc-title {
            text-align: center;
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '9pt' : '11pt'); ?>;
            font-weight: bold;
            margin: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6px 0' : '10px 0'); ?>;
            text-decoration: underline;
        }
        
        .info-compact {
            margin: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6px 0' : '10px 0'); ?>;
            padding: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '4px 6px' : '6px 8px'); ?>;
            background: #f5f5f5;
            border-left: 3px solid #333;
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '7pt' : '8pt'); ?>;
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
            margin: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '4px 0' : '8px 0'); ?>;
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
            padding: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '3px 2px' : '5px 4px'); ?>;
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6.5pt' : '8pt'); ?>;
            word-wrap: break-word;
            vertical-align: middle;
        }
        
        table td {
            border: 1px solid #999;
            padding: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '2px 3px' : '4px 5px'); ?>;
            text-align: left;
            vertical-align: top;
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6.5pt' : '8pt'); ?>;
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
            margin-top: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '15px' : '25px'); ?>;
            page-break-inside: avoid;
        }
        
        .signature-row {
            display: table;
            width: 100%;
            margin-top: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '8px' : '12px'); ?>;
        }
        
        .signature-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '7pt' : '8pt'); ?>;
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
            margin-top: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '40px' : '50px'); ?>;
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
        <h1>PEMERINTAH KABUPATEN PASURUAN - PERJANJIAN KINERJA TAHUN <?php echo e($perjanjian->tahun ?? date('Y')); ?></h1>
        <h2>UOBK RSUD BANGIL</h2>
    </div>
    
    <?php if(isset($perjanjian->deskripsi) && !empty($perjanjian->deskripsi)): ?>
    <div style="text-align: justify; margin: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6px 0' : '10px 0'); ?>; padding: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '4px' : '6px'); ?>; background: #f9f9f9; font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '7pt' : '8pt'); ?>;">
        <?php echo e($perjanjian->deskripsi); ?>

    </div>
    <?php endif; ?>
    
    <div class="info-compact">
        <?php if(isset($perjanjian->nama)): ?><span><strong>Nama:</strong> <?php echo e($perjanjian->nama); ?></span><?php endif; ?>
        <?php if(isset($perjanjian->jabatan)): ?><span><strong>Jabatan:</strong> <?php echo e($perjanjian->jabatan); ?></span><?php endif; ?>
        <?php if(isset($perjanjian->periode)): ?><span><strong>Periode:</strong> <?php echo e($perjanjian->periode); ?></span><?php endif; ?>
    </div>

    
    
    <?php if($perjanjian->detail && $perjanjian->detail->count() > 0): ?>
    <table>
        <thead>
            <tr>
                <th style="width: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '3%' : '4%'); ?>;">NO</th>
                <?php
                    $firstDetail = $perjanjian->detail->first();
                    $columns = array_diff(
                        array_keys($firstDetail->getAttributes()),
                        ['id', 'perjanjian_kinerja_id', 'created_at', 'updated_at']
                    );
                ?>
                
                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e(strtoupper(str_replace('_', ' ', $column))); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $perjanjian->detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="text-center"><?php echo e($index + 1); ?></td>
                <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <?php if(in_array($column, ['target', 'realisasi', 'anggaran'])): ?>
                            <?php echo e(number_format($detail->$column ?? 0, 0, ',', '.')); ?>

                        <?php elseif($column == 'capaian' || $column == 'persentase'): ?>
                            <?php echo e(number_format($detail->$column ?? 0, 2, ',', '.')); ?>%
                        <?php else: ?>
                            <?php echo e($detail->$column ?? '-'); ?>

                        <?php endif; ?>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php endif; ?>
    
    
    <div class="signature-section">
        <div class="signature-row">
            <div class="signature-col left">
                <div style="margin-bottom: 3px;">Mengetahui,</div>
                <div style="font-weight: bold;">Pihak Pertama</div>
                <div class="signature-name">
                    <?php echo e($perjanjian->pihak_pertama ?? 'Direktur RSUD Bangil'); ?><br>
                    <span style="font-weight: normal; font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6pt' : '7pt'); ?>;">NIP. ________________</span>
                </div>
            </div>
            <div class="signature-col right">
                <div style="margin-bottom: 3px;">Bangil, <?php echo e(date('d F Y')); ?></div>
                <div style="font-weight: bold;">Pihak Kedua</div>
                <div class="signature-name">
                    <?php echo e($perjanjian->pihak_kedua ?? $perjanjian->jabatan ?? '_______________'); ?><br>
                    <span style="font-weight: normal; font-size: <?php echo e(($orientation ?? 'portrait') === 'landscape' ? '6pt' : '7pt'); ?>;">NIP. ________________</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\pdf\perjanjian.blade.php ENDPATH**/ ?>