<?php include 'header.php'; ?>
<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md mx-auto my-12">
  <h1 class="text-2xl font-bold mb-6">Créer un compte</h1>

  <?php if (isset($_GET['error']) && $_GET['error'] === 'email-format'): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">Format d'email invalide</div>
  <?php endif; ?>
  <?php if (isset($_GET['error']) && $_GET['error'] === 'fields'): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">Merci de remplir tous les champs</div>
  <?php endif; ?>
  <?php if (isset($_GET['error']) && $_GET['error'] === 'email-used'): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">Email déjà utilisé</div>
  <?php endif; ?>


  <form class="space-y-4" action="index.php?page=register-valid" method="POST">

    <?php // label for="..." = id du champ : lu par les lecteurs d'écran, et cliquer sur le texte active le champ 
    ?>
    <div>
      <label for="firstname" class="block mb-1 font-medium">Prénom</label>
      <?php // name="firstname" = clé lue dans $_POST['firstname'] par le contrôleur ; autocomplete aide le navigateur à préremplir 
      ?>
      <input type="text" id="firstname" name="firstname" required autocomplete="given-name" class="w-full border rounded-lg p-3">
    </div>

    <div>
      <label for="lastname" class="block mb-1 font-medium">Nom</label>
      <input type="text" id="lastname" name="lastname" required autocomplete="family-name" class="w-full border rounded-lg p-3">
    </div>

    <div>
      <label for="email" class="block mb-1 font-medium">Email</label>
      <?php // type="email" : le navigateur vérifie le format (le contrôleur le revérifie avec filter_var) 
      ?>
      <input type="email" id="email" name="email" required autocomplete="email" class="w-full border rounded-lg p-3">
    </div>

    <div>
      <label for="password" class="block mb-1 font-medium">Mot de passe (8 caractères minimum)</label>
      <?php // type="password" masque la saisie ; minlength="8" bloque côté navigateur, le contrôleur revérifie avec strlen() 
      ?>
      <?php // autocomplete="new-password" : le navigateur peut proposer un mot de passe solide 
      ?>
      <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password" class="w-full border rounded-lg p-3">
    </div>

    <?php // required sur chaque champ : le navigateur bloque l'envoi si un champ est vide 
    ?>
    <?php // Ces vérifications sont du confort : elles se contournent, d'où les contrôles dans le contrôleur 
    ?>
    <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">S'inscrire</button>
  </form>
</div>
<?php include 'footer.php'; ?>