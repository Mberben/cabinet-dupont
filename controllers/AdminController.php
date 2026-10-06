<?php
require_once __DIR__ . '/../models/PostsManager.php';
require_once __DIR__ . '/../models/TagsManager.php';

class AdminController {

 public function admin() {
   if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
   }
    require 'views/admin.php';
 }

 public function adminPosts() {
   if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
   }
       $manager = new PostsManager();
      $articles = $manager->getPosts();
    require 'views/admin-posts.php';
 }
  public function adminTags() {
   if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
   }
      $tagsmanager = new TagsManager();
      $tags = $tagsmanager->getTags();
    require 'views/admin-tags.php';
 }

 public function adminCreatePost() {
   if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
   }
      $tagsmanager = new TagsManager();
      $tags = $tagsmanager->getTags();
    require 'views/create-article.php';
 }

public function adminCreatePostValid()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }

    $resume  = trim($_POST['description'] ?? '');
    $title   = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags    = $_POST['tags'] ?? [];

    if (strlen($title) < 3 || strlen($content) < 10 || strlen($resume) < 3) {
        header('Location: index.php?page=create-post&error=true');
        exit;
    }

    $newFileName = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!getimagesize($_FILES['image']['tmp_name'])
            || $_FILES['image']['size'] > 2 * 1024 * 1024
            || !in_array($imageFileType, ['jpeg', 'jpg', 'png', 'gif'])) {
            header('Location: index.php?page=create-post&error=true');
            exit;
        }

        $newFileName = uniqid('img_', true) . '.' . $imageFileType;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $newFileName)) {
            header('Location: index.php?page=create-post&error=upload');
            exit;
        }
    }

    $manager = new PostsManager();
    $manager->createPost($title, $content, $resume, $newFileName, $tags);
    header('Location: index.php?page=admin-posts');
    exit;
}
   public function deletePost()
   {
      if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
      }
      if(!isset($_GET['id'])) {
      header('location: index.php?page=login');
      exit;
      }
      $id = $_GET['id'];
         $manager = new PostsManager();
        $manager->deletePost($id);
        header('location: index.php?page=admin-posts');
   
   }
  public function updatePost() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    if (!isset($_GET['id'])) {
        header('Location: index.php?page=admin-posts');
        exit;
    }

    $manager = new PostsManager();
    $article = $manager->getPost((int) $_GET['id']);

    if (!$article) {
        header('Location: index.php?page=admin-posts');
        exit;
    }

    require 'views/update-post.php';
}

public function updatePostValid() {

   if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
   }
   if (!isset($_GET['id'])) {
        header('Location: index.php?page=admin-posts');
        exit;
    }
   
    $resume = trim($_POST['description']);
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    // $tags = $_POST['tags'];
    $id = $_GET['id'];

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $fileName = 'uploads/' .basename($_FILES['image']['name']);
      $imageFileType = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));

   if (!getimagesize($_FILES['image']['tmp_name'])) {
    header('Location: index.php?page=create-post&error=true');
    
   } else if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
    header('Location: index.php?page=create-post&error=true');
    
   } else if (!in_array($imageFileType, ['jpeg', 'jpg', 'png', 'gif'])) {
    header('Location: index.php?page=create-post&error=true');
    
       } else {
        $newFileName = uniqid('img_', true) . '.' . $imageFileType;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $newFileName)) {
            header('Location: index.php?page=create-post&error=upload');
            exit;
        }
    }

    }

    if (strlen($title) < 3 || strlen($content) < 10 || strlen($resume) < 3) {
        header('Location: index.php?page=create-post&error=true');
        exit;
    } else {
         $manager = new PostsManager();
        $manager->updatePost($id, $title, $content, $resume, $newFileName ?? null);
        header('Location: index.php?page=admin-posts');
        exit;
    }
 }

 public function createTag(){

      if(!isset($_SESSION['user_id'])) {
      header('location: index.php?page=login');
      exit;
   }
   $name = $_POST['name'];

   if(!$name)
      header('Location: index.php?page=admin-tags');
   else{
      $tagsmanager = new TagsManager();
      $tagsmanager->createTag($name);
      header('Location: index.php?page=admin-tags');
      }



 }
} 