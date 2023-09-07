<?php

namespace lyn;

class Request
{
    public static string $url;

    public static array $slugs = [];
    public static string $lynHeader;
    public static string $type;
    public static string $method;
    public static string $activeRoute;
    public static string $acceptType;
    public static array $routeParts = [];
    public static array $get=[];
    public static function checkEager($_route):string
    {
        return str_starts_with($_route, Path::$hotPath);
    }
    public static function response($content, $code = 200):void
    {
    }
}
