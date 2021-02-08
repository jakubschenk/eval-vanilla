<?php

require_once 'init.php';
use Steampixel\Route;

Route::pathNotFound(function($path) {
  header('HTTP/1.0 404 Not Found');
  echo 'Error 404 :-(<br>';
  echo 'The requested path "'.$path.'" was not found!';
});

require_once 'routes/AdminRoutes.php';
require_once 'routes/UserRoutes.php';

Route::run('/');