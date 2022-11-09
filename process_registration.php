<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

include "connection.php";

//Input extraction
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
printf($username);
echo "<br>";
printf($password);
echo "<br>";
printf($passwordConfirmation);
echo "<br>";
printf($firstName);
echo "<br>";
printf($lastName);
echo "<br>";
printf($email);
echo "<br>";
printf($phoneNumber);
echo "<br>";
printf($accountType);
echo "<br>";




// Input validiation
// what is the best structure/hierarchy?

// check for empty input
if (empty($username) || empty($password) || empty($passwordConfirmation) || empty($firstName) ||
empty($lastName) || empty($email) || empty($phoneNumber)
|| ctype_space($username) || ctype_space($password) || ctype_space($passwordConfirmation) || ctype_space($firstName) ||
ctype_space($lastName) || ctype_space($email) || ctype_space($phoneNumber)) {
    $error = "Please fill in all the required information";
    header("Location: register.php?error =" . urlencode ($error));
        
    // add a closable alert message box
    // might wanna set different alerts for different errors? 
    // separate checks for each input, or group them into one statement?
    // also can the form save valid values so users don't have to type all of them in again?
        
    exit();  // is this necessary?
}


//username validation - VARCHAR(20), no whitespace, does not exist in database

$query = "SELECT accountUsername FROM Account WHERE accountUsername = '$username'";
$result = mysqli_query($conn, $query);

if (mb_strlen($username) > 20 || mb_strlen($username) < 4 || $username != trim($username)) {
    $error = "Invalid input, username must be 4 to 20 characters long with no space, please try again.";
    header("Location: register.php?error =" . urlencode ($error));
    exit();
} elseif (mysqli_num_rows($result) > 0) {
    $error = "Username already exists, please try again.";
    header("Location: register.php?error =" . urlencode ($error));
    exit();
} else {
    printf("valid!");
    exit();
}


// password confirmation - password == retyped-password
if ($password != $passwordConfirmation) {
    $error = "Your re-typed password is different";
    header("Location: register.php?error =" . urlencode ($error));

    // add a closable alert message box
        
    exit();  
}



// test connection by inserting data -- success
// $query = "INSERT INTO Account (accountUsername,accountPassword,accountType,firstName,lastName,emailAddress,phoneNumber)
// VALUES ('acc5','123','seller','john','doe','123@gmail.com','333333333')";
// if (mysqli_query($conn, $query)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $query . "<br>" . mysqli_error($conn);
// }


// create an account
//$query = "INSERT INTO Account (accountUsername,accountPassword,accountType,firstName,lastName,emailAddress,phoneNumber)
// VALUES ('$username', '$password', '$accountType', '$firstName', '$lastName', '$email', '$phoneNumber')";
// if (mysqli_query($conn, $query)) {
//     echo "New account created successfully";
// } else {
//     echo "Error: " . $query . "<br>" . mysqli_error($conn);
// }
    
?>