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

// pripoj databazi pres databazovy wrapper
Databaze::pripoj('localhost', 'eval_vanilla', 'root', '');
session_start();

$config_json = file_get_contents('config.json');
$cfg = json_decode($config_json, true);