<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Ticket</title>
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

        // function to remove unnecessary slashes, spaces, and converts special characters into html equivalents; common security measure
        function cleanInput($data){
            return htmlspecialchars(stripslashes((trim($data))));
        }

        // if id exists 
        if ($_SERVER["REQUEST_METHOD"] == "GET"){
            $id = $_GET['id'];
            // if its empty or not a numerical data
            if(empty($id) or !is_numeric($id)){
                echo '<h2>The booking ID is invalid.</h2>';
                exit();
            }
        }

        // if the submit button variable exists and is set in the url, is not empty, and is the same button as the one with the delete value
        if(isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit']=='Delete')){
            $error = 0;
            $msg = "Error";
    
            // if id exists, id is not empty, and has is an integer
            if(isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))){
                $id = cleanInput($_POST['id']);
            }else{
                $error++;
                $msg .='Invalid booking id';
                $id = 0;
            }

            // if there are no errors and the booking id is a greater value than 0
            if($error == 0 and $id > 0){
                // delete data with that booking id number from booking table in bnb database
                $query = "DELETE FROM booking WHERE bookingID=?";
                $stmt = mysqli_prepare($DBC, $query);
                mysqli_stmt_bind_param($stmt,'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                echo "<h2>Booking has been deleted.</h2>";
            }else{
                echo "<h5>$msg</h5>" .PHP_EOL;
            }
        }
    ?>
    <div class="booking-form">
        <h1>Booking preview before deletion</h1>
        <div class="return-links">
            <!-- have replaced "booking" with "Bookings" for design consistency-->
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <?php
            if(isset($id)) {
                $query = 'SELECT booking.bookingID, booking.checkinDate, booking.checkoutDate, room.roomname
                FROM booking
                INNER JOIN room ON booking.roomID = room.roomID
                WHERE bookingID = ' .$id;

                $result = mysqli_query($DBC, $query);
                $rowcount = mysqli_num_rows($result);

                if($rowcount > 0){
                    echo "<fieldset><legend>Booking detail #$id</legend><dl>";
                    $row = mysqli_fetch_assoc($result);
                    echo "<dt>Room name:</dt><dd>" .$row["roomname"] ."</dd>" .PHP_EOL;
                    echo "<dt>Checkin date:</dt><dd>" .$row["checkinDate"] ."</dd>" .PHP_EOL;
                    echo "<dt>Checkout date:</dt><dd>" .$row["checkoutDate"] ."</dd>" .PHP_EOL;
                    echo "</dl></fieldset>" .PHP_EOL;
                } 
        ?>
        <form method="POST" action="deletebooking.php">
            <h2>Are you sure you want to delete this Booking?</h2>
            <div class="booking-form-buttons">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" name="submit" value="Delete">
            <a href="listbookings.php">Cancel</a>
            </div>
        </form>
    </div>
    <?php
    }
    else{
        echo "<h2>No booking exists.</h2>";
        mysqli_free_result($result);
        mysqli_close($DBC);
    }
    ?>
</body>
</html>
