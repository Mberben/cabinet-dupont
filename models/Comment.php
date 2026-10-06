<?php

class Comment{

    private int $id;

    private string $content;

    private string $author;

    private string $date;

    public function __construct($data) {
        $this->id = $data['id'];
        $this->content = $data['content'];
        $this->author = $data['author'];
        $this->date = $data['date'];

    }
    public function getId() {
        return $this->id;
    }
        public function getContent() {
        return $this->content;
    }
        public function getAuthor() {
        return $this->author;
    }
    public function getDate() {
        return $this->date;
    }


}