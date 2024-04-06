<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a booking</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // sets default date format to yy-mm-dd for following fields
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
            });

            $(function(){
                // checkin date set to 2018 to fit design brief, shows 2 month span
                checkinDate = $('#checkinDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
                .on("change", function() {
                checkoutDate.datepicker("option", "minDate", getDate(this));
                });

                // checkout date set to 2018 to fit design brief, shows 2 month span
                checkoutDate = $('#checkoutDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
                .on("change", function() {
                    checkinDate.datepicker("option", "maxDate", getDate(this));
                });

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }
                    return date;
                }
            });
        });
    </script>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
        .booking-form-input{display: flex; flex-direction: row; align-items: center; height: 2.5em;}
        p{padding-right: 0.5em;}
        .booking-form-input select{width:10em;}
        .booking-form-textarea{display: flex; flex-direction: row; align-items: flex-end;}
        .booking-form-textarea textarea{width:20em; height: 7em;}
        .booking-form-textarea p{margin-bottom: 0;}
        .booking-form-buttons{padding-top:1em;}
        .cancelbtn{text-decoration: underline;}
    </style>
</head>
<body>
    <?php
        include "checksession.php";
        checkUser();
        loginStatus(); 

        // assigns callable variable to database connection and provides error message if connection is unavailable
        include "config.php";
        $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MYSQL.". mysqli_connect_error();
            exit;
        };

        // if id exists 
        if ($_SERVER["REQUEST_METHOD"] == "GET"){
            $id = $_GET['id'];
            // if its empty or not a numerical data
            if(empty($id) or !is_numeric($id)){
                echo '<h2>The booking ID is invalid.</h2>';
                exit;
            }
        }

        // function to remove unnecessary slashes, spaces, and converts special characters into html equivalents; common security measure
        function cleanInput($data){
            return htmlspecialchars(stripslashes((trim($data))));
        }

        if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')){
            $error = 0;
            $msg = "Error: ";

            if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
                $id = cleanInput($_POST['id']); 
            } else {
                $error++; 
                $msg .= 'Invalid ID '; 
                $id = 0;  
            };

            if (isset($_POST['roomname']) and !empty($_POST['roomname']) and is_string($_POST['roomname'])) {
                $roomname = cleanInput($_POST['roomname']); 
            } else {
                $error++; 
                $msg .= 'Invalid roomname '; 
                $roomname = ' ';  
            };

            if (isset($_POST['roomtype']) and !empty($_POST['roomtype']) and is_string($_POST['roomtype'])) {
                $roomtype = cleanInput($_POST['roomtype']); 
            } else {
                $error++; 
                $msg .= 'Invalid roomtype '; 
                $roomtype = ' ';  
            };

            if (isset($_POST['beds']) and !empty($_POST['beds']) and is_integer(intval($_POST['beds']))) {
                $beds = cleanInput($_POST['beds']); 
            } else {
                $error++; 
                $msg .= 'Invalid number of beds '; 
                $beds = 0;  
            };

            if (isset($_POST['checkinDate']) and !empty($_POST['checkinDate']) and is_string($_POST['checkinDate'])) {
                $checkinDate = cleanInput($_POST['checkinDate']); 
            } else {
                $error++;
                $msg .= 'Invalid check in date ';
                $checkinDate = ' ';  
            };

            if (isset($_POST['checkoutDate']) and !empty($_POST['checkoutDate']) and is_string($_POST['checkoutDate'])) {
                $checkoutDate = cleanInput($_POST['checkoutDate']); 
            } else {
                $error++;
                $msg .= 'Invalid check out date ';
                $checkoutDate = ' ';  
            };

            if (isset($_POST['contactNumber']) and !empty($_POST['contactNumber']) and is_string($_POST['contactNumber'])) {
                $contactNumber = cleanInput($_POST['contactNumber']); 
            } else {
                $error++; 
                $msg .= 'Invalid room ID '; 
                $contactNumber = ' ';  
            };

            if (isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])) {
                $extras = cleanInput($_POST['extras']); 
            } else {
                $error++; 
                $msg .= 'Invalid comment in extras'; 
                $extras = ' ';  
            };

            if (isset($_POST['roomReview']) and !empty($_POST['roomReview']) and is_string($_POST['roomReview'])) {
                $roomReview = cleanInput($_POST['roomReview']); 
            } else {
                $error++; 
                $msg .= 'Invalid room review '; 
                $roomReview = ' ';  
            };
            
            // $update = "UPDATE booking 
            // INNER JOIN room ON booking.roomID = room.roomID
            // SET booking.checkinDate=?, booking.checkoutDate=?, booking.contactNumber=?, booking.extras=?, booking.roomReview=?, room.roomname=?, room.roomtype=?, room.beds=?
            // WHERE bookingID=" .$id;

            $update = "UPDATE booking 
                SET checkinDate=?, checkoutDate=?, contactNumber=?, extras=?, roomReview=?
                WHERE bookingID=?";

            $stmt = mysqli_prepare($DBC, $update);
            mysqli_stmt_bind_param($stmt, 'sssssi', $checkinDate, $checkoutDate, $contactNumber, $extras, $roomReview, $id);

            if (!mysqli_stmt_execute($stmt)) {
                echo "<h2>Error: Booking could not be updated-> " .mysqli_error($DBC) ."</h2>";
            }else{
                echo "<h2>Booking updated successfully</h2>";
            }
            mysqli_stmt_close($stmt);
            
        }

        $query = 'SELECT booking.bookingID, booking.extras, booking.roomReview, booking.checkinDate, booking.checkoutDate, booking.contactNumber, room.roomname, room.roomtype, room.beds, room.roomID
        FROM booking
        INNER JOIN room ON booking.roomID = room.roomID
        WHERE booking.bookingID = ' .$id;

        $result = mysqli_query($DBC, $query);
        $rowcount = mysqli_num_rows($result);

        $roomquery = 'SELECT * FROM room';
        $roomresult = mysqli_query($DBC, $roomquery);
        $roomrowcount = mysqli_num_rows($roomresult);

        $customerquery = 'SELECT customerID, firstname, lastname FROM customer';
        $customerresult = mysqli_query($DBC, $customerquery);
        $customerrowcount = mysqli_num_rows($customerresult);
    ?>
    <div class="edit-booking-form">
        <h1>Edit a booking</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="editbooking.php">
            <!-- Typo fixed - removed extra 'for' -->
            <h3>Booking made for Test</h3>
            <?php
                if($rowcount > 0){
                    $row = mysqli_fetch_assoc($result);
                ?>
                <div class="booking-form-input">
                    <p><label for="roomID">Room (name, type, beds):</label></p>
                    <select id="roomID" name="roomID" required>
                        <?php
                            if ($roomrowcount > 0){
                                while($room_row = mysqli_fetch_assoc($roomresult)){
                                    if ($room_row['roomID'] == $row['roomID']) {
                                        echo '<option value="' .$room_row['roomID'] .'" selected>' .$room_row['roomname'] .', ' .$room_row['roomtype'] .', ' .$room_row['beds'] .'</option>' .PHP_EOL;
                                    } else {
                                        echo '<option value="' .$room_row['roomID'] .'">' .$room_row['roomname'] .', ' .$room_row['roomtype'] .', ' .$room_row['beds'] .'</option>' .PHP_EOL;
                                    }
                                }
                            }
                        ?>
                    </select>
                </div>
            
            <div class="booking-form-input">
                <p><label for="checkinDate">Checkin date:</label></p>
                <input id="checkinDate" name="checkinDate" type="text" maxlength="11" value="<?php echo $row['checkinDate'] ?>" required>
            </div>
            <div class="booking-form-input">
                <p><label for="checkoutDate">Checkout date:</label></p>
                <input id="checkoutDate" name="checkoutDate" type="text" maxlength="11" value="<?php echo $row['checkoutDate'] ?>" required>
            </div>
            <div class="booking-form-input">
                <p><label for="contactNumber">Contact number:</label></p>
                <input type="phone" id="contactNumber" name="contactNumber" pattern="\(\d{3}\) \d{3} \d{4}" value="<?php echo $row['contactNumber'] ?>"minlength="14" required>
            </div>
            <div class="booking-form-textarea">
                <p><label for="extras">Booking extras:</label></p>
                <textarea id="extras" name="extras" maxlength="255" required><?php echo $row['extras'] ?></textarea>
            </div>
            <div class="booking-form-textarea">
                <p><label for="roomReview">Room review:</label></p>
                <textarea id="roomReview" name="roomReview"><?php echo $row['roomReview'] ?></textarea>
            </div>
            <input type="hidden" name="id" value="<?php echo $id ?>" >
            
            <div class="booking-form-buttons">
                <input type="submit" name="submit" value="Update">
                <a href="listbookings.php" class="cancelbtn">[Cancel]</a>
            </div> 
        </form>
        <?php
                }
                mysqli_free_result($result);
                mysqli_close($DBC);
            ?>
    </div>
</body>
</html>