<?php
// db.php
// НА ЭКЗАМЕНЕ: замените host, dbname, username, password на выданные вам значения!
$host = 'localhost';       // или IP сервера БД, который выдадут
$dbname = 'de_base';       // имя вашей базы
$username = 'root';        // на XAMPP по умолчанию root
$password = '';            // на XAMPP по умолчанию пусто

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}