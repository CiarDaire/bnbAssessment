<?php
    include 'config.php';
    include 'ChromePhp.php';

    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
            if (mysqli_connect_errno()) {
                echo "Error: Unable to connect to MYSQL.". mysqli_connect_error();
                exit();
            };

    if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];

        $query = 'SELECT * FROM room 
        WHERE roomID NOT IN (SELECT roomID FROM booking 
        WHERE NOT (checkoutDate <= ? OR checkinDate >= ?))';

        $stmt = $DBC->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        ChromePhp::log('StartDate: ', $startDate);
        ChromePhp::log('StartDate: ', $endDate);
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
            echo "Error executing the query: " . $DBC->error;
        }

        $stmt->close();
        $DBC->close();
    }

    

?>
