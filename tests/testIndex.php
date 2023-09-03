<?php
function testRoute()
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REDIRECT_URI'] = '/lyn/products/catalog/men/shoe';
    require '../testBase.php';
}

function testRouteFragment()
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REDIRECT_URI'] = '/lyn/http/component/shoe?class=shoe';
    $_SERVER['HTTP_LYN_REQUEST_HEADER'] = 'application/fragment';
    require '../testBase.php';
}
//testRouteFragment();
testRoute();
