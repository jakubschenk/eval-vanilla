<?php

class Student extends Uzivatel {

    public static function pridejStudenty($data) {
        $dotaz = 'INSERT INTO studenti(jmeno, prijmeni, trida, email, skolnirok) VALUES';
        foreach($data->Student as $student) {
            $jmeno = $student->Jmeno;
            $prijmeni = $student->Prijmeni;
            $trida = $student->Trida;
            $email = $student->Email;
            $skol_rok = Config::getValueFromConfig("skolnirok_id");

            $dotaz = $dotaz . '("' . $jmeno . '","' . $prijmeni . '","' . $trida . '","'.$email.'",'.$skol_rok.'),'; 

        }
        $dotaz = rtrim($dotaz, ',');
        Databaze::dotaz($dotaz, array()); 
    }

    public static function propojPredmety($data) {
        $ids = Databaze::dotaz("SELECT id FROM studenti WHERE email LIKE ? and trida LIKE ? and skolnirok = ?", array($data->Email, $data->Trida, Config::getValueFromConfig("skolnirok_id")));
        $skol_rok = Config::getValueFromConfig("skolnirok_id");
        $dotaz = "INSERT INTO studenti_predmety(id_s, id_p, id_u, skolnirok, skupina) VALUES";
        $predmety = $data->Predmety->Predmet;
        foreach($predmety as $predmet) {
            $dotaz = $dotaz . '(' . $ids[0]["id"] . ',"' . $predmet->Zkratka . '","' . $predmet->Ucitel . '",'.$skol_rok.',"'.$predmet->Skupina.'"),';
        }
        $dotaz = rtrim($dotaz, ',');
        Databaze::dotaz($dotaz, array());
    }

    // funkce na doplneni gid a avataru uzivatele co se prihlasuje pres gsuite
    public static function updateAndCheckUser($user_data) {

        $student = new Student($user_data);
        
        $exists = Databaze::dotaz("SELECT * FROM studenti WHERE email LIKE ? AND skolnirok LIKE ?", array($student->getEmail(), Config::getValueFromConfig("skolnirok_id")));
        // prvni se musime podivat, jestli je uzivatel v seznamu uzivatelu
        if($exists) {
            $databaze_profil = Databaze::dotaz("SELECT * FROM studenti WHERE gid LIKE ?", array($student->getGid()));
            //doplneni informaci
            if(empty($databaze_profil)) { 
                Databaze::vloz("UPDATE studenti SET gid = ?, avatar = ? WHERE email LIKE ?",
                    array($student->getGid(), $student->getObrazek(), $student->getEmail()));
            } else {
                Databaze::dotaz("UPDATE studenti SET avatar = ? WHERE email LIKE ?", array($student->getObrazek(), $student->getEmail()));
            }
            return $student;
        } else {
            return null;  
        }
    }

    public static function getId($email) {
        $id = Databaze::dotaz("SELECT id FROM studenti WHERE email LIKE ? and skolnirok = ?", array($email, Config::getValueFromConfig("skolnirok_id")));
        return $id[0][0];
    }

    public static function vratStudenty() {
        return Databaze::dotaz("SELECT * from studenti where skolnirok like ? order by trida asc", array(Config::getValueFromConfig('skolnirok_id')));
    }

    public static function vratPocetVyplnenychDotazniku($skolnirok) {
        return Databaze::dotaz("SELECT sum(vyplneno) as pocet, count(vyplneno) as celkem from studenti_predmety where skolnirok like ?", array($skolnirok))[0];
    }
}