<?php
// autocargardo de clases de slim 2
require 'vendor/autoload.php';
// inclusion del router
require 'application/Router.php';
// instancio una nueva aplicacion en slim
$application = new \Slim\Slim();
// configuracion
$application->config('debug', true);
// router
Router::registerRouter();
// correr la aplicacion
$application->run();