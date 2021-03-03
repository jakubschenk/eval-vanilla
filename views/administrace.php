<?php
    if(!isset($_SESSION["viewedRok"])) {
        $_SESSION["viewedRok"] = Config::getValueFromConfig("skolnirok_id");
    }
?>
<label for="selectRok" class="mb-2">Vyberte rok pro zobrazení:</label>
<select id="selectRok" class="form-control">
<?php
    $roky = Databaze::dotaz("SELECT * from skolniroky");
    foreach($roky as $rok) {
        echo '<option class="form-control" value="'. $rok["idr"] . '">'. $rok["idr"] . ' - ' . $rok["rok"] . '</option>';
    }
?>
</select>
<?php
    $tridy = Databaze::dotaz("SELECT st.trida FROM studenti_odpovedi st join studenti_otazky sot on st.id_o = sot.id where sot.skolnirok = ? group by st.trida", array($_SESSION["viewedRok"]));
    echo '<div id="accordion">';
    foreach($tridy as $trida) {
?>
    <div class="card">
        <div class="card-header btn btn-link" id="<?php echo $trida["trida"];?>" data-toggle="collapse" data-target="col<?php echo $trida["trida"];?>" aria-expanded="false" aria-controls="col<?php echo $trida["trida"];?>">
            <h5 class="mb-0">
                    <?php echo $trida["trida"];?>    
            </h5>
        </div>

        <div id="col<?php echo $trida["trida"];?>" class="collapse" aria-labelledby="<?php echo $trida["trida"];?>" data-parent="#accordion">
            <div class="card-body">
            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
            </div>
        </div>
    </div>
  <?php
}
?>
</div>