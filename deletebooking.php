<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. 

Delete booking page ONLY available to bookings with upcoming dates; dates prior to current will disable the deletion form. As the listbookings.php page shows the CURRENT bookings, I assume prior bookings will be automatically removed and we will need to include a new page to show old bookings for legal purposes. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete a booking</title>
    </script>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
        .detail{margin-left: 2.5em;}
        fieldset{width:50%;}
        p{height: 0.5em;}
        .booking-form-buttons{padding-top:1em;}
        .cancelbtn{text-decoration: underline;}
    </style>
</head>
<body>
    <div class="booking-form">
        <h1>Booking preview before deletion</h1>
        <div class="return-links">
            <!-- Would suggest a replacement to "Bookings" to keep it consistent with the other pages -->
            <h2><a href="listbookings.php">[Return to the booking listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <fieldset>
            <!-- Assumption: booking detail number will be replaced with the bookingID value -->
            <legend>Booking detail #2</legend>
            <!-- Details will be replaced with respective booking data from backend -->
            <p class="field">Room name:</p>
            <p class="detail">Kellie</p>
            <p class="field">Checkin date:</p>
            <p class="detail">2018-09-15</p>
            <p class="field">Checkout date:</p>
            <p class="detail">2018-09-19</p>
        </fieldset>
        <form method="POST" action="deletebooking.php">
            <h2>Are you sure you want to delete this Booking?</h2>
            <div class="booking-form-buttons">
                <button type="submit" name="submit">Delete</button>
                <a href="listbookings.php" class="cancelbtn">[Cancel]</a>
            </div>
        </form>
    </div>
</body>
</html>