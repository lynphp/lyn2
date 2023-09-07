<?php


namespace lyn;

use Head;

class Page
{
    public static string $title = '';
    public static array $metas = [];
    public static string $metaViewPort = '<meta name="viewport" content="width=device-width, initial-scale=1">';
    public static string $metaCharSet = '<meta charset="utf-8">';
    public static array $links = [];
    public static array $scripts = [];
    public static array $scriptsEnd = [];
    public static array $keywords = [];
    public static array $styles = [];
    public static function setTitle($newTitle):void
    {
        self::$title = $newTitle;
    }
    public static function getTitle():string
    {
        return self::$title;
    }
    public static function addScript($src, $type = 'text/javascript'):void
    {
        self::$scripts[] = "<script tyle='$type' src='".url_base_path.assets_path."/$src'></script>";
    }
    public static function addScriptSrc($src, $type = 'text/javascript'):void
    {
        self::$scripts[] = "<script tyle='$type'>$src</script>";
    }
    public static function JScriptStart():void
    {
        ob_start();
    }
    public static function JScriptEnd($renderEnd=false):void
    {
        $code = ob_get_clean();
        if($renderEnd){
            self::$scriptsEnd[] = $code;
        }else{
            self::$scripts[] = $code;
        }
    }
    public static function addScriptAsync($src, $type = 'text/javascript'):void
    {
        self::$scripts[] = "<script async type='$type' src='".url_base_path."/$src'></script>";
    }
    public static function addCDNScriptAsync($src, $type = 'text/javascript'):void
    {
        self::$scripts[] = "<script async type='$type' src='$src'></script>";
    }

    /**
     * Register's the relative URL path of the local stylesheet file.
     * @example <?php Page::addAssetStyle("static/css/main.css", "stylesheet"); ?>
     * @param string $href The relative URL path of the stylesheet file. It should NOT include "/" at the beginning.
     * @param string $rel
     * @param string $type
     * @return void
     */
    public static function addAssetStyle(string $href, string $rel = 'stylesheet', string $type = ''):void
    {
        if ($type === '') {
            self::$styles[] = "<link rel='$rel' href='".url_base_path.assets_path."/$href'>";
        } else {
            self::$styles[] = "<link rel='$rel' href='".url_base_path.assets_path."/$href' type='$type'>";
        }
    }

    /**
     * Register's external stylesheets or CDN (Content Delivery Network).
     * @param string $href The relative URL path of the stylesheet file. It should NOT include "/" at the beginning.
     * @param string $rel
     * @param string $type
     * @return void
     */
    public static function addCDNStyle(string $href, string $rel = 'stylesheet',string $type = ''):void
    {
        if ($type === '') {
            self::$styles[] = "<link rel='$rel' href='$href'>";
        } else {
            self::$styles[] = "<link rel='$rel' href='$href' type='$type'>";
        }
    }

    /**
     * Register's the complete link element
     * @param string $linkRel
     * @return void
     */
    public static function addStyleString(string $linkRel):void
    {
        self::$styles[] = $linkRel;
    }

    public static function addMetaProperty($name, $description):void
    {
        self::$metas[] = "<meta property='$name' content='$description'>";
    }

    public static function setMetaCharset($charset = 'utf-8'):void
    {
        self::$metaCharSet = "<meta charset='$charset'>";
    }
    public static function setMetaViewPort($content):void
    {
        self::$metaViewPort = "<meta name='viewport' content='$content'>";
    }
    public static function getMetaCharset():string
    {
        return self::$metaCharSet;
    }
    public static function getLinks():string
    {
        return implode('', self::$links);
    }
    public static function getScripts():string
    {

        return implode('', self::$scripts);
    }
    public static function getEndScripts():string
    {

        return implode('', self::$scriptsEnd);
    }
    public static function getStyles():string
    {
        return implode('', self::$styles);
    }
    public static function getMetaViewport():string
    {
        return self::$metaViewPort;
    }
}
