<?php
// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.
// For now, I will just set session variables and redirect.
// assign post to 
//Go on to header.php. Under Login should send request to login_result.php
require_once "connection.php";
session_start();
$email = $_POST["email"];
    //echo "123123";
$pass = $_POST["password"];

$rows = mysqli_query($conn, "SELECT * FROM Account WHERE emailAddress = '$email' and accountPassword = '$pass' ")-> fetch_array(MYSQLI_NUM);
if(isset($rows)){
        echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
        // Redirect to index after 5 seconds
        header("refresh:3;url=index.php");
        } else {
        echo "asjfad";
        header("refresh:3;url=index.php");
        }

?>