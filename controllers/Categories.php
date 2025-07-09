<?php
namespace controllers;

class Categories extends \app\Controller {

    public function index(): void {
        $this->loadModel('Categories');
        $categories = $this->Categories->getAll();
        $this->render('index', compact('categories'));
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loadModel('Categories');
            $message = $this->Categories->create($_POST);
            echo $message;
        } else {
            $this->render('create');
        }
    }

    public function update(int $id): void {
        $this->loadModel('Categories');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->Categories->update($id, $_POST);
            echo $message;
        } else {
            $categorie = $this->Categories->getById($id);
            $this->render('update', compact('categorie'));
        }
    }

    public function delete(int $id): void {
        $this->loadModel('Categories');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->Categories->delete($id);
            echo $message;
        } else {
            $categorie = $this->Categories->getById($id);
            $this->render('delete', compact('categorie'));
        }
    }
}