<?php
namespace controllers;

class Carrousel extends \app\Controller {

    /**
     * Affiche la liste des éléments du carrousel
     * @return void
     */
    public function index(): void {
        // Charge le modèle "Carrousel"
        $this->loadModel('Carrousel');

        // Récupère tous les éléments du carrousel
        $carrousel = $this->Carrousel->getAll();

        // Envoie les données à la vue index
        $this->render('index', compact('carrousel'));
    }

    /**
     * Crée un nouvel élément dans le carrousel
     * @return void
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loadModel('Carrousel');
            $message = $this->Carrousel->create($_POST);
            echo $message;
        } else {
            $this->render('create');
        }
    }

    /**
     * Met à jour un élément du carrousel
     * @param int $id
     * @return void
     */
    public function update(int $id): void {
        $this->loadModel('Carrousel');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->Carrousel->update($id, $_POST);
            echo $message;
        } else {
            $carrouselItem = $this->Carrousel->getById($id);
            $this->render('update', compact('carrouselItem'));
        }
    }

    /**
     * Supprime un élément du carrousel
     * @param int $id
     * @return void
     */
    public function delete(int $id): void {
        $this->loadModel('Carrousel');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->Carrousel->delete($id);
            echo $message;
        } else {
            $carrouselItem = $this->Carrousel->getById($id);
            $this->render('delete', compact('carrouselItem'));
        }
    }
}