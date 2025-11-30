<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規登録</title>
    <link rel="stylesheet" href="<?= Uri::create('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= Uri::create('assets/css/knockout_validation.css') ?>">
</head>
<body>
    
<div class="container" style="max-width: 400px; margin: 50px auto;">

    <h1 class="text-center mt20">新規登録</h1>

    <!-- エラーメッセージ -->
    <?php if (!empty($error)): ?>
        <p class="text-red mt20"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- 新規登録フォーム -->
    <form data-bind="submit: register" class="mt20 flex-col"> 

        <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

        <label for="username">ユーザー名</label>
        <input type="text" name="username" id="username" class="input" 
               data-bind="value: username, valueUpdate: 'afterkeydown'" >

        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" class="input" 
               data-bind="value: password" >

        <label for="password_confirm">パスワード確認</label>
        <input type="password" name="password_confirm" id="password_confirm" class="input" 
               data-bind="value: passwordConfirm" >

        <button type="submit" class="btn btn-logout" >
            登録
        </button>
    </form>

    <p class="mt20 text-center">
        すでにアカウントをお持ちの方は
        <a href="<?= Uri::create('user/login') ?>" class="text-blue">ログイン</a>
    </p>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> 
<?php echo Asset::js('knockout.min.js'); ?>
<?php echo Asset::js('knockout.validation.min.js'); ?>
<?php echo Asset::js('registration.js'); ?>

</body>
</html>
