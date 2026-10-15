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
}