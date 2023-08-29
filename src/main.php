<?php

use lyn\helpers\Config;
use lyn\base\View;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Config::$config['name']; ?></title>
    <link rel="stylesheet" href="/lyn/public/css/main.css" class="css">
</head>

<body>
    <?= View::render('main.template', 'index.css') ?>
</body>

</html>