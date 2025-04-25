-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 24 2025 г., 17:44
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
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type` int UNSIGNED NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `img`, `product_type`, `country`, `color`, `qty`, `description`, `created_at`, `updated_at`, `category_id`) VALUES
(1, 'Мультиварка Redmond RMC-M900S', 200, 'Мультиварка Redmond RMC-M900S.webp', 8, 'Китай', 'Черный', 40, 'Мощная мультиварка с множеством программ.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 8),
(2, 'Apple AirPods Pro 2', 250, 'Apple AirPods Pro 2.webp', 7, 'США', 'Белый', 70, 'Беспроводные наушники с активным шумоподавлением.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 7),
(3, 'ASUS ROG Zephyrus G14', 1300, 'ASUS ROG Zephyrus G14.webp', 5, 'Тайвань', 'Серый', 20, 'Компактный игровой ноутбук с высокой производительностью.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 5),
(4, 'Samsung Galaxy S23', 800, 'Samsung Galaxy S23.webp', 1, 'Южная Корея', 'Черный', 50, 'Флагманский смартфон с мощным процессором и камерой.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 1),
(5, 'Apple MacBook Air M2', 1200, 'Apple MacBook Air M2.webp', 2, 'США', 'Серый', 30, 'Ультратонкий ноутбук с чипом M2.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 2),
(6, 'Sony Alpha A7 III', 2000, 'Sony Alpha A7 III.webp', 10, 'Япония', 'Черный', 15, 'Флагманская камера с отличной автономностью.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 10),
(7, 'Canon EOS R5', 3500, 'Canon EOS R5.webp', 10, 'Япония', 'Черный', 10, 'Профессиональная беззеркальная камера с 8K видео.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 10),
(8, 'Sony WH-1000XM5', 400, 'Sony WH-1000XM5.webp', 7, 'Япония', 'Черный', 50, 'Флагманские накладные наушники с лучшим шумоподавлением.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 7),
(9, 'MSI Stealth GS66', 1500, 'MSI Stealth GS66.webp', 5, 'Тайвань', 'Черный', 15, 'Игровой ноутбук с мощной видеокартой.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 5),
(10, 'Samsung Galaxy Watch 5 Pro', 500, 'Samsung Galaxy Watch 5 Pro.webp', 6, 'Южная Корея', 'Черный', 50, 'Умные часы с мониторингом здоровья и длительным временем работы.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 6),
(11, 'Samsung Galaxy Tab S8 Ultra', 900, 'Samsung Galaxy Tab S8 Ultra.webp', 9, 'Южная Корея', 'Черный', 30, 'Флагманский планшет с AMOLED экраном.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 9),
(12, 'Dell XPS 13', 1000, 'Dell XPS 13.webp', 2, 'США', 'Белый', 40, 'Компактный ноутбук с InfinityEdge экраном.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 2),
(13, 'Apple Watch Ultra', 800, 'Apple Watch Ultra.webp', 6, 'США', 'Серебристый', 40, 'Умные часы для спортсменов с GPS и водонепроницаемостью.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 6),
(14, 'LG OLED C2', 1500, 'LG OLED C2.webp', 3, 'Южная Корея', 'Черный', 15, 'Премиальный OLED-телевизор с идеальной чернотой.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 3),
(15, 'Samsung QLED Q80A', 1300, 'Samsung QLED Q80A.webp', 3, 'Южная Корея', 'Черный', 20, 'Флагманский телевизор с технологией Quantum Dot.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 3),
(16, 'Apple iPhone 14 Pro', 1000, 'Apple iPhone 14 Pro.webp', 1, 'США', 'Золотой', 30, 'Премиальный смартфон с дисплеем ProMotion.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 1),
(17, 'Apple iPad Pro 12.9', 1100, 'Apple iPad Pro 12.9.webp', 9, 'США', 'Серый', 25, 'Планшет с дисплеем Liquid Retina XDR.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 9),
(18, 'Чехол для Apple iPhone 14 Pro', 30, 'Чехол для Apple iPhone 14 Pro.webp', 4, 'Китай', 'Прозрачный', 80, 'Силиконовый чехол с защитой от ударов.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 4),
(19, 'Защитное стекло для Samsung Galaxy S23', 20, 'Защитное стекло для Samsung Galaxy S23.webp', 4, 'Китай', 'Прозрачный', 100, 'Защитное стекло с антибликовым покрытием.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 4),
(20, 'Робот-пылесос Xiaomi Roborock S7', 500, 'Робот-пылесос Xiaomi Roborock S7.webp', 8, 'Китай', 'Белый', 30, 'Автоматический пылесос с функцией влажной уборки.', '2025-04-24 14:44:01', '2025-04-24 14:44:01', 8);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_product_type_foreign` (`product_type`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_product_type_foreign` FOREIGN KEY (`product_type`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
