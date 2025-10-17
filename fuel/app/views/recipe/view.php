<link rel="stylesheet" href="<?= Uri::create('assets/css/style.css') ?>">

<div class="container" style="position: relative;">

  <!-- バツボタン（ホームに戻る） -->
  <div class="back-wrapper">
      <a href="<?= Uri::create('home/index') ?>" class="btn-close">
        <span>×</span>
      </a>
  </div>

  <h1 class="text-center mt20">レシピ詳細</h1>

  <!-- レシピ名 -->
  <h2 class="text-center mt20"><?= htmlspecialchars($recipe->name) ?></h2>

  <!-- 材料一覧 -->
  <?php if (!empty($ingredients)): ?>
      <ul class="list-none mt20">
          <?php foreach ($ingredients as $item): ?>
              <li class="p10 border-b flex flex-between">
                  <span><?= htmlspecialchars($item['name']) ?></span>
                  <span><?= htmlspecialchars($item['amount']) ?></span>
              </li>
          <?php endforeach; ?>
      </ul>
  <?php else: ?>
      <p class="text-center mt20">材料は登録されていません</p>
  <?php endif; ?>

  <div class="text-center mt20">
      <a href="<?= Uri::create('recipe/edit/'.$recipe->id) ?>" class="btn btn-edit">編集</a>
  </div>

</div>
