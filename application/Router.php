<?php

use Slim\Slim as SlimApp;
use App\Authorization\AuthorizationGuard;
use App\Controllers\ChecklistController;
use App\Helpers\Response;

abstract class Router {

    protected static $application;

    public static function registerRouter() {
        self::$application = SlimApp::getInstance();
        // rutas para la checklist
        self::routesGroupForChecklist();
        // rutas para el manejo de errores (siempre deberian ir por el metodo GET)
        self::routesForHandleError();
    }
    
    private static function routesGroupForChecklist() {
        $checklistController = new ChecklistController();
        self::$application->group('/api', function () use ($checklistController) {
            self::$application->get('/checklist', AuthorizationGuard::validateAuthorization(true), function () use ($checklistController) {
                $cheklist = $checklistController->getChecklist();
                Response::responseJSON($cheklist);
            });
            // lista una checklist por su id
            self::$application->get('/checklist/:id', AuthorizationGuard::validateAuthorization(true), function ($id) use ($checklistController) {
                $checklist = $checklistController->getChecklistUnique($id);
                Response::responseJSON($checklist);
            });
            // lista las categorias de una checklist
            self::$application->get('/checklist/:id/categories', AuthorizationGuard::validateAuthorization(true), function ($id) use ($checklistController) {
                $associtedCategories = $checklistController->getAssociatedCategories($id);
                Response::responseJSON($associtedCategories);
            });
            // lista una categoria de una checklist por su id
            self::$application->get('/checklist/:checklistId/categories/:categoryId', AuthorizationGuard::validateAuthorization(true), function ($checklistId, $categoryId) use ($checklistController) {
                $associatedCategory = $checklistController->getAssociatedCategory($checklistId, $categoryId);
                Response::responseJSON($associatedCategory);
            });
            // lista los items de una categoria asociada a una checklist
            self::$application->get('/checklist/:checkListId/categories/:categoryId/items', AuthorizationGuard::validateAuthorization(true), function ($checklistId, $categoryId) use ($checklistController) {
                $itemsOfTheAssociatedCategory = $checklistController->getItemsOfTheAssociatedCategory($checklistId, $categoryId);
                Response::responseJSON($itemsOfTheAssociatedCategory);
            });
            // lista un item de una categoria que esta asociada a una checklist
            self::$application->get('/checklist/:checkListId/categories/:categoryId/items/:itemId', AuthorizationGuard::validateAuthorization(true), function () {
                echo 'lista un item de una categoria que esta asociada a una checklist';
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
        self::$application->get('/accessDenied', function () {
            echo 'Acceso denegado, contenido protegido!';
        });
    }

}
