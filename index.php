<?php

require('init.php');
session_start();

// Router namespace
use Steampixel\Route;

// root route
Route::add('/', function() {
  if(isset($_SESSION['access_token'])) {
    require_once "views/home.php";
  } else {
     require_once 'views/landing.php';
  }
});

Route::add('/test', function() {
  require_once 'test.php';
});

Route::add('/unauthorized', function() {
  require_once 'views/unauthorizeduser.php';
});

Route::add('/auth/google', function() {
  require_once 'controllers/googleoauth.php';
});

Route::add('/callback/google', function() {
  require_once 'views/landing.php';
});

// Run the router
Route::run('/');