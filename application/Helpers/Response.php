<?php

namespace App\Helpers;

abstract class Response {
    
    public static function responseJSON($array) {
        echo json_encode($array);
    }
    
}
