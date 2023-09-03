<?php


define('time_start', microtime(true));
define("base_path", __DIR__);
define("route_base_path", '\lyn');
define("public_web_path", "\lyn\public");
define("public_server_path", __DIR__ . "\public");
require_once 'vendor\autoload.php';
require_once 'lyn\lyndex.php';

use lyn\Lyn;

/**
 * set your environment to dev|prod
 */
$env = 'dev';
$conf = __DIR__ . '\config\app.' . $env . '.php';
/**
 * 
 */

if ($handleRoute ?? true) {
    $app = new Lyn();
    $app->start($conf);
}
