<?php

class AdminRegisterController extends AdminController {
    public static function registerAdmin() {
        $admin_reg_login = $_POST['regLogin'];
        $admin_reg_email = $_POST['regEmail'];
        $admin_reg_pass = $_POST['regPassword'];
        $admin_reg_pass_hash = password_hash($admin_reg_pass, PASSWORD_DEFAULT);
        
        Administrator::newAdmin($admin_reg_login, $admin_reg_email, $admin_reg_pass_hash);
    }    
}