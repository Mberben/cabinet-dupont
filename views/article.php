<?php
/** @var Post $article */
/** @var Comment[] $comments */
?>
<?php include 'header.php'; ?>
    <article class="mb-12 min-w-[700px]">
      <h1 class="text-4xl font-bold mb-4"><?php echo $article->getTitle(); ?></h1>
      <p class="text-gray-500 mb-6">Par Admin • <?php echo date('d/m/Y', strtotime($article->getDate())); ?></p>
       <?php if($article->getImage()){?>
        <img class="rounded-lg mb-4" alt="<?php echo $article->getTitle(); ?>" src="uploads/<?php echo $article->getImage(); ?>">
        <?php } ?>
      <p class="leading-relaxed mb-6"><?php echo $article->getContent(); ?></p>
      <div class="flex gap-2">
       <?php foreach ($tags as $tag) { ?>
         <span class="bg-indigo-100 text-indigo-600 px-3 py-1 rounded-full text-sm">#<?php echo $tag->getName();?></span>
        <?php } ?>
      </div>
    </article>
    <section>
      <h2 class="text-2xl font-semibold mb-4">Commentaires</h2>
      <?php foreach ($comments as $comment){?>
      <div class="space-y-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow">
          <p class="text-gray-700"><?php echo $comment->getContent(); ?></p>
          <p class="text-sm text-gray-500 mt-2">Posté par <?php echo $comment->getAuthor(); ?>, <?php echo date('d/m/Y', strtotime($comment->getDate())); ?></p>
        </div>
      </div>
      <?php } ?>
      <?php if(count($comments) ===0) { ?>
             <div class="space-y-4 mb-6">
              <div class="bg-white p-4 rounded-lg shadow">
                <p class="text-gray-700">Aucun commentaire.</p>
              </div>
             </div> 
        <?php } ?>
<form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=comment&id=<?= $article->getId() ?>">
        <input type="text" name="author" placeholder="Pseudo" class="w-full border rounded-lg p-3">
        <textarea name="content" placeholder="Votre commentaire..." class="w-full border rounded-lg p-3"></textarea>
        <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Envoyer</button>
      </form>
    </section>
<?php include 'footer.php'; ?>