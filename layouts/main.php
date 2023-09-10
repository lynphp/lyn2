<?php
use lyn\Page;
use lyn\Server;
Page::addScriptSrc('const urlBasePath="' . url_base_path . '/";');
Page::addScriptSrc('const php_start_time=' . time_start . ';');
Page::addScriptSrc('const php_num_cores =' . Server::get_processor_cores_number() . ';');
Page::addScript('js/lyn.js');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= Page::getMetaCharset() ?>
    <meta http-equiv="content-type" content="private">
    <?= Page::getMetaViewport() ?>
    <?= Page::getStyles() ?>
    <title><?= Page::$title ?></title>
    <?= Page::getScripts() ?>
</head>
<body>
    <slot name='main'></slot>
</body>
<script>
    <?php echo "const php_end_time=" . microtime(true) . ";" ?>
</script>
</html>