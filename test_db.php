<?php
$start = microtime(true);
$host = 'aws-0-us-east-1.pooler.supabase.com';
$port = 6543;
$db = 'postgres';
$user = 'postgres.zrotygtbcwcscqbjevqt';
$pass = 'UYP_magang123';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    $pdo = new PDO($dsn, $user, $pass, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    echo "PDO Connection time: " . (microtime(true) - $start) . " seconds\n";
    $stmt = $pdo->query("SELECT 1");
    echo "Query time: " . (microtime(true) - $start) . " seconds\n";
} catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

