<?php

class OtazkyController extends Controller {
    private $predmety;
    private $predmet;
    private $id;

    public function __construct($predmet) {
        $this->predmet = $predmet;
        if(Uzivatel::typChooser($_SESSION["email"]) == 'student') {
            $this->id = Student::getId($_SESSION["email"]);
            $this->predmety = Predmet::vratPredmetyProStudenta($this->id);
            $shoda = 0;
            foreach($this->predmety as $povolenePredmety) {
                if($povolenePredmety["zkratka"] == $this->predmet) {
                    $shoda = 1;
                    break;
                }
            }
        }
        
        if($shoda) {
            echo "Zobrazuji otazky pro id ". $this->id . ' v predmetu '. $this->predmet;
        } else {
            echo "neplatny predmet";
        }

    }
}
?>