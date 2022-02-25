<!DOCTYPE html>
<html>
    <head>
        <title>ログアウト</title>
        <meta carset="utf-8">
    </head>
    <body>
        <p>ログアウトしました。</p>
        <a href="login.html">ログインページ</a>
<?php

session_start();

$_SESSION = array();
session_destroy();

?>
    </body>
</html>