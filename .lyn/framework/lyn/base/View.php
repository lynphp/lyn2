<?php

namespace lyn\base;

use lyn\Page;
use UnderflowException;

class View
{
    /**
     * Lyn Component
     * @param string $file the includable file name, like .php and .html
     * @param mixed[] $args .css and or .js file names and or array of variables to be passed on.
     * @example location description
     * @return string html
     */
    static function render($file = '', ...$args)
    {
        $css = '';
        $cssOutput = '';
        $output = '';
        $js = [];
        $callerFile = debug_backtrace()[0]['file'];
        $callerFileDir = dirname(debug_backtrace()[0]['file']);
        $params = [];
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $params = $arg;
            } else {
                if (str_ends_with($arg, '.css')) {
                    $css = $arg;
                }
            }
        }
        if (file_exists($file) == 0) {
            if (file_exists($callerFileDir . '/' . $file . '.php')) {
                $file = $callerFileDir . '/' . $file . '.php';
            }
        }
        if ($file == $callerFile) {
            die('Error:E10001: Parent file should not load it self.');
        } else {

            extract($params, EXTR_OVERWRITE);
            ob_start();
            include $file;
            $output = ob_get_clean();

            if (strpos($output, "lyn-style") !== false && strlen($css) < 5) {
                die('Error:E10002: CSS file cant be loaded. get ' . $css);
            } else {
                $cssContent = '';

                /**
                 * detect if th css needs to be overwitten through md5 hash
                 */
                $hash = hash_file('md5', $callerFileDir . '/' . $css);
                //remove all the numbers in hash_file output.
                $hash = preg_replace('/[0-9]+/', '', $hash);
                //shorten the hash output.
                $hash = substr($hash, -6);
                //let reconstruct the css file name. index.css will become index-bfgsjb.css
                $cssHashFile = substr($css, 0, -4) . '-' . $hash . '.css';
                /**
                 * search the css in public/scc direactory
                 */
                foreach (glob(public_server_path . '/css' . $cssHashFile . '*') as $filename) {
                    $cssHashFile = $filename;
                }
                //check the index-bfgsjb.css* if exist, if not, let's create it.
                if (!file_exists(public_server_path . '/css/' . $cssHashFile)) {
                    foreach (file($callerFileDir . '/' . $css) as $line) {
                        if (strpos($line, '{') > 1) {
                            $cssContent = $cssContent . ' .' . $hash . ' ' . $line;
                        } else {
                            $cssContent = $cssContent . $line;
                        }
                    }
                    $cacheTime = (string)time();
                    $cssfile = fopen(public_server_path . '/css/' . $cssHashFile, "w");
                    fwrite($cssfile, $cssContent);
                    fclose($cssfile);
                    $cssHashFile = $cssHashFile;
                }
                Page::addStyleString("<link rel='stylesheet' type='text/css' href='" . public_web_path . "/css/" . $cssHashFile . "?v=123234' />");
                $output = str_replace("lyn-style",  $hash, $output);
            }
        }
        return $output;
    }
}
