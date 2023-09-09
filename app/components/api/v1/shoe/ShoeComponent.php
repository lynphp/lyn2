<?php

namespace App\app\components\api\v1\shoe;

use App\app\models\Shoe;
use lyn\base\component\SecureComponent;
use lyn\base\View;

/**
 * Lyn Component/SecureComponent usage:
 * As REST API:  
 *  GET:/api/shoes/getShoes
 *  POST:/api/shoes/postShoe
 *  UPDATE:/api/shoes/updateShoe
 *  DELETE:/api/shoes/deleteShoe
 *  PUT:/api/shoes/putShoe
 * As Web Component
 *  GET:/component/shoes/getShoePage
 */

class ShoeComponent extends SecureComponent
{
    /**
     * Define your security rules here. Lyn will execute this secure() method before calling the index method.
     * Before the index is called, Lyn will check if this method return's true.
     * SecureComponent has a limited display time after render, and it will be unmounted after.
     * Display duration is also based on cookie expiry by default, but it can customize per Component/Render
     * Rules is also applied in component in shadow DOM or in SPA (Single Page App) page waiting to be hydrated
     */
    function secure()
    {
        return true;
    }
    /**
     * Component to render products catalog
     * 
     * URL:products/catalog/mens/shoes
     * Path: src/routes/products/catalog/[slug]/index.php
     */
    final public function index(...$props):string
    {
        return View::render('shoe.template', 'shoe.css', $props);
    }
    /**
     * Component to return products info in JSON format
     *
     * URL:products/catalog/mens/shoes
     * Path: src/routes/products/catalog/[slug]/index.php
     */
    final public function getShoe(...$props):string
    {
        return json_encode((new Shoe())->getShoes($props));
    }
    /**
     * Component to return products info in JSON format
     * 
     * URL:products/catalog/mens/shoes
     * Path: src/routes/products/catalog/[slug]/index.php
     */
    function getShoes(...$props)
    {
        return json_encode(Shoe::getShoes($props));
    }

    /**
     * Component to render products catalog
     * 
     * URL:products/catalog/mens/shoes
     * Path: src/routes/products/catalog/[slug]/index.php
     */
    final public function getShoePage(...$props):string
    {
        return View::render('shoe.template', 'shoe.css', $props);
    }
    final public function postShoePage(...$props):string
    {
        return 'form data posted...';
    }
}
