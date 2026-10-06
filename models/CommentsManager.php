<?php

require_once __DIR__ . '/Comment.php';

class CommentsManager {

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

    public function getComments(int $idPost) {
        $pdo = $this->connectDB();

        $request = $pdo->prepare('SELECT * FROM comments WHERE id_post = ?');
        $request->execute([$idPost]);
        $commentaires = $request->fetchAll();

        $comments = [];
        foreach ($commentaires as $commentaire) {
            $comments[] = new Comment($commentaire);
        }
        return $comments;
    }

    public function createComment(int $idPost, string $author, string $content) {
        $pdo = $this->connectDB();

        $request = $pdo->prepare('INSERT INTO comments (id_post, author, content, date) VALUES (:id_post, :author, :content, NOW())');
        $request->execute([
            'id_post' => $idPost,
           'author'  => $author,
           'content' => $content
]);
    }
}

