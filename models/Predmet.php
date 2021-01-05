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

    public static function propojPredmetAUcitele($predmet, $ucitel) {
        //Databaze::dotaz()
    }

}