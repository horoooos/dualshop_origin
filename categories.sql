-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 24 2025 г., 17:40
-- Версия сервера: 8.0.24
-- Версия PHP: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dualshop`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `product_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_seasonal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `product_type`, `is_seasonal`, `created_at`, `updated_at`, `parent_id`) VALUES
(1, 'Смартфоны', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(2, 'Ноутбуки', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(3, 'Телевизоры', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(4, 'Аксессуары для смартфонов', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', 1),
(5, 'Игровые ноутбуки', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', 2),
(6, 'Умные часы', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(7, 'Наушники и гарнитуры', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(8, 'Бытовая техника', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(9, 'Планшеты', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL),
(10, 'Фотоаппараты и камеры', 0, '2025-04-24 14:36:34', '2025-04-24 14:36:34', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
