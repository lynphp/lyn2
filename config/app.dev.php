<?php

global $db, $router;
require "backend.".env.".php";
return [
    'environment' => env,
    'title' => 'Lyn Framework',
    'version' => '0.1.0',
    'email' => 'admin@localhost.com',
    'defaultRender' => 'ssr',
    'db' => require 'db.'.env.'.php',
    'router' => require "router.php",
    'plugins' => require "plugins.php",
    'error404' => '../error404.php',
    'hotreload' => require 'hotreload.conf.php',
];
