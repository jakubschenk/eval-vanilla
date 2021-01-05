<?php

class XMLImport {

    private $skolnirok;
    private $logfile;
    private $xml_file;

    public function __construct($xml_file, $rok, $log) {

        $this->skolnirok = $rok;
        $this->logfile = $log;
        $this->xml_file = $xml_file;

        $this->vytvorRok();
        $data = $this->nactiXML();
        
        $this->nahrajPredmety($data->Predmety);
        $this->nahrajPredmety($data->Ucitele);
    }

    private function vytvorRok() {
        $exists = Databaze::dotaz("SELECT * FROM skolniroky WHERE rok LIKE ?", array($this->skolnirok));
        if(!$exists) {
            Databaze::dotaz("INSERT INTO skolniroky(rok) VALUES(?)", array($this->skolnirok));
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

    }

    private function nahrajUcitele($data) {
        foreach($data->Ucitel as $ucitel) {
            Ucitel::pridejUcitele($ucitel);
        }
    }

}