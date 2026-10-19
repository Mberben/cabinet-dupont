<?php
// Chargement des managers utilisés par les pages publiques
require_once __DIR__ . '/../models/ServicesManager.php';
require_once __DIR__ . '/../models/NewsManager.php';
require_once __DIR__ . '/../models/OpeningHoursManager.php';

// Contrôleur des pages publiques (front office)
class PostsController {

    // Page d'accueil : aperçu des services et horaires d'ouverture
    public function home()
    {
        // Récupère les services et garde les 3 premiers pour l'aperçu de l'accueil
        $servicesManager = new ServicesManager();
        $services = array_slice($servicesManager->getServices(), 0, 3);

        // Horaires regroupés par jour
        $hoursManager = new OpeningHoursManager();
        $hoursByDay = $hoursManager->getHoursByDay();

        // Toujours en dernier : on affiche la vue une fois toutes les données prêtes
        require __DIR__ . '/../views/home.php';
    }

    // Page Services : tous les services du cabinet
    public function services()
    {
        $servicesManager = new ServicesManager();
        $services = $servicesManager->getServices();

        require __DIR__ . '/../views/services.php';
    }

        // Page À propos : présentation du Dr Dupont et de son équipe (contenu fixe)
    public function about()
    {
        require __DIR__ . '/../views/about.php';
    }

    // Page Actualités : liste de toutes les actualités
    public function newsList()
    {
        $newsManager = new NewsManager();
        $newsList = $newsManager->getAllNews();

        require __DIR__ . '/../views/news.php';
    }

    // Page d'une actualité seule
    public function newsDetail()
    {
        $newsManager = new NewsManager();
        $news = $newsManager->getNews((int) ($_GET['id'] ?? 0));

        // Actualité introuvable : retour à la liste
        if (!$news) {
            header('Location: index.php?page=news');
            exit;
        }

        require __DIR__ . '/../views/news-detail.php';
    }
}