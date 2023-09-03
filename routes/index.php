<?php

use lyn\base\View;
use lyn\Page;

echo View::render('index.template', 'index.css');
?>


<?php Page::JScriptStart() ?>
<script>
    log('hello world')
</script>
<?php Page::JScriptEnd() ?>