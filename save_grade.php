<?php
require 'functions.php';
require 'db.php';
require_role('teacher');

// Восстановление удаленной оценки
if (isset($_POST['restore_id'])) {
    $restore_id = (int)$_POST['restore_id'];
    
    // Находим информацию об удаленной оценке, чтобы проверить дубликаты перед восстановлением
    $stmt = $pdo->prepare("SELECT student_id, discipline_id, teacher_id FROM grades WHERE id = ?");
    $stmt->execute([$restore_id]);
    $gradeToRestore = $stmt->fetch();
    
    if ($gradeToRestore) {
        // Проверяем, нет ли уже активной оценки для этого студента по этой дисциплине
        $stmt = $pdo->prepare("
            SELECT id FROM grades 
            WHERE student_id = ? AND discipline_id = ? AND teacher_id = ? AND deleted_at = '1970-01-01 00:00:00'
        ");
        $stmt->execute([$gradeToRestore['student_id'], $gradeToRestore['discipline_id'], $gradeToRestore['teacher_id']]);
        
        if ($stmt->fetch()) {
            die('Невозможно восстановить: у студента уже есть активная оценка по этой дисциплине. Сначала удалите текущую.');
        }
        
        // Восстанавливаем (делаем дату дефолтной)
        $stmt = $pdo->prepare("UPDATE grades SET deleted_at = '1970-01-01 00:00:00' WHERE id = ?");
        $stmt->execute([$restore_id]);
    }
    
    header('Location: teacher.php');
    exit;
}

// teacher_id по user_id
$stmt = $pdo->prepare("SELECT id FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$teacher = $stmt->fetch();
if (!$teacher) { die('Преподаватель не найден'); }

$student_id = (int)($_POST['student_id'] ?? 0);
$discipline_id = (int)($_POST['discipline_id'] ?? 0);
$grade_raw = $_POST['grade'] ?? '';
$grade = ($grade_raw === '') ? null : (int)$grade_raw;

if (!$student_id || !$discipline_id) {
    die('Не выбраны студент или дисциплина');
}

// Проверяем, что преподаватель действительно ведёт эту дисциплину
$stmt = $pdo->prepare("
    SELECT 1 FROM teacher_disciplines
    WHERE teacher_id = ? AND discipline_id = ?
");
$stmt->execute([$teacher['id'], $discipline_id]);
if (!$stmt->fetch()) {
    die('Вы не ведёте эту дисциплину');
}

// Ищем активную оценку (сравниваем с датой 1970-01-01)
$stmt = $pdo->prepare("
    SELECT id FROM grades
    WHERE student_id = ? AND discipline_id = ? AND teacher_id = ? AND deleted_at = '1970-01-01 00:00:00'
");
$stmt->execute([$student_id, $discipline_id, $teacher['id']]);
$existing = $stmt->fetch();

if ($grade === null) {
    // Пустое значение = мягкое удаление активной оценки (ставим текущее время)
    if ($existing) {
        $stmt = $pdo->prepare("UPDATE grades SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$existing['id']]);
    }
} else {
    // Валидация диапазона
    if ($grade < 2 || $grade > 5) {
        die('Оценка должна быть от 2 до 5');
    }
    
    if ($existing) {
        // Изменение существующей активной оценки
        $stmt = $pdo->prepare("UPDATE grades SET grade = ? WHERE id = ?");
        $stmt->execute([$grade, $existing['id']]);
    } else {
        // Новая оценка (явно указываем дату 1970-01-01 для активности)
        $stmt = $pdo->prepare("
            INSERT INTO grades (student_id, discipline_id, teacher_id, grade, deleted_at)
            VALUES (?, ?, ?, ?, '1970-01-01 00:00:00')
        ");
        $stmt->execute([$student_id, $discipline_id, $teacher['id'], $grade]);
    }
}

header('Location: teacher.php');
exit;