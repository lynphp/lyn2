<?php

use lyn\base\View;
use lyn\Page;

echo View::render('index.template', 'index.css');
?>


<?php Page::JScriptStart() ?>
<script>
    alert('hello')
</script>
<?php Page::JScriptEnd() ?>