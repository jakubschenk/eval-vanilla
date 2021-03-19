<?php

abstract class Uzivatel {

    private $jmeno;
    private $email;
    private $obrazek;
    private $g_id;
    private $typ;
    private $skolnirok;

    public function __construct($user_data) { // $user_data dostaneme od Google_Service_Oauth2
        $this->jmeno = $user_data['name'];
        $this->email = $user_data['email'];
        $this->obrazek = $this->savePicture($user_data['picture']);
        $this->g_id = $user_data['id'];
        $this->typ = $this->typChooser($this->email);
        $this->skolnirok = Config::getValueFromConfig("skolnirok_id");
    }

    public static function typChooser($mail) {
            if(strpos($mail, '.st@') !== false) {
              return 'student';
            } else {
              return 'ucitel';
            }    
    }

    private function savePicture($picAddr) {
        if(!is_dir("pictures")) {
            mkdir("pictures");
            mkdir("pictures/profiles");
        } else if (!is_dir("pictures/profiles")) {
            mkdir("pictures/profiles");   
        }
        $path = '/pictures/profiles/' . $this->email . '.png';
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . $path, fopen($picAddr, 'r'));
        return $path;
    }

    public function getEmail() {
        return $this->email;
    }
    public function getTyp() {
        return $this->typ;
    }
    public function getObrazek() {
        return $this->obrazek;
    }
    public function getJmeno() {
        return $this->jmeno;
    }
    public function getGid() {
        return $this->g_id;
    }

    abstract public static function updateAndCheckUser($user_data);
}