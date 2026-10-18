<?php include 'header.php'; ?>

<h1 class="text-3xl font-bold mb-6">Ajouter un patient</h1>

<?php if (($_GET['error'] ?? '') === 'fields') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Prénom, nom et un email valide sont obligatoires.</p>
<?php } elseif (($_GET['error'] ?? '') === 'email') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Cet email est déjà utilisé.</p>
<?php } ?>

<form action="index.php?page=create-patient-valid" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4 max-w-2xl">
  <div>
    <label for="firstname" class="block mb-1 font-medium">Prénom</label>
    <input type="text" id="firstname" name="firstname" required class="w-full border rounded-lg p-3">
  </div>
  <div>
    <label for="lastname" class="block mb-1 font-medium">Nom</label>
    <input type="text" id="lastname" name="lastname" required class="w-full border rounded-lg p-3">
  </div>
  <div>
    <label for="email" class="block mb-1 font-medium">Email</label>
    <input type="email" id="email" name="email" required class="w-full border rounded-lg p-3">
  </div>
  <div>
    <label for="phone" class="block mb-1 font-medium">Téléphone (facultatif)</label>
    <?php // type="tel" : clavier numérique sur mobile ?>
    <input type="tel" id="phone" name="phone" class="w-full border rounded-lg p-3">
  </div>

  <?php // Pas de mot de passe : le patient pourra s'inscrire lui-même avec cet email ?>
  <div class="flex gap-3">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Ajouter</button>
    <a href="index.php?page=admin-patients" class="px-6 py-2 rounded-lg border hover:bg-gray-100">Annuler</a>
  </div>
</form>

<?php include 'footer.php'; ?>