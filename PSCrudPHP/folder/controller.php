<?php

require 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    if (isset($_POST['remember_me'])) {
        setcookie('remember_me', '1', time() + (86400 * 30), "/"); // 86400 = 1 day
    } else {
        if (isset($_COOKIE['remember_me'])) {
            unset($_COOKIE['remember_me']);
            setcookie('remember_me', '', time() - 3600, "/"); 
        }
    }

print_r($_REQUEST);


$regexemail = '/^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/m';
preg_match_all($regexemail, htmlspecialchars($_REQUEST['email']), $matchesEmail, PREG_SET_ORDER, 0);
$email = $matchesEmail ? htmlspecialchars($_REQUEST['email']) : exit();

$regexPass = '/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{6,})\S$/';
preg_match_all($regexPass, htmlspecialchars($_REQUEST['password']), $matchesPass, PREG_SET_ORDER, 0);
$pass = $matchesPass ? htmlspecialchars($_REQUEST['password']) : exit();



$sql = "SELECT * FROM users WHERE email = '" . $email . "'";
$res = $mysqli->query($sql); // return un mysqli result

if($row = $res->fetch_assoc()) { 
    if(password_verify($pass, $row['password'])){
        $_SESSION['userLogin'] = $row;
        session_write_close();
       
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Password errata!!!';
        header('Location: login.php');
    }
} else {
    $_SESSION['error'] = 'Email e Password errati!!!';
    header('Location: login.php');
}
}