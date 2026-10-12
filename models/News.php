<?php

// Entité News : une actualité du cabinet
class News {
    private int $id;
    private string $title;
    private string $content;
    private ?string $image;
    private string $publishedAt;
    private int $userId;
    private ?string $authorName;   // rempli grâce à la jointure avec users

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->title = $data['title'];
        $this->content = $data['content'];
        $this->image = $data['image'];
        $this->publishedAt = $data['published_at'];
        $this->userId = (int) $data['user_id'];
        $this->authorName = $data['author_name'] ?? null;   // absent si la requête ne fait pas de jointure
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getContent(): string { return $this->content; }
    public function getImage(): ?string { return $this->image; }
    public function getPublishedAt(): string { return $this->publishedAt; }
    public function getUserId(): int { return $this->userId; }
    public function getAuthorName(): ?string { return $this->authorName; }
}