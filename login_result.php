<?php
//Go on to header.php. Under Login should send request to login_result.php

require_once "connection.php";
session_start();

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $pass = $_POST["password"];
} 

$hashquery = "SELECT accountPassword FROM Account WHERE accountUsername = '$username'";
$hashresult = mysqli_query($conn, $hashquery);

$hash = mysqli_fetch_array($hashresult)["accountPassword"];

if (password_verify($pass, $hash)) {  // returns true if the password and hash match, or false otherwise
    
    $query = "SELECT * FROM account WHERE accountUsername = '$username'";
    $result = mysqli_query($conn, $query);
    $account = mysqli_fetch_array($result);
  
    if(mysqli_num_rows($result) == 1) { 

        $_SESSION['logged_in'] = true;

        $_SESSION["accountID"] = $account["accountID"];
        $_SESSION["logged_in_message"] = "Welcome, " . $account["accountUsername"] . ".";  // can also use firstName or lastName

        if ($account["accountType"] == "buyer") {
            $_SESSION["account_type"] = "buyer";
        } else{
            $_SESSION["account_type"] = "seller";
        }
            
    } else {
        $_SESSION['logged_in'] = false;
        // account_type? 
    }
    
    header("refresh:3;url=index.php");
    echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');  // FIXME: can be fancier
} else {
    echo "Invalid Username or Password";  
    header("refresh:3;url=login.php");
}





// $query = mysqli_query($conn, "SELECT * FROM Account WHERE accountUsername = $username and accountPassword = $pass");
// $result = mysqli_query($con, $query);

// if ($rows == 1){ 
//     $account = mysqli_fetch_assoc($results);
//     header("refresh:5;url=index.php");
//     echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');
// } else {
//     echo "Invalid Username or Password";
//     //Redirect to where? 
//     }
?>