<?php
namespace controllers;

class Benevoles extends \app\Controller {
    public function index() {
        $this->loadModel('Benevoles');
        $bo['benevoles'] = $this->Benevoles->getNonValides();
        $bo['benevoles_valides'] = $this->Benevoles->getValides();
        $bo['msg'] = '';
        $this->render('../backoffice/Benevoles', compact('bo'));
    }

    public function updateBenevole($id) {
        $this->loadModel('Benevoles');
        $benevole = $this->Benevoles->getById($id);

        if ($_POST['action'] === 'valider') {
            $this->Benevoles->valider($id);
            $msg = "Bénévole accepté et ajouté à la liste.";
        } elseif ($_POST['action'] === 'refuser' || $_POST['action'] === 'supprimer') {
            $this->Benevoles->delete($id);
            $msg = ($_POST['action'] === 'refuser') ? "Bénévole refusé et supprimé." : "Bénévole validé supprimé.";
        } else {
            $msg = '';
        }
        $bo['benevoles'] = $this->Benevoles->getNonValides();
        $bo['benevoles_valides'] = $this->Benevoles->getValides();
        $bo['msg'] = $msg;
        $this->render('../backoffice/Benevoles', compact('bo'));
    }

    public function liste() {
        $this->loadModel('Benevoles');
        $bo['benevoles'] = $this->Benevoles->getAll();
        $bo['msg'] = '';
        $this->render('../backoffice/BenevolesListe',compact('bo'));
    }

    // Affiche le formulaire d'inscription bénévole
    public function form_benevole() {
        $this->render('form_benevole');
    }

    // Ajoute un bénévole (depuis le formulaire public)
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->loadModel('Benevoles');
            $data = [
                'nom'       => $_POST['nom'] ?? '',
                'prenom'    => $_POST['prenom'] ?? '',
                'email'     => $_POST['email'] ?? '',
                'telephone' => $_POST['telephone'] ?? '',
            ];
            if ($this->Benevoles->create($data)) {
                $_SESSION['msg'] = "Votre demande de bénévolat a bien été envoyée. Merci !";
            } else {
                $_SESSION['msg'] = "Erreur lors de l'envoi de la demande.";
            }
            header('Location: /benevoles/form_benevole');
            exit;
        }
        $this->render('form_benevole');
    }
}