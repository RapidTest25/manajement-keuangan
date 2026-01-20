-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Jan 20, 2026 at 02:32 PM
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
-- Database: `management_keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_activation_attempts`
--

CREATE TABLE `auth_activation_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups`
--

CREATE TABLE `auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups`
--

INSERT INTO `auth_groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Site Administrator'),
(2, 'user', 'Regular User');

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_permissions`
--

CREATE TABLE `auth_groups_permissions` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `group_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`group_id`, `user_id`) VALUES
(1, 3),
(1, 3),
(1, 3),
(1, 3),
(1, 3),
(2, 3),
(2, 3),
(2, 6),
(2, 6),
(2, 17),
(2, 22);

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `email`, `user_id`, `date`, `success`) VALUES
(1, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-03 09:07:47', 1),
(2, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 16:31:43', 1),
(3, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 17:10:09', 1),
(4, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 18:17:04', 1),
(5, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 18:19:28', 1),
(6, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 18:19:43', 1),
(7, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 18:53:58', 1),
(8, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 18:56:19', 1),
(9, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-05 19:07:15', 1),
(10, '127.0.0.1', 'testing1', NULL, '2025-05-05 21:11:28', 0),
(11, '127.0.0.1', 'aong@gmail.com', 5, '2025-05-05 21:12:17', 1),
(12, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-05 22:09:37', 1),
(13, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-06 12:10:05', 1),
(14, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-06 18:28:59', 1),
(15, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-06 22:22:36', 1),
(16, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-06 22:30:23', 1),
(17, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-07 07:54:47', 1),
(18, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-07 08:13:18', 1),
(19, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-07 10:02:35', 1),
(20, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-07 10:03:38', 1),
(21, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-07 10:04:52', 1),
(22, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-07 10:07:02', 1),
(23, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-07 15:00:41', 1),
(24, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-07 15:10:27', 1),
(25, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-07 18:30:06', 1),
(26, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-07 22:22:30', 1),
(27, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-08 04:36:42', 1),
(28, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-08 04:55:18', 1),
(29, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-08 05:06:13', 1),
(30, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-08 11:55:54', 1),
(31, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-08 12:02:09', 1),
(32, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 04:35:01', 1),
(33, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 05:37:48', 1),
(34, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 05:37:56', 1),
(35, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 05:38:04', 1),
(36, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 05:38:38', 1),
(37, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 05:38:53', 1),
(38, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 09:55:56', 1),
(39, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 09:59:38', 1),
(40, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:00:46', 1),
(41, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:01:03', 1),
(42, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:01:16', 1),
(43, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:12:37', 1),
(44, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:13:21', 1),
(45, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:19:46', 1),
(46, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:30:22', 1),
(47, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:35:27', 1),
(48, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 10:35:54', 1),
(49, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 11:05:07', 1),
(50, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 11:05:48', 1),
(51, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 11:10:32', 1),
(52, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 17:41:38', 1),
(53, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 17:46:05', 1),
(54, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 17:55:11', 1),
(55, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 18:15:13', 1),
(56, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 18:20:53', 1),
(57, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 18:24:26', 1),
(58, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 18:25:30', 1),
(59, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 18:25:55', 1),
(60, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 18:26:15', 1),
(61, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 18:35:17', 1),
(62, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 18:36:25', 1),
(63, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 18:39:52', 1),
(64, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 20:00:31', 1),
(65, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 21:55:09', 1),
(66, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:02:12', 1),
(67, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:06:57', 1),
(68, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:07:01', 1),
(69, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:07:27', 1),
(70, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:08:46', 1),
(71, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:12:21', 1),
(72, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-09 22:18:08', 1),
(73, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 22:19:23', 1),
(74, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 22:21:50', 1),
(75, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-09 22:26:53', 1),
(76, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 06:37:51', 1),
(77, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 07:09:49', 1),
(78, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:10:32', 1),
(79, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:20:46', 1),
(80, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:22:11', 1),
(81, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:26:37', 1),
(82, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:29:14', 1),
(83, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 07:45:06', 1),
(84, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:45:31', 1),
(85, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:54:26', 1),
(86, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 07:57:50', 1),
(87, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 08:04:30', 1),
(88, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 08:05:51', 1),
(89, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 08:06:33', 1),
(90, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 08:14:50', 1),
(91, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 08:31:59', 1),
(92, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 08:57:02', 1),
(93, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 19:07:14', 1),
(94, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 21:35:53', 1),
(95, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-10 21:51:51', 1),
(96, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-10 21:55:33', 1),
(97, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-11 07:57:55', 1),
(98, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-11 08:11:36', 1),
(99, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-11 09:13:21', 1),
(100, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-11 09:47:47', 1),
(101, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-11 17:12:32', 1),
(102, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-12 08:32:31', 1),
(103, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-12 10:23:57', 1),
(104, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-12 18:50:12', 1),
(105, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-12 20:17:42', 1),
(106, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-13 07:55:56', 1),
(107, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-19 15:23:37', 1),
(108, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-20 15:15:15', 1),
(109, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-21 23:06:18', 1),
(110, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-22 00:58:22', 1),
(111, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-22 09:42:36', 1),
(112, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-22 09:42:37', 1),
(113, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-22 23:12:24', 1),
(114, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-22 23:27:07', 1),
(115, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-24 09:12:18', 1),
(116, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-24 09:23:53', 1),
(117, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-25 01:18:00', 1),
(118, '127.0.0.1', 'aong@gmail.com', 6, '2025-05-25 04:46:24', 1),
(119, '127.0.0.1', 'ahmadkhadifar@gmail.com', 3, '2025-05-25 15:38:15', 1),
(120, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-25 21:13:47', 1),
(121, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-26 00:49:58', 1),
(122, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-26 15:21:17', 1),
(123, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-27 01:54:53', 1),
(124, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-27 04:03:46', 1),
(125, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-27 04:08:36', 1),
(126, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-27 15:10:42', 1),
(127, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-28 20:09:08', 1),
(128, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 01:17:48', 1),
(129, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 01:20:20', 1),
(130, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 01:21:22', 1),
(131, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 01:23:36', 1),
(132, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 01:32:12', 1),
(133, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 04:10:09', 1),
(134, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 07:17:35', 1),
(135, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 07:20:03', 1),
(136, '::1', 'aong@gmail.com', 6, '2025-05-29 07:20:30', 1),
(137, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 07:28:56', 1),
(138, '::1', 'aong@gmail.com', 6, '2025-05-29 07:51:03', 1),
(139, '::1', 'aong@gmail.com', 6, '2025-05-29 08:10:24', 1),
(140, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 08:11:47', 1),
(141, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-05-29 08:33:15', 1),
(142, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-19 12:53:18', 1),
(143, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-19 18:50:38', 1),
(144, '::1', 'ahmadkhadifar@gmail.com', NULL, '2025-07-20 13:47:18', 0),
(145, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-20 13:47:29', 1),
(146, '::1', 'gilang@gmail.com', NULL, '2025-07-20 16:32:33', 0),
(147, '::1', 'gilang@gmail.com', NULL, '2025-07-20 16:32:43', 0),
(148, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-21 16:18:58', 1),
(149, '::1', 'gilangmugi', NULL, '2025-07-21 17:22:02', 0),
(150, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-21 17:25:03', 1),
(151, '::1', 'gilangmugi', NULL, '2025-07-21 17:26:46', 0),
(152, '::1', 'aufahoki', NULL, '2025-07-21 17:30:36', 0),
(153, '::1', 'aufahoki', NULL, '2025-07-21 17:30:43', 0),
(154, '::1', 'aufahoki', NULL, '2025-07-21 17:30:52', 0),
(155, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-21 20:52:48', 1),
(156, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-22 03:49:33', 1),
(157, '::1', 'aong123', NULL, '2025-07-22 04:26:26', 0),
(159, '::1', 'aryaz@gmail.com', NULL, '2025-07-22 05:30:33', 0),
(160, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-22 11:03:52', 1),
(161, '::1', 'testing1', NULL, '2025-07-22 12:56:08', 0),
(162, '::1', 'testing1', NULL, '2025-07-22 13:00:51', 0),
(163, '::1', 'testing1@gmail.com', 21, '2025-07-22 13:02:18', 1),
(164, '::1', 'testing1', NULL, '2025-07-22 13:07:00', 0),
(165, '::1', 'ahmadkhadifar@gmail.com', 3, '2025-07-22 18:48:17', 1),
(166, '::1', 'aong123@gmail.com', 16, '2025-07-22 21:13:46', 1),
(167, '::1', 'ahmadkhadifar@gmail.com', 3, '2026-01-16 05:27:33', 1),
(168, '::1', 'ahmadkhadifar@gmail.com', 3, '2026-01-16 18:33:23', 1),
(169, '::1', 'ahmadkhadifar@gmail.com', 3, '2026-01-20 17:40:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions`
--

CREATE TABLE `auth_permissions` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_permissions`
--

INSERT INTO `auth_permissions` (`id`, `name`, `description`) VALUES
(1, 'manage-user', 'Manage All Users'),
(2, 'manage-profile', 'Manage User\'s Profile');

-- --------------------------------------------------------

--
-- Table structure for table `auth_reset_attempts`
--

CREATE TABLE `auth_reset_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_users_permissions`
--

CREATE TABLE `auth_users_permissions` (
  `user_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `permission_id` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_users_permissions`
--

INSERT INTO `auth_users_permissions` (`user_id`, `permission_id`) VALUES
(3, 1),
(3, 1),
(3, 2),
(3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `biaya_efektif`
--

CREATE TABLE `biaya_efektif` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `kategori` varchar(55) NOT NULL,
  `nama_biaya` varchar(55) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `frekuensi` enum('harian','mingguan','bulanan','tahunan') NOT NULL DEFAULT 'bulanan',
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biaya_efektif`
--

INSERT INTO `biaya_efektif` (`id`, `user_id`, `kategori`, `nama_biaya`, `jumlah`, `frekuensi`, `tanggal_mulai`, `tanggal_selesai`, `is_active`, `created_at`, `updated_at`) VALUES
(20, 3, 'Rumah', '331', 4252.00, 'harian', '2025-05-10', '0000-00-00', 1, '2025-05-12 10:43:57', '2025-05-12 10:43:57'),
(22, 3, 'JAJAN', 'JAJAN', 50000.00, 'mingguan', '2025-05-23', '0000-00-00', 1, '2025-05-23 02:49:43', '2025-05-23 02:49:43'),
(24, 3, 'Rumah', 'Cicilan', 3000000.00, 'bulanan', '2025-07-22', NULL, 1, '2025-07-22 21:28:02', '2025-07-22 21:28:02');

-- --------------------------------------------------------

--
-- Table structure for table `budget_settings`
--

CREATE TABLE `budget_settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(55) NOT NULL,
  `daily_budget` decimal(15,2) NOT NULL DEFAULT 100000.00,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `budget_settings`
--

INSERT INTO `budget_settings` (`id`, `username`, `daily_budget`, `created_at`, `updated_at`) VALUES
(14, 'aong123', 300000.00, '2025-05-10 23:40:27', '2025-05-10 23:40:27'),
(21, 'aong098', 100000.00, '2025-07-22 04:27:43', '2025-07-22 04:27:43'),
(22, 'testing1', 100000.00, '2025-07-22 13:02:19', '2025-07-22 13:02:19'),
(30, 'ahmadkhadifar', 500000.00, '2025-07-22 19:32:55', '2025-07-22 19:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(55) NOT NULL,
  `type` enum('EXPENSE','INCOME') NOT NULL DEFAULT 'EXPENSE',
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cicilan`
--

CREATE TABLE `cicilan` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(55) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `total_amount` decimal(15,2) DEFAULT NULL,
  `monthly_amount` decimal(15,2) DEFAULT NULL,
  `tenor` int(11) DEFAULT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `remaining_amount` decimal(15,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('active','completed') DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cicilan`
--

INSERT INTO `cicilan` (`id`, `user_id`, `name`, `type`, `total_amount`, `monthly_amount`, `tenor`, `paid_amount`, `remaining_amount`, `start_date`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'beat', 'Kendaraan', 2500000.00, 650000.00, 24, 2500000.00, 0.00, '2025-05-05', '', 'completed', '2025-05-11 12:16:17', '2025-05-11 12:17:24'),
(2, 3, 'Komputer', 'Elektronik', 2400000.00, 150000.00, 16, 450000.00, 1950000.00, '2025-05-05', '', 'active', '2025-05-11 12:24:10', '2025-05-20 02:36:47'),
(3, 3, 'beat', 'Kendaraan', 18658164.00, 444242.00, 42, 1776968.00, 16881196.00, '2025-05-07', 'bakds', 'active', '2025-05-11 12:25:57', '2025-05-29 08:01:47'),
(5, 3, 'beat', 'Kendaraan', 75000.00, 25000.00, 3, 50000.00, 25000.00, '2025-05-23', NULL, 'active', '2025-05-23 02:32:44', '2025-05-29 08:01:45'),
(6, 3, 'Komputer', 'Elektronik', 399996.00, 66666.00, 6, 66666.00, 333330.00, '2025-05-18', NULL, 'active', '2025-05-23 02:37:57', '2025-05-29 08:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `debt_notes`
--

CREATE TABLE `debt_notes` (
  `id` int(5) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `borrowType` varchar(10) DEFAULT NULL,
  `application` varchar(55) DEFAULT NULL,
  `loan_amount` int(11) DEFAULT NULL,
  `payment_amount` int(11) NOT NULL,
  `payment_period` varchar(10) NOT NULL,
  `loan_duration` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(10) DEFAULT 'active',
  `lender_category` varchar(50) DEFAULT NULL,
  `lender_name` varchar(55) DEFAULT NULL,
  `borrower_name` varchar(55) DEFAULT NULL,
  `amount_paid` int(11) DEFAULT NULL,
  `payments` text DEFAULT NULL,
  `payments_count` int(11) DEFAULT 0,
  `loan_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `debt_notes`
--

INSERT INTO `debt_notes` (`id`, `user_id`, `type`, `borrowType`, `application`, `loan_amount`, `payment_amount`, `payment_period`, `loan_duration`, `due_date`, `description`, `status`, `lender_category`, `lender_name`, `borrower_name`, `amount_paid`, `payments`, `payments_count`, `loan_date`, `created_at`, `updated_at`) VALUES
(29, 3, 'borrowing', 'online', 'Spinjam', 900000, 300000, '', 3, '2025-05-07', 'Untuk Kebutuhan', 'paid', NULL, NULL, NULL, 900000, '[{\"date\":\"2025-05-27\",\"amount\":300000},{\"date\":\"2025-05-29\",\"amount\":300000},{\"date\":\"2025-05-29\",\"amount\":300000}]', 3, '2025-05-21', '2025-05-26 18:57:37', '2025-05-29 01:02:01'),
(30, 3, 'borrowing', 'offline', NULL, 510000, 0, '', NULL, NULL, '100', 'active', 'teman', 'samsul', NULL, 10000, '[{\"date\":\"2025-05-27\",\"amount\":10000}]', 0, NULL, '2025-05-26 21:04:07', '2025-05-26 21:04:59'),
(31, 3, 'lending', NULL, NULL, 50000, 0, '', NULL, NULL, 'depo', 'active', NULL, NULL, 'celo', 25000, '[{\"date\":\"2025-05-27\",\"amount\":5000},{\"date\":\"2025-05-28\",\"amount\":20000}]', 0, '2025-05-26', '2025-05-26 21:04:41', '2025-05-28 13:09:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2017-11-20-223112', 'Myth\\Auth\\Database\\Migrations\\CreateAuthTables', 'default', 'Myth\\Auth', 1746211308, 1),
(2, '2017-11-20-223112', 'App\\Database\\Migrations\\CreateAuthTables', 'default', 'App', 1746475360, 2),
(11, '2024-01-20-223112', 'App\\Database\\Migrations\\CreateBudgetSettings', 'default', 'App', 1746788499, 3),
(12, '2024-01-20-223112', 'App\\Database\\Migrations\\CreateSavingsTables', 'default', 'App', 1746788499, 3),
(13, '2024-01-20-223112', 'App\\Database\\Migrations\\DebtNotes', 'default', 'App', 1746788499, 3),
(14, '2025-05-09-123000', 'App\\Database\\Migrations\\AddRoleColumn', 'default', 'App', 1746788557, 4),
(15, '2025-05-22-000001', 'App\\Database\\Migrations\\CreateSettingsTable', 'default', 'App', 1747849389, 5),
(16, '2026-01-16-000001', 'App\\Database\\Migrations\\CreateSubscriptionsTables', 'default', 'App', 1768516232, 6);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `role` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `rating` int(1) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `role`, `content`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Edgar', 'Boss', 'Aplikasi yang sangat membantu untuk mengatur keuangan. Sekarang saya bisa menabung lebih baik!', 5, 'active', '2025-05-25 01:51:15', '2025-05-25 01:51:58'),
(12, 'Samsul', 'Mahasiswa', 'Fitur kategori dan laporan keuangannya lengkap. Cocok untuk yang mau mulai mengelola keuangan.', 4, 'active', '2025-05-25 01:58:10', '2025-05-25 01:58:17'),
(14, 'Rizky Arbian', 'Dosen Pembimbing', 'Design simple sangat mudah digunakan, dan banyak fitur', 5, 'active', '2025-05-29 07:18:16', '2025-05-29 07:18:16'),
(15, 'Celo', 'Pengusaha Batu Bara', 'Sangat membantu dalam mangament keuangan saya', 5, 'active', '2025-05-29 08:32:40', '2025-05-29 08:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_amount` decimal(15,2) NOT NULL,
  `daily_amount` decimal(15,2) NOT NULL,
  `wish_target` varchar(55) NOT NULL,
  `description` text DEFAULT NULL,
  `saved_amount` decimal(15,2) DEFAULT 0.00,
  `payment_count` int(11) DEFAULT 0,
  `total_days_needed` int(11) DEFAULT 0,
  `start_date` date NOT NULL,
  `target_date` date DEFAULT NULL,
  `is_achieved` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `savings`
--

INSERT INTO `savings` (`id`, `user_id`, `target_amount`, `daily_amount`, `wish_target`, `description`, `saved_amount`, `payment_count`, `total_days_needed`, `start_date`, `target_date`, `is_achieved`, `created_at`, `updated_at`) VALUES
(3, 3, 5000000.00, 25000.00, 'Rumah', 'Beli Rumah di Monopoli', 50000.00, 2, 200, '2025-05-29', NULL, 0, '2025-05-29 03:23:52', '2025-05-29 07:54:50'),
(4, 6, 5000000.00, 25000.00, 'membeli keyboard', 'd', 25000.00, 1, 200, '2025-05-29', NULL, 0, '2025-05-29 08:10:40', '2025-05-29 08:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `saving_records`
--

CREATE TABLE `saving_records` (
  `id` int(11) NOT NULL,
  `savings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` date NOT NULL,
  `status` enum('done','missed') NOT NULL DEFAULT 'done',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saving_records`
--

INSERT INTO `saving_records` (`id`, `savings_id`, `user_id`, `amount`, `date`, `status`, `created_at`, `updated_at`) VALUES
(4, 3, 3, 25000.00, '2025-05-28', 'done', '2025-05-29 03:25:36', '2025-05-29 03:25:36'),
(5, 3, 3, 25000.00, '2025-05-29', 'done', '2025-05-29 03:25:40', '2025-05-29 07:54:50'),
(6, 4, 6, 25000.00, '2025-05-29', 'done', '2025-05-29 08:10:44', '2025-05-29 08:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) UNSIGNED NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(1, 'website_name', 'FinanceFlow', '2025-05-22 00:43:09', '2025-07-22 04:55:55'),
(2, 'website_description', 'Catat Keuangan Jadi Menyenangkan', '2025-05-22 00:43:09', '2025-07-22 04:55:55'),
(3, 'admin_email', 'ahmadkhadifar@gmail.com', '2025-05-22 00:43:09', '2025-07-22 04:55:55'),
(4, 'maintenance_mode', '0', '2025-05-22 00:43:09', '2025-07-22 04:55:55'),
(5, 'contact_phone', '089666285670', '2025-05-22 01:33:25', '2025-05-22 01:47:54'),
(6, 'contact_address', 'Jalan raya Krukut', '2025-05-22 01:33:25', '2025-05-22 01:47:54');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duration_days` int(11) NOT NULL COMMENT 'Duration in days (30 for monthly, 365 for yearly)',
  `features` text DEFAULT NULL COMMENT 'JSON array of features',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `slug`, `description`, `price`, `duration_days`, `features`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Free', 'free', 'Paket gratis dengan fitur dasar', 0.00, 0, '[\"Pencatatan transaksi dasar\",\"Budget sederhana\",\"Maksimal 3 kategori\",\"Laporan bulanan basic\"]', 1, '2026-01-16 05:30:32', '2026-01-16 05:30:32'),
(2, 'Premium Bulanan', 'premium-monthly', 'Paket premium dengan semua fitur lengkap', 49000.00, 30, '[\"\\u2705 Semua fitur Free\",\"\\u2705 Laporan keuangan lanjutan dengan grafik\",\"\\u2705 Export ke PDF & Excel\",\"\\u2705 Multiple budget planning (unlimited)\",\"\\u2705 Recurring transactions otomatis\",\"\\u2705 Financial goal tracker\",\"\\u2705 Analisis pengeluaran AI\",\"\\u2705 Tanpa iklan\",\"\\u2705 Support prioritas\"]', 1, '2026-01-16 05:30:32', '2026-01-16 05:30:32'),
(3, 'Premium Tahunan', 'premium-yearly', 'Paket premium tahunan - hemat 30%!', 399000.00, 365, '[\"\\u2705 Semua fitur Premium Bulanan\",\"\\u2705 Hemat 30% (Normal: Rp 588.000\\/tahun)\",\"\\u2705 Prioritas fitur baru\",\"\\u2705 Konsultasi keuangan gratis (1x\\/bulan)\"]', 1, '2026-01-16 05:30:32', '2026-01-16 05:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(55) DEFAULT NULL,
  `users` varchar(55) DEFAULT NULL,
  `category` varchar(55) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` double NOT NULL,
  `transaction_date` date DEFAULT NULL,
  `image_receipt` mediumtext DEFAULT NULL,
  `status` enum('EXPENSE','INCOME') CHARACTER SET utf8mb4 COLLATE utf8mb4_german2_ci NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `transaction_id`, `users`, `category`, `description`, `amount`, `transaction_date`, `image_receipt`, `status`, `created_at`) VALUES
(212, '687f9ca40b7fcTRx', 'aong098', 'Gaji', '', 1000000, '2025-07-22', NULL, 'INCOME', '2025-07-22 21:13:56'),
(213, '687fa90fc11b6TRx', 'ahmadkhadifar', 'Makanan', '', 1000000, '2025-07-22', NULL, 'EXPENSE', '2025-07-22 22:06:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `fullname` varchar(55) DEFAULT NULL,
  `user_image` varchar(255) DEFAULT NULL,
  `email` varchar(55) DEFAULT NULL,
  `username` varchar(55) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `reset_hash` varchar(55) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `activate_hash` varchar(55) DEFAULT NULL,
  `status` varchar(55) DEFAULT NULL,
  `status_message` varchar(55) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `force_pass_reset` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `role` varchar(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `user_image`, `email`, `username`, `password_hash`, `reset_hash`, `reset_at`, `reset_expires`, `activate_hash`, `status`, `status_message`, `active`, `force_pass_reset`, `created_at`, `updated_at`, `deleted_at`, `role`) VALUES
(3, 'Ahmad Khadifar', 'https://i.imgur.com/PbBlwBq.jpg', 'ahmadkhadifar@gmail.com', 'ahmadkhadifar', '$2y$05$FZHuggx5h9Mhp78rtpcXcephxIOXL8L0PsnCf/zCTVDEzgPxLWe2G', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2025-05-03 08:41:44', '2026-01-20 17:40:40', NULL, 'admin'),
(6, 'aong', 'default.jpg', 'aong@gmail.com', 'aong123', '$2y$05$b6.OD/BGost1Jar3x8ciW.vRz5j4lUncyCPOYr5buwY184E7MPjl.', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2025-05-05 22:09:28', '2025-05-29 08:10:24', NULL, 'user'),
(7, NULL, 'assets/images/default.jpg', 'edgar@gmail.com', 'edgar', '$2y$10$p/81jyjGS4buwQwLmNnVpu.8TmFJbBlIEWh0BZxHf4xiGGNxkMSxe', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-05-20 20:14:22', '2025-05-20 20:14:22', NULL, 'admin'),
(8, NULL, 'assets/images/default.jpg', 'dddddd@gmail.com', 'ahmadkhadifar_', '$2y$10$mM7JpQRNvCqLT.osM0wNBu0Nd70JlAa47BNMQe6YoeJ/RdZ7ZwlOa', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-05-20 21:03:51', '2025-05-20 21:03:51', NULL, 'user'),
(10, NULL, 'assets/images/default.jpg', 'iki@gmail.com', 'iki123', '$2y$10$UNHRlYGuZEWte7WHcbej9ec2Lxr.uzF5hwUvIHfqNWd6FGxl8bHd.', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-05-29 04:03:19', '2025-05-29 04:03:19', NULL, 'user'),
(11, NULL, 'assets/images/default.jpg', 'bambang@gmail.com', 'bambang123', '$2y$10$bXCLi21MdDoDlMFqzt/20ObO7xhuh6X2YBVtNrqzj0SqCLK7wMfAC', NULL, NULL, NULL, NULL, 'active', NULL, 0, 0, '2025-05-29 04:20:25', '2025-07-22 13:20:08', NULL, 'user'),
(14, NULL, 'assets/images/default.jpg', 'gilangmugi@gmail.com', 'gilangmugi', '$2y$12$kH2.ltqrkWK4Gok6iPT..usa4YhNPcSFWyyip50fZ9tU6S4lSQZRS', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-07-21 17:26:17', '2025-07-21 17:26:17', NULL, 'user'),
(16, 'aong098', 'assets/images/default.jpg', 'aong123@gmail.com', 'aong098', '$2y$05$XnnW9mdflra.EmnAzf8hkeKqjxLUc9nxcM2K7koNZ17lA1SsNTNra', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-07-22 04:27:32', '2025-07-22 21:13:46', NULL, 'user'),
(17, NULL, 'assets/images/default.jpg', 'ayraz@gmail.com', 'arya', '$2y$12$GeVYK9NT3vvu1iuLcFvIHuGayLlRxGX/Z0mgumI3EG5ZDmq1PI9ZC', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-07-22 05:30:03', '2025-07-22 05:30:03', NULL, 'user'),
(22, 'testing1', 'assets/images/default.jpg', 'testing1@gmail.com', 'testing1', '$2y$12$.tYvMs397qAicp92OMYxUu0NP8IJUpriekK4kIrZxKETVHQ/FjgBO', NULL, NULL, NULL, NULL, 'active', NULL, 1, 0, '2025-07-22 13:06:31', '2025-07-22 13:06:31', NULL, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

CREATE TABLE `user_subscriptions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `plan_id` int(11) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_subscriptions`
--

INSERT INTO `user_subscriptions` (`id`, `user_id`, `plan_id`, `start_date`, `end_date`, `status`, `payment_method`, `payment_status`, `transaction_id`, `created_at`, `updated_at`) VALUES
(1, 3, 2, '2026-01-16 19:10:55', '2026-02-15 19:10:55', 'cancelled', 'qris', 'paid', 'TRX-1768565455-3', '2026-01-16 19:10:55', '2026-01-20 17:52:39'),
(2, 3, 2, '2026-01-20 18:05:47', '2026-02-19 18:05:47', 'cancelled', 'credit_card', 'paid', 'TRX-1768907147-3', '2026-01-20 18:05:47', '2026-01-20 19:45:43'),
(3, 3, 2, '2026-01-20 20:16:48', '2026-02-19 20:16:48', 'active', 'e_wallet', 'paid', 'TRX-1768915008-3', '2026-01-20 20:16:48', '2026-01-20 20:16:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups`
--
ALTER TABLE `auth_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD KEY `auth_groups_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `group_id_permission_id` (`group_id`,`permission_id`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`),
  ADD KEY `group_id_user_id` (`group_id`,`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_tokens_user_id_foreign` (`user_id`),
  ADD KEY `selector` (`selector`);

--
-- Indexes for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD KEY `auth_users_permissions_permission_id_foreign` (`permission_id`),
  ADD KEY `user_id_permission_id` (`user_id`,`permission_id`);

--
-- Indexes for table `biaya_efektif`
--
ALTER TABLE `biaya_efektif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_biaya_efektif_user` (`user_id`);

--
-- Indexes for table `budget_settings`
--
ALTER TABLE `budget_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_user_id_foreign` (`user_id`);

--
-- Indexes for table `cicilan`
--
ALTER TABLE `cicilan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `debt_notes`
--
ALTER TABLE `debt_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_achieved` (`is_achieved`);

--
-- Indexes for table `saving_records`
--
ALTER TABLE `saving_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `savings_id` (`savings_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_activation_attempts`
--
ALTER TABLE `auth_activation_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_groups`
--
ALTER TABLE `auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `auth_permissions`
--
ALTER TABLE `auth_permissions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auth_reset_attempts`
--
ALTER TABLE `auth_reset_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `biaya_efektif`
--
ALTER TABLE `biaya_efektif`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `budget_settings`
--
ALTER TABLE `budget_settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `cicilan`
--
ALTER TABLE `cicilan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `debt_notes`
--
ALTER TABLE `debt_notes`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `saving_records`
--
ALTER TABLE `saving_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=214;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_groups_permissions`
--
ALTER TABLE `auth_groups_permissions`
  ADD CONSTRAINT `auth_groups_permissions_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `auth_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_users_permissions`
--
ALTER TABLE `auth_users_permissions`
  ADD CONSTRAINT `auth_users_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `auth_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auth_users_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `biaya_efektif`
--
ALTER TABLE `biaya_efektif`
  ADD CONSTRAINT `fk_biaya_efektif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cicilan`
--
ALTER TABLE `cicilan`
  ADD CONSTRAINT `cicilan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `saving_records`
--
ALTER TABLE `saving_records`
  ADD CONSTRAINT `saving_records_ibfk_1` FOREIGN KEY (`savings_id`) REFERENCES `savings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_subscriptions`
--
ALTER TABLE `user_subscriptions`
  ADD CONSTRAINT `user_subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
