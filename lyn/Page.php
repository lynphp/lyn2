<?php


namespace lyn;

use Head;

class Page
{
    static string $title = '';
    static array $metas = [];
    static string $metaViewPort = '<meta name="viewport" content="width=device-width, initial-scale=1">';
    static string $metaChartset = '<meta charset="utf-8">';
    static array $links = [];
    static array $scripts = [];
    static array $scriptsEnd = [];
    static array $keywords = [];
    static array $styles = [];
    static function setTitle($newTitle)
    {
        self::$title = $newTitle;
    }
    static function getTitle()
    {
        return self::$title;
    }
    static function addScript($src, $type = 'text/javascript')
    {
        array_push(self::$scripts, "<script tyle='$type' src='$src'></script>");
    }
    static function addScriptSrc($src, $type = 'text/javascript')
    {
        array_push(self::$scripts, "<script tyle='$type'>$src</script>");
    }
    static function JScriptStart()
    {
        ob_start();;
    }
    static function JScriptEnd($renderEnd=false)
    {
        $code = ob_get_clean();
        if($renderEnd){
            array_push(self::$scriptsEnd, $code);
        }else{
            array_push(self::$scripts, $code);
        }
    }
    static function addScriptAsync($src, $type = 'text/javascript')
    {
        array_push(self::$scripts, "<script async tyle='$type' src='$src'></script>");
    }
    static function addStyle($href, $rel = 'stylesheet', $type = '')
    {
        if ($type === '') {
            array_push(self::$styles, "<link rel='$rel' href='$href'>");
        } else {
            array_push(self::$styles, "<link rel='$rel' href='$href' type='$type'>");
        }
    }
    static function addStyleString($linkRel)
    {
        array_push(self::$styles, $linkRel);
    }
    static function addMetaName($name, $description)
    {
        array_push(self::$metas, "<meta name='$name' content='$description'>");
    }
    static function addMetaProperty($name, $description)
    {
        array_push(self::$metas, "<meta property='$name' content='$description'>");
    }

    static function setMetaCharset($charset = 'utf-8')
    {
        self::$metaChartset = "<meta charset='$charset'>";
    }
    static function setMetaViewPort($content)
    {
        self::$metaViewPort = "<meta name='viewport' content='$content'>";
    }
    static function getMetaCharset()
    {
        return self::$metaChartset;
    }
    static function getLinks()
    {
        $linkElements = '';
        foreach (self::$links as $link) {
            $linkElements = $linkElements .
                $link;
        }
        return $linkElements;
    }
    static function getScripts()
    {

        $scriptElements = '';
        foreach (self::$scripts as $link) {
            $scriptElements = $scriptElements .
                $link;
        }
        return $scriptElements;
    }
    static function getEndScripts()
    {

        $scriptElements = '';
        foreach (self::$scriptsEnd as $link) {
            $scriptElements = $scriptElements .
                $link;
        }
        return $scriptElements;
    }
    static function getStyles()
    {

        $styleElements = '';
        foreach (self::$styles as $style) {
            $styleElements = $styleElements .
                $style;
        }
        return $styleElements;
    }
    static function getMetaViewport()
    {
        return self::$metaViewPort;
    }
}
