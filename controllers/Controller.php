<?php

class Controller {
    
    protected $title;

    public static function view($name, $title, array $args) {
        if(isset($_SESSION['email'])) {
            $pageName = $title;
            require_once 'templates/header.php';
            require_once 'views/'.$name.'.php';
            require_once 'templates/footer.php';
        } else {
            self::viewStatic("404");
        }
    }   
    
    public static function viewStatic($name) {
        require_once 'views/'.$name.'.php';
    }   
}