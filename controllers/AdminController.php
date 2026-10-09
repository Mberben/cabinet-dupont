<?php
require_once __DIR__ . '/../models/PostsManager.php';
require_once __DIR__ . '/../models/TagsManager.php';

class AdminController {

    // Vérifie que l'utilisateur est connecté ET fait partie du personnel (admin ou staff)
    // Sinon : redirection vers la connexion
    private function requireStaff(): void
    {
        $role = $_SESSION['user_role'] ?? null;

        if (!isset($_SESSION['user_id']) || !in_array($role, ['admin', 'staff'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function admin() {
        $this->requireStaff();
        require 'views/admin.php';
    }

    public function adminPosts() {
        $this->requireStaff();
        $manager = new PostsManager();
        $articles = $manager->getPosts();
        require 'views/admin-posts.php';
    }

    public function adminTags() {
        $this->requireStaff();
        $tagsmanager = new TagsManager();
        $tags = $tagsmanager->getTags();
        require 'views/admin-tags.php';
    }

    public function adminCreatePost() {
        $this->requireStaff();
        $tagsmanager = new TagsManager();
        $tags = $tagsmanager->getTags();
        require 'views/create-article.php';
    }

    public function adminCreatePostValid()
    {
        $this->requireStaff();

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
        $this->requireStaff();

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=admin-posts');
            exit;
        }

        $manager = new PostsManager();
        $manager->deletePost((int) $_GET['id']);
        header('Location: index.php?page=admin-posts');
        exit;
    }

    public function updatePost() {
        $this->requireStaff();

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
        $this->requireStaff();

        if (!isset($_GET['id'])) {
            header('Location: index.php?page=admin-posts');
            exit;
        }

        $id      = (int) $_GET['id'];
        $resume  = trim($_POST['description'] ?? '');
        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if (strlen($title) < 3 || strlen($content) < 10 || strlen($resume) < 3) {
            header('Location: index.php?page=update-post&id=' . $id . '&error=true');
            exit;
        }

        $newFileName = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!getimagesize($_FILES['image']['tmp_name'])
                || $_FILES['image']['size'] > 2 * 1024 * 1024
                || !in_array($imageFileType, ['jpeg', 'jpg', 'png', 'gif'])) {
                header('Location: index.php?page=update-post&id=' . $id . '&error=true');
                exit;
            }

            $newFileName = uniqid('img_', true) . '.' . $imageFileType;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/' . $newFileName)) {
                header('Location: index.php?page=update-post&id=' . $id . '&error=upload');
                exit;
            }
        }

        $manager = new PostsManager();
        $manager->updatePost($id, $title, $content, $resume, $newFileName);
        header('Location: index.php?page=admin-posts');
        exit;
    }

    public function createTag() {
        $this->requireStaff();

        $name = trim($_POST['name'] ?? '');

        if ($name) {
            $tagsmanager = new TagsManager();
            $tagsmanager->createTag($name);
        }

        header('Location: index.php?page=admin-tags');
        exit;
    }
}