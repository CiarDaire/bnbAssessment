<!DOCTYPE HTML>
<html>
<head>
    <title>Browse rooms</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    
    include 'checksession.php';
    
    if (isset($_POST['logout'])) {
        logout();
        
        exit();
    }
    
    // import database connection
    include 'dbcConnect.php';

    //prepare a query and send it to the server
    $query = 'SELECT roomID,roomname,roomtype FROM room ORDER BY roomtype';
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
            <?php
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ==1){
            ?>
                <li><a href="index.php">Home</a></li>
                <li class="selected"><a href="listrooms.php">Rooms</a></li>
                <li><a href="listbookings.php">Bookings</a></li>
                <li><a href="listcustomers.php">Customers</a></li>
            ?>
            <?php
            }else{
            ?>
                <li><a href="index.php">Home</a></li>
                <li class="selected"><a href="listrooms.php">Rooms</a></li>
                <li><a href="registercustomer.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
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
            <h1>Room list</h1>
            <?php
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ==1){
            ?>
                <h2><a href='addroom.php'>[Add a room]</a><a href="/bnb/">[Return to main page]</a></h2>
            <?php
            }else{
            ?>
            <h2><a href="/bnb/">[Return to main page]</a></h2>
            <?php
            }
            ?>
            <table border="1">
            <thead><tr><th>Room Name</th><th>Type</th><th>Action</th></tr></thead>
            <?php

            //makes sure we have rooms
            if ($rowcount > 0) {  
                while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['roomID'];	
                echo '<tr><td>'.$row['roomname'].'</td><td>'.$row['roomtype'].'</td>';
                echo '<td><a href="viewroom.php?id='.$id.'">[view]</a>';
                if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ==1){
                    echo '<a href="editroom.php?id='.$id.'">[edit]</a>';
                    echo '<a href="deleteroom.php?id='.$id.'">[delete]</a></td>';
                }
                echo '</tr>'.PHP_EOL;
            }
            } else echo "<h2>No rooms found!</h2>"; //suitable feedback

            mysqli_free_result($result); //free any memory used by the query
            mysqli_close($DBC); //close the connection once done
            ?>
            </table>
        </div>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>
  