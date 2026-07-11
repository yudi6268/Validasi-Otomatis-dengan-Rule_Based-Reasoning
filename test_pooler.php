<?php
try {
    $start = microtime(true);
    $pdo = new PDO('pgsql:host=aws-0-us-east-1.pooler.supabase.com;port=6543;dbname=postgres;sslmode=require', 'postgres.zrotygtbcwcscqbjevqt', 'Kinerja2025!');
    echo 'Connected in ' . round((microtime(true) - $start)*1000) . 'ms\n';
} catch (Exception $e) {
    echo 'Failed: ' . $e->getMessage();
}
