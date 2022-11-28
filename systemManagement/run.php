<?php require_once("connection.php")?>
<?php require_once("mail.php")?>
<?php 


$data_arr = mysqli_query($conn, "SELECT endDate,auctionID FROM AUCTION WHERE auctionStatus=TRUE and  endDate<=NOW() ORDER BY endDate ASC;");
if (isset($data_arr)){
    while( $row = $data_arr -> fetch_array(MYSQLI_NUM)){
        $end_time = $row[0];
        $auction_id = $row[1];
        mysqli_query($conn, "UPDATE AUCTION SET auctionStatus=FALSE WHERE auctionID=$auction_id");
        $result = mysqli_query($conn,"SELECT * FROM AUCTION WHERE auctionID=$auction_id and currentBidder IS NOT NULL and currentPrice >= reservePrice")-> fetch_array(MYSQLI_NUM);
        if (isset($result)){
            $final_price = (mysqli_query($conn, "SELECT currentPrice FROM Auction WHERE auctionID =$auction_id") -> fetch_array(MYSQLI_NUM))[0];
            $auction_url =  "<a href ='https://178auction.azurewebsites.net/listing.php?auctionID=".$auction_id."'</a> see more details";
            // SEND SELLER EMAIL
            $seller_email_address =  (mysqli_query($conn, "SELECT emailAddress FROM Account, Auction WHERE accountID=seller_accountID and auctionID=$auction_id") -> fetch_array(MYSQLI_NUM))[0];
            $seller_email_subject = "[Seller Notification] Successful Auction";
            $seller_email_content = "Congratulations! Your auction ".$auction_id. " has ended. The final bid price of the auction is ".chr(163).$final_price.$auction_url;
            send_email($seller_email_address,$seller_email_subject,$seller_email_content);
            // SEND AWARDED EMAIL
            $award_email_address =  (mysqli_query($conn, "SELECT emailAddress FROM Account, Auction WHERE accountID=currentBidder and auctionID=$auction_id") -> fetch_array(MYSQLI_NUM))[0];
            $award_email_subject = "[Award Notification] Awarded Bidder";
            $award_email_content = "Congratulations! The auction ".$auction_id. " has ended. You bid it with the price ".chr(163).$final_price.$auction_url;
            send_email($award_email_address,$award_email_subject,$award_email_content);
            // SEND WATCHER EMAIL
            $watcher_email_subject = "[Watcher Notification] The auction ".$auction_id." is over";
            $watcher_email_content = "The auction ".$auction_id. " has ended. The final bid price is ".chr(163).$final_price.$auction_url;
            $watcher_email_address =  mysqli_query($conn, "SELECT emailAddress FROM Account, BuyerWatchAuction WHERE accountID=buyer_accountID and auction_auctionID=$auction_id"); 
            if (isset($watcher_email_address)) {
                while( $watcher_email = $watcher_email_address -> fetch_array(MYSQLI_NUM)){
                    if ($watcher_email[0] != $seller_email_address and $watcher_email[0] != $award_email_address){
                        send_email($watcher_email[0], $watcher_email_subject, $watcher_email_content);
                    }
                }
            }

        }else{
            // SEND SELLER EMAIL
            $seller_email_address =  (mysqli_query($conn, "SELECT emailAddress FROM Account, Auction WHERE accountID=seller_accountID and auctionID=$auction_id") -> fetch_array(MYSQLI_NUM))[0];
            $seller_email_subject = "[Seller Notification] Unsuccessful Auction";
            $seller_email_content = "Sorry that your auction ".$auction_id. " is abortive.";
            send_email($seller_email_address,$seller_email_subject,$seller_email_content);
            echo $seller_email_address;
            // SEND WATCHER EMAIL
            $watcher_email_subject = "[Watcher Notification] The auction ".$auction_id." is over";
            $watcher_email_content = "The auction ".$auction_id. " has ended. The auction is abortive. ";
            $watcher_email_address =  mysqli_query($conn, "SELECT emailAddress FROM Account, BuyerWatchAuction WHERE accountID=buyer_accountID and auction_auctionID=$auction_id"); 
            if (isset($watcher_email_address)) {
                while( $watcher_email = $watcher_email_address -> fetch_array(MYSQLI_NUM)){
                    if ($watcher_email[0] != $seller_email_address){
                        send_email($watcher_email[0], $watcher_email_subject, $watcher_email_content);
                        //echo $watcher_email[0];
                    }
                }
            }

        }
    }

}


?>