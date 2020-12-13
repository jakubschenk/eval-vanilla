<?php

class Controller {
    
    protected $title;

    public static function view($name, $title) {
        $pageName = $title;
        require_once 'views/'.$name.'.php';
    }   
    
}