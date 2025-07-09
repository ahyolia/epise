<?php
namespace controllers;

class Catalogue extends \app\Controller {
    public function index() {
        $this->loadModel('Produits');
        $this->loadModel('Categories');
        $produits = $this->Produits->getAll();
        $categories = $this->Categories->getAll();
        $activeCategory = 'all'; // Par défaut
        $this->render('index', compact('produits', 'categories', 'activeCategory'));
    }

    public function categories($id) {
        $this->loadModel('Produits');
        $this->loadModel('Categories');
        $produits = $this->Produits->getByCategory($id);
        $categories = $this->Categories->getAll();
        $activeCategory = $id;
        $this->render('index', compact('produits', 'categories', 'activeCategory'));
    }
}
?>