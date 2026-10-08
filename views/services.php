<?php // En-tête commun à toutes les pages : <head>, menu, début du <main> ?>
<?php include 'header.php'; ?>

<?php /** @var Service[] $services */ // Pour VS Code : $services est un tableau d'objets Service, créé dans le contrôleur ?>

<?php // Titre principal de la page : un seul <h1> par page (référencement et accessibilité) ?>
<h1 class="text-3xl font-bold mb-2">Nos services</h1>
<p class="text-gray-600 mb-8">Découvrez les soins proposés par le Dr Dupont et son équipe.</p>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

  <?php // Boucle sur tous les services de la base : une carte par service ?>
  <?php foreach ($services as $service) { ?>

    <?php // <article> : balise sémantique pour un contenu autonome (ici, un service) ?>
    <article class="bg-white rounded-lg shadow overflow-hidden flex flex-col">

      <?php // L'image est facultative : on ne l'affiche que si le service en a une ?>
      <?php if ($service->getImage()) { ?>
        <?php // alt = nom du service, pour l'accessibilité ; loading="lazy" = chargée seulement quand elle devient visible ?>
        <img src="uploads/<?= htmlspecialchars($service->getImage()) ?>" alt="<?= htmlspecialchars($service->getName()) ?>" class="w-full h-48 object-cover" loading="lazy">
      <?php } ?>

      <div class="p-6 flex flex-col grow">

        <?php // Nom du service en <h2>, sous le <h1> de la page ; htmlspecialchars() protège contre les failles XSS ?>
        <h2 class="font-semibold text-xl mb-2"><?= htmlspecialchars($service->getName()) ?></h2>

        <?php // Description du service, protégée de la même façon ?>
        <p class="text-gray-600 mb-4 grow"><?= htmlspecialchars($service->getDescription()) ?></p>

        <?php // Durée en minutes : c'est un int, pas besoin de htmlspecialchars() ?>
        <p class="text-sm text-gray-500 mb-4">Durée : <?= $service->getDuration() ?> min</p>

        <?php // Appel à l'action : renvoie vers le formulaire de prise de rendez-vous ?>
        <a href="index.php?page=appointment" class="text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Prendre rendez-vous</a>
      </div>
    </article>

  <?php } ?>
  <?php // Fin de la boucle ?>

</div>

<?php // Pied de page commun : fin du <main>, footer, fermeture du <body> et du <html> ?>
<?php include 'footer.php'; ?>