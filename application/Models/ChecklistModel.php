<?php

namespace App\Models;

use App\DataBase\TransactionBD;

class ChecklistModel {
    
    private $transactionBD;


    public function __construct() {
        $this->transactionBD =  new TransactionBD();
    }
    
    public function findAll() {
        $query = "SELECT * FROM tab_genera_checkl WHERE ind_estado = 'S'";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
    
    public function findById($id) {
        $query = "SELECT * FROM tab_genera_checkl WHERE cod_checkl = '{$id}'";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }
    
    
}
