<?php

define('time_start', microtime(true));
const base_path = __DIR__;
const route_base_path = '\lyn';
const public_web_path = "\lyn\public";
const public_server_path = __DIR__ . "\public";
require_once 'vendor\autoload.php';
require_once 'lyn\lyndex.php';

use lyn\Lyn;

/**
 * set your environment to backend|prod
 */
$env = 'backend';
$conf = __DIR__ . '\config\app.' . $env . '.php';

/**
 * 
 */
$app = new Lyn();
//Initialize the web app to support unit testing.
$app->init($conf);
//set $handleRoute to true to handle user request
if ($handleRoute ?? true) {
    $app->start();
}
