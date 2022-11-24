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
  
  if(!$conn){
		die("Connection error: " . mysqli_connect_error());
	}
  else {
    $accountID = $_SESSION['accountID'];
    $accountType = $_SESSION['accountType'];

    // TODO: Perform a query to pull up auctions they might be interested in.

    if ($accountType == 'buyer') {
      $stmt = mysqli_prepare($conn, "CALL collaborative_filtering(?);");
      mysqli_stmt_bind_param($stmt, "s", $accountID);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $auctionID, $itemName, $itemDescription, $currentPrice, $num_bids, $endDate);
      
      mysqli_stmt_fetch($stmt);

      // TODO: Loop through results and print them out as list items.
      if (($auctionID == NULL) or ($stmt == FALSE)){
        echo 'No results. Try to bid on more auctions, wait for others to bid on auctions, or wait for new auctions to appear!';
      } else{
        print_listing_li($auctionID, $itemName, $itemDescription, $currentPrice, $num_bids, $endDate);
        while (mysqli_stmt_fetch($stmt)){
          print_listing_li($auctionID, $itemName, $itemDescription, $currentPrice, $num_bids, $endDate);
        }
      }
      mysqli_stmt_close($stmt);
    } else {
      echo 'You must be a buyer to get recommendations';
      }
  }
?>
</ul>

<?php include_once("footer.php");?>