<?php

class Otazka {
    private $id;
    private $druh;
    private $text;
    private $leva;
    private $prava;
    private $skolnirok;

    public function __construct($id, $druh, $text)
    {
        $this->id = $id;
        $this->druh = $druh;
        $this->skolnirok = Config::getValueFromConfig("skolnirok_id");
        if($this->druh == "otevřená") {
            $this->text = $text;
        } else if ($this->druh == "výběrová") {
            list($this->text, $this->leva, $this->prava) = explode(";", $text, 3);    
        }
        
    }

    public function vypisOtazku() {
        echo '<div class="otazka" id="' . $this->id . '">';
        if($this->druh == "otevřená") {
            echo '<p class="textOtazky">' . $this->text . '</p>';
            echo '<input type="text" id="' . $this->id .'" name="' . $this->id .'">';
        } else if ($this->druh == "výběrová") {
            echo '<p class="textOtazky">' . $this->id . '. ' . $this->text . '</p>';
            echo '<span class="levytext">' . $this->leva . '</span>';
            echo '<input type="radio" id="' . $this->id .'" name="' . $this->id .'" value="1">';
            echo '<label for="' . $this->id .'">1</label>';
            echo '<input type="radio" id="' . $this->id .'" name="' . $this->id .'" value="2">';
            echo '<label for="' . $this->id .'">2</label>';
            echo '<input type="radio" id="' . $this->id .'" name="' . $this->id .'" value="3">';
            echo '<label for="' . $this->id .'">3</label>';
            echo '<input type="radio" id="' . $this->id .'" name="' . $this->id .'" value="4">';
            echo '<label for="' . $this->id .'">4</label>';
            echo '<input type="radio" id="' . $this->id .'" name="' . $this->id .'" value="5">';
            echo '<label for="' . $this->id .'">5</label>';
            echo '<span class="pravytext">' . $this->prava . '</span>';
        }
        echo '</div>';
    }

    public static function vypisOtazky(array $otazky) {
        foreach($otazky as $otazka) {
            $o = new Otazka($otazka["id"], $otazka["druh"], $otazka["otazka"]);
            $o->vypisOtazku();
        }
    }

    public static function vratOtazkyProStudenty() {
        $otazky = Databaze::dotaz("SELECT * FROM otazky_pro_studenty WHERE skolnirok LIKE ?", array(Config::getValueFromConfig("skolnirok_id")));
        return $otazky;
    }
    public static function vratOtazkyProUcitele() {
        $otazky = Databaze::dotaz("SELECT * FROM otazky_pro_ucitele WHERE skolnirok LIKE ?", array(Config::getValueFromConfig("skolnirok_id")));
        return $otazky;
    }

    public static function aktualizujOtazkuStudenta($id, $otazka, $druh, $skolnirok) {
        Databaze::dotaz("UPDATE TABLE otazky_pro_studenty SET otazka = ?, druh = ? WHERE skolnirok like ? and id like ?", array($otazka, $druh, $skolnirok, $id));
    }
}