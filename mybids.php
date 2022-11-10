<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php include_once("connection.php")?>


<div class="container">
<h2 class="my-3">My bids</h2>
<ul class="list-group">
<?php
  $accountID = $_SESSION['accountID'];
  // Fetch all the auctions that the buyer has engaged. 
  //$query = "SELECT auction_auctionID FROM (SELECT auction_auctionID, MAX(bidTime) FROM Bid WHERE buyer_accountID = $accountID group by auction_auctionID order BY max(bidTime) DESC, auction_auctionID) AS a";
  $query = "SELECT bidID FROM Bid WHERE buyer_accountID = $accountID order BY bidTime DESC";
  $res = mysqli_query($conn, $query);
  while( $row = $res -> fetch_array(MYSQLI_NUM)){
    $bid_id_arr[] = $row[0]; // Inside while loop
  }
  // if ($auc_id_arr) {
  //   foreach ($auc_id_arr as $auc_id){
  //     $item_id = $auc_id;
  //     $title = (mysqli_query($conn, "SELECT itemName FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM))[0];
  //     $description = (mysqli_query($conn, "SELECT itemDescription FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM))[0];
  //     $current_price = (mysqli_query($conn, "SELECT * FROM (SELECT currentPrice FROM Auction, bid WHERE auctionID= auction_auctionID and auctionID =$auc_id and currentBidder = $accountID ORDER BY bidTime) As a GROUP by currentPrice") -> fetch_array(MYSQLI_NUM))[0];
  //     $num_bids = (mysqli_query($conn, "SELECT COUNT(*) FROM bid where auction_auctionID=$auc_id and buyer_accountID =$accountID") -> fetch_array(MYSQLI_NUM))[0];
  //     $end_date = (mysqli_query($conn, "SELECT endDate FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM))[0];
  //     print_listing_li_bids($item_id, $title, $description, $current_price, $num_bids, $end_date);
  //   }
  if (isset($bid_id_arr)) {
      foreach ($bid_id_arr as $bid_id){
        $auc_id= (mysqli_query($conn, "SELECT auction_auctionID FROM Bid WHERE bidID =$bid_id") -> fetch_array(MYSQLI_NUM))[0];
        $title = (mysqli_query($conn, "SELECT itemName FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM))[0];
        $description = (mysqli_query($conn, "SELECT itemDescription FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM))[0];
        $bid_price = (mysqli_query($conn, "SELECT bidPrice FROM Bid WHERE bidID= $bid_id ") -> fetch_array(MYSQLI_NUM))[0];
        $end_date = (mysqli_query($conn, "SELECT endDate FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM))[0];
        $created_date = (mysqli_query($conn, "SELECT bidTime FROM Bid WHERE bidID= $bid_id ") -> fetch_array(MYSQLI_NUM))[0];
        print_listing_li_bids($auc_id, $title, $description, $bid_price,$end_date,$created_date);
    }

  } else {
    echo('<div class="text-center"> No bids have been made yet.</div>');
  }
?>
</ul>

<?php include_once("footer.php")?>