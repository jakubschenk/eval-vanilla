<?php

// Autoloader composeru
require_once __DIR__ . '/vendor/autoload.php';

// Autoloader

function nactitridu($trida) {
  if (preg_match("/controllers$/", $trida)) {
    require("kontroler/" . $trida . ".php");
  } else {
    require("models/" . $trida . ".php");
  }
}

spl_autoload_register("nactitridu");