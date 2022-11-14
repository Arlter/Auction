<?php include_once("header.php")?>

<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

include "connection.php";

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */

// have to set $_SESSION['accountID'] when logging in
// $accountID = $_SESSION["accountID"];
// hardcoded for now for testing
$accountID = "10000001"; // DELETE LATER

if (isset($_POST["submit"])) {
    $auctionTitle = $_POST["auctionTitle"];
    $auctionDetails = mysqli_real_escape_string ($conn, $_POST["auctionDetails"]);  // or htmlspecialchars?
    $auctionCategory = mysqli_real_escape_string ($conn, $_POST["auctionCategory"]);
    $auctionStartPrice = mysqli_real_escape_string ($conn, $_POST["auctionStartPrice"]);
    $auctionReservePrice = mysqli_real_escape_string ($conn, $_POST["auctionReservePrice"]);
    $auctionEndDate = mysqli_real_escape_string ($conn, $_POST["auctionEndDate"]);
}             


/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */


// have to set $_SESSION['accountID'] when logging in
$query = "INSERT INTO Auction (itemName, itemDescription, categoryName, seller_accountID, startingPrice, reservePrice, endDate)
VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$accountID', '$auctionStartPrice', '$auctionReservePrice', '$auctionEndDate')";
if (mysqli_query($conn, $query)) {

    $auctionIDquery = mysqli_insert_id($conn);  // get the primary key (auctionID) of the last insert
    $_SESSION["auctionID"] = $auctionIDquery;


    //echo "New record created successfully. Last inserted ID is: " . $auctionIDquery;

    
    // can't store info in url? how to $_GET in listing.php then???
    // echo('<div class="text-center">Auction successfully created! <a href="listing.php">View your new listing.</a></div>');

    //not working, format super ugly??
    echo('<div class="text-center">Auction successfully created! <a href="listing.php?item_id='.$auctionIDquery.'">View your new listing.</a></div>');
    // directed to e.g. listing.php/?item_id=100000000
    

    // $success_message = "Auction created successfully.";
    // function_success_register($success_message);
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
    // $error = "Connection error, please try again later.";
    // function_alert_register($error);
}


// If all is successful, let user know.
// echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>