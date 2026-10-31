<?php require 'functions.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="utf-8"><title>Вход</title></head>
<body>
<h1>Вход в систему оценок</h1>
<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red"><?= e($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<form method="post" action="auth.php">
    <label>Логин: <input type="text" name="login" required></label><br><br>
    <label>Пароль: <input type="password" name="password" required></label><br><br>
    <button type="submit">Войти</button>
</form>
</body>
</html>