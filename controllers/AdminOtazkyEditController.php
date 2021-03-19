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
?>
        <script>
            var otazky = <?php echo json_encode($otazky); ?>;
            console.log(otazky);
        </script>
        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#pridatOtazku" aria-expanded="false" aria-controls="pridaniOtazky">Přidat otázku</button>
        <div class="collapse my-2" id="pridatOtazku">
            <div class="card card-body form-group">
                <label for="vyberDruhuNova">Vyberte druh otázky: </label>
                <select name="vyberDruhu" class="form-control" id="vyberDruhuNova">
                    <option value="otevřená">Otevřená</option>
                    <option value="výběrová" selected>Výběřová</option>
                </select>
                <label for="textOtazkyInputNova">Název otázky:</label>
                <textarea id="textOtazkyInputNova" class="form-control" name="textOtazkyInputNova"></textarea>
                <label for="levytextInputNova">Levá strana hodnocení:</label>
                <textarea id="levytextInputNova" class="form-control" name="levytextInputNova"></textarea>
                <label for="pravytextInputNova">Pravá strana hodnocení:</label>
                <textarea id="pravytextInputNova" class="form-control" name="pravytextInputNova"></textarea>
                <button id="pridatNovaBtn" class="btn btn-dark" value="přidat">Přidat!</button>
            </div>
        </div>
        <div id="otazky">
            <?php
            foreach ($otazky as $otazka) {
                $id = $otazka["id"];
                $druh = $otazka["druh"];
                $poradi = $otazka["poradi"];
                if ($druh == "otevřená") {
                    $text = $otazka["otazka"];
                    $leva = '';
                    $prava = '';
                } else {
                    list($text, $leva, $prava) = explode(";", $otazka["otazka"], 3);
                }
            ?>
                <div class="card collapsed mx-auto my-2">
                    <div class="card-header" id="otazkaNadpis<?php echo $id; ?>"  data-toggle="collapse" data-target="#otazka<?php echo $id; ?>" aria-expanded="false" aria-controls="otazka<?php echo $id; ?>">
                        <h5 class="mb-0" id="nadpis<?php echo $id; ?>">
                            <?php echo $poradi . '. ' . $text; ?>
                        </h5>
                    </div>
                    <div id="otazka<?php echo $id; ?>" class="collapse" aria-labelledby="otazkaNadpis<?php echo $id; ?>" data-parent="#otazky">
                        <div class="card-body">
                            <label for="vyberDruhu<?php echo $id; ?>">Vyberte druh otázky: </label>
                            <select name="vyberDruhu" class="form-control" id="vyberDruhu<?php echo $id; ?>">
                                <option value="otevřená">Otevřená</option>
                                <option value="výběrová">Výběřová</option>
                            </select>
                            <label for="textOtazkyInput<?php echo $id; ?>">Název otázky:</label>
                            <textarea id="textOtazkyInput<?php echo $id; ?>" class="form-control" name="textOtazkyInput<?php echo $id; ?>"><?php echo $text; ?></textarea>
                            <label for="levytextInput<?php echo $id; ?>">Levá strana hodnocení:</label>
                            <textarea id="levytextInput<?php echo $id; ?>" class="form-control" name="levytextInput<?php echo $id; ?>"><?php echo $leva; ?></textarea>
                            <label for="pravytextInput<?php echo $id; ?>">Pravá strana hodnocení:</label>
                            <textarea id="pravytextInput<?php echo $id; ?>" class="form-control" name="pravytextInput<?php echo $id; ?>"><?php echo $prava; ?></textarea>
                            <button class="btn btn-dark text-light mt-2" id="delBtn<?php echo $id; ?>">Smazat otázku</button>
                            <button class="btn btn-dark text-light mt-2" id="ulozitOtazkuBtn<?php echo $id; ?>">Uložit otázku</button>
                            <button class="btn btn-dark text-light mt-2" id="ukazZmenu<?php echo $id; ?>">Změnit číslo</button><br/>
                            <label for="noveCisloPro<?php echo $id; ?>" class="mt-2 d-none">Nové číslo otázky:</label>
                            <input type="number" class="form-control d-none" name="noveCisloPro<?php echo $id; ?>" id="noveCisloPro<?php echo $id; ?>" value="<?php echo $poradi; ?>">
                            <button class="btn btn-dark text-light mt-2 d-none" id="zmenCislo<?php echo $id; ?>">OK!</button>
                        </div>
                    </div>
                </div>
            <?php
            }
    }

    public static function zapisUpravenouOtazku($data, $druh)
    {
        if ($druh == "student")
            Otazka::aktualizujOtazkuStudenta($data["id"], $data["otazka"], $data["druh"], Config::getValueFromConfig("skolnirok_id"));
        else if ($druh == "ucitel")
            Otazka::aktualizujOtazkuUcitele($data["id"], $data["otazka"], $data["druh"], Config::getValueFromConfig("skolnirok_id"));
    }

    public static function pridejNovouOtazku($data, $druh)
    {
        $skolrok = Config::getValueFromConfig("skolnirok_id");
        if ($druh == "student") {
            $poradi = Databaze::dotaz("SELECT poradi FROM studenti_otazky WHERE skolnirok LIKE ? ORDER BY id DESC LIMIT 1", array($skolrok));
            if ($poradi != null)
                $poradi = $poradi[0][0] + 1;
            else
                $poradi = 1;
            Otazka::pridejOtazkuStudentovi($poradi, $data["text"], $data["druh"], $skolrok);
        } else if ($druh == "ucitel") {
            $poradi = Databaze::dotaz("SELECT poradi FROM ucitele_otazky WHERE skolnirok LIKE ? ORDER BY id DESC LIMIT 1", array($skolrok));
            if ($poradi != null)
                $poradi = $poradi[0][0] + 1;
            else
                $poradi = 1;
            Otazka::pridejOtazkuUciteli($poradi, $data["text"], $data["druh"], $skolrok);
        }
    }

    public static function smazOtazku($id, $druh)
    {
        $skolrok = Config::getValueFromConfig("skolnirok_id");
        if ($druh == "ucitel") {
            $otazka = Databaze::dotaz("SELECT * from ucitele_otazky WHERE id = ?", array($id));
            Databaze::dotaz("UPDATE ucitele_otazky SET poradi = poradi-1 WHERE poradi > ? AND skolnirok LIKE ?", array($otazka[0]["poradi"], $skolrok));
            Databaze::dotaz("DELETE FROM ucitele_otazky WHERE skolnirok = ? AND id = ?", array($skolrok, $id));
        } else if ($druh == "student") {
            $otazka = Databaze::dotaz("SELECT * from studenti_otazky WHERE id = ?", array($id));
            Databaze::dotaz("UPDATE studenti_otazky SET poradi = poradi-1 WHERE poradi > ? AND skolnirok LIKE ?", array($otazka[0]["poradi"], $skolrok));
            Databaze::dotaz("DELETE FROM studenti_otazky WHERE skolnirok = ? AND id = ?", array($skolrok, $id));
        }
    }

    public static function zmenCislo($id, $druh, $cisloStare, $cisloNove) {
        $skolrok = Config::getValueFromConfig("skolnirok_id");
        if ($druh == "ucitel") {
            Databaze::dotaz("UPDATE ucitele_otazky SET poradi = 999 WHERE poradi = ?", array($cisloStare));
            Databaze::dotaz("UPDATE ucitele_otazky SET poradi = ? WHERE poradi = ?", array($cisloStare, $cisloNove));
            Databaze::dotaz("UPDATE ucitele_otazky SET poradi = ? WHERE poradi = 999", array($cisloNove));
        } else if ($druh == "student") {
            Databaze::dotaz("UPDATE studenti_otazky SET poradi = 999 WHERE poradi = ?", array($cisloStare));
            Databaze::dotaz("UPDATE studenti_otazky SET poradi = ? WHERE poradi = ?", array($cisloStare, $cisloNove));
            Databaze::dotaz("UPDATE studenti_otazky SET poradi = ? WHERE poradi = 999", array($cisloNove));
        }
    }
}
