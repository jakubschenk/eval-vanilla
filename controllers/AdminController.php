<?php

class AdminController extends Controller {

    public static function logout() {
        $_SESSION = array();
        session_destroy();
        $redirect_url = '/administrace';
        header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
    }

    public static function view($name, $title, array $args) {
        if (isset($_SESSION['admin'])) {
            $pageName = $title;
            require_once 'views/'. $name . '.php';
        } else {
            $redirect_url = '/administrace/login';
            header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
        }        
    }
}