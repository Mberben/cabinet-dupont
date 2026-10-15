<?php include 'header.php'; ?>
<?php /** @var OpeningHour[] $hours */ ?>

<h1 class="text-3xl font-bold mb-6">Gestion des horaires</h1>

<?php // Messages après une action ?>
<?php if (($_GET['error'] ?? '') === 'times') { ?>
  <p class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">Pour un jour ouvert, indiquez les deux heures, avec l'ouverture avant la fermeture.</p>
<?php } elseif (($_GET['success'] ?? '') === 'updated') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">Les horaires ont bien été enregistrés.</p>
<?php } elseif (($_GET['success'] ?? '') === 'created') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">La plage a bien été ajoutée.</p>
<?php } elseif (($_GET['success'] ?? '') === 'deleted') { ?>
  <p class="bg-green-100 text-green-700 p-4 rounded-lg mb-6">La plage a bien été supprimée.</p>
<?php } ?>

<?php // Un seul formulaire pour toutes les plages : name="hours[id][champ]" crée un tableau dans $_POST ?>
<form action="index.php?page=update-hours-valid" method="POST" class="bg-white p-6 rounded-lg shadow mb-10 overflow-x-auto">
  <table class="w-full text-left">
    <thead>
      <tr class="border-b">
        <th class="p-3">Jour</th>
        <th class="p-3">Ouverture</th>
        <th class="p-3">Fermeture</th>
        <th class="p-3">Fermé</th>
        <th class="p-3"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($hours as $hour) { ?>
        <?php $id = $hour->getId(); ?>
        <tr class="border-b">
          <td class="p-3 font-medium"><?= $hour->getDayName() ?></td>
          <td class="p-3">
            <?php // aria-label : le lecteur d'écran sait de quel jour il s'agit ; substr garde "08:00" de "08:00:00" ?>
            <input type="time" name="hours[<?= $id ?>][open_time]" value="<?= substr($hour->getOpenTime() ?? '', 0, 5) ?>" aria-label="Ouverture <?= $hour->getDayName() ?>" class="border rounded-lg p-2">
          </td>
          <td class="p-3">
            <input type="time" name="hours[<?= $id ?>][close_time]" value="<?= substr($hour->getCloseTime() ?? '', 0, 5) ?>" aria-label="Fermeture <?= $hour->getDayName() ?>" class="border rounded-lg p-2">
          </td>
          <td class="p-3">
            <?php // Une case décochée n'est pas envoyée : le contrôleur utilise isset() ?>
            <input type="checkbox" name="hours[<?= $id ?>][is_closed]" value="1" <?= $hour->isClosed() ? 'checked' : '' ?> aria-label="Fermé <?= $hour->getDayName() ?>" class="w-5 h-5">
          </td>
          <td class="p-3">
            <?php // formaction : ce bouton envoie le formulaire vers la route de suppression au lieu de l'enregistrement ?>
            <button formaction="index.php?page=delete-hour&id=<?= $id ?>" onclick="return confirm('Supprimer cette plage ?');" class="text-red-600 hover:underline">Supprimer</button>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <button class="mt-6 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer les horaires</button>
</form>

<?php // Formulaire séparé pour ajouter une plage (ex. : mercredi après-midi) ?>
<h2 class="text-2xl font-bold mb-4">Ajouter une plage</h2>
<form action="index.php?page=create-hour-valid" method="POST" class="bg-white p-6 rounded-lg shadow flex flex-wrap items-end gap-4">
  <div>
    <label for="day_of_week" class="block mb-1 font-medium">Jour</label>
    <select id="day_of_week" name="day_of_week" class="border rounded-lg p-2">
      <?php foreach (OpeningHour::DAYS as $number => $name) { ?>
        <option value="<?= $number ?>"><?= $name ?></option>
      <?php } ?>
    </select>
  </div>
  <div>
    <label for="new_open" class="block mb-1 font-medium">Ouverture</label>
    <input type="time" id="new_open" name="open_time" required class="border rounded-lg p-2">
  </div>
  <div>
    <label for="new_close" class="block mb-1 font-medium">Fermeture</label>
    <input type="time" id="new_close" name="close_time" required class="border rounded-lg p-2">
  </div>
  <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Ajouter</button>
</form>

<?php include 'footer.php'; ?>