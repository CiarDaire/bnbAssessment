<!-- Current bookings. Assumption that user login required. 

Future iteration will likely include filtering options by customer and booking period data  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings list</title>
    <style>
        .return-links{display: flex; flex-direction: row;}
        .return-links a{text-decoration: underline;}
        table td a{text-decoration: underline;}
        table, th, td {border: 1px solid black;}
    </style>
</head>
<body>
    <div class="booking-list">
        <h1>Current bookings</h1>
        <div class="return-links">
            <h2><a href="addbooking.php">[Make a booking]</a><a href="/bnb/">[Return to the main page]</a></h2>
        </div>
        <table>
            <!-- Table will be filled with dummy data until bookings database has been established, and sql queries are added -->
            <tr>
                <th>Booking (room, dates)</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>
            <tr>
                <td>Kellie, 2018-09-15, 2018-09-19</td>
                <td>Jordan, Garrison</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
            <tr>
                <td>Kellie, 2018-09-20, 2018-09-23</td>
                <td>Walker, Irene</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
            <tr>
                <td>Herman, 2018-10-01, 2018-10-14</td>
                <td>Walker, Irene</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
            <tr>
                <td>Herman, 2018-10-16, 2018-10-20</td>
                <td>Sellers, Beverly</td>
                <td>
                    <a href="viewbooking.php">[view]</a>
                    <a href="editbooking.php">[edit]</a>
                    <a href="managereviews.php">[manage reviews]</a>
                    <a href="deletebooking.php">[delete]</a>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>