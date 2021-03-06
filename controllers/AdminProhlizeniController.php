<?php

class AdminProhlizeniController extends AdminController {
    public static function vratOtazkyStudent($string) {
        list($trida, $predmet, $ucitel, $skupina, $otazka_id) = explode('-', $string);
        $odpovedi = Databaze::dotaz("SELECT odpoved, id_odpovedi as id FROM studenti_odpovedi WHERE id_p = ? and id_u = ? and trida = ? and skupina = ? and id_o = ?", array($predmet, $ucitel, $trida, $skupina, $otazka_id));
        $j_od = json_encode($odpovedi);
        
        header("application/json");
        echo $j_od;
    }
}