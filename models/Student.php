<?php

class Student extends Uzivatel {

    public static function pridejStudenta($data) {
        $jmeno = $data->Jmeno;
        $prijmeni = $data->Prijmeni;
        $trida = $data->Trida;
        $email = $data->Email;
        $skol_rok = Config::getValueFromConfig("skolnirok");

        $exists = Databaze::dotaz("SELECT * FROM studenti WHERE email LIKE ?", array($email));
        if($exists == null) {
            Databaze::dotaz("INSERT INTO studenti(jmeno, prijmeni, trida, email, skolnirok) VALUES(?,?,?,?,?)",
                array($jmeno, $prijmeni, $trida, $email, $skol_rok));
        }
    }

    public static function propojPredmety($data) {
        $ids = Databaze::dotaz("SELECT id FROM studenti WHERE email LIKE ?", array($data->Email));
        $skol_rok = Config::getValueFromConfig("skolnirok");
        foreach($data->Predmety->Predmet as $predmet) {
            Databaze::dotaz("INSERT INTO studenti_predmety(id_s, id_p, id_u, skolnirok, skupina) VALUES(?,?,?,?,?)",
                array($ids[0]["id"], $predmet->Zkratka, $predmet->Ucitel, $skol_rok, $predmet->Skupina));
        }
    }

    // funkce na doplneni gid a avataru uzivatele co se prihlasuje pres gsuite
    public static function updateAndCheckUser($user_data) {

        $student = new Student($user_data);
        
        $exists = Databaze::dotaz("SELECT * FROM studenti WHERE email LIKE ? AND skolnirok LIKE ?", array($student->getEmail(), Config::getValueFromConfig("skolnirok")));
        // prvni se musime podivat, jestli je uzivatel v seznamu uzivatelu
        if($exists) {
            $databaze_profil = Databaze::dotaz("SELECT * FROM studenti WHERE gid LIKE ?", array($student->getGid()));
            //doplneni informaci
            if(empty($databaze_profil)) { 
                Databaze::vloz("UPDATE studenti SET gid = ?, avatar = ? WHERE email LIKE ?",
                    array($student->getGid(), $student->getObrazek(), $student->getEmail()));
            }
            return $student;
        } else {
            return null;  
        }
    }

}