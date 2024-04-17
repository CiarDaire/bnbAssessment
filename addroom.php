<!DOCTYPE HTML>
<html>
<head>
    <title>Add a new room</title> 
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
    //function to clean input but not validate type and content
    function cleanInput($data) {  
      return htmlspecialchars(stripslashes(trim($data)));
    }

    //the data was sent using a formtherefore we use the $_POST instead of $_GET
    //check if we are saving data first by checking if the submit button exists in the array
    if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Add')) {
    //if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
        include "config.php"; //load in any variables
        $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

        if (mysqli_connect_errno()) {
            echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
            exit; //stop processing the page further
        };

    //validate incoming data - only the first field is done for you in this example - rest is up to you do
    //roomname
        $error = 0; //clear our error flag
        $msg = 'Error: ';
        if (isset($_POST['roomname']) and !empty($_POST['roomname']) and is_string($_POST['roomname'])) {
          $fn = cleanInput($_POST['roomname']); 
          $roomname = (strlen($fn)>50)?substr($fn,1,50):$fn; //check length and clip if too big
          //we would also do context checking here for contents, etc       
        } else {
          $error++; //bump the error flag
          $msg .= 'Invalid roomname '; //append eror message
          $roomname = '';  
        } 
    
    //description
          $description = cleanInput($_POST['description']);        
    //roomtype
          $roomtype = cleanInput($_POST['roomtype']);            
    //beds    
          $beds = cleanInput($_POST['beds']);        
          
    //save the room data if the error flag is still clear
        if ($error == 0) {
            $query = "INSERT INTO room (roomname,description,roomtype,beds) VALUES (?,?,?,?)";
            $stmt = mysqli_prepare($DBC,$query); //prepare the query
            mysqli_stmt_bind_param($stmt,'sssd', $roomname, $description, $roomtype,$beds); 
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);    
            echo "<h2>New room added to the list</h2>";        
        } else { 
          echo "<h2>$msg</h2>".PHP_EOL;
        }      
        mysqli_close($DBC); //close the connection once done
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
        <h1>Add a new room</h1>
        <h2><a href='listrooms.php'>[Return to the room listing]</a><a href='index.php'>[Return to the main page]</a></h2>

        <form method="POST" action="addroom.php">
          <p>
            <label for="roomname">Room name: </label>
            <input type="text" id="roomname" name="roomname" minlength="5" maxlength="50" required> 
          </p> 
          <p>
            <label for="description">Description: </label>
            <input type="text" id="description" size="100" name="description" minlength="5" maxlength="200" required> 
          </p>  
          <p>  
            <label for="roomtype">Room type: </label>
            <input type="radio" id="roomtype" name="roomtype" value="S"> Single 
            <input type="radio" id="roomtype" name="roomtype" value="D" Checked> Double 
          </p>
          <p>
            <label for="beds">Beds (1-5): </label>
            <input type="number" id="beds" name="beds" min="1" max="5" value="1" required> 
          </p> 
          
          <input type="submit" name="submit" value="Add">
        </form>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>
  