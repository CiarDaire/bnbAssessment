<?php

$servername = "127.0.0.1";
$username = "root";
$password = "root";
$dbname = "bnb";

if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = 'SELECT * FROM room 
WHERE roomID NOT IN (SELECT roomID FROM booking 
WHERE checkinDate >= ? AND checkoutDate <= ?)';

$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $startDate, $endDate);
$stmt->execute();

$result = $stmt->get_result();

if($result){
    if($result -> num_rows > 0){
        echo '<thead><tr><th>Room # </th><th>Roomname</th><th>Room Type</th><th>Beds</th></tr></thead>' .PHP_EOL;
        while ($row = $result->fetch_assoc()) {
            echo '<<tr><td>' .$row['roomID'] .'</td><td>'. $row['roomname'] .'</td><td>'. $row['roomtype'] .'</td><td>'. $row['beds'] .'</td></tr>' .PHP_EOL;
        }
    } else {
        echo "No rooms are available.";
    }
} else {
    echo "Error executing the query: " . $conn->error;
}

$stmt->close();
$conn->close();

?>
