<?php

class Ucitel extends Uzivatel {

    public static function pridejUcitele($data) {
        $jmeno = $data->Jmeno;
        $prijmeni = $data->Prijmeni;
        $titul = $data->Titul;
        $email = $data->Email;
        $zkratka = $data->Zkratka;
        $skol_rok = Config::getValueFromConfig("skolnirok_id");

        $exists = Databaze::dotaz("SELECT * FROM ucitele WHERE email LIKE ?", array($email));
        if($exists == null) {
            Databaze::dotaz("INSERT INTO ucitele(id, jmeno, prijmeni, titul, email, skolnirok) VALUES(?,?,?,?,?,?)",
                array($zkratka, $jmeno, $prijmeni, $titul, $email, $skol_rok));
        }
        Ucitel::propojPredmety($data);
    }

    public static function propojPredmety($data) {
        $idu = $data->Zkratka;
        $skol_rok = Config::getValueFromConfig("skolnirok_id");
        $predmety = $data->Predmety->Predmet;
        $dotaz = "INSERT INTO ucitele_predmety(id_u, id_p, trida, skolnirok, skupina) VALUES";
        if($predmety != null) {
            foreach($predmety as $predmet) {
                $dotaz = $dotaz . '("' . $idu . '","' . $predmet->Zkratka . '","' . $predmet->Trida . '",'.$skol_rok.',"'.$predmet->Skupina.'"),';
            }
            $dotaz = rtrim($dotaz, ',');
            //print_r($dotaz);
            Databaze::dotaz($dotaz, array());
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
            } else {
                Databaze::dotaz("UPDATE ucitele SET avatar = ? WHERE email LIKE ?", array($ucitel->getObrazek(), $ucitel->getEmail()));
            }
            $_SESSION["typ"] = 1;
            return $ucitel;
        } else {
            return null;  
        }
    }

    public static function getId($email) {
        $id = Databaze::dotaz("SELECT id FROM ucitele WHERE email LIKE ?", array($email));
        return $id[0][0];
    }

    public static function vratUcitele() {
        return Databaze::dotaz("SELECT * from ucitele where skolnirok like ? order by id asc", array(Config::getValueFromConfig('skolnirok_id')));
    }

}