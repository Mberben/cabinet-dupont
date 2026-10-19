<?php
require_once __DIR__ . '/../models/ServicesManager.php';
require_once __DIR__ . '/../models/UsersManager.php';
require_once __DIR__ . '/../models/OpeningHoursManager.php';
require_once __DIR__ . '/../models/AppointmentsManager.php';

// Contrôleur de la prise de rendez-vous (front office)
class AppointmentsController {

    // Formulaire de prise de rendez-vous
    public function appointment()
    {
        $servicesManager = new ServicesManager();
        $services = $servicesManager->getServices();

        // Patient connecté : on récupère ses infos pour ne pas les redemander
        $currentUser = null;
        if (Auth::isLoggedIn() && !Auth::isStaff()) {
            $usersManager = new UsersManager();
            $currentUser = $usersManager->getUserById((int) $_SESSION['user_id']);
        }

        require __DIR__ . '/../views/appointment.php';
    }

    // Traitement du formulaire : vérifications puis enregistrement
    public function appointmentValid()
    {
        $serviceId = (int) ($_POST['service_id'] ?? 0);
        $date      = $_POST['date'] ?? '';
        $time      = $_POST['time'] ?? '';
        $message   = trim($_POST['message'] ?? '') ?: null;

        // 1. Le service doit exister (sa durée sert aux vérifications)
        $servicesManager = new ServicesManager();
        $service = $servicesManager->getService($serviceId);

        if (!$service || !$date || !$time) {
            header('Location: index.php?page=appointment&error=fields');
            exit;
        }

        $start = $date . ' ' . $time . ':00';   // format SQL : "2026-10-19 09:00:00"
        $duration = $service->getDuration();

        // 2. Dans le futur
        if (strtotime($start) <= time()) {
            header('Location: index.php?page=appointment&error=past');
            exit;
        }

        // 3. Pendant une plage d'ouverture
        $hoursManager = new OpeningHoursManager();
        if (!$hoursManager->isOpen($start, $duration)) {
            header('Location: index.php?page=appointment&error=closed');
            exit;
        }

        // 4. Créneau libre (aucun chevauchement)
        $appointmentsManager = new AppointmentsManager();
        if (!$appointmentsManager->isSlotFree($start, $duration)) {
            header('Location: index.php?page=appointment&error=taken');
            exit;
        }

        // 5. Identifier le patient
        $usersManager = new UsersManager();

        if (Auth::isLoggedIn() && !Auth::isStaff()) {
            // Patient connecté : on utilise son compte
            $patientId = (int) $_SESSION['user_id'];
        } else {
            // Visiteur : on cherche son email, sinon on crée le patient
            $firstname = trim($_POST['firstname'] ?? '');
            $lastname  = trim($_POST['lastname'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $phone     = trim($_POST['phone'] ?? '') ?: null;

            if (!$firstname || !$lastname || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header('Location: index.php?page=appointment&error=fields');
                exit;
            }

            $existing = $usersManager->getUser($email);

            if ($existing) {
                // Un email du personnel ne peut pas servir à réserver comme patient
                if ($existing->getRole() !== 'patient') {
                    header('Location: index.php?page=appointment&error=email');
                    exit;
                }
                $patientId = $existing->getId();
            } else {
                $patientId = $usersManager->createPatient($firstname, $lastname, $email, $phone);
            }
        }

        // 6. Tout est bon : on enregistre (statut "pending")
        $appointmentsManager->createAppointment($start, $message, $patientId, $serviceId);

        header('Location: index.php?page=appointment&success=1');
        exit;
    }

        // Page "Mes rendez-vous" : réservée aux patients connectés
    public function myAppointments()
    {
        Auth::requireLogin();

        // Le personnel n'a pas de rendez-vous : on le renvoie vers le back office
        if (Auth::isStaff()) {
            header('Location: index.php?page=admin-appointments');
            exit;
        }

        // On lit l'id dans la SESSION, jamais dans l'URL : un patient ne peut voir que les siens
        $appointmentsManager = new AppointmentsManager();
        $appointments = $appointmentsManager->getAppointmentsByPatient((int) $_SESSION['user_id']);

        require __DIR__ . '/../views/my-appointments.php';
    }
}