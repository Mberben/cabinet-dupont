<?php include 'header.php'; ?>
<?php /** @var Service[] $services */ ?>

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
  <?php // Grille responsive : 1 colonne sur mobile, 2 sur tablette (sm), 3 sur ordinateur (lg) ?>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <?php // Boucle sur les services envoyés par le contrôleur : une carte par service ?>
    <?php foreach ($services as $service) { ?>

    <article class="bg-white p-6 rounded-lg shadow">
      <?php // Nom du service, protégé contre les failles XSS avec htmlspecialchars() ?>
      <h3 class="font-semibold text-lg mb-2"><?= htmlspecialchars($service->getName()) ?></h3>

      <?php // Description du service, protégée de la même façon ?>
      <p class="text-gray-600"><?= htmlspecialchars($service->getDescription()) ?></p>
    </article>

    <?php } ?>
    <?php // Fin de la boucle ?>

  </div>
  <a href="index.php?page=services" class="inline-block mt-6 text-indigo-600 hover:underline">Voir tous nos services</a>
</section>

<section>
  <h2 class="text-2xl font-bold mb-6">Horaires d'ouverture</h2>
  <ul class="bg-white p-6 rounded-lg shadow max-w-md space-y-2">
    <li class="flex justify-between"><span>Lundi – Mardi</span><span>8h-12h / 14h-18h</span></li>
    <li class="flex justify-between"><span>Mercredi</span><span>8h-12h</span></li>
    <li class="flex justify-between"><span>Jeudi – Vendredi</span><span>8h-12h / 14h-18h</span></li>
    <li class="flex justify-between"><span>Samedi – Dimanche</span><span>Fermé</span></li>
  </ul>
</section>

<?php include 'footer.php'; ?>