<?php

require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../models/Tag.php';


class PostsManager {

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

public function getPosts(){
    $pdo = $this->connectDB();

    $request = $pdo->query('SELECT posts.id, posts.title, posts.description, posts.content, posts.date, posts.image, posts.id_user, users.name FROM posts LEFT JOIN users ON posts.id_user = users.id');
    $articles = $request->fetchAll();

    $posts = [];
    foreach ($articles as &$article) {
        $posts[] = new Post($article);
    
    }
    return $posts;
}

public function getTagsPost(int $id):array{
    $pdo = $this->connectDB();

$request = $pdo->prepare('
    SELECT tags.id, tags.name
    FROM posts_tags
    INNER JOIN tags ON posts_tags.id_tag = tags.id
    WHERE posts_tags.id_post = :id_post
');
$request->execute(['id_post' => $id]);
    $etiquettes = $request->fetchAll();


    $tags = [];
    foreach ($etiquettes as $etiquette) {
        $tags[] = new Tag($etiquette);
    
    }
    return $tags;
}

public function createPost(string $title, string $content, string $resume, ?string $image, array $tags): void {
    // Connexion à la base de données , toujours la meme
 $pdo = $this->connectDB();

 // enregistrement Base de données
    $request =$pdo->prepare('INSERT INTO posts (title, description, content, date, id_user, image) VALUES (:title, :description, :content, NOW(), :id_user,:image)');
    $request->execute([
    'title' => $title,
    'description' => $resume,
    'content' => $content,
    'id_user'     => $_SESSION["user_id"],
    'image' => $image
]);
$id_post = $pdo->lastInsertId();

$request = $pdo->prepare('INSERT INTO posts_tags (id_post, id_tag) VALUES (:id_post, :id_tag)');
foreach ($tags as $tag) {
    $request->execute([
        'id_post' => $id_post,
        'id_tag'  => (int) $tag
    ]);
}
}

public function deletePost(int $id){ 
    $pdo = $this->connectDB();

    $request = $pdo->prepare('DELETE FROM posts WHERE id = ?');
    $request->execute([$id]);
}
public function getPost(int $id): Post|null{
    $pdo = $this->connectDB();

    $request = $pdo->prepare('SELECT posts.*, users.name FROM posts LEFT JOIN users ON posts.id_user = users.id WHERE posts.id = ?');
    $request->execute([$id]);
    $article = $request->fetch();
    if(!$article)
        return null;

    return new Post($article);

    }

public function updatePost(int $id, string $title, string $content, string $resume, ?string $image): void {
    $pdo = $this->connectDB();

    $request = $pdo->prepare('UPDATE posts SET title = :title, description = :description, content = :content, image = :image WHERE id = :id');
    $request->execute([
        'title'       => $title,
        'description' => $resume,
        'content'     => $content,
        'image'       => $image,
        'id'          => $id,
    ]);
}
}

