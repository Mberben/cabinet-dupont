<?php

require_once __DIR__ . '/Appointment.php';

// Toutes les requêtes SQL sur la table appointments
class AppointmentsManager {

    private function connectDB(): PDO
    {
        require_once __DIR__ . '/../config.php';

        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    // Vrai si aucun rendez-vous actif ne chevauche le créneau [début, début + durée]
    // $excludeId : en modification, on ignore le rendez-vous qu'on est en train de déplacer
    public function isSlotFree(string $start, int $duration, int $excludeId = 0): bool
    {
        $end = date('Y-m-d H:i:s', strtotime($start) + $duration * 60);

        $pdo = $this->connectDB();
        $request = $pdo->prepare("
            SELECT COUNT(*)
            FROM appointments
            INNER JOIN services ON services.id = appointments.service_id
            WHERE appointments.status != 'cancelled'
              AND appointments.id != :exclude_id
              AND appointments.appointment_date < :end
              AND DATE_ADD(appointments.appointment_date, INTERVAL services.duration MINUTE) > :start
        ");
        $request->execute(['start' => $start, 'end' => $end, 'exclude_id' => $excludeId]);

        return (int) $request->fetchColumn() === 0;
    }

    // Enregistre un nouveau rendez-vous (statut "pending" par défaut grâce à la base)
    public function createAppointment(string $date, ?string $message, int $patientId, int $serviceId): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('INSERT INTO appointments (appointment_date, message, patient_id, service_id) VALUES (:date, :message, :patient_id, :service_id)');
        $request->execute([
            'date'       => $date,
            'message'    => $message,
            'patient_id' => $patientId,
            'service_id' => $serviceId
        ]);
    }

        // Début de requête commun : le rendez-vous + le patient + le service
    private const SELECT_WITH_JOINS = "
        SELECT appointments.*,
               CONCAT(users.firstname, ' ', users.lastname) AS patient_name,
               users.email AS patient_email,
               users.phone AS patient_phone,
               services.name AS service_name,
               services.duration AS service_duration
        FROM appointments
        INNER JOIN users ON users.id = appointments.patient_id
        INNER JOIN services ON services.id = appointments.service_id
    ";

    // Transforme les lignes SQL en objets Appointment
    private function toAppointments(array $rows): array
    {
        $appointments = [];
        foreach ($rows as $data) {
            $appointments[] = new Appointment($data);
        }
        return $appointments;
    }

    // Tous les rendez-vous, du plus proche au plus lointain
    public function getAllAppointments(): array
    {
        $pdo = $this->connectDB();
        $request = $pdo->query(self::SELECT_WITH_JOINS . ' ORDER BY appointments.appointment_date');
        return $this->toAppointments($request->fetchAll());
    }

    // Un seul rendez-vous par son id
    public function getAppointment(int $id): ?Appointment
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(self::SELECT_WITH_JOINS . ' WHERE appointments.id = :id');
        $request->execute(['id' => $id]);
        $data = $request->fetch();

        return $data ? new Appointment($data) : null;
    }

        // Les rendez-vous d'un patient, du plus récent au plus ancien
    public function getAppointmentsByPatient(int $patientId): array
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare(self::SELECT_WITH_JOINS . ' WHERE appointments.patient_id = :patient_id ORDER BY appointments.appointment_date DESC');
        $request->execute(['patient_id' => $patientId]);
        return $this->toAppointments($request->fetchAll());
    }

    // Change le statut : seulement vers une valeur autorisée
    public function updateStatus(int $id, string $status): void
    {
        if (!array_key_exists($status, Appointment::STATUS_LABELS)) {
            return;
        }

        $pdo = $this->connectDB();
        $request = $pdo->prepare('UPDATE appointments SET status = :status WHERE id = :id');
        $request->execute(['status' => $status, 'id' => $id]);
    }

    // Modifie la date et le service d'un rendez-vous
    public function updateAppointment(int $id, string $date, int $serviceId): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('UPDATE appointments SET appointment_date = :date, service_id = :service_id WHERE id = :id');
        $request->execute(['date' => $date, 'service_id' => $serviceId, 'id' => $id]);
    }
}