<?php
require_once 'templates/header.php';
?>

<form action="/administrace/registrace" method="post">
    <label for="regLogin">Jméno: </label>
    <input type="text" id="regLogin" name="regLogin" required>
    <label for="regEmail">Email: </label>
    <input type="email" id="regEmail" name="regEmail" required>
    <label for="regPassword">Heslo: </label>
    <input type="password" id="regPassword" name="regPassword" required>
    <input type="submit" value="Registrovat">
</form>

<?php
require_once 'templates/footer.php';
?>