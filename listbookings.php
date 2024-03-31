<!-- Current bookings. Assumption that user login required. 

Future iteration will likely include filtering options by customer and booking period data  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings list</title>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
    </style>
</head>
<body>
    <?php
    // assigns callable variable to database connection and provides error message if connection is unavailable
    include "config.php";
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL Database". mysqli_connect_error();
        exit();
    }

    // gets booking id, customers name, room name, and booking dates from database
    $query = 'SELECT booking.bookingID, customer.firstname, customer.lastname, room.roomname, booking.checkinDate, booking.checkoutDate
    FROM booking
    JOIN room ON booking.roomID = room.roomID
    JOIN customer ON booking.customerID = customer.customerID
    ORDER BY bookingID';

    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);

    ?>

    <div class="booking-list">
        <h1>Current bookings</h1>
        <div class="return-links">
            <h2><a href="addbooking.php">[Make a booking]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <table border="1">
            <thead>
                <tr>
                    <th>Booking (room, dates)</th>
                    <th>Customer</th>
                    <th>Action</th>
                </tr>
            <thead>
            <?php 
                if($rowcount > 0){
                    while($row = mysqli_fetch_array($result)){
                        $bookingID = $row['bookingID'];
                        echo '<tr><td>' .$row['roomname'] .','. $row['checkinDate'] .','. $row['checkoutDate'] .'</td>';
                        echo '<td>' .$row['lastname'] .',' .$row['firstname'] .'</td>';
                        echo '<td> <a href="viewbooking.php?id='.$bookingID .'">[view]</a><a href="editbooking.php?id='.$bookingID .'">[edit]</a><a href="managereviews.php?id='.$bookingID .'">[manage reviews]</a><a href="deletebooking.php?id='.$bookingID .'">[delete]</a></td>';
                        echo '</tr>' .PHP_EOL;
                    }
                }else{
                    echo '<h2>No bookings found.</h2>';
                    mysqli_free_result($result);
                    mysqli_close($DBC);
                }
            ?>
        </table>
    </div>
</body>
</html>

<!-- <tr> -->
                <!-- <td>Kellie, 2018-09-15, 2018-09-19</td>
                <td>Jordan, Garrison</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
            <tr>
                <td>Kellie, 2018-09-20, 2018-09-23</td>
                <td>Walker, Irene</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
            <tr>
                <td>Herman, 2018-10-01, 2018-10-14</td>
                <td>Walker, Irene</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
            <tr>
                <td>Herman, 2018-10-16, 2018-10-20</td>
                <td>Sellers, Beverly</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr> -->