<?php include 'header.php'; ?>

<h1 class="text-3xl font-bold mb-6">Ajouter une actualité</h1>

<?php // Messages d'erreur selon le paramètre error dans l'URL ?>
<?php if (($_GET['error'] ?? '') === 'fields') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Titre (3 caractères min.) et contenu (10 caractères min.) sont obligatoires.</p>
<?php } elseif (($_GET['error'] ?? '') === 'image') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Image invalide : formats jpg, png, gif ou webp, 2 Mo maximum.</p>
<?php } elseif (($_GET['error'] ?? '') === 'upload') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">L'image n'a pas pu être enregistrée.</p>
<?php } ?>

<?php // enctype="multipart/form-data" est indispensable pour envoyer un fichier ?>
<form action="index.php?page=create-news-valid" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4 max-w-2xl">
  <div>
    <label for="title" class="block mb-1 font-medium">Titre</label>
    <input type="text" id="title" name="title" required minlength="3" class="w-full border rounded-lg p-3">
  </div>

  <div>
    <label for="content" class="block mb-1 font-medium">Contenu</label>
    <textarea id="content" name="content" required minlength="10" rows="10" class="w-full border rounded-lg p-3"></textarea>
  </div>

  <div>
    <label for="image" class="block mb-1 font-medium">Image (facultative)</label>
    <?php // accept : le sélecteur de fichiers ne propose que des images ?>
    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif, image/webp" class="w-full">
  </div>

  <?php // Pas de champ auteur ni date : l'auteur vient de la session, la date est remplie par la base ?>
  <div class="flex gap-3">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Publier</button>
    <a href="index.php?page=admin-news" class="px-6 py-2 rounded-lg border hover:bg-gray-100">Annuler</a>
  </div>
</form>

<?php include 'footer.php'; ?>