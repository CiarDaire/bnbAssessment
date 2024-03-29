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
    <div class="manage-review-form">
        <h1>Edit/add room review</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="editbooking.php">
            <h3>Review made by Test</h3>
            <div class="booking-form-textarea">
                <p><label for="extras">Room review:</label></p>
                <textarea id="extras" name="extras">nothing</textarea>
            </div>
            <div class="booking-form-buttons">
                <button type="submit" name="submit">Update</button>
            </div>
        </form>
    </div>
</body>
</html>