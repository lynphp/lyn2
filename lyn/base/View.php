<?php

namespace lyn\base;

use lyn\Page;

class View
{
    static function renderError($error, $responseCode = 404)
    {
        http_response_code($responseCode);
        return $error;
    }

    /* 
    0: "class="app.shoe""
    1: "class"
    2: """
    3: "app.shoe"
    */
    private static function getComponentAttribute($attributes)
    {
        $attributes = 'class="app.shoe" data-*="product=\'shoe\'"';
        $pattern = '/(\w+)=(["\'])(.*?)\2/';
        preg_match_all($pattern, $attributes, $matches, PREG_SET_ORDER);
        $keyValue = $matches[0][0];
        parse_str($keyValue, $var);
        return $matches;
    }
    private static function getAttributeKeyValue($attr)
    {
        parse_str($attr, $var);
        return $var;
    }
    private static function getComponentTags($html)
    {

        $pattern = '/<x-lyn-component\s+([^>]+)>\s+(.*?)<\/x-lyn-component>/s';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        return $matches;
    }
    /**
     * Lyn Component
     * @param string $file the includable file name, like .php and .html
     * @param mixed[] $args .css and or .js file names and or array of variables to be passed on.
     * @example location description
     * @return string html
     */
    static function render($file = '', ...$args)
    {
        $eager = false;
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
            } elseif (is_bool($arg)) {
                $eager = $arg;
            } else {
                if (str_ends_with($arg, '.css')) {
                    $css = $arg;
                }
            }
        }
        if (file_exists($file) == 0) {
            if (file_exists($callerFileDir . '\\' . $file . '.php')) {
                $file = $callerFileDir . '\\' . $file . '.php';
            }
        }
        if ($file == $callerFile) {
            return self::renderError('Error:E10001: Parent file should not load it self.', 500);
        } else {

            extract($params, EXTR_OVERWRITE);
            ob_start();
            include $file;
            $arr = get_defined_vars();
            $output = ob_get_clean();
            /**
             * populate our template if eager is true
             */
            if ($eager) {
                //var_dump($arr);
                foreach ($params as $key => $value) {
                    //echo $key . '-' . $value;
                    $output = str_replace('{{' . $key . '}}', "'" . $value . "'", $output);
                }
                foreach (self::getComponentTags($output) as $comp) {
                    foreach (self::getComponentAttribute($comp[1]) as $attr) {
                        extract(self::getAttributeKeyValue($attr[0]), EXTR_OVERWRITE);
                        $defVars = get_defined_vars();
                        $varValue = ${$attr[1]};
                        $result = eval('use App\Components\ShoeComponent; $component = new ShoeComponent(); return $component->index(' . $varValue . ');');
                        $output = str_replace($comp[2],  $result, $output);
                        $output = str_replace('<x-lyn-component ',  '<x-lyn-component render="ssr" ', $output);
                    }
                }
            }
            if (strpos($output, "lyn-style") !== false && strlen($css) < 5) {
                return self::renderError('Error:E10002: CSS file can\'t be loaded. file ' . $css, 404);
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
                foreach (glob(public_server_path . '\css' . $cssHashFile . '*') as $filename) {
                    $cssHashFile = $filename;
                }
                //check the index-bfgsjb.css* if exist, if not, let's create it.
                if (!file_exists(public_server_path . '\css\\' . $cssHashFile)) {
                    foreach (file($callerFileDir . '\\' . $css) as $line) {
                        if (strpos($line, '{') > 1) {
                            $cssContent = $cssContent . ' .' . $hash . ' ' . $line;
                        } else {
                            $cssContent = $cssContent . $line;
                        }
                    }

                    $cssfile = fopen(public_server_path . '\css\\' . $cssHashFile, "w");
                    fwrite($cssfile, $cssContent);
                    fclose($cssfile);
                    $cssHashFile = $cssHashFile;
                }
                Page::addStyleString("<link rel='stylesheet' type='text/css' href='" . public_web_path . "\css\\" . $cssHashFile . "?v=123234' />");
                $output = str_replace("lyn-style",  $hash, $output);
            }
        }
        return $output;
    }
}
