<?php

namespace App\Models;

use App\DataBase\TransactionBD;
use App\Helpers\DinamicGlobalConfig;

class ItemModel {
    
    private $transactionBD;
    private $dataBase;
    
    public function __construct() {
        $this->transactionBD =  new TransactionBD();
        $this->dataBase = DinamicGlobalConfig::getConfig('nom_baseda');
    }
    
    public function findAll() {
        $query = "SELECT * FROM ".$this->dataBase.".tab_catego_itemsx";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
    public function findByChecklistIdAndCategoryId($checklistId, $categoryId) {
        $query = "SELECT * FROM ".$this->dataBase.".tab_catego_itemsx WHERE cod_checkl = '{$checklistId}' AND cod_catego = '{$categoryId}' ";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
}