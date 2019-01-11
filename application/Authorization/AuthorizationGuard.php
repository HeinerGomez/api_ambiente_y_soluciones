<?php

namespace App\Authorization;

use Slim\Slim as SlimApp;
use App\Models\UserModel;

abstract class AuthorizationGuard {
    
    public static function validateAuthorization($token) {
        return function () use ($token) {
            $application = SlimApp::getInstance();
            // aqui  agregar toda la logica para determinar si dicha peticion es autorizada o no
            $userModel = new UserModel();
            // verifico si la busqueda del usuario por token es un exito
            $user = $userModel->findByToken($token);
            if (empty($user)) {
                $application->redirect('accessDenied');
            }
            return true;
        };
    }
    
    public static function getToken($userId, $pwd) {
        $application = SlimApp::getInstance();
        $userModel = new UserModel();
        // obtengo el usuario
        $user = $userModel->findByIdAndPwd($userId, base64_encode($pwd));
        if (empty($user)) {
            $application->redirect('accessDenied');
        } else {
           return self::generateToken($user['cod_usuari'], $user['clv_usuari']);
        }
    }
    
    private static function generateToken($userId, $pwd) {
        return sha1($userId.$pwd);
    }
    
}
