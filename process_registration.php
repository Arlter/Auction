<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

// session_start();

require_once "connection.php";  // using local db for testing for now
require_once "utilities.php";

/////////////////////////////////////////////////////

// // Utility functions (already in utilities.php, DELETE LATER)

// // redirects to register page and shows a closeable red alert box with relevant error message
// // see code in register.php
// function function_alert_register($error) {
//     $_SESSION["alert"] = $error;
//     header("Location: register.php?error =" . urlencode ($error));  // redirection to register.php
// }

// // redirects to login page after successful registration with green alert box indicating success
// // see code in browse.php (put there for now)
// function function_success_register($success_message) {
//     $_SESSION["reg_success"] = $success_message;
// // to prevent inputs appearing again when going to register.php again
//     unset($_SESSION["username"]);  // what if I wanna save username for login? hmmmm...
//     unset($_SESSION["firstName"]); 
//     unset($_SESSION["lastName"]); 
//     unset($_SESSION["email"]); 
//     unset($_SESSION["phoneNumber"]); 
//     header("Location: login.php?success =" . urlencode ($success_message));  // redirection to login.php
// }

/////////////////////////////////////////////////////


// Test connection
if (!$conn) {
    $error = "Connection error, please try again later.";
    function_alert_register($error);
    exit();
}


// Input validiation
// what is the best structure/hierarchy?
// current hierarchy is according to order below


// ajax check_username
// username checks: length longer than 4, alphanumericals only, no space, does not exist in db
if (!empty($_POST["username"])) {
    $query_username = "SELECT accountUsername FROM account WHERE accountUsername='".$_POST["username"]."'";
    $result_username = mysqli_query($conn, $query_username);
    $row = mysqli_num_rows($result_username);
    if (mb_strlen($_POST["username"]) < 4) {
        echo "<span style='color:red'>Username is too short, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif (strpos($_POST["username"], ' ') != false || !ctype_alnum($_POST["username"])) {
        echo "<span style='color:red'>Invalid username format, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif ($row > 0) {
        echo "<span style='color:red'>Username already exists, please try a different username.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'>Username is available.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
}

// ajax check_password
// check: length longer than 8, no space, pattern match: at least one number and one letter, may contain !@#$%
if (!empty($_POST["user_password"])) {
    if (mb_strlen($_POST["user_password"]) < 8) {
        echo "<span style='color:red'>Password is too short, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%&]{8,20}$/", $_POST["user_password"])) {
        echo "<span style='color:red'>Invalid password format, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'>Valid password.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
}

// FIXME: bug: if password gets deleted validation stays up 
// ajax confirm_password
// check: password matches with password confirmation
if (!empty($_POST["passwordConfirmation"]) && !empty($_POST["user_password_c"])) {
    if ($_POST["user_password_c"] != $_POST["passwordConfirmation"]) {
        echo "<span style='color:red'>The password confirmation does not match, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'>Password confirmed.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
}

// ajax phone validation
// check: pattern match, starts with +,
if (!empty($_POST["phone"])) {
    if (!preg_match("/^[+][(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/", $_POST["phone"])) {
        echo "<span style='color:red'>Invalid phone number format, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        echo "<span style='color:green'>Valid phone number format.</span>";
        echo "<script>$('#submit').prop('disabled',false);</script>";
    }
}


// Variable extraction
if (isset($_POST["submit"])) {
    $accountType = $_POST["accountType"];
    $accountUsername = mysqli_real_escape_string ($conn, $_POST["username"]);
    $accountPassword = mysqli_real_escape_string ($conn, $_POST["password"]);
    $passwordConfirmation = mysqli_real_escape_string ($conn, $_POST["passwordConfirmation"]);
    $firstName = mysqli_real_escape_string ($conn, $_POST["firstName"]);
    $lastName = mysqli_real_escape_string ($conn, $_POST["lastName"]);
    $email = mysqli_real_escape_string ($conn, $_POST["email"]);
    $phoneNumber = mysqli_real_escape_string ($conn, $_POST["phoneNumber"]);

    $hash = password_hash($accountPassword, PASSWORD_DEFAULT);  // requires VARCHAR(60) in database
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phoneNumber = str_replace(["(", ")","-", " ", ".", "/"], "", $phoneNumber);

    // Create an account!
    $query = "INSERT INTO Account (accountUsername, accountPassword, accountType, firstName,lastName, emailAddress, phoneNumber)
    VALUES ('$accountUsername', '$hash', '$accountType', '$firstName', '$lastName', '$email', '$phoneNumber')";
    if (mysqli_query($conn, $query)) {
        mysqli_close($conn);  // put this here?
        $success_message = "Account created successfully!";
        function_success_register($success_message);
    } else {
    // echo "Error: " . $query . "<br>" . mysqli_error($conn);
        $error = "Connection error, please try again later.";
        function_alert_register($error);
    }
} 



// // check for empty input
// if (empty($accountUsername) || empty($accountPassword) || empty($passwordConfirmation) || empty($firstName) ||
// empty($lastName) || empty($email) || empty($phoneNumber) || ctype_space($firstName) || ctype_space($lastName)) {
//     $error = "Please fill in all the required details.";
//     function_alert_register($error);
//     exit();
// }
// // Question: separate checks for each input, or group them into one statement?


// // Username validation - 4 to 20 characters long, no whitespace, does not exist in database
// // Question: other extra validation? legal characters?

// $result = mysqli_query($conn, "SELECT accountUsername FROM Account WHERE accountUsername = '$username'");

// if (mb_strlen($accountUsername) > 20 || mb_strlen($accountUsername) < 4 || 
// strpos($accountUsername, ' ') != false || !ctype_alnum($accountUsername) || ctype_space($accountUsername)) {
//     unset($_SESSION["username"]); 
//     $error = "Invalid username format, please try again.";

//     // error message ver1: javascript alert pop up --> redirect to register.php
//     // echo "<script type='text/javascript'>alert('$error');</script>";  // popup box
//     // how to change "localhost says"?

//     // error message ver2: HTML alert message box and sessions
//     // see code in register.php
//     header("Location: create_auction.php?error=" . urlencode ($error)); 
//     exit();
// } elseif (mysqli_num_rows($result) > 0) {  // query finds same username in database
//     unset($_SESSION["username"]); 
//     $error = "Username already exists, please try again.";
//     header("Location: register.php?error=" . urlencode ($error));
//     exit(); 
// } 
// // no need to exit if input is valid 


// // Password validation - must be 8 to 20 characters long, no space
// // Question: other extra validation? legal characters?
// if (mb_strlen($accountPassword) > 20 || mb_strlen($accountPassword) < 8 || 
// strpos($accountPassword, ' ') != false || !preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/", $accountPassword)) {
//     function_alert_register("Invalid password format, please try again.");
//     exit();
// }

// // Password confirmation - password == retyped-password
// if ($accountPassword != $passwordConfirmation) {
//     function_alert_register("The password confirmation does not match, please try again.");
//     exit();  
// }

// // Hash password to protect it, save hash to database instead of the actual password
// // See tutorial 4
// // Verify using password_verify ($accountPassword, $hash) function
// $hash = password_hash($accountPassword, PASSWORD_DEFAULT);  // requires VARCHAR(60) in database


// // Question: First name and last name validations necessary? Requirements?

// // FIXME: add length validation for firstname, lastname, email, phone

// // Email validation -- can only validate format for now
// $email = filter_var($email, FILTER_SANITIZE_EMAIL);
// if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     unset($_SESSION["email"]); 
//     function_alert_register("Invalid email format, please try again.");
//     exit();   
// }
// // Question: extra: email confirmation? lol

// // Phone format validation - start with + sign
// if (!preg_match("/^[+][(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/", $phoneNumber)) {
//     unset($_SESSION["phoneNumber"]); 
//     function_alert_register("Invalid phone format, please try again.");
//     exit(); 
// }

// test connection by inserting data directly
// $query = "INSERT INTO Account (accountUsername,accountPassword,accountType,firstName,lastName,emailAddress,phoneNumber)
// VALUES ('acc5','12345678','seller','John','Doe','123@abc.com','+3333333333')";
// if (mysqli_query($conn, $query)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $query . "<br>" . mysqli_error($conn);
// }


// // Create an account!

// $query = "INSERT INTO Account (accountUsername, accountPassword, accountType, firstName,lastName, emailAddress, phoneNumber)
// VALUES ('$accountUsername', '$hash', '$accountType', '$firstName', '$lastName', '$email', '$phoneNumber')";
// if (mysqli_query($conn, $query)) {
//     mysqli_close($conn);  // put this here?
//     $success_message = "Account created successfully!";
//     function_success_register($success_message);
// } else {
//    // echo "Error: " . $query . "<br>" . mysqli_error($conn);
//     $error = "Connection error, please try again later.";
//     function_alert_register($error);
// }

?>