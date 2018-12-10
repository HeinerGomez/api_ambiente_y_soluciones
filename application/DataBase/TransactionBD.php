<?php

namespace App\DataBase;

class TransactionBD {
    
    private $conexion;
    private $responseQuery;

    public function __construct() {
        $this->conexion = Conexion::singletonInstance();
    }
    
    public function beginTransaction() {
        $this->conexion->beginTransaction();
    }
    
    public function commit() {
        $this->conexion->commit();
    }
    
    public function doSelect($query, $associative = true) {
        if ($associative) {
            $this->responseQuery = $this->conexion->query($query, \PDO::FETCH_ASSOC);
        } else {
            $this->responseQuery = $this->conexion->query($query, \PDO::FETCH_NUM);
        }
    }
    
    
    public function getResult($type = 'multi-row') {
        $result = [];
        switch ($type) {
            case 'row':
                foreach ($this->responseQuery as $row) {
                    $result = $row;
                }
            break;
            default:
                foreach ($this->responseQuery as $row) {
                    $result[] = $row;
                }
        }
        return $result;
    }
    
}