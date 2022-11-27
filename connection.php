<?php
// Prevent direct access to this file
if( count(get_included_files()) == ((version_compare(PHP_VERSION, '5.0.0', '>='))?1:0) )
{
  echo "Direct access not allowed and you will be redirected shortly.";
  header("refresh:3;url=https://178auction.azurewebsites.net/browse.php");
}
?>
<?php
    $host_address='erte.mysql.database.azure.com';
    $username='kien';
    $password='kien!ucl1';
    $dbname='auction_online';

    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
    mysqli_real_connect($conn, $host_address, $username, $password, $dbname, 3306, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno())
    {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
    }
 ?>