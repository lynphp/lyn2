
<?php
\lyn\Page::addAssetStyle('css/static/main.css');
?>
<div class="topnav">
    <a class="active" href="/">Home</a>
    <a href="/documents">Documents</a>
    <a href="/contact">Contact</a>
    <a href="/about">About</a>
    <a href="/signout">Sign Out</a>
</div>
<slot name='main'></slot>