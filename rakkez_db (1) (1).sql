-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 29 أبريل 2026 الساعة 10:29
-- إصدار الخادم: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rakkez_db`
--

-- --------------------------------------------------------

--
-- بنية الجدول `interruption`
--

CREATE TABLE `interruption` (
  `interruption_id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `interruption`
--

INSERT INTO `interruption` (`interruption_id`, `session_id`, `reason`) VALUES
(3, 6, 'Social Media');

-- --------------------------------------------------------

--
-- بنية الجدول `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `message` varchar(100) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `notification`
--

INSERT INTO `notification` (`notification_id`, `id`, `message`, `date`) VALUES
(1, 1, 'Great job on your session!', '2026-04-28 10:00:00');

-- --------------------------------------------------------

--
-- بنية الجدول `study_session`
--

CREATE TABLE `study_session` (
  `session_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `break_preference` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `study_session`
--

INSERT INTO `study_session` (`session_id`, `id`, `duration`, `break_preference`) VALUES
(6, 1, 60, 1);

-- --------------------------------------------------------

--
-- بنية الجدول `tip`
--

CREATE TABLE `tip` (
  `tip_id` int(11) NOT NULL,
  `tip_text` varchar(100) NOT NULL,
  `interruption_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `tip`
--

INSERT INTO `tip` (`tip_id`, `tip_text`, `interruption_id`) VALUES
(1, 'Put your phone away', 3);

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `photo`, `isAdmin`) VALUES
(1, 'jana', 'j@j.com', '$2y$10$0vm86ycNAr1MPI41wkRlougX2ZAThtMXaqBmMsaleV.EqC5oLNiS.', 'default.png', 0),
(2, 'Ward', 'admin@test.com', '$2y$10$0vm86ycNAr1MPI41wkRlougX2ZAThtMXaqBmMsaleV.EqC5oLNiS.', 'default.png', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `interruption`
--
ALTER TABLE `interruption`
  ADD PRIMARY KEY (`interruption_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `study_session`
--
ALTER TABLE `study_session`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `tip`
--
ALTER TABLE `tip`
  ADD PRIMARY KEY (`tip_id`),
  ADD KEY `interruption_id` (`interruption_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `interruption`
--
ALTER TABLE `interruption`
  MODIFY `interruption_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `study_session`
--
ALTER TABLE `study_session`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tip`
--
ALTER TABLE `tip`
  MODIFY `tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- قيود الجداول المحفوظة
--

--
-- القيود للجدول `interruption`
--
ALTER TABLE `interruption`
  ADD CONSTRAINT `interruption_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `study_session` (`session_id`);

--
-- القيود للجدول `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- القيود للجدول `study_session`
--
ALTER TABLE `study_session`
  ADD CONSTRAINT `study_session_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`);

--
-- القيود للجدول `tip`
--
ALTER TABLE `tip`
  ADD CONSTRAINT `tip_ibfk_1` FOREIGN KEY (`interruption_id`) REFERENCES `interruption` (`interruption_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
