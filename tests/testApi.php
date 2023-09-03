<?php
function testGetApi()
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET['type'] = 'men';
    $_SERVER['CONTENT_TYPE'] = 'application/json';
    $_SERVER['REDIRECT_URI'] = '/lyn/api/shoe/getShoe';
    require '../testBase.php';
}
testGetApi();
