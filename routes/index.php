<?php

use lyn\base\View;
use lyn\Page;

echo View::render('index.template', 'index.css');
?>


<?php Page::JScriptStart() ?>
<script type="text/javascript">
   window.addEventListener('load',log('Hello, Lyn Developer!'));
</script>
<?php Page::JScriptEnd(true) ?>