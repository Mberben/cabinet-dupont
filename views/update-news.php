<?php include 'header.php'; ?>
<?php /** @var News $news */ ?>

<h1 class="text-3xl font-bold mb-6">Modifier l'actualité</h1>

<?php // Messages d'erreur selon le paramètre error dans l'URL ?>
<?php if (($_GET['error'] ?? '') === 'fields') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Titre (3 caractères min.) et contenu (10 caractères min.) sont obligatoires.</p>
<?php } elseif (($_GET['error'] ?? '') === 'image') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Image invalide : formats jpg, png, gif ou webp, 2 Mo maximum.</p>
<?php } elseif (($_GET['error'] ?? '') === 'upload') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">L'image n'a pas pu être enregistrée.</p>
<?php } ?>

<?php // L'id passe dans l'URL de l'action pour savoir quelle actualité modifier ?>
<form action="index.php?page=update-news-valid&id=<?= $news->getId() ?>" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-4 max-w-2xl">
  <div>
    <label for="title" class="block mb-1 font-medium">Titre</label>
    <?php // value = valeur actuelle : le formulaire est pré-rempli ?>
    <input type="text" id="title" name="title" required minlength="3" value="<?= htmlspecialchars($news->getTitle()) ?>" class="w-full border rounded-lg p-3">
  </div>

  <div>
    <label for="content" class="block mb-1 font-medium">Contenu</label>
    <?php // Pour un textarea, la valeur se met entre les balises, pas dans value ?>
    <textarea id="content" name="content" required minlength="10" rows="10" class="w-full border rounded-lg p-3"><?= htmlspecialchars($news->getContent()) ?></textarea>
  </div>

  <?php // Aperçu de l'image actuelle, si l'actualité en a une ?>
  <?php if ($news->getImage()) { ?>
    <div>
      <p class="mb-1 font-medium">Image actuelle</p>
      <img src="uploads/<?= htmlspecialchars($news->getImage()) ?>" alt="<?= htmlspecialchars($news->getTitle()) ?>" class="w-48 rounded-lg">
    </div>
  <?php } ?>

  <div>
    <label for="image" class="block mb-1 font-medium">Nouvelle image (laisser vide pour garder l'actuelle)</label>
    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif, image/webp" class="w-full">
  </div>

  <div class="flex gap-3">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    <a href="index.php?page=admin-news" class="px-6 py-2 rounded-lg border hover:bg-gray-100">Annuler</a>
  </div>
</form>

<?php include 'footer.php'; ?>