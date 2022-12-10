<?php require_once("connection.php")?>
<?php if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return "error";
}
session_start();
// Extract arguments from the POST variables:

$item_id = $_POST['arguments'];
$accountID = $_SESSION['accountID'];

if ($_POST['functionname'] == "add_to_watchlist") {

  $result = mysqli_query($conn, "SELECT * FROM BuyerWatchAuction WHERE auction_auctionID = $item_id and buyer_accountID=$accountID")-> fetch_array(MYSQLI_NUM);
  if (isset($result))
    {
      $res= "The buyer has existed in the watchlist";
    }
  else
    {
      mysqli_query($conn, "INSERT INTO BuyerWatchAuction(auction_auctionID,buyer_accountID) VALUES ($item_id,$accountID)");
      $res= "success";
  }
}else if ($_POST['functionname'] == "remove_from_watchlist") {

  $result = mysqli_query($conn, "SELECT * FROM BuyerWatchAuction WHERE auction_auctionID = $item_id and buyer_accountID=$accountID")-> fetch_array(MYSQLI_NUM);
  if (!isset($result))
    {
      $res= "The buyer is not in the watchlist";
    }
  else
    {
      mysqli_query($conn, "DELETE FROM BuyerWatchAuction WHERE auction_auctionID=$item_id and buyer_accountID=$accountID");
      $res= "success";
  }
}

echo $res;

?>