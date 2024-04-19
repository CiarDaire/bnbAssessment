<!DOCTYPE HTML>
<html>
<head>
    <title>Edit a room</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
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

    //retrieve the roomid from the URL
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) or !is_numeric($id)) {
            echo "<h2>Invalid room ID</h2>"; //simple error feedback
            exit;
        } 
    }
    //the data was sent using a formtherefore we use the $_POST instead of $_GET
    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {  
      $error = 0;   
      $msg = "Error: ";
    //validate incoming data - only the first field is done for you in this example - rest is up to you do
        
    //roomID (sent via a form ti is a string not a number so we try a type conversion!)    
        if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
          $id = cleanInput($_POST['id']); 
        } else {
          $error++; //bump the error flag
          $msg .= 'Invalid room ID '; //append error message
          $id = 0;  
        }   
    //roomname
          $roomname = cleanInput($_POST['roomname']); 
    //description
          $description = cleanInput($_POST['description']);        
    //roomtype
          $roomtype = cleanInput($_POST['roomtype']);         
    //beds
          $beds = cleanInput($_POST['beds']);         
        
    //save the room data if the error flag is still clear and room id is > 0
        if ($error == 0 and $id > 0) {
            $query = "UPDATE room SET roomname=?,description=?,roomtype=?,beds=? WHERE roomID=?";
            $stmt = mysqli_prepare($DBC,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'ssssi', $roomname, $description, $roomtype, $beds, $id); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>Room details updated.</h2>";     
    //        header('Location: http://localhost/bit608/listrooms.php', true, 303);      
        } else { 
          echo "<h2>$msg</h2>".PHP_EOL;
        }      
    }
    //locate the room to edit by using the roomID
    //we also include the room ID in our form for sending it back for saving the data
    $query = 'SELECT roomID,roomname,description,roomtype,beds FROM room WHERE roomid='.$id;
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
            <li class="selected"><a href="listrooms.php">Rooms</a></li>
            <li><a href="listbookings.php">Bookings</a></li>
            <li><a href="listcustomers.php">Customers</a></li>
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
        <h1>Room Details Update</h1>
        <h2><a href='listrooms.php'>[Return to the room listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>

        <form method="POST" action="editroom.php">
          <input type="hidden" name="id" value="<?php echo $id;?>">
          <p>
            <label for="roomname">Room name: </label>
            <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" value="<?php echo $row['roomname']; ?>" required> 
          </p> 
          <p>
            <label for="description">Description: </label>
            <input type="text" id="description" name="description" size="100" minlength="5" maxlength="200" value="<?php echo $row['description']; ?>" required> 
          </p>  
          <p>  
            <label for="roomtype">Room type: </label>
            <input type="radio" id="roomtype" name="roomtype" value="S" <?php echo $row['roomtype']=='S'?'Checked':''; ?>> Single 
            <input type="radio" id="roomtype" name="roomtype" value="D" <?php echo $row['roomtype']=='D'?'Checked':''; ?>> Double 
          </p>
          <p>
            <label for="beds">Beds (1-5): </label>
            <input type="number" id="beds" name="beds" min="1" max="5" value="1" value="<?php echo $row['beds']; ?>" required> 
          </p> 
          <input type="submit" name="submit" value="Update">
        </form>
        <?php 
        } else { 
          echo "<h2>room not found with that ID</h2>"; //simple error feedback
        }
        mysqli_close($DBC); //close the connection once done
        ?>
        </div>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>

  