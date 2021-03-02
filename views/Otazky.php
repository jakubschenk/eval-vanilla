<div class="container otazky">
<?php
    if($_SESSION["druh"] == 'ucitel') {
        $args[2] = urldecode($args[2]);
        echo '<h2>' . $args[0] . ' - ' . $args[1] . ' - ' . $args[2] . '</h2>';
        new OtazkyController($args[0], ['trida' => $args[1], 'skupina' => $args[2]]);
    } else {
        echo '<h2>' . $args[0] . ' - ' . $args[1] . '</h2>';
        new OtazkyController($args[0], ['ucitel' => $args[1]]);
    }

?>
</div>