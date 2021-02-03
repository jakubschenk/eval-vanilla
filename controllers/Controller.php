<?php

class Controller {
    
    protected $title;

    public static function view($name, $title, array $args) {
        $pageName = $title;
        require_once 'views/'.$name.'.php';
    }   
    
}