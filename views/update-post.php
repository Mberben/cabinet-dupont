<?php include 'header.php'; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier l'article</h1>
    <?php if (isset($_GET['error'])): ?>
      <div class="bg-red-100 text-red-700 p-4 rounded mb-4">Merci de saisir le titre complet</div>
    <?php endif; ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="post" action="index.php?page=update-post-valid&id=<?php echo $_GET['id']; ?>" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $article->getId(); ?>">

  <input type="text" name="title" placeholder="Titre de l'article" class="w-full border rounded-lg p-3" value="<?php echo htmlspecialchars($article->getTitle()); ?>">

  <?php if ($article->getImage()) { ?>
    <img class="rounded-lg mb-4" alt="<?php echo htmlspecialchars($article->getTitle()); ?>" src="uploads/<?php echo $article->getImage(); ?>">
  <?php } ?>
  <label for="image">Ajoutez une image pour la modifier</label>
  <input type="file" name="image" id="image" class="w-full border rounded-lg p-3" accept="image/*">

  <textarea name="content" placeholder="Contenu de l'article" class="w-full border rounded-lg p-3 h-40"><?php echo htmlspecialchars($article->getContent()); ?></textarea>
  <textarea name="description" placeholder="Description de l'article" class="w-full border rounded-lg p-3 h-20"><?php echo htmlspecialchars($article->getDescription()); ?></textarea>

      <div class="grid grid-cols-3 gap-4">
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="tags" value="tech" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>Tech</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="tags" value="design" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>Design</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="tags" value="javascript" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>JavaScript</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="tags" value="tailwind" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>Tailwind</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="tags" value="php" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>PHP</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="tags" value="html" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
          <span>HTML</span>
        </label>
      </div>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Publier</button>
    </form>
<?php include 'footer.php'; ?>