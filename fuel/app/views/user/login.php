<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>

    <!-- エラーメッセージ表示 -->
    <?php if (isset($error_message) && $error_message): ?>
    <p style="color:red;"><?= $error_message ?></p>
<?php endif; ?>

    <form action="/user/login" method="post">
        <label>ユーザー名:</label>
        <input type="text" name="username" required><br>

        <label>パスワード:</label>
        <input type="password" name="password" required><br>

        <button type="submit">ログイン</button>
    </form>

    <!-- 新規登録ボタン -->
    <form action="/user/register" method="get" style="margin-top:10px;">
        <button type="submit">新規登録</button>
    </form>

</body>
</html>

