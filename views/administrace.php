<?php
require_once 'templates/header.php';

?>

<p>vitej admine <?php echo($_SESSION['login']); ?></p>
<p><a href="/administrace/import">Importovat XML</a></p>
<p><a href="/administrace/student/uzivatele/upravit">Upravit studenty</a></p>
<p><a href="/administrace/student/uzivatele/upravit">Upravit učitele</a></p>
<p><a href="/administrace/student/otazky/upravit">Upravit otázky pro studenty</a></p>
<p><a href="/administrace/ucitel/otazky/upravit">Upravit otázky pro učitele</a></p>
<p><a href="/administrace/import">Export dat</a></p>
<p><a href="/administrace/logout">Odhlásit se</a></p>
<?php
require_once 'templates/footer.php';
?>
