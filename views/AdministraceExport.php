<h3>Export dat</h3>
<form class="form-group" action="/administrace/export" method="POST">
    <label class="mt-2" for="skolnirok">Vyberte školní rok pro export:</label>
    <select class="form-control" id="skolnirok" name="skolnirok">
        <?php
            $roky = Databaze::dotaz("SELECT * FROM skolniroky");
            foreach($roky as $rok) {
                if($rok["idr"] == Config::getSkolniRok()) {
                    echo '<option value="'.$rok["idr"].'" selected>'. $rok["idr"] . ' - ' .$rok["rok"] . '</option>';
                } else {
                    echo '<option value="'.$rok["idr"].'">'. $rok["idr"] . ' - ' .$rok["rok"] . '</option>';
                }
            }   
        ?>
    </select>
    <label class="mt-2" for="trida">Vyberte třídu pro export:</label>
    <select class="form-control" id="trida" name="trida">
        <?php
            $tridy = Databaze::dotaz("SELECT distinct trida FROM studenti");
            print_r($tridy);
            foreach($tridy as $trida) {
                echo '<option value="'.$trida["trida"].'">'.$trida["trida"] . '</option>';
            }
            echo '<option value="all" selected>Všechny</option>';
        ?>
    </select>
    <label class="mt-2" for="datum_od">Exportovat od:</label>
    <input class="form-control" type="datetime-local" id="datum_od" name="datum_od">
    <label class="mt-2" for="datum_do">Exportovat do:</label>
    <input class="form-control" type="datetime-local" id="datum_do" name="datum_do">
    <button class="btn btn-dark mt-2" type="submit">Export!</button>
</form>