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
    final public  function init($conf):Lyn
    {
        global $config;
        $appConfig = [];
        if (file_exists($conf)) {
            include_once $conf;
        } else {
            throw new ErrorException("Error:E10005: Config file {$conf} does not exist");
        }
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
        Path::$routePath = base_path . '/routes/';
        Path::$apiComponentPath = dirname($_SERVER["SCRIPT_FILENAME"]) . '/src/components/';
        return $this;
    }
    /**
     *
     * @throws ErrorException
     */
    function start()
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
        /**
         * 
         */
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
        require base_path . DIRECTORY_SEPARATOR.'main.php';
        $page = ob_get_clean();
        $page = str_replace("<slot name='main'></slot>",  $slotContent, $page);
        echo $page;
    }
    /**
     * 
     */
    private function handleFragmentRequest($url, $get):string
    {
        $api = substr($url, strlen(base_path));
        ob_start();
        if (Request::$method === 'GET') {
            eval(' use App\Components\ShoeComponent; $component = new ShoeComponent(); echo $component->index();');
        }
        return ob_get_clean();
    }

    /**
     * @param String $url
     * @param array $get
     * @return string
     */
    private function handleURL(string $url, array $get):string
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
        // Loop through the segments and check if they match any subdirectory or file
        foreach ($segments as $segment) {
            // Get the full path of the current segment
            $full_path = $current_dir . DIRECTORY_SEPARATOR . $segment;

            // Check if the full path is a directory and if it exists
            if (is_dir($full_path) && file_exists($full_path)) {
                // Update the current directory to the full path
                $current_dir = $full_path;
            }
            // Check if the full path is a file and if it exists
            elseif (is_file($full_path) && file_exists($full_path)) {
                // Do something with the matching file, for example, include it
                echo $full_path;
                break;
            }
            // Otherwise, add the segment to the slug array
            else {
                Request::$slugs[] = $segment;
            }
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
                    require Path::$routePath . Request::$route . DIRECTORY_SEPARATOR . 'index.php';
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
