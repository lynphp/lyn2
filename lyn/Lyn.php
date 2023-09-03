<?php

namespace lyn;

use lyn\data\JSONResponse;
use lyn\helpers\Config;
use lyn\helpers\StringHelpers;
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
        //$lynConf['db'] = $appConfig['db'];
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
        Path::$apiComponentPath = dirname($_SERVER["SCRIPT_FILENAME"]) . '\src\\components\\';
        //application/fragment or application/json
        Request::$lynHeader = $_SERVER['HTTP_LYN_REQUEST_HEADER'] ?? '';
        Request::$acceptType = $_SERVER['HTTP_ACCEPT'] ?? '';
        Request::$method = $_SERVER['REQUEST_METHOD'] ?? '';
        /**
         * Let's make sure we can read and handle the url properly
         */
        $rawUrl = $_SERVER['REQUEST_URI'] ?? '';
        $rawUrl = explode('?', $rawUrl)[0];
        Request::$url = filter_var($rawUrl, FILTER_SANITIZE_URL);
        Request::$route = substr(Request::$url, strlen(route_base_path) + 1);
        Request::$routeParts = explode('/', Request::$route);
        /**
         * 
         */
        $rawGet = $_GET;
        /**
         * 
         */
        Request::$get = filter_var($rawGet, FILTER_SANITIZE_URL);

        /**
         * handle route 
         */
        if (Request::$lynHeader === 'application/fragment') {
            Request::$type = 'fragment';
            $slotContent = $this->handleFragmentRequest($url, Request::$get);
            http_response_code(200);
            echo $slotContent;
            return;
        }
        if (Request::$routeParts[0] === 'api') {
            $comp = Request::$routeParts[1];
            $service = Request::$routeParts[2];
            $uComp = StringHelpers::toCamelCase($comp);
            $response = new JSONResponse();
            $response->requestTime = '';
            if (Request::$method === 'GET') {
                $response->data = eval('use App\Components\\' . $uComp . 'Component; $component = new ' . $uComp . 'Component(); return $component->' . $service . '($_GET);');
            } elseif (Request::$method === 'POST') {
                $response->data = eval('use App\Components\\' . $uComp . 'Component; $component = new ' . $uComp . 'Component(); return $component->' . $service . '($_POST,$_GET);');
            } elseif (Request::$method === 'PUT') {
                $response->data = eval('use App\Components\\' . $uComp . 'Component; $component = new ' . $uComp . 'Component(); return $component->' . $service . '($_PUT,$_GET);');
            } elseif (Request::$method === 'DELETE') {
                $response->data = eval('use App\Components\\' . $uComp . 'Component; $component = new ' . $uComp . 'Component(); return $component->' . $service . '($_DELETE,$_GET);');
            }
            header('Content-Type: application/json');
            echo  $response->toJSONString();
            return;
        }
        $slotContent = $this->handleURL(Request::$url, Request::$get);
        ob_start();
        require base_path . '\main.php';
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
        if (Request::$method === 'GET') {
            eval(' use App\Components\ShoeComponent; $component = new Shoe(); echo $component->index();');
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
        $bp = strlen('routes\\');
        //@var $url = products/catalog/mens/shoe
        $route = substr($url, strlen(route_base_path) + 1);
        Request::$route = $route;
        //products/catalog/mens
        $routeParts = explode('/', Request::$route);
        ob_start();
        $routeFound = false;
        if (sizeof($routeParts) > 1) {
            if (file_exists(Path::$routePath . $routeParts[0])) {
                $toCheck = Path::$routePath . $routeParts[0] . '\\' . $routeParts[1];
                if (array_key_exists(1, $routeParts) && file_exists($toCheck)) {
                    // echo $routeParts[1];
                    $toCheck = Path::$routePath . $routeParts[0] . '\\' . $routeParts[1] . '\\' . $routeParts[2];
                    if (array_key_exists(2, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '\\' . $routeParts[1] . '/' . $routeParts[2])) {
                        // echo $routeParts[2];
                    } else if (array_key_exists(2, $routeParts) && file_exists(Path::$routePath . $routeParts[0] . '\\' . $routeParts[1] . '/[slug]')) {
                        $toCheck = Path::$routePath . $routeParts[0] . '\\' . $routeParts[1] . '\[slug]/index.php';
                        if (file_exists($toCheck)) {
                            Path::$hotPath = Path::$routePath . $routeParts[0] . '\\' . $routeParts[1];
                            $routeFound = true;
                            Request::$slugs = array_slice($routeParts, 2);
                            //read the first 400 words

                            $indexContent = file_get_contents($toCheck, false, null, 0, 400);
                            require Path::$routePath . $routeParts[0] . '\\' . $routeParts[1] . '\[slug]/index.php';
                            if (function_exists('index')) {
                                echo call_user_func('index');
                            }
                        } else {
                            echo 'not found';
                        }
                    }
                } else if (file_exists(Path::$routePath . $routeParts[0] . '\[slug]')) {
                    echo '[slug]';
                }
            }
        }

        if ($routeFound === false) {
            if (file_exists(Path::$routePath . $route)) {
                //load the rout teamplate;
                if (file_exists(Path::$routePath . $route . '\index.php')) {
                    require Path::$routePath . $route . '\index.php';
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
