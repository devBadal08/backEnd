-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2024 at 08:07 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_02_20_112146_create_posts_table', 1),
(6, '2024_03_04_093858_create_permission_tables', 1),
(7, '2024_03_04_110244_user_seeder', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 1),
(5, 'App\\Models\\User', 1),
(6, 'App\\Models\\User', 2),
(6, 'App\\Models\\User', 3),
(6, 'App\\Models\\User', 4),
(6, 'App\\Models\\User', 5),
(6, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 7),
(6, 'App\\Models\\User', 8),
(6, 'App\\Models\\User', 9),
(6, 'App\\Models\\User', 10),
(6, 'App\\Models\\User', 11),
(6, 'App\\Models\\User', 12),
(6, 'App\\Models\\User', 13),
(7, 'App\\Models\\User', 2),
(7, 'App\\Models\\User', 6),
(7, 'App\\Models\\User', 8),
(7, 'App\\Models\\User', 13),
(8, 'App\\Models\\User', 2),
(8, 'App\\Models\\User', 6),
(8, 'App\\Models\\User', 8),
(8, 'App\\Models\\User', 13),
(9, 'App\\Models\\User', 2),
(9, 'App\\Models\\User', 6),
(9, 'App\\Models\\User', 8),
(9, 'App\\Models\\User', 13),
(10, 'App\\Models\\User', 2),
(10, 'App\\Models\\User', 6),
(10, 'App\\Models\\User', 8),
(10, 'App\\Models\\User', 13);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 8),
(2, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(3, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 12);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'manager.list', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(2, 'manager.view', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(3, 'manager.create', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(4, 'manager.update', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(5, 'manager.delete', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(6, 'user.list', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(7, 'user.view', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(8, 'user.create', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(9, 'user.update', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47'),
(10, 'user.delete', 'web', '2024-03-07 06:04:47', '2024-03-07 06:04:47');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'Token', '45e6db818a97c850588685f715dc5551a60dffc9cf350857be8bec3a14a7d8d1', '[\"*\"]', NULL, NULL, '2024-03-07 06:46:29', '2024-03-07 06:46:29'),
(2, 'App\\Models\\User', 2, 'Token', 'e9268f1695f17adf7445acf974c4ef96de10aae827b4a31ffba6da016e4b9a1f', '[\"*\"]', '2024-03-07 06:51:50', NULL, '2024-03-07 06:50:17', '2024-03-07 06:51:50'),
(3, 'App\\Models\\User', 2, 'Token', '4375aff5ca8238fc0624dc83cc2bcc957237924c300249a14a22a53bbba08cbb', '[\"*\"]', NULL, NULL, '2024-03-08 00:19:19', '2024-03-08 00:19:19'),
(4, 'App\\Models\\User', 2, 'Token', '439e21d1acdc43ee04eca816aa196eace27f0a023d91bc484e1291db20827597', '[\"*\"]', NULL, NULL, '2024-03-08 00:38:29', '2024-03-08 00:38:29'),
(5, 'App\\Models\\User', 1, 'Token', '37c690527f9bd30ba38d356fa8aa690b87ce562ae0e85f6d588e4ac908b42f92', '[\"*\"]', NULL, NULL, '2024-03-08 01:00:48', '2024-03-08 01:00:48'),
(6, 'App\\Models\\User', 6, 'Token', '8952cd7e23e5442b371eccf4b129f50a46bc0136ff26a2fe58a222d7c7462d97', '[\"*\"]', NULL, NULL, '2024-03-08 01:03:07', '2024-03-08 01:03:07'),
(7, 'App\\Models\\User', 1, 'Token', '8d48bfd88f17dd6a5f44569f8dba12c44951c7270a40902727c94a711fa9985a', '[\"*\"]', NULL, NULL, '2024-03-08 01:06:44', '2024-03-08 01:06:44'),
(8, 'App\\Models\\User', 8, 'Token', 'f9397320ac50d2f0c9170980125a4f04e14ac22cecf71cfbacc61aa0e06fe01c', '[\"*\"]', '2024-03-08 01:45:26', NULL, '2024-03-08 01:14:06', '2024-03-08 01:45:26'),
(9, 'App\\Models\\User', 1, 'Token', '22f9dcf833284b16a85ab68cb429d6628c013055523116c3703db275b69c8213', '[\"*\"]', '2024-03-08 01:57:13', NULL, '2024-03-08 01:55:19', '2024-03-08 01:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2024-03-07 06:04:50', '2024-03-07 06:04:50'),
(2, 'manager', 'web', '2024-03-07 06:04:51', '2024-03-07 06:04:51'),
(3, 'user', 'web', '2024-03-07 06:04:51', '2024-03-07 06:04:51');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 2),
(6, 3),
(7, 2),
(8, 2),
(9, 2),
(10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `phone`, `email`, `role`, `created_by`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL, 'admin@admin.com', NULL, NULL, NULL, '$2y$12$2aUtu6ED2ACg6TKzVdLEae2O1WfSFOzydVcX0GpcnMLNATUE92shO', NULL, '2024-03-07 06:04:50', '2024-03-07 06:04:50'),
(2, 'manager', NULL, NULL, 'manager@manager.com', NULL, NULL, NULL, '$2y$12$f6xxCqc6GkFIbWaOFbiMxuZv3kgJTALWEYkMd2jjg9mfAZAxrp9Uy', NULL, '2024-03-07 06:04:51', '2024-03-07 06:04:51'),
(3, 'user', NULL, NULL, 'user@user.com', NULL, NULL, NULL, '$2y$12$6SS.5dhTYp6E9ThPyIwGweP9h9TDK7WHlFUxoSClMB6l/c4WgcuvG', NULL, '2024-03-07 06:04:51', '2024-03-07 06:04:51'),
(6, 'manager1', NULL, NULL, 'manager1@gmail.com', 'manager', NULL, NULL, '$2y$12$qPcrT/oIvkHydpStgV59xu7amxqs7u8RvivldkavZN/wytIB2BYxW', NULL, '2024-03-08 01:02:31', '2024-03-08 01:02:31'),
(8, 'manager2', NULL, NULL, 'manager2@gmail.com', 'manager', NULL, NULL, '$2y$12$uAuGq6B4F6NuYzlwj8uvUOybWalXwe9NSQZksvHDmqlM9CpFgvFvO', NULL, '2024-03-08 01:07:46', '2024-03-08 01:07:46'),
(9, 'div2', NULL, NULL, 'div2@gmail.com', 'user', NULL, NULL, '$2y$12$bnfJpGzP/LxyvmdeP9yZaulQjPn9D.suL4qsmrIat3lNAuTcDt/o6', NULL, '2024-03-08 01:16:47', '2024-03-08 01:16:47'),
(10, 'urvish2', NULL, NULL, 'urvish2@gmail.com', 'user', NULL, NULL, '$2y$12$TMAwR34a4bGGiVGF9/au5uJd/meJdXfOfgqzvJ/pANCzF1/UsZtt.', NULL, '2024-03-08 01:21:43', '2024-03-08 01:21:43'),
(11, 'pratham22', NULL, NULL, 'pratham22@gmail.com', 'user', 8, NULL, '$2y$12$NPgAq3j0QM5qXU4jFdm4gufM0zEOiwxQ8uQb7UhqSRAlqvBhAXLeG', NULL, '2024-03-08 01:32:02', '2024-03-08 01:32:02'),
(12, 'pratham222', NULL, NULL, 'pratham222@gmail.com', 'user', 8, NULL, '$2y$12$qjspgMr1TXug2tJDVy56aOmpOByZNHDGj26ibuhk7CWu7UmkwmUiG', NULL, '2024-03-08 01:45:27', '2024-03-08 01:45:27'),
(13, 'manager3', NULL, NULL, 'manager3@gmail.com', 'manager', NULL, NULL, '$2y$12$8fGfWTfv6Mast2H/MdcyduUMrvy.YdDFHaS8Cr72s6bhS7n6RUBHm', NULL, '2024-03-08 01:55:44', '2024-03-08 01:55:44');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
