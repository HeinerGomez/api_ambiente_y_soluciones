<?php

namespace App\DataBase;

abstract class Conexion {
    
    protected static $conexion = null;

    public static function singletonInstance() {
        if (self::$conexion === null) {
            $conexion = self::generateConexion();
            return $conexion;
        } else {
            return $this->conexion;
        }
    }
    
    private static function generateConexion() {
        try {
            $config = parse_ini_file('application/config/config.ini');
            return new \PDO('mysql:dbname=' . $config['NAME_DB'] . ';host' . $config['HOST_DB'], $config['USER_DB'], $config['PWD_DB']);
        } catch (Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
        }
        
    }
    
    
} 
