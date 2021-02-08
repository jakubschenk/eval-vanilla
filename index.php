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
    PredmetyController::view("Predmety", "eval", array());
  } else {
     require_once 'views/LandingPage.php';
  }
});

Route::add('/p/([a-zA-Z]*)', function($predmet) {
  new OtazkyController($predmet); 
});

Route::add('/administrace/login', function() {
  AdminLoginController::loginAdmin();
}, 'post');

Route::add('/administrace/([a-z]*)/otazky/upravit', function($druh) {
  AdminController::view("AdministraceOtazky", "Administrace", array($druh));
});

Route::add('/administrace/([a-z]*)/otazky/ulozit', function($druh) {
  $input = json_decode(file_get_contents('php://input'), true);
  AdminOtazkyEditController::zapisUpravenouOtazku($input, $druh);
}, 'post');

Route::add('/administrace/([a-z]*)/otazky/pridat', function($druh) {
  $input = json_decode(file_get_contents('php://input'), true);
  AdminOtazkyEditController::pridejNovouOtazku($input, $druh);
}, 'post');

Route::add('/administrace/upravit/uzivatele/([a-z]*)', function($druh) {
  new AdminUzivateleEditController($druh);
});

Route::add('/administrace/login', function() {
  AdminLoginController::view('AdministraceLogin', "Přihlášení", array());
}, 'get');

if($cfg['adminReg']) {
  Route::add('/administrace/registrace', function() {
    $pageName = "Registrace";
    AdminRegisterController::view("AdministraceRegistrace", "Registrace", array());
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
  AdminController::view("Administrace", $pageName, array());
});

Route::add('/administrace/import', function() {
  $pageName = "Administrace";
  AdminController::view("AdministraceImport", $pageName, array());
}, 'get');

Route::add('/administrace/importing', function() {
  AdminController::view("AdministraceNahravaniDatabaze", "Nahrávání..", array());
  new AdminImportController();
}, 'post');

Route::add('/test', function() {
  require_once 'test.php';
});

Route::add('/unauthorized', function() {
  $Controller = new Controller();
  $Controller->view('UnauthorizedUser', "Nepovoleno!", array());
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

Route::add('/js/([a-z][A-Z]*.js)', function($nazev) {
  $location = "public/js/" . $nazev;
  if (file_exists($location)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    readfile($location);
    die();        
  } else {
    die("Error: File not found.");
  } 
});

Route::add('/css/([a-z][A-Z]*.css)', function($nazev) {
  $location = "public/css/" . $nazev;
  if (file_exists($location)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
    readfile($location);
    die();        
  } else {
    die("Error: File not found.");
  } 
});

// Run the router
Route::run('/');