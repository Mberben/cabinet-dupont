<?php include 'header.php'; ?>

<h1 class="text-3xl font-bold mb-2">Tableau de bord</h1>
<p class="text-gray-600 mb-8">Bonjour <?= htmlspecialchars($_SESSION['user_name']) ?>, que souhaitez-vous gérer ?</p>

<?php // Une carte par gestion du back office (cahier des charges) ?>
<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <a href="index.php?page=admin-appointments" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition">
    Rendez-vous
  </a>
  <a href="index.php?page=admin-patients" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition">
    Patients
  </a>
  <a href="index.php?page=admin-services" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition">
    Services
  </a>
  <?php // Pointe encore vers la gestion des articles du blog, en attendant sa transformation en actualités ?>
  <a href="index.php?page=admin-posts" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition">
    Actualités
  </a>
  <a href="index.php?page=admin-hours" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-600 text-white shadow-lg hover:bg-indigo-700 transition">
    Horaires
  </a>
</div>

<?php include 'footer.php'; ?>