<?php

// connecting to local database for testing
// if you use a different database, delete this file
// and connect to your own connection.php

$conn = mysqli_connect("localhost", "root", "root", "auction");

if(!$conn){
    echo "Connection error: " . mysqli_connect_error();
}

?>