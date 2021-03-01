<?php
$druh = $args[0];
if($druh == 'student') {
    ?>
    <h2>Upravte otázky pro studenty</h2>
    <?php
}
new AdminOtazkyEditController($druh);
?>