<?php
//https://stackoverflow.com/a/21810423/2353076
header('Content-Type: text/plain');

/**
 * Changes permissions on files and directories within $dir and dives recursively
 * into found subdirectories.
 */
function chmod_r($dir, $dirPermissions, $filePermissions): void
{
    $dp = opendir($dir);
    while($file = readdir($dp)) {
        if (($file === ".") || ($file === ".."))
            continue;

        $fullPath = $dir."/".$file;

        if(is_dir($fullPath)) {
            echo('DIR:' . $fullPath . "\n");
            chmod($fullPath, $dirPermissions);
            chmod_r($fullPath, $dirPermissions, $filePermissions);
        } else {
            echo('FILE:' . $fullPath . "\n");
            chmod($fullPath, $filePermissions);
        }

    }
    closedir($dp);
}

chmod_r(__DIR__, 0755, 0755);
