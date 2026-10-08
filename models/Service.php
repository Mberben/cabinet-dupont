<?php

class Service {
    private int $id;
    private string $name;
    private string $description;
    private int $duration;
    private ?string $image;

    public function __construct(array $data) {
        $this->id = (int) $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->duration = (int) $data['duration'];
        $this->image = $data['image'];
    }

        public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getDuration(): int {
        return $this->duration;
    }

    public function getImage(): ?string {
        return $this->image;
    }
}