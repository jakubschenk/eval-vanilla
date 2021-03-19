<?php

class XMLImport {

    private $skolnirok;
    private $skolnirok_id;
    private $logfile;
    private $xml_file;

    public function __construct($xml_file, $log) {
        set_time_limit(1000); // import trval dlouho, rozsireni delky casu
        $this->logfile = $log;
        $this->xml_file = $xml_file;
        $data = $this->nactiXML(); //nacteni xml souboru od administratora
        $this->skolnirok = $data->SkolniRok;
        fwrite($this->logfile, $data->SkolniRok . '\n');

        if(($this->skolnirok_id = $this->vytvorRok()) != null) { //vytvoreni skolniho roku
            $staryRok = Config::getSkolniRok();
            Config::setSkolniRok($this->skolnirok_id); //nastaveni noveho skolniho roku
            Config::setPristup(null, null); //nastaveni uzivatelskeho pristupu na null - nastavuje administrator
            $this->nahrajPredmety($data->Predmety);
            $this->nahrajUcitele($data->Ucitele);
            $this->nahrajStudenty($data->Studenti);
            if($staryRok == null) {
                Otazka::importDefaultOtazky();
            } else {
                Otazka::prenesOtazky($staryRok, $this->skolnirok_id); //preneseni otazek z minuleho roku
            }

            fwrite($this->logfile, date("Ymd_h-i-s") . " IMPORT DOKONCEN\n");
            set_time_limit(120);
            header("Location: /administrace/import?success");
        } else {
            fwrite($this->logfile, "SKOLNI ROK JIZ EXISTUJE, UKONCUJI\n");
            set_time_limit(120);
            header('Location: /administrace/import?error');
        }

        fclose($this->logfile);
    }

    private function vytvorRok() {
        $exists = Databaze::dotaz("SELECT * FROM skolniroky WHERE rok LIKE ?", array($this->skolnirok));
        if(!$exists) {
            Databaze::dotaz("INSERT INTO skolniroky(rok) VALUES(?)", array($this->skolnirok));
            $id = Databaze::dotaz("SELECT idr FROM skolniroky WHERE rok LIKE ?", array($this->skolnirok));
            return $id[0][0];
        } else {
            return null;
        }
    }

    private function nactiXML() {
        $data = simplexml_load_file($this->xml_file) or die("Error: Nelze vytvorit datovy objekt");
        return $data;
    }

    private function nahrajPredmety($data) {
        foreach($data->Predmet as $predmet) {
            Predmet::pridejPredmet($predmet->Zkratka, $predmet->Nazev);
        }
    }

    private function nahrajStudenty($data) {
        $nalezy = 0;
        $duplikaty = array();
        $skolrok = Config::getValueFromConfig("skolnirok_id");

        foreach($data->Student as $student) {
            foreach($data->Student as $hledanyDuplikat) {
                if(strcmp($student->Email,$hledanyDuplikat->Email) == 0) {
                    $nalezy++;
                }
            }
            if($nalezy > 1) {
                    $duplikaty[] = $student->Email;
                    fwrite($this->logfile, "\nVkladam do duplikatu: ". $student->Email ."\n");
            }
            $nalezy = 0;
        }

        Student::pridejStudenty($data);
        foreach($data->Student as $student) {
            Student::propojPredmety($student);
        }
        $dupArray = array_unique($duplikaty);
        foreach($dupArray as $duplikat) {
            $ids = Databaze::dotaz("SELECT id FROM studenti WHERE email LIKE ? AND skolnirok LIKE ?", array($duplikat, $skolrok));
            foreach($ids as $id) {
                Databaze::dotaz("INSERT INTO duplikaty(id_studenta, skolnirok) VALUES(?,?)", array($id[0], $skolrok));
            }
        }
    }

    private function nahrajUcitele($data) {
        foreach($data->Ucitel as $ucitel) {
            Ucitel::pridejUcitele($ucitel);
        }
    }

}