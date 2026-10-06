<?php include 'header.php'; ?>
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md mx-auto my-12">
      <h1 class="text-2xl font-bold mb-6">Connexion</h1>
          <?php if (isset($_GET['error']) && $_GET['error'] === 'credentials'): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">Identifiants invalides</div>
          <?php endif; ?>
          <?php if (isset($_GET['error']) && $_GET['error'] === 'fields'): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">Merci de remplir tous les champs</div>
          <?php endif; ?>

      <form class="space-y-4" action="index.php?page=login-valid" method="POST">
        <input type="email" name="email" placeholder="Email" class="w-full border rounded-lg p-3">
        <input type="password" name="password" placeholder="Mot de passe" class="w-full border rounded-lg p-3">
        <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Se connecter</button>
      </form>
      <p class="mt-4 text-sm text-gray-500">Pas encore de compte ? <a href="index.php?page=register" class="text-indigo-600 hover:underline">Créer un compte</a></p>
    </div>
<?php include 'footer.php'; ?>