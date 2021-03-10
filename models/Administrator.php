<?php

class Administrator {
    public static function verify($login, $pass) {
        $exists = Databaze::dotaz("SELECT heslo FROM administratori WHERE jmeno = ?", array($login));
        if($exists != array()) {
            if (password_verify($pass, $exists[0]['heslo'])) {
                $_SESSION['admin'] = true;
                $_SESSION['login'] = $login;
                $redirect_url = '/administrace';
                header('Location: ' . filter_var($redirect_url, FILTER_SANITIZE_URL));
            } else {
                $login_url = '/administrace/login?badLogin';
                header('Location: ' . filter_var($login_url, FILTER_SANITIZE_URL));
            }
        } else {
            $login_url = '/administrace/login?badLogin';
                header('Location: ' . filter_var($login_url, FILTER_SANITIZE_URL));    
        }
    }

    public static function newAdmin($login, $email, $pass) {
        if(Databaze::dotaz("SELECT * FROM administratori where jmeno = ?", array($login)) == null) {
            Databaze::dotaz(
                "INSERT INTO administratori(jmeno, email, heslo) VALUES(?,?,?)",
                array($login, $email, $pass)
            );
            $login_url = '/administrace';
            header('Location: ' . filter_var($login_url, FILTER_SANITIZE_URL));    
        } 
        
    }

    public static function zmenHeslo($uzivatel, $stareHeslo, $noveHeslo) {
        $heslo = Databaze::dotaz("SELECT heslo FROM administratori WHERE jmeno = ?", array($uzivatel))[0];
        if($heslo != array()) {
            if (password_verify($stareHeslo, $heslo['heslo'])) {
                $noveHeslo = password_hash($noveHeslo, PASSWORD_DEFAULT);
                Databaze::dotaz("UPDATE administratori SET heslo = ? WHERE jmeno LIKE ?", array($noveHeslo, $uzivatel));
                return true;
            } else {
                return false;
            }
        }
    }

    public static function authenticated() {
        if(isset($_SESSION['admin']))
            return true;
        else
            return false;
    }

    public static function vratAdministratory() {
        return Databaze::dotaz("SELECT * from administratori");
    }

    public static function smazAdministratora($jmeno) {
        Databaze::dotaz("DELETE FROM adminstratori where jmeno like ?", array($jmeno));
    }
}