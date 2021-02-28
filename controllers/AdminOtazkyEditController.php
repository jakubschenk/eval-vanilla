<?php

class AdminOtazkyEditController extends AdminController
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

        $this->vypisOtazky($this->otazky);
    }

    private function vypisOtazky($otazky)
    {
        echo '<script>var pocetOtazek = ' . count($otazky) . ';var otazkyIds = [];</script>';
        echo '<div class="pridatOtazku" id="pridatOtazku">';
        echo '<button class="btn bg-dark text-light" lid="pridatOtazkuBtn" onclick="EditorOtazek.zobrazPridaniOtazky();" value="Nová otázka">Přidat novou otázku</button>';
        echo '<textarea class="d-none" id="textOtazkyInputNova" name="textOtazkyInputNova"></textarea>';
        echo '<textarea class="d-none" id="levytextInputNova" name="levytextInputNova"></textarea>';
        echo '<textarea class="d-none" id="pravytextInputNova" name="pravytextInputNova"></textarea>';
        echo '<label for="vyberDruhuNova" hidden>Vyber druh otázky: </label>';
        echo '<select name="vyberDruhu" id="vyberDruhuNova" onchange="EditorOtazek.zmenTypNove();" hidden>';
        echo '<option value="otevřená">Otevřená</option>';
        echo '<option value="výběrová" selected>Výběřová</option>';
        echo '</select>';
        echo '<button id="pridatNovaBtn" class="btn" onclick="EditorOtazek.pridatOtazku();" value="přidat" hidden>Přidat!</button>';
        echo '</div>';
        foreach ($otazky as $otazka) {       
            $id = $otazka["id"];
            $druh = $otazka["druh"];
            $poradi = $otazka["poradi"];
            echo '<div class="otazka" id="div' . $id . '">';
            if ($druh == "otevřená") {
                $text = $otazka["otazka"];
                $leva = '';
                $prava = '';
                echo '<p class="cisloOtazky">'. $poradi . '.</p>';
                echo '<p class="textOtazky" id="textOtazky' . $id . '" >' . $text . '</p>';
                echo '<textarea id="textOtazkyInput' . $id . '" name="textOtazkyInput' . $id . '" hidden>' . $text . '</textarea>';
                echo '<p id="druh' . $id . '">' . $druh . '</p>';
                echo '<span class="levytext" id="levytext' . $id . '" hidden>' . $leva . '</span><br>';
                echo '<textarea id="levytextInput' . $id . '" name="levytextInput' . $id . '" hidden>' . $leva . '</textarea>';
                echo '<span class="pravytext" id="pravytext' . $id . '" hidden>' . $prava . '</span><br>';
                echo '<textarea id="pravytextInput' . $id . '" name="pravytextInput' . $id . '" hidden>' . $prava . '</textarea>';
                echo '<label for="vyberDruhu' . $id . '" hidden>Vyber druh otázky: </label>';
                echo '<select name="vyberDruhu" id="vyberDruhu' . $id . '" onchange="editory['. $id . '].zmenTyp();" hidden>';
                echo '<option value="otevřená" selected>Otevřená</option>';
                echo '<option value="výběrová">Výběřová</option>';
                echo '</select>';
            } else if ($druh == "výběrová") {
                list($text, $leva, $prava) = explode(";", $otazka["otazka"], 3);
                echo '<p class="cisloOtazky">'. $poradi . '.</p>';
                echo '<p class="textOtazky" id="textOtazky' . $id . '" >' . $text . '</p>';
                echo '<textarea id="textOtazkyInput' . $id . '" name="textOtazkyInput' . $id . '" hidden>' . $text . '</textarea>';
                echo '<p id="druh' . $id . '">' . $druh . '</p>';
                echo '<span class="levytext" id="levytext' . $id . '" >' . $leva . '</span><br>';
                echo '<textarea id="levytextInput' . $id . '" name="levytextInput' . $id . '" hidden>' . $leva . '</textarea>';
                echo '<span id="pravytext' . $id . '" >' . $prava . '</span><br>';
                echo '<textarea id="pravytextInput' . $id . '" name="pravytextInput' . $id . '" hidden>' . $prava . '</textarea>';
                echo '<label for="vyberDruhu' . $id . '" hidden>Vyber druh otázky: </label>';
                echo '<select name="vyberDruhu" id="vyberDruhu' . $id . '" onchange="editory['. $id . '].zmenTyp();" hidden>';
                echo '<option value="otevřená">Otevřená</option>';
                echo '<option value="výběrová" selected>Výběřová</option>';
                echo '</select>';
            }
            echo '<button class="btn btn-dark text-light" id="editBtn' . $id . '" onclick="editory['. $id . '].upravOtazku();">Upravit otázku</button>';
            echo '<button class="btn btn-dark text-light" id="delBtn' . $id . '" onclick="editory['. $id . '].smazOtazku();">Smazat otázku</button>';
            echo '<button class="btn btn-dark text-light" id="ulozitOtazkuBtn' . $id . '" onclick="editory['. $id . '].ulozOtazku();" hidden>Uložit otázku</button>';
            echo '</div>';
            echo '<script>otazkyIds.push('.$id.')</script>';
        }
        echo '<script>var pocetOtazek = ' . count($otazky) . ';</script>';
    }

    public static function zapisUpravenouOtazku($data, $druh) {
        if($druh == "student")
            Otazka::aktualizujOtazkuStudenta($data["id"], $data["otazka"], $data["druh"], Config::getValueFromConfig("skolnirok_id"));
        else if ($druh == "ucitel")
            Otazka::aktualizujOtazkuUcitele($data["id"], $data["otazka"], $data["druh"], Config::getValueFromConfig("skolnirok_id"));
    }

    public static function pridejNovouOtazku($data, $druh) {
        $skolrok = Config::getValueFromConfig("skolnirok_id");
        if($druh == "student") {
            $id = Databaze::dotaz("SELECT id FROM otazky_pro_studenty WHERE skolnirok LIKE ? ORDER BY id DESC LIMIT 1", array($skolrok));
            if($id != null)
                $id = $id[0][0] + 1;
            else
                $id = 1;
            Otazka::pridejOtazkuStudentovi($id, $data["text"], $data["druh"], $skolrok);
        } else if ($druh == "ucitel") {
            $id = Databaze::dotaz("SELECT id FROM otazky_pro_ucitele WHERE skolnirok LIKE ? ORDER BY id DESC LIMIT 1", array($skolrok));
            if($id != null)
                $id = $id[0][0] + 1;
            else
                $id = 1;
            Otazka::pridejOtazkuUciteli($id, $data["text"], $data["druh"], $skolrok);  
        }   
    }

    public static function smazOtazku($id, $druh) {
        $skolrok = Config::getValueFromConfig("skolnirok_id");
        if($druh == "ucitel") {
            Databaze::dotaz("DELETE FROM otazky_pro_ucitele WHERE skolnirok = ? AND id = ?", array($skolrok, $id));
        } else if($druh == "student") {
            Databaze::dotaz("DELETE FROM otazky_pro_studenty WHERE skolnirok = ? AND id = ?", array($skolrok, $id));
        }
        
    }
}
