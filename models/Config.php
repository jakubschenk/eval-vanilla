<?php

class Config {

    private static $config_path = 'config.json';

    public static function getValueFromConfig($var) { // vrací hodnotu chtěné proměnné z config.json
        $cfg = Config::getConfig();
        if(isset($cfg[$var])) //kontrola existence proměnné
            return $cfg[$var];
        else
            return null;
    }

    public static function setValueInConfig($var, $val) { //nastavuje hodnotu proměnné v config.json
        $cfg = Config::getConfig();
        $cfg[$var] = $val;

        Config::setConfig($cfg);
    }

    private static function getConfig() { //vrací soubor konfigurace
        $config_json = file_get_contents(Config::getConfigName());
        $cfg = json_decode($config_json, true);
        
        return $cfg;
    }

    private static function setConfig($json_data) { //nastaví celý config
        $jsonString = json_encode($json_data);
        file_put_contents(Config::getConfigName(), $jsonString);
    }

    private static function getConfigName() { //vrací cestu/jméno configu
        return Config::$config_path;
    }

}