<?php 
include_once("header.php")?>
<?php require_once("utilities.php")?>

<?php
  // Prevent direct access
  if (!isset($_POST["submit"]) ) {
    header('Location: browse.php');
  }
?>

<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.


$accountID = $_SESSION["accountID"];

if (isset($_POST["submit"])) {
    $auctionTitle = mysqli_real_escape_string ($conn, $_POST["auctionTitle"]);
    $auctionDetails = htmlspecialchars($_POST["auctionDetails"]);
    $auctionCategory = mysqli_real_escape_string ($conn, $_POST["auctionCategory"]);
    $auctionStartPrice = mysqli_real_escape_string ($conn, $_POST["auctionStartPrice"]);
    $auctionReservePrice = mysqli_real_escape_string ($conn, $_POST["auctionReservePrice"]);
    $auctionEndDate = mysqli_real_escape_string ($conn, $_POST["auctionEndDate"]);
}             


// if reserve price is not entered, set to 0 by default
if (empty($auctionReservePrice)) {
    $auctionReservePrice = 0;
}

// creates string of the current time as input for auction created date 
$now = New DateTime();
$auctionCreatedDate = $now -> format("Y-m-d\TH:i:s");

//If everything looks good, make the appropriate call to insert data into the database.

$query = "INSERT INTO Auction (itemName, itemDescription, categoryName, seller_accountID, startingPrice, reservePrice, createdDate, endDate)
VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$accountID', '$auctionStartPrice', '$auctionReservePrice', '$auctionCreatedDate', '$auctionEndDate')";
$res = mysqli_query($conn, $query);

if (mysqli_affected_rows($conn) ==1 && !mysqli_error($conn)) {

    $auction_id = mysqli_insert_id($conn);  // get the primary key (auctionID) of the last insert

    echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View all your listings</a> or <a href="listing.php?auctionID=' . $auction_id . '">view your new listing.</a></div>');
    // "view your new listing" link directs user to the newest item listing page, e.g. listing.php/?auctionID=100000000
    
} else {
    // echo "Error: " . $query . "<br>" . mysqli_error($conn);
    $error = "Connection error, please try again later.";
    function_alert_create_auction($error);
}

?>

</div>


<?php include_once("footer.php")?>