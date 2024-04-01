<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/edit room review based on booking</title>
    <link rel="stylesheet" href="/resources/demos/style.css">
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
    <div class="manage-review-form">
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