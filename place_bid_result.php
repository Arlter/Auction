<?php include_once("header.php")?>
<?php require_once("connection.php")?>
<?php require_once("mail.php")?>
<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.

$auctionId=$_POST['auctionId'];
$bidPrice=$_POST['bidPrice'];
$email_address=$_SESSION['emailAddress'];
$email_subject = "Successful bid on the auction : ".$auctionId;
$email_content = " Congratulation! You have placed a bid of ".chr(163). $bidPrice." on the auction ".$auctionId;
/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */

            
/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */

$query = "INSERT INTO Bid(auction_auctionID,bidPrice,buyer_accountID,bidTime) VALUES($auctionId,CAST($bidPrice AS DECIMAL(10,2)),$_SESSION[accountID], CURRENT_TIME())";
$res = mysqli_query($conn, $query);
if (mysqli_affected_rows($conn) ==1 && !mysqli_error($conn))
{
    echo('<div class="text-center">Your bid is successful! <a href="mybids.php">View your new bids.</a></div>');
    send_email($email_address,$email_subject,$email_content);
}
else
{
    echo '<div class="text-center">'. mysqli_error($conn);
    echo('<div class="text-center"> Unsucessful Bid.       <a href="place_bid.php">Try bidding on another auction.</a></div>');
    header("refresh:5;url=browse.php");    
}


// If all is successful, let user know.



?>

</div>


<?php include_once("footer.php")?>