<!DOCTYPE HTML>
<html>
<head>
    <title>Ongaonga Bed & Breakfast: Login</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
</head>
<body>
<?php
    include "checksession.php";

    //simple logout
    if (isset($_POST['logout'])) {
        logout();
        
        exit();
    }
    
    if (isset($_POST['login']) and !empty($_POST['login']) and ($_POST['login'] == 'Login')) {

        // import database connection
        include 'dbcConnect.php';

        $error = 0; //clear our error flag
        $msg = 'Error: ';

        if (isset($_POST['username']) and !empty($_POST['username']) and is_string($_POST['username'])) {
            $un = htmlspecialchars(stripslashes(trim($_POST['username'])));  
            $username = (strlen($un)>32)?substr($un,1,32):$un; //check length and clip if too big       
        } else {
            $error++; //bump the error flag
            $msg .= 'Invalid username '; //append error message
            $username = '';  
        }                    
        
        if (isset($_POST['password']) and !empty($_POST['password']) and is_string($_POST['password'])) {
            $password = trim($_POST['password']);     
        } else {
            $error++; 
            $msg .= 'Invalid password '; 
            $username = '';  
        }  

        if ($error == 0) {
            $query = "SELECT customerID,password FROM customer 
            WHERE email = '$username' AND password = '$password'";

            $result = mysqli_query($DBC,$query); 

            if (mysqli_num_rows($result) == 1) { 
                $row = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                mysqli_close($DBC);  

                if ($password === $row['password'])          
                login($row['customerID'],$username);
                // header("Location: index.php");
                // exit();

            } echo "<h6>Login fail</h6>".PHP_EOL;   
        } else { 
            echo "<h6>$msg</h6>".PHP_EOL;
        }      
    }
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
        <?php
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ==1){
            ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="listrooms.php">Rooms</a></li>
                <li><a href="listbookings.php">Bookings</a></li>
                <li><a href="listcustomers.php">Customers</a></li>
            ?>
            <?php
            }else{
            ?>
                <li><a href="index.php">Home</a></li>
                <li><a href="listrooms.php">Rooms</a></li>
                <li><a href="registercustomer.php">Register</a></li>
                <li class="selected"><a href="login.php">Login</a></li>
            <?php
            }
            ?>
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
            <h1>Login</h1>
            <h2>
                <a href="registercustomer.php">[Create new customer]</a>
                <a href="index.php">[Return to main page]</a>
            </h2>
            <form method="POST">
                <p>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" maxlength="32" autocomplete="off"> 
                </p>

                <p>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" maxlength="32" autocomplete="off"> 
                </p>
                <input type="submit" name="login" value="Login">
                <input  type="submit" name="logout" value="Logout">   
            </form> 
        </div>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>