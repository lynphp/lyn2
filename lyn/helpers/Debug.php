<?php

namespace lyn\helpers;

use Exception;

class Debug
{

    function declareGlobalsHelpers()
    {
        function stop($msg = "Dont't panic its juts Lyn Debug", $level = 1)
        {
            throw new Exception($msg, $level);
        }
    }
}
