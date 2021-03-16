<?php

use Steampixel\Route;

// cesta pri nedostatecnych pravech
Route::add('/unauthorized', function () {
  Controller::viewStatic('UnauthorizedUser', "Nepovoleno!");
});

if (Cas::isPristup() == 1) {
  //base cesta (autentizace, prohlizeni predmetu)
  Route::add('/', function () {
    if (isset($_SESSION['access_token'])) {
      Controller::view("Home", "Předměty", ['stylesheets' => ['predmety']]);
    } else {
      require_once 'views/LandingPage.php';
    }
  });

  Route::add('/fakelogin', function () {
    // $_SESSION['email'] = 'r.nowak@spseiostrava.cz';
    // $_SESSION['id'] = Ucitel::getId($_SESSION['email']);
    // $_SESSION['druh'] = 'ucitel';
    $_SESSION['email'] = 'm.scupak.st@spseiostrava.cz';
    $_SESSION['id'] = 5296;
    $_SESSION['druh'] = 'student';
    $_SESSION['access_token'] = "XD";
    header('Location: /');
  });

  // hodnoceni studenty
  Route::add('/p/([a-zA-Z]*)/([a-zA-Z]*)', function ($ucitel, $predmet) {
    Controller::view("Otazky", $predmet, array($predmet, $ucitel, 'stylesheets' => ['otazky']));
  });

  Route::add('/p/([A-Z]*)/([A-Z]*)/submit', function ($ucitel, $predmet) {
    OtazkyController::zpracuj($predmet, $ucitel);
  }, 'post');

  // hodnoceni uciteli
  Route::add('/t/([A-Z0-9]*)/([a-zA-Z]*)/([A-Z0-9%]*)', function ($trida, $predmet, $skupina) {
    Controller::view("Otazky", $predmet, array($predmet, $trida, $skupina, 'stylesheets' => ['otazky']));
  });

  Route::add('/t/([A-Z0-9]*)/([a-zA-Z]*)/([A-Z0-9%]*)/submit', function ($trida, $predmet, $skupina) {
    OtazkyController::zpracujUcitel($predmet, $trida, $skupina);
  }, 'post');

  //autorizace
  Route::add('/auth/google', function () {
    $AuthController = new OAuthController();
    $AuthController->redirectUserToAuth();
  });

  Route::add('/auth/callback', function () {
    $AuthController = new OAuthController();
    $AuthController->authUserFromRedirect();
  });

  Route::add('/auth/google/logout', function () {
    $AuthController = new OAuthController();
    $AuthController->logout();
  });
} else {
  Route::add('/', function() {
    Controller::viewStatic("Countdown");
  });
}
