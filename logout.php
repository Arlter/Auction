<?php
include_once("header.php");
unset($_SESSION['logged_in']);
unset($_SESSION['accountType']);
setcookie(session_name(), "", time() - 360);
//session_destroy();
$_SESSION['logged_in'] = 0;
header("refresh:1;url=browse.php");
    echo('<div class="text-center" style="margin-top:50px">You are now logged out! You will be redirected shortly.</div>');
?>