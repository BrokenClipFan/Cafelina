-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 14, 2026 at 05:42 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cafelina`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-gaylepalame@gmail.cum|127.0.0.1', 'i:1;', 1783415983),
('laravel-cache-gaylepalame@gmail.cum|127.0.0.1:timer', 'i:1783415983;', 1783415983);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `icon`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Hot Drinks', 'bi-cup-hot', 0, '2026-07-05 23:26:15', '2026-07-06 02:21:59'),
(3, 'Happy Meal', 'bi-emoji-smile', 1, '2026-07-05 23:40:07', '2026-07-06 02:21:59'),
(5, 'Food', 'bi-egg-fried', 3, '2026-07-06 00:02:59', '2026-07-06 02:21:59'),
(6, 'Cold Drinks', 'bi-snow', 4, '2026-07-06 02:21:53', '2026-07-06 02:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedules`
--

CREATE TABLE `employee_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `days` json NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `station_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Front Counter',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `category`, `position`, `price`, `created_at`, `updated_at`) VALUES
(1, 'coco marti', 'Hot Drinks', 2, 25.00, '2026-07-05 23:26:32', '2026-07-06 02:22:10'),
(2, 'HOT MUKA', 'Hot Drinks', 0, 27.00, '2026-07-05 23:26:51', '2026-07-06 02:22:10'),
(3, '3 In 1 Cape Stick', 'Hot Drinks', 1, 5.00, '2026-07-05 23:59:59', '2026-07-06 02:22:10'),
(4, 'Me', 'Hot Drinks', 3, 2.00, '2026-07-06 00:01:57', '2026-07-06 02:22:10'),
(5, 'MICHEAL', 'Hot Drinks', 4, 234.00, '2026-07-06 00:02:30', '2026-07-06 02:22:13'),
(6, 'EGG WITH RICE', 'Food', 5, 25.00, '2026-07-06 00:03:10', '2026-07-06 00:03:10'),
(7, 'MILO', 'Hot Drinks', 6, 10.00, '2026-07-06 02:22:35', '2026-07-06 02:22:35'),
(8, 'JOLLY HOTDOG', 'Happy Meal', 7, 45.00, '2026-07-13 00:08:27', '2026-07-13 00:08:27'),
(9, 'ICED WATER', 'Cold Drinks', 8, 15.00, '2026-07-13 00:08:46', '2026-07-13 00:08:46');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_19_040111_create_categories_table', 1),
(5, '2026_06_19_050409_create_items_table', 1),
(6, '2026_06_29_103315_create_purchases_table', 1),
(7, '2026_06_30_060538_create_purchase_items_table', 1),
(8, '2026_06_30_063700_add_role_to_users_table', 1),
(9, '2026_06_30_095905_add_name_to_purchases_table', 1),
(10, '2026_06_30_152849_create_queue_lists_table', 1),
(11, '2026_07_06_071859_add_user_id_to_queue_lists', 1),
(12, '2026_07_07_062206_create_settings_table', 2),
(13, '2026_07_07_083926_create_schedules_table', 3),
(14, '2026_07_07_090826_create_schedules_table', 4),
(15, '2026_07_13_083852_add_online_status_to_users_table', 5),
(16, '2026_07_13_101543_create_employee_schedules_table', 6),
(17, '2026_07_13_104519_update_user_id_foreign_on_purchases_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax` decimal(8,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `subtotal`, `tax`, `total`, `payment_method`, `status`, `created_at`, `updated_at`, `name`) VALUES
(3, 1, 2.00, 0.08, 2.16, 'Cash', 'completed', '2026-07-06 00:08:41', '2026-07-06 00:08:41', '7003'),
(4, 1, 25.00, 0.08, 27.00, 'Cash', 'completed', '2026-07-06 00:11:25', '2026-07-06 00:11:25', '54169'),
(6, 1, 27.00, 0.08, 29.16, 'Cash', 'completed', '2026-07-06 00:12:22', '2026-07-06 00:12:22', 'Rinel order'),
(7, 1, 2.00, 0.08, 2.16, 'Cash', 'completed', '2026-07-06 00:22:40', '2026-07-06 00:22:40', '61801'),
(8, 1, 234.00, 0.08, 252.72, 'Cash', 'completed', '2026-07-06 00:23:03', '2026-07-06 00:23:03', '4407'),
(9, 1, 2.00, 0.08, 2.16, 'Cash', 'completed', '2026-07-06 00:23:46', '2026-07-06 00:23:46', '78313'),
(10, 1, 27.00, 0.08, 29.16, 'Cash', 'completed', '2026-07-06 00:54:51', '2026-07-06 00:54:51', '82631'),
(11, 1, 52.00, 0.08, 56.16, 'Cash', 'completed', '2026-07-06 01:02:39', '2026-07-06 01:02:39', 'Gayle'),
(12, 1, 30.00, 0.08, 32.40, 'Cash', 'completed', '2026-07-06 01:02:44', '2026-07-06 01:02:44', 'Johny'),
(13, 1, 261.00, 0.08, 281.88, 'Cash', 'completed', '2026-07-06 01:04:20', '2026-07-06 01:04:20', 'Micheal'),
(14, 1, 7.00, 0.08, 7.56, 'Cash', 'completed', '2026-07-06 01:04:35', '2026-07-06 01:04:35', 'Reaven'),
(15, 1, 30.00, 0.08, 32.40, 'Cash', 'completed', '2026-07-06 01:04:52', '2026-07-06 01:04:52', 'Charise'),
(36, 1, 30.00, 0.08, 32.40, 'Cash', 'completed', '2026-07-06 21:49:39', '2026-07-06 21:49:39', '70147'),
(44, 1, 27.00, 0.08, 29.16, 'Cash', 'completed', '2026-07-12 23:32:02', '2026-07-12 23:32:02', '99750'),
(45, 1, 32.00, 0.08, 34.56, 'Cash', 'completed', '2026-07-12 23:32:05', '2026-07-12 23:32:05', '42289'),
(46, 1, 170.00, 0.08, 183.60, 'Cash', 'completed', '2026-07-12 23:32:35', '2026-07-12 23:32:35', '50902'),
(47, 1, 602.00, 0.08, 650.16, 'Cash', 'completed', '2026-07-12 23:32:42', '2026-07-12 23:32:42', '99719'),
(48, 1, 2.00, 0.08, 2.16, 'Cash', 'completed', '2026-07-12 23:33:08', '2026-07-12 23:33:08', '59798');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `count` int UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `name`, `category`, `price`, `count`, `created_at`, `updated_at`) VALUES
(1, 3, 'Me', 'Hot Drinks', 2.00, 1, '2026-07-06 00:08:41', '2026-07-06 00:08:41'),
(2, 4, 'coco marti', 'Hot Drinks', 25.00, 1, '2026-07-06 00:11:25', '2026-07-06 00:11:25'),
(5, 6, 'HOT MUKA', 'Hot Drinks', 27.00, 1, '2026-07-06 00:12:22', '2026-07-06 00:12:22'),
(6, 7, 'Me', 'Hot Drinks', 2.00, 1, '2026-07-06 00:22:40', '2026-07-06 00:22:40'),
(7, 8, 'MICHEAL', 'Hot Drinks', 234.00, 1, '2026-07-06 00:23:03', '2026-07-06 00:23:03'),
(8, 9, 'Me', 'Hot Drinks', 2.00, 1, '2026-07-06 00:23:46', '2026-07-06 00:23:46'),
(9, 10, 'HOT MUKA', 'Hot Drinks', 27.00, 1, '2026-07-06 00:54:51', '2026-07-06 00:54:51'),
(10, 11, 'HOT MUKA', 'Hot Drinks', 27.00, 1, '2026-07-06 01:02:39', '2026-07-06 01:02:39'),
(11, 11, 'coco marti', 'Hot Drinks', 25.00, 1, '2026-07-06 01:02:39', '2026-07-06 01:02:39'),
(12, 12, 'coco marti', 'Hot Drinks', 25.00, 1, '2026-07-06 01:02:44', '2026-07-06 01:02:44'),
(13, 12, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 1, '2026-07-06 01:02:44', '2026-07-06 01:02:44'),
(14, 13, 'HOT MUKA', 'Hot Drinks', 27.00, 1, '2026-07-06 01:04:20', '2026-07-06 01:04:20'),
(15, 13, 'MICHEAL', 'Hot Drinks', 234.00, 1, '2026-07-06 01:04:20', '2026-07-06 01:04:20'),
(16, 14, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 1, '2026-07-06 01:04:35', '2026-07-06 01:04:35'),
(17, 14, 'Me', 'Hot Drinks', 2.00, 1, '2026-07-06 01:04:35', '2026-07-06 01:04:35'),
(18, 15, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 1, '2026-07-06 01:04:52', '2026-07-06 01:04:52'),
(19, 15, 'coco marti', 'Hot Drinks', 25.00, 1, '2026-07-06 01:04:52', '2026-07-06 01:04:52'),
(46, 36, 'coco marti', 'Hot Drinks', 25.00, 1, '2026-07-06 21:49:39', '2026-07-06 21:49:39'),
(47, 36, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 1, '2026-07-06 21:49:39', '2026-07-06 21:49:39'),
(57, 44, 'HOT MUKA', 'Hot Drinks', 27.00, 1, '2026-07-12 23:32:02', '2026-07-12 23:32:02'),
(58, 45, 'Me', 'Hot Drinks', 2.00, 1, '2026-07-12 23:32:05', '2026-07-12 23:32:05'),
(59, 45, 'coco marti', 'Hot Drinks', 25.00, 1, '2026-07-12 23:32:05', '2026-07-12 23:32:05'),
(60, 45, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 1, '2026-07-12 23:32:05', '2026-07-12 23:32:05'),
(61, 46, 'HOT MUKA', 'Hot Drinks', 27.00, 3, '2026-07-12 23:32:35', '2026-07-12 23:32:35'),
(62, 46, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 2, '2026-07-12 23:32:35', '2026-07-12 23:32:35'),
(63, 46, 'coco marti', 'Hot Drinks', 25.00, 3, '2026-07-12 23:32:35', '2026-07-12 23:32:35'),
(64, 46, 'Me', 'Hot Drinks', 2.00, 2, '2026-07-12 23:32:35', '2026-07-12 23:32:35'),
(65, 47, 'coco marti', 'Hot Drinks', 25.00, 2, '2026-07-12 23:32:42', '2026-07-12 23:32:42'),
(66, 47, '3 In 1 Cape Stick', 'Hot Drinks', 5.00, 2, '2026-07-12 23:32:42', '2026-07-12 23:32:42'),
(67, 47, 'HOT MUKA', 'Hot Drinks', 27.00, 2, '2026-07-12 23:32:42', '2026-07-12 23:32:42'),
(68, 47, 'MICHEAL', 'Hot Drinks', 234.00, 2, '2026-07-12 23:32:42', '2026-07-12 23:32:42'),
(69, 47, 'MILO', 'Hot Drinks', 10.00, 2, '2026-07-12 23:32:42', '2026-07-12 23:32:42'),
(70, 48, 'Me', 'Hot Drinks', 2.00, 1, '2026-07-12 23:33:08', '2026-07-12 23:33:08');

-- --------------------------------------------------------

--
-- Table structure for table `queue_lists`
--

CREATE TABLE `queue_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `order_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3uyqJq1Ia79vPVjdZv8Yf09DobTUFAUy0oqF53vn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJWekg5VFo4VW05UDdvU0pud3VISE1qWjNlQXV3VzVZQUVSazNCYU9BIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL2dldFwvcXVldWUiLCJyb3V0ZSI6bnVsbH0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1783939679),
('D73syGjdrVmi3lzT3hIQovZb3GdBdiGSlK5Xs14v', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'eyJfdG9rZW4iOiJCaTdaMGNKakpqTHN0MndSeXhaZzdqTDJDNUV3TUt5bHRycnRBbjZtIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2dldFwvcXVldWUiLCJyb3V0ZSI6bnVsbH0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozfQ==', 1783937090);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'tax', '0.08', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `online_status` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `online_status`) VALUES
(1, 'Gayle parame', 'gaylepalame@gwapo.cum', NULL, '$2y$12$S/.qbQW4YXIPdbsnG/2zvegHN5ynkyX7uS.9XwvnatwbB27esHmwq', NULL, '2026-07-05 23:22:54', '2026-07-06 21:57:07', 'admin', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_schedules_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `queue_lists`
--
ALTER TABLE `queue_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `queue_lists_purchase_id_foreign` (`purchase_id`),
  ADD KEY `queue_lists_user_id_foreign` (`user_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_user_id_shift_date_index` (`user_id`,`shift_date`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `queue_lists`
--
ALTER TABLE `queue_lists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD CONSTRAINT `employee_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `queue_lists`
--
ALTER TABLE `queue_lists`
  ADD CONSTRAINT `queue_lists_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `queue_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
