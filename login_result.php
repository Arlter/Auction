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

$hashquery = "SELECT accountPassword FROM Account WHERE accountUsername = '$username'";
$hashresult = mysqli_query($conn, $hashquery);

$hash = mysqli_fetch_array($hashresult)["accountPassword"];

if (password_verify($pass, $hash)) {  // returns true if the password and hash match, or false otherwise
    
    $query = "SELECT * FROM account WHERE accountUsername = '$username'";
    $result = mysqli_query($conn, $query);
    $account = mysqli_fetch_array($result);
  
    if(mysqli_num_rows($result) == 1) { 

        $_SESSION['logged_in'] = true; 
        $_SESSION['emailAddress'] = $account['emailAddress'];
        $_SESSION["accountID"] = $account["accountID"];
        $_SESSION["logged_in_message"] = "Welcome, " . $account["accountUsername"] . ".";  // can also use firstName or lastName
        
        if ($account["accountType"] == "buyer") {
            $_SESSION["accountType"] = "buyer";
        } else {
            $_SESSION["accountType"] = "seller";
        }
            
    } else {
        header("refresh:2;url=login.php");
        echo('<div class="text-center" style="margin-top:50px">Login error, please try again. You will be redirected shortly.</div>');
        exit();  
    }
    header("refresh:2;url=index.php");
    echo('<div class="text-center" style="margin-top:50px">You are now logged in! You will be redirected shortly.</div>');
} else {
    header("refresh:2;url=login.php");
    echo('<div class="text-center" style="margin-top:50px">Invalid username or password. You will be redirected shortly.</div>');
    exit();  
}

?>