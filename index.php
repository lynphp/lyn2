<?php
define('time_start', microtime(true));
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
echo 'this page was rendered in the server in ' . substr((microtime(true) - time_start) * 1000, 0, -10) . ' millisecond';
