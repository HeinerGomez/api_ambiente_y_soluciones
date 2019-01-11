<?php

namespace App\DataBase;

use App\Helpers\DinamicGlobalConfig;

abstract class Conexion {
    
    protected static $connection = null;
    
    public static function singletonInstance() {
        if (self::$connection === null) {
            $connection = self::generateConnection();
            return $connection;
        } else {
            return $this->connection;
        }
    }
    
    private static function generateConnection() {
        try {
            $connectionParameters = DinamicGlobalConfig::getConfig('db');
            return new \PDO('mysql:dbname=' . $connectionParameters['NAME_DB'] . ';host' . $connectionParameters['HOST_DB'], $connectionParameters['USER_DB'], $connectionParameters['PWD_DB']);
        } catch (Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
        } 
    }
    
    public static function destroyConnection() {
        self::$connection = null;
    }
} 
