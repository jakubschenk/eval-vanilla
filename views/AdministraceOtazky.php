<?php

include 'templates/header.php';

$druh = $args[0];

new AdminOtazkyEditController($druh);

?>

<script src="/public/js/EditorOtazek.js"></script>
<script src="/public/js/loadEditor.js"></script>

<?php
include 'templates/footer.php';
?>