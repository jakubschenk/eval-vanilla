<?php

include 'templates/header.php';

$druh = $args[0];

new AdminOtazkyEditController($druh);

?>

<script src="/js/EditorOtazek.js"></script>
<script src="/js/loadEditor.js"></script>

<?php
include 'templates/footer.php';
?>