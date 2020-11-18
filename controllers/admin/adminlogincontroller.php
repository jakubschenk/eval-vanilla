<?php

if (isset($_POST['login'])) {
    $admin_login = $_POST['login'];
    $admin_pass = $_POST['password'];
    $admin = Databaze::dotaz("SELECT heslo FROM administratori WHERE jmeno = ?", array($admin_login));
    print_r($admin);
    if (password_verify($admin_pass, $admin[0]['heslo'])) {
        $_SESSION['admin'] = true;
        $_SESSION['login'] = $admin_login;
        $redirect_url = '/administrace';
        header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
    } else {
        $login_url = '/administrace?badLogin';
        //header('Location: ' . filter_var($login_url, FILTER_SANITIZE_URL));
    }
} else {
    require_once 'views/administrace-login.php';
}

if (isset($_GET['badLogin'])) {
    require_once 'views/administrace-login.php';
}