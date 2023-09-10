<?php
\lyn\Page::$template='welcome';
function index_action(){
    $signin = \lyn\base\View::render('signin.form');
    return $signin;
}

