<?php include_once("header.php")?>

<?php
  // If user is not logged in or not a seller, they should not be able to
  // use this page.
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'buyer') {
    header('Location: browse.php');
  }

  //$placeholder = $_POST['auctionID']

?>



<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create a new bid</h2>
  <div class="card">
    <div class="card-body">
      <!-- Note: This form does not do any dynamic / client-side / 
      JavaScript-based validation of data. It only performs checking after 
      the form has been submitted, and only allows users to try once. You 
      can make this fancier using JavaScript to alert users of invalid data
      before they try to send it, but that kind of functionality should be
      extremely low-priority / only done after all database functions are
      complete. -->
      <form method="post" action="place_bid_result.php">
        <div class="form-group row">
          <label for="auctionid" class="col-sm-2 col-form-label text-right">Auction ID</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" id="auctionId" name ="auctionId" placeholder="e.g. 10000000">
            <small id="auctionId" class="form-text text-muted"><span class="text-danger">* Required.</span> The auctionID of the item you want to make a bid on.</small>
          </div>
        </div>

        <div class="form-group row">
          <label for="bidPrice" class="col-sm-2 col-form-label text-right">Bid price</label>
          <div class="col-sm-10">
	        <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" step="0.01" class="form-control" id="bidPrice" name="bidPrice">
            </div>
            <small id="bidPrice" class="form-text text-muted"><span class="text-danger">* Required.</span> Enter you bid price. </small>
          </div>
        </div>
        <button type="submit" class="btn btn-primary form-control">Create Bid</button>
      </form>
    </div>
  </div>
</div>

</div>


<?php include_once("footer.php")?>


<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

?>