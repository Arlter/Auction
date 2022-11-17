<?php
    $host_address='erte.mysql.database.azure.com';
    $username='kien';
    $password='kien!ucl1';
    $dbname='auction';

// connecting to local database for testing
// if you use a different database, delete this file
// and connect to your own connection.php

// if every file has to include this file, session_start() can be put here instead

$conn = mysqli_connect("localhost", "root", "root", "auction");

if(!$conn){
    echo "Connection error: " . mysqli_connect_error();
}

?>