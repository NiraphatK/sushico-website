-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2025 at 08:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sushico`
--
CREATE DATABASE IF NOT EXISTS `sushico` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sushico`;

-- --------------------------------------------------------

--
-- Table structure for table `counter`
--

CREATE TABLE `counter` (
  `c_id` int(10) NOT NULL,
  `c_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `counter`
--

INSERT INTO `counter` (`c_id`, `c_date`) VALUES
(1, '2025-09-10 12:16:17'),
(2, '2025-09-10 12:16:31'),
(3, '2025-09-10 12:17:42'),
(4, '2025-09-10 12:28:41'),
(5, '2025-09-10 12:29:01'),
(6, '2025-09-10 12:30:34'),
(7, '2025-09-10 12:30:36'),
(8, '2025-09-10 12:32:07'),
(9, '2025-09-10 12:32:45'),
(10, '2025-09-10 12:33:20'),
(11, '2025-09-10 12:33:50'),
(12, '2025-09-10 12:34:02'),
(13, '2025-09-10 12:34:28'),
(14, '2025-09-10 12:35:35'),
(15, '2025-09-10 12:35:42'),
(16, '2025-09-10 12:36:09'),
(17, '2025-09-10 12:36:30'),
(18, '2025-09-10 12:37:33'),
(19, '2025-09-10 12:37:49'),
(20, '2025-09-10 12:38:54'),
(21, '2025-09-10 12:39:06'),
(22, '2025-09-10 12:39:22'),
(23, '2025-09-10 12:39:50'),
(24, '2025-09-10 12:40:00'),
(25, '2025-09-10 12:40:50'),
(26, '2025-09-10 12:41:12'),
(27, '2025-09-10 12:41:21'),
(28, '2025-09-10 12:41:23'),
(29, '2025-09-10 12:41:35'),
(30, '2025-09-10 12:41:56'),
(31, '2025-09-10 12:41:59'),
(32, '2025-09-10 12:42:01'),
(33, '2025-09-10 12:42:10'),
(34, '2025-09-10 12:42:34'),
(35, '2025-09-10 12:42:34'),
(36, '2025-09-10 12:42:59'),
(37, '2025-09-10 12:43:03'),
(38, '2025-09-10 12:43:18'),
(39, '2025-09-10 12:43:33'),
(40, '2025-09-10 12:43:39'),
(41, '2025-09-10 12:43:59'),
(42, '2025-09-10 12:44:38'),
(43, '2025-09-10 12:44:49'),
(44, '2025-09-10 12:45:21'),
(45, '2025-09-10 12:45:35'),
(46, '2025-09-10 12:46:18'),
(47, '2025-09-10 12:46:24'),
(48, '2025-09-10 12:46:47'),
(49, '2025-09-10 12:48:00'),
(50, '2025-09-10 12:48:14'),
(51, '2025-09-10 12:49:03'),
(52, '2025-09-10 12:49:04'),
(53, '2025-09-10 12:49:07'),
(54, '2025-09-10 12:49:15'),
(55, '2025-09-10 12:52:37'),
(56, '2025-09-10 12:52:54'),
(57, '2025-09-10 12:53:51'),
(58, '2025-09-10 12:53:52'),
(59, '2025-09-10 12:53:53'),
(60, '2025-09-10 12:55:53'),
(61, '2025-09-10 12:57:07'),
(62, '2025-09-10 13:00:05'),
(63, '2025-09-10 13:00:21'),
(64, '2025-09-10 13:00:28'),
(65, '2025-09-10 13:00:48'),
(66, '2025-09-10 13:01:33'),
(67, '2025-09-10 13:05:03'),
(68, '2025-09-10 13:06:45'),
(69, '2025-09-10 13:07:16'),
(70, '2025-09-10 13:07:53'),
(71, '2025-09-10 13:09:12'),
(72, '2025-09-10 13:09:21'),
(73, '2025-09-10 13:09:50'),
(74, '2025-09-10 13:10:01'),
(75, '2025-09-10 13:10:04'),
(76, '2025-09-10 13:14:14'),
(77, '2025-09-10 13:14:57'),
(78, '2025-09-10 13:15:03'),
(79, '2025-09-10 13:16:27'),
(80, '2025-09-10 13:16:34'),
(81, '2025-09-10 13:16:36'),
(82, '2025-09-10 13:16:40'),
(83, '2025-09-10 13:16:51'),
(84, '2025-09-10 13:16:53'),
(85, '2025-09-10 13:16:55'),
(86, '2025-09-10 13:17:11'),
(87, '2025-09-10 13:17:25'),
(88, '2025-09-10 13:17:31'),
(89, '2025-09-10 13:17:37'),
(90, '2025-09-10 13:17:41'),
(91, '2025-09-10 13:17:49'),
(92, '2025-09-10 13:18:06'),
(93, '2025-09-10 13:18:16'),
(94, '2025-09-10 13:18:24'),
(95, '2025-09-10 13:18:25'),
(96, '2025-09-10 13:18:54'),
(97, '2025-09-10 13:19:36'),
(98, '2025-09-10 13:21:44'),
(99, '2025-09-10 13:21:58'),
(100, '2025-09-10 13:22:12'),
(101, '2025-09-10 13:22:51'),
(102, '2025-09-10 13:23:02'),
(103, '2025-09-10 13:23:16'),
(104, '2025-09-10 13:24:18'),
(105, '2025-09-10 13:24:52'),
(106, '2025-09-10 13:25:18'),
(107, '2025-09-10 13:25:24'),
(108, '2025-09-10 13:25:27'),
(109, '2025-09-10 13:25:27'),
(110, '2025-09-10 13:26:06'),
(111, '2025-09-10 13:27:48'),
(112, '2025-09-10 13:28:05'),
(113, '2025-09-10 13:28:13'),
(114, '2025-09-10 13:28:26'),
(115, '2025-09-10 13:28:44'),
(116, '2025-09-10 13:29:38'),
(117, '2025-09-10 13:29:55'),
(118, '2025-09-10 13:30:04'),
(119, '2025-09-10 13:30:19'),
(120, '2025-09-10 13:30:26'),
(121, '2025-09-10 13:32:06'),
(122, '2025-09-10 13:32:17'),
(123, '2025-09-10 13:33:02'),
(124, '2025-09-10 13:33:14'),
(125, '2025-09-10 13:33:54'),
(126, '2025-09-10 13:34:34'),
(127, '2025-09-10 13:35:06'),
(128, '2025-09-10 13:35:14'),
(129, '2025-09-10 13:36:03'),
(130, '2025-09-10 13:36:15'),
(131, '2025-09-10 13:36:35'),
(132, '2025-09-10 13:36:47'),
(133, '2025-09-10 13:37:05'),
(134, '2025-09-10 13:38:25'),
(135, '2025-09-10 13:39:44'),
(136, '2025-09-10 13:40:00'),
(137, '2025-09-10 13:40:11'),
(138, '2025-09-10 13:40:17'),
(139, '2025-09-10 13:40:22'),
(140, '2025-09-10 13:40:53'),
(141, '2025-09-10 13:41:07'),
(142, '2025-09-10 13:44:17'),
(143, '2025-09-10 13:46:46'),
(144, '2025-09-10 13:48:26'),
(145, '2025-09-10 13:48:47'),
(146, '2025-09-10 13:50:10'),
(147, '2025-09-10 13:50:36'),
(148, '2025-09-10 13:51:40'),
(149, '2025-09-10 13:52:11'),
(150, '2025-09-10 13:52:15'),
(151, '2025-09-10 13:54:42'),
(152, '2025-09-10 13:55:07'),
(153, '2025-09-10 13:55:13'),
(154, '2025-09-10 13:55:19'),
(155, '2025-09-10 13:55:26'),
(156, '2025-09-10 14:02:51'),
(157, '2025-09-10 14:03:15'),
(158, '2025-09-10 14:03:30'),
(159, '2025-09-10 14:03:41'),
(160, '2025-09-10 14:04:01'),
(161, '2025-09-10 14:04:07'),
(162, '2025-09-10 14:04:20'),
(163, '2025-09-10 14:04:32'),
(164, '2025-09-10 14:04:43'),
(165, '2025-09-10 14:13:11'),
(166, '2025-09-10 14:13:23'),
(167, '2025-09-10 14:15:10'),
(168, '2025-09-10 14:15:19'),
(169, '2025-09-10 14:30:58'),
(170, '2025-09-10 14:31:36'),
(171, '2025-09-10 14:31:40'),
(172, '2025-09-10 14:32:08'),
(173, '2025-09-10 14:32:12'),
(174, '2025-09-10 14:32:25'),
(175, '2025-09-10 14:32:49'),
(176, '2025-09-10 14:32:50'),
(177, '2025-09-10 14:52:06'),
(178, '2025-09-10 14:52:46'),
(179, '2025-09-10 14:54:54'),
(180, '2025-09-10 17:57:16'),
(181, '2025-09-10 17:57:31'),
(182, '2025-09-10 17:57:51'),
(183, '2025-09-10 17:58:23'),
(184, '2025-09-10 17:58:41'),
(185, '2025-09-10 18:01:56'),
(186, '2025-09-10 18:02:05'),
(187, '2025-09-10 18:02:13'),
(188, '2025-09-10 18:03:01'),
(189, '2025-09-10 18:04:18'),
(190, '2025-09-10 18:06:05'),
(191, '2025-09-10 18:15:34'),
(192, '2025-09-10 18:17:28'),
(193, '2025-09-10 18:21:10'),
(194, '2025-09-10 18:21:47'),
(195, '2025-09-10 18:35:25'),
(196, '2025-09-10 18:38:57'),
(197, '2025-09-10 18:40:07'),
(198, '2025-09-10 18:41:45'),
(199, '2025-09-10 18:43:26'),
(200, '2025-09-10 18:44:37'),
(201, '2025-09-10 18:44:49');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `menu_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`menu_id`, `name`, `description`, `price`, `image_path`, `is_active`, `created_at`, `updated_at`) VALUES
(7, 'วากิวบิ๊กโรล', 'THAI WAGYU BEEF BIG ROLL', 30.00, 'uploads/menu/u7xUTTNtdOTWAlKm16djRQrffevCz4BdXs2HSQ9D.png', 1, '2025-09-09 19:47:16', '2025-09-11 01:10:28'),
(8, 'วากิวเอ๊กโรล', 'THAI WAGYU BEEF EGG ROLL', 30.00, 'uploads/menu/zQHp4QSoem7GPA7Pf1caJ0I652S4Ypn9LFuqVbzY.png', 1, '2025-09-09 19:47:16', '2025-09-11 01:10:03'),
(9, 'วากิวแซ่บ', 'THAI WAGYU BEEF EGG ROLL WITH THAI SPICY SAUCE', 30.00, 'uploads/menu/NSV2ou3ohzQmradzsBjwf84y710x6sZcOotqg4on.png', 1, '2025-09-09 19:47:16', '2025-09-11 01:08:40'),
(10, 'ซูชิวากิวสไปซี่', 'THAI WAGYU BEEF WITH SPICY CREAM SAUCE SUSHI', 30.00, 'uploads/menu/niDIs0gbAGaQ5wrVeT49BROp4hffXZqfMs0gtXl8.png', 1, '2025-09-09 19:47:16', '2025-09-11 01:07:24');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `party_size` tinyint(3) UNSIGNED NOT NULL,
  `seat_type` enum('BAR','TABLE') NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `status` enum('CONFIRMED','SEATED','COMPLETED','CANCELLED','NO_SHOW') DEFAULT 'CONFIRMED',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `settings_id` int(11) NOT NULL,
  `timezone` varchar(50) DEFAULT 'Asia/Bangkok',
  `cut_off_minutes` tinyint(3) UNSIGNED DEFAULT NULL,
  `grace_minutes` tinyint(3) UNSIGNED DEFAULT NULL,
  `buffer_minutes` tinyint(3) UNSIGNED DEFAULT NULL,
  `slot_granularity_minutes` tinyint(3) UNSIGNED DEFAULT NULL,
  `default_duration_minutes` tinyint(3) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`settings_id`, `timezone`, `cut_off_minutes`, `grace_minutes`, `buffer_minutes`, `slot_granularity_minutes`, `default_duration_minutes`, `created_at`, `updated_at`) VALUES
(1, 'Asia/Bangkok', 30, 15, 10, 15, 60, '2025-09-07 17:45:19', '2025-09-07 17:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `capacity` tinyint(3) UNSIGNED NOT NULL,
  `seat_type` enum('BAR','TABLE') NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(120) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('CUSTOMER','STAFF','ADMIN') DEFAULT 'CUSTOMER',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password_hash`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Test Test', 'test@gmail.com', '0999999999', '$2y$12$xBr8v5S81Fe.NuR42GLyO.XHtRUHUTn4oEhs2p4S47yOP01Opws82', 'CUSTOMER', 1, '2025-09-09 03:03:12', '2025-09-09 03:03:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `counter`
--
ALTER TABLE `counter`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `fk_reservation_user` (`user_id`),
  ADD KEY `fk_reservation_table` (`table_id`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`settings_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `phone_2` (`phone`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `counter`
--
ALTER TABLE `counter`
  MODIFY `c_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
