-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.23 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных webservice
CREATE DATABASE IF NOT EXISTS `webservice` /*!40100 DEFAULT CHARACTER SET utf8 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `webservice`;

-- Дамп структуры для таблица webservice.auth
CREATE TABLE IF NOT EXISTS `auth` (
  `id` int DEFAULT NULL,
  `username` text,
  `password` text,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы webservice.auth: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `auth` DISABLE KEYS */;
INSERT INTO `auth` (`id`, `username`, `password`) VALUES
	(1, 'clickuser', '!QAZxsw2');
/*!40000 ALTER TABLE `auth` ENABLE KEYS */;

-- Дамп структуры для таблица webservice.cards
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int NOT NULL,
  `owner_id` int NOT NULL,
  `card_number` text,
  `amount` bigint NOT NULL DEFAULT '0',
  `max_limit` bigint NOT NULL DEFAULT '10000000',
  `status` char(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_cards_users` (`owner_id`),
  CONSTRAINT `FK_cards_users` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы webservice.cards: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` (`id`, `owner_id`, `card_number`, `amount`, `max_limit`, `status`) VALUES
	(1, 1, '99913', 498650000, 10000000, '1'),
	(2, 2, '99912', 401570000, 10000000, '1');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;

-- Дамп структуры для таблица webservice.services
CREATE TABLE IF NOT EXISTS `services` (
  `id` int NOT NULL,
  `type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы webservice.services: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` (`id`, `type`) VALUES
	(1, 'get_info'),
	(2, 'perform_transaction');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;

-- Дамп структуры для таблица webservice.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` int DEFAULT NULL,
  `sender_id` int DEFAULT NULL,
  `sender_card` text,
  `sender_phone` text,
  `recipient_id` int DEFAULT '0',
  `recipient_card` text,
  `recipient_phone` text,
  `transaction_type` text,
  `amount` bigint DEFAULT '0',
  `transaction_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы webservice.transactions: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` (`id`, `transaction_id`, `sender_id`, `sender_card`, `sender_phone`, `recipient_id`, `recipient_card`, `recipient_phone`, `transaction_type`, `amount`, `transaction_time`) VALUES
	(1, 373, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 20000, '2021-11-05 13:14:15'),
	(2, 911, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 20000, '2021-11-05 13:14:25'),
	(3, 289, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 40000, '2021-11-05 13:14:42'),
	(4, 794, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:24:08'),
	(5, 105, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:26:51'),
	(6, 306, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:27:00'),
	(7, 395, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:27:47'),
	(8, 130, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:36:30'),
	(9, 225, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:36:44'),
	(10, 376, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:37:57'),
	(11, 598, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:38:11'),
	(12, 52, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:39:24'),
	(13, 595, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:40:07'),
	(14, 734, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:44:41'),
	(15, 719, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:45:33'),
	(16, 0, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:49:34'),
	(17, 345, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:49:41'),
	(18, 582, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 13:51:49'),
	(19, 206, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 60000, '2021-11-05 14:01:10'),
	(20, 229, 1, '99913', NULL, 2, '99912', NULL, 'by_card', 70000, '2021-11-05 14:01:15');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;

-- Дамп структуры для таблица webservice.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL,
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `card_id` int NOT NULL DEFAULT '0',
  `phone_num` text,
  PRIMARY KEY (`id`),
  KEY `card_id` (`card_id`),
  CONSTRAINT `card_id` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы webservice.users: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `fullname`, `card_id`, `phone_num`) VALUES
	(1, 'Kamol Israilov', 1, '998946201611'),
	(2, 'Kolya Pechkin', 2, '998946201612');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
