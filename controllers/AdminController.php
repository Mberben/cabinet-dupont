<?php
// Chargement des managers utilisés par le back office
require_once __DIR__ . '/../models/ServicesManager.php';
require_once __DIR__ . '/../models/NewsManager.php';

// Contrôleur du back office : toutes les pages réservées au personnel du cabinet
class AdminController {

    // ===== OUTILS =====

    // Vérifie que l'utilisateur est connecté ET fait partie du personnel (admin ou staff)
    // Sinon : redirection vers la connexion
    private function requireStaff(): void
    {
        $role = $_SESSION['user_role'] ?? null;

        if (!isset($_SESSION['user_id']) || !in_array($role, ['admin', 'staff'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Gère l'upload d'une image : renvoie le nouveau nom, null si aucun fichier,
    // ou redirige vers $errorUrl si l'image est invalide
    private function uploadImage(string $errorUrl): ?string
    {
        // Aucun fichier envoyé : on renvoie null (l'appelant décide quoi faire)
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        // Vérifications : vraie image, 2 Mo maximum, format autorisé
        if (!getimagesize($_FILES['image']['tmp_name'])
            || $_FILES['image']['size'] > 2 * 1024 * 1024
            || !in_array($imageFileType, ['jpeg', 'jpg', 'png', 'gif', 'webp'])) {
            header('Location: ' . $errorUrl . '&error=image');
            exit;
        }

        // Nom unique pour éviter d'écraser une image existante
        $newFileName = uniqid('img_', true) . '.' . $imageFileType;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $newFileName)) {
            header('Location: ' . $errorUrl . '&error=upload');
            exit;
        }

        return $newFileName;
    }

    // ===== TABLEAU DE BORD =====

    // Page d'accueil du back office
    public function admin() {
        $this->requireStaff();
        require 'views/admin.php';
    }

    // ===== SERVICES =====

    // Liste des services
    public function adminServices() {
        $this->requireStaff();
        $servicesManager = new ServicesManager();
        $services = $servicesManager->getServices();
        require 'views/admin-services.php';
    }

    // Suppression d'un service
    public function deleteService() {
        $this->requireStaff();

        $id = (int) ($_POST['id'] ?? 0);

        try {
            $servicesManager = new ServicesManager();
            $servicesManager->deleteService($id);
            header('Location: index.php?page=admin-services&success=deleted');
        } catch (PDOException $e) {
            // Service lié à au moins un rendez-vous : MySQL bloque (ON DELETE RESTRICT)
            header('Location: index.php?page=admin-services&error=used');
        }
        exit;
    }

    // Formulaire d'ajout d'un service
    public function createService() {
        $this->requireStaff();
        require 'views/create-service.php';
    }

    // Traitement de l'ajout d'un service
    public function createServiceValid() {
        $this->requireStaff();

        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration    = (int) ($_POST['duration'] ?? 0);

        // Champs obligatoires et durée positive
        if (strlen($name) < 3 || strlen($description) < 10 || $duration <= 0) {
            header('Location: index.php?page=create-service&error=fields');
            exit;
        }

        // Image facultative
        $image = $this->uploadImage('index.php?page=create-service');

        $servicesManager = new ServicesManager();
        $servicesManager->createService($name, $description, $duration, $image);

        header('Location: index.php?page=admin-services&success=created');
        exit;
    }

    // Formulaire de modification d'un service, pré-rempli
    public function updateService() {
        $this->requireStaff();

        $servicesManager = new ServicesManager();
        $service = $servicesManager->getService((int) ($_GET['id'] ?? 0));

        // Service introuvable : retour à la liste
        if (!$service) {
            header('Location: index.php?page=admin-services');
            exit;
        }

        require 'views/update-service.php';
    }

    // Traitement de la modification d'un service
    public function updateServiceValid() {
        $this->requireStaff();

        $id          = (int) ($_GET['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $duration    = (int) ($_POST['duration'] ?? 0);

        $servicesManager = new ServicesManager();
        $service = $servicesManager->getService($id);

        if (!$service) {
            header('Location: index.php?page=admin-services');
            exit;
        }

        if (strlen($name) < 3 || strlen($description) < 10 || $duration <= 0) {
            header('Location: index.php?page=update-service&id=' . $id . '&error=fields');
            exit;
        }

        // Nouvelle image si envoyée, sinon on garde l'ancienne
        $image = $this->uploadImage('index.php?page=update-service&id=' . $id) ?? $service->getImage();

        $servicesManager->updateService($id, $name, $description, $duration, $image);

        header('Location: index.php?page=admin-services&success=updated');
        exit;
    }

    // ===== ACTUALITÉS =====

    // Liste des actualités
    public function adminNews() {
        $this->requireStaff();
        $newsManager = new NewsManager();
        $newsList = $newsManager->getAllNews();
        require 'views/admin-news.php';
    }

    // Formulaire d'ajout d'une actualité
    public function createNews() {
        $this->requireStaff();
        require 'views/create-news.php';
    }

    // Traitement de l'ajout d'une actualité
    public function createNewsValid() {
        $this->requireStaff();

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (strlen($title) < 3 || strlen($content) < 10) {
            header('Location: index.php?page=create-news&error=fields');
            exit;
        }

        $image = $this->uploadImage('index.php?page=create-news');

        // L'auteur est la personne connectée
        $newsManager = new NewsManager();
        $newsManager->createNews($title, $content, $image, (int) $_SESSION['user_id']);

        header('Location: index.php?page=admin-news&success=created');
        exit;
    }

    // Formulaire de modification d'une actualité, pré-rempli
    public function updateNews() {
        $this->requireStaff();

        $newsManager = new NewsManager();
        $news = $newsManager->getNews((int) ($_GET['id'] ?? 0));

        if (!$news) {
            header('Location: index.php?page=admin-news');
            exit;
        }

        require 'views/update-news.php';
    }

    // Traitement de la modification d'une actualité
    public function updateNewsValid() {
        $this->requireStaff();

        $id      = (int) ($_GET['id'] ?? 0);
        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        $newsManager = new NewsManager();
        $news = $newsManager->getNews($id);

        if (!$news) {
            header('Location: index.php?page=admin-news');
            exit;
        }

        if (strlen($title) < 3 || strlen($content) < 10) {
            header('Location: index.php?page=update-news&id=' . $id . '&error=fields');
            exit;
        }

        // Nouvelle image si envoyée, sinon on garde l'ancienne
        $image = $this->uploadImage('index.php?page=update-news&id=' . $id) ?? $news->getImage();

        $newsManager->updateNews($id, $title, $content, $image);

        header('Location: index.php?page=admin-news&success=updated');
        exit;
    }

    // Suppression d'une actualité (aucune autre table n'en dépend : pas de try/catch)
    public function deleteNews() {
        $this->requireStaff();

        $newsManager = new NewsManager();
        $newsManager->deleteNews((int) ($_POST['id'] ?? 0));

        header('Location: index.php?page=admin-news&success=deleted');
        exit;
    }
}