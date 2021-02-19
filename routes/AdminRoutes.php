<?php

use Steampixel\Route;

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

Route::add('/administrace/([a-z]*)/otazky/smazat', function($druh) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input["id"];
    AdminOtazkyEditController::smazOtazku($id, $druh);
}, 'post');
  
Route::add('/administrace/([a-z]*)/uzivatele/upravit', function($druh) {
    AdminUzivateleEditController::view("AdministraceUzivatele", "Úprava uživatelů", array($druh));
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
}, 'post', 'get');
