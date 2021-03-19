<?php
if (!isset($_SESSION["viewedRok"])) {
    $_SESSION["viewedRok"] = Config::getValueFromConfig("skolnirok_id");
}
?>
<h3>Prohlížení odpovědí učitelů</h3>
<label for="selectRok" class="mb-2">Vyberte rok pro zobrazení:</label>
<select id="selectRok" class="form-control">
    <?php
    $roky = Databaze::dotaz("SELECT * from skolniroky");
    foreach ($roky as $rok) {
        if ($rok["idr"] == $_SESSION["viewedRok"]) {
            echo '<option class="form-control" value="' . $rok["idr"] . '" selected>' . $rok["idr"] . ' - ' . $rok["rok"] . '</option>';
        } else {
            echo '<option class="form-control" value="' . $rok["idr"] . '">' . $rok["idr"] . ' - ' . $rok["rok"] . '</option>';
        }
    }
    ?>
</select>
<?php
$tridy = Databaze::dotaz("SELECT up.trida as trida FROM ucitele_predmety up where up.skolnirok = ? group by up.trida", array($_SESSION["viewedRok"]));
$predmety = Databaze::dotaz("SELECT up.id_p as id_p, up.trida as trida, up.skupina as skupina, sum(up.vyplneno) as vyplneno from ucitele_predmety up where up.skolnirok like ? group by up.id_p,up.skupina,up.trida order by id_p, skupina asc", array($_SESSION["viewedRok"]));
$otazky = Databaze::dotaz("SELECT * from ucitele_otazky where skolnirok like ?", array($_SESSION["viewedRok"]));
$list = [];
echo '<div id="tridy">';
foreach ($tridy as $trida) { //tisk vsech trid pomoci collapsible bootstrap karet https://getbootstrap.com/docs/4.0/components/collapse/
?>
    <div class="card mt-2">
        <div class="card-header btn" id="nadpis<?php echo $trida["trida"]; ?>" data-toggle="collapse" data-target="#obsah<?php echo $trida["trida"]; ?>" aria-expanded="true" aria-controls="obsah<?php echo $trida["trida"]; ?>">
            <h5 class="mb-0">
                <?php echo $trida["trida"]; ?>
            </h5>
        </div>

        <div id="obsah<?php echo $trida["trida"]; ?>" class="collapse" aria-labelledby="nadpis<?php echo $trida["trida"]; ?>" data-parent="#tridy">
            <div class="card-body">
                <?php
                echo '<div id="predmety' . $trida["trida"] . '">';
                foreach ($predmety as $predmet) { //vnorene karty predmetu
                    if ($predmet["trida"] == $trida["trida"]) {
                        $predmetIdentifikator = $predmet["trida"] . '-' . $predmet["id_p"] . '-' . $predmet["skupina"];
                ?>
                        <div class="card mt-2">
                            <div class="card-header collapsed btn" id="nadpis<?php echo $predmetIdentifikator; ?>" data-toggle="collapse" data-target="#obsah<?php echo $predmetIdentifikator; ?>" aria-expanded="false" aria-controls="obsah<?php echo $predmetIdentifikator; ?>">
                                <div class="mb-0 text-left d-inline-flex justify-content-between w-100">
                                    <h5>
                                        <?php echo $predmet["id_p"] . ' - ' . $predmet["skupina"]; ?>
                                    </h5>
                                    <div>
                                        <h5>
                                            <?php echo ($predmet["vyplneno"] == 1) ? "Ano" : "Ne"; ?>
                                        </h5>
                                        <?php if ($predmet["vyplneno"] > 0) {
                                            echo '<a href="/administrace/ucitel/prohlizeni/smaz/' . $predmetIdentifikator . '" class="btn btn-dark ml-2">Smaž odpovědi</a>';
                                        } ?>
                                    </div>

                                </div>
                            </div>
                            <?php
                            if ($predmet["vyplneno"] == 1) {
                            ?>
                                <div id="obsah<?php echo $predmetIdentifikator; ?>" class="collapse" aria-labelledby="nadpis<?php echo $predmetIdentifikator; ?>" data-parent="#predmety<?php echo $trida["trida"]; ?>">
                                    <div class="card-body">
                                        <?php
                                        echo '<div id="otazky' . $predmet["trida"] . '-' . $predmet["id_p"] . '-' . $predmet["skupina"] . '">';
                                        foreach ($otazky as $otazka) {
                                            $otazkaIdentifikator = $predmetIdentifikator . '-' . $otazka["id"];
                                            $list[] = $otazkaIdentifikator;
                                        ?>
                                            <div class="card mt-2">
                                                <div class="card-header btn collapsed" id="otazka<?php echo $otazkaIdentifikator; ?>" data-toggle="collapse" data-target="#obsah<?php echo $otazkaIdentifikator; ?>" aria-expanded="false" aria-controls="otazka<?php echo $otazkaIdentifikator ?>">
                                                    <h5 class="mb-0">
                                                        <?php
                                                        if ($otazka["druh"] == "otevřená") {
                                                            echo $otazka["poradi"] . '. ' . $otazka["otazka"];
                                                        } else {
                                                            list($text, $levy, $pravy) = explode(";", $otazka["otazka"]);
                                                            echo $otazka["poradi"] . '. ' . $text;
                                                        }
                                                        ?>
                                                    </h5>
                                                </div>
                                                <div id="obsah<?php echo $otazkaIdentifikator; ?>" class="collapse" aria-labelledby="otazka<?php echo $otazkaIdentifikator; ?>" data-parent="#otazky<?php echo $predmetIdentifikator; ?>">
                                                    <div class="card-body">
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        echo '</div>';
                                        ?>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                <?php
                    }
                }
                echo '</div>';
                ?>
            </div>
        </div>
    </div>
<?php
}
?>
</div>
<script>
    var listUcitel = [<?php $string = "";
                        foreach ($list as $l) {
                            $string = $string . '"' . $l . '",';
                        }
                        $string = rtrim($string, ',');
                        echo $string; ?>];
</script>