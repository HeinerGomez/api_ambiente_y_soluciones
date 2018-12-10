<?php

namespace App\Controllers;

class BaseController {
    
    private $model = null;
    const LENGTH_STR_MODEL = 5;
    
    public function __get($property) {
        return $this->analyzeProperty($property);
    }
    
    private function analyzeProperty($property) {
        $chunkProperty = substr($property, (strlen($property) - self::LENGTH_STR_MODEL));
        switch ($chunkProperty) {
            case 'Model':
                return $this->model = $this->singletonInstanceModel(ucfirst($property));
                break;
        }
    }
    
    private function singletonInstanceModel($nameModel) {
        if ($this->model === null) {
            $class =  '\\App\\Models\\' . $nameModel;
            if (class_exists($class)) {
                return new $class();
            } else {
                echo '<br> La clase :' . $nameModel . ' <br> No existe';
            }
        } else {
            return $this->model;
        }
    }
    
}
