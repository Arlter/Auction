<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

<<<<<<< Updated upstream
=======
<<<<<<< Updated upstream
=======
>>>>>>> Stashed changes
session_start();

include "connection.php";  // using local db for testing for now


<<<<<<< Updated upstream
// Utility functions (move somewhere else later?)

// redirects to register page and shows a closeable red alert box with relevant error message
// see code in browse.php (put there for now)
=======
/////////////////////////////////////////////////////

// Utility functions (move somewhere else later?)

// redirects to register page and shows a closeable red alert box with relevant error message
// see code in register.php
>>>>>>> Stashed changes
function function_alert_register($error) {
    $_SESSION["alert"] = $error;
    header("Location: register.php?error =" . urlencode ($error));  // redirection to register.php
}

// redirects to login page after successful registration with green alert box indicating success
<<<<<<< Updated upstream
// see code in register.php
=======
// see code in browse.php (put there for now)
>>>>>>> Stashed changes
function function_success_register($success_message) {
    $_SESSION["reg_success"] = $success_message;
// to prevent inputs appearing again when going to register.php again
    unset($_SESSION["username"]);  // what if I wanna save username for login? hmmmm...
    unset($_SESSION["firstName"]); 
    unset($_SESSION["lastName"]); 
    unset($_SESSION["email"]); 
    unset($_SESSION["phoneNumber"]); 
    header("Location: browse.php?success =" . urlencode ($success_message));  // redirection to browse.php
}  // might change redirection to the login page if a separate login page is created 

<<<<<<< Updated upstream
=======
/////////////////////////////////////////////////////

>>>>>>> Stashed changes

// Test connection
if (!$conn) {
    $error = "Connection error, please try again later.";
    function_alert_register($error);
    exit();
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


// temporarily saves data in form input field so users don't have to enter all over again
// password and confirmation are never saved
// see changes to code in form in register.php
<<<<<<< Updated upstream
$_SESSION["accountType"] = $accountType;
=======
$_SESSION["account_type"] = $accountType;  // sorry for confusing naming styles, this is to match header.php
>>>>>>> Stashed changes
$_SESSION["username"] = $username;
$_SESSION["firstName"] = $firstName;
$_SESSION["lastName"] = $lastName;
$_SESSION["email"] = $email;
$_SESSION["phoneNumber"] = $phoneNumber;

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
// current hierarchy is according to order below

// check for empty input
if (empty($username) || empty($password) || empty($passwordConfirmation) || empty($firstName) ||
empty($lastName) || empty($email) || empty($phoneNumber) || ctype_space($firstName) || ctype_space($lastName)) {
    $error = "Please fill in all the required details.";
    function_alert_register($error);
    exit();
}
// Question: separate checks for each input, or group them into one statement?


// Username validation - 4 to 20 characters long, no whitespace, does not exist in database
// Question: other extra validation? legal characters?

$result = mysqli_query($conn, "SELECT accountUsername FROM Account WHERE accountUsername = '$username'");

if (mb_strlen($username) > 20 || mb_strlen($username) < 4 || 
strpos($username, ' ') != false || !ctype_alnum($username) || ctype_space($username)) {
    unset($_SESSION["username"]); 
    $error = "Invalid username format, please try again.";

    // error message ver1: javascript alert pop up --> redirect to register.php
    // echo "<script type='text/javascript'>alert('$error');</script>";  // popup box
    // how to change "localhost says"?

    // error message ver2: HTML alert message box and sessions
    // see code in register.php
    function_alert_register($error);
    exit();
} elseif (mysqli_num_rows($result) > 0) {  // query finds same username in database
    unset($_SESSION["username"]); 
    $error = "Username already exists, please try again.";
    function_alert_register($error);
    exit(); 
} 
// no need to exit if input is valid 


// Password validation - must be 8 to 20 characters long, no space
// Question: other extra validation? legal characters?
if (mb_strlen($password) > 20 || mb_strlen($password) < 8 || 
strpos($password, ' ') != false || !preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/", $password)) {
    $error = "Invalid password format, please try again.";
    function_alert_register($error);
    exit();
}

// Password confirmation - password == retyped-password
if ($password != $passwordConfirmation) {
    $error = "The password confirmation does not match, please try again.";
    function_alert_register($error);
    exit();  
}

// Hash password to protect it, save hash to database instead of the actual password
// See tutorial 4
// Verify using password_verify ($password, $hash) function
$hash = password_hash($password, PASSWORD_DEFAULT);  // requires VARCHAR(60) in database


// Question: First name and last name validations necessary? Requirements?


// Email validation -- can only validate format for now
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    unset($_SESSION["email"]); 
    $error = "Invalid email format, please try again.";
    function_alert_register($error);
    exit();   
}
// Question: extra: email confirmation? lol


// Phone format validation - start with + sign, 7-15 numbers (not including + sign) (I set the length arbituarily)
$phoneNumber = str_replace([" ", ".", "-", "(", ")"], "", $phoneNumber);
if (!preg_match("/[+][0-9]{7,15}/", $phoneNumber)) {
    unset($_SESSION["phoneNumber"]); 
    $error = "Invalid phone format, please try again.";
    function_alert_register($error);
    exit(); 
}


// test connection by inserting data directly
// $query = "INSERT INTO Account (accountUsername,accountPassword,accountType,firstName,lastName,emailAddress,phoneNumber)
// VALUES ('acc5','12345678','seller','John','Doe','123@abc.com','+3333333333')";
// if (mysqli_query($conn, $query)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $query . "<br>" . mysqli_error($conn);
// }


// Create an account!
$query = "INSERT INTO Account (accountUsername, accountPassword, accountType, firstName,lastName, emailAddress, phoneNumber)
VALUES ('$username', '$hash', '$accountType', '$firstName', '$lastName', '$email', '$phoneNumber')";
if (mysqli_query($conn, $query)) {
    mysqli_close($conn);  // put this here?
    $success_message = "Account created successfully.";
    function_success_register($success_message);
} else {
<<<<<<< Updated upstream
   // echo "Error: " . $query . "<br>" . mysqli_error($conn);  // what kind of error here?
=======
   // echo "Error: " . $query . "<br>" . mysqli_error($conn);
>>>>>>> Stashed changes
    $error = "Connection error, please try again later.";
    function_alert_register($error);
}
    
<<<<<<< Updated upstream
=======
>>>>>>> Stashed changes
>>>>>>> Stashed changes
?>