-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3306
-- Čas generovania: Po 27.Okt 2025, 20:21
-- Verzia serveru: 9.1.0
-- Verzia PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `distributed_db`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `records`
--

DROP TABLE IF EXISTS `records`;
CREATE TABLE IF NOT EXISTS `records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `node_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `posledna_synchronizacia` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `replicated` tinyint(1) DEFAULT '0',
  `quantity` int DEFAULT '0',
  `needs_replication` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(10,2) DEFAULT '0.00',
  `size` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `product_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `records`
--

INSERT INTO `records` (`id`, `node_id`, `title`, `description`, `created_at`, `updated_at`, `posledna_synchronizacia`, `deleted_at`, `replicated`, `quantity`, `needs_replication`, `price`, `size`, `color`, `product_code`) VALUES
(3, 1, 'sukňa', 'Dlhá letná bodkovaná sukňa', '2025-09-28 18:28:51', '2025-10-27 20:18:33', '2025-10-27 20:18:33', NULL, 0, 8, 0, 20.00, 'S', 'modrá', '1234'),
(4, 2, 'Tričko', 'Tričko s dlhým rukávom', '2025-10-27 19:22:36', '2025-10-27 20:18:39', '2025-10-27 20:18:39', '2025-10-27 19:25:18', 0, 8, 0, 10.00, 'XS', 'rúžová', '1235'),
(5, 2, 'Tričko', 'Tričko s dlhým rukávom', '2025-10-27 19:22:36', '2025-10-27 20:18:44', '2025-10-27 20:18:44', NULL, 0, 8, 0, 8.00, 'XS', 'rúžová', '1235');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `record_replication`
--

DROP TABLE IF EXISTS `record_replication`;
CREATE TABLE IF NOT EXISTS `record_replication` (
  `record_id` int NOT NULL,
  `node_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `record_replication`
--

INSERT INTO `record_replication` (`record_id`, `node_id`, `created_at`) VALUES
(3, 2, '2025-10-27 19:20:25'),
(5, 2, '2025-10-27 19:24:37');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `node_id` int NOT NULL,
  `needs_replication` tinyint(1) NOT NULL DEFAULT '0',
  `cancelled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Sťahujem dáta pre tabuľku `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_name`, `product_id`, `quantity`, `total_price`, `node_id`, `needs_replication`, `cancelled`, `created_at`) VALUES
(1, 'Uzol2_2025-10-27_19-20-25', 3, 1, 20.00, 2, 1, 1, '2025-10-27 19:20:25');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `transaction_replication`
--

DROP TABLE IF EXISTS `transaction_replication`;
CREATE TABLE IF NOT EXISTS `transaction_replication` (
  `transaction_id` int NOT NULL,
  `node_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `transaction_replication`
--

INSERT INTO `transaction_replication` (`transaction_id`, `node_id`, `created_at`) VALUES
(1, 2, '2025-10-27 19:20:25');

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `record_replication`
--
ALTER TABLE `record_replication`
  ADD CONSTRAINT `fk_rr_rec` FOREIGN KEY (`record_id`) REFERENCES `records` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_records` FOREIGN KEY (`product_id`) REFERENCES `records` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Obmedzenie pre tabuľku `transaction_replication`
--
ALTER TABLE `transaction_replication`
  ADD CONSTRAINT `fk_tr_tr` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
