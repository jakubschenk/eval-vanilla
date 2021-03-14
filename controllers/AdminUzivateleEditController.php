<?php

class AdminUzivateleEditController extends AdminController
{
    private $druh;

    public function __construct($druh)
    {
        $this->druh = $druh;
        $this->vypisVsechny();
    }

    private function vypisVsechny()
    {
        if ($this->druh == 'ucitel') {
            $ucitele = Ucitel::vratUcitele();
            self::printTableHead("uzivatele", $this->druh);
            foreach ($ucitele as $uc) {
                self::vypisUzivatele($uc, $this->druh);
            }
            echo '</tbody>';
            echo '</table>';
        } else if ($this->druh == 'student') {
            $studenti = Student::vratStudenty();
            self::printTableHead("uzivatele", $this->druh);
            foreach ($studenti as $st) {
                self::vypisUzivatele($st, $this->druh);
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            require_once '404.php';
        }
    }

    public static function printTableHead($name, $druh)
    {
?>
        <table class="table table-striped" id="<?php echo $name; ?>">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Email</th>
                    <th scope="col">Jméno</th>
                    <?php
                    if ($druh == 'student' || $druh == 'duplikat')
                        echo '<th scope="col">Třída</th>';
                    if ($druh != 'duplikat')
                        echo '<th scope="col">Byl přihlášen?</th>';
                    ?>         
                    <th scope="col">Změnit</th>
                </tr>
            </thead>
            <tbody>
<?php
    }

        public static function vypisUzivatele($uzivatel, $druh)
        {
            ?>
                <tr>
                    <td class="align-middle"><?php echo $uzivatel['id']; ?></td>
                    <td class="align-middle"><?php echo $uzivatel['email']; ?></td>
                    <td class="align-middle"><?php echo $uzivatel['jmeno'] . ' ' . $uzivatel['prijmeni']; ?></td>
                    <?php

                    if ($druh == 'student') {
                    ?>                
                        <td class="align-middle"><?php echo $uzivatel['trida']; ?></td>
                        <td class="align-middle"><?php echo ($uzivatel['gid'] != null) ? ('Ano') : ('Ne'); ?></td>
                    <?php
                    } else if ($druh == 'duplikat') {
                    ?>
                        <td class="align-middle"><?php echo $uzivatel['trida']; ?></td>
                    <?php
                    } else {
                    ?>
                        <td class="align-middle"><?php echo ($uzivatel['gid'] != null) ? ('Ano') : ('Ne'); ?></td>
                    <?php
                    }
                    ?>
                    <td class="align-middle"><a class="btn btn-dark" href="<?php echo $uzivatel['id']; ?>/upravit">Upravit</a></td>
                </tr>
        <?php
        }

        public static function vratDuplikaty()
        {
            $dotaz = Databaze::dotaz("SELECT d.id_studenta as id, s.trida as trida, s.email as email, s.jmeno as jmeno, s.prijmeni as prijmeni, s.gid as gid from duplikaty d inner join studenti s on s.id = d.id_studenta WHERE d.skolnirok LIKE ?", array(Config::getValueFromConfig("skolnirok_id")));
            if ($dotaz != array()) {
                return $dotaz;
            } else {
                return null;
            }
        }

        public static function vypisUzivateleProEdit($id, $druh)
        {
            if ($druh == 'student') {
                echo '<h2 class="mb-2">Upravit studenta</h2>';
                $uzivatel = Databaze::dotaz("SELECT * from studenti where id like ?", array($id))[0];
                ?>
                    <div class="card" id="uzivatel">
                        <div class="card-header">
                            <?php echo $uzivatel["trida"] . ' - ' . $uzivatel["jmeno"] . ' ' . $uzivatel["prijmeni"]; ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group form-group-lg">
                            <form action="/administrace/<?php echo $druh;?>/uzivatele/<?php echo $id?>/upravit" method="post">
                                <label for="email">Email: </label>
                                <input type="text" class="form-control" name="email" id="email" value="<?php echo $uzivatel["email"]; ?>"/>
                                <button type="submit" class="mt-2 btn btn-dark float-sm-right">Aktualizovat!</button>
                            </form>
                            </div>
                        </div>
                    </div>
                <?php
            } else if ($druh == 'ucitel') {
                echo '<h2 class="mb-2">Upravit učitele</h2>';
                $uzivatel = Databaze::dotaz("SELECT * from ucitele where id like ?", array($id));
                ?>
                    <div class="card" id="uzivatel">
                        <div class="card-header">
                            <?php echo $uzivatel["trida"] . ' - ' . $uzivatel["jmeno"] . ' ' . $uzivatel["prijmeni"]; ?>
                        </div>
                        <div class="card-body">
                            <div class="form-group form-group-lg">
                            <form action="/administrace/<?php echo $druh;?>/uzivatele/<?php echo $id?>/upravit" method="post">
                                <label for="email">Email: </label>
                                <input type="text" class="form-control" name="email" id="email" value="<?php echo $uzivatel["email"]; ?>"/>
                                <button type="submit" class="mt-2 btn btn-dark float-sm-right">Aktualizovat!</button>
                            </form>
                            </div>
                        </div>
                    </div>
                <?php
            }
        }

        public static function aktualizujUzivatele($id, $druh, $novyEmail) {
            if($druh == "student") {
                $dup = AdminUzivateleEditController::vratDuplikaty();
                $found = 0;
                foreach($dup as $d) {
                    if(in_array($id, $d)) {
                        $found = 1;
                        break;
                    }
                }
                Databaze::dotaz("UPDATE studenti SET email = ? WHERE id LIKE ?", array($novyEmail, $id));
                if($found = 1) {
                    Databaze::dotaz("DELETE FROM duplikaty WHERE id_studenta LIKE ?", array($id));
                }
            } else if ($druh == "ucitel") {
                Databaze::dotaz("UPDATE ucitele SET email = ? WHERE id LIKE ?", array($novyEmail, $id));
            } 
            header("Location: /administrace/".$druh."/uzivatele/upravit");
        }
    }