<?php

class XMLImport {

    private $skolnirok;
    private $skolnirok_id;
    private $logfile;
    private $xml_file;

    public function __construct($xml_file, $rok, $log) {
        set_time_limit(1000);
        $this->skolnirok = $rok;
        $this->logfile = $log;
        $this->xml_file = $xml_file;

        if(($this->skolnirok_id = $this->vytvorRok()) != null) {
            Config::setValueInConfig("skolnirok", $this->skolnirok);
            Config::setValueInConfig("skolnirok_id", $this->skolnirok_id);
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
                    fwrite($this->logfile, "\nNALEZ: ". $hledanyDuplikat->Email ."POCTE: ".$nalezy);
                }
            }
            if($nalezy > 1) {
                $isIn = 0;
                foreach($duplikaty as $dup) {
                    if(strcmp($dup, $student->Email)) {
                        $isIn = 1;
                    }
                }
                if($isIn == 0) {
                    $duplikaty[] = $student->Email;
                }
                fwrite($this->logfile, "\nPocet: ". $nalezy . "Vkladam do duplikatu: ". $student->Email);
            }
            $nalezy = 0;
        }

        Student::pridejStudenty($data);
        foreach($data->Student as $student) {
            Student::propojPredmety($student);
        }

        foreach($duplikaty as $duplikat) {
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