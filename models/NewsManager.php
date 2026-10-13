<?php
require_once __DIR__ . '/News.php';

// Toutes les requêtes SQL sur la table news
class NewsManager {

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

    // Toutes les actualités, de la plus récente à la plus ancienne, avec le nom de l'auteur
    public function getAllNews(): array
    {
        $pdo = $this->connectDB();
        $request = $pdo->query('
            SELECT news.*, CONCAT(users.firstname, " ", users.lastname) AS author_name
            FROM news
            INNER JOIN users ON users.id = news.user_id
            ORDER BY news.published_at DESC
        ');

        $newsList = [];
        foreach ($request->fetchAll() as $data) {
            $newsList[] = new News($data);
        }
        return $newsList;
    }

    // Une seule actualité par son id (null si elle n'existe pas)
    public function getNews(int $id): ?News
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('
            SELECT news.*, CONCAT(users.firstname, " ", users.lastname) AS author_name
            FROM news
            INNER JOIN users ON users.id = news.user_id
            WHERE news.id = :id
        ');
        $request->execute(['id' => $id]);
        $data = $request->fetch();

        return $data ? new News($data) : null;
    }
        // Ajoute une actualité (l'auteur est l'utilisateur connecté)
    public function createNews(string $title, string $content, ?string $image, int $userId): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('INSERT INTO news (title, content, image, user_id) VALUES (:title, :content, :image, :user_id)');
        $request->execute([
            'title'   => $title,
            'content' => $content,
            'image'   => $image,
            'user_id' => $userId
        ]);
    }

    // Met à jour une actualité (l'auteur et la date de publication ne changent pas)
    public function updateNews(int $id, string $title, string $content, ?string $image): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('UPDATE news SET title = :title, content = :content, image = :image WHERE id = :id');
        $request->execute([
            'title'   => $title,
            'content' => $content,
            'image'   => $image,
            'id'      => $id
        ]);
    }

    // Supprime une actualité
    public function deleteNews(int $id): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('DELETE FROM news WHERE id = :id');
        $request->execute(['id' => $id]);
    }
}