<?php

ini_set("display_errors", true);
error_reporting(E_ALL);

use Slim\Slim as SlimApp;
use App\Authorization\AuthorizationGuard;
use App\Controllers\ChecklistController;
use App\Controllers\ChecklistAnsweredController;
use App\Models\UserModel;
use App\Helpers\Response;
use App\Helpers\DinamicGlobalConfig;

abstract class Router {

    protected static $application;

    public static function registerRouter() {
        $config = parse_ini_file('application/config/config.ini');
        DinamicGlobalConfig::registerConfig($config);
        self::$application = SlimApp::getInstance();
        // rutas para la checklist
        self::routesGroupForChecklist();
        // rutas para las respuestas a la checklist 
        self::routesGroupForChecklistAnswered();
        // rutas para la seguridad de la aplicacion
        self::routesForSecurity();
        // rutas para el manejo de errores (siempre deberian ir por el metodo GET)
        self::routesForHandleError();
    }
    
    private static function routesGroupForChecklist() {
        $checklistController = new ChecklistController();
        self::$application->group('/api', function () use ($checklistController) {
            $token = self::$application->request->get('token');
            self::$application->get('/checklist', AuthorizationGuard::validateAuthorization($token), function () use ($checklistController) {
                $cheklist = $checklistController->getChecklistOnlyNoAnswered();
                Response::responseJSON($cheklist);
            });
            // lista una checklist por su id
            self::$application->get('/checklist/:id', AuthorizationGuard::validateAuthorization($token), function ($id) use ($checklistController) {
                $checklist = $checklistController->getChecklistUnique($id);
                Response::responseJSON($checklist);
            });
            // lista las categorias de una checklist
            self::$application->get('/checklist/:id/categories', AuthorizationGuard::validateAuthorization($token), function ($id) use ($checklistController) {
                $associtedCategories = $checklistController->getAssociatedCategories($id);
                Response::responseJSON($associtedCategories);
            });
            // lista una categoria de una checklist por su id
            self::$application->get('/checklist/:checklistId/categories/:categoryId', AuthorizationGuard::validateAuthorization($token), function ($checklistId, $categoryId) use ($checklistController) {
                $associatedCategory = $checklistController->getAssociatedCategory($checklistId, $categoryId);
                Response::responseJSON($associatedCategory);
            });
            // lista los items de una categoria asociada a una checklist
            self::$application->get('/checklist/:checkListId/categories/:categoryId/items', AuthorizationGuard::validateAuthorization($token), function ($checklistId, $categoryId) use ($checklistController) {
                $itemsOfTheAssociatedCategory = $checklistController->getItemsOfTheAssociatedCategory($checklistId, $categoryId);
                Response::responseJSON($itemsOfTheAssociatedCategory);
            });
            // lista un item de una categoria que esta asociada a una checklist
            self::$application->get('/checklist/:checkListId/categories/:categoryId/items/:itemId', AuthorizationGuard::validateAuthorization($token), function () {
                echo 'lista un item de una categoria que esta asociada a una checklist';
            });
        });
    }
    
    public static function routesGroupForChecklistAnswered() {
        // instancia del controlador de checklist contestadas
        $checklistAnsweredController = new ChecklistAnsweredController();
        // aqui el grupo de rutas para los metodos tipo get
        
        // aqui el grupo de rutas para los metodos tipo post
        $token = self::$application->request->get('token');
        if ($token == "" && isset($_REQUEST['body'])) {
            $body = json_decode($_REQUEST['body'], true);
            $token = $body['token'];
        }
        self::$application->group('/api', AuthorizationGuard::validateAuthorization($token), function () use ($checklistAnsweredController) {
            self::$application->post('/checklistanswered', function () use ($checklistAnsweredController) {
                $body = self::$application->request->getBody();
                if ($checklistAnsweredController->setChecklistAnswered($_REQUEST['body'])) {
                    $response = ['data' => ["message" => "La checklist se ha insertado con exito"]];
                } else {
                    $response = ['data' => ["message" => "Ha ocurrido un error al insertar la checklist"]];
                }
                Response::responseJSON($response);
            });
        });
    }
    
    private static function routesGroupForCategory() {
        // aqui se puede agregar las rutas para las acciones relacionadas a la categoria
    }
    
    private static function routesGroupForItems() {
        // aqui se puede agregar las rutas para la acciones relacionadas a los items
    }
    
    private static function routesForHandleError() {
        // ruta para el accesso denegado
        self::$application->get('/api/accessDenied', function () {
            $response = ['data' => ['message' => 'Acceso no autorizado', 'code' => '401']];
            Response::responseJSON($response);
        });
    }
    
    private static function routesForSecurity() {
        $token = self::$application->request->get('token');
        // ruta para autenticar y obtener token
        self::$application->get('/api/authenticate', function (){
            $userId = self::$application->request->get('userId');
            $pwd = self::$application->request->get('pwd');
            $token = AuthorizationGuard::getToken($userId, $pwd);
            $userModel = new UserModel();
            $user = $userModel->findById($userId);
            $response = ['data' => ['message' => 'Acceso Permitido', 'token' => $token, 'user' => $user]];
            Response::responseJSON($response);
        });
    }

}
