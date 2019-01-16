<?php

namespace App\Models;

use App\DataBase\TransactionBD;
use App\Helpers\DinamicGlobalConfig;

class CategoryModel {
    
    private $transactionBD;
    private $dataBase;
    
    public function __construct() {
        $this->transactionBD =  new TransactionBD();
        $this->dataBase = DinamicGlobalConfig::getConfig('nom_baseda');
    }
    
    public function findAll() {
        $query = "SELECT * FROM ".$this->dataBase.".tab_genera_catego";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
    public function findById($checklistId, $categoryId) {
        $query = "SELECT * FROM ".$this->dataBase.".tab_genera_catego WHERE cod_catego = '{$categoryId}' AND cod_checkl = '{$checklistId}' ";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }
    
    public function findByChecklistId($id) {
        $query = "SELECT * FROM ".$this->dataBase.".tab_genera_catego WHERE cod_checkl = '{$id}'";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
}
