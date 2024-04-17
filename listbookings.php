<!-- Current bookings. Assumption that user login required. 

Future iteration will likely include filtering options by customer and booking period data  -->
<!DOCTYPE HTML>
<html>
<head>
    <title>Bookings list</title>
    <meta name="description" content="website description" />
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
                        echo '<tr><td>' .$row['roomname'] .', '. $row['checkinDate'] .', '. $row['checkoutDate'] .'</td>';
                        echo '<td>' .$row['lastname'] .', ' .$row['firstname'] .'</td>';
                        echo '<td> <a href="viewbooking.php?id='.$bookingID .'">[view]</a>';
                        
                        if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ==1){
                        echo '<a href="editbooking.php?id='.$bookingID .'">[edit]</a><a href="managereviews.php?id='.$bookingID .'">[manage reviews]</a><a href="deletebooking.php?id='.$bookingID .'">[delete]</a></td>';
                        }
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
        </div>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>
