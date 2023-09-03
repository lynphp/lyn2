<?php

namespace lyn;

class Request
{
    public static $url;
    public static $route;
    public static $slugs = [];
    public static $lynHeader;
    public static $type;
    public static $method;
    public static $activeRoute;
    public static $acceptType;
    public static $routeParts = [];
    public static $get;
    public static function checkEager($_route)
    {
        $hotPath = Path::$hotPath;
        $result =  str_starts_with($_route, $hotPath);
        return $result;
    }
    public static function response($content, $code = 200)
    {
    }
}
