<?php

// Entité OpeningHour : une plage d'ouverture du cabinet (un jour peut en avoir plusieurs)
class OpeningHour {

    // Noms des jours : 1 = lundi ... 7 = dimanche, comme date('N') en PHP
    public const DAYS = [
        1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi',
        5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'
    ];

    private int $id;
    private int $dayOfWeek;
    private ?string $openTime;    // null si le cabinet est fermé ce jour-là
    private ?string $closeTime;
    private bool $isClosed;

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->dayOfWeek = (int) $data['day_of_week'];
        $this->openTime = $data['open_time'];
        $this->closeTime = $data['close_time'];
        $this->isClosed = (bool) $data['is_closed'];   // TINYINT(1) en base : 0 ou 1 → false ou true
    }

    public function getId(): int { return $this->id; }
    public function getDayOfWeek(): int { return $this->dayOfWeek; }
    public function getOpenTime(): ?string { return $this->openTime; }
    public function getCloseTime(): ?string { return $this->closeTime; }
    public function isClosed(): bool { return $this->isClosed; }

    // Nom du jour en toutes lettres : "Lundi"
    public function getDayName(): string {
        return self::DAYS[$this->dayOfWeek];
    }

    // Plage lisible : "8h00 - 12h00"
    public function getLabel(): string {
        return $this->formatTime($this->openTime) . ' - ' . $this->formatTime($this->closeTime);
    }

    // Transforme "08:00:00" (format SQL) en "8h00"
    private function formatTime(?string $time): string {
        return $time ? date('G\hi', strtotime($time)) : '';
    }
}