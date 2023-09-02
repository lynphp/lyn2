## Lyn in Danish language is Lightning

### Lyn is Lightning Fast PHP Framework

To render templates with dedicated css for scoped styling

```php
echo useComponent('head', 'head.css');
echo useComponent('index.template', 'index.css');
```

```php
<?php

use lyn\base\View;
use lyn\Page;

/**
 * Component to render products catalog
 *
 * URL:products/catalog/mens/shoes
 * Path: src\routes\products\cataglog\[slug]\index.php
 */
function component()
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
```
