<?php

namespace App\Helpers;

abstract class DinamicGlobalConfig {
    
    protected static $config = [];

    public static function registerConfig($config) {
        if (empty(self::$config)) {
            self::$config = $config;
        } else {
            self::appendConfig($config);
        }
    }
    
    public static function getConfig($key = false) {
        if ($key !== false && isset(self::$config[$key])) {
            return self::$config[$key];
        }
        
        return self::$config;
    }
    
    public static function cleanConfig($key = false) {
        if ($key !== false && isset(self::$config[$key])) {
            unset(self::$config[$key]);
        }
        self::$config = [];
    }
    
    
    private static function appendConfig($config) {
        try {
            // valido que sea un arreglo, si no lo es, lanzo una excepción
            if (!is_array($config)) {
                throw new Exception('Imposible registrar configuración, la configuración debe ser un arreglo!');
            }
            // recorro el arreglo
            foreach($config as $key => $value) {
                self::$config[$key] = $value;
            }
        } catch (Exception $ex) {
            trigger_error($ex->getMessage(), E_USER_ERROR);
        }
    }
}

