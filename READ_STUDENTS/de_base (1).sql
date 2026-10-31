-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 31 2026 г., 13:52
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `de_base`
--

-- --------------------------------------------------------

--
-- Структура таблицы `disciplines`
--

CREATE TABLE `disciplines` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `disciplines`
--

INSERT INTO `disciplines` (`id`, `name`) VALUES
(9, 'Big Data и аналитика'),
(10, 'DevOps и CI/CD'),
(11, 'UI/UX Дизайн'),
(1, 'Базы данных'),
(13, 'Блокчейн-технологии'),
(3, 'Веб-дизайн'),
(4, 'Искусственный интеллект'),
(7, 'Кибербезопасность'),
(5, 'Машинное обучение'),
(12, 'Микросервисная архитектура'),
(6, 'Облачные технологии'),
(2, 'Программирование на PHP'),
(8, 'Разработка мобильных приложений');

-- --------------------------------------------------------

--
-- Структура таблицы `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `deleted_at` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `grades`
--

INSERT INTO `grades` (`id`, `student_id`, `discipline_id`, `teacher_id`, `grade`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 5, '2026-10-31 16:38:05', '2026-10-31 13:54:19', '2026-10-31 16:38:05'),
(2, 2, 1, 1, 5, '2026-10-31 14:37:17', '2026-10-31 13:54:19', '2026-10-31 14:37:17'),
(3, 1, 2, 1, 4, '1970-01-01 00:00:00', '2026-10-31 13:54:19', '2026-10-31 16:33:18'),
(4, 3, 3, 2, 5, '1970-01-01 00:00:00', '2026-10-31 13:54:19', '2026-10-31 16:33:18');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(3, 'ИС-22-1'),
(1, 'ИС-23-1'),
(2, 'ИС-23-2'),
(4, 'ИС-24-1'),
(5, 'ИС-24-2'),
(6, 'ПИ-24-1');

-- --------------------------------------------------------

--
-- Структура таблицы `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `students`
--

INSERT INTO `students` (`id`, `user_id`, `full_name`, `group_id`) VALUES
(1, 3, 'Сидоров Алексей Петрович', 1),
(2, 4, 'Кузнецова Мария Игоревна', 1),
(3, 5, 'Смирнов Дмитрий Олегович', 2),
(4, 6, 'Алексеев Пётр Сергеевич', 1),
(5, 7, 'Борисова Екатерина Андреевна', 1),
(6, 8, 'Волков Никита Дмитриевич', 2),
(7, 9, 'Григорьева Ольга Игоревна', 2),
(8, 10, 'Дмитриев Артём Алексеевич', 3),
(9, 11, 'Егорова Светлана Павловна', 3),
(10, 12, 'Жуков Максим Владимирович', 1),
(11, 13, 'Зайцева Анна Романовна', 2),
(12, 14, 'Иванов Сергей Николаевич', 3),
(13, 15, 'Козлова Дарья Михайловна', 1),
(14, 1, 'Тестовый Студент 1', 1),
(15, 2, 'Тестовый Студент 2', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `teachers`
--

INSERT INTO `teachers` (`id`, `user_id`, `full_name`) VALUES
(1, 1, 'Иванов Иван Иванович'),
(2, 2, 'Петрова Анна Сергеевна');

-- --------------------------------------------------------

--
-- Структура таблицы `teacher_disciplines`
--

CREATE TABLE `teacher_disciplines` (
  `teacher_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `teacher_disciplines`
--

INSERT INTO `teacher_disciplines` (`teacher_id`, `discipline_id`) VALUES
(1, 1),
(1, 2),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(2, 3),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('teacher','student') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `role`) VALUES
(1, 'teacher1', '12345', 'teacher'),
(2, 'teacher2', '12345', 'teacher'),
(3, 'student1', '12345', 'student'),
(4, 'student2', '12345', 'student'),
(5, 'student3', '12345', 'student'),
(6, 'student4', '12345', 'student'),
(7, 'student5', '12345', 'student'),
(8, 'student6', '12345', 'student'),
(9, 'student7', '12345', 'student'),
(10, 'student8', '12345', 'student'),
(11, 'student9', '12345', 'student'),
(12, 'student10', '12345', 'student'),
(13, 'student11', '12345', 'student'),
(14, 'student12', '12345', 'student'),
(15, 'student13', '12345', 'student');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `disciplines`
--
ALTER TABLE `disciplines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_active_grade` (`student_id`,`discipline_id`,`teacher_id`,`deleted_at`),
  ADD KEY `discipline_id` (`discipline_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Индексы таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Индексы таблицы `teacher_disciplines`
--
ALTER TABLE `teacher_disciplines`
  ADD PRIMARY KEY (`teacher_id`,`discipline_id`),
  ADD KEY `discipline_id` (`discipline_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `disciplines`
--
ALTER TABLE `disciplines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT для таблицы `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `teacher_disciplines`
--
ALTER TABLE `teacher_disciplines`
  ADD CONSTRAINT `teacher_disciplines_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_disciplines_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `disciplines` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
