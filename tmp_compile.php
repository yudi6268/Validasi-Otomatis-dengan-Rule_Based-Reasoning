<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$compiler = $app->make('blade.compiler');
$path = __DIR__ . '/resources/views/perjanjian/create.blade.php';
$content = file_get_contents($path);
try {
    $compiled = $compiler->compileString($content);
    echo $compiled;
} catch (Throwable $e) {
    echo 'EXCEPTION: ' . get_class($e) . '\n';
    echo $e->getMessage() . '\n';
    echo $e->getTraceAsString() . '\n';
}
