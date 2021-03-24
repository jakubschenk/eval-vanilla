<?php
        if(AdminUzivateleEditController::vratDuplikaty() != null) {
                ?>
                <div class="alert alert-danger" role="alert">
                Pro tento rok máte duplikáty, prosím vyřeště je v <a href="/administrace/student/uzivatele/upravit" class="alert-link">Úpravě uživatelů</a>!
                </div>
                <?php
        }
?>

<h2>Administrace</h2>
<div class="container">
        <h5>Status: </h5>
        <p>
                <?php
                setlocale(LC_ALL, "cs_CZ.UTF-8");
                switch(Cas::isPristup()) {
                        case 1: {
                                echo 'Dotazníky aktivní. Přístup uživatelů do ' . strftime("%d. %B %Y - %H:%M:%S", Cas::getCasPristupuDo()->getTimestamp());
                                break;
                        }
                        case 0: {
                                echo 'Dotazníky neaktivní. Přístup uživatelů od ' . strftime("%a %e.%l.%Y", Cas::getCasPristupuOd()->getTimestamp());
                                break;
                        }
                        case -1: {
                                echo 'Dotazníky neaktivní. Čas přístupu nenastaven!';
                                break;
                        }
                }
                ?>
        </p>
        <h5>Aktivní rok: </h5>
        <?php
        echo '<p>' . Config::getSkolniRok() . ' - ' . Config::getValueFromConfig("skolnirok") . '<p>';
        ?>
        <h5>Přístup: </h5>
        <?php
        if (Cas::isPristup() != -1) {
                echo '<p>Od: ' . Cas::getCasPristupuOd()->format("d-M-Y H:i:s") . '</p>';
                echo '<p>Do: ' . Cas::getCasPristupuDo()->format("d-M-Y H:i:s") . '</p>';
        } else {
                echo '<p>Čas přístupu nenastaven, nastavte ho prosím v nastavení!';
        }

        ?>
        <h5>Dotazníků vyplněno</h5>
        <?php $studentiDotazniky = Student::vratPocetVyplnenychDotazniku(Config::getSkolniRok());
        $uciteleDotazniky = Ucitel::vratPocetVyplnenychDotazniku(Config::getSkolniRok()); ?>
        <p>Studenti: <b><?php echo $studentiDotazniky["pocet"] . '/' . $studentiDotazniky["celkem"]; ?></b></p>
        <p>Učitelé: <b><?php echo $uciteleDotazniky["pocet"] . '/' . $uciteleDotazniky["celkem"]; ?></b></p>
</div>