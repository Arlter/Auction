<?php include_once("header.php")?>
<?php require_once("connection.php")?>
<?php require_once("mail.php")?>
<div class="container my-5">

<?php
  // Prevent direct access
  if (!isset($_POST['auctionId']) || !isset($_POST['bidPrice']) ) {
    header('Location: browse.php');
  }
?>

<?php

// This function takes the form data and adds the new auction to the database.
    
    $auctionId=$_POST['auctionId'];
    $bidPrice=$_POST['bidPrice'];
    $accountId= $_SESSION['accountID'];
    $email_address=$_SESSION['emailAddress'];

    $query = "INSERT INTO Bid(auction_auctionID,bidPrice,buyer_accountID,bidTime) VALUES($auctionId,CAST($bidPrice AS DECIMAL(10,2)), $accountId, CURRENT_TIME())";
    $res = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) ==1 && !mysqli_error($conn))
    {
        echo('<div class="text-center">Your bid is successful! <a href="mybids.php">View your new bids.</a></div>');
        # Buyer notification 
        $email_subject = "[Buyer Notification] Successful bid on the auction : ".$auctionId;
        $email_content = " Congratulation! You have placed a bid of ".chr(163). $bidPrice." on the auction ".$auctionId;
        send_email($email_address,$email_subject,$email_content);
        $watcher_email_subject = "[Watcher Notification] A higher bid ".chr(163).$bidPrice." on the auction ".$auctionId." has been made.";
        $watcher_email_content = "A higher bid of price ".$bidPrice." has been made to the auction ".$auctionId;
        $watcher_email_address = mysqli_query($conn, "SELECT emailAddress FROM Account, BuyerWatchAuction WHERE accountID=buyer_accountID and auction_auctionID=$auctionId");  
        if (mysqli_num_rows($watcher_email_address)>0) {
            while( $watcher_email = $watcher_email_address -> fetch_array(MYSQLI_NUM)){
                if ($watcher_email[0] != $email_address){
                    send_email($watcher_email[0], $watcher_email_subject, $watcher_email_content);
                }
            }
        }
    }

    else
    {
        echo '<div class="text-center">'. mysqli_error($conn);
        echo('<div class="text-center"> Unsucessful Bid.       <a href="browse.php">Try bidding on another auction.</a></div>');
        header("refresh:5;url=browse.php");    
    }

?>

</div>


<?php include_once("footer.php")?>