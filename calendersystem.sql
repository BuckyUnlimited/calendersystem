-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 05:17 AM
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
-- Database: `calendersystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('Study','Freelance','Personal') DEFAULT 'Personal',
  `priority` enum('High','Medium','Low') DEFAULT 'Medium',
  `status` enum('Pending','Completed') DEFAULT 'Pending',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `category`, `priority`, `status`, `start_time`, `end_time`, `created_at`) VALUES
(2, 2, 'Homework', 'Database', 'Study', 'Low', 'Completed', '2026-03-20 12:46:00', '0000-00-00 00:00:00', '2026-03-20 05:46:34'),
(5, 2, 'Database Analysis', 'Create 1NF 2NF 3NF', 'Study', 'High', 'Completed', '2026-03-23 09:37:00', '0000-00-00 00:00:00', '2026-03-23 02:40:10'),
(6, 2, 'Web Assignment', 'Create System', 'Freelance', 'Medium', 'Pending', '2026-03-23 09:55:00', '0000-00-00 00:00:00', '2026-03-23 02:55:30'),
(7, 2, 'Mobile App', 'Create App', 'Personal', 'Medium', 'Completed', '2026-03-23 09:57:00', '0000-00-00 00:00:00', '2026-03-23 02:57:32'),
(8, 2, 'Gengneral Mangement', 'Create slide for present', 'Personal', 'Medium', 'Pending', '2026-03-23 09:58:00', '0000-00-00 00:00:00', '2026-03-23 02:58:37'),
(9, 2, 'Meeting', 'TeamWork', 'Study', 'Medium', 'Pending', '2026-03-23 11:05:00', '2026-03-24 11:05:00', '2026-03-23 04:05:27'),
(18, 8, 'homework3', '', 'Personal', 'Medium', 'Pending', '2026-03-25 09:02:00', '2026-03-26 09:02:00', '2026-03-25 02:02:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `profile_image`) VALUES
(2, 'bobo', 'bobo@gmail.com', '$2y$10$VLRZzOAjwYeH3Fj0olaNq.m6cyce.zKvf0KWaw/K5eTf5J/HCsUwG', '2026-03-20 05:27:58', '1774158851_image_2023-12-27_02-22-20.png'),
(4, 'Sreymom', 'Sreymom@gmail.com', '$2y$10$iYjzTXSc5lLCix47IwMEOej7ggZpgNZZk83RiquDUF9kPCmCe/JLG', '2026-03-23 05:27:07', 'default.png'),
(5, 'Chhailim', 'Chhailim@gmail.com', '$2y$10$nNC5P6W8fQWjIweDhBBW3ecs2ys1M9D3aK14TspxeD2Zx5Qhmidp6', '2026-03-23 11:10:31', 'default.png'),
(8, 'Bunnat', 'bunnat@gmail.com', '$2y$10$vwUnTXSLzJFhz0yfe8MRc.gxPYpmKHD6IKmJGt8VS/WHT8PAgKOBq', '2026-03-23 11:12:21', 'default.png'),
(11, 'Nat', 'nat@gmail.com', '$2y$10$/LGGSG29TlNunXudTxp2Z.BwGkslp.SMYgEOjZ.NXwKO8CV.a/RFa', '2026-03-23 11:24:45', 'default.png'),
(14, 'bobo', 'bobo029@gmail.com', '$2y$10$3APqSO4/ZafUZXu5/NCSeeoSy3FCU4pg42LVtK72Ym/CfarJR8nFO', '2026-03-25 02:21:34', 'default.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
