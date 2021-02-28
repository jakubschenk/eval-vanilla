<?php

$druh = $args[0];
if($druh == 'student') {
    echo '<h2>Upravte otázky pro studenty</h2>';
}
new AdminOtazkyEditController($druh);

?>

<script src="/public/js/EditorOtazek.js"></script>
<script src="/public/js/loadEditor.js"></script>

<?php
?>