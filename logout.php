<?php
include_once("header.php");
unset($_SESSION['accountType']);
unset($_SESSION['accountID']);
unset($_SESSION['emailAddress']);
setcookie(session_name(), "", time() - 360);
//session_destroy();
$_SESSION['logged_in'] = 0;
header("refresh:1;url=index.php");
    echo('<div class="text-center" style="margin-top:50px">You are now logged out! You will be redirected shortly.</div>');
?>