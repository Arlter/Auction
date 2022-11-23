<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php require_once("connection.php")?>

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
		echo 'Connection error: ' . mysqli_connect_error();
	}
  else {
    $accountID = $_SESSION['accountID'];
    $accountType = $_SESSION['accountType'];

    if ($accountType == 'buyer') {
    // TODO: Perform a query to pull up auctions they might be interested in.
    $sql =
    "
    -- creates a table showing unique auction ID, buyer account ID pairs
    DROP TEMPORARY TABLE IF EXISTS table_1;

    CREATE TEMPORARY TABLE table_1
    SELECT DISTINCT auction_auctionID, buyer_accountID
    FROM bid;

    -- creates a table of all auction IDs that the user is involved in
    DROP TEMPORARY TABLE IF EXISTS table_2;

    CREATE TEMPORARY TABLE table_2
    SELECT auction_auctionID
    FROM table_1
    WHERE buyer_accountID = " . $accountID . ";

    -- create table to store cosine similarities with other users
    DROP TEMPORARY TABLE IF EXISTS similarity;

    CREATE TEMPORARY TABLE similarity
    SELECT DISTINCT buyer_accountID
    FROM bid
    WHERE buyer_accountID != " . $accountID . ";

    ALTER TABLE similarity ADD cosine_similarity FLOAT(10);

    -- create procedure that calculates and inputs cosine similarities into the relevant table created
    DROP PROCEDURE IF EXISTS get_similarities;

    DELIMITER $$
    CREATE PROCEDURE get_similarities()
    BEGIN
    DECLARE buyer_h INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE my_cursor CURSOR FOR SELECT buyer_accountID FROM similarity WHERE buyer_accountID != " . $accountID . ";
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN my_cursor;
    IF (SELECT COUNT(buyer_accountID) FROM similarity) != 0 THEN
      FETCH NEXT FROM my_cursor INTO buyer_h;
      
      WHILE (NOT done) DO
      IF ((SELECT COUNT(*) FROM table_2) != 0) AND (SELECT COUNT(table_1.auction_auctionID) FROM table_1 WHERE buyer_accountID = buyer_h) != 0
      THEN
      BEGIN
      DROP TEMPORARY TABLE IF EXISTS buyer_h_auctions;
          
          CREATE TEMPORARY TABLE buyer_h_auctions
          SELECT DISTINCT auction_auctionID AS buyer_h_auctionID
          FROM table_1
          WHERE buyer_accountID = buyer_h;
          
      DROP TEMPORARY TABLE IF EXISTS intersect_auctions;
          
          CREATE TEMPORARY TABLE intersect_auctions
          SELECT buyer_h_auctionID FROM buyer_h_auctions
          WHERE buyer_h_auctionID IN(SELECT * FROM table_2);
          
      INSERT INTO similarity VALUES(buyer_h,
          (SELECT COUNT(*) FROM intersect_auctions) /(((SELECT SQRT(COUNT(*)) FROM table_2))*(SELECT SQRT(COUNT(*)) FROM buyer_h_auctions)) );
      END;
      FETCH NEXT FROM my_cursor INTO buyer_h;
      END IF;
      END WHILE;
    
    CLOSE my_cursor;
    #deallocate my_cursor;
    END IF;
    END $$
    DELIMITER ;

    -- call procedure
    CALL get_similarities();

    -- drop entries with null in the cosine similarities column
    DELETE FROM similarity WHERE cosine_similarity IS NULL;

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

<?php include_once("footer.php")?>