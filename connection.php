<?php
    $host_address='erte.mysql.database.azure.com';
    $username='kien';
    $password='kien!ucl1';
    $dbname='auction';

    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL, "C:/Software/wamp/www/php_program/auction_final/COMP0178-Auction/DigiCertGlobalRootCA.crt.pem", NULL, NULL);
    mysqli_real_connect($conn, $host_address, $username, $password, $dbname, 3306, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno())
    {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
    }
 ?>