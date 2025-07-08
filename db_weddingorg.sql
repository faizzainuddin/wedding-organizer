-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 15, 2024 at 01:20 PM
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
-- Database: `db_weddingorg`
--

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

DROP TABLE IF EXISTS `goods`;
CREATE TABLE IF NOT EXISTS `goods` (
  `g_id` int NOT NULL AUTO_INCREMENT,
  `g_name` varchar(255) NOT NULL,
  `g_price` int NOT NULL,
  `g_qty` int NOT NULL,
  `g_created_at` datetime NOT NULL,
  `g_p_id` int NOT NULL,
  PRIMARY KEY (`g_id`),
  KEY `p_id` (`g_p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goods`
--

INSERT INTO `goods` (`g_id`, `g_name`, `g_price`, `g_qty`, `g_created_at`, `g_p_id`) VALUES
(2, 'Meja (Warna Emas)', 120000, 15, '2024-06-03 20:08:04', 1),
(3, 'Tenda', 100000, 10, '2024-06-05 08:56:43', 1),
(5, 'Meja (Warna Putih)', 120000, 12, '2024-06-09 17:56:15', 2),
(6, 'Kursi', 120000, 10, '2024-06-14 08:17:44', 1),
(7, 'Sound System', 5000000, 1, '2024-06-14 09:07:36', 3),
(8, 'Pelaminan', 15000000, 1, '2024-06-14 09:08:42', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `o_id` int NOT NULL AUTO_INCREMENT,
  `o_user_id` int NOT NULL,
  `o_p_id` int NOT NULL,
  `o_pm_id` int NOT NULL,
  `o_status` enum('Menunggu dikonfirmasi','Dikonfirmasi','Selesai','') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `o_event_date` date NOT NULL,
  `o_created_at` datetime NOT NULL,
  PRIMARY KEY (`o_id`),
  KEY `o_user_id` (`o_user_id`),
  KEY `o_p_id` (`o_p_id`),
  KEY `o_payment_id` (`o_pm_id`),
  KEY `o_pm_id` (`o_pm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`o_id`, `o_user_id`, `o_p_id`, `o_pm_id`, `o_status`, `o_event_date`, `o_created_at`) VALUES
(31, 5, 1, 2, 'Selesai', '2024-06-28', '2024-06-15 19:59:58'),
(30, 5, 1, 2, 'Menunggu dikonfirmasi', '2024-06-29', '2024-06-15 19:09:56'),
(7, 5, 1, 1, 'Menunggu dikonfirmasi', '2024-06-11', '2024-06-06 16:55:18'),
(8, 5, 2, 2, 'Selesai', '2024-06-09', '2024-06-06 18:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `p_id` int NOT NULL AUTO_INCREMENT,
  `p_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `p_price` int NOT NULL,
  `p_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_created_at` datetime NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`p_id`, `p_name`, `p_description`, `p_price`, `p_image`, `p_created_at`) VALUES
(1, 'Bronze Package', 'Gedung type Anggrek, simple, minimalist dan elegan', 10000000, 'uploads/bronze.jpeg', '2024-06-03 23:13:07'),
(2, 'Silver Package', 'Gedung type Garbera nuansa awan, megah dan mewah', 40000000, 'uploads/silver.jpg', '2024-06-03 00:00:00'),
(3, 'Gold Package', 'Gedung type black orchid, nuansa sultan, mewah dan serba emas', 80000000, 'uploads/gold.jpg', '2024-06-03 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `p_user_id` int NOT NULL,
  `p_o_id` int DEFAULT NULL,
  `p_pm_id` int NOT NULL,
  `payment_sender` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `p_amount` int NOT NULL,
  `payment_status` enum('Unpaid','Paid DP','Fully Paid','') NOT NULL,
  `payment_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `p_o_id` (`p_o_id`),
  KEY `p_user_id` (`p_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `p_user_id`, `p_o_id`, `p_pm_id`, `payment_sender`, `p_amount`, `payment_status`, `payment_proof`) VALUES
(2, 5, 7, 1, '1231231231231', 10000065, 'Unpaid', 'bukti-pembayaran/Screenshot 2024-05-14 at 10-09-38 065122029_Kaka Maulana Abdillah_Test Case.pdf.png'),
(3, 5, 8, 4, '1231203120123', 40000065, 'Fully Paid', 'bukti-pembayaran/WhatsApp Image 2024-05-20 at 08.53.54.jpeg'),
(16, 5, 31, 2, '50523042043', 10000155, 'Fully Paid', 'bukti-pembayaran/buktipaymentbc4.jpg'),
(7, 5, 14, 2, '5124123012', 10000814, 'Unpaid', 'bukti-pembayaran/unpwedding-gpt.png'),
(8, 5, 13, 3, '1203123011', 40000814, 'Unpaid', 'bukti-pembayaran/asciicode.jpg'),
(9, 5, 17, 1, '5120310312', 80000085, 'Fully Paid', 'WhatsApp Image 2024-05-13 at 15..jpg');

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

DROP TABLE IF EXISTS `payment_method`;
CREATE TABLE IF NOT EXISTS `payment_method` (
  `pm_id` int NOT NULL AUTO_INCREMENT,
  `pm_name` varchar(50) NOT NULL,
  `pm_detail` varchar(255) NOT NULL,
  PRIMARY KEY (`pm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`pm_id`, `pm_name`, `pm_detail`) VALUES
(1, 'Bank BNI', '1846338905 (A/N: UNPWedding)'),
(2, 'Bank BRI', '52001231021300 (A/N: UNPWedding)'),
(3, 'Bank BCA', '3780411293 (A/N : UNPWedding)'),
(4, 'Bank Mandiri', '1023155249 (A/N : UNPWedding)');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `level` enum('User','Admin') NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `zipcode` varchar(10) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `level`, `first_name`, `last_name`, `address`, `city`, `zipcode`, `telephone`, `created_at`) VALUES
(2, 'admin', 'ee5e390eb6d41846715febef641f205f', 'admin@gmail.com', 'Admin', 'Admin', 'Admin', 'Admin address', 'Adm1n', '210312', '08123123213', '2024-06-03 07:59:26'),
(14, 'demouser', '91017d590a69dc49807671a51f10ab7f', 'demouser@gmail.com', 'User', 'User', 'Demo', 'Jalan Pakuan No.1, Tegallega, Kec. Bogor Tengah', 'Bogor', '166262', '08213333382812', '2024-06-07 08:49:15'),
(5, 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@gmail.com', 'User', 'test', 'test', 'testaja', 'test', 't3st', '08219312312', '2024-04-18 22:41:36');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
