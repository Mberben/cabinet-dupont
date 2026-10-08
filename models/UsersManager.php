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

    // Inscription d'un patient avec compte (mot de passe haché)
    public function register(string $firstname, string $lastname, string $email, string $password): void {
    $pdo = $this->connectDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $request = $pdo->prepare('INSERT INTO users (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password)');
    $request->execute([
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'email'     => $email,
        'password'  => $hash
    ]);
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

// Complète le compte d'un patient qui avait pris RDV sans compte
public function completeAccount(int $id, string $firstname, string $lastname, string $password): void {
    $pdo = $this->connectDB();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $request = $pdo->prepare('UPDATE users SET firstname = :firstname, lastname = :lastname, password = :password WHERE id = :id');
    $request->execute([
        'firstname' => $firstname,
        'lastname'  => $lastname,
        'password'  => $hash,
        'id'        => $id
    ]);
}

}
