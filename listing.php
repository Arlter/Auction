<?php include_once("header.php")?>
<<<<<<< Updated upstream
<?php require("utilities.php")?>
=======
<<<<<<< Updated upstream
<?php require_once("utilities.php")?>
<?php require_once("connection.php")?>
>>>>>>> Stashed changes

<?php
  // Get info from the URL:
  $item_id = $_GET['item_id'];
<<<<<<< Updated upstream

=======
  $accountID = $_SESSION['accountID'];
  $created_date = (mysqli_query($conn, "SELECT createdDate  FROM Auction WHERE auctionID =$item_id") -> fetch_array(MYSQLI_NUM))[0];
  $current_bidder = (mysqli_query($conn, "SELECT currentBidder FROM Auction WHERE auctionID =$item_id") -> fetch_array(MYSQLI_NUM))[0];
  $auction_status = (mysqli_query($conn, "SELECT auctionStatus FROM Auction WHERE auctionID =$item_id") -> fetch_array(MYSQLI_NUM))[0];
=======
<?php require("utilities.php")?>
<?php include "connection.php"?>

<div class="container my-5">

<?php

// see error messages
// error_reporting(E_ALL); ini_set('display_errors', '1');

  // Get info from the URL:
  $item_id = $_GET['itemID'];
  $accountID = $_SESSION["accountID"];

>>>>>>> Stashed changes
>>>>>>> Stashed changes
  // TODO: Use item_id to make a query to the database.

  $itemQuery = "SELECT itemName, itemDescription, currentPrice, endDate FROM Auction WHERE auctionID = '$item_id'";

  $result = mysqli_query($conn, $itemQuery);
  $row = mysqli_fetch_array($result);

  $title = $row['itemName'];
  $description = $row['itemDescription'];
  $current_price = $row['currentPrice'];
  // DELETEME: For now, using placeholder data.
<<<<<<< Updated upstream
  $title = "Placeholder title";
  $description = "Description blah blah blah";
  $current_price = 30.50;
  $num_bids = 1;
  $end_time = new DateTime('2020-11-02T00:00:00');
=======
<<<<<<< Updated upstream
  $title = (mysqli_query($conn, "SELECT itemName FROM Auction WHERE auctionID =$item_id") -> fetch_array(MYSQLI_NUM))[0];
  $description = (mysqli_query($conn, "SELECT itemDescription FROM Auction WHERE auctionID =$item_id") -> fetch_array(MYSQLI_NUM))[0];
  $current_price = (mysqli_query($conn, "SELECT currentPrice FROM Auction WHERE auctionID =$item_id ") -> fetch_array(MYSQLI_NUM))[0];
  $num_bids = (mysqli_query($conn, "SELECT COUNT(*) FROM Bid where auction_auctionID=$item_id") -> fetch_array(MYSQLI_NUM))[0];
  $end_time = (mysqli_query($conn, "SELECT endDate FROM Auction WHERE auctionID =$item_id") -> fetch_array(MYSQLI_NUM))[0];
  $history = (mysqli_query($conn, "SELECT bidTime,buyer_accountID,bidPrice FROM Bid WHERE auction_auctionID =$item_id ORDER BY bidTime desc"));    
=======
  $num_bids = 1;  // need count query here?
  $end_time = $row['endDate'];

  // test
  // printf($item_id);
  // echo "<br>";
  // printf($title);
  // echo "<br>";
  // printf($description);
  // echo "<br>";
  // printf($current_price);
  // echo "<br>";
  // printf($num_bids);
  // echo "<br>";
  // printf($end_time);
  // echo "<br>";
>>>>>>> Stashed changes
  
>>>>>>> Stashed changes

  // TODO: Note: Auctions that have ended may pull a different set of data,
  //       like whether the auction ended in a sale or was cancelled due
  //       to lack of high-enough bids. Or maybe not.
  

  // Calculate time to auction end:

  $now = new DateTime();
<<<<<<< Updated upstream
=======
  $end_time = new DateTime($end_time);
<<<<<<< Updated upstream
>>>>>>> Stashed changes
  
  if ($now < $end_time) {
=======

  if ($now < $end_time) { 
>>>>>>> Stashed changes
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
    echo($time_remaining);
  }
  

  // TODO: If the user has a session, use it to make a query to the database
  //       to determine if the user is already watching this item.
  //       For now, this is hardcoded.
  $has_session = true;
  $watching = false;
?>


<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php echo($title); ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if ($now < $end_time):
?>
    <div id="watch_nowatch" <?php if ($has_session && $watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to watchlist</button>
    </div>
    <div id="watch_watching" <?php if (!$has_session || !$watching) echo('style="display: none"');?> >
      <button type="button" class="btn btn-success btn-sm" disabled>Watching</button>
      <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove watch</button>
    </div>
<?php endif /* Print nothing otherwise */ ?>
  </div>
</div>

<div class="row"> <!-- Row #2 with auction description + bidding info -->
  <div class="col-sm-8"> <!-- Left col with item info -->

    <div class="itemDescription">
    <?php echo($description); ?>
    </div>

  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if ($now > $end_time): ?>
     This auction ended <?php echo(date_format($end_time, 'j M H:i')) ?>
     <!-- TODO: Print the result of the auction here? -->
<?php else: ?>
     Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
    <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

    <!-- Bidding form -->
    <form method="POST" action="place_bid.php">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">£</span>
        </div>
	    <input type="number" class="form-control" id="bid">
      </div>
      <button type="submit" class="btn btn-primary form-control">Place bid</button>
    </form>
<?php endif ?>

  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  console.log("These print statements are helpful for debugging btw");

  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_nowatch").hide();
          $("#watch_watching").show();
        }
        else {
          var mydiv = document.getElementById("watch_nowatch");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Add to watch failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: [<?php echo($item_id);?>]},

    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        console.log("Success");
        var objT = obj.trim();
 
        if (objT == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("Error");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>