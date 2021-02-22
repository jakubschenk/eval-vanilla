<?php
if ($args[0] == 'ucitel') {
    new AdminUzivateleEditController($args[0]);
} else {
    $duplikaty = AdminUzivateleEditController::vratDuplikaty();
    if ($duplikaty != null) {
        echo '<h3 class="border-bottom py-2" id="duplikatyTitle">Duplicitní emaily</h3>';
        AdminUzivateleEditController::printTableHead('duplikaty');
        foreach ($duplikaty as $duplikat) {
            AdminUzivateleEditController::vypisUzivatele($duplikat, $args[0]);
        }
        echo '</table>';
    }
?>
    <div class="d-flex justify-content-between">
    <h3 class="" id="uzivateleTitle">Uživatelé</h3>
    <form class="form-inline ">
        <div class="input-group pb-2">
            <input class="form-control" type="text" id="hledatEmail" onkeyup="hledatUzivatele()" placeholder="Hledat podle emailu"/>
        </div>
    </form>
    </div>
    
<?php
    new AdminUzivateleEditController($args[0]);
}
?>

<script src="/public/js/searchUsers.js"></script>