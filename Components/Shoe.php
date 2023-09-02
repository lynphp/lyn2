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
    function secure()
    {
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
