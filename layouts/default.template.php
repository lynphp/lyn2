
<?php
\lyn\Page::addAssetStyle('css/static/main.css');
\lyn\Page::addAssetStyle('css/static/sidenave.css');
\lyn\Page::addCDNStyle('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
?>
<div class="topnav">
    <a class="active" href="/">Home</a>
    <a href="/documents">Documents</a>
    <a href="/contact">Contact</a>
    <a href="/about">About</a>
    <a href="/signout">Sign Out</a>
</div>
<slot name='main'></slot>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#">Clients</a>
    <a href="#">Contact</a>
</div>

<!-- Use any element to open the sidenav -->
<span onclick="openNav()">open</span>

<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->

<?php \lyn\Page::JScriptStart();?>
<script>

/* Set the width of the side navigation to 250px */
function openNav() {
document.getElementById("mySidenav").style.width = "250px";
}

/* Set the width of the side navigation to 0 */
function closeNav() {
document.getElementById("mySidenav").style.width = "0";
}
</script>
<?php \lyn\Page::JScriptEnd();?>