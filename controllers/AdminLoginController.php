<?php

class AdminLoginController extends AdminController {

    public static function loginAdmin() {
        $admin_login = $_POST['login'];
        $admin_pass = $_POST['password'];

        Administrator::verify($admin_login, $admin_pass);
    }

}