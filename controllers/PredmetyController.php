<?php

class PredmetyController extends Controller {
    
    public static function vypisPredmety() {
        if($_SESSION["typ"] == 1) {
            $id = Ucitel::getId($_SESSION["email"]);
            //$id = Ucitel::getId("r.nowak@spseiostrava.cz");
            $predmety = Predmet::vratPredmetyProUcitele($id);
            print_r($predmety);
        } else if($_SESSION["typ"] == 2) {
            $id = Student::getId($_SESSION["email"]);
            $predmety = Predmet::vratPredmetyProStudenta($id);
            foreach($predmety as $predmet) {
                if(PredmetyController::vyplneno($predmet["zkratka"], $predmet["u_id"], $_SESSION["email"], $_SESSION["druh"]));
                PredmetyController::vytvorDivStudentPredmet($predmet);
            }
            
        }
    }

    public static function vytvorDivStudentPredmet($predmet) {
        echo '<div class="predmet">';
        echo '<a href="/p/' . $predmet["u_id"] . '/' .$predmet["zkratka"].'">';
        echo '<h3 class="nadpis">'.$predmet["nazev"].'</h3>';
        echo '<p class="skupina">'.$predmet["skupina"].'</p>';
        echo '<p class="ucitel">'.$predmet["ucitel"].'</p>';
        echo '</a>';
        echo '</div>';
    }

    public static function vyplneno($predmet, $ucitel, $email, $druh) {
        if($druh == "student") { 
            $skolrok = Config::getValueFromConfig("skolnirok_id");
            $dotaz = Databaze::dotaz("SELECT vyplneno FROM studenti_predmety WHERE id_s = ? AND id_p LIKE ? AND id_u LIKE ? AND skolnirok = ?", array($_SESSION["id"], $predmet, $ucitel, $skolrok));
            if($dotaz[0]["vyplneno"] == 0) {
                return false;
            } else {
                return true;
            }
        }

    }

}

?>