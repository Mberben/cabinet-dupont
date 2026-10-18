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

    // ===== GESTION DES PATIENTS (back office) =====

    // Tous les patients, triés par nom de famille
    public function getPatients(): array
    {
        $pdo = $this->connectDB();
        $request = $pdo->query("SELECT * FROM users WHERE role = 'patient' ORDER BY lastname, firstname");

        $patients = [];
        foreach ($request->fetchAll() as $data) {
            $patients[] = new User($data);
        }
        return $patients;
    }

    // Un seul patient par son id (null s'il n'existe pas ou n'est pas patient)
    public function getPatient(int $id): ?User
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("SELECT * FROM users WHERE id = :id AND role = 'patient'");
        $request->execute(['id' => $id]);
        $data = $request->fetch();

        return $data ? new User($data) : null;
    }

    // Vrai si l'email est déjà utilisé par un AUTRE utilisateur que $exceptId
    // (en modification, le patient garde le droit d'utiliser son propre email)
    public function emailUsedByOther(string $email, int $exceptId = 0): bool
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('SELECT id FROM users WHERE email = :email AND id != :id');
        $request->execute(['email' => $email, 'id' => $exceptId]);
        return (bool) $request->fetch();
    }

    // Ajout d'un patient sans mot de passe (par l'équipe ou lors d'une prise de RDV)
    // Renvoie l'id du patient créé, pour pouvoir lui attacher son rendez-vous
    public function createPatient(string $firstname, string $lastname, string $email, ?string $phone): int
    {
            $pdo = $this->connectDB();
            $request = $pdo->prepare('INSERT INTO users (firstname, lastname, email, phone) VALUES (:firstname, :lastname, :email, :phone)');
            $request->execute([
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'email'     => $email,
                'phone'     => $phone
            ]);

            // lastInsertId() renvoie l'id généré par l'AUTO_INCREMENT de la ligne qu'on vient d'insérer
            return (int) $pdo->lastInsertId();
    }

    // Modification d'un patient (le mot de passe et le rôle ne changent pas)
    public function updatePatient(int $id, string $firstname, string $lastname, string $email, ?string $phone): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone WHERE id = :id AND role = 'patient'");
        $request->execute([
            'firstname' => $firstname,
            'lastname'  => $lastname,
            'email'     => $email,
            'phone'     => $phone,
            'id'        => $id
        ]);
    }

    // Suppression d'un patient : ses rendez-vous partent avec lui (ON DELETE CASCADE)
    public function deletePatient(int $id): void
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare("DELETE FROM users WHERE id = :id AND role = 'patient'");
        $request->execute(['id' => $id]);
    }

        // Un utilisateur par son id (pour le patient connecté)
    public function getUserById(int $id): ?User
    {
        $pdo = $this->connectDB();
        $request = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $request->execute(['id' => $id]);
        $data = $request->fetch();

        return $data ? new User($data) : null;
    }

}
