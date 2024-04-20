<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. -->

<!DOCTYPE HTML>
<html>
<head>
    <title>Edit a booking</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
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
</head>
<body>
    <?php
    include 'checksession.php';
    checkUser();
    if (isset($_POST['logout'])) {
        logout();
        header("Location: login.php");
        exit();
    }
    
    // import function to clean inputs
    include 'cleanInput.php';
    // import database connection
    include 'dbcConnect.php';

    // if id exists 
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
        $id = $_GET['id'];
        // if its empty or not a numerical data
        if(empty($id) or !is_numeric($id)){
            echo '<h2>The booking ID is invalid.</h2>';
            exit;
        }
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

        if (isset($_POST['roomID']) and !empty($_POST['roomID']) and is_integer(intval($_POST['roomID']))) {
            $roomID = cleanInput($_POST['roomID']);
        } else {
            $error++;
            $msg .= 'Invalid room selection ';
            $roomID = 0;
        }

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

        $update = "UPDATE booking 
            SET checkinDate=?, checkoutDate=?, contactNumber=?, extras=?, roomReview=?, roomID=?
            WHERE bookingID=?";

        $stmt = mysqli_prepare($DBC, $update);
        if ($stmt === false) {
            echo "<h2>Error: Failed to prepare the statement -> " . mysqli_error($DBC) . "</h2>";
            exit;
        }
        mysqli_stmt_bind_param($stmt, 'sssssii', $checkinDate, $checkoutDate, $contactNumber, $extras, $roomReview, $roomID, $id);

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
            <li><a href="listrooms.php">Rooms</a></li>
            <li class="selected"><a href="listbookings.php">Bookings</a></li>
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
            <li><a href="privacy.php">Privacy Statement</a></li>
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
    </div>
</body>
</html>
