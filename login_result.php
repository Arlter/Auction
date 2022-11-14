<?php
//Go on to header.php. Under Login should send request to login_result.php

require_once "connection.php";
session_start();

$email = $_POST["email"];
$pass = $_POST["password"];

//Check if user is in the database
$query = mysqli_query($conn, "SELECT * FROM account WHERE emailAddress = $email and accountPassword = $pass");
$result = mysqli_query($con, $query)

if($rows == 1){ 
    $account = mysqli_fetch_assoc($results);
    header("refresh:5;url=index.php");
    echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>') 
} else {
    echo "Invalid Username or Password"
    //Redirect to where? 
    }
?>