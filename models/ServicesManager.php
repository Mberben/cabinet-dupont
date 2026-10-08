<?php

// Charge l'entité Service : le manager va créer des objets Service
require_once __DIR__ . '/Service.php';

// Le manager regroupe toutes les requêtes SQL sur la table services
class ServicesManager {

    // Ouvre la connexion à la base (privée : utilisée seulement ici)
    private function connectDB(): PDO
    {
        // Charge les infos de connexion (DB_HOST, DB_NAME, DB_USER, DB_PASS)
        require_once __DIR__ . '/../config.php';

        // Crée la connexion : adresse + nom de la base + encodage, puis utilisateur et mot de passe
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS
        );

        // Affiche une erreur claire si une requête SQL échoue
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Renvoie la connexion (objet PDO), pas la base elle-même
        return $pdo;
    }

    // Récupère tous les services et les renvoie sous forme d'objets Service
    public function getServices(): array
    {
        // Ouvre la connexion avec la méthode ci-dessus
        $pdo = $this->connectDB();

        // Toutes les colonnes de toutes les lignes, triées par id
        // query() suffit : aucune donnée venant de l'utilisateur dans la requête
        $request = $pdo->query('SELECT * FROM services ORDER BY id');

        // Tableau vide qui va recevoir les objets Service
        $services = [];

        // Pour chaque ligne de la table, on crée un objet Service
        foreach ($request->fetchAll() as $data) {
            $services[] = new Service($data);
        }

        // Renvoie la liste des services au contrôleur
        return $services;
    }
}
