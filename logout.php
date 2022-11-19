<?php

session_start();

unset($_SESSION['logged_in']);
unset($_SESSION['account_type']);
setcookie(session_name(), "", time() - 360);
session_destroy();
header("refresh:1;url=index.php");
    echo('<div class="text-center" style="margin-top:50px">You are now logged out! You will be redirected shortly.</div>');
?>