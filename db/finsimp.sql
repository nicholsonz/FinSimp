-- phpMyAdmin SQL Dump
-- version 5.2.2deb1+deb13u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
<<<<<<< HEAD
-- Generation Time: Sep 30, 2026 at 01:30 PM
=======
-- Generation Time: Sep 30, 2026 at 02:06 PM
>>>>>>> 424b650 (db update)
-- Server version: 11.8.6-MariaDB-0+deb13u1 from Debian-log
-- PHP Version: 8.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `finsimp`
--
CREATE DATABASE IF NOT EXISTS `finsimp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `finsimp`;

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

DROP TABLE IF EXISTS `asset`;
CREATE TABLE `asset` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(13,2) NOT NULL DEFAULT 0.00,
  `apy` decimal(5,2) NOT NULL DEFAULT 0.00,
  `risk` enum('High','Moderate','Low') NOT NULL DEFAULT 'Low',
  `asset_type` enum('Cash Equivalents','Stocks','Bonds','Real Property','Personal Property') NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

DROP TABLE IF EXISTS `budget`;
CREATE TABLE `budget` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(50) NOT NULL,
  `descr` text NOT NULL,
  `cat_type` enum('Asset','Liability','Income','Expense') NOT NULL,
  `user_id` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equity`
--

DROP TABLE IF EXISTS `equity`;
CREATE TABLE `equity` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

DROP TABLE IF EXISTS `expense`;
CREATE TABLE `expense` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `chrg_type` enum('ATM','Bank EFT','Cash','Check','Credit','Debit Card','Depreciation','Capital Loss','Refund') NOT NULL DEFAULT 'Debit Card',
  `cat_id` int(11) NOT NULL,
  `acct_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_del`
--

DROP TABLE IF EXISTS `expense_del`;
CREATE TABLE `expense_del` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `chrg_type` enum('ATM','Bank EFT','Cash','Check','Credit','Debit Card','Depreciation','Capital Loss','Refund') NOT NULL DEFAULT 'Debit Card',
  `cat_id` int(11) NOT NULL,
  `acct_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goals`
--

DROP TABLE IF EXISTS `goals`;
CREATE TABLE `goals` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `goal` varchar(55) NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `deadline` date NOT NULL,
  `cat_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

DROP TABLE IF EXISTS `income`;
CREATE TABLE `income` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `acct_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_del`
--

DROP TABLE IF EXISTS `income_del`;
CREATE TABLE `income_del` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `acct_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `liability`
--

DROP TABLE IF EXISTS `liability`;
CREATE TABLE `liability` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `apr` decimal(5,2) NOT NULL DEFAULT 0.00,
  `liab_type` enum('Current','Long Term') NOT NULL,
  `user_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `hide` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

DROP TABLE IF EXISTS `logins`;
CREATE TABLE `logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `logintime` timestamp NOT NULL,
  `login_status` varchar(15) NOT NULL,
  `ip` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passwdrst`
--

DROP TABLE IF EXISTS `passwdrst`;
CREATE TABLE `passwdrst` (
  `ID` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `key` varchar(250) NOT NULL,
  `expDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

DROP TABLE IF EXISTS `transfers`;
CREATE TABLE `transfers` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `facct_id` int(11) NOT NULL,
  `tacct_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfers_del`
--

DROP TABLE IF EXISTS `transfers_del`;
CREATE TABLE `transfers_del` (
  `id` int(11) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date` date NOT NULL,
  `descr` text NOT NULL,
  `amount` decimal(13,2) NOT NULL,
  `facct_id` int(11) NOT NULL,
  `tacct_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `date_created` date NOT NULL DEFAULT current_timestamp(),
  `username` varchar(30) NOT NULL,
  `password` varchar(75) NOT NULL,
  `email` varchar(30) NOT NULL,
  `activate` varchar(20) NOT NULL,
  `account` enum('Basic','Standard','Professional','Unlimited') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_id_2` (`cat_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `date` (`date`),
  ADD KEY `asset_idx_user_id_hide` (`user_id`,`hide`),
  ADD KEY `asset_idx_user_id_cat_id` (`user_id`,`cat_id`),
  ADD KEY `asset_idx_asset_type_user_id_hide` (`asset_type`,`user_id`,`hide`),
  ADD KEY `asset_idx_asset_type_user_id` (`asset_type`,`user_id`);

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `date` (`date`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_name_2` (`cat_name`,`user_id`,`cat_type`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_name` (`cat_name`) USING BTREE,
  ADD KEY `category_idx_user_id_hide_cat_name` (`user_id`,`hide`,`cat_name`),
  ADD KEY `category_idx_hide_id_cat_name` (`hide`,`id`,`cat_name`),
  ADD KEY `category_idx_id_cat_name` (`id`,`cat_name`),
  ADD KEY `category_idx_cat_type_id_cat_name` (`cat_type`,`id`,`cat_name`),
  ADD KEY `category_idx_cat_name_cat_type_user_id` (`cat_name`,`cat_type`,`user_id`),
  ADD KEY `category_idx_id_cat_type` (`id`,`cat_type`),
  ADD KEY `category_idx_id_hide` (`id`,`hide`);

--
-- Indexes for table `equity`
--
ALTER TABLE `equity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE;

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `date` (`date`),
  ADD KEY `expense_idx_acct_id_cat_id_date` (`acct_id`,`cat_id`,`date`),
  ADD KEY `expense_idx_date_cat_id` (`date`,`cat_id`),
  ADD KEY `expense_idx_cat_id` (`cat_id`),
  ADD KEY `expense_idx_acct_id_cat_id` (`acct_id`,`cat_id`),
  ADD KEY `expense_idx_acct_id` (`acct_id`),
  ADD KEY `expense_idx_user_id_date` (`user_id`,`date`);

--
-- Indexes for table `expense_del`
--
ALTER TABLE `expense_del`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `acct_id` (`acct_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `goals`
--
ALTER TABLE `goals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `goals_idx_cat_id_name` (`cat_id`,`goal`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `date` (`date`),
  ADD KEY `income_idx_acct_id` (`acct_id`),
  ADD KEY `income_idx_cat_id_acct_id` (`cat_id`,`acct_id`);

--
-- Indexes for table `income_del`
--
ALTER TABLE `income_del`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `acct_id` (`acct_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `liability`
--
ALTER TABLE `liability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `cat_id` (`cat_id`) USING BTREE,
  ADD KEY `liability_idx_liab_type_cat_id_hide` (`liab_type`,`cat_id`,`hide`),
  ADD KEY `liability_idx_user_id_cat_id` (`user_id`,`cat_id`),
  ADD KEY `liability_idx_liab_type_user_id_hide` (`liab_type`,`user_id`,`hide`),
  ADD KEY `liability_idx_user_id_hide` (`user_id`,`hide`),
  ADD KEY `liability_idx_liab_type_user_id` (`liab_type`,`user_id`),
  ADD KEY `liability_idx_user_id_cat_id_apr` (`user_id`,`cat_id`,`apr`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `passwdrst`
--
ALTER TABLE `passwdrst`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facct_id` (`facct_id`),
  ADD KEY `tacct_id` (`tacct_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `transfers_idx_facct_id` (`facct_id`),
  ADD KEY `transfers_idx_tacct_id` (`tacct_id`);

--
-- Indexes for table `transfers_del`
--
ALTER TABLE `transfers_del`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facct_id` (`facct_id`),
  ADD KEY `tacct_id` (`tacct_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`,`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `equity`
--
ALTER TABLE `equity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_del`
--
ALTER TABLE `expense_del`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goals`
--
ALTER TABLE `goals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_del`
--
ALTER TABLE `income_del`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liability`
--
ALTER TABLE `liability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passwdrst`
--
ALTER TABLE `passwdrst`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfers_del`
--
ALTER TABLE `transfers_del`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `asset`
--
ALTER TABLE `asset`
  ADD CONSTRAINT `asset_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `asset_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `budget`
--
ALTER TABLE `budget`
  ADD CONSTRAINT `budget_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `budget_ibfk_3` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `equity`
--
ALTER TABLE `equity`
  ADD CONSTRAINT `equity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `equity_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expense_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `expense_ibfk_3` FOREIGN KEY (`acct_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `expense_del`
--
ALTER TABLE `expense_del`
  ADD CONSTRAINT `expense_del_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `goals`
--
ALTER TABLE `goals`
  ADD CONSTRAINT `goals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `goals_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `income`
--
ALTER TABLE `income`
  ADD CONSTRAINT `income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `income_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `income_ibfk_3` FOREIGN KEY (`acct_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `income_del`
--
ALTER TABLE `income_del`
  ADD CONSTRAINT `income_del_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `liability`
--
ALTER TABLE `liability`
  ADD CONSTRAINT `liability_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `liability_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`facct_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transfers_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transfers_ibfk_3` FOREIGN KEY (`tacct_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transfers_del`
--
ALTER TABLE `transfers_del`
  ADD CONSTRAINT `transfers_del_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELIMITER $$
--
-- Events
--
DROP EVENT IF EXISTS `Clear_Logins`$$
CREATE DEFINER=`zach`@`localhost` EVENT `Clear_Logins` ON SCHEDULE EVERY 4 MONTH STARTS '2024-01-01 01:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `logins` WHERE logintime < now() - INTERVAL 90 DAY$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
