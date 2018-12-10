<?php

namespace App\Controllers;


class ChecklistController extends BaseController {
    
    public function __construct() {
        
    }
    
    public function getChecklist() {
        return $this->checklistModel->findAll();
    }
    
    public function getChecklistUnique($id) {
        return $this->checklistModel->findById($id);
    }
    
    public function getAssociatedCategories($id) {
        return $this->categoryModel->findByChecklistId($id);
    }
    
    public function getAssociatedCategory($checklistId, $categoryId) {
        return $this->categoryModel->findById($checklistId, $categoryId);
    }
    
    public function getItemsOfTheAssociatedCategory($checklistId, $categoryId) {
        return $this->itemModel->findByChecklistIdAndCategoryId($checklistId, $categoryId);
    }
}