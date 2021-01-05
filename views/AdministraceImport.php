<?php
require_once 'templates/header.php';
?>

<form action="/administrace/import" method="post" enctype="multipart/form-data">
    <label for="xmlfile">Zvolte soubor pro XML Import</label>
    <input type="file" name="xmlfile" accept=".xml" required>
    <label for="rok">Zadejte školní rok: </label>
    <input type="text" name="rok" required>
    <input type="submit" value="Nahrát do databáze">
</form>

<?php
require_once 'templates/footer.php';
?>