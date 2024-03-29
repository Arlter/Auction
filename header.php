<?php require_once "connection.php"?>
<?php session_start()?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">

  <title>Auction </title>
</head>


<body>

<!-- Navbars -->
<nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
  <a class="navbar-brand" href="browse.php">ɔ:kʃn <! --CHANGEME!--></a>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
    
<?php
  // Displays either login or logout on the right, depending on user's
  // current status (session).
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo "<link rel='stylesheet' type='text/css' href='.//css/custom.css'/>";
    echo '<span style="display:inline-block; margin-right:16px; font-size:14px">Username: ' . $_SESSION["accountUsername"] . '</span>';
    echo '<span style="display:inline-block; margin-right:16px; font-size:14px">Account ID: ' . $_SESSION["accountID"] . '</span>';
    echo '<span style="display:inline-block; margin-right:16px; font-size:14px">Account type: ' . $_SESSION["accountType"] . '</span>';
    echo '<a class="button" style="vertical-align:middle" href="logout.php"><span>Logout</span></a>';
  }
  else {
    // Avoid the case when logged_in is not declared initially.
    $_SESSION['logged_in'] = false;
    echo "<link rel='stylesheet' type='text/css' href='.//css/custom.css' />";
    echo ('
     <li class="nav-item mx-1">
        <a class="button" style="vertical-align:middle" href="login.php"><span>Login</span></a>
      </li>');
    }
?>

    </li>
  </ul>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav align-middle">
	<li class="nav-item mx-1">
      <a class="nav-link" href="browse.php">Browse</a>
    </li>
<?php
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['accountType']) && $_SESSION['accountType'] == 'buyer') {
  echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mybids.php">My Bids</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="recommendations.php">Recommended</a>
    </li>');
  }
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && isset($_SESSION['accountType']) && $_SESSION['accountType'] == 'seller') {
  echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mylistings.php">My Listings</a>
    </li>
	<li class="nav-item ml-3">
      <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
    </li>');
  }
?>
  </ul>
</nav>
</div> <!-- End modal -->