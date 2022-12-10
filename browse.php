<?php include_once("header.php")?>
<?php require_once("utilities.php")?>
<?php require_once("connection.php")?>

<?php
// If SESSION is set, display logged in message
if(isset($_SESSION["logged_in_message"])) {
  $logged_in_message = $_SESSION["logged_in_message"];
  echo
  "<div class='alert' style='background-color:lightgreen; color:black'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  <h5>$logged_in_message</h5>
  </div>";
unset($_SESSION["logged_in_message"]);
}
?>

<div class="container">
<h2 class="my-3">Browse listings</h2>
<div id="searchSpecs">
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text bg-transparent pr-0 text-muted">
              <i class="fa fa-search"></i>
            </span>
          </div>
          <?php
          // If text within keyword search field is not set, set keyword to null. Else, GET 'keyword' from URL
          if (!isset($_GET['keyword'])) {
            $keyword = null;
          }
          else {
            $keyword = $_GET['keyword'];
          }
          ?>
          <input name=keyword type="text" class="form-control border-left-0" id="keyword" placeholder="Search for anything" value="<?php echo $keyword;?>">
        </div>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <?php
        // Select category name from database, else set category to "All" 
        if (isset($_GET['cat'])){
          $category = $_GET['cat'];
        }else{
          $category = 'All';
        }
        // Query to select all from category table from database
        $query = "SELECT * FROM category";
        $result = mysqli_query($conn, $query);
        ?>
        <select name ="cat" class="form-control" id="cat">
        <option value="All">All categories</option>
        <?php while ($row=mysqli_fetch_array($result)){ ?>
          <option <?php if ($row['categoryName']==$category) { ?>selected ="selected"<?php } ?>>
            <?php echo ($row['categoryName']); ?>
          </option>
       <?php } ?>  
      </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <?php
        // If order_by is not set, set default ordering to be sorted by price low to high. Else, GET 'order_by' from URL
        if (!isset($_GET['order_by'])) {
          $ordering = 'pricelow';
        }else {
          $ordering = $_GET['order_by'];
        }
        ?>
        <select name="order_by" class="form-control" id="order_by" >
          <option value ="pricelow" <?php if ($ordering=='pricelow') { ?>selected = "selected"<?php } ?>> Price (low to high) </option>
          <option value ="pricehigh" <?php if ($ordering=='pricehigh') { ?>selected = "selected"<?php } ?>> Price (high to low) </option>
          <option value ="date" <?php if ($ordering=='date') { ?>selected = "selected"<?php } ?>> Soonest expiry </option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->


</div>

<?php
  // If text within keyword search field is not set, set keyword to null. Else, GET 'keyword' from URL
  if (!isset($_GET['keyword'])) {
    $keyword = null;
  }
  else {
    $keyword = $_GET['keyword'];
  }
  // If category is not set, set category to "All". Else, GET 'category' from URL
  if (!isset($_GET['cat'])) {
    $category = 'All';
  }
  else {
    $category = $_GET['cat'];
  }
  // If order_by is not set, set default ordering to be sorted by price low to high. Else, GET 'order_by' from URL
  if (!isset($_GET['order_by'])) {
    $ordering = 'pricelow';
  }
  else {
    $ordering = $_GET['order_by'];

  }
  // If page is not set, set current page to 1. Else, GET 'page' from URL
  if (!isset($_GET['page'])) {
    $curr_page = 1;
  }
  else {
    $curr_page = $_GET['page'];
  }
?>

<div class="container mt-5">
<ul class="list-group">

<?php
  // Construct search query from keywords, category and order by. Using these query, retrieve data from the database 
  $query = "SELECT * FROM auction WHERE (itemName LIKE '%$keyword%' OR itemDescription LIKE '%$keyword%')";

  if($category!='All'){
    $query.=" AND (categoryName LIKE '%$category%')";
  }
  if($ordering=='pricelow'){
    $query.=" ORDER BY currentPrice ASC";
  }
  else if($ordering=='pricehigh'){
    $query.=" ORDER BY currentPrice DESC";
  }
  else if($ordering=='date'){
    $query.=" AND (now() < endDate) ORDER BY endDate ASC";
  }

  // Maximum results per page = 5 
  $results_per_page = 5;

  // Retrieve the total number of results from the query 
  $res = mysqli_query($conn, $query);
  $num_results = (mysqli_num_rows($res)); 
  $max_page = ceil($num_results / $results_per_page);
  $rows = $res->fetch_all(MYSQLI_NUM);
  // Use for loop to print a list item for each auction listing retrieved from the query on first page
  if ($num_results > 0){
    if ($curr_page<$max_page){
      for ($x = ($curr_page-1)*$results_per_page; $x<$curr_page*$results_per_page; $x++){
        $row = $rows[$x];
        $auctionID = $row[0];
        $title = (mysqli_query($conn, "SELECT itemName FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $description = (mysqli_query($conn, "SELECT itemDescription FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $num_bids = (mysqli_query($conn, "SELECT COUNT(*) FROM bid where auction_auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $current_price = (mysqli_query($conn, "SELECT currentPrice FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $end_date = (mysqli_query($conn, "SELECT endDate FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        
        print_listing_li($auctionID, $title, $description, $current_price, $num_bids, $end_date);
      }
    } else{
    // Use for loop to print a list item for each auction listing retrieved from the query on the last page
    for ($x = ($curr_page-1)*$results_per_page;$x<$num_results;$x++){
      $row = $rows[$x];
        $auctionID = $row[0];
        $title = (mysqli_query($conn, "SELECT itemName FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $description = (mysqli_query($conn, "SELECT itemDescription FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $num_bids = (mysqli_query($conn, "SELECT COUNT(*) FROM bid where auction_auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $current_price = (mysqli_query($conn, "SELECT currentPrice FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        $end_date = (mysqli_query($conn, "SELECT endDate FROM auction where auctionID=$auctionID") -> fetch_array(MYSQLI_NUM))[0]; 
        
        print_listing_li($auctionID, $title, $description, $current_price, $num_bids, $end_date);
      }
    }
    }
  else {
  // If result set is empty, print 'No item found'
    echo 'No item found';
    $flag = 1;
    }
?>
</ul>

<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  
<?php
  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }
  
  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);
  
  if ($curr_page != 1 && !isset($flag) ) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }
    
  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }
    
    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }
  
  if ($curr_page != $max_page && !isset($flag)) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>


</div>



<?php include_once("footer.php")?>