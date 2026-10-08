<?php

// Entité User : patients, équipe et Dr Dupont (le rôle fait la différence)
class User {
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $email;
    private ?string $password;   // null = patient qui a pris RDV sans créer de compte
    private ?string $phone;      // facultatif
    private string $role;        // 'patient', 'staff' ou 'admin'
    private string $createdAt;

    // Remplit l'objet à partir d'une ligne de la table users
    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->phone = $data['phone'];
        $this->role = $data['role'];
        $this->createdAt = $data['created_at'];   // snake_case en base, camelCase en PHP
    }

    public function getId(): int {
        return $this->id;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    // Prénom + nom en une seule chaîne, pratique pour l'affichage
    public function getFullName(): string {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function getPhone(): ?string {
        return $this->phone;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getCreatedAt(): string {
        return $this->createdAt;
    }

    // Vrai seulement pour le Dr Dupont
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    // Vrai pour tout le personnel du cabinet (admin ou staff) : accès au back office
    public function isStaff(): bool {
        return in_array($this->role, ['admin', 'staff']);
    }

    // Vrai si le patient a créé un compte (il a un mot de passe)
    public function hasAccount(): bool {
        return $this->password !== null;
    }
}