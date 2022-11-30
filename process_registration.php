
<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

session_start();

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
        $_SESSION['check_array']["username_check"]=false;
        echo "<span style='color:red'>Username is too short, please try again.</span>";
        echo "<script>document.writeln(p1);</script>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif (strpos($_POST["username"], ' ') != false || !ctype_alnum($_POST["username"])) {
        $_SESSION['check_array']["username_check"]=false;
        echo "<span style='color:red'>Invalid username format, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif ($row > 0) {
        $_SESSION['check_array']["username_check"]=false;
        echo "<span style='color:red'>Username already exists, please try a different username.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        $_SESSION['check_array']["username_check"]=true;
        echo "<span style='color:green'>Username is available.</span>";
        if(in_array(false, $_SESSION['check_array'], true) === false){
            echo "<script>$('#submit').prop('disabled',false);</script>";
        }

    }
}

// ajax check_password
// check: length longer than 8, no space, pattern match: at least one number and one letter, may contain !@#$%
if (!empty($_POST["password"])) {
    if (mb_strlen($_POST["password"]) < 8) {
        $_SESSION['check_array']["password_check"]=false;
        echo "<span style='color:red'>Password is too short, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif (!preg_match("/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%&]{8,20}$/", $_POST["password"])) {
        $_SESSION['check_array']["password_check"]=false;
        echo "<span style='color:red'>Invalid password format, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        $_SESSION['check_array']["password_check"]=true;
        echo "<span style='color:green'>Valid password.</span>";
        if(in_array(false, $_SESSION['check_array'], true) === false){
            echo "<script>$('#submit').prop('disabled',false);</script>";
        }
       
    }
}

// ajax confirm_password
//' check: password matches with password confirmation
if (!empty($_POST["password_c"]) && !empty($_POST["passwordConfirmation"])) {
    if ($_POST["password_c"] != $_POST["passwordConfirmation"]) {
        $_SESSION['check_array']["confirmpassword_check"]=false;
        echo "<span style='color:red'>Password confirmation does not match, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } elseif ($_POST["password_c"] == $_POST["passwordConfirmation"]) {
        $_SESSION['check_array']["confirmpassword_check"]=true;
        echo "<span style='color:green'>Password confirmed.</span>";
        if(in_array(false, $_SESSION['check_array'], true) === false){
            echo "<script>$('#submit').prop('disabled',false);</script>";
        }
    }
}

if (!empty($_POST["email"])) {
    if (!strpos($_POST["email"], '@') || !strpos($_POST["email"], '.') ) {
        $_SESSION['check_array']["email_check"]=false;
        echo "<span style='color:red'>Invalid email, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        $_SESSION['check_array']["email_check"]=true;
        echo "<span style='color:green'>Valid email format.</span>";
        if(in_array(false, $_SESSION['check_array'], true) === false){
            echo "<script>$('#submit').prop('disabled',false);</script>";
        }
    }
}


// ajax phone validation
// check: pattern match, starts with +,
if (!empty($_POST["phone"])) {
    if (!preg_match("/^[+][(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/", $_POST["phone"])) {
        $_SESSION['check_array']["phone_check"]=false;
        echo "<span style='color:red'>Invalid phone number format, please try again.</span>";
        echo "<script>$('#submit').prop('disabled',true);</script>";
    } else {
        $_SESSION['check_array']["phone_check"]=true;
        echo "<span style='color:green'>Valid phone number format.</span>";
        if(in_array(false, $_SESSION['check_array'], true) === false){
            echo "<script>$('#submit').prop('disabled',false);</script>";
        }
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
        unset($_SESSION['check_array']);
        function_success_register($success_message);
    } else {
    // echo "Error: " . $query . "<br>" . mysqli_error($conn);
        $error = "Connection error, please try again later.";
        function_alert_register($error);
    }
} 
?>