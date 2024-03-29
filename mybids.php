<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php include_once("connection.php")?>

<?php
  // Prevent user from direct entering
  if ($_SESSION['logged_in'] == false || (isset($_SESSION['accountType']) and $_SESSION['accountType']=='seller') ) {
    header('Location: browse.php');
  }
?>

<div class="container">
<h2 class="my-3">My Bids</h2>
<ul class="list-group">
<?php

  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
  $results_per_page = 5;
  $accountID = $_SESSION['accountID'];
  // Fetch all the auctions that the buyer has engaged. 
  //$query = "SELECT auction_auctionID FROM (SELECT auction_auctionID, MAX(bidTime) FROM Bid WHERE buyer_accountID = $accountID group by auction_auctionID order BY max(bidTime) DESC, auction_auctionID) AS a";
  $query = "SELECT bidID FROM Bid WHERE buyer_accountID = $accountID order BY bidTime DESC";
  $res = mysqli_query($conn, $query);
  $num_results = (mysqli_num_rows($res)); 
  $max_page = ceil($num_results / $results_per_page);
  $rows = $res->fetch_all(MYSQLI_NUM);
  if ($num_results>0) {
      if ($curr_page<$max_page){
        for ($x = ($curr_page-1)*$results_per_page; $x<$curr_page*$results_per_page; $x++){
          $row = $rows[$x];
          $bid_id= $row[0]; 
          $bid_result_row = mysqli_query($conn, "SELECT * FROM Bid WHERE bidID =$bid_id") -> fetch_array(MYSQLI_NUM);
          $auc_id = $bid_result_row[1];
          $bid_price = $bid_result_row[2];
          $created_date = $bid_result_row[4];
          $auction_result_row = mysqli_query($conn, "SELECT itemName,itemDescription,endDate FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM);
          $title = $auction_result_row[0];
          $description = $auction_result_row[1];
          $end_date = $auction_result_row[2] ;
          print_listing_li_bids($auc_id, $title, $description, $bid_price,$end_date,$created_date);
        }
      }
      else{
        for ($x = ($curr_page-1)*$results_per_page;$x<$num_results;$x++){
          $row = $rows[$x];
          $bid_id= $row[0]; 
          $bid_result_row = mysqli_query($conn, "SELECT * FROM Bid WHERE bidID =$bid_id") -> fetch_array(MYSQLI_NUM);
          $auc_id = $bid_result_row[1];
          $bid_price = $bid_result_row[2];
          $created_date = $bid_result_row[4];
          $auction_result_row = mysqli_query($conn, "SELECT itemName,itemDescription,endDate FROM Auction WHERE auctionID =$auc_id") -> fetch_array(MYSQLI_NUM);
          $title = $auction_result_row[0];
          $description = $auction_result_row[1];
          $end_date = $auction_result_row[2] ;
          print_listing_li_bids($auc_id, $title, $description, $bid_price,$end_date,$created_date);
        }
      }

  } else {
    echo('<div class="text-center"> You have not placed any bid, <a href="browse.php">go bidding!</a></div>');

    $flag = 1;
  }
?>
</ul>

<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php
  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1 && !isset($flag)) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page  && !isset($flag)) {
    echo('
    <li class="page-item">
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>
