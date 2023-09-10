<?php

namespace lyn;

class Request
{
    public static string $url;

    public static array $slugs = [];
    public static string $lyn_header;
    public static string $type;
    public static string $method;
    public static string $activeRoute;
    public static string $http_accept;
    public static array $segments = [];
    public static array $get=[];
    public static function checkEager($_route):string
    {
        return str_starts_with($_route, Path::$hotPath);
    }
    public static function response($content, $code = 200):void
    {
    }
}
