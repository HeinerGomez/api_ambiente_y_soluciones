<?php

namespace App\Models;

use App\DataBase\TransactionBD;

class StandarConnection {
    
    private $config;
    private $transactionBD;
    
    public function __construct() {
        $this->transactionBD = new TransactionBD();
    }   
    
    public function getDataOfClient($key) {
        $query = "SELECT * FROM tab_connect_apixxx WHERE cod_tokenx='{$key}' ";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }
    
}