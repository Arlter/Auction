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
      // creates a table showing unique auction ID, buyer account ID pairs
      $sql1 = "DROP TEMPORARY TABLE IF EXISTS table_1;";
      mysqli_query($conn, $sql1);
      $sql2 = "CREATE TEMPORARY TABLE table_1 SELECT DISTINCT auction_auctionID, buyer_accountID FROM bid;";
      mysqli_query($conn, $sql2);

      // creates a table of all auction IDs that the user is involved in
      $sql3 = "DROP TEMPORARY TABLE IF EXISTS table_2;";
      mysqli_query($conn, $sql3);
      $sql4 = "CREATE TEMPORARY TABLE table_2 SELECT auction_auctionID FROM table_1 WHERE buyer_accountID = " . $accountID . ";";
      mysqli_query($conn, $sql4);

      // create table to store cosine similarities with other users
      $sql5 = "DROP TEMPORARY TABLE IF EXISTS similarity;";
      mysqli_query($conn, $sql5);
      $sql6 = "CREATE TEMPORARY TABLE similarity SELECT DISTINCT buyer_accountID FROM bid WHERE buyer_accountID != " . $accountID . ";";
      mysqli_query($conn, $sql6);
      $sql7 = "ALTER TABLE similarity ADD cosine_similarity FLOAT(10);";
      mysqli_query($conn, $sql7);

      // create procedure that calculates and inputs cosine similarities into the relevant table created
      $sql8 = "DROP PROCEDURE IF EXISTS get_similarities;";
      mysqli_query($conn, $sql8);

      // call procedure
      $sql9 = "CALL get_similarities(" . $accountID . ");";
      mysqli_multi_query($conn, $sql9);

      // drop entries with null in the cosine similarities column
      $sql10 = "DELETE FROM similarity WHERE cosine_similarity IS NULL;";
      mysqli_query($conn, $sql10);

      // call procedure
      $sql11 = "CALL get_recommendations();";

      $result = mysqli_multi_query($conn, $sql11);
      if (mysqli_num_rows($result)>0){
        $array_of_auctions = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_free_result($result);

        // mysqli_close($conn);

        // TODO: Loop through results and print them out as list items.
        foreach($array_of_auctions as $row){
          $num_bids = (mysqli_query($conn, 'SELECT COUNT(*) FROM bid WHERE auction_auctionID=' . $row['auctionID']) -> fetch_array(MYSQLI_NUM))[0];

          print_listing_li($row['auctionID'], $row['itemName'], $row['itemDescription'], $row['currentPrice'], $num_bids, $row['endDate']);
        }
      }else{
        echo 'No results';
      }
    }    else {
      echo 'You must be a buyer to get recommendations';
      }
  }
?>
</ul>

<?php include_once("footer.php");?>