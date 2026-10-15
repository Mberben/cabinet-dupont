<?php include 'header.php'; ?>
<?php /** @var Service[] $services */ ?>
<?php /** @var array $hoursByDay */ ?>

<section class="grid md:grid-cols-2 gap-10 items-center mb-16">
  <div>
    <h1 class="text-4xl font-bold mb-4">Cabinet dentaire du Dr Dupont</h1>
    <p class="text-lg text-gray-600 mb-6">
      Le Dr Dupont et son équipe vous accueillent pour tous vos soins dentaires,
      dans un cabinet moderne et chaleureux.
    </p>
    <a href="index.php?page=appointment" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
      Prendre rendez-vous
    </a>
  </div>
  <img src="img/cabinet-dentaire.jpg" alt="Salle de soins du cabinet du Dr Dupont" class="rounded-lg shadow w-full" loading="lazy">
</section>

<section class="mb-16">
  <h2 class="text-2xl font-bold mb-6">Nos services</h2>
  <?php // Grille responsive : 1 colonne sur mobile, 2 sur tablette (sm), 3 sur ordinateur (lg) 
  ?>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <?php // Boucle sur les services envoyés par le contrôleur : une carte par service 
    ?>
    <?php foreach ($services as $service) { ?>

      <article class="bg-white p-6 rounded-lg shadow">
        <?php // Nom du service, protégé contre les failles XSS avec htmlspecialchars() 
        ?>
        <h3 class="font-semibold text-lg mb-2"><?= htmlspecialchars($service->getName()) ?></h3>

        <?php // Description du service, protégée de la même façon 
        ?>
        <p class="text-gray-600"><?= htmlspecialchars($service->getDescription()) ?></p>
      </article>

    <?php } ?>
    <?php // Fin de la boucle 
    ?>

  </div>
  <a href="index.php?page=services" class="inline-block mt-6 text-indigo-600 hover:underline">Voir tous nos services</a>
</section>

<section>
  <h2 class="text-2xl font-bold mb-6">Horaires d'ouverture</h2>
  <ul class="bg-white p-6 rounded-lg shadow max-w-md space-y-2">
    <?php // On parcourt les 7 jours, même ceux qui n'ont aucune plage en base 
    ?>
    <?php foreach (OpeningHour::DAYS as $dayNumber => $dayName) { ?>
      <?php
      $slots = $hoursByDay[$dayNumber] ?? [];
      // Fermé si aucune plage, ou si la plage est marquée is_closed
      $closed = empty($slots) || $slots[0]->isClosed();
      // date('N') = numéro du jour actuel : on met aujourd'hui en évidence
      $isToday = (int) date('N') === $dayNumber;
      ?>
      <li class="flex justify-between gap-4 <?= $isToday ? 'font-semibold text-indigo-600' : '' ?>">
        <span><?= $dayName ?><?= $isToday ? " (aujourd'hui)" : '' ?></span>
        <span>
          <?php if ($closed) { ?>
            Fermé
          <?php } else { ?>
            <?php // Toutes les plages du jour, séparées par " / " 
            ?>
            <?= implode(' / ', array_map(fn($slot) => $slot->getLabel(), $slots)) ?>
          <?php } ?>
        </span>
      </li>
    <?php } ?>
  </ul>
</section>

<?php include 'footer.php'; ?>