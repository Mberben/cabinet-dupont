<?php

// Entité Appointment : un rendez-vous, avec les infos du patient et du service (jointure)
class Appointment {

    // Libellés français des statuts
    public const STATUS_LABELS = [
        'pending'   => 'En attente',
        'confirmed' => 'Confirmé',
        'cancelled' => 'Annulé',
    ];

    private int $id;
    private string $appointmentDate;
    private string $status;
    private ?string $message;
    private int $patientId;
    private int $serviceId;

    // Rempli grâce aux jointures avec users et services
    private string $patientName;
    private string $patientEmail;
    private ?string $patientPhone;
    private string $serviceName;
    private int $serviceDuration;

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->appointmentDate = $data['appointment_date'];
        $this->status = $data['status'];
        $this->message = $data['message'];
        $this->patientId = (int) $data['patient_id'];
        $this->serviceId = (int) $data['service_id'];
        $this->patientName = $data['patient_name'];
        $this->patientEmail = $data['patient_email'];
        $this->patientPhone = $data['patient_phone'];
        $this->serviceName = $data['service_name'];
        $this->serviceDuration = (int) $data['service_duration'];
    }

    public function getId(): int { return $this->id; }
    public function getAppointmentDate(): string { return $this->appointmentDate; }
    public function getStatus(): string { return $this->status; }
    public function getMessage(): ?string { return $this->message; }
    public function getPatientId(): int { return $this->patientId; }
    public function getServiceId(): int { return $this->serviceId; }
    public function getPatientName(): string { return $this->patientName; }
    public function getPatientEmail(): string { return $this->patientEmail; }
    public function getPatientPhone(): ?string { return $this->patientPhone; }
    public function getServiceName(): string { return $this->serviceName; }
    public function getServiceDuration(): int { return $this->serviceDuration; }

    // "En attente", "Confirmé" ou "Annulé"
    public function getStatusLabel(): string {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    // Date lisible : "lun. 19/10/2026 à 9h00"
    public function getFormattedDate(): string {
        return date('d/m/Y \à G\hi', strtotime($this->appointmentDate));
    }

    // Vrai si le rendez-vous est déjà passé
    public function isPast(): bool {
        return strtotime($this->appointmentDate) < time();
    }
}