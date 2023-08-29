<?php
$appConfig = array(
    'basePath' => '',
    'title' => 'Lyn Framework',
    'version' => '0.1.0',
    'email' => 'admin@localhost.com',
    'components' => array(
        'app' => array(
            'paths' => array(
                __DIR__ . '\components\\',
                __DIR__ . '\models\\',
                __DIR__ . '\views\\',
                __DIR__ . '\controllers\\',
                __DIR__ . '\modules\\',
            ),
            'loader' => 'appClassAutoloader'
        ),
    ),
    'dbs' => array()

);