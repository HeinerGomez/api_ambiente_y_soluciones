<?php

namespace App\Models;

use App\DataBase\TransactionBD;

class ItemModel {
    
    private $transactionBD;
    
    public function __construct() {
        $this->transactionBD =  new TransactionBD();
    }
    
    public function findAll() {
        $query = "SELECT * FROM tab_catego_itemsx";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
    public function findByChecklistIdAndCategoryId($checklistId, $categoryId) {
        $query = "SELECT * FROM tab_catego_itemsx WHERE cod_checkl = '{$checklistId}' AND cod_catego = '{$categoryId}' ";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
}