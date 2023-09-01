<?php

use lyn\base\View;
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
        Path::$routePath = base_path . '\routes\\';
        /**
         * 
         */
        $rawUrl = $_SERVER['REDIRECT_URL'] ?? '';
        Path::$apiComponentPath = dirname($_SERVER["SCRIPT_FILENAME"]) . '/src/components/';
        //application/fragment or application/json
        Request::$lynHeader = $_SERVER['HTTP_LYN_REQUEST_HEADER'] ?? '';
        Request::$action = $_SERVER['REQUEST_METHOD'] ?? '';
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
        if (Request::$lynHeader === 'application/fragment') {
            Request::$type = 'fragment';
            $slotContent = $this->handleFragmentRequest($url, $get);
            http_response_code(200);
            echo $slotContent;
            return;
        }
        $slotContent = $this->handleURL($url, $get);
        ob_start();
        require base_path . '/main.php';
        $page = ob_get_clean();
        $page = str_replace("<slot name='main'></slot>",  $slotContent, $page);
        echo $page;
    }
    /**
     * 
     */
    private function handleFragmentRequest($url, $get)
    {
        $api = substr($url, strlen(base_path));
        ob_start();
        if (Request::$action === 'GET') {
            eval(' use Shoe\Shoe; $component = new Shoe(); echo $component->index();');
            //require base_path . '/components/Shoe.php';
            //echo call_user_func('index');
        }
        $response = ob_get_clean();
        return $response;
    }
    /**
     * @param  String $url
     * @param  array $get
     */
    private function handleURL($url, $get)
    {
        $bp = strlen('routes/');
        //@var $url = products/catalog/mens/shoe
        $route = substr($url, strlen(route_base_path) + 1);
        Request::$route = $route;
        //products/catalog/mens
        $routeParts = explode('/', Request::$route);
        //echo $routeParts[0];
        //echo $routeParts[1];
        //echo $routeParts[2];
        ob_start();
        $routeFound = false;
        if (sizeof($routeParts) > 1) {
            if (file_exists(Path::$routePath . $routeParts[0])) {
                $toCheck = Path::$routePath . $routeParts[0] . '\\' . $routeParts[1];
                if (array_key_exists(1, $routeParts) && file_exists($toCheck)) {
                    // echo $routeParts[1];
                    $toCheck = Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/' . $routeParts[2];
                    if (array_key_exists(2, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/' . $routeParts[2])) {
                        // echo $routeParts[2];
                    } else if (array_key_exists(2, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/[slug]')) {
                        $toCheck = Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/[slug]/index.php';
                        if (file_exists($toCheck)) {
                            $routeFound = true;
                            Request::$slugs = array_slice($routeParts, 2);
                            //read the first 400 words

                            $indexContent = file_get_contents($toCheck, false, null, 0, 400);
                            $lines = strtok($indexContent, ';');
                            //echo $indexContent;
                            //echo $lines;
                            $hasNamespace = strpos($indexContent, 'namespace');
                            $hasUseComponents =  strpos($indexContent, 'use components\\');
                            require Path::$routePath . $routeParts[0] . '/' . $routeParts[1] . '/[slug]/index.php';
                            if (function_exists('index')) {
                                echo call_user_func('index');
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
        if ($routeFound === false) {
            if (file_exists(Path::$routePath . $route)) {
                //load the rout teamplate;
                if (file_exists(Path::$routePath . $route . '/index.php')) {
                    require Path::$routePath . $route . '/index.php';
                } else {
                    Page::setTitle('Page not Found');
                    http_response_code(404);
                    require Config::$config['error404'];
                }
            } else {
                Page::setTitle('Page not Found');

                http_response_code(404);
                require Config::$config['error404'];
            }
        }
        return ob_get_clean();
    }
}
