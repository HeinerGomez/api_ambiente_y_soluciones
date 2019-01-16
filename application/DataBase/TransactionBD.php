<?php

namespace App\DataBase;

use App\Helpers\Response;

class TransactionBD {

    private $conexion;
    private $responseQuery;

    public function __construct() {
        $this->conexion = Conexion::singletonInstance();
    }

    public function beginTransaction() {
        if ($this->conexion->inTransaction() !== true) {
            $this->conexion->beginTransaction();
        }
    }

    public function commit() {
        if ($this->conexion->inTransaction()) {
            $this->conexion->commit();
        }
    }

    public function doSelect($query, $associative = true) {
        if ($associative) {
            $this->responseQuery = $this->conexion->query($query, \PDO::FETCH_ASSOC);
        } else {
            $this->responseQuery = $this->conexion->query($query, \PDO::FETCH_NUM);
        }
    }

    public function doInsert($query) {
        try {
            if ($this->conexion->exec($query)) {
                return true;
            } else {
                echo "<pre>--->";
                print_r($this->conexion->errorInfo());
                echo "</pre>";
                return false;
            }
        } catch (PDOException $ex) {
            echo "<pre>";
            print_r($ex);
            echo "</pre>";
            $response = ["error" => "La insercion ha fallado porque: " . $ex->getMessage()];
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
