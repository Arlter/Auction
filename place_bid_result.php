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
    if (!isset($bidPrice)){
        $url = 'https://178auction.azurewebsites.net/listing.php?auctionID='.$auctionId;
        echo('<div class="text-center">Invalid bid! Try it again.</div>');
        header("refresh:3;url=$url");
    }
    $accountId= $_SESSION['accountID'];
    $email_address=$_SESSION['emailAddress'];

    // Verify if there exists an outbidder
    $cur_bidder_res = mysqli_query($conn, "SELECT currentBidder FROM Auction WHERE auctionID = $auctionId");
    if (mysqli_num_rows($cur_bidder_res)>0){
        $cur_bidder= $cur_bidder_res -> fetch_array(MYSQLI_NUM)[0];
        if ( !is_null($cur_bidder)){
            $outbidder_email = mysqli_query($conn, "SELECT emailAddress FROM Account WHERE accountID = $cur_bidder")-> fetch_array(MYSQLI_NUM)[0];
        }
    }
   
    $query = "INSERT INTO Bid(auction_auctionID,bidPrice,buyer_accountID,bidTime) VALUES($auctionId,CAST($bidPrice AS DECIMAL(10,2)), $accountId, CURRENT_TIME())";
    $res = mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) ==1 && !mysqli_error($conn))
    {
        // Insertion succeeded 
        echo('<div class="text-center">Your bid is successful! <a href="mybids.php">View your bids.</a></div>');
        // Buyer notification 
        $auction_url =  "<a href ='https://178auction.azurewebsites.net/listing.php?auctionID=".$auctionId."'</a>  see more details";
        $email_subject = "[Buyer Notification] Successful bid on the auction : ".$auctionId;
        $email_content = " Congratulation! You have placed a bid of ".chr(163). $bidPrice." on the auction ".$auctionId.$auction_url;
        send_email($email_address,$email_subject,$email_content);
        // Watcher notification
        $watcher_email_subject = "[Watcher Notification] A new bid ".chr(163).$bidPrice." on the auction ".$auctionId." has been made.";
        $watcher_email_content = "A new bid of price ".$bidPrice." has been made to the auction ".$auctionId.$auction_url;
        $watcher_email_address = mysqli_query($conn, "SELECT emailAddress FROM Account, BuyerWatchAuction WHERE accountID=buyer_accountID and auction_auctionID=$auctionId");  
        if (mysqli_num_rows($watcher_email_address)>0) {
            if (isset($outbidder_email)){
                // Outbid notification
                $outbidder_email_subject = "[Outbid Notification] A higher bid ".chr(163).$bidPrice." on the auction ".$auctionId." has been made.";
                $outbidder_email_content = "We are sorry to inform you that a higher bid of price ".$bidPrice." has been made to the auction ".$auctionId.$auction_url;
                send_email($outbidder_email, $outbidder_email_subject, $outbidder_email_content);
                while( $watcher_email = $watcher_email_address -> fetch_array(MYSQLI_NUM)){
                    if ($watcher_email[0] != $email_address && $watcher_email[0] != $outbidder_email){
                        send_email($watcher_email[0], $watcher_email_subject, $watcher_email_content);
                    }
                }
            }else{
                while( $watcher_email = $watcher_email_address -> fetch_array(MYSQLI_NUM)){
                    if ($watcher_email[0] != $email_address){
                        send_email($watcher_email[0], $watcher_email_subject, $watcher_email_content);
                    }
                }
            }

        }
    }

    else
    {
        echo '<div class="text-center">'. mysqli_error($conn);
        echo('<div class="text-center"> Unsucessful Bid.       <a href="https://178auction.azurewebsites.net/listing.php?auctionID='.$auctionId.'">Try bidding the auction again.</a></div>');
        header("refresh:5;url=browse.php");    
    }

?>

</div>


<?php include_once("footer.php")?>