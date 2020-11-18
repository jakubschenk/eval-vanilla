<?php
if (isset($_SESSION['admin'])) {
    require_once 'views/administrace.php';
} else {
    $redirect_url = '/administrace/login';
    header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
}

if(isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    $redirect_url = '/administrace';
    header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
}