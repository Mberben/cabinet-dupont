<?php

// Middleware d'authentification : vérifications à appeler avant les pages restreintes
class Auth {

    // Vrai si un utilisateur est connecté
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    // Vrai si l'utilisateur connecté fait partie du personnel (admin ou staff)
    public static function isStaff(): bool
    {
        return self::isLoggedIn() && in_array($_SESSION['user_role'] ?? '', ['admin', 'staff']);
    }

    // Bloque l'accès si personne n'est connecté (pages patient : "Mes rendez-vous"...)
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Bloque l'accès si l'utilisateur n'est pas du personnel (pages du back office)
    public static function requireStaff(): void
    {
        if (!self::isStaff()) {
            header('Location: index.php?page=login');
            exit;
        }
    }
}