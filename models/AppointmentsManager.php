<?php

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
    public function isSlotFree(string $start, int $duration): bool
    {
        $end = date('Y-m-d H:i:s', strtotime($start) + $duration * 60);

        $pdo = $this->connectDB();
        // On joint services pour connaître la durée de chaque rendez-vous existant
        // Chevauchement : l'existant commence avant notre fin ET finit après notre début
        $request = $pdo->prepare("
            SELECT COUNT(*)
            FROM appointments
            INNER JOIN services ON services.id = appointments.service_id
            WHERE appointments.status != 'cancelled'
              AND appointments.appointment_date < :end
              AND DATE_ADD(appointments.appointment_date, INTERVAL services.duration MINUTE) > :start
        ");
        $request->execute(['start' => $start, 'end' => $end]);

        // fetchColumn() renvoie directement le nombre compté
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
}