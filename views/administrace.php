<?php
require_once 'templates/header.php';
?>

<p>Pogchamp cest <?php echo($_SESSION['login']); ?></p>
<a href="/administrace?logout">Odhlasit</a>
<?php
require_once 'templates/footer.php';
?>
