<?php

require_once 'init.php';
use Steampixel\Route;

Route::pathNotFound(function($path) {
  header('HTTP/1.0 404 Not Found');
  Controller::viewStatic("404");
});

require_once 'routes/AdminRoutes.php';
require_once 'routes/UserRoutes.php';

Route::run('/');