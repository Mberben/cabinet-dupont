<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Cabinet dentaire du Dr Dupont : soins courants, orthodontie, implantologie. Prenez rendez-vous en ligne.">
  <title>Cabinet du Dr Dupont – Dentiste</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
  <header class="bg-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="index.php" class="text-2xl font-bold text-indigo-600">Cabinet du Dr Dupont</a>
      <nav class="flex flex-wrap items-center gap-x-6 gap-y-2">
        <a href="index.php" class="text-gray-700 hover:text-indigo-600">Accueil</a>
        <a href="index.php?page=services" class="text-gray-700 hover:text-indigo-600">Services</a>
        <a href="index.php?page=about" class="text-gray-700 hover:text-indigo-600">À propos</a>
        <a href="index.php?page=news" class="text-gray-700 hover:text-indigo-600">Actualités</a>
        <?php if (!isset($_SESSION['user_id'])) { ?>
          <a href="index.php?page=login" class="text-gray-700 hover:text-indigo-600">Connexion</a>
        <?php } else { ?>
          <?php // Lien Admin réservé au personnel 
          ?>
          <?php if (Auth::isStaff()) { ?>
            <a href="index.php?page=admin" class="text-gray-700 hover:text-indigo-600">Admin</a>
          <?php } ?>
          <a href="index.php?page=logout" class="text-gray-700 hover:text-indigo-600">Déconnexion</a>
        <?php } ?>
        <a href="index.php?page=appointment" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Prendre rendez-vous</a>
      </nav>
    </div>
  </header>
  <main class="max-w-6xl mx-auto px-4 py-10 grow">