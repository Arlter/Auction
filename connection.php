<?php

$conn = mysqli_connect("localhost", "root", "root", "auction");

if(!$conn){
    echo "Connection error: " . mysqli_connect_error();
}

?>