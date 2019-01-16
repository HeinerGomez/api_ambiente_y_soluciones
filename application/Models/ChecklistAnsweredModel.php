<?php

namespace App\Models;

use App\DataBase\TransactionBD;
use App\Helpers\DinamicGlobalConfig;

class ChecklistAnsweredModel {
    
    private $transactionBD;
    private $dataBase;
    
    public function __construct() {
        $this->transactionBD =  new TransactionBD();
        $this->dataBase = DinamicGlobalConfig::getConfig('nom_baseda');
    }
    
    public function insertData($data) {
        // inicio la transaccion
        // $this->transactionBD->beginTransaction();
        $query = "INSERT INTO ".$this->dataBase.".tab_respon_checkl( ind_respon, obs_respon, usr_respon, fec_respon, cod_progra, cod_itemxx, rut_photox, rut_firmxx) "
                . " VALUES( '{$data['ind_respon']}', '{$data['obs_respon']}', '{$data['usr_respon']}', '{$data['fec_respon']}', "
                . "         {$data['cod_progra']}, '{$data['cod_itemxx']}', '{$data['rut_photox']}', '{$data['rut_firmxx']}' )";
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
        $query = " SELECT IFNULL( MAX(cod_progra), '0' ) as lastConsecutive FROM ".$this->dataBase.".tab_respon_checkl";
        $this->transactionBD->doSelect($query);
        return $this->transactionBD->getResult('row');
    }
}

