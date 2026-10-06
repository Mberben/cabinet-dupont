<?php

require_once __DIR__ . '/../models/User.php';

class UsersManager {

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

    public function emailAlreadyExists(string $email): bool {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $request->execute([$email]);
        $user = $request->fetch();
        if (!$user) {
            return false;
        } else {
            return true;
        }
    }

 public function register(string $name, string $email, string $password): void {
    $pdo = $this->connectDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $request = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
    $request->execute([$name, $email, $hash]);
    }

    public function getUser(string $email): User|null {
    $pdo = $this->connectDB();
    $request = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $request->execute([$email]);
    $user = $request->fetch();
    if ($user) {
        return new User($user);
    } else {
        return null;
    }
}
}
