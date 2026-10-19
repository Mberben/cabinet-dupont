<?php
// Chargement des managers utilisés par le back office
require_once __DIR__ . '/../models/ServicesManager.php';
require_once __DIR__ . '/../models/NewsManager.php';
require_once __DIR__ . '/../models/OpeningHoursManager.php';
require_once __DIR__ . '/../models/UsersManager.php';
require_once __DIR__ . '/../models/AppointmentsManager.php';

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
        Auth::requireStaff();
        require 'views/admin.php';
    }

    // ===== SERVICES =====

    // Liste des services
    public function adminServices() {
        Auth::requireStaff();
        $servicesManager = new ServicesManager();
        $services = $servicesManager->getServices();
        require 'views/admin-services.php';
    }

    // Suppression d'un service
    public function deleteService() {
        Auth::requireStaff();

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
        Auth::requireStaff();
        require 'views/create-service.php';
    }

    // Traitement de l'ajout d'un service
    public function createServiceValid() {
        Auth::requireStaff();

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
        Auth::requireStaff();

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
        Auth::requireStaff();

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
        Auth::requireStaff();
        $newsManager = new NewsManager();
        $newsList = $newsManager->getAllNews();
        require 'views/admin-news.php';
    }

    // Formulaire d'ajout d'une actualité
    public function createNews() {
        Auth::requireStaff();
        require 'views/create-news.php';
    }

    // Traitement de l'ajout d'une actualité
    public function createNewsValid() {
        Auth::requireStaff();

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
        Auth::requireStaff();

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
        Auth::requireStaff();

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
        Auth::requireStaff();

        $newsManager = new NewsManager();
        $newsManager->deleteNews((int) ($_POST['id'] ?? 0));

        header('Location: index.php?page=admin-news&success=deleted');
        exit;
    }
        // ===== HORAIRES =====

    // Page de gestion des horaires : toutes les plages dans un formulaire
    public function adminHours() {
        Auth::requireStaff();
        $hoursManager = new OpeningHoursManager();
        $hours = $hoursManager->getOpeningHours();
        require 'views/admin-hours.php';
    }

    // Enregistre toutes les plages modifiées d'un coup
    public function updateHoursValid() {
        Auth::requireStaff();

        // $_POST['hours'] = [id => ['open_time' => ..., 'close_time' => ..., 'is_closed' => ...], ...]
        $hoursData = $_POST['hours'] ?? [];

        // 1. On vérifie TOUTES les plages avant d'en enregistrer une seule
        foreach ($hoursData as $data) {
            $isClosed = isset($data['is_closed']);   // case cochée = présente dans $_POST
            $open  = $data['open_time'] ?? '';
            $close = $data['close_time'] ?? '';

            // Jour ouvert : les deux heures sont obligatoires, et l'ouverture avant la fermeture
            if (!$isClosed && (!$open || !$close || $open >= $close)) {
                header('Location: index.php?page=admin-hours&error=times');
                exit;
            }
        }

        // 2. Tout est valide : on enregistre
        $hoursManager = new OpeningHoursManager();
        foreach ($hoursData as $id => $data) {
            $isClosed = isset($data['is_closed']);
            $hoursManager->updateHour(
                (int) $id,
                $isClosed ? null : $data['open_time'],
                $isClosed ? null : $data['close_time'],
                $isClosed
            );
        }

        header('Location: index.php?page=admin-hours&success=updated');
        exit;
    }

    // Ajoute une nouvelle plage
    public function createHourValid() {
        Auth::requireStaff();

        $day   = (int) ($_POST['day_of_week'] ?? 0);
        $open  = $_POST['open_time'] ?? '';
        $close = $_POST['close_time'] ?? '';

        // Jour entre 1 et 7, heures remplies, ouverture avant fermeture
        if ($day < 1 || $day > 7 || !$open || !$close || $open >= $close) {
            header('Location: index.php?page=admin-hours&error=times');
            exit;
        }

        $hoursManager = new OpeningHoursManager();
        $hoursManager->createHour($day, $open, $close);

        header('Location: index.php?page=admin-hours&success=created');
        exit;
    }

    // Supprime une plage
    public function deleteHour() {
        Auth::requireStaff();

        $hoursManager = new OpeningHoursManager();
        $hoursManager->deleteHour((int) ($_GET['id'] ?? 0));

        header('Location: index.php?page=admin-hours&success=deleted');
        exit;
    }

        // ===== PATIENTS =====

    // Liste des patients
    public function adminPatients() {
        Auth::requireStaff();
        $usersManager = new UsersManager();
        $patients = $usersManager->getPatients();
        require 'views/admin-patients.php';
    }

    // Formulaire d'ajout d'un patient
    public function createPatient() {
        Auth::requireStaff();
        require 'views/create-patient.php';
    }

    // Traitement de l'ajout
    public function createPatientValid() {
        Auth::requireStaff();

        $firstname = trim($_POST['firstname'] ?? '');
        $lastname  = trim($_POST['lastname'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '') ?: null;   // champ vide → null en base

        if (!$firstname || !$lastname || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?page=create-patient&error=fields');
            exit;
        }

        $usersManager = new UsersManager();

        // L'email est UNIQUE en base : on vérifie avant pour afficher un message clair
        if ($usersManager->emailUsedByOther($email)) {
            header('Location: index.php?page=create-patient&error=email');
            exit;
        }

        $usersManager->createPatient($firstname, $lastname, $email, $phone);

        header('Location: index.php?page=admin-patients&success=created');
        exit;
    }

    // Formulaire de modification, pré-rempli
    public function updatePatient() {
        Auth::requireStaff();

        $usersManager = new UsersManager();
        $patient = $usersManager->getPatient((int) ($_GET['id'] ?? 0));

        if (!$patient) {
            header('Location: index.php?page=admin-patients');
            exit;
        }

        require 'views/update-patient.php';
    }

    // Traitement de la modification
    public function updatePatientValid() {
        Auth::requireStaff();

        $id        = (int) ($_GET['id'] ?? 0);
        $firstname = trim($_POST['firstname'] ?? '');
        $lastname  = trim($_POST['lastname'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $phone     = trim($_POST['phone'] ?? '') ?: null;

        $usersManager = new UsersManager();

        if (!$usersManager->getPatient($id)) {
            header('Location: index.php?page=admin-patients');
            exit;
        }

        if (!$firstname || !$lastname || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?page=update-patient&id=' . $id . '&error=fields');
            exit;
        }

        // Email déjà pris par quelqu'un d'autre que ce patient
        if ($usersManager->emailUsedByOther($email, $id)) {
            header('Location: index.php?page=update-patient&id=' . $id . '&error=email');
            exit;
        }

        $usersManager->updatePatient($id, $firstname, $lastname, $email, $phone);

        header('Location: index.php?page=admin-patients&success=updated');
        exit;
    }

    // Suppression d'un patient
    public function deletePatient() {
        Auth::requireStaff();

        $usersManager = new UsersManager();
        $usersManager->deletePatient((int) ($_POST['id'] ?? 0));

        header('Location: index.php?page=admin-patients&success=deleted');
        exit;
    }

        // ===== RENDEZ-VOUS =====

    // Liste de tous les rendez-vous
    public function adminAppointments() {
        Auth::requireStaff();
        $appointmentsManager = new AppointmentsManager();
        $appointments = $appointmentsManager->getAllAppointments();
        require 'views/admin-appointments.php';
    }

    // Confirmer un rendez-vous
    public function confirmAppointment() {
        Auth::requireStaff();
        $appointmentsManager = new AppointmentsManager();
        $appointmentsManager->updateStatus((int) ($_POST['id'] ?? 0), 'confirmed');
        header('Location: index.php?page=admin-appointments&success=confirmed');
        exit;
    }

    // Annuler un rendez-vous (on garde la ligne pour l'historique)
    public function cancelAppointment() {
        Auth::requireStaff();
        $appointmentsManager = new AppointmentsManager();
        $appointmentsManager->updateStatus((int) ($_POST['id'] ?? 0), 'cancelled');
        header('Location: index.php?page=admin-appointments&success=cancelled');
        exit;
    }

    // Formulaire de modification d'un rendez-vous
    public function updateAppointment() {
        Auth::requireStaff();

        $appointmentsManager = new AppointmentsManager();
        $appointment = $appointmentsManager->getAppointment((int) ($_GET['id'] ?? 0));

        if (!$appointment) {
            header('Location: index.php?page=admin-appointments');
            exit;
        }

        $servicesManager = new ServicesManager();
        $services = $servicesManager->getServices();

        require 'views/update-appointment.php';
    }

    // Traitement de la modification : mêmes vérifications que la prise de rendez-vous
    public function updateAppointmentValid() {
        Auth::requireStaff();

        $id        = (int) ($_GET['id'] ?? 0);
        $serviceId = (int) ($_POST['service_id'] ?? 0);
        $date      = $_POST['date'] ?? '';
        $time      = $_POST['time'] ?? '';
        $errorUrl  = 'index.php?page=update-appointment&id=' . $id;

        $appointmentsManager = new AppointmentsManager();
        if (!$appointmentsManager->getAppointment($id)) {
            header('Location: index.php?page=admin-appointments');
            exit;
        }

        $servicesManager = new ServicesManager();
        $service = $servicesManager->getService($serviceId);

        if (!$service || !$date || !$time) {
            header('Location: ' . $errorUrl . '&error=fields');
            exit;
        }

        $start = $date . ' ' . $time . ':00';
        $duration = $service->getDuration();

        if (strtotime($start) <= time()) {
            header('Location: ' . $errorUrl . '&error=past');
            exit;
        }

        $hoursManager = new OpeningHoursManager();
        if (!$hoursManager->isOpen($start, $duration)) {
            header('Location: ' . $errorUrl . '&error=closed');
            exit;
        }

        // On exclut ce rendez-vous lui-même de la vérification de chevauchement
        if (!$appointmentsManager->isSlotFree($start, $duration, $id)) {
            header('Location: ' . $errorUrl . '&error=taken');
            exit;
        }

        $appointmentsManager->updateAppointment($id, $start, $serviceId);

        header('Location: index.php?page=admin-appointments&success=updated');
        exit;
    }
}