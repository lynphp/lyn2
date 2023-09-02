## Lyn in Danish language is Lightning

### PHP Framework for Web and Enterprise Application

To render templates with dedicated css for scoped styling

```php
echo useComponent('head', 'head.css');
echo useComponent('index.template', 'index.css');
```

```php
## Lyn has Auto-Routing feature
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
 * File: index.php
 */
function index()
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
        console.log('Hello script!')
    }
    window.addEventListener('load', load);
</script>
<?php Page::JScriptEnd() ?>
```

## Lyn is Secured by Default

```php
<?php
namespace App\Components;

use lyn\base\Component;
use lyn\base\SecureComponent;
use lyn\base\View;

class Shoe extends SecureComponent
{
    function __construct()
    {
        parent::__construct();
    }
    /**
     * Define your security rules here. Lyn will execute this secure() method before calling the index method.
     * Before the index is called, Lyn will check if this method return's true.
     * SecureComponent has a limited display time after render and it will be unmounted after.
     * Display duration is also based on cookie expiry by default, but it can customized per Component/Render
     * Rules is also applied in component in shadow DOM or in SPA (Single Page App) page waiting to be hyrated
     */
    function secure()
    {
        return true;
    }
    /**
     * Component to render products catalog
     *
     * URL:products/catalog/mens/shoes
     * Path: src/routes/products/cataglog/[slug]/index.php
     */
    function index(...$props)
    {
        return View::render('shoe.template', 'shoe.css', $props);
    }

    function post($props = [])
    {
    }
}
```
