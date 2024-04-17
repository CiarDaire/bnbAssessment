<!DOCTYPE html>
<html lang="en">
<head>
    <title>Privacy Statement</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
</head>
<body>
<?php
    include 'checksession.php';
    if (isset($_POST['logout'])) {
        logout();
        exit();
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
        <div id="content">
            <h1>Our Privacy Statement</h1>
    <p>We collect personal information from you, including information about your:</p>
    <ul>
        <li>name</li>
        <li>contact information</li>
        <li>location</li>
    </ul>
    <p>We collect your personal information in order to:</p>
    <ul>
        <li>create and manage bookings.</li>
    </ul>
    <p>Providing some information is optional. If you choose not to enter your name or contact details, we'll be unable to authenticate your booking or contact you regarding your booking.</p>
    <p>We keep your information safe by storing it in an encrypted database and only allow our owners and staff to access necessary information.</p>
    <p>We keep your information for as long as the account remains active, at which point we will securely destroy it by erasing all data once we receive no confirmation that you are still using the account.</p>
    <p>You have the right to ask for a copy of any personal information we hold about you, and to ask for it to be corrected if you think it is wrong. If you’d like to ask for a copy of your information, or to have it corrected, please contact us at <a href="mailto:ongaongab&b@gmail.com">ongaongab&b@gmail.com</a>, or (09) 123 4567, or 9 City Street, Auckland.</p>
    </div>
    </div>
    <div id="footer">
        Copyright &copy; black_white | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Free CSS Templates</a>
    </div>
    </div>
</body>
</html>