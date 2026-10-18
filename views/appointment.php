<?php include 'header.php'; ?>
<?php /** @var Service[] $services */ ?>
<?php /** @var ?User $currentUser */ ?>

<h1 class="text-3xl font-bold mb-2">Prendre rendez-vous</h1>
<p class="text-gray-600 mb-8">Choisissez votre soin, puis la date et l'heure. Votre demande sera confirmée par le cabinet.</p>

<?php // Messages selon le résultat ?>
<?php
  $errors = [
      'fields' => 'Merci de remplir tous les champs obligatoires avec un email valide.',
      'past'   => 'La date choisie est déjà passée.',
      'closed' => 'Le cabinet est fermé à ce moment-là, ou le soin dépasse l\'heure de fermeture. Consultez nos horaires sur la page d\'accueil.',
      'taken'  => 'Ce créneau est déjà réservé. Merci d\'en choisir un autre.',
      'email'  => 'Cet email ne peut pas être utilisé pour une prise de rendez-vous.',
  ];
  $error = $_GET['error'] ?? '';
?>
<?php if (isset($errors[$error])) { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6" role="alert"><?= $errors[$error] ?></p>
<?php } elseif (isset($_GET['success'])) { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6" role="status">Votre demande de rendez-vous a bien été envoyée. Le cabinet vous la confirmera rapidement.</p>
<?php } ?>

<form action="index.php?page=appointment-valid" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4 max-w-2xl">

  <div>
    <label for="service_id" class="block mb-1 font-medium">Type de consultation</label>
    <select id="service_id" name="service_id" required class="w-full border rounded-lg p-3">
      <option value="">— Choisir un soin —</option>
      <?php foreach ($services as $service) { ?>
        <option value="<?= $service->getId() ?>"><?= htmlspecialchars($service->getName()) ?> (<?= $service->getDuration() ?> min)</option>
      <?php } ?>
    </select>
  </div>

  <div class="grid sm:grid-cols-2 gap-4">
    <div>
      <label for="date" class="block mb-1 font-medium">Date</label>
      <?php // min = aujourd'hui : le calendrier grise les jours passés ?>
      <input type="date" id="date" name="date" required min="<?= date('Y-m-d') ?>" class="w-full border rounded-lg p-3">
    </div>
    <div>
      <label for="time" class="block mb-1 font-medium">Heure</label>
      <?php // step="900" : créneaux de 15 minutes (900 secondes) ?>
      <input type="time" id="time" name="time" required step="900" class="w-full border rounded-lg p-3">
    </div>
  </div>

  <?php // Les coordonnées ne sont demandées qu'aux visiteurs non connectés ?>
  <?php if ($currentUser) { ?>
    <p class="text-gray-600">Rendez-vous au nom de <strong><?= htmlspecialchars($currentUser->getFullName()) ?></strong>.</p>
  <?php } else { ?>
    <div class="grid sm:grid-cols-2 gap-4">
      <div>
        <label for="firstname" class="block mb-1 font-medium">Prénom</label>
        <input type="text" id="firstname" name="firstname" required autocomplete="given-name" class="w-full border rounded-lg p-3">
      </div>
      <div>
        <label for="lastname" class="block mb-1 font-medium">Nom</label>
        <input type="text" id="lastname" name="lastname" required autocomplete="family-name" class="w-full border rounded-lg p-3">
      </div>
    </div>
    <div class="grid sm:grid-cols-2 gap-4">
      <div>
        <label for="email" class="block mb-1 font-medium">Email</label>
        <input type="email" id="email" name="email" required autocomplete="email" class="w-full border rounded-lg p-3">
      </div>
      <div>
        <label for="phone" class="block mb-1 font-medium">Téléphone (facultatif)</label>
        <input type="tel" id="phone" name="phone" autocomplete="tel" class="w-full border rounded-lg p-3">
      </div>
    </div>
  <?php } ?>

  <div>
    <label for="message" class="block mb-1 font-medium">Message (facultatif)</label>
    <textarea id="message" name="message" rows="4" placeholder="Ex. : douleur à une molaire" class="w-full border rounded-lg p-3"></textarea>
  </div>

  <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Envoyer ma demande</button>
</form>

<?php include 'footer.php'; ?>