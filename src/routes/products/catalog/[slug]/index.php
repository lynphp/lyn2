<?php

use lyn\base\View;
use lyn\Page;

/**
 * Route handler to render 
 * products/catalog/* URL
 * 
 * Example URL:products/catalog/mens/shoes
 * Path: src/routes/products/cataglog/[slug]/
 * File: index.php
 */
function index()
{
    [$type, $product] = Request::$slugs;
    Page::$title = $product . '/' . $type;

    return View::render(
        'product.template',
        'product.css',
        ['type' => $type, 'product' => $product]
    );
}
?>

<?php Page::JScriptStart() ?>
<script>
    function load() {
        alert('Hello script!')
    }
    window.addEventListener('load', load);
</script>
<?php Page::JScriptEnd() ?>