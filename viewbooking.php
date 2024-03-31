<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. 

Details will be updated after any edits/updates have been made. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View a booking</title>
    </script>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
        .detail{margin-left: 2.5em;}
        fieldset{width:50%;}
        p{height: 0.5em;}
    </style>
</head>
<body>
    <?php
    // assigns callable variable to database connection and provides error message if connection is unavailable
    include "config.php";
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MYSQL.". mysqli_connect_error();
        exit();
    };

    // if id exists 
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        $id = $_GET['id'];
        // if its empty or not a numerical data
        if(empty($id) or !is_numeric($id)){
            echo '<h2>The booking ID is invalid.</h2>';
            exit();
        }
    }

    $query = 'SELECT booking.bookingID, room.roomname, booking.checkinDate, booking.checkoutDate, booking.contactNumber, booking.extras, booking.roomReview
    FROM booking
    INNER JOIN room ON booking.roomID = room.roomID
    WHERE booking.bookingID = ' .$id;

    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);

    ?>
    <div class="booking-form">
        <h3>Logged in as Test</h3>
        <h1>Booking Details View</h1>
        <div class="return-links">
            <!-- Would suggest a replacement to "Bookings" to keep it consistent with the other pages -->
            <h2><a href="listbookings.php">[Return to the booking listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <!-- Have changed "room" detail to "booking" detail to fix what is being viewed, and will use booking id number instead of room id -->
        <?php
        if ($rowcount > 0){
            echo "<fieldset><legend>Booking detail #$id</legend><dl>";
            $row = mysqli_fetch_assoc($result);
            echo "<dt>Room name:</dt><dd>" .$row["roomname"] ."</dd>" .PHP_EOL;
            echo "<dt>Checkin date:</dt><dd>" .$row["checkinDate"] ."</dd>" .PHP_EOL;
            echo "<dt>Checkout date:</dt><dd>" .$row["checkoutDate"] ."</dd>" .PHP_EOL;
            echo "<dt>Contact number:</dt><dd>" .$row["contactNumber"] ."</dd>" .PHP_EOL;
            echo "<dt>Extras:</dt><dd>" .$row["extras"] ."</dd>" .PHP_EOL;
            echo "<dt>Room review</dt><dd>" .$row["roomReview"] ."</dd>" .PHP_EOL;
            echo "</dl></fieldset>" .PHP_EOL;
        }else{
            echo "<h2>No booking exists.</h2>";
            mysqli_free_result($result);
            mysqli_close($DBC);
        }
        ?>
    </div>
</body>
</html>