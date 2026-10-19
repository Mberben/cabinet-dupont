<?php include 'header.php'; ?>

<?php
  // L'équipe du cabinet : un tableau facile à modifier, affiché par une boucle
  $team = [
      ['name' => 'Claire Muller',  'role' => 'Assistante dentaire', 'text' => 'Elle accompagne le Dr Dupont pendant les soins et veille au confort des patients.'],
      ['name' => 'Thomas Weber',   'role' => 'Assistant dentaire',  'text' => 'Il prépare les salles de soins et s\'occupe de la stérilisation du matériel.'],
      ['name' => 'Sophie Klein',   'role' => 'Secrétaire médicale', 'text' => 'Elle vous accueille, gère les rendez-vous et répond à vos questions.'],
  ];
?>

<?php // Présentation du Dr Dupont ?>
<section class="grid md:grid-cols-3 gap-10 items-center mb-16">
  <?php // Avatar avec les initiales, faute de photo ; aria-hidden car purement décoratif ?>
  <div class="flex justify-center">
    <div class="w-48 h-48 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-5xl font-bold" aria-hidden="true">JD</div>
  </div>

  <div class="md:col-span-2">
    <h1 class="text-4xl font-bold mb-2">Dr Jean Dupont</h1>
    <p class="text-indigo-600 font-medium mb-4">Chirurgien-dentiste</p>
    <p class="text-gray-600 leading-relaxed mb-4">
      Installé depuis plus de quinze ans, le Dr Dupont accueille petits et grands dans un cabinet moderne,
      pensé pour le confort et la sécurité de chacun. Sa priorité : prendre le temps d'expliquer chaque soin
      et proposer un traitement adapté à chaque patient.
    </p>
    <a href="index.php?page=appointment" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Prendre rendez-vous</a>
  </div>
</section>

<?php // Parcours et qualifications ?>
<section class="grid md:grid-cols-2 gap-6 mb-16">
  <div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Parcours</h2>
    <ul class="space-y-3 text-gray-600">
      <li><strong class="text-gray-800">2008</strong> : Doctorat en chirurgie dentaire, Université de Strasbourg</li>
      <li><strong class="text-gray-800">2008 – 2011</strong> : Exercice en cabinet de groupe à Strasbourg</li>
      <li><strong class="text-gray-800">2011</strong> : Ouverture du cabinet</li>
      <li><strong class="text-gray-800">2020</strong> : Rénovation et modernisation complète des salles de soins</li>
    </ul>
  </div>

  <div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Qualifications</h2>
    <ul class="space-y-3 text-gray-600 list-disc list-inside">
      <li>Diplôme universitaire d'implantologie orale</li>
      <li>Formation en orthodontie de l'adulte et de l'enfant</li>
      <li>Formation continue en radiologie 3D</li>
      <li>Inscrit à l'Ordre national des chirurgiens-dentistes</li>
    </ul>
  </div>
</section>

<?php // L'équipe ?>
<section>
  <h2 class="text-2xl font-bold mb-6">L'équipe</h2>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($team as $member) { ?>
      <article class="bg-white p-6 rounded-lg shadow text-center">
        <?php
          // Initiales : première lettre du prénom et du nom
          $parts = explode(' ', $member['name']);
          $initials = mb_substr($parts[0], 0, 1) . mb_substr($parts[1] ?? '', 0, 1);
        ?>
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold" aria-hidden="true"><?= $initials ?></div>
        <h3 class="font-semibold text-lg"><?= $member['name'] ?></h3>
        <p class="text-indigo-600 text-sm mb-2"><?= $member['role'] ?></p>
        <p class="text-gray-600"><?= $member['text'] ?></p>
      </article>
    <?php } ?>
  </div>
</section>

<?php include 'footer.php'; ?>