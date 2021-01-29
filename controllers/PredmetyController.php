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
                PredmetyController::vytvorDivStudentPredmet($predmet);
            }
            
        }
    }

    public static function vytvorDivStudentPredmet($predmet) {
        echo '<div class="predmet">';
        echo '<a href="/p/' . $predmet["zkratka"].'">';
        echo '<h3 class="nadpis">'.$predmet["nazev"].'</h3>';
        echo '<p class="skupina">'.$predmet["skupina"].'</p>';
        echo '<p class="ucitel">'.$predmet["ucitel"].'</p>';
        echo '</a>';
        echo '</div>';
    }

}

?>