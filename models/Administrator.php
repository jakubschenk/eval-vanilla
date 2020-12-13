<?php

class Administrator {
    public static function verify($login, $pass) {
        $exists = Databaze::dotaz("SELECT heslo FROM administratori WHERE jmeno = ?", array($login));
        if($exists != null) {
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
}