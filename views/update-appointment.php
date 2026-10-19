<?php include 'header.php'; ?>
<?php /** @var Appointment $appointment */ ?>
<?php /** @var Service[] $services */ ?>

<h1 class="text-3xl font-bold mb-2">Modifier le rendez-vous</h1>
<p class="text-gray-600 mb-6">Patient : <strong><?= htmlspecialchars($appointment->getPatientName()) ?></strong></p>

<?php
  $errors = [
      'fields' => 'Merci de remplir tous les champs.',
      'past'   => 'La date choisie est déjà passée.',
      'closed' => 'Le cabinet est fermé à ce moment-là, ou le soin dépasse l\'heure de fermeture.',
      'taken'  => 'Ce créneau chevauche un autre rendez-vous.',
  ];
  $error = $_GET['error'] ?? '';
?>
<?php if (isset($errors[$error])) { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6" role="alert"><?= $errors[$error] ?></p>
<?php } ?>

<form action="index.php?page=update-appointment-valid&id=<?= $appointment->getId() ?>" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4 max-w-2xl">
  <div>
    <label for="service_id" class="block mb-1 font-medium">Type de consultation</label>
    <select id="service_id" name="service_id" required class="w-full border rounded-lg p-3">
      <?php foreach ($services as $service) { ?>
        <?php // selected : le service actuel est présélectionné ?>
        <option value="<?= $service->getId() ?>" <?= $service->getId() === $appointment->getServiceId() ? 'selected' : '' ?>>
          <?= htmlspecialchars($service->getName()) ?> (<?= $service->getDuration() ?> min)
        </option>
      <?php } ?>
    </select>
  </div>

  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label for="date" class="block mb-1 font-medium">Date</label>
      <?php // Pré-rempli : on découpe "2026-10-19 09:00:00" en date et heure ?>
      <input type="date" id="date" name="date" required min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d', strtotime($appointment->getAppointmentDate())) ?>" class="w-full border rounded-lg p-3">
    </div>
    <div>
      <label for="time" class="block mb-1 font-medium">Heure</label>
      <input type="time" id="time" name="time" required step="900" value="<?= date('H:i', strtotime($appointment->getAppointmentDate())) ?>" class="w-full border rounded-lg p-3">
    </div>
  </div>

  <div class="flex gap-3">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    <a href="index.php?page=admin-appointments" class="px-6 py-2 rounded-lg border hover:bg-gray-100">Retour</a>
  </div>
</form>

<?php include 'footer.php'; ?>