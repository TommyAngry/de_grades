<?php
require 'functions.php';
require 'db.php';

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';

if ($login === '' || $password === '') {
    $_SESSION['error'] = 'Заполните логин и пароль';
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, login, password, role FROM users WHERE login = ?");
$stmt->execute([$login]);
$user = $stmt->fetch();

// В учебном проекте пароль в открытом виде. В реальном — password_verify().
if (!$user || $user['password'] !== $password) {
    $_SESSION['error'] = 'Неверный логин или пароль';
    header('Location: login.php');
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['login'] = $user['login'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'teacher') {
    header('Location: teacher.php');
} else {
    header('Location: student.php');
}
exit;