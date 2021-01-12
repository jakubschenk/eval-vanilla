<?php

class Ucitel extends Uzivatel {

    public static function pridejUcitele($data) {
        $jmeno = $data->Jmeno;
        $prijmeni = $data->Prijmeni;
        $titul = $data->Titul;
        $email = $data->Email;
        $zkratka = $data->Zkratka;
        $skol_rok = Config::getValueFromConfig("skolnirok");

        $exists = Databaze::dotaz("SELECT * FROM ucitele WHERE email LIKE ?", array($email));
        if($exists == null) {
            Databaze::dotaz("INSERT INTO ucitele(id, jmeno, prijmeni, titul, email, skolnirok) VALUES(?,?,?,?,?,?)",
                array($zkratka, $jmeno, $prijmeni, $titul, $email, $skol_rok));
        }
    }

    public static function propojPredmety($data) {
        $idu = $data->Zkratka;
        $skol_rok = Config::getValueFromConfig("skolnirok");
        foreach($data->Predmety->Predmet as $predmet) {
            Databaze::dotaz("INSERT INTO ucitele_predmety(id_u, id_p, trida, skolnirok, skupina) VALUES(?,?,?,?,?)",
                array($idu, $predmet->Zkratka, $predmet->Trida, $skol_rok, $predmet->Skupina));
        }
    }

    public static function updateAndCheckUser($user_data) {

        $ucitel = new Ucitel($user_data);
        
        $exists = Databaze::dotaz("SELECT * FROM ucitele WHERE email LIKE ?", [$ucitel->getEmail()]);
        // prvni se musime podivat, jestli je uzivatel v seznamu uzivatelu
        if($exists) {
            $databaze_profil = Databaze::dotaz("SELECT * FROM ucitele WHERE gid LIKE ?", [$ucitel->getGid()]);
            //doplneni informaci
            if(empty($databaze_profil)) { 
                Databaze::vloz("INSERT INTO ucitele(gid, avatar) VALUES(?,?)",
                    array($ucitel->getGid(), $ucitel->getObrazek()));
            }
            return $ucitel;
        } else {
            return null;  
        }
    }
}