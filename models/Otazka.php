<?php

class Otazka
{
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
        if ($this->druh == "otevřená") {
            $this->text = $text;
        } else if ($this->druh == "výběrová") {
            list($this->text, $this->leva, $this->prava) = explode(";", $text, 3);
        }
    }

    public function vypisOtazku()
    {
        echo '<div class="card otazka mb-4" id="div' . $this->id . '">';
        if ($this->druh == "otevřená") {
            echo '<div class="card-header">';
            echo '<h4 class="card-title my-auto">' . $this->id . '. '  . $this->text . '</h4>';
            echo '</div>';
            echo '<div class="container mx-auto card-body">';
            echo '<textarea class="form-control" id="text' . $this->id . '" name="' . $this->id . '"></textarea>';
            echo '</div>';
        } else if ($this->druh == "výběrová") {
?>
            <div class="card-header">
                <h4 class="card-title my-auto"><?php echo $this->id . '. ' . $this->text; ?></h4>
            </div>
            <div class="card-body row textFix">
                <div class=""><?php echo $this->leva; ?></div>
                <div class="radio-wrapper text-center btn-group">
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="1-<?php echo $this->id; ?>">
                            1
                            <input type="radio" id="1-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="1">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="2-<?php echo $this->id; ?>">
                            2
                            <input type="radio" id="2-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="2">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="3-<?php echo $this->id; ?>">
                            3
                            <input type="radio" id="3-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="3">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="4-<?php echo $this->id; ?>">
                            4
                            <input type="radio" id="4-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="4">
                        </label>
                    </div>
                    <div class="form-check form-check-inline w-20">
                        <label class="form-check-label radio-label-vertical" for="5-<?php echo $this->id; ?>">
                            5
                            <input type="radio" id="5-<?php echo $this->id; ?>" name="<?php echo $this->id; ?>" value="5">
                        </label>
                    </div>
                </div>
                <div class=""><?php echo $this->prava; ?></div>
            </div>

<?php
        }
        echo '</div>';
    }

    public static function vypisOtazky($predmet, $ucitel, array $otazky)
    {
        echo '<h2>' . $predmet . ' - ' . $ucitel . '</h2>';
        echo '<form class="form mx-auto my-4 pb-2" action="/p/' . $ucitel . '/' . $predmet . '/submit" method="post">';
        foreach ($otazky as $otazka) {
            $o = new Otazka($otazka["id"], $otazka["druh"], $otazka["otazka"]);
            $o->vypisOtazku();
        }
        echo '<input class="btn btn-secondary" type="submit" name="Odeslat" value="Submit">';
        echo '</form>';
    }

    public static function vratOtazkyProStudenty()
    {
        $otazky = Databaze::dotaz("SELECT * FROM otazky_pro_studenty WHERE skolnirok LIKE ?", array(Config::getValueFromConfig("skolnirok_id")));
        return $otazky;
    }
    public static function vratOtazkyProUcitele()
    {
        $otazky = Databaze::dotaz("SELECT * FROM otazky_pro_ucitele WHERE skolnirok LIKE ?", array(Config::getValueFromConfig("skolnirok_id")));
        return $otazky;
    }

    public static function aktualizujOtazkuStudenta($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("UPDATE otazky_pro_studenty SET otazka = ?, druh = ? WHERE skolnirok like ? and id like ?", array($otazka, $druh, $skolnirok, $id));
    }

    public static function aktualizujOtazkuUcitele($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("UPDATE otazky_pro_ucitele SET otazka = ?, druh = ? WHERE skolnirok like ? and id like ?", array($otazka, $druh, $skolnirok, $id));
    }

    public static function pridejOtazkuStudentovi($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("INSERT INTO otazky_pro_studenty(id, otazka, druh, skolnirok) VALUES(?, ?, ?, ?)", array($id, $otazka, $druh, $skolnirok));
    }
    public static function pridejOtazkuUciteli($id, $otazka, $druh, $skolnirok)
    {
        Databaze::dotaz("INSERT INTO otazky_pro_ucitele(id, otazka, druh, skolnirok) VALUES(?, ?, ?, ?)", array($id, $otazka, $druh, $skolnirok));
    }
}
