-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 30, 2026 at 09:22 PM
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
-- Database: `eventtiket_db3200`
--

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_price` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `event_id`, `order_id`, `customer_name`, `customer_email`, `customer_phone`, `total_price`, `status`, `snap_token`, `created_at`, `updated_at`) VALUES
(1, 9, 'TRX-1782326990-wV5e8', 'mufasa', 'faruqsulaiman@students.amikom.ac.id', '087837560313', 55000, 'Pending', '1adfe692-c8ff-481a-924b-9d3acf499e9a', '2026-06-24 11:49:50', '2026-06-24 11:49:50'),
(2, 9, 'TRX-1782327219-fSta2', 'mufasa', 'faruqsulaiman@students.amikom.ac.id', '087837560313', 55000, 'Pending', '82d7f24e-f5fe-4b28-b3bb-89e25d90202a', '2026-06-24 11:53:39', '2026-06-24 11:53:40'),
(3, 10, 'TRX-1782327433-bxQyE', 'mufasa', 'faruqsulaiman@students.amikom.ac.id', '087837560313', 30000, 'success', '49f6c6de-d863-43fe-b8fe-36483b9894f4', '2026-06-24 11:57:13', '2026-06-24 12:02:47'),
(4, 10, 'TRX-1782853940-DRvAe', 'Quraf', 'Qruaf123@gmail.com', '0834567898', 30000, 'success', '3e0b018b-ba01-499d-be03-0e4f1b936ab0', '2026-06-30 14:12:20', '2026-06-30 14:13:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_order_id_unique` (`order_id`),
  ADD KEY `transactions_event_id_foreign` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
