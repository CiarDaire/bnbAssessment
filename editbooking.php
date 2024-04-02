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
            var dateFormat = 'yy-mm-dd';
            $.datepicker.setDefaults({
                dateFormat: dateFormat
            });

            // checkin date set to 2018 to fit design brief, shows 2 month span
            checkinDate = $('#checkinDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                checkoutDate.datepicker("option", "minDate", getDate(this));
            });

            // checkour date set to 2018 to fit design brief, shows 2 month span
            checkoutDate = $('#checkoutDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                checkinDate.datepicker("option", "maxDate", getDate(this));
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

        // function to remove unnecessary slashes, spaces, and converts special characters into html equivalents; common security measure
        function cleanInput($data){
            return htmlspecialchars(stripslashes((trim($data))));
        }

        if (isset($_POST['submit']) and !empty($_POST['id']) and ($_POST['submit'] == 'Update')){
            $id = cleanInput($_POST['id']);
            $roomname = cleanInput($_POST['roomname']);
            $roomtype = cleanInput($_POST['roomtype']);
            $beds = cleanInput($_POST['beds']);
            $checkinDate = cleanInput($_POST['checkinDate']);
            $checkoutDate = cleanInput($_POST['checkoutDate']);
            $contact = cleanInput($_POST['contactNumber']);
            $extra = cleanInput($_POST['extras']);
            $roomReview = cleanInput($_POST['roomReview']);

            $update = "UPDATE booking 
            INNER JOIN room ON booking.roomID = room.roomID
            SET booking.checkinDate=?, booking.checkoutDate=?, booking.contactNumber=?, booking.extras=?, booking.roomReview=?, room.roomname=?, room.roomtype=?, room.beds=? 
            WHERE bookingID=?";

            $stmt = mysqli_prepare($DBC, $update);
            mysqli_stmt_bind_param($stmt, 'ssisssssi', $roomname, $roomtype, $beds, $checkinDate, $checkoutDate, $contact, $extra, $roomReview, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<h2>Booking updated successfully</h2>";
        }

        $query = 'SELECT booking.bookingID, booking.extras, booking.roomReview, booking.checkinDate, booking.checkoutDate, booking.contactNumber, room.roomname, room.roomtype, room.beds
        FROM booking
        INNER JOIN room ON booking.roomID = room.roomID
        WHERE booking.bookingID = ' .$id;

        $result = mysqli_query($DBC, $query);
        $rowcount = mysqli_num_rows($result);
    ?>
    <div class="edit-booking-form">
        <h1>Edit a booking</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="editbooking.php">
            <!-- Typo fixed - removed extra 'for' -->
            <h3>Booking made for Test</h3>
            <div class="booking-form-input">
                <p><label for="room">Room (name, type, beds):</label></p>
                <select id="room" name="room" required>
                    <?php
                        if ($rowcount > 0){
                            $row = mysqli_fetch_assoc($result);
                    ?>
                    <option><?php echo $row['roomname'] .", " .$row['roomtype'] .", " .$row['beds'] ?></option>
                </select>
                <input type="hidden" id="roomname" name="roomname">
                <input type="hidden" id="roomtype" name="roomtype">
                <input type="hidden" id="beds" name="beds">
                <?php
                }else echo "<option> No booking found.</option>";
                ?>
            </div>
            <div class="booking-form-input">
                <p><label for="checkinDate">Checkin date:</label></p>
                <input id="checkinDate" name="checkinDate" type="text" maxlength="10" value="<?php echo $row['checkinDate'] ?>" required>
            </div>
            <div class="booking-form-input">
                <p><label for="checkoutDate">Checkout date:</label></p>
                <input id="checkoutDate" name="checkoutDate" type="text" maxlength="10" value="<?php echo $row['checkoutDate'] ?>" required>
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
            <input type="hidden" name=id value="<?php echo $id ?>" >
            <div class="booking-form-buttons">
                <input type="submit" name="submit" value="Update">
                <a href="listbookings.php" class="cancelbtn">[Cancel]</a>
            </div>
        </form>
    </div>
    <?php
    mysqli_free_result($result);
    mysqli_close($DBC);
    ?>
</body>
</html>