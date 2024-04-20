<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. -->
<!DOCTYPE HTML>
<html>
<head>
<title>Add/edit room review based on booking</title>
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

    if (isset($_POST['submit']) and !empty($_POST['id']) and ($_POST['submit'] == 'Update')){
        $id = cleanInput($_POST['id']);
        $roomReview = cleanInput($_POST['roomReview']);

        $update = "UPDATE booking SET booking.roomReview=?
        WHERE bookingID=?";

        $stmt = mysqli_prepare($DBC, $update);
        mysqli_stmt_bind_param($stmt, 'si', $roomReview, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Room review updated successfully</h2>"; 
    }

    $query = 'SELECT booking.bookingID, booking.roomReview
    FROM booking
    WHERE booking.bookingID = ' .$id;

    $result = mysqli_query($DBC, $query);
    $row = mysqli_fetch_assoc($result);

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
        <h1>Edit/add room review</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="managereviews.php">
            <h3>Review made by Test</h3>
            <div class="booking-form-textarea">
                <p><label for="roomReview">Room review:</label></p>
                <textarea id="roomReview" name="roomReview"><?php echo $row['roomReview'] ?></textarea>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="booking-form-buttons">
                <button type="submit" name="submit" value="Update">Update</button>
            </div>
        </form>
    </div>
</body>
</html>
