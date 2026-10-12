<?php include 'header.php'; ?>
<?php /** @var News $news */ ?>

<article class="max-w-3xl mx-auto">
  <h1 class="text-4xl font-bold mb-4"><?= htmlspecialchars($news->getTitle()) ?></h1>
  <p class="text-gray-500 mb-6">
    Publié le <?= date('d/m/Y', strtotime($news->getPublishedAt())) ?> par <?= htmlspecialchars($news->getAuthorName() ?? '') ?>
  </p>

  <?php if ($news->getImage()) { ?>
    <img src="uploads/<?= htmlspecialchars($news->getImage()) ?>" alt="<?= htmlspecialchars($news->getTitle()) ?>" class="rounded-lg mb-6 w-full">
  <?php } ?>

  <?php // nl2br transforme les retours à la ligne du texte en <br> ?>
  <div class="leading-relaxed"><?= nl2br(htmlspecialchars($news->getContent())) ?></div>

  <a href="index.php?page=news" class="inline-block mt-8 text-indigo-600 hover:underline">← Retour aux actualités</a>
</article>

<?php include 'footer.php'; ?>