<?php
//phpinfo();
const strict_mode = 1;
define('time_start', microtime(true));
const base_path = __DIR__;
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']),DIRECTORY_SEPARATOR);
define("url_base_path",$base);
const assets_path = "/public";
const public_server_path = __DIR__ . "/public";
require_once 'vendor/autoload.php';
/**
 * set your environment to backend|prod
 */
const env = 'dev';
$conf = __DIR__ . '/config/lyn.conf.php';

/**
 * 
 */
try {
    $app=(new \lyn\Lyn())->init($conf);
    $app->start();
} catch (ErrorException $e) {
}
