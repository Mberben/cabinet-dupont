<?php include 'header.php'; ?>
<?php /** @var User[] $patients */ ?>

<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
  <h1 class="text-3xl font-bold">Gestion des patients</h1>
  <a href="index.php?page=create-patient" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Ajouter un patient</a>
</div>

<?php // Messages après une action ?>
<?php if (($_GET['success'] ?? '') === 'created') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">Le patient a bien été ajouté.</p>
<?php } elseif (($_GET['success'] ?? '') === 'updated') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">Le patient a bien été modifié.</p>
<?php } elseif (($_GET['success'] ?? '') === 'deleted') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">Le patient a bien été supprimé.</p>
<?php } ?>

<div class="overflow-x-auto">
  <table class="w-full bg-white shadow rounded-lg">
    <thead class="bg-gray-100 text-left">
      <tr>
        <th class="p-4">Nom</th>
        <th class="p-4">Email</th>
        <th class="p-4">Téléphone</th>
        <th class="p-4">Compte</th>
        <th class="p-4">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($patients as $patient) { ?>
        <tr class="border-t">
          <?php // Nom de famille en premier : la liste est triée par nom ?>
          <td class="p-4"><?= htmlspecialchars($patient->getLastname() . ' ' . $patient->getFirstname()) ?></td>
          <td class="p-4"><?= htmlspecialchars($patient->getEmail()) ?></td>
          <td class="p-4"><?= htmlspecialchars($patient->getPhone() ?? '—') ?></td>
          <?php // hasAccount() : le patient a-t-il créé un mot de passe ? ?>
          <td class="p-4"><?= $patient->hasAccount() ? 'Oui' : 'Non' ?></td>
          <td class="p-4 space-x-2 whitespace-nowrap">
            <a href="index.php?page=update-patient&id=<?= $patient->getId() ?>" class="text-indigo-600 hover:underline">Modifier</a>
            <form action="index.php?page=delete-patient" method="POST" class="inline delete-form">
              <input type="hidden" name="id" value="<?= $patient->getId() ?>">
              <button class="text-red-600 hover:underline">Supprimer</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php // Fenêtre de confirmation : prévient que les rendez-vous seront aussi supprimés (CASCADE) ?>
<dialog id="confirm-dialog" class="rounded-lg p-6 shadow-xl backdrop:bg-black/50">
  <p class="mb-2 text-lg">Supprimer ce patient ?</p>
  <p class="mb-6 text-gray-600">Tous ses rendez-vous seront également supprimés.</p>
  <div class="flex justify-end gap-3">
    <button type="button" id="cancel-btn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Annuler</button>
    <button type="button" id="confirm-btn" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Supprimer</button>
  </div>
</dialog>

<script>
  const dialog = document.getElementById('confirm-dialog');
  let formToSubmit = null;

  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault();
      formToSubmit = form;
      dialog.showModal();
    });
  });

  document.getElementById('cancel-btn').addEventListener('click', () => dialog.close());
  document.getElementById('confirm-btn').addEventListener('click', () => formToSubmit.submit());
</script>

<?php include 'footer.php'; ?>