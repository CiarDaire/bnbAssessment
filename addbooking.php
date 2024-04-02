<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. 

I would also assume that the datepicker date range would cover from current day onwards to match with customers regardless of when they have logged in to make a booking, however for the purpose of matching the design brief in its entirely at this point, it has been changed so that its set in 2018. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a booking</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        // for date picker and date range
        $(document).ready(function() {
            // sets default date format to yy-mm-dd for following fields
            var dateFormat = 'yy-mm-dd';
            $.datepicker.setDefaults({
                dateFormat: dateFormat
            });
            // checkin date set to 2018 to fit design brief, shows 2 month span
            checkinDate = $('#checkinDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                checkoutDate.datepicker("option", "minDate");
            });
            // checkout date set to 2018 to fit design brief, shows 2 month span
            checkoutDate = $('#checkoutDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                checkinDate.datepicker("option", "maxDate");
            });
            // search room availability start date
            startDate = $('#startDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                endDate.datepicker("option", "minDate");
            });
            // search room availability end date
            endDate = $('#endDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                startDate.datepicker("option", "maxDate");
            });
        });

        // Filter rooms by dates
        function filterRooms() {
            var xhttp = new XMLHttpRequest();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            xhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                $('#filteredRooms').html(this.responseText);
                }
            }
        
            xhttp.open("GET", "roomfilter.php?startDate=" + startDate + "&endDate=" + endDate, true);
            xhttp.send();
        }
    </script>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
        .submitbtn{margin-top: 1em;}
        .booking-form-input{display: flex; flex-direction: row; align-items: center; height: 2.5em;}
        p{padding-right: 0.5em;}
        .booking-form-input select{width:10em;}
        .booking-form-textarea{display: flex; flex-direction: row; align-items: flex-end;}
        .booking-form-textarea textarea{width:20em; height: 7em;}
        .booking-form-textarea p{margin-bottom: 0;}
        .booking-form-buttons{padding-top:1em;}
        .cancelbtn{text-decoration: underline;}
        .search{display: flex; flex-direction: row; align-items: center; height: 2.5em;}
    </style>
</head>
<body>
    <?php
        include "checksession.php";
        checkUser();
        loginStatus(); 
        
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

        if(isset($_POST["submit"]) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')){
            $error = 0;
            $msg = 'Error: ';

            if(isset($_POST['checkinDate']) and !empty($_POST['checkinDate']) and is_string($_POST['checkinDate'])){
                $cInD = cleanInput($_POST['checkinDate']);
                $checkinDate = (strlen($cInD) < 11) ? substr($cInD,1,11): $cInD;
            } else {
                $error++;
                $msg .= 'Invalid checkin date';
                $checkinDate = '';
            }

            if(isset($_POST['checkoutDate']) and !empty($_POST['checkoutDate']) and is_string($_POST['checkoutDate'])){
                $cOutD = cleanInput($_POST['checkoutDate']);
                $checkoutDate = (strlen($cOutD) < 11) ? substr($cOutD,1,11): $cOutD;
            } else {
                $error++;
                $msg .= 'Invalid checkout date';
                $checkoutDate = '';
            }

            if(isset($_POST['contactNumber']) and !empty($_POST['contactNumber']) and is_string($_POST['contactNumber'])){
                $phone = cleanInput($_POST['contactNumber']);
                $contactNumber = (strlen($phone) < 15) ? substr($phone,1,15): $phone;
            } else {
                $error++;
                $msg .= 'Invalid phone number';
                $contactNumber = '';
            }

            if(isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])){
                $ex = cleanInput($_POST['extras']);
                $extras = (strlen($ex) < 255) ? substr($ex,1,255): $ex;
            } else {
                $error++;
                $msg .= 'Invalid comment';
                $extras = '';
            }

            if($error == 0){
                $roomID = cleanInput($_POST['roomID']);
                $query = "INSERT INTO booking (roomID, checkinDate, checkoutDate, contactNumber, extras) VALUES (?,?,?,?,?)";
                $stmt = mysqli_prepare($DBC, $query);
                mysqli_stmt_bind_param($stmt, "issss", $roomID, $checkinDate, $checkoutDate, $contactNumber, $extras);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<h2>New booking has been added.</h2>";
                } else {
                    echo "<h2>Error adding booking: " . mysqli_error($DBC) . "</h2>";
                }
                mysqli_stmt_close($stmt);    
            } else {
                echo "<h2>$msg</h2>";
            }
            
            
        }

        $query = 'SELECT * FROM room';
        $result = mysqli_query($DBC, $query);
        $rowcount = mysqli_num_rows($result);

        mysqli_close($DBC);
        
    ?>
    <div class="booking-form">
        <h1>Make a booking</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="<?php echo ($_SERVER['PHP_SELF']) ?>">
            <h3>Booking for Test</h3>
            <div class="booking-form-input">
                <p><label for="roomID">Room (name, type, beds):</label></p>
                <select id="roomID" name="roomID" required>
                    <option value="1">yeah</option>
                </select>
            </div>
            <div class="booking-form-input">
                <p><label for="checkinDate">Checkin date:</label></p>
                <input id="checkinDate" name="checkinDate" type="text" placeholder="yyyy-mm-dd" maxlength="10" required>
            </div>
            <div class="booking-form-input">
                <p><label for="checkoutDate">Checkout date:</label></p>
                <input id="checkoutDate" name="checkoutDate" type="text" placeholder="yyyy-mm-dd" maxlength="10" required>
            </div>
            <div class="booking-form-input">
                <p><label for="contactNumber">Contact number:</label></p>
                <input type="phone" id="contactNumber" name="contactNumber" placeholder="(###) ### ####" pattern="\(\d{3}\) \d{3} \d{4}"  maxlength="14" required>
            </div>
            <div class="booking-form-textarea">
                <p><label for="extras">Booking extras:</label></p>
                <textarea id="extras" name="extras" maxlength="255" required></textarea>
            </div>
            <div class="booking-form-buttons">
                <input type="submit" name="submit" value="Add">
                <a href="listbookings.php" class="cancelbtn">[Cancel]</a>
            </div>
        </form>
        
    </div>
    <hr>
    <h3>Search for room availability</h3>
    <div class="search">
        <p><label for="startDate">Start date:</label></p>
        <input id="startDate" name="startDate" type="text" required>
        <p><label for="endDate">End date:</label></p>
        <input id="endDate" name="endDate" type="text" required>
        <p><input type="button" value="Search availability" onclick="filterRooms()"><p>
    </div>
    <table border='1'>
        <thead>
            <tr>
                <th>Room #</th>
                <th>Roomname</th>
                <th>Room type</th>
                <th>Beds</th>
            </tr>
        </thead>
        <?php
            if($rowcount > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $roomID = $row['roomID'];
                    echo '<tr><td>' .$row['roomID'] .'</td>';
                    echo '<td>'. $row['roomname'] .'</td>';
                    echo '<td>'. $row['roomtype'] .'</td>';
                    echo '<td>'. $row['beds'] .'</td></tr>' .PHP_EOL;
                }
            }
        ?>
    </table>
</body>
</html>