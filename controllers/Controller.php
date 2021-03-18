<?php

class Controller {
    
    protected $title;

    public static function view($name, $title, array $args) {
        if(isset($_SESSION['email'])) {
            $pageName = $title;
            require_once $_SERVER["DOCUMENT_ROOT"].'/templates/header.php';
            require_once $_SERVER["DOCUMENT_ROOT"].'/views/'.$name.'.php';
            require_once $_SERVER["DOCUMENT_ROOT"].'/templates/footer.php';
        } else {
            self::viewStatic("404");
        }
    }   
    
    public static function viewStatic($name) {
        require_once $_SERVER["DOCUMENT_ROOT"].'/views/'.$name.'.php';
    }   
}
