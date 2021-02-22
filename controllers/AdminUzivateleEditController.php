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
            $ucitele = Databaze::dotaz("SELECT * from ucitele where skolnirok like ?", array(Config::getValueFromConfig('skolnirok_id')));
            print_r($ucitele);
        } else if ($this->druh == 'student') {
            $studenti = Student::vratStudenty();
            self::printTableHead("uzivatele");
            foreach ($studenti as $st) {
                self::vypisUzivatele($st, $this->druh);
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            require_once '404.php';
        }
    }

    public static function printTableHead($name)
    {
?>
        <table class="table table-striped" id="<?php echo $name; ?>">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Email</th>
                    <th scope="col">Jméno</th>
                    <th scope="col">Třída</th>
                    <th scope="col">Byl přihlášen?</th>
                    <th scope="col">Změnit</th>
                    <th scope="col">Smazat</th>
                </tr>
            </thead>
            <tbody>
<?php
    }

    public static function vypisUzivatele($uzivatel, $druh)
    {
        if ($druh == 'ucitel') {
        
        } else if ($druh == 'student') {
?>
        <tr>
            <td class="align-middle"><?php echo $uzivatel['id']; ?></td>
            <td class="align-middle"><?php echo $uzivatel['email']; ?></td>
            <td class="align-middle"><?php echo $uzivatel['jmeno'] . ' ' . $uzivatel['prijmeni']; ?></td>
            <td class="align-middle"><?php echo $uzivatel['trida']; ?></td>
            <td class="align-middle"><?php echo ($uzivatel['gid'] != null) ? ('Ano') : ('Ne'); ?></td>
            <td class="align-middle"><a class="btn btn-dark" href="<?php echo $uzivatel['id']; ?>/upravit">Upravit</a></td>
            <td class="align-middle"><a class="btn btn-dark" href="<?php echo $uzivatel['id']; ?>/smazat">Smazat</a></td>
        </tr>
<?php
        }
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
}