<!DOCTYPE HTML>
<html>
<head>
    <title>Delete Customer</title>
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
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Delete')) {     
        $error = 0; //clear our error flag
        $msg = 'Error: ';  
    //customerID (sent via a form it is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
        $id = cleanInput($_POST['id']); 
        } else {
        $error++; //bump the error flag
        $msg .= 'Invalid Customer ID '; //append error message
        $id = 0;  
        }        
        
    //save the customer data if the error flag is still clear and customer id is > 0
        if ($error == 0 and $id > 0) {
            $query = "DELETE FROM customer WHERE customerID=?";
            $stmt = mysqli_prepare($DBC,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'i', $id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>Customer details deleted.</h2>";     
            
        } else { 
        echo "<h2>$msg</h2>".PHP_EOL;
        }      

    }

    //prepare a query and send it to the server
    //NOTE for simplicity purposes ONLY we are not using prepared queries
    //make sure you ALWAYS use prepared queries when creating custom SQL like below
    $query = 'SELECT * FROM customer WHERE customerid='.$id;
    $result = mysqli_query($DBC,$query);
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
            <h1>Customer details preview before deletion</h1>
            <h2><a href='listcustomers.php'>[Return to the Customer listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>
            <?php

            //makes sure we have the customer
            if ($rowcount > 0) {  
            echo "<fieldset><legend>Customer detail #$id</legend><dl>"; 
            $row = mysqli_fetch_assoc($result);
            echo "<dt>Name:</dt><dd>".$row['firstname']."</dd>".PHP_EOL;
            echo "<dt>Lastname:</dt><dd>".$row['lastname']."</dd>".PHP_EOL;
            echo "<dt>Email:</dt><dd>".$row['email']."</dd>".PHP_EOL;
            echo "<dt>Password:</dt><dd>".$row['password']."</dd>".PHP_EOL; 
            echo '</dl></fieldset>'.PHP_EOL;  
            ?><form method="POST" action="deletecustomer.php">
                <h2>Are you sure you want to delete this customer?</h2>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" name="submit" value="Delete">
                <a href="listcustomers.php">[Cancel]</a>
                </form>
            <?php    
            } else echo "<h2>No Customer found, possbily deleted!</h2>"; //suitable feedback

            mysqli_free_result($result); //free any memory used by the query
            mysqli_close($DBC); //close the connection once done
            ?>
            </table>
        </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>

