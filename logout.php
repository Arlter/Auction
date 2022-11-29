<?php
include_once("header.php");
unset($_SESSION['accountType']);
unset($_SESSION['accountID']);
unset($_SESSION['emailAddress']);
unset($_SESSION["accountUsername"]);
setcookie(session_name(), "", time() - 360);
//session_destroy();
$_SESSION['logged_in'] = 0;
header("Location: browse.php");
?>