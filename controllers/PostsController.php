<?php


require __DIR__ . '/../models/PostsManager.php';
require __DIR__ . '/../models/CommentsManager.php';
require_once __DIR__ . '/../models/ServicesManager.php';

class PostsController{

 public function home() {
    $manager = new PostsManager();
    // Récupère les services et garde les 3 premiers pour l'aperçu de l'accueil
    $servicesManager = new ServicesManager();
    $services = array_slice($servicesManager->getServices(), 0, 3);
    require __DIR__ . '/../views/home.php';
}

 public function post() {

    if(!isset($_GET['id'])){
        header('location: index.php');
        exit;
    }
    $id = $_GET['id'];
    $postManager = new PostsManager();
    $commentsManager = new CommentsManager();
    $article = $postManager->getPost($id);
    $tags = $postManager->getTagsPost($id);
    $comments = $commentsManager->getComments($id);
    

    

 require 'views/article.php';
 } 

 public function comment(){

    if(!isset($_GET['id'])){
        header('location: index.php');
        exit;
    }
    $id = $_GET['id'];

    $author = trim($_POST['author']);
    $content = trim($_POST['content']);
    if(!$author || !$content){
        header('location: index.php?page=post&id=' . $id);
        
    }else{ 
        $commentsManager = new CommentsManager();
        $commentsManager->createComment($id, $author, $content);
        header('Location: index.php?page=post&id=' . $id);
    }

 }
}