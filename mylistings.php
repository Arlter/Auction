<?php 
include_once("header.php");
require_once("utilities.php");
?>

<div class="container">

<h2 class="my-3">My listings</h2>
<ul class="list-group">


<?php
  // This page is for showing a user the auction listings they've made.
  // It will be pretty similar to browse.php, except there is no search bar.
  // This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

  // $accountID = 10000001;  // test
  // $_SESSION["accountType"] = "seller";  // test

  $accountID = $_SESSION["accountID"];
  // account type session should have been set after successful login

  // TODO: Check user's credentials (cookie/session).

  // TODO: Perform a query to pull up their auctions.

  // TODO: Loop through results and print them out as list items.

  if ($_SESSION["logged_in"] == true && isset($_SESSION["accountType"]) && $_SESSION["accountType"] == "seller") {
    $auctionquery = "SELECT * FROM Auction WHERE seller_accountID = $accountID";  // ORDER BY createdDate DESC, missing from local database for now
    $result = mysqli_query($conn, $auctionquery);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
        
        $item_id = $row["auctionID"];
        $title = $row["itemName"];
        $desc = $row["itemDescription"];
        $price = $row["currentPrice"];
        $num_bids = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM Bid where auction_auctionID=$item_id"))[0];
        $end_time = new DateTime($row["endDate"]);

        print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time);
      }
    } else {
      echo('<div class="text-center"> No listings have been made yet.</div>');  // FIXME: fancy
    }

  } else {
    header("refresh:3;url=browse.php");
    echo('<div class="text-center"> Seller-only function, redirecting in 3 seconds</div>'); 
  }
  
?>

<?php include_once("footer.php")?>