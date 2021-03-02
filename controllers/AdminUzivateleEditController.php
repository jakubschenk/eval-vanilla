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
                print_r(Databaze::dotaz("SELECT * from studenti where id like ?", array($id)));
            } else if ($druh == 'ucitel') {
                print_r(Databaze::dotaz("SELECT * from ucitele where id like ?", array($id)));
            }
        }
    }
