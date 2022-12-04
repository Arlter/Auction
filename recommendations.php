<?php include_once("header.php");?>
<?php require_once("utilities.php");?>
<?php require_once("connection.php");?>

<div class="container">

<h2 class="my-3">Recommendations for you</h2>

<?php

  // This page is for showing a buyer recommended items based on their bid 
  // history. It will be pretty similar to browse.php, except there is no 
  // search bar. This can be started after browse.php is working with a database.
  // Feel free to extract out useful functions from browse.php and put them in
  // the shared "utilities.php" where they can be shared by multiple files.

  // TODO: Check user's credentials (cookie/session).
  // connection
  
  $accountType = $_SESSION['accountType'];
  if ($accountType != 'buyer') {
    header('Location: browse.php');
  } else {
    $result = mysqli_query($conn, "SELECT COUNT(auctionID) FROM auction WHERE outcomeNotificationStatus = 1");
    $count_active_auctions = mysqli_fetch_row($result)[0];
    
    if ($count_active_auctions == 0){
      echo 'No active auctions at the moment. Please wait for new auctions to be created.';
    } else {
    
      $accountID = $_SESSION['accountID'];

      $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM bid WHERE buyer_accountID = ?;");
      mysqli_stmt_bind_param($stmt, "s", $accountID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $count_bids);

      mysqli_stmt_fetch($stmt);

      if ($count_bids == 0){
        echo 'No results. You have made no bids and so we cannot provide you recommendations based on your bidding history. Bid on some auctions first!';
      } else {
        mysqli_stmt_close($stmt);
        $stmt = mysqli_prepare($conn, "CALL collaborative_filtering(?);");
        mysqli_stmt_bind_param($stmt, "s", $accountID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $auctionID, $itemName, $itemDescription, $currentPrice, $num_bids, $endDate);
    
        mysqli_stmt_fetch($stmt);

        // TODO: Loop through results and print them out as list items.
        // TODO: Perform a query to pull up auctions they might be interested in.
        if (($auctionID == NULL) or ($stmt == FALSE)){
          echo 'No results. Please wait for the other users who are sufficiently similar to you to bid on auctions!';
        } else{
          print_listing_li($auctionID, $itemName, $itemDescription, $currentPrice, $num_bids, $endDate);
          while (mysqli_stmt_fetch($stmt)){
            print_listing_li($auctionID, $itemName, $itemDescription, $currentPrice, $num_bids, $endDate);
          }
        }
        mysqli_stmt_close($stmt);
      }
    }
  }
?>
</ul>

<?php include_once("footer.php");?>