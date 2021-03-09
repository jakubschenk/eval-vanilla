<div class="card">
    <div class="card-header">
        <h4>Nastavení</h4>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-header">
                <h5>Datum přístupu</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="datum_od">Přístup od: </label>
                    <input type="datetime-local" id="datum_od" name="datum_od" class="form-control" value="<?php $myvar; if(($myvar = Config::getValueFromConfig("pristup_od")) != null) {echo $myvar;} ?>">
                    <label class="mt-2" for="datum_do">Přístup do: </label>
                    <input type="datetime-local" id="datum_do" name="datum_do" class="form-control" value="<?php $myvar; if(($myvar = Config::getValueFromConfig("pristup_do")) != null) {echo $myvar;} ?>">
                    <button class="btn btn-dark mt-2" type="button" id="zmenDatumBtn">Změň datum přístupu!</button>
                </div>
            </div>
        </div>
    </div>
</div>