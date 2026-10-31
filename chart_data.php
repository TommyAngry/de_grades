<?php
require 'db.php';
header('Content-Type: application/json; charset=utf-8');

$disciplineId = $_GET['discipline_id'] ?? null;
$activeDate = '1970-01-01 00:00:00'; // Наша новая дата активности

// 1. Средний балл по дисциплинам
$stmt = $pdo->prepare("
    SELECT d.id, d.name AS discipline, ROUND(AVG(g.grade), 2) AS avg_grade
    FROM grades g
    JOIN disciplines d ON d.id = g.discipline_id
    WHERE g.deleted_at = ?
    GROUP BY d.id, d.name
    ORDER BY d.name
");
$stmt->execute([$activeDate]);
$avgByDiscipline = $stmt->fetchAll();

// 2. Распределение оценок (с фильтрацией)
$sql = "SELECT grade, COUNT(*) AS cnt FROM grades WHERE deleted_at = ?";
$params = [$activeDate];

if ($disciplineId) {
    $sql .= " AND discipline_id = ?";
    $params[] = $disciplineId;
}
$sql .= " GROUP BY grade ORDER BY grade";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$distribution = $stmt->fetchAll();

echo json_encode([
    'avgByDiscipline' => $avgByDiscipline,
    'distribution' => $distribution,
], JSON_UNESCAPED_UNICODE);