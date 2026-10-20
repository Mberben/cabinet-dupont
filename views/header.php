<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <?php // Indispensable pour le responsive : sans elle, le mobile affiche la version ordinateur en miniature ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php // Texte affiché sous le titre dans les résultats Google (référencement) ?>
  <meta name="description" content="Cabinet dentaire du Dr Dupont : soins courants, orthodontie, implantologie. Prenez rendez-vous en ligne.">
  <title>Cabinet du Dr Dupont – Dentiste</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

<?php
  // Page actuelle, pour mettre le lien correspondant en évidence
  $currentPage = $_GET['page'] ?? 'home';

  // Liens publics du menu : [page => libellé]
  $navLinks = [
      'home'     => 'Accueil',
      'services' => 'Services',
      'about'    => 'À propos',
      'news'     => 'Actualités',
  ];
?>

<?php // Lien d'évitement : permet aux utilisateurs clavier d'aller directement au contenu ?>
<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 bg-indigo-600 text-white px-4 py-2 rounded-lg z-50">Aller au contenu</a>

<header class="bg-white shadow-md relative">
  <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
    <a href="index.php" class="text-xl sm:text-2xl font-bold text-indigo-600">Cabinet du Dr Dupont</a>

    <?php // Bouton burger : visible seulement sur mobile ; aria-expanded indique si le menu est ouvert ?>
    <button id="menu-toggle" class="md:hidden p-2 rounded-lg border" aria-controls="main-nav" aria-expanded="false" aria-label="Ouvrir le menu">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <nav id="main-nav" aria-label="Navigation principale" class="hidden md:flex absolute md:static top-full left-0 right-0 bg-white md:bg-transparent shadow-md md:shadow-none flex-col md:flex-row items-start md:items-center gap-4 md:gap-6 p-4 md:p-0 z-40">
      <?php foreach ($navLinks as $page => $label) { ?>
        <?php // aria-current="page" signale le lien de la page actuelle ?>
        <a href="index.php?page=<?= $page ?>"
           class="hover:text-indigo-600 <?= $currentPage === $page ? 'text-indigo-600 font-semibold' : 'text-gray-700' ?>"
           <?= $currentPage === $page ? 'aria-current="page"' : '' ?>>
          <?= $label ?>
        </a>
      <?php } ?>

      <?php if (!Auth::isLoggedIn()) { ?>
        <a href="index.php?page=login" class="text-gray-700 hover:text-indigo-600">Connexion</a>
      <?php } else { ?>
        <?php // Personnel : accès au back office / Patient : ses rendez-vous ?>
        <?php if (Auth::isStaff()) { ?>
          <a href="index.php?page=admin" class="text-gray-700 hover:text-indigo-600">Admin</a>
        <?php } else { ?>
          <a href="index.php?page=my-appointments" class="hover:text-indigo-600 <?= $currentPage === 'my-appointments' ? 'text-indigo-600 font-semibold' : 'text-gray-700' ?>">Mes rendez-vous</a>
        <?php } ?>
        <a href="index.php?page=logout" class="text-gray-700 hover:text-indigo-600">Déconnexion</a>
      <?php } ?>

      <a href="index.php?page=appointment" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Prendre rendez-vous</a>
    </nav>
  </div>
</header>

<?php // Ouverture / fermeture du menu sur mobile ?>
<script>
  const menuToggle = document.getElementById('menu-toggle');
  const mainNav = document.getElementById('main-nav');

  menuToggle.addEventListener('click', () => {
    const isOpen = mainNav.classList.toggle('hidden') === false;   // false = la classe a été retirée, donc le menu est ouvert
    mainNav.classList.toggle('flex', isOpen);
    menuToggle.setAttribute('aria-expanded', isOpen);
    menuToggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
  });
</script>

<main id="main-content" class="max-w-6xl mx-auto px-4 py-10 grow">