<?php

use lyn\helpers\Config;

class Lyn
{

    private $config = array();
    /**
     * 
     */
    function start($conf)
    {
        global $config;
        include $conf;
        $lynConf['lynVersion'] = '0.0.1';
        $lynConf['name'] = 'Lyn PHP Framework';
        $lynConf['components'] = array(
            'framework' => array(
                'path' => __DIR__ . '\ramework\\',
                'loader' => 'lynClassAutoloader'
            ),
            'app' => $appConfig['components']['app']
        );
        $config = array_merge($appConfig, $lynConf);
        Config::set($config);
        require './src/main.php';
    }
}
