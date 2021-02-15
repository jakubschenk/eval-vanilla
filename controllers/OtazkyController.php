<?php

class OtazkyController extends Controller {
    private $predmety;
    private $predmet;
    private $ucitel_id;
    private $id;

    public function __construct($predmet, $ucitel) {
        $this->predmet = $predmet;
        if(Uzivatel::typChooser($_SESSION["email"]) == 'student') {
            $this->ucitel_id = $ucitel;
            $this->id = Student::getId($_SESSION["email"]);
            $this->predmety = Predmet::vratPredmetyProStudenta($this->id);
            $shoda = 0;
            foreach($this->predmety as $povolenePredmety) {
                if($povolenePredmety["zkratka"] == $this->predmet && $povolenePredmety["u_id"] == $this->ucitel_id) {
                    $shoda = 1;
                    break;
                }
            }
            if($shoda) {
                Otazka::vypisOtazky($this->predmet, $this->ucitel_id, Otazka::vratOtazkyProStudenty());
            } else {
                echo "neplatny predmet";
            }
        }
    }
    public static function zpracuj($predmet, $ucitel) {
        if(!PredmetyController::vyplneno($predmet, $ucitel, $_SESSION["email"], $_SESSION["druh"]) && $_SESSION["druh"] == 'student') {
            $dotaz = "INSERT INTO studenti_odpovedi(id_p, id_u, id_o, trida, skupina, odpoved) VALUES";
            $skupina = Databaze::dotaz("SELECT skupina from studenti_predmety where id_s = ? and id_u = ? and id_p = ? and skolnirok = ?",
                array($_SESSION["id"], $ucitel, $predmet, Config::getValueFromConfig("skolnirok_id"))); 
            $trida = Databaze::dotaz("SELECT trida from studenti where id = ? and skolnirok = ?", array($_SESSION["id"], Config::getValueFromConfig("skolnirok_id")));
            foreach($_POST as $key => $value) {
                if($key != "Odeslat") {
                    $dotaz = $dotaz . '("'.$predmet.'","'.$ucitel.'",'.$key.',"'.$trida[0]["trida"].'","'.$skupina[0]["skupina"].'","'.$value.'"),';
                }

            }

            $dotaz = rtrim($dotaz, ',');
            print_r($dotaz);
            Databaze::dotaz($dotaz, array());
            Databaze::dotaz("UPDATE studenti_predmety SET vyplneno = 1 WHERE id_s = ? AND id_p LIKE ? AND id_u LIKE ? AND skolnirok = ?", array($_SESSION["id"], $predmet, $ucitel, Config::getValueFromConfig("skolnirok_id")));
        }
        header("Location: /");
    }

}