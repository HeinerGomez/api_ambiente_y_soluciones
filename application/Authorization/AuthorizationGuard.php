<?php

namespace App\Authorization;

use Slim\Slim as SlimApp;

abstract class AuthorizationGuard {
    public static function validateAuthorization($user) {
        return function () use ($user) {
            $application = SlimApp::getInstance();
            // aqui  agregar toda la logica para determinar si dicha peticion es autorizada o no
            if (!$user) {
                $application->redirect('/accessDenied');
            }
        };
    }
}
