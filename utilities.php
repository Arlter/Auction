<?php

// display_time_remaining:
// Helper function to help figure out what time to display
function display_time_remaining($interval) {

    if ($interval->days == 0 && $interval->h == 0) {
      // Less than one hour remaining: print mins + seconds:
      $time_remaining = $interval->format('%im %Ss');
    }
    else if ($interval->days == 0) {
      // Less than one day remaining: print hrs + mins:
      $time_remaining = $interval->format('%hh %im');
    }
    else {
      // At least one day remaining: print days + hrs:
      $time_remaining = $interval->format('%ad %hh');
    }

  return $time_remaining;

}

// print_listing_li:
// This function prints an HTML <li> element containing an auction listing
function print_listing_li($item_id, $title, $desc, $price, $num_bids, $end_time)
{
  // Truncate long descriptions
  if (strlen($desc) > 120) {
    $desc_shortened = substr($desc, 0, 120) . '...';
  }
  else {
    $desc_shortened = $desc;
  }
  
  // Fix language of bid vs. bids
  if ($num_bids == 1) {
    $bid = ' bid';
  }
  else {
    $bid = ' bids';
  }
  
  // Calculate time to auction end
  $now = new DateTime();
  $end_time = new DateTime($end_time);
  if ($now > $end_time) {
    $time_remaining = 'This auction has ended';
  }
  else {
    // Get interval:
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = display_time_remaining($time_to_end) . ' remaining';
  }
  
  // Print HTML
  echo('
    <li class="list-group-item d-flex justify-content-between">
    <div class="p-2 mr-5"><h5><a href="listing.php?auctionID=' . $item_id . '">' . $title . '</a></h5>' .$desc_shortened . '</div>
    <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>' . $num_bids . $bid . '<br/>' . $time_remaining . '</div>
  </li>'
  );
}


function print_listing_li_bids($item_id, $title, $desc, $price, $end_time,$created_date)
{
  // Truncate long descriptions
    if (strlen($desc) > 250) {
      $desc_shortened = substr($desc, 0, 250) . '...';
    }
    else {
      $desc_shortened = $desc;
    }

    $now = new DateTime();
    $end_time = new DateTime($end_time);
    if ($now > $end_time) {
      $time_remaining = 'This auction has ended';
    }
    else {
      // Get interval:
      $time_to_end = date_diff($now, $end_time);
      $time_remaining = display_time_remaining($time_to_end) . ' remaining until the end';
    }
    
    // Print HTML
    echo('
      <li class="list-group-item d-flex justify-content-between">
      <div class="p-2 mr-5"><h5><a href="listing.php?auctionID=' .$item_id . '">' .' ' . $title. '</a></h5>' .'<h10>'. '   AuctionID: '.$item_id.'</h10>' .'<br>'. 'ItemDescription: '.$desc_shortened . '</div>
      <div class="text-center text-nowrap"><span style="font-size: 1.5em">£' . number_format($price, 2) . '</span><br/>'  . '<br/>' .$created_date.'<br/>'. $time_remaining . '</div>
    </li>'
    );
}


function print_listing_li_history($item_id, $title, $num_bids, $history){
  if (mysqli_num_rows($history)>0) {
  echo('<br>
  <li class="list-group-item">
  <div class="p-2 mr-5"> '. '<center><h4>'.'Bid History&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<button type="button" disabled>'.'Total Bids: '. $num_bids.'</button>'. '</h4></center>' .'</div>
  </li>'
  );

    // Fix language of bid vs. bids
  if ($num_bids == 1) {
      $bid = ' bid';
    }
  else {
      $bid = ' bids';
    }
  while( $row = $history -> fetch_array(MYSQLI_NUM)){
    $bid_time = $row[0];
    $buyer_accountID = $row[1];
    $bid_price = $row[2];
    echo('
    <li class="list-group-item">
    <div class="p-2 mr-5"> '.'<i>'. $bid_time .'</i>&nbsp&nbsp' .' AccountID: ' .'<b>'.$buyer_accountID. '</b>'. '<span style="font-size: 1.0em"> &nbsp&nbspBid Price: £' . '<b>'. number_format($bid_price, 2) .  '</b>'.'</span><br/>'.'</div>
  </li>'
  );
}
}else{
  echo('<br>
    <li class="list-group-item">
    <div class="p-2 mr-5"> '. '<center><h4>No bid History for this auction</h4></center>' .'</div>
    </li>'
    );
}
}

// redirects to register page and shows a closeable red alert box with relevant error message
// see code in register.php
function function_alert_register($error) {
  header("Location: register.php?error=" . urlencode ($error));  // redirection to register.php
}

// redirects to login page after successful registration with green alert box indicating success
// see code in browse.php (put there for now)
function function_success_register($success_message) {
  header("Location: login.php?success=" . urlencode ($success_message));  // redirection to login.php
}

// redirects to create auction page and shows a closeable red alert box with relevant error message
// see code in create_auction.php
function function_alert_create_auction($error_message) {
  header("Location: create_auction.php?error=" . urlencode ($error_message));  // redirection to create_auction.php
}

?>