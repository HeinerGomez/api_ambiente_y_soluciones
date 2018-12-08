<?php
// autocargardo de clases de slim 2
require 'vendor/autoload.php';

// instancio una nueva aplicacion en slim
$application = new \Slim\Slim();

/* Definición de las rutas */
$application->get('/hello', function () {
    echo "Hello World Gays";
});
