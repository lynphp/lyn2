<?php

namespace lyn;

class Server
{
    final public static function get_processor_cores_number():int {
        /*if (PHP_OS_FAMILY === 'Windows') {
            $cores = shell_exec('echo %NUMBER_OF_PROCESSORS%');
        } else {
            $cores = shell_exec('nproc');
        }*/

        return 12;//(int) $cores;
    }
}