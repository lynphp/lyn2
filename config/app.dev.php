<?php

global $db, $router;
require "db.".env.".php";
require "router.".env.".php";
require "backend.".env.".php";
$appConfig = array(
    'environment' => 'dev',
    'basePath' => '',
    'title' => 'Lyn Framework',
    'version' => '0.1.0',
    'email' => 'admin@localhost.com',
    'defaultRender' => 'ssr',
    'dependencies'=>[

    ],
    'components' => array(
        'app' => array(
            'paths' => array(
                '.\components\\',
                '.\models\\',
                '.\views\\',
                '.\controllers\\',
                '.\modules\\',
            ),
            'loader1' => 'appClassAutoloader'
        ),
    ),
    'db' => $db,
    'router' => $router,
    'error404' => '../error404.php'

);
