<?php
// headers 
header("Access-Control-Allow-Origin: *"); // si la API podra ser accedida desde cualquier URL
header('Access-Control-Allow-Credentials: false'); // si manejara control de accesos
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS'); // los metodos permitidos para el consumo de la API
header("Access-Control-Allow-Headers: X-Requested-With"); // habilitacion de cabeceras REQUEST
header('Content-Type: text/html; charset=utf-8'); // codificacion de las solicitudes y las respuestas
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"'); // compatibilidad con cualquier navegador web
ini_set("display_errors", true);
error_reporting(E_ALL);
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