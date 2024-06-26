<?php

session_start();

function checkUser() {
    $_SESSION['URI'] = '';
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1){
        return true;
    } else {
        $_SESSION['URI'] = $_SERVER['REQUEST_URI'];
        if($_SERVER['REQUEST_URI'] == '/bnb/listbookings.php') {
            return true;
        } else {
            header('Location: /bnb/login.php');
            exit();
        }
    }
}

function loginStatus(){
    $un = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 1){
        echo "<h6> logged in as $un </h6>";
    }else{
        echo "<h6>User is not logged in.</h6>";
    }
}

function login($id, $username){
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 0 && !empty($_SESSION['URI'])){
        $uri = $_SESSION['URI'];
    }else{
        $_SESSION['URI'] = '/bnb/index.php';
        $uri = $_SESSION['URI'];
    }

    header('location:/bnb/index.php', True, 303);
    $_SESSION['loggedin'] = 1;
    $_SESSION['userid'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['URI'] = '';
}

function logout(){
    $_SESSION['loggedin'] = 0;
    $_SESSION['userid'] = -1;
    $_SESSION['username'] = '';
    $_SESSION['URI'] = '';

    header('location:/bnb/login.php', True, 303);
}


?>