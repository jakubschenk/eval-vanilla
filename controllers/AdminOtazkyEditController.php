<?php

class AdminOtazkyEditController
{
    private $druh;
    private $skolnirok;
    private $otazky;

    public function __construct($druh)
    {
        $this->druh = $druh;
        $this->skolnirok = Config::getValueFromConfig("skolnirok_id");
        if ($this->druh == "student") {
            $this->otazky = Otazka::vratOtazkyProStudenty();
        } else {
            $this->otazky = Otazka::vratOtazkyProUcitele();
        }

        $this->vypisOtazkyProStudenty($this->otazky);
    }

    private function vypisOtazkyProStudenty($otazky)
    {
        
        foreach ($otazky as $otazka) {
            $id = $otazka["id"];
            $druh = $otazka["druh"];
            echo '<div class="otazka" id="div' . $id . '">';
            if ($druh == "otevřená") {
                $text = $otazka["otazka"];
                $leva = '';
                $prava = '';
                echo '<p class="cisloOtazky">'. $id . '.</p>';
                echo '<p class="textOtazky" id="textOtazky' . $id . '" >' . $text . '</p>';
                echo '<input type="text" id="textOtazkyInput' . $id . '" name="textOtazkyInput' . $id . '" value="' . $text . '" hidden>';
                echo '<p id="druh' . $id . '">' . $druh . '</p>';
                echo '<span class="levytext" id="levytext' . $id . '" hidden>' . $leva . '</span><br>';
                echo '<input type="text" id="levytextInput' . $id . '" name="levytextInput' . $id . '" value="' . $leva . '" hidden>';
                echo '<span class="pravytext" id="pravytext' . $id . '" hidden>' . $prava . '</span><br>';
                echo '<input type="text" id="pravytextInput' . $id . '" name="pravytextInput' . $id . '" value="' . $prava . '" hidden>';
                echo '<label for="vyberDruhu" hidden>Vyber druh otázky: </label>';
                echo '<select name="vyberDruhu" id="vyberDruhu' . $id . '" onchange="zmenTyp('. $id .')" hidden>';
                echo '<option value="otevřená" selected>Otevřená</option>';
                echo '<option value="výběrová">Výběřová</option>';
                echo '</select>';
            } else if ($druh == "výběrová") {
                list($text, $leva, $prava) = explode(";", $otazka["otazka"], 3);
                echo '<p class="cisloOtazky">'. $id . '.</p>';
                echo '<p class="textOtazky" id="textOtazky' . $id . '" >' . $text . '</p>';
                echo '<input type="text" id="textOtazkyInput' . $id . '" name="textOtazkyInput' . $id . '" value="' . $text . '" hidden>';
                echo '<p id="druh' . $id . '">' . $druh . '</p>';
                echo '<span class="levytext" id="levytext' . $id . '" >' . $leva . '</span><br>';
                echo '<input type="text" id="levytextInput' . $id . '" name="levytextInput' . $id . '" value="' . $leva . '" hidden>';
                echo '<span class="pravytext" id="pravytext' . $id . '" >' . $prava . '</span><br>';
                echo '<input type="text" id="pravytextInput' . $id . '" name="pravytextInput' . $id . '" value="' . $prava . '" hidden>';
                echo '<label for="vyberDruhu" hidden>Vyber druh otázky: </label>';
                echo '<select name="vyberDruhu" id="vyberDruhu' . $id . '" onchange="zmenTyp('. $id .')" hidden>';
                echo '<option value="otevřená">Otevřená</option>';
                echo '<option value="výběrová" selected>Výběřová</option>';
                echo '</select>';
            }
            echo '<button id="editBtn' . $id . '" onclick="upravOtazku('. $id . ')">Upravit otázku</button>';
            echo '<button id="ulozitOtazkuBtn' . $id . '" onclick="ulozOtazku(' . $id . ')" hidden>Uložit otázku</button>';
            echo '</div>';
        }
    }

    public static function zapisUpravenouOtazku($data) {

    }
}
