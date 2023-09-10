<?php

use lyn\helpers\Config;
if($_SERVER['REQUEST_URI']==='/hotreload') {

    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    $hash_file_name = base_path . '/tmp/hotreload.hash';
    $hash_file = fopen(base_path . '/tmp/hotreload.hash', "r");
    $filesize = filesize($hash_file_name);
    $hashText = fread($hash_file, $filesize);
    $hash_files = (array)json_decode($hashText);
    fclose($hash_file);
    $reload = false;
    foreach ($hash_files as $file => $hash) {
        if ($hash !== hash_file('md5', $file)) {
            $reload = true;
            break;
        }
    }
    $msg ="data:".json_encode(['reload' => $reload]). PHP_EOL;
    echo  $msg. PHP_EOL;
    ob_flush();
    exit();
}
