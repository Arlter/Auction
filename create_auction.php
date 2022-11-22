<?php 
include_once("header.php")
?>

<?php
$now = new DateTime();
$now = $now -> format("Y-m-d\TH:i");
?>

<?php
// (Uncomment this block to redirect people without selling privileges away from this page)
  // If user is not logged in or not a seller, they should not be able to use this page.
  if ($_SESSION["logged_in"] == false || !isset($_SESSION["accountType"]) || $_SESSION["accountType"] != "seller") {
    header("Location:browse.php");
}
?>

<?php
if($_GET["error"]) {
  $error = $_GET["error"];
  echo
  "<div class='alert' style='background-color:pink; color:black'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  <h5><i class='icon fa fa-close'></i> Error</h5>$error
  </div>";
}
?>

<div class="container">

<!-- Create auction form -->
<div style="max-width: 800px; margin: 10px auto">
  <h2 class="my-3">Create new auction</h2>
  <div class="card">
    <div class="card-body">
      <!-- Note: This form does not do any dynamic / client-side / 
      JavaScript-based validation of data. It only performs checking after 
      the form has been submitted, and only allows users to try once. You 
      can make this fancier using JavaScript to alert users of invalid data
      before they try to send it, but that kind of functionality should be
      extremely low-priority / only done after all database functions are
      complete. -->
      <form method="post" action="create_auction_result.php">
        <div class="form-group row">
          <label for="auctionTitle" class="col-sm-2 col-form-label text-right">Title of auction</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="auctionTitle" id="auctionTitle" maxlength="40" placeholder="e.g. Black mountain bike" required>
              <small id='titleHelp' class='form-text text-muted'><span class='text-danger'>* Required. 40 characters maximum.</span> A short description of the item you're selling, which will display in listings.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionDetails" class="col-sm-2 col-form-label text-right">Details</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="auctionDetails" id="auctionDetails" maxlength="2000" rows="4" placeholder="Enter details here..."></textarea>
            <small id="detailsHelp" class="form-text text-muted">250 characters maximum. Full details of the listing to help bidders decide if it's what they're looking for.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="categories" class="col-sm-2 col-form-label text-right">Categories</label>
            <div class="col-sm-10">
            <select class="form-control" name="auctionCategory" id="auctionCategory" required>
            <option disabled hidden selected></option>
              <?php
              // Select category from database
              $query = "SELECT * FROM category";
              $result = mysqli_query($conn, $query);
              while ($row=mysqli_fetch_array($result)){
                echo '<option value="' . $row['categoryName'] . '">' . $row['categoryName'] . '</option>';
              }
              echo '</select>';
              ?> 
            <small id="categoryHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Select a category for this item.</small>
            </div>
        </div>
        <div class="form-group row">
          <label for="auctionStartPrice" class="col-sm-2 col-form-label text-right">Starting price</label>
          <div class="col-sm-10">
	        <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" step="0.10" name="auctionStartPrice" id="auctionStartPrice" min="0" required>  <!-- FIXME?: I set step=0.10 for now-->
            </div>
            <small id="startBidHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Initial bid amount.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionReservePrice" class="col-sm-2 col-form-label text-right">Reserve price</label>
          <div class="col-sm-10">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">£</span>
              </div>
              <input type="number" class="form-control" step="0.10" name="auctionReservePrice" id="auctionReservePrice" min="0">  <!-- optional-->
            </div>
            <small id="reservePriceHelp" class="form-text text-muted">Optional. Auctions that end below this price will not go through. This value is not displayed in the auction listing.</small>
          </div>
        </div>
        <div class="form-group row">
          <label for="auctionEndDate" class="col-sm-2 col-form-label text-right">End date</label>
          <div class="col-sm-10">
            <input type="datetime-local" class="form-control" name="auctionEndDate" id="auctionEndDate" min=<?php echo $now ?> required>  <!-- FIXME: min time-->
              <small id="endDateHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Day for the auction to end.</small>
          </div>
        </div>
        <button type="submit" class="btn btn-primary form-control" name="submit">Create Auction</button>
      </form>
    </div>
  </div>
</div>

</div>


<?php include_once("footer.php")?>