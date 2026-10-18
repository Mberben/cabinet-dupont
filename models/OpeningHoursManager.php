<?php
require_once __DIR__ . '/OpeningHour.php';

// Toutes les requêtes SQL sur la table opening_hours
class OpeningHoursManager {

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

    // Toutes les plages, triées par jour puis par heure d'ouverture
    public function getOpeningHours(): array
    {
        $pdo = $this->connectDB();
        $request = $pdo->query('SELECT * FROM opening_hours ORDER BY day_of_week, open_time');

        $hours = [];
        foreach ($request->fetchAll() as $data) {
            $hours[] = new OpeningHour($data);
        }
        return $hours;
    }

    // Les mêmes plages, regroupées par jour : [1 => [plage, plage], 2 => [...], ...]
    public function getHoursByDay(): array
    {
        $byDay = [];
        foreach ($this->getOpeningHours() as $hour) {
            $byDay[$hour->getDayOfWeek()][] = $hour;
        }
        return $byDay;
    }
        // Met à jour une plage (heures à null si le cabinet est fermé ce jour-là)
    public function updateHour(int $id, ?string $openTime, ?string $closeTime, bool $isClosed): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('UPDATE opening_hours SET open_time = :open_time, close_time = :close_time, is_closed = :is_closed WHERE id = :id');
        $request->execute([
            'open_time'  => $openTime,
            'close_time' => $closeTime,
            'is_closed'  => (int) $isClosed,   // true/false → 1/0 pour la base
            'id'         => $id
        ]);
    }

    // Ajoute une plage d'ouverture
    public function createHour(int $dayOfWeek, string $openTime, string $closeTime): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('INSERT INTO opening_hours (day_of_week, open_time, close_time) VALUES (:day_of_week, :open_time, :close_time)');
        $request->execute([
            'day_of_week' => $dayOfWeek,
            'open_time'   => $openTime,
            'close_time'  => $closeTime
        ]);
    }

    // Supprime une plage
    public function deleteHour(int $id): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('DELETE FROM opening_hours WHERE id = :id');
        $request->execute(['id' => $id]);
    }

        // Vrai si le créneau [début, début + durée] tient entièrement dans une plage d'ouverture
    public function isOpen(string $start, int $duration): bool
    {
        $startTs = strtotime($start);
        $endTs   = $startTs + $duration * 60;

        // Un rendez-vous ne peut pas déborder sur le jour suivant
        if (date('Y-m-d', $startTs) !== date('Y-m-d', $endTs)) {
            return false;
        }

        $pdo = $this->connectDB();
        // Une plage ouverte du bon jour qui commence avant le RDV et finit après
        $request = $pdo->prepare('
            SELECT COUNT(*) FROM opening_hours
            WHERE day_of_week = :day
              AND is_closed = 0
              AND open_time <= :start_time
              AND close_time >= :end_time
        ');
        $request->execute([
            'day'        => (int) date('N', $startTs),   // 1 = lundi, comme day_of_week
            'start_time' => date('H:i:s', $startTs),
            'end_time'   => date('H:i:s', $endTs)
        ]);

        return (int) $request->fetchColumn() > 0;
    }
}