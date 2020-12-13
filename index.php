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
  AdminLoginController::loginAdmin();
}, 'post');

Route::add('/administrace/login', function() {
  AdminLoginController::view('administrace-login', "Přihlášení");
}, 'get');

if($cfg['adminRegOn']) {
  Route::add('/administrace/registrace', function() {
    $pageName = "Registrace";
    AdminRegisterController::view("administrace-registrace", "Registrace");
  }, 'get');
  Route::add('/administrace/registrace', function() {
    AdminRegisterController::registerAdmin();
  }, 'post');
}

Route::add('/administrace/logout', function() {
  AdminController::logout();
});

Route::add('/administrace', function() {
  $pageName = "Administrace";
  AdminController::view("administrace", $pageName);
});

Route::add('/test', function() {
  require_once 'test.php';
});

Route::add('/unauthorized', function() {
  $Controller = new Controller();
  $Controller->view('unauthorized.php', "Nepovoleno!");
});

Route::add('/auth/google', function() {
  $AuthController = new OAuthController();
  $AuthController->redirectUserToAuth();
});

Route::add('/auth/callback', function() {
  $AuthController = new OAuthController();
  $AuthController->authUserFromRedirect();
});

Route::add('/auth/google/logout', function() {
  $AuthController = new OAuthController();
  $AuthController->logout();
});



// Run the router
Route::run('/');