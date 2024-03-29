<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a booking</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // sets default date format to yy-mm-dd for following fields
            var dateFormat = 'yy-mm-dd';
            $.datepicker.setDefaults({
                dateFormat: dateFormat
            });

            // checkin date set to 2018 to fit design brief, shows 2 month span
            checkinDate = $('#checkinDateInput').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                checkoutDate.datepicker("option", "minDate", getDate(this));
            });

            // checkour date set to 2018 to fit design brief, shows 2 month span
            checkoutDate = $('#checkoutDateInput').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
            .on("change", function() {
                checkinDate.datepicker("option", "maxDate", getDate(this));
            });
        });

    // clears dates...?
    // $(".ui-datepicker-close").on("click", function() {
    //     $("#checkinDateInput").clearDate();
    //     $("#checkoutDateInput").clearDate();
    //   });
    </script>
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
    <div class="edit-booking-form">
        <h1>Edit a booking</h1>
        <div class="return-links">
            <h2><a href="listbookings.php">[Return to the Bookings listing]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <form method="POST" action="editbooking.php">
            <!-- Suggest a typo fix for this heading -->
            <h3>Booking made for for Test</h3>
            <!-- Once backend quieries are established, options will be replaced with room db data, provided here is the first default option -->
            <div class="booking-form-input">
                <p><label for="room">Room (name, type, beds):</label></p>
                <select id="room" name="room" required>
                    <option value="room1">Kellie, S, 5</option>
                </select>
            </div>
            <div class="booking-form-input">
                <p><label for="checkinDate">Checkin date:</label></p>
                <input id="checkinDateInput" name="checkinDateInput" type="text" maxlength="10" value="15/09/2018" required>
            </div>
            <div class="booking-form-input">
                <p><label for="checkoutDate">Checkout date:</label></p>
                <input id="checkoutDateInput" name="checkoutDateInput" type="text" maxlength="10" value="19/09/2018" required>
            </div>
            <div class="booking-form-input">
                <p><label for="contactNumber">Contact number:</label></p>
                <input type="phone" id="contactNumber" name="contactNumber" pattern="\(\d{3}\) \d{3} \d{4}" value="(001) 123 1234" maxlength="14" required>
            </div>
            <div class="booking-form-textarea">
                <p><label for="extras">Booking extras:</label></p>
                <textarea id="extras" name="extras" maxlength="255" required>nothing</textarea>
            </div>
            <div class="booking-form-buttons">
                <button type="submit" name="submit">Update</button>
                <a href="listbookings.php" class="cancelbtn">[Cancel]</a>
            </div>
        </form>
    </div>
</body>
</html>