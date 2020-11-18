<?php

require_once 'init.php';

// Router namespace
use Steampixel\Route;

Route::pathNotFound(function($path) {
  header('HTTP/1.0 404 Not Found');
  echo 'Error 404 :-(<br>';
  echo 'The requested path "'.$path.'" was not found!';
});

// root route
Route::add('/', function() {
  if(isset($_SESSION['access_token'])) {
    require_once "views/home.php";
  } else {
     require_once 'views/landing.php';
  }
});

Route::add('/administrace/login', function() {
  require_once 'controllers/admin/adminlogincontroller.php';
}, ['get','post']);

if($cfg['adminRegOn']) {
  Route::add('/administrace/registrace', function() {
    require_once 'controllers/admin/adminregistercontroller.php';
  }, ['get','post']);
}

Route::add('/administrace/logout', function() {
  require_once 'controllers/admin/admincontroller.php';
});

Route::add('/administrace', function() {
  require_once 'controllers/admin/admincontroller.php';
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