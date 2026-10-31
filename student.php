
<?php
require 'functions.php';
require 'db.php';
require_role('student');

// student_id по user_id
$stmt = $pdo->prepare("SELECT id, full_name FROM students WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$student = $stmt->fetch();
if (!$student) { die('Студент не найден'); }

$stmt = $pdo->prepare("
    SELECT g.grade, d.name AS discipline, t.full_name AS teacher
    FROM grades g
    JOIN disciplines d ON d.id = g.discipline_id
    JOIN teachers t ON t.id = g.teacher_id
    WHERE g.student_id = ? AND g.deleted_at = '1970-01-01 00:00:00'
    ORDER BY d.name
");
$stmt->execute([$student['id']]);
$grades = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="utf-8"><title>Мои оценки</title></head>
<body>
<h1>Мои оценки: <?= e($student['full_name']) ?></h1>
<p><a href="dashboard.php">Дашборд</a> | <a href="logout.php">Выйти</a></p>

<?php if (!$grades): ?>
    <p>Оценок пока нет.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr><th>Дисциплина</th><th>Преподаватель</th><th>Оценка</th></tr>
        <?php foreach ($grades as $g): ?>
            <tr>
                <td><?= e($g['discipline']) ?></td>
                <td><?= e($g['teacher']) ?></td>
                <td><?= e($g['grade']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
</body>
</html>
