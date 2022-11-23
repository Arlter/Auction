<?php 
include_once("header.php")?>
<?php require_once("utilities.php")?>

<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */

// have to set $_SESSION['accountID'] when logging in
$accountID = $_SESSION["accountID"];
// hardcoded for now for testing

if (isset($_POST["submit"])) {
    $auctionTitle = mysqli_real_escape_string ($conn, $_POST["auctionTitle"]);
    $auctionDetails = htmlspecialchars($_POST["auctionDetails"]); // FIXME: how to preserve line breaks in listing page?
    $auctionCategory = mysqli_real_escape_string ($conn, $_POST["auctionCategory"]);
    $auctionStartPrice = mysqli_real_escape_string ($conn, $_POST["auctionStartPrice"]);
    $auctionReservePrice = mysqli_real_escape_string ($conn, $_POST["auctionReservePrice"]);
    $auctionEndDate = mysqli_real_escape_string ($conn, $_POST["auctionEndDate"]);
}             

// FIXME: Data validation
// this time can try use $_GET["error"]?
// when error:
// $error = "error_message";
// header("Location: create_auction.php?error=" . urlencode ($error)); 


// client-side checks should stop invalid data inputs, but do we have to put in server-side checks just in case?
if (mb_strlen($auctionTitle) > 40) {
    function_alert_create_auction("Title is too long, please try again.");
    exit();
} elseif (empty($auctionTitle) || ctype_space($auctionTitle)) {
    function_alert_create_auction("Title of auction is required, please try again.");
    exit();
}

if (mb_strlen($auctionDetails) > 250) {
    function_alert_create_auction("Details are too long, please try again.");
    exit();
}

if (empty($auctionCategory)) {
    function_alert_create_auction("Please choose a category.");
    exit();
} 

if ($auctionStartPrice != 0 && empty($auctionStartPrice) || ctype_space($auctionStartPrice)) {
    function_alert_create_auction("Starting price is required, please try again.");
    exit();
} elseif ($auctionStartPrice < 0) {
    function_alert_create_auction("Starting price cannot be negative, please try again.");
    exit();
}

if (!empty($auctionReservePrice) && $auctionReservePrice < 0) {
    function_alert_create_auction("Reserve price cannot be negative, please try again.");
    exit();
} elseif (empty($auctionReservePrice)) {
    $auctionReservePrice = 0;
}

$now = New DateTime();
if (empty($auctionEndDate) || ctype_space($auctionEndDate)) {
    function_alert_create_auction("End date is required, please try again.");
    exit();
} elseif ((New DateTime($auctionEndDate)) < $now) {
    function_alert_create_auction("End date cannot be earlier than the current time, please try again.");
    exit();
}

$auctionCreatedDate = $now -> format("Y-m-d\TH:i");

/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */

$query = "INSERT INTO Auction (itemName, itemDescription, categoryName, seller_accountID, startingPrice, reservePrice, createdDate, endDate)
VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$accountID', '$auctionStartPrice', '$auctionReservePrice', '$auctionCreatedDate', '$auctionEndDate')";
$res = mysqli_query($conn, $query);

if (mysqli_affected_rows($conn) ==1 && !mysqli_error($conn)) {

    $auction_id = mysqli_insert_id($conn);  // get the primary key (auctionID) of the last insert

    echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View all your listings</a> or <a href="listing.php?auctionID=' . $auction_id . '">view your new listing.</a></div>');
    // "view your listing" link directs user to the new item listing page, e.g. listing.php/?auctionID=100000000
    
} else {
    // echo "Error: " . $query . "<br>" . mysqli_error($conn);
    $error = "Connection error, please try again later.";
    function_alert_create_auction($error);
}


// If all is successful, let user know.
// echo('<div class="text-center">Auction successfully created! <a href="FIXME">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>