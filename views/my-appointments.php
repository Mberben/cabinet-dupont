<?php include 'header.php'; ?>
<?php /** @var Appointment[] $appointments */ ?>

<?php
  // Couleur de la pastille selon le statut
  $statusColors = [
      'pending'   => 'bg-yellow-100 text-yellow-800',
      'confirmed' => 'bg-green-100 text-green-800',
      'cancelled' => 'bg-gray-200 text-gray-600',
  ];
?>

<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
  <h1 class="text-3xl font-bold">Mes rendez-vous</h1>
  <a href="index.php?page=appointment" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Prendre un rendez-vous</a>
</div>

<?php if (empty($appointments)) { ?>
  <p class="text-gray-500">Vous n'avez aucun rendez-vous pour le moment.</p>
<?php } else { ?>
  <div class="space-y-4 max-w-3xl">
    <?php foreach ($appointments as $appointment) { ?>
      <?php // Les rendez-vous passés sont atténués ?>
      <article class="bg-white p-6 rounded-lg shadow flex flex-wrap justify-between items-center gap-4 <?= $appointment->isPast() ? 'opacity-60' : '' ?>">
        <div>
          <p class="font-semibold text-lg"><?= $appointment->getFormattedDate() ?></p>
          <p class="text-gray-600"><?= htmlspecialchars($appointment->getServiceName()) ?> (<?= $appointment->getServiceDuration() ?> min)</p>
          <?php if ($appointment->getMessage()) { ?>
            <p class="text-sm italic text-gray-500 mt-1">« <?= htmlspecialchars($appointment->getMessage()) ?> »</p>
          <?php } ?>
        </div>
        <span class="px-3 py-1 rounded-full text-sm <?= $statusColors[$appointment->getStatus()] ?? '' ?>">
          <?= $appointment->getStatusLabel() ?>
        </span>
      </article>
    <?php } ?>
  </div>

  <p class="text-gray-500 mt-6">Pour modifier ou annuler un rendez-vous, merci de contacter le cabinet.</p>
<?php } ?>

<?php include 'footer.php'; ?>