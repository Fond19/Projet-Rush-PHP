-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.6.25-0ubuntu0.15.04.1 - (Ubuntu)
-- SE du serveur:                debian-linux-gnu
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de la structure de la base pour pool_php_rush
CREATE DATABASE IF NOT EXISTS `pool_php_rush` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pool_php_rush`;


DROP TABLE IF EXISTS `categories`;

-- Export de la structure de table pool_php_rush. categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table pool_php_rush.categories : ~0 rows (environ)
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;


DROP TABLE IF EXISTS `products`;
-- Export de la structure de table pool_php_rush. products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '0',
  `price` int(11) NOT NULL DEFAULT '0',
  `image` varchar(255) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table pool_php_rush.products : ~0 rows (environ)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;


-- Export de la structure de table pool_php_rush. users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `admin` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table pool_php_rush.users : ~0 rows (environ)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-- Ajout de catégories
INSERT INTO `categories` (`name`, `parent_id`) VALUES("Electronic device", NULL), ("Accessory", NULL), ("Smartphone", 1), ("Tablet", 1), ("Laptop", 1), ("Phone case", 2);

-- Ajout de produits
INSERT INTO `products` (`name`, `price`, `image`, `category_id`) VALUES ("iPhone SE", 600, "5092b3bfbfb5b6574cc93107d9d99e7e.jpeg", 3), ("iPhone 8", 900, "212d058f206375137d639dada5b6eaa1.jpg", 3), ("iPhone X", 1200, "2ea7d07ac55c882a83ad4b3fe9759561.jpg", 3), ("iPad 5", 1100, "628a29794363bb83b421c61844e6ac2d.jpg", 4), ("iPad Air 2", 850, "628a29794363bb83b421c61844e6ac2d.jpg", 4), ("iPad Mini 4", 650, "729cc70ff36cffb5ee6e3cb58e8bf1de.jpeg", 4), ("Macbook Air", 1250, "90d408291f69b0e633a000694fc7806e.jpg", 5), ("Macbook Pro", 1800, "5af1ca71f638748b0998d49ea646b9e9.jpg", 5), ("iPhone 8 case (burgundy)", 25, "4881f08e718d207654274e69585f1604.jpeg", 6), ("iPhone 8 case (pink)", 25, "9a55ed76fee7f33531422af02c6c0ab2.jpg", 6) ;