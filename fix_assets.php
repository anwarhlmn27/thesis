<?php
$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);
$content = str_replace("asset('vendor", "asset('assets/vendor", $content);
$content = str_replace("asset('css", "asset('assets/css", $content);
$content = str_replace("asset('js", "asset('assets/js", $content);
file_put_contents($file, $content);
echo "Done!\n";
