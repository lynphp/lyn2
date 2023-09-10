<?php

namespace lyn\base;

use lyn\Page;

class View
{
    public static array $loaded_templates=[];
    public static function render_error(string $error, int $responseCode = 404):string
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
    private static function get_component_attributes(array $attributes):array
    {
        //$attributes = 'class="app.shoe" data-*="product=\'shoe\'"';
        $pattern = '/(\w+)=(["\'])(.*?)\2/';
        preg_match_all($pattern, $attributes, $matches, PREG_SET_ORDER);
        $keyValue = $matches[0][0];
        parse_str($keyValue, $var);
        return $matches;
    }
    private static function get_attribute_by_key(array $attr):array
    {
        parse_str($attr, $vars);
        return $vars;
    }
    private static function get_web_component_element_tags(string $html):array
    {
        $pattern = '/<x-lyn-component\s+([^>]+)>\s+(.*?)<\/x-lyn-component>/s';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        return $matches;
    }
    /**
     * Lyn Component
     * @param string $file the includable file name, like .php and .html
     * @param array $args .css and or .js file names and or array of variables to be passed on.
     * @example location description
     * @return string html
     */
    public static function render(string $file = '', ...$args):string
    {
        $eager = false;
        $css = '';
        $cssOutput = '';
        $output = '';
        $js = [];
        $callerFile = debug_backtrace()[0]['file'];
        $caller_file_dir = dirname(debug_backtrace()[0]['file']);
        $params = [];
        foreach ($args as $arg) {
            if (is_array($arg)) {
                $params = $arg;
            } elseif (is_bool($arg)) {
                $eager = $arg;
            } else if (str_ends_with($arg, '.css')) {
                $css = $arg;
            }
        }
        if (file_exists($file) || file_exists($caller_file_dir . '/' . $file . '.php')) {
            $file = $caller_file_dir . '/' . $file . '.php';
        }
        if ($file === $callerFile) {
            return self::render_error('Error:E10001: Parent file should not load it self.', 500);
        }

        extract($params, EXTR_OVERWRITE);
        ob_start();

        include $file;
        self::$loaded_templates[]=$file;
        $arr = get_defined_vars();
        $output = ob_get_clean();
        /**
         * populate our template if eager is true
         */
        if ($eager) {
            foreach ($params as $key => $value) {
                $output = str_replace('{{' . $key . '}}', "'" . $value . "'", $output);
            }
            foreach (self::get_web_component_element_tags($output) as $comp) {
                foreach (self::get_component_attributes($comp[1]) as $attr) {
                    extract(self::get_attribute_by_key($attr[0]), EXTR_OVERWRITE);
                    $defVars = get_defined_vars();
                    $varValue = ${$attr[1]};
                    $result = eval('use App\app\components\api\v1\shoe\ShoeComponent; $component = new ShoeComponent(); return $component->index(' . $varValue . ');');
                    $output = str_replace(array($comp[2], '<x-lyn-component '), array($result, '<x-lyn-component render="ssr" '), $output);
                }
            }
        }
        if (str_contains($output, "lyn-style") && strlen($css) < 5) {
            return self::render_error('Error:E10002: CSS file can\'t be loaded. file ' . $css, 404);
        }

        if ($css!=='') {
            $css_content = '';
            /**
             * detect if th css needs to be overwritten through md5 hash
             */

            self::$loaded_templates[]=$caller_file_dir . '/' . $css;

            $hash = hash_file('md5', $caller_file_dir . '/' . $css);
            //remove all the numbers in hash_file output.
            $hash = preg_replace('/[0-9]+/', '', $hash);
            //shorten the hash output.
            $hash = substr($hash, -6);
            //let reconstruct the css file name. index.css will become index-bfgsjb.css
            $css_hash_file = substr($css, 0, -4) . '-' . $hash . '.css';
            /**
             * search the css in public/scc directory
             */
            foreach (glob(public_server_path . '/css' . $css_hash_file . '*') as $file_name) {
                $css_hash_file = $file_name;
            }
            //check the index-bfgsjb.css* if existed, if not, let's create it.
            if (!file_exists(public_server_path . '/css/' . $css_hash_file)) {
                foreach (file($caller_file_dir . '/' . $css) as $line) {
                    if (strpos($line, '{') > 1) {
                        $css_content .= ' .' . $hash . ' ' . $line;
                    } else {
                        $css_content .= $line;
                    }
                }
                $css_file = fopen(public_server_path . '/css/' . $css_hash_file, 'wb');
                fwrite($css_file, $css_content);
                fclose($css_file);
            }
            Page::addStyleString("<link rel='stylesheet' type='text/css' href='". url_base_path.assets_path . "/css/" . $css_hash_file . "?v=123234' />");
            $output = str_replace("lyn-style",  $hash, $output);
        }
        return $output;
    }
}

