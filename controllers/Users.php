<?php
namespace controllers;
class Users extends \app\Controller {

    public function register() {
        $this->loadModel('Users');
        $userModel = $this->Users;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'];
            $this->loadModel('Users');
            $userModel = $this->Users;

            // Vérification email déjà utilisé
            if ($userModel->findByLogin($_POST['email_etudiant'] ?? $_POST['email_particulier'])) {
                $msg = "Cet email est déjà utilisé.";
                $this->render('register', compact('msg'));
                return;
            }

            $veutAdherent = ($role === 'etudiant' && isset($_POST['adherent'])); // <-- Correction ici

            $data = [
                'role' => $role,
                'password' => $_POST['password'],
                'adherent' => 0, // Toujours 0 à l'inscription, paiement plus tard
            ];

            if ($role === 'etudiant') {
                $data['email'] = $_POST['email_etudiant'];
                $data['numero_etudiant'] = $_POST['numero_etudiant'];
                $data['nom'] = $_POST['nom'];
                $data['prenom'] = $_POST['prenom'];
            } else {
                $data['email'] = $_POST['email_particulier'];
                $data['numero_etudiant'] = null;
                $data['nom'] = $_POST['nom_particulier'];
                $data['prenom'] = $_POST['prenom_particulier'];
            }

            if ($userModel->register($data)) {
                // Récupère l'utilisateur pour la connexion auto
                $user = $userModel->findByLogin($data['email']);
                if ($user) {
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'email' => $user['email'],
                        'prenom' => $user['prenom'],
                        'adherent' => $user['adherent'] ?? 0
                    ];
                    if ($role === 'etudiant' && $veutAdherent) {
                        header('Location: /users/pay');
                        exit;
                    } else {
                        header('Location: /');
                        exit;
                    }
                } else {
                    $msg = "Erreur lors de la connexion automatique.";
                    $this->render('register', compact('msg'));
                    return;
                }
            } else {
                $msg = "Erreur lors de la création du compte.";
                $this->render('register', compact('msg'));
                return;
            }
        }
        $this->render('register');
    }

    public function activate() {
        $msg = "Merci, votre adresse mail est maintenant confirmée !";
        $this->render('activate', compact('msg'));
    }

    public function login() {
        $this->loadModel('Users');
        $userModel = $this->Users;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $user = $userModel->findByLogin($login);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'prenom' => $user['prenom'],
                    'email' => $user['email'],
                    'adherent' => $user['adherent'] ?? 0
                ];
                $_SESSION['welcome'] = true;
                header('Location: /catalogue');
                exit;
            } else {
                $msg = "Nom d'utilisateur, email ou mot de passe incorrect.";
                $this->render('login', compact('msg'));
                return;
            }
        }
        $this->render('login');
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }

    public function edit() {
        if (empty($_SESSION['user'])) {
            header('Location: /users/login');
            exit;
        }
        $user = $_SESSION['user'];
        $this->render('edit', compact('user'));
    }

    public function update() {
        if (empty($_SESSION['user'])) {
            header('Location: /users/login');
            exit;
        }
        $this->loadModel('Users');
        $userModel = $this->Users;
        $userId = $_SESSION['user']['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            $success = $userModel->updateProfile($userId, $prenom, $email, $password);
            if ($success) {
                // Mets à jour la session
                $_SESSION['user']['prenom'] = $prenom;
                $_SESSION['user']['email'] = $email;
                $msg = "Profil mis à jour avec succès.";
            } else {
                $msg = "Erreur lors de la mise à jour du profil.";
            }
            $user = $_SESSION['user'];
            $this->render('edit', compact('user', 'msg'));
            return;
        }
        header('Location: /users/edit');
        exit;
    }

    public function pay() {
        if (empty($_SESSION['user'])) {
            header('Location: /users/login');
            exit;
        }
        // Affiche un formulaire de paiement (fictif ou réel)
        $this->render('pay');
    }

    public function payProcess() {
        if (empty($_SESSION['user'])) {
            header('Location: /users/login');
            exit;
        }
        $this->loadModel('Users');
        $userId = $_SESSION['user']['id'];

        $this->Users->setAdherent($userId);
        $_SESSION['user']['adherent'] = 1;
        $msg = "Paiement réussi, vous êtes maintenant adhérent !";
        $this->render('pay', compact('msg'));
    }
}
?>