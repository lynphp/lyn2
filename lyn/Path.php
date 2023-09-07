<?php

namespace lyn;

class Path
{
    public static string $routePath;
    public static string $route;
    public static string $apiComponentPath;
    /**
     * Path for eager loading
     * value is set during routing automically for you.
     */
    public static string $hotPath;
}
