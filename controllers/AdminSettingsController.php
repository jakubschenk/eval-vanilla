<?php

class AdminSettingsController extends AdminController {
    public static function zmenitHeslo($stareHeslo, $noveHeslo) {
        return Administrator::zmenHeslo($_SESSION['login'], $stareHeslo, $noveHeslo); 
    }

    public static function nastavitDatum($zacatek, $konec) {
        Config::setPristup($zacatek, $konec);
    }

    public static function smazAdministratora($jmeno) {
        Administrator::smazAdministratora($jmeno);
    }

    public static function zmenSkolniRok($skolnirok) {
        Config::setSkolniRok($skolnirok);    
    }
}