<?php

class PredmetyController extends Controller
{

    public static function vypisPredmety()
    {
        if ($_SESSION["druh"] == 'ucitel') {
            $id = Ucitel::getId($_SESSION["email"]);
            $predmety = Predmet::vratPredmetyProUcitele($id);
            $i = 1;
            foreach ($predmety as $predmet) {
                if ($i == 1) {
                    echo '<div class="row align-items-start">';
                }
                PredmetyController::vytvorDivUcitelPredmet($predmet, true);
                if ($i != 1 && $i % 3 == 0) {
                    echo '</div>';
                    echo '<div class="row">';
                }
                $i++;
            }
            echo '</div>';
        } else if ($_SESSION["druh"] == 'student') {
            $id = Student::getId($_SESSION["email"]);
            $predmety = Predmet::vratPredmetyProStudenta($id);
            $i = 1;
            foreach ($predmety as $predmet) {
                if ($i == 1) {
                    echo '<div class="row align-items-start">';
                }
                PredmetyController::vytvorDivStudentPredmet($predmet, true);
                if ($i != 1 && $i % 3 == 0) {
                    echo '</div>';
                    echo '<div class="row">';
                }
                $i++;
            }
            echo '</div>';
        }
    }

    public static function vypisVyplnenePredmety() {
        if ($_SESSION["druh"] == 'ucitel') {
            $id = Ucitel::getId($_SESSION["email"]);
            $predmety = Predmet::vratVyplnenePredmety($id);
            $i = 1;
            foreach ($predmety as $predmet) {
                if ($i == 1) {
                    echo '<div class="row align-items-start">';
                }
                PredmetyController::vytvorDivUcitelPredmet($predmet, false);
                if ($i != 1 && $i % 3 == 0) {
                    echo '</div>';
                    echo '<div class="row">';
                }
                $i++;
            }
            echo '</div>';
        } else if ($_SESSION["druh"] == 'student') {
            $id = Student::getId($_SESSION["email"]);
            $predmety = Predmet::vratVyplnenePredmety($id);
            $i = 1;
            foreach ($predmety as $predmet) {
                if ($i == 1) {
                    echo '<div class="row align-items-start">';
                }
                PredmetyController::vytvorDivStudentPredmet($predmet, false);
                if ($i != 1 && $i % 3 == 0) {
                    echo '</div>';
                    echo '<div class="row">';
                }
                $i++;
            }
            echo '</div>';
        }
    }

    public static function vytvorDivUcitelPredmet($predmet, $link) {
        ?>
        <div class="card border-dark col-sm">
            <a class="text-decoration-none text-dark" href="<?php echo '/t/' . $predmet['trida'] . '/' . $predmet['zkratka'] . '/' . $predmet['skupina']; ?>">
                <div class="card-body">
                    <h5 class="card-title border-bottom py-2 d-flex align-items-center justify-content-center h-100"><?php echo $predmet['trida'] . ' | ' . $predmet['skupina']; ?></h5>
                    <p class="card-text"><?php echo $predmet['nazev']; ?> </p>
                    <!-- <p class="card-text"><?php //echo '<b>Skupina:</b> ' . $predmet['skupina']; ?> </p> -->
                </div>
            </a>
        </div>
<?php
    }


    public static function vytvorDivStudentPredmet($predmet, $link)
    {
?>
        <div class="card border-dark col-sm">
            <?php if($link) { ?>
            <a class="text-decoration-none text-dark" href="<?php echo '/p/' . $predmet['u_id'] . '/' . $predmet['zkratka']; ?>">
            <?php } ?>
                <div class="card-body">
                    <h5 class="card-title border-bottom py-2"><?php echo $predmet['nazev']; ?></h5>
                    <p class="card-text"><?php echo '<b>Vyučující:</b> ' . $predmet['ucitel']; ?> </p>
                    <p class="card-text"><?php echo '<b>Skupina:</b> ' . $predmet['skupina']; ?> </p>
                </div>
            <?php if($link) { ?>    <!-- ja vim, fuj... -->
            </a>
            <?php } ?>
        </div>
<?php
    }

    public static function vyplneno($predmet, $ucitel, $email, $druh)
    {
        if ($druh == "student") {
            $skolrok = Config::getValueFromConfig("skolnirok_id");
            $dotaz = Databaze::dotaz("SELECT vyplneno FROM studenti_predmety WHERE id_s = ? AND id_p LIKE ? AND id_u LIKE ? AND skolnirok = ?", array($_SESSION["id"], $predmet, $ucitel, $skolrok));
            if ($dotaz[0]["vyplneno"] == 0) {
                return false;
            } else {
                return true;
            }
        } 
    }
}

?>