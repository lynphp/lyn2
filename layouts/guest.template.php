
<?php
\lyn\Page::addAssetStyle('css/static/main-guest.css');
?>
<div class="topnav">
    <a class="active" href="/">Home</a>
    <a href="/contact">Contact</a>
    <a href="/about">About</a>
    <a href="/signup">Sign Up</a>
    <a href="/signin">Sign In</a>
</div>
Welcome!
<slot name='main'></slot>