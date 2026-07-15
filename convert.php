<?php
$content = file_get_contents('resources/views/index.html');
$content = preg_replace('/(href|src)=\"(\.\/)?(vendor|css|images|js|scss|icons|uploads)\/([^\"]+)\"/', '$1="{{ asset(\'assets/$3/$4\') }}"', $content);
file_put_contents('resources/views/dashboard.blade.php', $content);
echo "Done!\n";
