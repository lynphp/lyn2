<?php

/**
 * 
 */

use lyn\helpers\Debug;

function lynClassAutoloader($className)
{
    $classes = array(
        base_path . '\lyn/framework\lyn\\' . $className . '.php',
        base_path . '\lyn/framework\\' . $className . '.php',
    );

    $included = false;
    foreach ($classes as $classe) {
        if (file_exists($classe)) {
            $included = true;
            require_once $classe;
        }
    }
    //$className = substr($className, 4);
    $classes = array(
        base_path . '\components\\' . $className . '.php',
    );
    foreach ($classes as $classe) {
        if (file_exists($classe)) {
            $included = true;
            require_once $classe;
        }
    }
    if ($included == false) {
        die('ERROR:E10004 Lyn internal class require error. Class ' . $className . ' not found!');
    }
    //stop();
}
//spl_autoload_register('lynClassAutoloader');
//$Debughelpers = new Debug();
//$Debughelpers->declareGlobalsHelpers();
