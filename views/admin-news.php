<?php include 'header.php'; ?>
<?php /** @var News[] $newsList */ ?>

<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
  <h1 class="text-3xl font-bold">Gestion des actualités</h1>
  <a href="index.php?page=create-news" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Ajouter une actualité</a>
</div>

<?php // Messages après une action (paramètre success dans l'URL) ?>
<?php if (($_GET['success'] ?? '') === 'created') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">L'actualité a bien été ajoutée.</p>
<?php } elseif (($_GET['success'] ?? '') === 'updated') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">L'actualité a bien été modifiée.</p>
<?php } elseif (($_GET['success'] ?? '') === 'deleted') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">L'actualité a bien été supprimée.</p>
<?php } ?>

<?php // Tableau des actualités : titre, date, auteur et actions ?>
<table class="w-full bg-white shadow rounded-lg">
  <thead class="bg-gray-100 text-left">
    <tr>
      <th class="p-4">Titre</th>
      <th class="p-4">Date</th>
      <th class="p-4">Auteur</th>
      <th class="p-4">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($newsList as $news) { ?>
      <tr class="border-t">
        <td class="p-4"><?= htmlspecialchars($news->getTitle()) ?></td>
        <?php // Date de publication au format français ?>
        <td class="p-4"><?= date('d/m/Y', strtotime($news->getPublishedAt())) ?></td>
        <td class="p-4"><?= htmlspecialchars($news->getAuthorName() ?? '') ?></td>
        <td class="p-4 space-x-2">
          <?php // L'id de l'actualité passe dans l'URL pour savoir laquelle modifier ?>
          <a href="index.php?page=update-news&id=<?= $news->getId() ?>" class="text-indigo-600 hover:underline">Modifier</a>

          <?php // Suppression en POST ; la confirmation est gérée par la fenêtre plus bas ?>
          <form action="index.php?page=delete-news" method="POST" class="inline delete-form">
            <input type="hidden" name="id" value="<?= $news->getId() ?>">
            <button class="text-red-600 hover:underline">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<?php // Fenêtre de confirmation, partagée par tous les boutons Supprimer ?>
<dialog id="confirm-dialog" class="rounded-lg p-6 shadow-xl backdrop:bg-black/50">
  <p class="mb-6 text-lg">Supprimer cette actualité ?</p>
  <div class="flex justify-end gap-3">
    <button type="button" id="cancel-btn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Annuler</button>
    <button type="button" id="confirm-btn" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Supprimer</button>
  </div>
</dialog>

<script>
  const dialog = document.getElementById('confirm-dialog');
  let formToSubmit = null;

  // Au clic sur un bouton Supprimer : on bloque l'envoi et on ouvre la fenêtre
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault();
      formToSubmit = form;   // on retient quelle actualité supprimer
      dialog.showModal();
    });
  });

  // Annuler : on ferme simplement la fenêtre
  document.getElementById('cancel-btn').addEventListener('click', () => dialog.close());

  // Confirmer : on envoie le formulaire retenu
  document.getElementById('confirm-btn').addEventListener('click', () => formToSubmit.submit());
</script>

<?php include 'footer.php'; ?>