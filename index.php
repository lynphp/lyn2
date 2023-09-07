<?php
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
$conf = __DIR__ . '/config/app.' . env . '.php';
/**
 * 
 */
try {
    (new \lyn\Lyn())->init($conf)->start();
} catch (ErrorException $e) {
}
