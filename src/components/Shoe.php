<?php

namespace components\Shoe;

use lyn\base\View;
use lyn\Page;
use Request;

/**
 * Component to render products catalog
 * 
 * URL:products/catalog/mens/shoes
 * Path: src/routes/products/cataglog/[slug]/index.php
 */
$Shoe = function ($props = []) {
    [$type, $product] = Request::$slugs;
    //type=men
    //product=shoes
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