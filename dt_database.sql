-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1:3306
-- Čas generovania: So 24.Jan 2026, 21:29
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
-- Databáza: `dt_database`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `node_origin` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_slovak_ci NOT NULL,
  `description` text COLLATE utf8mb4_slovak_ci,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `size` varchar(20) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `color` varchar(30) COLLATE utf8mb4_slovak_ci DEFAULT NULL,
  `product_code` varchar(50) COLLATE utf8mb4_slovak_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_node_product` (`node_origin`,`product_code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `products`
--

INSERT INTO `products` (`id`, `node_origin`, `title`, `description`, `price`, `quantity`, `size`, `color`, `product_code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Dlhá sukňa', 'Červená bodkovaná dlhá Midi sukňa', 15.00, 3, 'S', 'červená', '1234', '2026-01-24 21:28:48', '2026-01-24 21:28:48', NULL),
(3, 1, 'Krátke tričko', 'Modré kratke tričko s golierom', 10.00, 6, 'S', 'modrá', '1235', '2026-01-24 21:55:37', '2026-01-24 21:55:37', NULL),
(7, 1, 'Široké nohavice', 'Široké nohavice s vysokým pasom', 20.00, 2, 'S', 'modrá', '1236', '2026-01-24 22:04:26', '2026-01-24 22:04:26', NULL),
(9, 1, 'Široké nohavice', 'Široké nohavice s vysokým pasom', 20.00, 2, 'S', 'modrá', '1369', '2026-01-24 22:04:45', '2026-01-24 22:15:12', '2026-01-24 22:15:12'),
(12, 1, 'Sako', 'Hnedé sako do pasu', 30.00, 2, 'M', 'hnedá', '1237', '2026-01-24 22:14:03', '2026-01-24 22:19:05', NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `replication_queue`
--

DROP TABLE IF EXISTS `replication_queue`;
CREATE TABLE IF NOT EXISTS `replication_queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `target_node` int NOT NULL,
  `operation` enum('INSERT','UPDATE','DELETE') COLLATE utf8mb4_slovak_ci NOT NULL,
  `sql_query` text COLLATE utf8mb4_slovak_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

--
-- Sťahujem dáta pre tabuľku `replication_queue`
--

INSERT INTO `replication_queue` (`id`, `target_node`, `operation`, `sql_query`, `created_at`) VALUES
(1, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Dlhá sukňa\',\r\n            \'Červená bodkovaná dlhá Midi sukňa\',\r\n            3,\r\n            15,\r\n            \'S\',\r\n            \'červená\',\r\n            \'1234\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:28:48'),
(2, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Dlhá sukňa\',\r\n            \'Červená bodkovaná dlhá Midi sukňa\',\r\n            3,\r\n            15,\r\n            \'S\',\r\n            \'červená\',\r\n            \'1234\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:28:48'),
(3, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Dlhá sukňa\',\r\n            \'Červená bodkovaná dlhá Midi sukňa\',\r\n            3,\r\n            15,\r\n            \'S\',\r\n            \'červená\',\r\n            \'1234\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:28:48'),
(4, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Dlhá sukňa\',\r\n            \'Červená bodkovaná dlhá Midi sukňa\',\r\n            3,\r\n            15,\r\n            \'S\',\r\n            \'červená\',\r\n            \'1234\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:28:48'),
(5, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Krátke tričko\',\r\n            \'Modré kratke tričko s golierom\',\r\n            6,\r\n            10,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1235\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:55:37'),
(6, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Krátke tričko\',\r\n            \'Modré kratke tričko s golierom\',\r\n            6,\r\n            10,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1235\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:55:37'),
(7, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Krátke tričko\',\r\n            \'Modré kratke tričko s golierom\',\r\n            6,\r\n            10,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1235\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:55:37'),
(8, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Krátke tričko\',\r\n            \'Modré kratke tričko s golierom\',\r\n            6,\r\n            10,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1235\',\r\n            1\r\n        )\r\n    ', '2026-01-24 21:55:37'),
(9, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Široké nohavice\',\r\n            \'Široké nohavice s vysokým pasom\',\r\n            2,\r\n            20,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1236\',\r\n            1\r\n        )\r\n    ', '2026-01-24 22:04:26'),
(10, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Široké nohavice\',\r\n            \'Široké nohavice s vysokým pasom\',\r\n            2,\r\n            20,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1236\',\r\n            1\r\n        )\r\n    ', '2026-01-24 22:04:26'),
(11, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Široké nohavice\',\r\n            \'Široké nohavice s vysokým pasom\',\r\n            2,\r\n            20,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1369\',\r\n            1\r\n        )\r\n    ', '2026-01-24 22:04:45'),
(12, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Široké nohavice\',\r\n            \'Široké nohavice s vysokým pasom\',\r\n            2,\r\n            20,\r\n            \'S\',\r\n            \'modrá\',\r\n            \'1369\',\r\n            1\r\n        )\r\n    ', '2026-01-24 22:04:45'),
(13, 2, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Sako\',\r\n            \'Hnedé sako do pasu\',\r\n            2,\r\n            25,\r\n            \'M\',\r\n            \'hnedá\',\r\n            \'1237\',\r\n            1\r\n        )\r\n    ', '2026-01-24 22:14:03'),
(14, 3, 'INSERT', '\r\n        INSERT INTO products\r\n        (title, description, quantity, price, size, color, product_code, node_origin)\r\n        VALUES (\r\n            \'Sako\',\r\n            \'Hnedé sako do pasu\',\r\n            2,\r\n            25,\r\n            \'M\',\r\n            \'hnedá\',\r\n            \'1237\',\r\n            1\r\n        )\r\n    ', '2026-01-24 22:14:03'),
(15, 2, 'DELETE', '\r\n    UPDATE products SET deleted_at = NOW()\r\n    WHERE id = 9\r\n', '2026-01-24 22:15:12'),
(16, 3, 'DELETE', '\r\n    UPDATE products SET deleted_at = NOW()\r\n    WHERE id = 9\r\n', '2026-01-24 22:15:12'),
(17, 2, 'UPDATE', '\r\n            UPDATE products SET\r\n                title = \'Sako\',\r\n                description = \'Hnedé sako do pasu\',\r\n                quantity = 2,\r\n                price = 30,\r\n                size = \'M\',\r\n                color = \'hnedá\',\r\n                product_code = \'1237\'\r\n            WHERE id = 12\r\n        ', '2026-01-24 22:19:05'),
(18, 3, 'UPDATE', '\r\n            UPDATE products SET\r\n                title = \'Sako\',\r\n                description = \'Hnedé sako do pasu\',\r\n                quantity = 2,\r\n                price = 30,\r\n                size = \'M\',\r\n                color = \'hnedá\',\r\n                product_code = \'1237\'\r\n            WHERE id = 12\r\n        ', '2026-01-24 22:19:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
