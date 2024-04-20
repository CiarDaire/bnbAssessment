<!DOCTYPE HTML>
<html>
<head>
    <title>Delete Booking</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
</head>
<body>
    <?php
    // functions to handle login/logout sessions
    include 'checksession.php';

    checkUser();

    // logout event
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
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>

