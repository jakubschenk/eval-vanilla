<?php

use Steampixel\Route;

Route::add('/administrace/login', function() {
    AdminLoginController::loginAdmin();
}, 'post');
  
Route::add('/administrace/([a-z]*)/otazky/upravit', function($druh) {
    AdminController::view("AdministraceOtazky", "Administrace", [$druh, 'stylesheets' => ['upravaOtazek'], 'js' => ['loadEditor']]);
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

Route::add('/administrace/([a-z]*)/otazky/zmenitCislo', function($druh) {
    $input = json_decode(file_get_contents('php://input'), true);
    $old= $input["old"];
    $new = $input["new"];
    $id = $input["id"];
    AdminOtazkyEditController::zmenCislo($id, $druh, $old, $new);
}, 'post');
  
Route::add('/administrace/([a-z]*)/uzivatele/upravit', function($druh) {
    AdminUzivateleEditController::view("AdministraceUzivatele", "Úprava uživatelů", [$druh, 'stylesheets' => ['upravaUzivatelu']]);
});

Route::add('/administrace/([a-z]*)/uzivatele/([A-Z0-9]*)/upravit', function($druh, $id) {
    AdminUzivateleEditController::view("AdministraceUzivatelEdit", "Úprava uživatelů", [$druh, $id, 'stylesheets' => ['upravaUzivatelu']]);
});

Route::add('/administrace/([a-z]*)/uzivatele/([A-Z0-9]*)/upravit', function($druh, $id) {
    if(isset($_POST["email"])) {
        $novy = $_POST["email"];
        AdminUzivateleEditController::aktualizujUzivatele($id, $druh, $novy);
    } else {
        header("Location: /administrace");
    }

}, 'post');

Route::add('/administrace/login', function() {
    AdminLoginController::viewStatic('AdministraceLogin', "Přihlášení");
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

Route::add('/administrace/prohlizeni', function() {
    AdminController::view("AdministraceProhlizeni", "Prohlížení odpovědí", array('js' => ['prohlizecOtazek']));
});

Route::add('/administrace/prohlizeni/zmenProhlizenyRok', function() {
    if(isset($_SESSION["admin"])) {
        $input = json_decode(file_get_contents('php://input'), true);
        $_SESSION["viewedRok"] = $input["rok"];
    }
}, 'post');

Route::add('/administrace/prohlizeni/getOtazkaStatStudent', function() {
    $input = json_decode(file_get_contents('php://input'), true);
    AdminProhlizeniController::vratOtazkyStudent($input['q']);
}, 'post');