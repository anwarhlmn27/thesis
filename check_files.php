<?php
$files = [
    'public/css/style.css',
    'public/vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
    'public/vendor/jqvmap/css/jqvmap.min.css',
    'public/vendor/chartist/css/chartist.min.css',
];

foreach ($files as $file) {
    echo $file . ": " . (file_exists($file) ? "EXISTS" : "MISSING") . " (" . (is_readable($file) ? "READABLE" : "NOT READABLE") . ", size: " . @filesize($file) . " bytes)\n";
}
