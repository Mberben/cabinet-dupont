<?php include 'header.php'; ?>
<?php /** @var Service[] $services */ ?>
 
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
  <h1 class="text-3xl font-bold">Gestion des services</h1>
  <a href="index.php?page=create-service" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Ajouter un service</a>
</div>
 
<?php // Messages après une suppression (paramètres error / success dans l'URL) ?>
<?php if (($_GET['error'] ?? '') === 'used') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Ce service est lié à des rendez-vous : il ne peut pas être supprimé.</p>
<?php } ?>
<?php if (($_GET['success'] ?? '') === 'deleted') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">Le service a bien été supprimé.</p>
<?php } ?>
 
<?php // Tableau des services : nom, durée et actions ?>
<table class="w-full bg-white shadow rounded-lg">
  <thead class="bg-gray-100 text-left">
    <tr>
      <th class="p-4">Service</th>
      <th class="p-4">Durée</th>
      <th class="p-4">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($services as $service) { ?>
      <tr class="border-t">
        <td class="p-4"><?= htmlspecialchars($service->getName()) ?></td>
        <td class="p-4"><?= $service->getDuration() ?> min</td>
        <td class="p-4 space-x-2">
          <?php // L'id du service passe dans l'URL pour savoir lequel modifier ?>
          <a href="index.php?page=update-service&id=<?= $service->getId() ?>" class="text-indigo-600 hover:underline">Modifier</a>
 
          <?php // Suppression en POST (et non par un simple lien) ; la confirmation est gérée par la fenêtre plus bas ?>
          <form action="index.php?page=delete-service" method="POST" class="inline delete-form">
            <input type="hidden" name="id" value="<?= $service->getId() ?>">
            <button class="text-red-600 hover:underline">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>
 
<?php // Fenêtre de confirmation, partagée par tous les boutons Supprimer ?>
<dialog id="confirm-dialog" class="rounded-lg p-6 shadow-xl backdrop:bg-black/50">
  <p class="mb-6 text-lg">Supprimer ce service ?</p>
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
      formToSubmit = form;   // on retient quel service supprimer
      dialog.showModal();
    });
  });
 
  // Annuler : on ferme simplement la fenêtre
  document.getElementById('cancel-btn').addEventListener('click', () => dialog.close());
 
  // Confirmer : on envoie le formulaire retenu
  document.getElementById('confirm-btn').addEventListener('click', () => formToSubmit.submit());
</script>
 
<?php include 'footer.php'; ?>