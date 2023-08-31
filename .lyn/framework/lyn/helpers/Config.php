<?php

namespace lyn\helpers;

class Config
{
    public static $config = null;
    public static function set($_config)
    {
        self::$config = $_config;
    }
}
