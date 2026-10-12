<?php include 'header.php'; ?>
<?php /** @var News[] $newsList */ ?>

<h1 class="text-3xl font-bold mb-2">Actualités</h1>
<p class="text-gray-600 mb-8">Les dernières nouvelles du cabinet et du monde de la santé dentaire.</p>

<?php // Message si aucune actualité n'est encore publiée ?>
<?php if (empty($newsList)) { ?>
  <p class="text-gray-500">Aucune actualité pour le moment.</p>
<?php } ?>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
  <?php foreach ($newsList as $news) { ?>
    <article class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
      <?php if ($news->getImage()) { ?>
        <img src="uploads/<?= htmlspecialchars($news->getImage()) ?>" alt="<?= htmlspecialchars($news->getTitle()) ?>" class="w-full h-48 object-cover" loading="lazy">
      <?php } ?>
      <div class="p-6 flex flex-col grow">
        <h2 class="font-semibold text-xl mb-2"><?= htmlspecialchars($news->getTitle()) ?></h2>
        <?php // Date au format français + auteur ?>
        <p class="text-sm text-gray-500 mb-4">
          <?= date('d/m/Y', strtotime($news->getPublishedAt())) ?> · <?= htmlspecialchars($news->getAuthorName() ?? '') ?>
        </p>
        <?php // Aperçu : les 150 premiers caractères du contenu ?>
        <p class="text-gray-600 mb-4 grow"><?= htmlspecialchars(mb_strimwidth($news->getContent(), 0, 150, '…')) ?></p>
        <a href="index.php?page=news-detail&id=<?= $news->getId() ?>" class="text-indigo-600 hover:underline">Lire la suite</a>
      </div>
    </article>
  <?php } ?>
</div>

<?php include 'footer.php'; ?>