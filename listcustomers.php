<!-- Current bookings. Assumption that user login required. 

Future iteration will likely include filtering options by customer and booking period data  -->
<!DOCTYPE HTML>
<html>
<head>
    <title>Customers list</title>
    <meta name="description" content="website description" />
    <meta name="keywords" content="website keywords, website keywords" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="original template/style/style.css" title="style" />
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>

function searchResult(searchstr) {
  if (searchstr.length==0) {

    return;
  }
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
    //take JSON text from the server and convert it to JavaScript objects
    //mbrs will become a two dimensional array of our customers much like 
    //a PHP associative array
      var mbrs = JSON.parse(this.responseText);              
      var tbl = document.getElementById("tblcustomers"); //find the table in the HTML
      
      //clear any existing rows from any previous searches
      //if this is not cleared rows will just keep being added
      var rowCount = tbl.rows.length;
      for (var i = 1; i < rowCount; i++) {
         //delete from the top - row 0 is the table header we keep
         tbl.deleteRow(1); 
      }      
      
      //populate the table
      //mbrs.length is the size of our array
      for (var i = 0; i < mbrs.length; i++) {
         var mbrid = mbrs[i]['customerID'];
         var fn    = mbrs[i]['firstname'];
         var ln    = mbrs[i]['lastname'];
      
         //concatenate our actions urls into a single string
         var urls  = '<a href="viewcustomer.php?id='+mbrid+'">[view]</a>';
             urls += '<a href="editcustomer.php?id='+mbrid+'">[edit]</a>';
             urls += '<a href="deletecustomer.php?id='+mbrid+'">[delete]</a>';
         
         //create a table row with three cells  
         tr = tbl.insertRow(-1);
         var tabCell = tr.insertCell(-1);
             tabCell.innerHTML = fn; //firstname
         var tabCell = tr.insertCell(-1);
             tabCell.innerHTML = ln; //lastname      
         var tabCell = tr.insertCell(-1);
             tabCell.innerHTML = urls; //action URLS            
        }
    }
  }
  //call our php file that will look for a customer or customers matchign the seachstring
  xmlhttp.open("GET","customersearch.php?sq="+searchstr,true);
  xmlhttp.send();
}
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
    loginStatus(); 

    // import database connection
    include 'dbcConnect.php';

    // gets booking id, customers name, room name, and booking dates from database
    $query = 'SELECT booking.bookingID, customer.firstname, customer.lastname, room.roomname, booking.checkinDate, booking.checkoutDate
    FROM booking
    JOIN room ON booking.roomID = room.roomID
    JOIN customer ON booking.customerID = customer.customerID
    ORDER BY bookingID';

    $result = mysqli_query($DBC, $query);
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
            <h1>Customer List Search by Lastname</h1>
            <h2><a href='registercustomer.php'>[Create new Customer]</a><a href="/bnb/">[Return to main page]</a>
            </h2>
            <form>
            <label for="lastname">Lastname: </label>
            <input id="lastname" type="text" size="30" 
                    onkeyup="searchResult(this.value)" 
                    onclick="javascript: this.value = ''" 
                    placeholder="Start typing a last name">

            </form>
            <table id="tblcustomers" border="1">
            <thead><tr><th>Lastname</th><th>Firstname</th><th>actions</th></tr></thead>

            </table>
        </div>
</body>
</html>
  