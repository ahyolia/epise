<?php
namespace controllers;

class Dons extends \app\Controller {

    // Partie utilisateurs/donneurs : ajout d'un don
    public function ajouter() {
        $this->loadModel('Categories');
        $categories = $this->Categories->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie si l'utilisateur est connecté
            if (empty($_SESSION['user'])) {
                $_SESSION['msg'] = "Vous devez être connecté pour faire un don.";
                header('Location: /dons/form');
                exit;
            }

            // Si l'utilisateur est connecté, on ajoute le don
            $this->loadModel('Dons');
            $this->Dons->add([
                'user_id' => $_SESSION['user']['id'],
                'produit' => $_POST['produit'],
                'quantite' => $_POST['quantite'],
                'categorie_id' => $_POST['categorie'],
                'date_don' => date('Y-m-d H:i:s')
            ]);
            $_SESSION['msg'] = "Merci beaucoup pour votre don !";
            header('Location: /dons/ajouter');
            exit;
        }

        // Si le formulaire n'est pas soumis, afficher simplement le formulaire
        $this->render('form', compact('categories'));
    }

    // Partie backoffice : validation/refus des dons
    public function updateDon($id) {
        $this->loadModel('Dons');
        $this->loadModel('Produits');
        $don = $this->Dons->getById($id);

        if ($_POST['action'] === 'valider') {
            // Cherche le produit par nom
            $produit = $this->Produits->getByName($don['produit']);
            if ($produit) {
                // Met à jour le stock
                $newStock = $produit['stock'] + $don['quantite'];
                $this->Produits->update($produit['id'], $produit['name'], $produit['description'], $newStock, $produit['category_id'], $produit['image']);
            } else {
                // Crée un nouveau produit
                $this->Produits->create($don['produit'], '', $don['quantite'], $don['categorie_id']);
            }
            // Marque le don comme validé (optionnel si tu supprimes juste après)
            $this->Dons->valider($id);

            // SUPPRIME le don de la table après validation
            $this->Dons->delete($id);

            $msg = "Don validé, stock mis à jour et don supprimé de la liste.";
        } elseif ($_POST['action'] === 'refuser') {
            // Supprime le don
            $this->Dons->delete($id);
            $msg = "Don refusé.";
        }

        // Recharge la liste des dons non validés pour le backoffice
        $bo['dons'] = $this->Dons->getNonValides();
        $bo['msg'] = $msg;
        $this->render('Dons', compact('bo'));
    }
}
