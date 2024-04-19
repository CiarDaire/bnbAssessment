<!-- Assumes user is logged in, otherwise they will be redirected to login/registration page. -->

<!DOCTYPE HTML>
<html>
<head>
    <title>Edit Customer</title> 
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // sets default date format to yy-mm-dd for following fields
            $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
            });

            $(function(){
                // checkin date set to 2018 to fit design brief, shows 2 month span
                checkinDate = $('#checkinDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
                .on("change", function() {
                checkoutDate.datepicker("option", "minDate", getDate(this));
                });

                // checkout date set to 2018 to fit design brief, shows 2 month span
                checkoutDate = $('#checkoutDate').datepicker({ defaultDate: "2018-09-05", changeMonth: true, numberOfMonths: 2 })
                .on("change", function() {
                    checkinDate.datepicker("option", "maxDate", getDate(this));
                });

                function getDate(element) {
                    var date;
                    try {
                        date = $.datepicker.parseDate(dateFormat, element.value);
                    } catch (error) {
                        date = null;
                    }
                    return date;
                }
            });
        });
    </script>
</head>
<body>
    <?php
    include 'checksession.php';
    checkUser();
    if (isset($_POST['logout'])) {
        logout();
        exit();
    }
    
    // import function to clean inputs
    include 'cleanInput.php';
    // import database connection
    include 'dbcConnect.php';

    //retrieve the customerid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid Customer ID</h2>"; //simple error feedback
            exit;
        } 
    }
    //the data was sent using a formtherefore we use the $_POST instead of $_GET
    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {     
    //validate incoming data - only the first field is done for you in this example - rest is up to you do
        $error = 0; //clear our error flag
        $msg = 'Error: ';  
        
    //customerID (sent via a form ti is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
          $id = cleanInput($_POST['id']); 
        } else {
          $error++; //bump the error flag
          $msg .= 'Invalid Customer ID '; //append error message
          $id = 0;  
        }   
    //firstname
          $firstname = cleanInput($_POST['firstname']); 
    //lastname
          $lastname = cleanInput($_POST['lastname']);        
    //email
          $email = cleanInput($_POST['email']);         
        
    //save the customer data if the error flag is still clear and customer id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE customer SET firstname=?,lastname=?,email=? WHERE customerID=?";
            $stmt = mysqli_prepare($DBC,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'ssssi', $firstname, $lastname, $email,$username,$id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>customer details updated.</h2>";     
    //        header('Location: http://localhost/bit608/listcustomers.php', true, 303);      
        } else { 
          echo "<h2>$msg</h2>".PHP_EOL;
        }      
    }
    //locate the customer to edit by using the customerID
    //we also include the customer ID in our form for sending it back for saving the data
    $query = 'SELECT customerID,firstname,lastname,email FROM customer WHERE customerid='.$id;
    $result = mysqli_query($DBC,$query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {
      $row = mysqli_fetch_assoc($result);
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
            <li><a href="listbookings.php">Bookings</a></li>
            <li class="selected"><a href="listcustomers.php">Customers</a></li>
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
        <h1>Customer Details Update</h1>
        <h2><a href='listcustomers.php'>[Return to the Customer listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>

        <form method="POST" action="editcustomer.php">
          <input type="hidden" name="id" value="<?php echo $id;?>">
          <p>
            <label for="firstname">Name: </label>
            <input type="text" id="firstname" name="firstname" minlength="5" 
                  maxlength="50" required value="<?php echo $row['firstname']; ?>"> 
          </p> 
          <p>
            <label for="lastname">Name: </label>
            <input type="text" id="lastname" name="lastname" minlength="5" 
                  maxlength="50" required value="<?php echo $row['lastname']; ?>">  
          </p>  
          <p>  
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" maxlength="100" 
                  size="50" required value="<?php echo $row['email']; ?>"> 
          </p>

          <input type="submit" name="submit" value="Update">
        </form>
        <?php 
        } else { 
          echo "<h2>Customer not found with that ID</h2>"; //simple error feedback
        }
        mysqli_close($DBC); //close the connection once done
        ?>
    </div>
</body>
</html>

