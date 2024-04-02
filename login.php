<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<?php
        include "checksession.php";
        loginStatus(); 

        //simple logout
        if (isset($_POST['logout'])) {
            logout();
            
            exit();
        }
        
        if (isset($_POST['login']) and !empty($_POST['login']) and ($_POST['login'] == 'Login')) {
            include "config.php"; //load in any variables
            $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE) or die();
        
        //validate incoming data - only the first field is done for you in this example - rest is up to you to do
        //firstname
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

    <h1>Login</h1>
    <h2>
        <a href="registercustomer.php">[Creat new customer]</a>
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
</body>
</html>