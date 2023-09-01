<?php

namespace Shoe;

use lyn\base\View;

class Shoe
{

    /**
     * Component to render products catalog
     * 
     * URL:products/catalog/mens/shoes
     * Path: src/routes/products/cataglog/[slug]/index.php
     */
    function index($props = [])
    {
        return View::render('shoe.template', 'shoe.css');
    }

    function post($props = [])
    {
    }
}
