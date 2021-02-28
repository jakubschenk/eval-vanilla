<?php
if(isset($_GET["error"])) {
    echo "Chyba pri importu! Zkontrolujte log.";
    echo '<a href="/administrace/import">Vratit se</a>';
} else if (isset($_GET["success"])) {
    echo "Import se zdaril!";
    echo '<a href="/administrace/">Vratit se do administrace</a>';
} else {
?>

<form action="/administrace/importing" method="post" enctype="multipart/form-data">
    <label for="xmlfile">Zvolte soubor pro XML Import</label>
    <input type="file" name="xmlfile" accept=".xml" required>
    <label for="rok">Zadejte školní rok: </label>
    <input type="text" name="rok" required>
    <input type="submit" value="Nahrát do databáze">
</form>

<?php
}
?>