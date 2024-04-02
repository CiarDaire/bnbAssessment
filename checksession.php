<?php

session_start();

function checkUser() {
    $_SESSION['URI'] = '';
    if($_SESSION['loggedin'] == 1){
        return True;
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
    $un = $_SESSION['username'];
    if($_SESSION['loggedin'] == 1){
        echo "<h6> logged in as $un </h6>";
    }
}

function login($id, $username){
    if($_SESSION['loggedin'] == 0 and !empty($_SESSION['URI'])){
        $uri = $_SESSION['URI'];
    }else{
        $_SESSION['URI'] = '/bnb/index.php';
        $uri = $_SESSION['URI'];
    }

    header('location:/bnb/login.php', True, 303);
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