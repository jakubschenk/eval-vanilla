<?php

class Student extends Uzivatel {

    // funkce na doplneni gid a avataru uzivatele co se prihlasuje pres gsuite
    public static function updateAndCheckUser($user_data) {

        $student = new Student($user_data);
        
        $exists = Databaze::dotaz("SELECT * FROM studenti WHERE email LIKE ?", [$student->getEmail()]);
        // prvni se musime podivat, jestli je uzivatel v seznamu uzivatelu
        if($exists) {
            $databaze_profil = Databaze::dotaz("SELECT * FROM studenti WHERE gid LIKE ?", [$student->getGid()]);
            //doplneni informaci
            if(empty($databaze_profil)) { 
                Databaze::vloz("INSERT INTO studenti(gid, avatar) VALUES(?,?)",
                    array($student->getGid(), $student->getObrazek()));
            }
            return $student;
        } else {
            return null;  
        }
    }

}