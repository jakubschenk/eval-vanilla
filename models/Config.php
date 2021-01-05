<?php

class Config {

    private static $config_path = 'config.json';

    public static function getValueFromConfig($var) { // vrací hodnotu chtěné proměnné z config.json
        $cfg = Config::getConfig();
        return $cfg[$var];
    }

    public static function setValueInConfig($var, $val) {
        $cfg = Config::getConfig();
        $cfg[$var] = $val;

        Config::setConfig($cfg);
    }

    private static function getConfig() {
        $config_json = file_get_contents(Config::getConfigName());
        $cfg = json_decode($config_json, true);
        
        return $cfg;
    }

    private static function setConfig($json_data) {
        $jsonString = json_encode($json_data);
        file_put_contents(Config::getConfigName(), $jsonString);
    }

    private static function getConfigName() {
        return Config::$config_path;
    }

}