<?php

class AdminController {

    public static function logout() {
        $_SESSION = array();
        session_destroy();
        $redirect_url = '/administrace';
        header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
    }

    public static function logoutWithoutRedirect() {
        $_SESSION = array();
        session_destroy();
        session_start();
    }

    public static function view($name, $title, array $args) {
        if (isset($_SESSION['admin'])) {
            $pageName = $title;
            require_once 'templates/adminHeader.php';
            require_once 'views/'. $name . '.php';
            require_once 'templates/adminFooter.php';
        } else {
            require_once 'views/AdministraceLogin.php';
        }        
    }

    public static function viewStatic($name) {
        require_once 'views/'.$name.'.php';
    }
}