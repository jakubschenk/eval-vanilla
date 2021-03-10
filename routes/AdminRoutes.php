<?php

use Steampixel\Route;

//root administrace(login/mainscreen)
Route::add('/administrace', function() {
    $pageName = "Administrace";
    AdminController::view("Administrace", $pageName, array());
});

//login screen
Route::add('/administrace/login', function() {
    AdminLoginController::viewStatic('AdministraceLogin', "Přihlášení");
}, 'get');

//login post
Route::add('/administrace/login', function() {
    AdminLoginController::loginAdmin();
}, 'post');

//registrace noveho uzivatele
Route::add('/administrace/registrace', function() {
    AdminRegisterController::view("AdministraceRegistrace", "Registrace", array());
}, 'get');

//zpracovani requestu registrace
Route::add('/administrace/registrace', function() {
    if(Administrator::authenticated())
        AdminRegisterController::registerAdmin();
}, 'post');

//odhlaseni
Route::add('/administrace/logout', function() {
    AdminController::logout();
});

//uprava otazek
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
  
//uprava ucitelu
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

//import
Route::add('/administrace/import', function() {
    $pageName = "Administrace";
    AdminController::view("AdministraceImport", $pageName, array());
}, 'get');
  
Route::add('/administrace/importing', function() {
    AdminController::view("AdministraceNahravaniDatabaze", "Nahrávání..", array());
}, 'post', 'get');

//prohlizeni odpovedi
Route::add('/administrace/prohlizeni', function() {
    AdminController::view("AdministraceProhlizeni", "Prohlížení odpovědí", array('js' => ['prohlizecOtazek']));
});

Route::add('/administrace/prohlizeni/zmenProhlizenyRok', function() {
    if(Administrator::authenticated()) {
        $input = json_decode(file_get_contents('php://input'), true);
        $_SESSION["viewedRok"] = $input["rok"];
    }
}, 'post');

Route::add('/administrace/prohlizeni/getOtazkaStatStudent', function() {
    $input = json_decode(file_get_contents('php://input'), true);
    AdminProhlizeniController::vratOtazkyStudent($input['q']);
}, 'post');

//nastaveni
Route::add('/administrace/nastaveni', function() {
    AdminSettingsController::view('AdministraceNastaveni', "Nastavení",
        array('stylesheet' => ['nastaveni'], 'js' => ['nastaveni']));
});

Route::add('/administrace/nastaveni/zmenDatum', function() {
    $input = json_decode(file_get_contents('php://input'), true);
    $datum_od = $input["datum_od"];   
    $datum_do = $input["datum_do"];

    if(Administrator::authenticated())
        AdminSettingsController::nastavitDatum($datum_od, $datum_do);
}, 'post');

Route::add('/administrace/nastaveni/zmenHeslo', function() {
    $input = json_decode(file_get_contents('php://input'), true);
    $stare = $input["stare"];   
    $nove = $input["nove"];

    if(Administrator::authenticated())
        if(AdminSettingsController::zmenitHeslo($stare, $nove))
            header("Location: /administrace/logout");  

        
}, 'post');

Route::add('/administrace/nastaveni/smazAdmina', function() {
    $input = json_decode(file_get_contents('php://input'), true);
    $jmeno = $input["jmeno"];   

    if(Administrator::authenticated())
        AdminSettingsController::smazAdministratora($jmeno);
     
}, 'post');