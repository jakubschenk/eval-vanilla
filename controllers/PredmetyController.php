<?php

class PredmetyController extends Controller {
    
    public static function vypisPredmety() {
        if($_SESSION["typ"] == 1) {
            $id = Ucitel::getId($_SESSION["email"]);
            $predmety = Predmet::vratPredmetyProUcitele($id);
            print_r($predmety);

        } else if($_SESSION["typ"] == 2) {
            $id = Student::getId($_SESSION["email"]);
            $predmety = Predmet::vratPredmetyProStudenta($id);
            print_r($predmety);
        }
    }

}

?>