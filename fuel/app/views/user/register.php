<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="<?= Uri::create('assets/css/style.css') ?>">
</head>
<body>

<div class="container" style="max-width: 400px; margin: 50px auto;">

    <h1 class="text-center mt20">新規登録</h1>

    <!-- エラーメッセージ -->
    <?php if (!empty($error)): ?>
        <p class="text-red mt20"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- 新規登録フォーム -->
    <form action="" method="post" class="mt20 flex-col">
        <label for="username">ユーザー名</label>
        <input type="text" name="username" id="username" class="input" required>

        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" class="input" required>

        <button type="submit" class="btn btn-logout">登録</button>
    </form>

    <p class="mt20 text-center">
        すでにアカウントをお持ちの方は
        <a href="<?= Uri::create('user/login') ?>" class="text-blue">ログイン</a>
    </p>

</div>

</body>
</html>
