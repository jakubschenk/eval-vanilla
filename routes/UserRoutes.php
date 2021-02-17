<?php

use Steampixel\Route;

Route::add('/', function() {
  if(isset($_SESSION['access_token'])) {
    Controller::view("Home", "Předměty", ['stylesheets' => ['predmety']]);
  } else {
     require_once 'views/LandingPage.php';
  }
});

Route::add('/p/([a-zA-Z]*)/([a-zA-Z]*)', function($ucitel, $predmet) {
  Controller::view("Otazky", $predmet, array($predmet, $ucitel));
});

Route::add('/p/([A-Z]*)/([A-Z]*)/submit', function($ucitel, $predmet) {
  OtazkyController::zpracuj($predmet, $ucitel);
}, 'post');

Route::add('/unauthorized', function() {
  Controller::viewStatic('UnauthorizedUser', "Nepovoleno!");
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