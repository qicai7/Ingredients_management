<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム画面</title>
    <link rel="stylesheet" href="<?= Uri::create('assets/css/style.css') ?>">
    <style>
        body { position: relative; } /* 絶対配置の基準 */
    </style>
</head>
<body>

<!-- コンテナ内絶対配置 -->
<div class="container">

  <div class="logout-wrapper">
      <a href="<?= Uri::create('home/logout') ?>" class="btn btn-logout">ログアウト</a>
  </div>

  <h1 class="text-center mt20">ホーム画面</h1>

  <hr class="mt20">

    <!-- 自分のレシピリスト -->
    <?php if (empty($recipes)): ?>
        <p class="mt20 text-center">まだレシピはありません</p>
    <?php else: ?>
        <ul class="list-none mt20">
        <?php foreach ($recipes as $recipe): ?>
            <li class="p10 border-b flex flex-between">
                <a href="<?= Uri::create('recipe/view/'.$recipe->id) ?>" class="text-blue">
                    <?= htmlspecialchars($recipe->name) ?>
                </a>
                <div>
                    <a href="<?= Uri::create('recipe/edit/'.$recipe->id) ?>" class="btn ml10 btn-edit">編集</a>
                    <a href="<?= Uri::create('recipe/confirm_delete/'.$recipe->id) ?>" class="btn ml10 btn-delete">削除</a>

                </div>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- コンテナ下中央にレシピ追加ボタン -->
    <div class="text-center mt20">
        <a href="<?= Uri::create('recipe/add') ?>" class="btn">レシピ追加</a>
    </div>

</div>

</body>
</html>
