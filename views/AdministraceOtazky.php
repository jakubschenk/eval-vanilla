<?php
$druh = $args[0];
if(Cas::isPristup()) {
    echo '<h2>V době hodnocení nelze měnit otázky!</h2>';
} else {
    if($druh == 'student') {
        ?>
        <h2>Upravte otázky pro studenty</h2>
        <?php
    } else if ($druh == 'ucitel') {
        ?>
        <h2>Upravte otázky pro učitele</h2>
        <?php
    }
    new AdminOtazkyEditController($druh);
}

?>