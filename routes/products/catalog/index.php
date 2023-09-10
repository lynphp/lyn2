<?php

use lyn\base\View;
use lyn\Page;
use lyn\Request;

/**
 * Route handler to render 
 * products/catalog/* URL
 * 
 * Example URL:products/catalog/mens/shoes
 * Path: src/routes/products/cataglog/[slug]/
 * File: mens.index.php
 */
function get()
{
    [$type, $product] = Request::$slugs;
    $eager = Request::checkEager(__DIR__);
    //type=men
    //product=shoes
    Page::$title = $product . '/' . $type;

    return View::render(
        'product.template',
        'product.css',
        $eager,
        ['type' => $type, 'product' => $product]
    );
}
?>

<?php Page::JScriptStart() ?>
<script>
    function load() {
        log('Hello script!')
    }
    window.addEventListener('load', load);
</script>
<?php Page::JScriptEnd() ?>