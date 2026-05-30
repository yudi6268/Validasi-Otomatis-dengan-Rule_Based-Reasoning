<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .email-header {
            background-color: #009970;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            color: #009970;
            display: inline-block;
            min-width: 100px;
        }
        .message-box {
            background-color: #f9f9f9;
            border-left: 4px solid #009970;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Pesan Kontak Baru</h1>
        </div>
        <div class="email-body">
            <p>Anda menerima pesan baru dari form kontak website Perjanjian Kinerja RSUD Bangil:</p>
            
            <div class="info-row">
                <span class="info-label">Nama:</span>
                <span><?php echo e($nama); ?></span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span><?php echo e($email); ?></span>
            </div>
            
            <div class="message-box">
                <strong>Pesan:</strong>
                <p style="margin: 10px 0 0 0; white-space: pre-wrap;"><?php echo e($pesan); ?></p>
            </div>
        </div>
        <div class="email-footer">
            <p>&copy; <?php echo e(date('Y')); ?> RSUD Bangil - Sistem Perjanjian Kinerja</p>
            <p style="margin-top: 5px;">Email ini dikirim otomatis dari form kontak website.</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\emails\kontak.blade.php ENDPATH**/ ?>