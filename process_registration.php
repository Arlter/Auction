<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

include "connection.php";  // using local db for testing for now


// Utility functions (move somewhere else later?)

// redirects to register page and give a closeable red alert box with relevant error message
// see code in browse.php (for now)
function function_alert($error) {
    session_start();
    $_SESSION["alert"] = $error;
    header("Location: register.php?error =" . urlencode ($error));  // redirection to register.php
}

// redirects to login page after successful registration with green alert box indicating success
// see code in register.php
function function_reg_success($success_message) {
    session_start();
    $_SESSION["reg_success"] = $success_message;
    //initiate();
    header("Location: browse.php?success =" . urlencode ($success_message));  // redirection to browse.php
}  // might change redirection to the login page if a separate login page is created 


// not sure about this, test this later on
function initiate() {
    session_start();
    $_SESSION["accountID"] = $row["accountID"];
    $_SESSION["accountUsername"] = $row["accountUsername"];
    $_SESSION["account_role"] = $row["accountType"];
}


// Variable extraction
if (isset($_POST["submit"])) {
    $accountType = $_POST["accountType"];
    $username = mysqli_real_escape_string ($conn, $_POST["username"]);
    $password = mysqli_real_escape_string ($conn, $_POST["password"]);
    $passwordConfirmation = mysqli_real_escape_string ($conn, $_POST["passwordConfirmation"]);
    $firstName = mysqli_real_escape_string ($conn, $_POST["firstName"]);
    $lastName = mysqli_real_escape_string ($conn, $_POST["lastName"]);
    $email = mysqli_real_escape_string ($conn, $_POST["email"]);
    $phoneNumber = mysqli_real_escape_string ($conn, $_POST["phoneNumber"]);
}
// alternative data cleaning: trim() then stripslashes() then htmlspecialchars()

// test
// printf($username);
// echo "<br>";
// printf($password);
// echo "<br>";
// printf($passwordConfirmation);
// echo "<br>";
// printf($firstName);
// echo "<br>";
// printf($lastName);
// echo "<br>";
// printf($email);
// echo "<br>";
// printf($phoneNumber);
// echo "<br>";
// printf($accountType);
// echo "<br>";


// Input validiation
// what is the best structure/hierarchy?
// current hierarchy is according to order

// check for empty input
if (empty($username) || empty($password) || empty($passwordConfirmation) || empty($firstName) ||
empty($lastName) || empty($email) || empty($phoneNumber)
|| ctype_space($username) || ctype_space($password) || ctype_space($passwordConfirmation) || ctype_space($firstName) ||
ctype_space($lastName) || ctype_space($email) || ctype_space($phoneNumber)) {
    $error = "Please fill in all the required information.";
    function_alert($error);
    exit();
}
// separate checks for each input, or group them into one statement?
// also can the form save valid values so users don't have to type all of them in again?


// username validation - VARCHAR(20), no whitespace, does not exist in database
// specific characters required?

$result = mysqli_query($conn, "SELECT accountUsername FROM Account WHERE accountUsername = '$username'");

if (mb_strlen($username) > 20 || mb_strlen($username) < 4 || strpos($username, ' ') != false) {
    $error = "Username must be 4 to 20 characters long with no space, please try again.";

    // error message ver1: javascript alert pop up --> redirect to register.php
    // echo "<script type='text/javascript'>alert('$error');</script>";  // popup box
    // how to change "localhost says"?

    // error message ver1.1: function with javascript, same as above
    // function_alert($error);

    // error message ver2: HTML alert message box
    function_alert($error);
    exit();
} elseif (mysqli_num_rows($result) > 0) {
    $error = "Username already exists, please try again.";
    function_alert($error);
    exit(); 
} //no need to exit if input is valid 

// password validation - must be 8 to 20 characters long, no space
// specific characters required?
if (mb_strlen($password) > 20 || mb_strlen($password) < 8 || strpos($password, ' ') != false) {
    $error = "Password must be 8 to 20 characters long with no space, please try again.";
    function_alert($error);
    exit();
}

// password confirmation - password == retyped-password
if ($password != $passwordConfirmation) {
    $error = "The password confirmation does not match, please try again.";
    function_alert($error);
    exit();  
}

// Hash password to protect it, save hash to database instead of the actual password
$hash = password_hash($password, PASSWORD_DEFAULT);  // requires VARCHAR(60) in database
// See tutorial 4
// Verify using password_verify ($password, $hash) function


// email address validation -- can only validate format for now
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format, please try again.";
    function_alert($error);
    exit();   
}
// extra: email confirmation? lol


// phone format validation

$phoneNumber = str_replace([" ", ".", "-", "(", ")"], "", $phoneNumber);
    if (!preg_match("/[+][0-9]{8,16}/", $phoneNumber)) {  // current set number of digits to 8-16 including + sign
        $error = "Invalid phone format, please try again.";
        function_alert($error);
        exit(); 
    }


// test connection by inserting data directly
// $query = "INSERT INTO Account (accountUsername,accountPassword,accountType,firstName,lastName,emailAddress,phoneNumber)
// VALUES ('acc5','123','seller','john','doe','123@gmail.com','333333333')";
// if (mysqli_query($conn, $query)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $query . "<br>" . mysqli_error($conn);
// }


// create an account
$query = "INSERT INTO Account (accountUsername,accountPassword,accountType,firstName,lastName,emailAddress,phoneNumber)
VALUES ('$username', '$hash', '$accountType', '$firstName', '$lastName', '$email', '$phoneNumber')";
if (mysqli_query($conn, $query)) {
    mysqli_close($conn);  // put this here? no idea
    $success_message = "Account created successfully.";
    function_reg_success($success_message);
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);  // what kind of error here?
    // $error = "Connection error, please try again later.";
    // function_alert($error);
}
    
?>