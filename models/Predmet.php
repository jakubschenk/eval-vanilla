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
        $predmety = Databaze::dotaz(
        "SELECT p.nazev as nazev, sp.id_p as zkratka, sp.skupina as skupina, sp.id_u as u_id, concat(u.titul, ' ', u.jmeno, ' ', u.prijmeni) as ucitel
        from studenti_predmety sp
        inner join predmety p on sp.id_p = p.zkratka
        inner join ucitele u on sp.id_u = u.id
        where sp.id_s = ? and sp.skolnirok like ? and vyplneno = 0
        order by p.nazev asc",
        array($id, Config::getValueFromConfig("skolnirok_id")));
        return $predmety;
    }

    public static function vratPredmetyProUcitele($id) {
        $predmety = Databaze::dotaz("SELECT * FROM ucitele_predmety WHERE id_u LIKE ?", array($id));
        return $predmety;
    }

}