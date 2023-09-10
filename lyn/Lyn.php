<?php

namespace lyn;

use ErrorException;
use lyn\base\View;
use lyn\data\JSONResponse;
use lyn\helpers\Config;
use lyn\helpers\StringHelpers;

class Lyn
{
    public function __construct()
    {
        //$docLen = strlen($_SERVER['CONTEXT_DOCUMENT_ROOT']);
        //$srpLen = strlen($_SERVER['SCRIPT_FILENAME']);
        //define("uri_base_path", substr($_SERVER['DOCUMENT_ROOT'],$docLen));
    }

    /**
     * @throws ErrorException
     */
    final public  function init(string $conf):Lyn
    {
        global $config;
        $appConfig = [];
        if (file_exists($conf)) {
            $config= require $conf;
        } else {
            throw new ErrorException("Error:E10005: Config file {$conf} does not exist");
        }
        //$lynConf['db'] = $appConfig['db'];

        Config::set($config);
        Page::setTitle($config['name']);
        Path::$routePath = base_path . '/routes/';
        Path::$apiComponentPath = dirname($_SERVER["SCRIPT_FILENAME"]) . '/src/components/';
        return $this;
    }
    /**
     *
     * @throws ErrorException
     */
    final public function start():void
    {
        //application/fragment or application/json
        Request::$lyn_header = $_SERVER['HTTP_LYN_REQUEST_HEADER'] ?? '';
        Request::$http_accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        Request::$method = $_SERVER['REQUEST_METHOD'] ?? '';
        /**
         * Let's make sure we can read and handle the url properly
         */
        $rawUrl = explode('?',$_SERVER['REQUEST_URI'])[0]?? '';
        Request::$url = filter_var($rawUrl, FILTER_SANITIZE_URL);
        Path::$route = str_replace("/",DIRECTORY_SEPARATOR ,ltrim(Request::$url, url_base_path));
        Request::$segments = explode(DIRECTORY_SEPARATOR, Path::$route);

        $rawGet = $_GET;
        /**
         * 
         */
        Request::$get[] = filter_var($rawGet, FILTER_SANITIZE_URL);
        if (isset(Request::$get['lyn'])) {
            if (Request::$get['lyn'] === 'phpinfo') {
            }
        }
        /**
         * handle route for WebComponent
         */
        if (Request::$http_accept === 'application/fragment') {
            Request::$type = 'fragment';
            $slot_content = $this->handleFragmentRequest(Request::$url, Request::$get);
            http_response_code(200);
            echo $slot_content;
            return;
        }
        //API or WebService/MicroServices handlers
        $API_TYPE=0;
        $API_VERSION=1;
        $API_COMPONENT=2;
        $API_SERVICE=3;
        //example URI: api/v1/component/doc
        //example URI: api/v1/component/service_name
        if (Request::$segments[$API_TYPE] === 'api') {
            $response = new JSONResponse();
            $response->requestTime = '';

            if(count(Request::$segments) < 3){
                http_response_code(404);
                $response->data='404 Service does not exist for: ' . Path::$route;
                echo  $response->toJSONString();
                return;
            }

            $comp = Request::$segments[$API_COMPONENT];
            $version = Request::$segments[$API_VERSION];
            $service = Request::$segments[$API_SERVICE];
            $uComp = StringHelpers::toCamelCase($comp);
            //file::components/v1/shoe/ShoeComponent.php
            //use::App\components\v1\shoe\ShoeComponent
            $compFile = base_path.'/components/'.$version.'/' . $uComp . 'Component.php';
            if (!file_exists($compFile)) {
                http_response_code(404);
                $response->data='404 Service does not exist for: ' . Path::$route;
                echo  $response->toJSONString();
                return;
            }
            /*
             * App\components\v1\shoe\ShoeComponent; $component = new ShoeComponent(); return $component->service_name()
             */
            $service_call = 'use App\Components\\'.$version .'\\' . $uComp . 'Component; $component = new ' . $uComp . 'Component(); return $component->' . $service;

            if(Request::$method === 'GET') {
                $response->data = eval($service_call . '($_GET);');
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
        $slot_content = $this->handleRequest(Request::$url, Request::$get);

        ob_start();

        $loaded_templates = View::$loaded_templates;
        require base_path . '/layouts/'. Page::$template.'.template.php';
        $template = ob_get_clean();
        $template = str_replace("<slot name='main'></slot>",  $slot_content, $template);
        ob_start();
        require base_path . '/layouts/main.php';
        $page = ob_get_clean();
        $page = str_replace("<slot name='main'></slot>",  $template, $page);

        if (env === 'dev' && Request::$http_accept === 'text/event-stream') {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            $run_files = get_included_files();

            foreach($loaded_templates as $file){
                $run_files[]=$file;
            }

            $hash =[];

            foreach(array_keys($run_files) as $key){
                $exclude = false;
                foreach(Config::$config['app']['hotreload']['exclude'] as $remove){
                    if(str_contains($run_files[$key], $remove)){
                        $exclude = true;
                    }
                }
                if(!$exclude){
                    $hash[$run_files[$key]] = hash_file('md5', $run_files[$key]);
                }
            }
            $msg ="data:".json_encode(['status'=>'okay']). PHP_EOL;
            $hash_file = fopen(base_path.'/tmp/hotreload.hash','w');
            fwrite($hash_file, json_encode($hash));
            fclose($hash_file);
            echo  $msg. PHP_EOL;
            return;
        }
        echo $page;
    }
    /**
     * 
     */
    private function handleFragmentRequest(string $url, array $get):string
    {
        $api = substr($url, strlen(base_path));
        ob_start();
        if (Request::$method === 'GET') {
            eval(' use App\app\components\api\v1\shoe\ShoeComponent; $component = new ShoeComponent(); echo $component->index();');
        }
        return ob_get_clean();
    }

    /**
     * @param String $url
     * @param array $get
     * @return string
     */
    private function handleRequest(string $url, array $get):string
    {
        $bp = strlen('routes\\');
        //products/catalog/mens
        $routeParts = explode(DIRECTORY_SEPARATOR, Path::$route);
        ob_start();
        $routeFound = false;

        // Split the path into segments
        $segments = explode('/', trim(Request::$url, '/'));

        // Initialize the current directory as the base directory
        $current_dir = base_path.DIRECTORY_SEPARATOR.'routes';

        // Initialize an empty array to store the slug variables
        Request::$slugs = [];

        $last_segment='';
        // Loop through the segments and check if they match any subdirectory or file
        foreach ($segments as $segment) {
            if(is_dir($current_dir.DIRECTORY_SEPARATOR.$segment)) {
                $current_dir .= '/' . $segment;
                $last_segment = $segment;
            }else if(is_file($current_dir.DIRECTORY_SEPARATOR.$segment.'.php')){
                $last_segment = $segment;
            } else {
                Request::$slugs[] = $segment;
            }
        }

        if(file_exists($current_dir.'/'.$last_segment.'.php')){
            $routeFound=true;
            require $current_dir.'/'.$last_segment.'.php';
        }elseif(file_exists($current_dir.'/index.php')){
            $routeFound=true;
            require $current_dir.'/index.php';
        }else{
            Page::setTitle('Page not Found');
            http_response_code(200);
            require base_path . Config::$config['error404'];
        }
        $indexActionFn ='index_action';
        $postActionFn ='post_action';
        $putActionFn ='put_action';
        $deleteActionFn ='delete_action';
        $output = '';
        Path::$hotPath = Path::$routePath . $current_dir;
        // Check if the mens.index.php file exists
        if(Request::$method === 'GET' && function_exists($indexActionFn)){
            $output = $indexActionFn();
        }else if(Request::$method === 'POST' && function_exists($postActionFn)){
            $output = $postActionFn();
        }else if(Request::$method === 'PUT' && function_exists($putActionFn)){
            $output = $putActionFn();
        }else if(Request::$method === 'DELETE' && function_exists($deleteActionFn)){
            $output = $deleteActionFn();
        }else {
            Page::setTitle('Page not Found');
            http_response_code(200);
            $output = require base_path . Config::$config['app']['error404'];
        }
        echo $output;
        return ob_get_clean();
    }
}
