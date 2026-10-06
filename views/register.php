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
        <input type="text" name="name" class="w-full border rounded-lg p-3" placeholder="Nom">
        <input type="email" name="email" class="w-full border rounded-lg p-3" placeholder="Email">
        <input type="password" name="password" class="w-full border rounded-lg p-3" placeholder="Mot de passe">
        <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">S'inscrire</button>
      </form>
    </div>
<?php include 'footer.php'; ?>