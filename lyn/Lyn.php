<?php

namespace lyn;

use ErrorException;
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
        Request::$lynHeader = $_SERVER['HTTP_LYN_REQUEST_HEADER'] ?? '';
        Request::$acceptType = $_SERVER['HTTP_ACCEPT'] ?? '';
        Request::$method = $_SERVER['REQUEST_METHOD'] ?? '';
        /**
         * Let's make sure we can read and handle the url properly
         */
        $rawUrl = explode('?',$_SERVER['REQUEST_URI'])[0]?? '';
        Request::$url = filter_var($rawUrl, FILTER_SANITIZE_URL);
        Path::$route = str_replace("/",DIRECTORY_SEPARATOR ,ltrim(Request::$url, url_base_path));
        Request::$routeParts = explode(DIRECTORY_SEPARATOR, Path::$route);

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
        if (Request::$acceptType === 'application/fragment') {
            Request::$type = 'fragment';
            $slotContent = $this->handleFragmentRequest(Request::$url, Request::$get);
            http_response_code(200);
            echo $slotContent;
            return;
        }
        //API or WebService/MicroServices handlers
        $API_TYPE=0;
        $API_VERSION=1;
        $API_COMPONENT=2;
        $API_SERVICE=3;
        //example URI: api/v1/component/doc
        //example URI: api/v1/component/service_name
        if (Request::$routeParts[$API_TYPE] === 'api') {
            $response = new JSONResponse();
            $response->requestTime = '';

            if(count(Request::$routeParts) < 3){
                http_response_code(404);
                $response->data='404 Service does not exist for: ' . Path::$route;
                echo  $response->toJSONString();
                return;
            }

            $comp = Request::$routeParts[$API_COMPONENT];
            $version = Request::$routeParts[$API_VERSION];
            $service = Request::$routeParts[$API_SERVICE];
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
            $slotContent = $this->handleRequest(Request::$url, Request::$get);

            ob_start();
            require base_path . '/layouts/'. Page::$template.'.template.php';
            $template = ob_get_clean();
            $template = str_replace("<slot name='main'></slot>",  $slotContent, $template);
            ob_start();
            require base_path . '/layouts/main.php';
            $page = ob_get_clean();
            $page = str_replace("<slot name='main'></slot>",  $template, $page);

            if (env === 'dev' && Request::$acceptType === 'text/event-stream') {
                header('Content-Type: text/event-stream');
                header('Cache-Control: no-cache');
                if(isset(Request::$slugs[0]) && Request::$slugs[0]==='check'){
                    $msg ="data:".$slotContent. PHP_EOL;
                    echo  $msg. PHP_EOL;
                    ob_flush();
                    return;
                }

                $runfiles = get_included_files();
                $hash =[];

                foreach(array_keys($runfiles) as $key){
                    $exclude = false;
                    foreach(Config::$config['app']['hotreload']['exclude'] as $remove){
                        if(str_contains($runfiles[$key], $remove)){
                            $exclude = true;
                        }
                    }
                    if(!$exclude){
                        $hash[$runfiles[$key]] = hash_file('md5', $runfiles[$key]);
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
        Request::$slugs = array();
        $includePath='';
        $phpFile = 'index';
        // Loop through the segments and check if they match any subdirectory or file
        foreach ($segments as $segment) {
            // Get the full path of the current segment
            $full_path = $current_dir.'/'.$segment;
            if($segment===''){
                $segment='index';
            }
            $full_path_php = $current_dir .'/'.$segment.'/index.php';
            $full_segment_php = $current_dir .'/'.$segment.'.php';

            // Check if the full path is a directory and if it exists
            if ($includePath==='' && is_dir($full_path) && file_exists($full_path_php)) {
                $includePath = $full_path_php;
                $routeFound=true;
            } else if ($includePath ==='' && file_exists($full_segment_php)) {
                $includePath = $full_segment_php;
                $routeFound = true;
            } else if ($includePath==='') {
                $current_dir .= '/'.$segment;
            }
            // Otherwise, add the segment to the slug array
            else {
                Request::$slugs[] = $segment;
            }
        }
        echo require $includePath;

        if (env === 'dev' && Request::$acceptType === 'text/event-stream') {

            return ob_get_clean();
        }

        $output='';
        // If no matching file was found, check if there is an index.php file in the current directory
        if (!isset($full_path) || !is_file($full_path)) {
            // Get the path of the index.php file
            $index_path = file_exists($current_dir . DIRECTORY_SEPARATOR.$current_dir.'-index.php') ? $current_dir . DIRECTORY_SEPARATOR . $current_dir.'-index.php': $current_dir . DIRECTORY_SEPARATOR .'index.php';
            Path::$hotPath = Path::$routePath . $current_dir;
            // Check if the index.php file exists
            if (is_file($index_path) && file_exists($index_path)) {
                $routeFound = true;
                // Include the index.php file and pass the slug array as a variable
                include_once $index_path;
                if(Request::$method==='GET' && function_exists('get')){
                    $output= get();
                }else if(Request::$method==='POST' && function_exists('post')){
                    $output=post();
                }else if(Request::$method==='PUT' && function_exists('put')){
                    $output=put();
                }else if(Request::$method==='DELETE' && function_exists('delete')){
                    $output=delete();
                }else{
                    if(ob_get_contents() !==''){
                        $routeFound = true;
                    }else{
                        //we did not detect an echo within the route
                        $routeFound = false;
                    }
                }
            }
        }
        if ($routeFound === false) {
            if (file_exists(Path::$routePath . Path::$route)) {
                //load the rout template;
                if (file_exists(Path::$routePath . Path::$route . DIRECTORY_SEPARATOR . 'index.php')) {
                    require Path::$routePath . Path::$route . DIRECTORY_SEPARATOR . 'index.php';
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
        echo $output;
        return ob_get_clean();
    }
}
