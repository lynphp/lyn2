<?php

\lyn\Page::addAssetStyle('css/static/signin.css');
?>

<form action="" method="post">
    <label for="email">Email:
    </label>
        <input type="text" name="email" autocomplete/>
    <label for="username">Username:
    </label>
        <input type="text" name="username" is="username" autocomplete/>
    <label for="new-password">Password:
    </label>
        <input type="password" name="new-password" autocomplete />
    <label for="reenter-password">Reenter Password:
    </label>
        <input type="password" name="reenter-password" autocomplete />
    <button type="submit">Sign-In</button>
</form>