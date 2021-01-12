<?php

class XMLImport {

    private $skolnirok;
    private $logfile;
    private $xml_file;

    public function __construct($xml_file, $rok, $log) {
        set_time_limit(1000);
        $this->skolnirok = $rok;
        $this->logfile = $log;
        $this->xml_file = $xml_file;

        if($this->vytvorRok()) {
            Config::setValueInConfig("skolnirok", $rok);
            $data = $this->nactiXML();
        
            $this->nahrajPredmety($data->Predmety);
            $this->nahrajUcitele($data->Ucitele);
            $this->nahrajStudenty($data->Studenti);

            fwrite($this->logfile, "\n". date("Ymd_h-i-s") . " IMPORT DOKONCEN");
            set_time_limit(120);
            header("Location: /administrace/import?success");
        } else {
            fwrite($this->logfile, "SKOLNI ROK JIZ EXISTUJE, UKONCUJI");
            set_time_limit(120);
            header('Location: /administrace/import?error');
        }
    }

    private function vytvorRok() {
        $exists = Databaze::dotaz("SELECT * FROM skolniroky WHERE rok LIKE ?", array($this->skolnirok));
        if(!$exists) {
            Databaze::dotaz("INSERT INTO skolniroky(rok) VALUES(?)", array($this->skolnirok));
            return true;
        } else {
            return false;
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
        foreach($data->Student as $student) {
            Student::pridejStudenta($student);
        }
    }

    private function nahrajUcitele($data) {
        foreach($data->Ucitel as $ucitel) {
            Ucitel::pridejUcitele($ucitel);
        }
    }

}