<?php

class Predmet {
    
    public static function pridejPredmet($zkratka, $nazev) {
        $exists = Databaze::dotaz("SELECT * FROM predmety WHERE zkratka LIKE ?", array($zkratka));
        if($exists) {
            Databaze::dotaz("UPDATE predmety SET nazev = ? WHERE zkratka LIKE ?", array($nazev, $zkratka));
        } else {
            Databaze::dotaz("INSERT INTO predmety(zkratka, nazev) VALUES(?,?)", array($zkratka, $nazev));
        }
    }

    public static function vratPredmetyProStudenta($id) {
        $predmety = Databaze::dotaz("SELECT * FROM studenti_predmety WHERE id_s LIKE ?", array($id));
        return $predmety;
    }

    public static function vratPredmetyProUcitele($id) {
        $predmety = Databaze::dotaz("SELECT * FROM ucitele_predmety WHERE id_u LIKE ?", array($id));
        return $predmety;
    }

}