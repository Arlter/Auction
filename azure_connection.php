<?php

?>

<?php
    $conn = mysqli_init();
    mysqli_ssl_set($conn,NULL,NULL, "DigiCertGlobalRootCA.crt.pem", NULL, NULL);
    mysqli_real_connect($conn, "erte.mysql.database.azure.com", "kien", "kien!ucl1", "auction", 3306, MYSQLI_CLIENT_SSL);
    if (mysqli_connect_errno())
    {
        die('Failed to connect to MySQL: '.mysqli_connect_error());
    }
    printf("Reading data from the database: \n");
    $res = mysqli_query($conn, "SELECT * FROM Seller");

    echo "<table class='table'>
			<thead>
		  		<tr>
		  			<th> accountID</th>
		  			<th> accountUsername </th>
		  			<th> accountPassword </th>
                    <th>  firstName </th>
                    <th>  lastName  </th>
                    <th>  emailAddress </th>
                    <th>  phoneNumber </th>
		  		</tr> 
			</thead>
			<tbody>";
        while ($row = mysqli_fetch_assoc($res))
        {
            echo "<tr>";  
            echo "<td>" . $row['accountID'] . "</td>";
            echo "<td>" . $row['accountUsername'] . "</td>";
            echo "<td>" . $row['accountPassword'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";     
            echo "<td>" . $row['emailAddress'] . "</td>";
            echo "<td>" . $row['phoneNumber'] . "</td>";                  
            echo "</tr>";
            echo "</tbody>";
        }
        
    echo "</table>";
 ?>