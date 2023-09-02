<?php
function testRoute()
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REDIRECT_URL'] = '/lyn/products/catalog/men/shoe';
    require '../test.php';
}

function testRouteFragment()
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REDIRECT_URL'] = '/lyn/http/component/shoe?class=shoe';
    $_SERVER['HTTP_LYN_REQUEST_HEADER'] = 'application/fragment';
    require '../test.php';
}
//testRouteFragment();
testRoute();
