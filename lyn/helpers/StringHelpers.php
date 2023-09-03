<?php

namespace lyn\helpers;

class StringHelpers
{

    public static function ucfirstSome($match)
    {
        $exclude = array('and', 'of', 'the');
        if (in_array(strtolower($match[0]), $exclude)) {
            return $match[0];
        }
        return ucfirst($match[0]);
    }
    public static function toCamelCase($string)
    {

        $major = preg_replace_callback("/[a-zA-Z]+/", 'self::ucfirstSome', $string);

        return ucfirst($major);
    }
}
