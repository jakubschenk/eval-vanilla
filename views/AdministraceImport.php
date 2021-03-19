<?php
if(Config::getSkolniRok() == null) {
    ?>
    <div class="alert alert-danger" role="alert">
        V konfiguračním souboru není zvolen rok! Můžete ho buď nastavit v nastavení nebo importujte nový!
    </div>
    <?php
}
if(isset($_GET["error"])) {
    echo "<p>Chyba pri importu! Zkontrolujte log.</p>";
    echo '<a class="btn btn-dark mt-2" href="/administrace/import">Vratit se</a>';
} else if (isset($_GET["success"])) {
    echo "<p>Import se zdaril!</p>";
    echo '<a class="btn btn-dark mt-2" href="/administrace/">Vratit se do administrace</a>';
} else {
?>

<form class="form-group" action="/administrace/importing" method="post" enctype="multipart/form-data">
    <label for="xmlfile">Zvolte soubor pro XML Import:</label>
    <input class="form-control-file" type="file" name="xmlfile" accept=".xml" required>
    <input class="btn btn-dark mt-2" type="submit" value="Nahrát do databáze">
</form>

<?php
}
?>