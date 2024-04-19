<?php
include "config.php";
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MYSQL.". mysqli_connect_error();
    exit();
};
?>