<?php

class Databaze {

    private static $spojeni;
    private static $nastaveni = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    );

    public static function pripoj($server, $databaze, $uzivatel, $heslo) {
        if (!isset(self::$spojeni)) {
            $dsn = "mysql:host=$server;dbname=$databaze;charset=utf8";
            self::$spojeni = new PDO(
                    $dsn, $uzivatel, $heslo, self::$nastaveni
            );
        }
    }

    public static function dotaz($dotaz, $parametry = array()) {
        $vysledek = self::$spojeni->prepare($dotaz);
        $vysledek->execute($parametry);
        return $vysledek->fetchAll();
    }
    
    public static function vloz($dotaz, $parametry = array()) {
        $vysledek = self::$spojeni->prepare($dotaz);
        $vysledek->execute($parametry);
    }
}