<?php
define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo view('admin.lecturers.index', [
        'lecturers' => collect(),
        'errors' => new \Illuminate\Support\ViewErrorBag
    ])->render();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
