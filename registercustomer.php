<!DOCTYPE HTML>
<html>
<head>
    <title>Ongaonga Bed & Breakfast: Register Customer</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
<?php
  //function to clean input but not validate type and content
  function cleanInput($data) {  
    return htmlspecialchars(stripslashes(trim($data)));
  }

  //the data was sent using a formtherefore we use the $_POST instead of $_GET
  //check if we are saving data first by checking if the submit button exists in the array
  if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
  //if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test    
      include "config.php"; //load in any variables
      $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

      if (mysqli_connect_errno()) {
          echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
          exit; //stop processing the page further
      };

  //validate incoming data - only the first field is done for you in this example - rest is up to you do
  //firstname
      $error = 0; //clear our error flag
      $msg = 'Error: ';
      if (isset($_POST['firstname']) and !empty($_POST['firstname']) and is_string($_POST['firstname'])) {
        $fn = cleanInput($_POST['firstname']); 
        $firstname = (strlen($fn)>50)?substr($fn,1,50):$fn; //check length and clip if too big
        //we would also do context checking here for contents, etc       
      } else {
        $error++; //bump the error flag
        $msg .= 'Invalid firstname '; //append eror message
        $firstname = '';  
      } 

      if (isset($_POST['lastname']) and !empty($_POST['lastname']) and is_string($_POST['lastname'])) {
        $ln = cleanInput($_POST['lastname']);
        $lastname = (strlen($ln)>50)?substr($ln,1,50):$ln;      
      } else {
        $error++; 
        $msg .= 'Invalid lastname '; 
        $lastname = '';  
      } 

      if (isset($_POST['email']) and !empty($_POST['email']) and is_string($_POST['email'])) {
        $em = cleanInput($_POST['email']);
        $email = (strlen($em)>50)?substr($em,1,50):$em;      
      } else {
        $error++; 
        $msg .= 'Invalid email address '; 
        $email = '';  
      } 

      if (isset($_POST['password']) and !empty($_POST['password']) and is_string($_POST['password'])) {
        $password = cleanInput($_POST['password']);    
      } else {
        $error++; 
        $msg .= 'Invalid password '; 
        $password = '';  
      } 

  //save the customer data if the error flag is still clear
      if ($error == 0) {
          $query = "INSERT INTO customer (firstname,lastname,email,password) VALUES (?,?,?,?)";
          $stmt = mysqli_prepare($DBC,$query); //prepare the query
          mysqli_stmt_bind_param($stmt,'ssss', $firstname, $lastname, $email,$password); 
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);    
          echo "<h2>customer saved</h2>";        
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
            <li><a href="listrooms.php">Rooms</a></li>
            <li class="selected"><a href="registercustomer.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
        </div>
    </div>
    <div id="site_content">
        <div class="sidebar">
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
        <h1>New Customer Registration</h1>
        <!-- <h2><a href='listcustomers.php'>[Return to the Customer listing]</a> -->
        <h2><a href='/bnb/'>[Return to the main page]</a></h2>

        <form method="POST" action="registercustomer.php">
          <p>
             <!-- Name changed to First Name for usability -->
            <label for="firstname">First Name: </label>
            <input type="text" id="firstname" name="firstname" minlength="5" maxlength="50" required> 
          </p> 
          <p>
            <!-- Name changed to Last Name for usability -->
            <label for="lastname">Last Name: </label>
            <input type="text" id="lastname" name="lastname" minlength="5" maxlength="50" required> 
          </p>  
          <p>  
            <label for="email">Email: </label>
            <input type="email" id="email" name="email" maxlength="100" size="50" required> 
          </p>
          <p>
            <label for="password">Password: </label>
            <input type="password" id="password" name="password" minlength="8" maxlength="32" required> 
          </p> 
          
          <input type="submit" name="submit" value="Register">
        </form>
        </div>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>
