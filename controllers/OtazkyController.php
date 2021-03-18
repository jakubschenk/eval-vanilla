<?php

class OtazkyController extends Controller
{
    private $predmety;
    private $predmet;
    private $ucitel_id;
    private $trida;
    private $skupina;
    private $id;

    public function __construct($predmet, array $uts)
    { // ucitel, trida, skupina, pouzivame jeden controller na nekolik veci tak pouzivame pole
        $this->predmet = $predmet;
        if ($_SESSION['druh'] == 'student') {
            $this->ucitel_id = $uts['ucitel'];
            $this->id = Student::getId($_SESSION["email"]);
            $this->predmety = Predmet::vratPredmetyProStudenta($this->id);
            $shoda = 0;
            foreach ($this->predmety as $povolenePredmety) {
                if ($povolenePredmety["zkratka"] == $this->predmet && $povolenePredmety["u_id"] == $this->ucitel_id) {
                    $shoda = 1;
                    break;
                }
            }
            if ($shoda) {
                Otazka::vypisOtazkyStudent($this->predmet, $this->ucitel_id, Otazka::vratOtazkyProStudenty());
            } else {
                echo "neplatny predmet";
            }
        } else if ($_SESSION['druh'] == 'ucitel') {
            $this->trida = $uts['trida'];
            $this->skupina = $uts['skupina'];
            $this->id = Ucitel::getId($_SESSION["email"]);
            $this->predmety = Predmet::vratPredmetyProUcitele($this->id);
            $shoda = 0;
            foreach ($this->predmety as $povolenePredmety) {
                if ($povolenePredmety["zkratka"] == $this->predmet && $povolenePredmety["trida"] == $this->trida && $povolenePredmety['skupina'] == $this->skupina) {
                    $shoda = 1;
                    break;
                }
            }
            if ($shoda) {
                Otazka::vypisOtazkyUcitel($this->predmet, $this->trida, $this->skupina, Otazka::vratOtazkyProUcitele());
            } else {
                echo "neplatny predmet";
            }
        }
    }
    public static function zpracuj($predmet, $ucitel)
    {
        if (!PredmetyController::vyplneno($predmet, $ucitel, $_SESSION["email"], $_SESSION["druh"]) && $_SESSION["druh"] == 'student') {
            $dotaz = "INSERT INTO studenti_odpovedi(id_p, id_u, id_o, trida, skupina, odpoved, datum) VALUES";
            $skupina = Databaze::dotaz(
                "SELECT skupina from studenti_predmety where id_s = ? and id_u = ? and id_p = ? and skolnirok = ?",
                array($_SESSION["id"], $ucitel, $predmet, Config::getValueFromConfig("skolnirok_id"))
            );
            $trida = Databaze::dotaz("SELECT trida from studenti where id = ? and skolnirok = ?", array($_SESSION["id"], Config::getValueFromConfig("skolnirok_id")));
            foreach ($_POST as $key => $value) {
                if ($key != "Odeslat") {
                    $dotaz = $dotaz . '("' . $predmet . '","' . $ucitel . '",' . $key . ',"' . $trida[0]["trida"] . '","' . $skupina[0]["skupina"] . '","' . utf8_encode(str_replace(array("\n", "\r","\'", "\""), " ", htmlspecialchars($value))) . '","' . date('Y-m-d H:i:s') .'"),';
                }
            }

            $dotaz = rtrim($dotaz, ',');
            Databaze::dotaz($dotaz, array());
            Databaze::dotaz("UPDATE studenti_predmety SET vyplneno = 1 WHERE id_s = ? AND id_p LIKE ? AND id_u LIKE ? AND skolnirok = ?", array($_SESSION["id"], $predmet, $ucitel, Config::getValueFromConfig("skolnirok_id")));
        }
        header("Location: /");
    }

    public static function zpracujUcitel($predmet, $trida, $skupina)
    {
        if (Databaze::dotaz("SELECT vyplneno FROM ucitele_predmety where id_u = ? and id_p like ? and trida like ? and skupina like ? and skolnirok = ?", array($_SESSION["id"], $predmet, $trida, $skupina, Config::getValueFromConfig("skolnirok_id")))[0][0] == 0) {
            $dotaz = "INSERT INTO ucitele_odpovedi(id_p, trida, skupina, id_o, odpoved, datum) VALUES";
            $skupina = urldecode($skupina);
            foreach ($_POST as $key => $value) {
                if ($key != "Odeslat") {
                    $dotaz = $dotaz . '("' . $predmet . '","' . $trida . '","' . $skupina . '",' . $key . ',"' . htmlspecialchars($value) . '","' . date('Y-m-d H:i:s') .'"),';
                }
            }

            $dotaz = rtrim($dotaz, ',');
            Databaze::dotaz($dotaz, array());
            Databaze::dotaz("UPDATE ucitele_predmety SET vyplneno = 1 WHERE id_u = ? AND id_p LIKE ? AND trida LIKE ? AND skupina LIKE ? AND skolnirok = ?", array($_SESSION["id"], $predmet, $trida, $skupina, Config::getValueFromConfig("skolnirok_id")));

            header("Location: /");
        } else {
            header("Location: /"); 
        }
    }
}
