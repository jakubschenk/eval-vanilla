<?php

class AdminUzivateleEditController extends AdminController{
    private $druh;
    
    public function __construct($druh) {
        $this->druh = $druh;
        $this->vypisVsechny();
    }

    private function vypisVsechny() {
        if($this->druh == 'ucitel') {
            $ucitele = Databaze::dotaz("SELECT * from ucitele where skolnirok like ?", array(Config::getValueFromConfig('skolnirok_id')));
            print_r($ucitele);    
        } else {
            $studenti = Databaze::dotaz("SELECT * from studenti where skolnirok like ? order by trida asc", array(Config::getValueFromConfig('skolnirok_id')));
            print_r($studenti);
        }
        
    }

    public static function vypisUzivatele($id, $druh) {
        if($druh == 'ucitel') {

        } else {
            $student = Databaze::dotaz("SELECT * FROM studenti WHERE skolnirok LIKE ? AND id LIKE ?", array(Config::getValueFromConfig('skolnirok_id'), $id));
            $student = $student[0]; 
            ?>
            <div id="student<?php echo $student['id'];?>">
                <?php echo print_r($student);?>
            </div>
            <?php
        }
    }

    public static function vratDuplikaty() {
        $dotaz = Databaze::dotaz("SELECT * from duplikaty WHERE skolnirok LIKE ?", array(Config::getValueFromConfig("skolnirok_id")));
        if($dotaz != array()) {
            return $dotaz;
        } else {
            return null;
        }
    }
}