<?php
    if($_SESSION["druh"] == 'ucitel') {
        new OtazkyController($args[0], ['trida' => $args[1], 'skupina' => $args[2]]);
    } else {
        new OtazkyController($args[0], ['ucitel' => $args[1]]);
    }

?>