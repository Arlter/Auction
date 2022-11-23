<?php include_once("header.php")?>
<?php require_once("utilities.php")?>
<?php require_once("connection.php")?>

<?php
  // Get info from the URL: 
  // item_id is also the auction id.
  $auctionID = $_GET['auctionID'];
  $accountID = $_SESSION['accountID'];

  // Check if the auctionID exists.
  $res = mysqli_query($conn, "SELECT * FROM Auction WHERE auctionID = $auctionID");
  if (mysqli_num_rows($res)>0) {
      $created_date = (mysqli_query($conn, "SELECT createdDate  FROM Auction WHERE auctionID =$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $current_bidder = (mysqli_query($conn, "SELECT currentBidder FROM Auction WHERE auctionID =$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $auction_status = (mysqli_query($conn, "SELECT auctionStatus FROM Auction WHERE auctionID =$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $title = (mysqli_query($conn, "SELECT itemName FROM Auction WHERE auctionID =$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $description = (mysqli_query($conn, "SELECT itemDescription FROM Auction WHERE auctionID =$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $current_price = (mysqli_query($conn, "SELECT currentPrice FROM Auction WHERE auctionID =$auctionID ") -> fetch_array(MYSQLI_NUM))[0];
      $num_bids = (mysqli_query($conn, "SELECT COUNT(*) FROM Bid where auction_auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $end_time = (mysqli_query($conn, "SELECT endDate FROM Auction WHERE auctionID =$auctionID") -> fetch_array(MYSQLI_NUM))[0];
      $history = (mysqli_query($conn, "SELECT bidTime,buyer_accountID,bidPrice FROM Bid WHERE auction_auctionID =$auctionID ORDER BY bidTime desc"));    
      // Calculate time to auction end:
      $now = new DateTime();
      $end_time = new DateTime($end_time);
      
      if ($now < $end_time) {
        $time_to_end = date_diff($now, $end_time);
        $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
      }   

      if ($has_session){
        $accountID=$_SESSION['accountID'];
        $accountType = $_SESSION["accountType"];
        $result = mysqli_query($conn,"SELECT *  FROM BuyerWatchAuction WHERE auction_auctionID =$auctionID and buyer_accountID=$accountID");
        if (mysqli_num_rows($result)>0) {
          $watching = true;
        }else{
          $watching = false;
        }
      }else{
        $watching = false;
      }
  }else {
    echo "The auction does not exist, please check the auctionID";
    $has_session = false;
    $watching = false;
    header("refresh:3;url=browse.php");
  }

?>


<div class="container">

<div class="row"> <!-- Row #1 with auction title + watch button -->
  <div class="col-sm-8"> <!-- Left col -->
    <h2 class="my-3"><?php if (mysqli_num_rows($res)>0) {echo($title);} ?></h2>
  </div>
  <div class="col-sm-4 align-self-center"> <!-- Right col -->
<?php
  /* The following watchlist functionality uses JavaScript, but could
     just as easily use PHP as in other places in the code */
  if (mysqli_num_rows($res)>0 &&  $now < $end_time && $has_session && $accountType!='seller'):
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
    <?php if (mysqli_num_rows($res)>0) echo($description);?>
    </div>

    <div class="history">
    <?php if (mysqli_num_rows($res)>0) {print_listing_li_history($auctionID, $title, $num_bids, $history);}?>
    </div>
    
  </div>

  <div class="col-sm-4"> <!-- Right col with bidding info -->

    <p>
<?php if (mysqli_num_rows($res)>0 and $now > $end_time  ): ?>
     This auction ended at  <b><?php echo(date_format($end_time, 'd/m/Y h:i:s A')) ?></b>
     <?php if ($current_bidder != Null): ?>
  <br>Successful auction with final bid price £:  <b><?php echo($current_price) ?></b>
    <?php else: ?>
  <br>Abortive auction with no bids
    <?php endif ?>
<?php else: ?>
    <?php if (mysqli_num_rows($res)>0 ): ?>
      Auction ends <?php echo(date_format($end_time, 'j M H:i') . $time_remaining) ?></p>  
      <p class="lead">Current bid: £<?php echo(number_format($current_price, 2)) ?></p>

      <?php if ($has_session and $accountType!='seller'): ?>
      <!-- Bidding form -->
      <form method="POST" action="place_bid_result.php">
      <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">AuctionId</span>
          </div>
          <input type="number" class="form-control" id="auctionId" name ="auctionId" value= <?php echo $auctionID ?> readonly>
        </div>
      
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text">£</span>
          </div>
        <input type="number" step="0.01" class="form-control" id="bidPrice" name ="bidPrice">
        </div>
        <button type="submit" class="btn btn-primary form-control">Place bid</button>
      </form>
    <?php else: ?>
    <?php endif ?>
<?php endif ?>

  
  </div> <!-- End of right col with bidding info -->

</div> <!-- End of row #2 -->



<?php include_once("footer.php")?>


<script> 
// JavaScript functions: addToWatchlist and removeFromWatchlist.

function addToWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'add_to_watchlist', arguments: <?php echo($auctionID);?>},

    success: 
      function (data, textstatus) {
        // Callback function for when call is successful and returns obj
        //console.log(data);
        if (data.trim()== "success") {
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
        console.log("The php file is not found");
      }
  }); // End of AJAX call

} // End of addToWatchlist func

function removeFromWatchlist(button) {
  // This performs an asynchronous call to a PHP function using POST method.
  // Sends item ID as an argument to that function.
  $.ajax('watchlist_funcs.php', {
    type: "POST",
    data: {functionname: 'remove_from_watchlist', arguments: <?php echo($auctionID);?>},
    success: 
      function (obj, textstatus) {
        // Callback function for when call is successful and returns obj
        if (obj.trim() == "success") {
          $("#watch_watching").hide();
          $("#watch_nowatch").show();
        }
        else {
          var mydiv = document.getElementById("watch_watching");
          console.log(mydiv);
          mydiv.appendChild(document.createElement("br"));
          mydiv.appendChild(document.createTextNode("Watch removal failed. Try again later."));
        }
      },

    error:
      function (obj, textstatus) {
        console.log("The php file is not found");
      }
  }); // End of AJAX call

} // End of addToWatchlist func
</script>