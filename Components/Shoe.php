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
