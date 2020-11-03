<?php

session_start();

// Autoload files using composer
require_once __DIR__ . '/vendor/autoload.php';

// Autoloader

function nactitridu($trida) {
  require("classes/$trida.php");
}

spl_autoload_register("nactitridu");

// Use this namespace
use Steampixel\Route;

// Add your first route
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