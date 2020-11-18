<?php
$pagename = "Přihlášení";
require_once 'templates/header.php';

$config_json = file_get_contents('config.json');
$cfg = json_decode($config_json, true);
?>

<form action="/administrace/login" method="post">
    <label for="login">Jméno: </label>
    <input type="text" name="login" required>
    <label for="password">Heslo: </label>
    <input type="password" name="password" required>
    <input type="submit" value="Přihlásit">
</form>

<?php
if($cfg['adminRegOn']) {
    echo '<a href="/administrace/registrace">Registrovat se</a>';
}
if(isset($_GET['badLogin'])) {
    echo('<p>Spatne prihlaseni</p>');
}
require_once 'templates/footer.php';
?>