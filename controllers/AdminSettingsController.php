<?php

class AdminSettingsController extends AdminController {
    public static function zmenitHeslo($stareHeslo, $noveHeslo) {
        return Administrator::zmenHeslo($_SESSION['login'], $stareHeslo, $noveHeslo);
        header("Location: /adminstrace/logout");   
    }

    public static function nastavitDatum($zacatek, $konec) {
        Config::setValueInConfig("pristup_od", $zacatek);
        Config::setValueInConfig("pristup_do", $konec);
    }

    public static function vytvorUzivatele() {

    }
}