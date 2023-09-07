<?php

/**
 * 
 */

use lyn\helpers\Debug;

function lynClassAutoloader($className): void
{
    $classes = array(
        base_path . '\lyn\framework\lyn\\' . $className . '.php',
        base_path . '\lyn\framework\\' . $className . '.php',
        base_path . '\lyn\base\\' . $className . '.php',
        base_path . '\\' . $className . '.php',
    );

    $included = false;
    foreach ($classes as $class) {
        if (file_exists($class)) {
            $included = true;
            require_once $class;
        }
    }
    //$className = substr($className, 4);
    $classes = array(
        base_path . '\components\\' . $className . '.php',
    );
    foreach ($classes as $class) {
        if (file_exists($class)) {
            $included = true;
            require_once $class;
        }
    }
    if (!$included) {
        die('ERROR:E10004 Lyn internal class require error. Class ' . $className . ' not found!');
    }
    //stop();
}
spl_autoload_register('lynClassAutoloader');
//$Debughelpers = new Debug();
//$Debughelpers->declareGlobalsHelpers();
