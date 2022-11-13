<?php
// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

// assign post to 
session_start();
$_SESSION['logged_in'] = True;
$_SESSION['username'] = "acc1"; 
$_SESSION['account_type'] = "seller";
$_SESSION['accountID'] = 10000000; // get from post form 
$_SESSION['emailAddress'] = "1419121502@qq.com";



echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to index after 5 seconds
header("refresh:5;url=index.php");

?>