<?php

class Config {

    private static $config_path = 'config.json';

    public static function getValueFromConfig($var) { // vrací hodnotu chtěné proměnné z config.json
        $cfg = self::getConfig();
        if(isset($cfg[$var])) //kontrola existence proměnné
            return $cfg[$var];
        else
            return null;
    }

    public static function setValueInConfig($var, $val) { //nastavuje hodnotu proměnné v config.json
        $cfg = self::getConfig();
        $cfg[$var] = $val;

        self::setConfig($cfg);
    }

    private static function getConfig() { //vrací soubor konfigurace
        $config_json = file_get_contents(Config::getConfigName());
        $cfg = json_decode($config_json, true);
        
        return $cfg;
    }

    private static function setConfig($json_data) { //nastaví celý config
        $jsonString = json_encode($json_data);
        file_put_contents(self::getConfigName(), $jsonString);
    }

    private static function getConfigName() { //vrací cestu/jméno configu
        return self::$config_path;
    }

    public static function getSkolniRok() {
        return self::getValueFromConfig("skolnirok_id");
    }

    public static function setSkolniRok($skolnirok) {
        $jmeno = Databaze::dotaz("SELECT rok from skolniroky where idr like ?", array($skolnirok))[0];
        self::setValueInConfig("skolnirok_id", $skolnirok);
        self::setValueInConfig("skolnirok", utf8_encode($jmeno["rok"]));
    }

    public static function setPristup($od, $do) {
        Config::setValueInConfig("pristup_od", $od);
        Config::setValueInConfig("pristup_do", $do);
    }

}