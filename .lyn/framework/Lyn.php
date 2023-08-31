<?php

use lyn\helpers\Config;
use lyn\Page;

class Lyn
{

    /**
     * 
     */
    function start($conf)
    {
        global $config;
        include $conf;
        $lynConf['lynVersion'] = '0.0.1';
        $lynConf['name'] = 'Lyn PHP Framework';
        $lynConf['components'] = [
            'framework' => [
                'path' => __DIR__ . '\framework\\',
                'loader' => 'lynClassAutoloader'
            ],
            'app' => $appConfig['components']['app'],
        ];
        $config = array_merge($appConfig, $lynConf);
        Config::set($config);
        Page::setTitle($config['name']);
        Path::$routePath = __DIR__ . './../../src/routes/';
        /**
         * 
         */
        $rawUrl = $_SERVER['REDIRECT_URL'] ?? '';
        /**
         * Let's make sure we can read and handle the url properly
         */
        $url = filter_var($rawUrl, FILTER_SANITIZE_URL);
        Request::$url = $url;
        /**
         * 
         */
        $rawGet = $_GET;
        /**
         * 
         */
        $get = filter_var($rawGet, FILTER_SANITIZE_URL);

        /**
         * handle route 
         */
        $slotContent = $this->handleURL($url, $get);
        ob_start();
        require './src/main.php';
        $page = ob_get_clean();

        $page = str_replace("<slot name='main'></slot>",  $slotContent, $page);
        echo $page;
    }
    private function handleURL($url, $get)
    {
        $route = substr($url, strlen(base_path) - 1);
        Request::$route = $route;
        //products/catalog/mens
        $routeParts = explode('/', Request::$route);
        //echo $routeParts[0];
        //echo $routeParts[1];
        //echo $routeParts[2];
        ob_start();
        $routFound = false;
        if (sizeof($routeParts) > 1) {
            if (file_exists(Path::$routePath . $routeParts[0])) {
                if (array_key_exists(1, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '/' . $routeParts[1])) {
                    // echo $routeParts[1];
                    if (array_key_exists(2, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/' . $routeParts[2])) {
                        // echo $routeParts[2];
                    } else if (array_key_exists(2, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/[slug]')) {
                        if (file_exists(Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/[slug]/index.php')) {
                            Request::$slugs = array_slice($routeParts, 2);
                            $routFound = true;
                            require Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/[slug]/index.php';
                            if (function_exists('component')) {
                                echo call_user_func('component');
                            }
                        } else {
                            echo 'not found';
                        }
                    }
                } else if (file_exists(Path::$routePath . $routeParts[0] . '/[slug]')) {
                    echo '[slug]';
                }
            }
        }
        if ($routFound === false) {
            if (file_exists(Path::$routePath . $route)) {
                //load the rout teamplate;
                if (file_exists(__DIR__ . Path::$routePath . $route . '/index.php')) {
                    require __DIR__ . Path::$routePath . $route . '/index.php';
                } else {
                    Page::setTitle('Page not Found');
                    require Config::$config['error404'];
                }
            } else {
                Page::setTitle('Page not Found');
                require Config::$config['error404'];
            }
        }
        return ob_get_clean();
    }
}
