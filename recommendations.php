<?php require_once("connection.php")?>
<?php include_once("header.php")?>
<?php require("utilities.php")?>

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
  session_start();

  $accountID = $_SESSION['accountID'];

  if(!$conn){
		echo 'Connection error: ' . mysqli_connect_error();
	}
  else {
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

-- create procedure to get recommendations
DROP PROCEDURE IF EXISTS get_recommendations;

DELIMITER //
CREATE PROCEDURE get_recommendations()
BEGIN
IF (SELECT COUNT(*) FROM similarity) != 0 THEN
	-- finds active auctions that the user is not currently participating in, that the other k most similar users are
	DROP TEMPORARY TABLE IF EXISTS table_active_auctions;
	
	CREATE TEMPORARY TABLE table_active_auctions
	SELECT auctionID
	FROM auction
	WHERE auctionStatus = 1;
    
	-- get k most similar users
	DROP TEMPORARY TABLE IF EXISTS table_k_users;
	
	CREATE TEMPORARY TABLE table_k_users
	SELECT buyer_accountID
	FROM similarity ORDER BY similarity.cosine_similarity DESC LIMIT 5;
	
	-- get auctions that k most similar users have participated in
	DROP TEMPORARY TABLE IF EXISTS table_k_users_auctions;
	
	CREATE TEMPORARY TABLE table_k_users_auctions
	SELECT DISTINCT auction_auctionID
	FROM bid
	WHERE bid.buyer_accountID IN(SELECT * FROM table_k_users);
	
	-- get auctions that k most similar users have participated in, that our user has not participated in
	DROP TEMPORARY TABLE IF EXISTS table_k_users_auctions_new;

	CREATE TEMPORARY TABLE table_k_users_auctions_new
	SELECT DISTINCT auction_auctionID
	FROM table_k_users_auctions
	WHERE auction_auctionID NOT IN(SELECT * FROM table_2);
	
	-- get auctions that k most similar users have participated in, that our user has not participated in, and that are active
	DROP TEMPORARY TABLE IF EXISTS table_k_users_auctions_new_active;
	
	CREATE TEMPORARY TABLE table_k_users_auctions_new_active
	SELECT auction_auctionID
	FROM table_k_users_auctions_new
	WHERE auction_auctionID IN(SELECT * FROM table_active_auctions);
  
  SELECT auctionID, itemName, itemDescription, currentPrice, endDate #missing num_bids
  FROM auction
  WHERE auctionID IN(SELECT * FROM table_k_users_auctions_new_active);
    
END IF;
END //
DELIMITER ;

-- call procedure and return results
CALL get_recommendations();";

  $result = mysqli_query($conn, $sql);

  $array_of_auctions = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);

  mysqli_close($conn);

  // TODO: Loop through results and print them out as list items.
  foreach($array_of_auctions as $row):
    $num_bids = (mysqli_query($conn, 'SELECT COUNT(*) FROM bid WHERE auction_auctionID=' . $row['auctionID']) -> fetch_array(MYSQLI_NUM))[0];

    print_listing_li($row['auctionID'], $row['itemName'], $row['itemDescription'], $row['currentPrice'], $num_bids, $row['endDate']);
    
  endforeach;
}
?>