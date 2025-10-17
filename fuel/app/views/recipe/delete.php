<link rel="stylesheet" href="<?= Uri::create('assets/css/style.css') ?>">

<div class="container" style="position: relative;">

  <!-- バツボタン（ホームに戻る） -->
  <div class="back-wrapper">
      <a href="<?= Uri::create('home/index') ?>" class="btn-close">
        <span>×</span>
      </a>
  </div>


  <p class="text-center mt20">以下のレシピを削除します　よろしいですか？</p>

  <!-- レシピ名 -->
  <h2 class="text-center mt20"><?= htmlspecialchars($recipe->name) ?></h2>

  <?php if (!empty($ingredients)): ?>
      <ul class="list-none mt20">
          <?php foreach ($ingredients as $item): ?>
              <li class="p10 border-b flex flex-between">
                  <span><?= htmlspecialchars($item['name']) ?></span>
                  <span><?= htmlspecialchars($item['amount']) ?></span>
              </li>
          <?php endforeach; ?>
      </ul>
  <?php endif; ?>

  <div class="text-center mt20">
      <!-- 削除実行 -->
      <form method="post" action="<?= Uri::create('recipe/delete/'.$recipe->id) ?>" style="display:inline;">
          <button type="submit" class="btn btn-delete">削除する</button>
      </form>
  </div>

</div>
