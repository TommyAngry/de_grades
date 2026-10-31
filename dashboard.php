<?php require 'functions.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Дашборд успеваемости</title>
    <!-- Убедитесь, что файл chart.min.js реально лежит в папке assets! -->
    <script src="assets/chart.min.js"></script>
</head>
<body>
<h1>Дашборд успеваемости</h1>
<p><a href="<?= $_SESSION['role'] === 'teacher' ? 'teacher.php' : 'student.php' ?>">Назад</a> | <a href="logout.php">Выйти</a></p>

<!-- Фильтр для интерактивности -->
<div style="margin-bottom: 20px; padding: 10px; background: #f0f0f0; display: inline-block;">
    <label>Выберите дисциплину (влияет на круговую диаграмму): 
        <select id="filterDiscipline">
            <option value="">Все дисциплины</option>
        </select>
    </label>
</div>

<div style="display: flex; gap: 40px; flex-wrap: wrap;">
    <div style="width: 500px;">
        <h2>Средний балл по дисциплинам</h2>
        <canvas id="chartAvg"></canvas>
    </div>
    <div style="width: 500px;">
        <h2>Распределение оценок</h2>
        <canvas id="chartDist"></canvas>
    </div>
</div>

<script>
// Переменные для хранения экземпляров графиков, чтобы их можно было перерисовывать
let chartAvgInstance = null;
let chartDistInstance = null;

function loadData(disciplineId = '') {
    let url = 'chart_data.php';
    if (disciplineId) url += '?discipline_id=' + disciplineId;

    fetch(url)
        .then(r => r.json())
        .then(data => {
            // --- График 1: Средний балл (Столбцы) ---
            const labels1 = data.avgByDiscipline.map(x => x.discipline);
            const values1 = data.avgByDiscipline.map(x => x.avg_grade);

            // Заполняем выпадающий список, если он еще пустой
            const select = document.getElementById('filterDiscipline');
            if (select.options.length === 1) {
                data.avgByDiscipline.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.discipline;
                    select.appendChild(opt);
                });
                select.value = disciplineId; // сохраняем выбранное значение
            }

            if (chartAvgInstance) chartAvgInstance.destroy(); // Удаляем старый график

            chartAvgInstance = new Chart(document.getElementById('chartAvg'), {
                type: 'bar',
                data: {
                    labels: labels1,
                    datasets: [{
                        label: 'Средний балл',
                        data: values1,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, max: 5 } }
                }
            });

            // --- График 2: Распределение (Круговая) ---
            const labels2 = data.distribution.map(x => 'Оценка ' + x.grade);
            const values2 = data.distribution.map(x => x.cnt);

            if (chartDistInstance) chartDistInstance.destroy(); // Удаляем старый график

            chartDistInstance = new Chart(document.getElementById('chartDist'), {
                type: 'pie',
                data: {
                    labels: labels2,
                    datasets: [{
                        data: values2,
                        backgroundColor: ['#e74c3c', '#f39c12', '#2ecc71', '#3498db']
                    }]
                },
                options: { responsive: true }
            });
        })
        .catch(err => console.error('Ошибка загрузки данных:', err));
}

// Слушатель изменения фильтра
document.getElementById('filterDiscipline').addEventListener('change', function() {
    loadData(this.value);
});

// Первичная загрузка при открытии страницы
loadData();
</script>
</body>
</html>