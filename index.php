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
    PredmetyController::view("Predmety", "eval");
  } else {
     require_once 'views/LandingPage.php';
  }
});

Route::add('/administrace/login', function() {
  AdminLoginController::loginAdmin();
}, 'post');

Route::add('/administrace/login', function() {
  AdminLoginController::view('AdministraceLogin', "Přihlášení");
}, 'get');

if($cfg['adminReg']) {
  Route::add('/administrace/registrace', function() {
    $pageName = "Registrace";
    AdminRegisterController::view("AdministraceRegistrace", "Registrace");
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
  AdminController::view("Administrace", $pageName);
});

Route::add('/administrace/import', function() {
  $pageName = "Administrace";
  AdminController::view("AdministraceImport", $pageName);
}, 'get');

Route::add('/administrace/importing', function() {
  AdminController::view("AdministraceNahravaniDatabaze", "Nahrávání..");
  new AdminImportController();
}, 'post');

Route::add('/test', function() {
  require_once 'test.php';
});

Route::add('/unauthorized', function() {
  $Controller = new Controller();
  $Controller->view('UnauthorizedUser', "Nepovoleno!");
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