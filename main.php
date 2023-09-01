<?php

use lyn\helpers\Config;
use lyn\base\View;
use lyn\Page;

Page::addScript('/lyn/public/js/lyn.js');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= Page::getMetaCharset(); ?>
    <?= Page::getMetaViewport(); ?>
    <?= Page::getStyles(); ?>
    <title><?= Page::$title; ?></title>
    <?= Page::getScripts(); ?>
</head>

<body>
    <slot name='main'></slot>
</body>

</html>