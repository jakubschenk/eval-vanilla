<?php

class Uzivatel {

    private $jmeno;
    private $email;
    private $obrazek;
    private $g_id;
    private $typ;

    public function __construct($user_data) { // $user_data dostaneme od Google_Service_Oauth2
        $this->jmeno = $user_data['name'];
        $this->email = $user_data['email'];
        $this->obrazek = $user_data['picture'];
        $this->g_id = $user_data['id'];
        $this->typ = $this->typChooser($this->email);
    }

    private function typChooser($mail) {
            if(strpos($mail, '.st@') !== false) {
              return 'student';
            } else {
              return 'ucitel';
            }    
    }

    public function getEmail() {
        return $this->email;
    }
    public function getTyp() {
        return $this->typ;
    }
    public function getObrazek() {
        return $this->obrazek;
    }
    public function getJmeno() {
        return $this->jmeno;
    }
    public function getId() {
        return $this->g_id;
    }

    // funkce na kontrolu jestli uzivatel, co se prihlasuje pres google oauth2 je v tabulce povolenych uzivatelu
    // domena je nastavena na tvrdo v nastaveni aplikace v google konzoli
    // to znamena ze uzivatele co nejsou na spseiostrava nemaji sanci se ani dostat pres google
    public static function updateAndCheckUser($user_data) {

        $uzivatel = new Uzivatel($user_data);
        
        $databaze_import_profil = Databaze::dotaz("SELECT * FROM uzivatele_import WHERE email LIKE ?", [$uzivatel->getEmail()]);
        // prvni se musime podivat, jestli je uzivatel v seznamu uzivatelu z importu
        if($databaze_import_profil) {
            // TODO: zaridit, aby uzivatel nemusel byt v import tabulce, protoze import tabulka by se mohla mazat
            //       pravdepodobne staci jen vymenit logiku if, nebo zamenit poradi, zatim nechat takhle
            $databaze_profil = Databaze::dotaz("SELECT * FROM uzivatele WHERE id LIKE ?", [$uzivatel->getId()]);
            print_r($databaze_profil);
            echo($uzivatel->getId());
            if(empty($databaze_profil)) { // vytvoreni uzivatele v tabulce uzivatele, protoze byl v uzivatele_import a uz pro nej mame gid
                print_r($databaze_profil);
                Databaze::vloz("INSERT INTO uzivatele(id, email, typ, avatar, jmeno) VALUES(?,?,?,?,?)",
                    [$uzivatel->getId(), $uzivatel->getEmail(), $uzivatel->getTyp(), $uzivatel->getObrazek(), $uzivatel->getJmeno()]);
            }
            return $uzivatel;
        } else {
            return null;  
        }
    }
}