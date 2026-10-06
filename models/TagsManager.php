<?php

require_once __DIR__ . '/../models/Tag.php';


class TagsManager {

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

public function getTags(){
    $pdo = $this->connectDB();

    $request = $pdo->query('SELECT * FROM tags');
    foreach($request->fetchAll() as $tag) {
        $tags[] = new Tag($tag);
    }

    return $tags;
}

public function createTag(string $name){
    $pdo = $this->connectDB();

    $request = $pdo->prepare('INSERT INTO tags (name) VALUES (:name)');
    $request->execute([
        'name' => $name
    ]);
}
}