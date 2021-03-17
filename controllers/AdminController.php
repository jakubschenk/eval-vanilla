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
        if (Administrator::authenticated()) {
            if(Config::getSkolniRok() == null) {
                if($_SERVER['REQUEST_URI'] == '/administrace/import' ||        //nechceme, aby někdo v administraci nastavoval věci
                    $_SERVER['REQUEST_URI'] == '/administrace/importing' ||    //když nemá zvolenou databázi, nebo dokonce žádnou!
                    $_SERVER['REQUEST_URI'] == '/administrace/nastaveni') {    //možnost zvolení již importované databáze
                    $pageName = $title;
                    require_once 'templates/adminHeader.php';
                    require_once 'views/'. $name . '.php';
                    require_once 'templates/adminFooter.php';
                } else {
                    header("Location: /administrace/import");
                }               
            } else {
                $pageName = $title;
                require_once 'templates/adminHeader.php';
                require_once 'views/'. $name . '.php';
                require_once 'templates/adminFooter.php';
            }
        } else {
            require_once 'views/AdministraceLogin.php';
        }        
    }

    public static function viewStatic($name) {
        require_once 'views/'.$name.'.php';
    }
}