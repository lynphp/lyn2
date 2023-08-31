<?php
define('time_start', microtime(true));
define("base_path", "/lyn//");
define("public_web_path", "/lyn/public");
define("public_server_path", __DIR__ . "/public");
require_once '.lyn/lyndex.php';
/**
 * set your environment to dev|prod
 */
$env = 'dev';
$conf = __DIR__ . '/src/config.' . $env . '.php';
/**
 * 
 */
$app = new Lyn();
$app->start($conf);
