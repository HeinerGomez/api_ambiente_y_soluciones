<?php

namespace App\Models;

use App\DataBase\TransactionBD;

class StandarConnection {
    
    private $config;
    private $transactionBD;
    
    public function __construct() {
        $this->config = parse_ini_file('application/config/config.ini');
        $this->transactionBD = new TransactionBD();
    }   
    
    public function getDataOfClient($key) {
        $query = "SELECT * FROM tab_config_appxxx WHERE id='{$key}' ";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }
    
}