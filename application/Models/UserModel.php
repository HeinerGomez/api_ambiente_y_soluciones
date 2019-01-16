<?php

namespace App\Models;

use App\DataBase\TransactionBD;
use App\Helpers\DinamicGlobalConfig;

class UserModel {

    private $transactionBD;
    private $dataBase;

    public function __construct() {
        $this->transactionBD = new TransactionBD();
        $this->dataBase = DinamicGlobalConfig::getConfig('nom_baseda');
    }

    public function findById($id) {
        $query = "SELECT * FROM ".$this->dataBase.".tab_genera_usuari WHERE cod_usuari = '{$id}'";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }

    public function findByIdAndPwd($id, $pwd) {
        $query = "SELECT * FROM ".$this->dataBase.".tab_genera_usuari WHERE cod_usuari = '{$id}' AND clv_usuari = '{$pwd}'";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }

    public function findByToken($token) {
        $query = "SELECT * FROM ".$this->dataBase.".tab_genera_usuari WHERE sha1(CONCAT(cod_usuari, clv_usuari)) = '{$token}'";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }

}
