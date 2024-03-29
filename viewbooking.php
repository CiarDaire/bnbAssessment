<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. 

Details will be updated after any edits/updates have been made. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View a booking</title>
    </script>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
        .detail{margin-left: 2.5em;}
        fieldset{width:50%;}
        p{height: 0.5em;}
    </style>
</head>
<body>
    <div class="booking-form">
        <h3>Logged in as Test</h3>
        <h1>Booking Details View</h1>
        <div class="return-links">
            <!-- Would suggest a replacement to "Bookings" to keep it consistent with the other pages -->
            <h2><a href="listbookings.php">[Return to the booking listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <fieldset>
            <!-- Assumption: room detail number will be replaced with the bookingID value -->
            <!-- Suggest this is changed to Booking Detail Number (#bookingID) instead of Room Detail Number -->
            <legend>Room detail #2</legend>
            <!-- Details will be replaced with respective booking data -->
            <p class="field">Room name:</p>
            <p class="detail">Kellie</p>
            <p class="field">Checkin date:</p>
            <p class="detail">2018-09-15</p>
            <p class="field">Checkout date:</p>
            <p class="detail">2018-09-19</p>
            <p class="field">Contact number:</p>
            <p class="detail">(001) 123 1234</p>
            <p class="field">Extras:</p>
            <p class="detail">nothing</p>
            <p class="field">Room review:</p>
            <p class="detail">nothing</p>
        </fieldset>
    </div>
</body>
</html>