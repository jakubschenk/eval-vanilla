<?php
    if(!isset($_SESSION["viewedRok"])) {
        $_SESSION["viewedRok"] = Config::getValueFromConfig("skolnirok_id");
    }
?>
<h3>Prohlížení odpovědí</h3>
<label for="selectRok" class="mb-2">Vyberte rok pro zobrazení:</label>
<select id="selectRok" class="form-control">
<?php
    $roky = Databaze::dotaz("SELECT * from skolniroky");
    foreach($roky as $rok) {
        if($rok["idr"] == $_SESSION["viewedRok"]) {
            echo '<option class="form-control" value="'. $rok["idr"] . '" selected>'. $rok["idr"] . ' - ' . $rok["rok"] . '</option>';
        } else {
            echo '<option class="form-control" value="'. $rok["idr"] . '">'. $rok["idr"] . ' - ' . $rok["rok"] . '</option>';
        }
        
    }
?>
</select>
<?php
    $tridy = Databaze::dotaz("SELECT s.trida as trida FROM studenti s where s.skolnirok = ? group by s.trida", array($_SESSION["viewedRok"]));
    $predmety = Databaze::dotaz("SELECT sp.id_p as id_p, sp.id_u as id_u, sp.skupina as skupina, s.trida as trida, sum(sp.vyplneno) as vyplneno, count(distinct id_s) as celkem from studenti_predmety sp inner join studenti s on sp.id_s = s.id where sp.skolnirok like ? group by sp.id_p,sp.id_u,sp.skupina,s.trida order by id_p, skupina asc", array($_SESSION["viewedRok"]));
    $otazky = Databaze::dotaz("SELECT * from studenti_otazky where skolnirok like ?", array($_SESSION["viewedRok"]));
    $list = [];
    echo '<div id="tridy">';
    foreach($tridy as $trida) {
?>
  <div class="card">
    <div class="card-header btn" id="nadpis<?php echo $trida["trida"]; ?>" data-toggle="collapse" data-target="#obsah<?php echo $trida["trida"]; ?>" aria-expanded="true" aria-controls="obsah<?php echo $trida["trida"]; ?>">
      <h5 class="mb-0">
        <?php echo $trida["trida"]; ?>  
      </h5>
    </div>

    <div id="obsah<?php echo $trida["trida"]; ?>" class="collapse" aria-labelledby="nadpis<?php echo $trida["trida"]; ?>" data-parent="#tridy">
        <div class="card-body">
            <?php
            echo '<div id="predmety'.$trida["trida"] . '">';
            foreach ($predmety as $predmet) {
                if ($predmet["trida"] == $trida["trida"]) {
                    ?>
                    <div class="card">
                        <div class="card-header collapsed btn" id="nadpis<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"];?>" data-toggle="collapse" data-target="#obsah<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"];?>" aria-expanded="false" aria-controls="obsah<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"];?>"">
                            <h5 class="mb-0">
                            <?php echo $predmet["id_p"].' - '.$predmet["id_u"].' - '.$predmet["skupina"];?>
                            </h5>
                        </div>
                        <div id="obsah<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"];?>" class="collapse" aria-labelledby="nadpis<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"];?>" data-parent="#predmety<?php echo $trida["trida"]; ?>">
        	                <div class="card-body">
                            <?php
                                echo '<div id="otazky' . $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"] . '">';
                                foreach($otazky as $otazka) {
                                    $list[] = $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"].'-'.$otazka["id"];
                                    ?>
                                    <div class="card">
                                        <div class="card-header btn collapsed" id="otazka<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"].'-'.$otazka["id"]; ?>" data-toggle="collapse" data-target="#obsah<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"].'-'.$otazka["id"]; ?>" aria-expanded="false" aria-controls="otazka<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"].'-'.$otazka["id"]; ?>">
                                            <h5 class="mb-0">
                                                <?php
                                                    if($otazka["druh"] == "otevřená") {
                                                        echo $otazka["poradi"] . '. ' . $otazka["otazka"];
                                                    } else {
                                                        list($text, $levy, $pravy) = explode(";", $otazka["otazka"]);
                                                        echo $otazka["poradi"] . '. ' . $text;
                                                    }
                                                ?>
                                            </h5>
                                        </div>
                                        <div id="obsah<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"].'-'.$otazka["id"]; ?>" class="collapse" aria-labelledby="otazka<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"].'-'.$otazka["id"]; ?>" data-parent="#otazky<?php echo $predmet["trida"].'-'.$predmet["id_p"].'-'.$predmet["id_u"].'-'.$predmet["skupina"];?>">
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
<script>var listStudent = [<?php $string = "";foreach($list as $l) {$string = $string .'"'.$l.'",';} $string = rtrim($string, ','); echo $string;?>];</script>
