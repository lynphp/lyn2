<?php

namespace lyn\helpers;

class Config
{
    public static array $config = [];
    public static function set(array $_config):void
    {
        self::$config = $_config;
    }
}
