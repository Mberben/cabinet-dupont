<?php

class Post {
    private string $id;

    private string $title;

    private string $description;

    private string $content;

    private string $date;

    private ?string $name;

    private ?string $image;


    public function __construct($data) {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->content = $data['content'];
        $this->date = $data['date'];
        $this->name = $data['name'];
        $this->image = $data['image'] ?? null;
    }

    public function getId(): string {
        return $this->id;
    }
    public function getTitle(): string {
        return $this->title;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getContent(): string {
        return $this->content;
    }
    public function getDate(): string {
        return $this->date;
    }
    public function getName(): ?string {
        return $this->name;
    }
      public function getImage(): ?string {
        return $this->image;
    }
}
