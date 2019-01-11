<?php

namespace App\Models;

use App\DataBase\TransactionBD;

class ChecklistAnsweredModel {
    
    private $transactionBD;
    
    public function __construct() {
        $this->transactionBD =  new TransactionBD();
    }
    
    public function insertData($data) {
        // inicio la transaccion
        $this->transactionBD->beginTransaction();
        $query = "INSERT INTO tab_respon_checkl( ind_respon, obs_respon, usr_respon, fec_respon, cod_progra, cod_itemxx) "
                . " VALUES( '{$data['ind_respon']}', '{$data['obs_respon']}', '{$data['usr_respon']}', '{$data['fec_respon']}', "
                . "         {$data['cod_progra']}, '{$data['cod_itemxx']}' )";
        // se ejecuta la transaccion
        if ($this->transactionBD->doInsert($query)) {
            // commit
            $this->transactionBD->commit();
            return true;
        } else {
         return false;   
        }
    }
    
    public function getLastonsecutiveOfChecklistAnswered() {
        $query = " SELECT IFNULL( MAX(cod_progra), '0' ) as lastConsecutive FROM tab_respon_checkl";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }
}

