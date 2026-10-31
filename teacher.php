<?php
require 'functions.php';
require 'db.php';
require_role('teacher');

// Находим teacher_id по user_id
$stmt = $pdo->prepare("SELECT id, full_name FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$teacher = $stmt->fetch();
if (!$teacher) { die('Преподаватель не найден'); }

// Дисциплины преподавателя
$stmt = $pdo->prepare("
    SELECT d.id, d.name
    FROM disciplines d
    JOIN teacher_disciplines td ON td.discipline_id = d.id
    WHERE td.teacher_id = ?
    ORDER BY d.name
");
$stmt->execute([$teacher['id']]);
$disciplines = $stmt->fetchAll();

// Группы, с которыми работает преподаватель (только те, где есть его оценки)
$stmt = $pdo->prepare("
    SELECT DISTINCT gr.id, gr.name 
    FROM groups gr
    JOIN students s ON s.group_id = gr.id
    JOIN grades g ON g.student_id = s.id
    WHERE g.teacher_id = ? AND g.deleted_at = '1970-01-01 00:00:00'
    ORDER BY gr.name
");
$stmt->execute([$teacher['id']]);
$groups = $stmt->fetchAll();

// Студенты, которым преподаватель уже ставил оценки
$stmt = $pdo->prepare("
    SELECT DISTINCT s.id, s.full_name 
    FROM students s
    JOIN grades g ON g.student_id = s.id
    WHERE g.teacher_id = ? AND g.deleted_at = '1970-01-01 00:00:00'
    ORDER BY s.full_name
");
$stmt->execute([$teacher['id']]);
$allStudents = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head><meta charset="utf-8"><title>Кабинет преподавателя</title></head>
<body>
<h1>Кабинет преподавателя: <?= e($teacher['full_name']) ?></h1>
<p><a href="dashboard.php">Дашборд</a> | <a href="logout.php">Выйти</a></p>
<h2>Мои дисциплины</h2>
<ul>
<?php foreach ($disciplines as $d): ?>
    <li><?= e($d['name']) ?></li>
<?php endforeach; ?>
</ul>

<h2>Выставить / изменить / удалить оценку</h2>
<form method="post" action="save_grade.php">
    <label>Дисциплина:
        <select name="discipline_id" required>
            <?php foreach ($disciplines as $d): ?>
                <option value="<?= $d['id'] ?>"><?= e($d['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <label>Группа:
        <select name="group_id" id="group_id" required>
            <?php foreach ($groups as $g): ?>
                <option value="<?= $g['id'] ?>"><?= e($g['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <label>Студент:
        <select name="student_id" id="student_id" required>
            <?php foreach ($allStudents as $s): ?>
                <option value="<?= $s['id'] ?>"><?= e($s['full_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>
    <label>Оценка (2–5, пусто = удалить):
        <input type="number" name="grade" min="2" max="5">
    </label><br><br>
    <button type="submit">Сохранить</button>
</form>

<h2>Просмотр оценок</h2>
<form method="get" action="teacher.php">
    <label>Студент (все оценки):
        <select name="view_student_id">
            <option value="">— выберите —</option>
            <?php foreach ($allStudents as $s): ?>
                <option value="<?= $s['id'] ?>" <?= (($_GET['view_student_id'] ?? '') == $s['id'] ? 'selected' : '') ?>>
                    <?= e($s['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Показать оценки студента</button>
</form>

<form method="get" action="teacher.php">
    <label>Группа (оценки всех студентов):
        <select name="view_group_id">
            <option value="">— выберите —</option>
            <?php foreach ($groups as $g): ?>
                <option value="<?= $g['id'] ?>" <?= (($_GET['view_group_id'] ?? '') == $g['id'] ? 'selected' : '') ?>>
                    <?= e($g['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Показать оценки группы</button>
</form>

<?php
// Вывод оценок выбранного студента
if (!empty($_GET['view_student_id'])) {
    $stmt = $pdo->prepare("
        SELECT g.grade, d.name AS discipline, gr.name AS group_name, s.full_name
        FROM grades g
        JOIN students s ON s.id = g.student_id
        JOIN groups gr ON gr.id = s.group_id
        JOIN disciplines d ON d.id = g.discipline_id
        WHERE g.student_id = ? AND g.deleted_at = '1970-01-01 00:00:00'
        ORDER BY d.name
    ");
    $stmt->execute([$_GET['view_student_id']]);
    $rows = $stmt->fetchAll();
    echo "<h3>Оценки студента</h3>";
    if (!$rows) {
        echo "<p>Оценок нет.</p>";
    } else {
        echo "<table border='1' cellpadding='5'><tr><th>Студент</th><th>Группа</th><th>Дисциплина</th><th>Оценка</th></tr>";
        foreach ($rows as $r) {
            echo "<tr><td>" . e($r['full_name']) . "</td><td>" . e($r['group_name']) . "</td><td>" . e($r['discipline']) . "</td><td>" . e($r['grade']) . "</td></tr>";
        }
        echo "</table>";
    }
}

// Вывод оценок всех студентов выбранной группы
if (!empty($_GET['view_group_id'])) {
    $stmt = $pdo->prepare("
        SELECT g.grade, d.name AS discipline, s.full_name
        FROM grades g
        JOIN students s ON s.id = g.student_id
        JOIN disciplines d ON d.id = g.discipline_id
        WHERE s.group_id = ? AND g.deleted_at = '1970-01-01 00:00:00'
        ORDER BY s.full_name, d.name
    ");
    $stmt->execute([$_GET['view_group_id']]);
    $rows = $stmt->fetchAll();
    echo "<h3>Оценки группы</h3>";
    if (!$rows) {
        echo "<p>Оценок нет.</p>";
    } else {
        echo "<table border='1' cellpadding='5'><tr><th>Студент</th><th>Дисциплина</th><th>Оценка</th></tr>";
        foreach ($rows as $r) {
            echo "<tr><td>" . e($r['full_name']) . "</td><td>" . e($r['discipline']) . "</td><td>" . e($r['grade']) . "</td></tr>";
        }
        echo "</table>";
    }
}
?>

<h2>Удаленные оценки (Корзина)</h2>
<?php
// Берем только те удаленные оценки, которые относятся к дисциплинам этого преподавателя
$stmt = $pdo->prepare("
    SELECT g.id, g.grade, s.full_name AS student, d.name AS discipline
    FROM grades g
    JOIN students s ON s.id = g.student_id
    JOIN disciplines d ON d.id = g.discipline_id
    WHERE g.deleted_at != '1970-01-01 00:00:00' AND g.teacher_id = ?
    ORDER BY g.deleted_at DESC
");
$stmt->execute([$teacher['id']]);
$deletedGrades = $stmt->fetchAll();

if ($deletedGrades): ?>
    <table border="1" cellpadding="5">
        <tr><th>Студент</th><th>Дисциплина</th><th>Оценка</th><th>Действие</th></tr>
        <?php foreach ($deletedGrades as $dg): ?>
            <tr>
                <td><?= e($dg['student']) ?></td>
                <td><?= e($dg['discipline']) ?></td>
                <td><?= e($dg['grade']) ?></td>
                <td>
                    <form method="post" action="save_grade.php" style="margin:0;">
                        <input type="hidden" name="restore_id" value="<?= $dg['id'] ?>">
                        <button type="submit">Восстановить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Удаленных оценок нет.</p>
<?php endif; ?>

</body>
</html>