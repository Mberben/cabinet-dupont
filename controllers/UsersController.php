<?php

require_once __DIR__ . '/../models/UsersManager.php';

class UsersController {


    // Affiche le formulaire de connexion
    public function login() {
        require __DIR__ . '/../views/login.php';
    }

    // Traitement du formulaire de connexion
    public function loginValid() {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';   // pas de trim : un espace peut faire partie du mot de passe

        // Champs vides
        if (!$email || !$password) {
            header('Location: index.php?page=login&error=fields');
            exit;
        }

        $usersManager = new UsersManager();
        $user = $usersManager->getUser($email);

        // Email inconnu, patient sans compte, ou mauvais mot de passe : même message d'erreur
        if (!$user || !$user->hasAccount() || !password_verify($password, $user->getPassword())) {
            header('Location: index.php?page=login&error=credentials');
            exit;
        }

        // Nouvel identifiant de session à la connexion (protection contre le vol de session)
        session_regenerate_id(true);

        // On garde en session ce dont les pages auront besoin
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getFirstname();
        $_SESSION['user_role'] = $user->getRole();

        // Le personnel va au back office, les patients à l'accueil
        if ($user->isStaff()) {
            header('Location: index.php?page=admin');
        } else {
            header('Location: index.php');
        }
        exit;
    }

    // Déconnexion : on vide la session puis on retourne à l'accueil
    public function logout() {
        session_destroy();
        header('Location: index.php');
        exit;
    }

    // ===== INSCRIPTION (patients) =====

    // Affiche le formulaire d'inscription
    public function register() {
        require __DIR__ . '/../views/register.php';
    }

    // Traitement du formulaire d'inscription
    public function registerValid() {
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname = trim($_POST['lastname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Champs vides
        if (!$firstname || !$lastname || !$email || !$password) {
            header('Location: index.php?page=register&error=fields');
            exit;
        }

        // Format d'email invalide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?page=register&error=email');
            exit;
        }

        // Mot de passe trop court
        if (strlen($password) < 8) {
            header('Location: index.php?page=register&error=password');
            exit;
        }

        $usersManager = new UsersManager();
        $user = $usersManager->getUser($email);

        if ($user && $user->hasAccount()) {
            // L'email a déjà un compte complet
            header('Location: index.php?page=register&error=used');
            exit;
        }

        if ($user) {
            // Patient qui a déjà pris RDV sans compte : on complète son compte
            $usersManager->completeAccount($user->getId(), $firstname, $lastname, $password);
        } else {
            // Nouveau patient : on crée le compte
            $usersManager->register($firstname, $lastname, $email, $password);
        }

        header('Location: index.php?page=login&success=register');
        exit;
    }
}