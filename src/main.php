<?php

use lyn\helpers\Config;
use lyn\base\View;
use lyn\Page;

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
    <div>Hello</div>
    <slot name='main'></slot>
</body>

</html>