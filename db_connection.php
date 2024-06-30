
<?php
$conn=new mysqli("localhost","root","","finance_users");


    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

?>
