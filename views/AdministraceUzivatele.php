<?php
if($args[0] == 'ucitel') {
    new AdminUzivateleEditController($args[0]);
} else {
    $duplikaty = AdminUzivateleEditController::vratDuplikaty();
    if($duplikaty != null) {
        echo '<h4 id="duplikaty">Duplicitní emaily</h4>';
        foreach($duplikaty as $duplikat) {
            AdminUzivateleEditController::vypisUzivatele($duplikat['id_studenta'], $args[0]);
        }
    }
   
    new AdminUzivateleEditController($args[0]); 
    
}

?>