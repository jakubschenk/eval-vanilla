<?php
if (isset($_POST['regLogin'])) {
    $admin_reg_login = $_POST['regLogin'];
    $admin_reg_email = $_POST['regEmail'];
    $admin_reg_pass = $_POST['regPassword'];
    $admin_reg_pass_hash = password_hash($admin_reg_pass, PASSWORD_DEFAULT);
        Databaze::dotaz(
            "INSERT INTO administratori(jmeno, email, heslo) VALUES(?,?,?)",
            array($admin_reg_login, $admin_reg_email, $admin_reg_pass_hash)
        );
        $login_url = '/administrace';
        header('Location: ' . filter_var($login_url, FILTER_SANITIZE_URL));
} else {
    require_once 'views/administrace-registrace.php';
}