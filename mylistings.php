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

  $accountID = $_SESSION["accountID"];

  if ($_SESSION["logged_in"] == true && isset($_SESSION["accountType"]) && $_SESSION["accountType"] == "seller") {
    $auctionquery = "SELECT * FROM Auction WHERE seller_accountID = $accountID order BY createdDate DESC";
    $result = mysqli_query($conn, $auctionquery);

    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
        
        $auction_id = $row["auctionID"];
        $title = $row["itemName"];
        $desc = $row["itemDescription"];
        $price = $row["currentPrice"];
        $num_bids = mysqli_fetch_array(mysqli_query($conn, "SELECT COUNT(*) FROM Bid where auction_auctionID=$auction_id"))[0];
        $end_time = $row["endDate"];

        print_listing_li($auction_id, $title, $desc, $price, $num_bids, $end_time);
      }
    } else {
      echo('<div class="text-center"> No listings have been made yet.</div>');  // FIXME: fancy
    }

  } else {
    header("refresh:1;url=browse.php");
    echo('<div class="text-center"> Seller-only function, you will be redirected shortly.</div>'); 
  }
  
?>

<?php include_once("footer.php")?>