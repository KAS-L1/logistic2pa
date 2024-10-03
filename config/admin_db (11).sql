-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 03, 2024 at 03:40 AM
-- Server version: 9.0.1
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_requests`
--

DROP TABLE IF EXISTS `account_requests`;
CREATE TABLE IF NOT EXISTS `account_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `account_requests`
--

INSERT INTO `account_requests` (`request_id`, `name`, `email`, `reason`, `status`, `created_at`, `role`) VALUES
(13, 'kupal ako ', 'kupal@gmail.com', 'kupal', 'approved', '2024-10-01 11:43:50', 'logistic1_admin'),
(12, 'Antoy', 'xoyeh20931@rinseart.com', 'asdasd', 'approved', '2024-09-30 07:25:57', 'logistic1_admin');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `last_name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'She', 'Velasco', 'valky', 'test@example.com', '$2y$10$ZvaNsgdPLFnfwykvDmjdVuJcX9luWxBJZr0psvUyhsMFbDYpbgV/C', 'admin', '2024-09-23 07:32:08'),
(2, 'Antoy', 'al', 'antoy', 'kupal@gmail.com', '$2y$10$hc1hWZrrowCRnFVE/0hc0eJq7DnfNeDZbganWOL4ALCyTuATbYEje', 'admin', '2024-09-23 08:00:14'),
(3, 'erik', 'lalas', 'erik12', 'erik@gmail.com', '$2y$10$eqVNx/tZKetJ5/QssMaa5eaGo0/AotFCozks8mHT/Ff/g6n38P4zi', 'admin', '2024-09-23 08:03:30'),
(4, 'kasl', 'kasl', 'kasl', 'kier@gmail.com', '$2y$10$OykmgON/IEGb7DaDhtxD/edkEohayeZEZtw1r2cBwo0jRj8Ge2ZjG', 'admin', '2024-09-23 08:11:00'),
(5, 'lakas', 'tama', 'lakas', 'lakas@gmail.com', '$2y$10$5gM3omAvlN8GU0KiAm9pUegTLNRdBrM8BEFDf/TWJWOPiNh0UwwIu', 'admin', '2024-09-23 08:39:05'),
(6, 'ano', 'ano', '123', 'ano@gmail.com', '$2y$10$V3bML3fKoqeSmnZah2PQ.OgKQ7AlZ03ApXr9jWEgH/ZCaXdn1fvmG', 'admin', '2024-09-23 08:39:31'),
(7, 'emar', 'industriya', 'emar', 'emar@gmail.com', '$2y$10$u7XYOdfv2KwEkD6/GbrvBOslgmdVqihmSDXwdGbXH58lyKd42Ab7q', 'admin', '2024-09-24 02:30:01');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `audit_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int DEFAULT NULL,
  `action_taken` text,
  `action_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`audit_id`),
  UNIQUE KEY `audit_id` (`audit_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `branch_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `manager_id` int DEFAULT NULL,
  PRIMARY KEY (`branch_id`),
  UNIQUE KEY `branch_id` (`branch_id`),
  KEY `fk_manager` (`manager_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `branch_name`, `location`, `created_at`, `manager_id`) VALUES
(12, 'testingggg', 'fdsfdsfdsf', '2024-09-30 10:29:49', 23);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `employee_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) DEFAULT NULL,
  `branch_id` int DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`employee_id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  UNIQUE KEY `email` (`email`),
  KEY `branch_id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `inventory_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `threshold` int NOT NULL,
  `last_replenished` date DEFAULT NULL,
  PRIMARY KEY (`inventory_id`),
  UNIQUE KEY `inventory_id` (`inventory_id`),
  KEY `branch_id` (`branch_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` int NOT NULL,
  `expire_at` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `expires_at`, `expire_at`) VALUES
('123@gmail.com', '05ae06534bea8b2076423df449c87029259a280465f0901764017f5acaf0f220', 2024, '0000-00-00 00:00:00'),
('kasl.54370906@gmail.com', '869d36da2fc2f59e155dee0d16b198573e4d9ee7d2f253c611bab4008480c0b081534a335e36397e0441110f0586be09eced', 2024, '2024-09-27 04:27:32'),
('valkyrievee00@gmail.com', 'f1dd80d1826f5b846819a12689f2b6624ea96a4138337543a923ec9be57df8a03e0d890dea2678b5af07e05aeb6901873c84', 0, '2024-09-27 05:10:42');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `permission` varchar(100) NOT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `role_id`, `permission`) VALUES
(1, 1, 'edit_all_profiles'),
(2, 2, 'edit_own_profile'),
(3, 3, 'manage_branch_inventory');

-- --------------------------------------------------------

--
-- Table structure for table `procurement_requests`
--

DROP TABLE IF EXISTS `procurement_requests`;
CREATE TABLE IF NOT EXISTS `procurement_requests` (
  `procurement_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `vendor_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`procurement_id`),
  UNIQUE KEY `procurement_id` (`procurement_id`),
  KEY `branch_id` (`branch_id`),
  KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `project_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL,
  `branch_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`),
  UNIQUE KEY `project_id` (`project_id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `replenishment_requests`
--

DROP TABLE IF EXISTS `replenishment_requests`;
CREATE TABLE IF NOT EXISTS `replenishment_requests` (
  `replenishment_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int NOT NULL,
  `request_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`replenishment_id`),
  UNIQUE KEY `replenishment_id` (`replenishment_id`),
  KEY `branch_id` (`branch_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'admin'),
(2, 'user'),
(3, 'branch_manager');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `task_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` int DEFAULT NULL,
  `assigned_to` int DEFAULT NULL,
  `task_description` text NOT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `task_id` (`task_id`),
  KEY `project_id` (`project_id`),
  KEY `assigned_to` (`assigned_to`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'employee',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiration` timestamp NULL DEFAULT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `created_at`, `first_name`, `last_name`, `otp`, `otp_expiration`, `branch_id`, `profile_pic`, `contact_number`, `address`) VALUES
(23, 'lupakakos', 'kasl.54370906@gmail.com', '$2y$10$P2wM1B88UblrYuXoVAzHuuERYbHIyelOsLMyHpo7.Zk0zAc5.YwjW', 'admin', '2024-09-28 10:00:34', 'kiers', 'salise', NULL, NULL, 0, '/includes/admin/profile/uploads/download.jpg', '123456784', 'malupiton to man '),
(33, 'dadasdasdsdfsd', 'adfdsf@gmail.com', '$2y$10$YC6u37QDCWemsYlJfhC9y.4zX163y1lmJsNudTYnjz4DXlSprbys6', 'admin', '2024-09-30 07:04:13', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(36, 'kupal', 'hawalic249@sgatra.com', '$2y$10$GW4uzAEQ57X/LJaDJVP80.sy7QFpE6OAxaLkTumuzeJb7r.VK3tyO', 'admin', '2024-09-30 07:28:14', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(44, 'mark@admin', 'marktayamora124@gmail.com', '$2y$10$HQgE2IoxXlNSmeNlpphFUetv5TuMHEVhLzprkLFlzjVpa4nMtj73e', 'admin', '2024-10-02 07:12:05', '', '', NULL, NULL, NULL, NULL, NULL, NULL),
(45, 'dump', 'dump41863@gmail.com', '$2y$10$RZvb1mh.7DUncw6cbImiLeF3vdE81bAu4EeOQi9e3jdq.ClZVAr2i', 'logistic2_admin', '2024-10-02 12:56:20', 'dumps', 'dumps', NULL, NULL, NULL, '/includes/admin/profile/uploads/images.png', '123456789', 'sfsfsfsf'),
(46, 'valky', 'valkyrievee00@gmail.com', '$2y$10$/5TjTk00PJ7jZEliNyqfVeRTPFS51WMb10y5FDOhEexJ2Sp3B8Gz6', 'logistic1_admin', '2024-10-02 13:02:54', 'valky', 'valky', NULL, NULL, NULL, '/includes/admin/profile/uploads/att.p-UWVvZ4ezq8gFeyyF9tfTbd5SCQyrrpu3eH0InFegg.png', '123456789', 'kupal ');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `vehicle_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `license_plate` varchar(50) NOT NULL,
  `capacity` decimal(10,2) DEFAULT NULL,
  `availability_status` varchar(50) DEFAULT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`vehicle_id`),
  UNIQUE KEY `vehicle_id` (`vehicle_id`),
  UNIQUE KEY `license_plate` (`license_plate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_reservations`
--

DROP TABLE IF EXISTS `vehicle_reservations`;
CREATE TABLE IF NOT EXISTS `vehicle_reservations` (
  `reservation_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `vehicle_id` int DEFAULT NULL,
  `reservation_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pickup_location` varchar(255) DEFAULT NULL,
  `delivery_destination` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`reservation_id`),
  UNIQUE KEY `reservation_id` (`reservation_id`),
  KEY `branch_id` (`branch_id`),
  KEY `vehicle_id` (`vehicle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
CREATE TABLE IF NOT EXISTS `vendors` (
  `vendor_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vendor_name` varchar(255) NOT NULL,
  `contact_info` text,
  `performance_rating` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `vendor_id` (`vendor_id`)
) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
