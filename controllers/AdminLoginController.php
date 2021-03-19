<?php

class AdminLoginController extends AdminController {

    public static function loginAdmin() {
        $admin_login = $_POST['login'];
        $admin_pass = $_POST['password'];
        AdminController::logoutWithoutRedirect();
        Administrator::verify($admin_login, $admin_pass);
    }

}