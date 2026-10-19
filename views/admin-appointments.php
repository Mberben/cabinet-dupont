<?php include 'header.php'; ?>
<?php /** @var Appointment[] $appointments */ ?>

<h1 class="text-3xl font-bold mb-6">Gestion des rendez-vous</h1>

<?php
  $successMessages = [
      'confirmed' => 'Le rendez-vous a bien été confirmé.',
      'cancelled' => 'Le rendez-vous a bien été annulé.',
      'updated'   => 'Le rendez-vous a bien été modifié.',
  ];
  $success = $_GET['success'] ?? '';

  // Couleur de la pastille selon le statut
  $statusColors = [
      'pending'   => 'bg-yellow-100 text-yellow-800',
      'confirmed' => 'bg-green-100 text-green-800',
      'cancelled' => 'bg-gray-200 text-gray-600',
  ];
?>
<?php if (isset($successMessages[$success])) { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6" role="status"><?= $successMessages[$success] ?></p>
<?php } ?>

<?php if (empty($appointments)) { ?>
  <p class="text-gray-500">Aucun rendez-vous pour le moment.</p>
<?php } else { ?>
  <div class="overflow-x-auto">
    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-4">Date</th>
          <th class="p-4">Patient</th>
          <th class="p-4">Soin</th>
          <th class="p-4">Statut</th>
          <th class="p-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($appointments as $appointment) { ?>
          <?php // Les rendez-vous passés sont grisés ?>
          <tr class="border-t align-top <?= $appointment->isPast() ? 'opacity-60' : '' ?>">
            <td class="p-4 whitespace-nowrap"><?= $appointment->getFormattedDate() ?></td>
            <td class="p-4">
              <?= htmlspecialchars($appointment->getPatientName()) ?><br>
              <span class="text-sm text-gray-500"><?= htmlspecialchars($appointment->getPatientEmail()) ?></span>
              <?php if ($appointment->getPatientPhone()) { ?>
                <br><span class="text-sm text-gray-500"><?= htmlspecialchars($appointment->getPatientPhone()) ?></span>
              <?php } ?>
              <?php // Message du patient, s'il en a laissé un ?>
              <?php if ($appointment->getMessage()) { ?>
                <p class="text-sm italic text-gray-600 mt-1">« <?= htmlspecialchars($appointment->getMessage()) ?> »</p>
              <?php } ?>
            </td>
            <td class="p-4"><?= htmlspecialchars($appointment->getServiceName()) ?> (<?= $appointment->getServiceDuration() ?> min)</td>
            <td class="p-4">
              <span class="px-3 py-1 rounded-full text-sm <?= $statusColors[$appointment->getStatus()] ?? '' ?>">
                <?= $appointment->getStatusLabel() ?>
              </span>
            </td>
            <td class="p-4 space-y-1 whitespace-nowrap">
              <?php // Actions possibles seulement sur un rendez-vous à venir et non annulé ?>
              <?php if (!$appointment->isPast() && $appointment->getStatus() !== 'cancelled') { ?>
                <?php if ($appointment->getStatus() === 'pending') { ?>
                  <form action="index.php?page=confirm-appointment" method="POST">
                    <input type="hidden" name="id" value="<?= $appointment->getId() ?>">
                    <button class="text-green-700 hover:underline">Confirmer</button>
                  </form>
                <?php } ?>
                <a href="index.php?page=update-appointment&id=<?= $appointment->getId() ?>" class="block text-indigo-600 hover:underline">Modifier</a>
                <form action="index.php?page=cancel-appointment" method="POST" class="cancel-form">
                  <input type="hidden" name="id" value="<?= $appointment->getId() ?>">
                  <button class="text-red-600 hover:underline">Annuler</button>
                </form>
              <?php } else { ?>
                <span class="text-gray-400">—</span>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
<?php } ?>

<?php // Fenêtre de confirmation avec des libellés sans ambiguïté ?>
<dialog id="confirm-dialog" class="rounded-lg p-6 shadow-xl backdrop:bg-black/50">
  <p class="mb-2 text-lg">Annuler ce rendez-vous ?</p>
  <p class="mb-6 text-gray-600">Le créneau redeviendra disponible pour les autres patients.</p>
  <div class="flex justify-end gap-3">
    <button type="button" id="keep-btn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Garder le rendez-vous</button>
    <button type="button" id="confirm-btn" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">Oui, annuler</button>
  </div>
</dialog>

<script>
  const dialog = document.getElementById('confirm-dialog');
  let formToSubmit = null;

  // Au clic sur Annuler : on bloque l'envoi et on demande confirmation
  document.querySelectorAll('.cancel-form').forEach(form => {
    form.addEventListener('submit', event => {
      event.preventDefault();
      formToSubmit = form;
      dialog.showModal();
    });
  });

  document.getElementById('keep-btn').addEventListener('click', () => dialog.close());
  document.getElementById('confirm-btn').addEventListener('click', () => formToSubmit.submit());
</script>
<?php include 'footer.php'; ?>