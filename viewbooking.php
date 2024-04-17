<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. 

Details will be updated after any edits/updates have been made. -->
<!DOCTYPE HTML>
<html>
<head>
    <title>View a booking</title>
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
</head>
<body>
    <?php
    include 'checksession.php';
    checkUser();
    if (isset($_POST['logout'])) {
        logout();
        exit();
    }
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

    $query = 'SELECT booking.bookingID, room.roomname, booking.checkinDate, booking.checkoutDate, booking.contactNumber, booking.extras, booking.roomReview
    FROM booking
    INNER JOIN room ON booking.roomID = room.roomID
    WHERE booking.bookingID = ' .$id;

    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);
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
            <h1>Booking Details View</h1>
            <div class="return-links">
                <!-- Would suggest a replacement to "Bookings" to keep it consistent with the other pages -->
                <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
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
        </div>
        <div id="footer">
            Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
        </div>
    </div>
</body>
</html>


