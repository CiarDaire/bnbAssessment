<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. 

I would also assume that the datepicker date range would cover from current day onwards to match with customers regardless of when they have logged in to make a booking, however for the purpose of matching the design brief in its entirely at this point, it has been changed so that its set in 2018. -->
<!DOCTYPE HTML>
<html>
<head>
    <title>Make a booking</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
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

            $.ajax({
                url: "roomsearch.php",
                method: "GET",
                data: { startDate: startDate, endDate: endDate },
                success: function(response) {
                    $('#filteredRooms').html(response);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
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
    include 'checksession.php';
    checkUser();
    if (isset($_POST['logout'])) {
        logout();
        exit();
    }
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
                $checkinDate = (strlen($cInD) < 11) ? substr($cInD,0,11): $cInD;
            } else {
                $error++;
                $msg .= 'Invalid checkin date';
                $checkinDate = '';
            }

            if(isset($_POST['checkoutDate']) and !empty($_POST['checkoutDate']) and is_string($_POST['checkoutDate'])){
                $cOutD = cleanInput($_POST['checkoutDate']);
                $checkoutDate = (strlen($cOutD) < 11) ? substr($cOutD,0,11): $cOutD;
            } else {
                $error++;
                $msg .= 'Invalid checkout date';
                $checkoutDate = '';
            }

            if(isset($_POST['contactNumber']) and !empty($_POST['contactNumber']) and is_string($_POST['contactNumber'])){
                $phone = cleanInput($_POST['contactNumber']);
                $contactNumber = (strlen($phone) < 15) ? substr($phone,0,15): $phone;
            } else {
                $error++;
                $msg .= 'Invalid phone number';
                $contactNumber = '';
            }

            if(isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])){
                $ex = cleanInput($_POST['extras']);
                $extras = (strlen($ex) < 255) ? substr($ex,0,255): $ex;
            } else {
                $error++;
                $msg .= 'Invalid comment';
                $extras = '';
            }

            if($error == 0){
                $roomID = cleanInput($_POST['roomID']);
                $customerID = cleanInput($_POST['customerID']);
                $query = "INSERT INTO booking (customerID, roomID, checkinDate, checkoutDate, contactNumber, extras) VALUES (?,?,?,?,?,?)";
                $stmt = mysqli_prepare($DBC, $query);
                mysqli_stmt_bind_param($stmt, "iissss", $customerID, $roomID, $checkinDate, $checkoutDate, $contactNumber, $extras, );
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

        $roomquery = 'SELECT * FROM room';
        $roomresult = mysqli_query($DBC, $roomquery);
        $roomrowcount = mysqli_num_rows($roomresult);

        $customerquery = 'SELECT customerID, firstname, lastname FROM customer';
        $customerresult = mysqli_query($DBC, $customerquery);
        $customerrowcount = mysqli_num_rows($customerresult);
    ?>
    <div id="main">
    <div id="header">
        <div id="logo">
        <div id="logo_text">
            <h1><a href="index.html"><span class="logo_colour">Ongaonga Bed & Breakfast</span></a></h1>
            <h2>Make yourself at home is our slogan. We offer some of the best beds on the east coast. Sleep well and rest well.</h2>
        </div>
        </div>
        <div id="menubar">
        <ul id="menu">
          <li><a href="index.php">Home</a></li>
          <li class="selected"><a href="listrooms.php">Rooms</a></li>
          <li><a href="listbookings.php">Bookings</a></li>
          <li><a href="listcustomers.php">Customers</a></li>
        </ul>
        </div>
    </div>
    <div id="site_content">
        <div class="sidebar">
        <?php
            loginStatus();
        ?>
        <form method="POST">
            <input  type="submit" name="logout" value="Logout">   
        </form> 
        <h3>Latest News</h3>
        <h4>New Website Launched</h4>
        <h5>July 1st, 2014</h5>
        <p>2014 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href="#">Read more</a></p>
        <p></p>
        <h4>New Website Launched</h4>
        <h5>July 1st, 2014</h5>
        <p>2014 sees the redesign of our website. Take a look around and let us know what you think.<br /><a href="#">Read more</a></p>
        <h3>Useful Links</h3>
        <ul>
            <li><a href="#">Whitecliffe Tech</a></li>
            <li><a href="#">iQualify</a></li>
            <li><a href="#">no link</a></li>
            <li><a href="#">Privacy Statement</a></li>
        </ul>
        <h3>Search</h3>
        <form method="post" action="#" id="search_form">
            <p>
            <input class="search" type="text" name="search_field" value="Enter keywords....." />
            <input name="search" type="image" style="border: 0; margin: 0 0 -9px 5px;" src="converted template/style/search.png" alt="Search" title="Search" />
            </p>
        </form>
        </div>
        <div id="content">
        <h1>Make a booking</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="<?php echo ($_SERVER['PHP_SELF']) ?>">
            <h3>Booking for Test</h3>
            <!-- Permission granted to add customer selection field -->
            <div class="booking-form-input">
                <p><label for="customerID">Customer:</label></p>
                <select id="customerID" name="customerID" required>
                    <?php
                        if($customerrowcount > 0){
                            while($row = mysqli_fetch_assoc($customerresult)){
                                echo '<option value=" ' .$row['customerID'] .'">' .$row['lastname'] .', ' .$row['firstname'] .'</option>' .PHP_EOL;
                        }}
                    ?>
                </select>
            </div>
            <div class="booking-form-input">
                <p><label for="roomID">Room (name, type, beds):</label></p>
                <select id="roomID" name="roomID" required>
                    <?php
                        if($roomrowcount > 0){
                            while($row = mysqli_fetch_assoc($roomresult)){
                                echo '<option value=" ' .$row['roomID'] .'">' .$row['roomname'] .', ' .$row['roomtype'] .', ' .$row['beds'] .'</option>' .PHP_EOL;
                        }}
                    ?>
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
    <p><label for="startDate">Start date:</label>
    <input id="startDate" name="startDate" type="text" required></p>
    <p><label for="endDate">End date:</label>
    <input id="endDate" name="endDate" type="text" required></p>
    <p><input type="button" value="Search availability" onclick="filterRooms()"><p>
    <table id="filteredRooms" border='1'>
        <thead>
            <tr>
                <th>Room # </th>
                <th>Roomname</th>
                <th>Room Type</th>
                <th>Beds</th>
            </tr>
        </thead>
    </table>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>
