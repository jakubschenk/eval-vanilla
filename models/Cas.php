<?php

class Cas {
    public static function getCasPristupuOd() {
        if(Config::getValueFromConfig("pristup_od") != null)
            return new DateTime(Config::getValueFromConfig("pristup_od"), new DateTimeZone("Europe/Prague"));
        else
            return null;
    }

    public static function getCasPristupuDo() {
        if(Config::getValueFromConfig("pristup_do") != null)
            return new DateTime(Config::getValueFromConfig("pristup_do"), new DateTimeZone("Europe/Prague"));
        else
            return null;
    }

    public static function getCasTed() {
        return new DateTime("now", new DateTimeZone("Europe/Prague"));
    }

    public static function isPristup() {
        if(self::getCasPristupuOd() != null) {
            if(self::getCasTed() > self::getCasPristupuOd() && self::getCasTed() < self::getCasPristupuDo()) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return -1;
        }
    }
}