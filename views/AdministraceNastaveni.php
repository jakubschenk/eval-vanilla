<div class="card mb-5">
    <div class="card-header">
        <h4>Nastavení</h4>
    </div>
    <div class="card-body">
        <div class="card">
            <div class="card-header">
                <h5>Školní rok</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <form action="/administrace/nastaveni/zmenSkolniRok" method="post">
                        <label for="skolnirok">Vybrat školní rok: </label>
                        <select id="skolnirok" name="skolnirok" class="form-control">
                            <?php
                            $skolniroky = Databaze::dotaz("SELECT * from skolniroky");
                            foreach ($skolniroky as $skolnirok) {
                                if ($skolnirok["idr"] == Config::getSkolniRok())
                                    echo '<option value="' . $skolnirok["idr"] . '"selected>' . $skolnirok["idr"] . ' - ' . $skolnirok["rok"] . '</option>';
                                else
                                    echo '<option value="' . $skolnirok["idr"] . '">' . $skolnirok["idr"] . ' - ' . $skolnirok["rok"] . '</option>';
                            }
                            ?>
                        </select>
                        <input type="submit" class="btn btn-dark mt-2" id="zmenRokBtn" value="Změn aktivní rok!"></input>
                    </form>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <h5>Datum přístupu</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="datum_od">Přístup od: </label>
                    <input type="datetime-local" id="datum_od" name="datum_od" class="form-control" value="<?php
                        $myvar;
                        if (($myvar = Config::getValueFromConfig("pristup_od")) != null) {
                            echo $myvar;
                        }
                    ?>">
                    <label class="mt-2" for="datum_do">Přístup do: </label>
                    <input type="datetime-local" id="datum_do" name="datum_do" class="form-control" value="<?php
                        $myvar;
                        if (($myvar = Config::getValueFromConfig("pristup_do")) != null) {
                            echo $myvar;
                        } 
                    ?>">
                    <button class="btn btn-dark mt-2" type="button" id="zmenDatumBtn">Změň datum přístupu</button>
                    <button class="btn btn-dark mt-2" type="button" id="smazPristupBtn">Vypnout přístup</button>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <h5>Změna hesla</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="heslo_stare">Staré heslo: </label>
                    <input type="password" id="heslo_stare" name="heslo_stare" class="form-control">
                    <label for="heslo_nove">Nové heslo: </label>
                    <input type="password" id="heslo_nove" name="heslo_nove" class="form-control">
                    <label for="heslo_nove_potvrdit">Potvrdit nové heslo: </label>
                    <input type="password" id="heslo_nove_potvrdit" name="heslo_nove_potvrdit" class="form-control">
                    <button class="btn btn-dark mt-2" type="button" id="zmenHesloBtn">Změnit heslo!</button>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <h5>Přidat administrátora</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <?php include "views/AdministraceRegistrace.php"; ?>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header">
                <h5>Smazat administrátora</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <?php
                    $admini = Administrator::vratAdministratory();
                    ?>
                    <table class="table">
                        <thead>
                            <thead>
                                <th scope="col">Login</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Smazat</th>
                            </thead>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($admini as $admin) {
                            ?>
                                <tr>
                                    <td><?php echo $admin["jmeno"] ?></td>
                                    <td><?php echo $admin["email"] ?></td>
                                    <td><button class="btn btn-dark" onclick="smazAdmina('<?php echo $admin['jmeno']; ?>');">Smazat</button></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>