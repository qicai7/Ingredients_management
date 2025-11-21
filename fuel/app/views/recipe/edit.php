<link rel="stylesheet" href="<?= Uri::create('assets/css/style.css') ?>">

<div class="container" style="position: relative;">

  <!-- バツボタン（ホームに戻る） -->
  <div class="back-wrapper">
      <a href="<?= Uri::create('home/index') ?>" class="btn-close">
        <span>×</span>
      </a>
  </div>

  <h1 class="text-center mt20">レシピ編集</h1>

  <?php if ($error): ?>
      <p style="color:red;"><?= $error ?></p>
  <?php endif; ?>

  <form method="post" class="mt20">
      <div class="mt10">
          <label>レシピ名:</label>
          <input type="text" name="name" value="<?= htmlspecialchars($recipe['name']) ?>" required>
      </div>

      <div id="ingredients-area" class="mt20">
          <?php foreach ($ingredients as $item): ?>
          <div class="mt10">
              <label>材料:</label>
              <input type="text" name="ingredients[]" value="<?= htmlspecialchars($item['name']) ?>">
              <label>分量:</label>
              <input type="text" name="amounts[]" value="<?= htmlspecialchars($item['amount']) ?>">
          </div>
          <?php endforeach; ?>
      </div>

      <div class="mt20">
          <button type="button" onclick="addIngredient()" class="btn btn-add">材料を追加</button>
      </div>

      <div class="text-center mt20">
          <button type="submit" class="btn btn-edit">更新</button>
      </div>
  </form>

</div>

<script>
function addIngredient() {
    const area = document.getElementById('ingredients-area');
    const div = document.createElement('div');
    div.classList.add('mt10');
    div.innerHTML = `
        <label>材料:</label>
        <input type="text" name="ingredients[]" placeholder="例: にんじん">
        <label>分量:</label>
        <input type="text" name="amounts[]" placeholder="例: 2本">
    `;
    area.appendChild(div);
}
</script>
