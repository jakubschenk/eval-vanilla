<?php

// Autoloader composeru
require_once __DIR__ . '/vendor/autoload.php';

// Autoloader

function nactitridu($trida) {
  if (preg_match('/controller$/i', $trida)) {
    require("controllers/" . $trida . ".php");
  } else {
    require("models/" . $trida . ".php");
  }
}

spl_autoload_register("nactitridu");

// pripoj databazi pres databazovy wrapper
try {
  Databaze::pripoj('localhost', 'eval_vanilla', 'root', '');
} catch(PDOException $e) {
  echo 'Nemáte importovanou databázi!';
  die(); 
}
session_start();

if(!file_exists('config.json')) {
  $emptyConfig = [
    'skolnirok_id' => null,
    'skolnirok' => null,
    'pristup_od' => null,
    'pristup_do' => null
  ];
  file_put_contents('config.json', json_encode($emptyConfig));
}

$config_json = file_get_contents('config.json');
$cfg = json_decode($config_json, true);
