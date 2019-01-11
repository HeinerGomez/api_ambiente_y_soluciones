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
    
    public function findOnlyNoAnswered() {
        $query = "SELECT *  
                    FROM tab_genera_checkl a
              INNER JOIN tab_catego_itemsx b ON a.cod_checkl = b.cod_checkl
               LEFT JOIN tab_respon_checkl c ON b.cod_itemxx = c.cod_itemxx
                   WHERE c.cod_itemxx IS NULL
                     AND a.ind_estado = 'S' 
                GROUP BY a.cod_checkl";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult();
    }
}
