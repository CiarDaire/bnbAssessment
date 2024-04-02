<?php
if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    $query = 'SELECT * FROM room 
    WHERE roomID NOT IN (SELECT roomID FROM booking 
    WHERE checkinDate >= [startDate]
    AND checkoutDate <= [endDate])';
}
?>
