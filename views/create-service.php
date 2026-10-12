<?php include 'header.php'; ?>

<h1 class="text-3xl font-bold mb-6">Ajouter un service</h1>

<?php // Messages d'erreur selon le paramètre error dans l'URL ?>
<?php if (($_GET['error'] ?? '') === 'fields') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Nom (3 caractères min.), description (10 caractères min.) et durée sont obligatoires.</p>
<?php } elseif (($_GET['error'] ?? '') === 'image') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Image invalide : formats jpg, png, gif ou webp, 2 Mo maximum.</p>
<?php } elseif (($_GET['error'] ?? '') === 'upload') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">L'image n'a pas pu être enregistrée.</p>
<?php } ?>

<?php // enctype="multipart/form-data" est indispensable pour envoyer un fichier ?>
<form action="index.php?page=create-service-valid" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4 max-w-2xl">
  <div>
    <label for="name" class="block mb-1 font-medium">Nom du service</label>
    <input type="text" id="name" name="name" required minlength="3" class="w-full border rounded-lg p-3">
  </div>

  <div>
    <label for="description" class="block mb-1 font-medium">Description</label>
    <textarea id="description" name="description" required minlength="10" rows="5" class="w-full border rounded-lg p-3"></textarea>
  </div>

  <div>
    <label for="duration" class="block mb-1 font-medium">Durée (en minutes)</label>
    <?php // type="number" + min : le navigateur n'accepte qu'un nombre positif ?>
    <input type="number" id="duration" name="duration" required min="5" step="5" class="w-full border rounded-lg p-3">
  </div>

  <div>
    <label for="image" class="block mb-1 font-medium">Image (facultative)</label>
    <?php // accept : le sélecteur de fichiers ne propose que des images ?>
    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif, image/webp" class="w-full">
  </div>

  <div class="flex gap-3">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Ajouter</button>
    <a href="index.php?page=admin-services" class="px-6 py-2 rounded-lg border hover:bg-gray-100">Annuler</a>
  </div>
</form>

<?php include 'footer.php'; ?>