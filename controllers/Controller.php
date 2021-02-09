<?php

class Controller {
    
    protected $title;

    public static function view($name, $title, array $args) {
        $pageName = $title;
        require_once 'templates/header.php';
        require_once 'views/'.$name.'.php';
        require_once 'templates/footer.php';
    }   
    
    public static function viewStatic($name) {
        require_once 'views/'.$name.'.php';
    }   
}