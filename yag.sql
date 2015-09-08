-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.24 - MySQL Community Server (GPL)
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица yug.yug_attributes
CREATE TABLE IF NOT EXISTS `yug_attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_base` int(11) DEFAULT NULL,
  `attribute_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `attribute_value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_base` (`id_base`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы yug.yug_attributes: 0 rows
/*!40000 ALTER TABLE `yug_attributes` DISABLE KEYS */;
/*!40000 ALTER TABLE `yug_attributes` ENABLE KEYS */;


-- Дамп структуры для таблица yug.yug_base
CREATE TABLE IF NOT EXISTS `yug_base` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) DEFAULT NULL,
  `NameRu` text COLLATE utf8_unicode_ci,
  `Slug` text COLLATE utf8_unicode_ci,
  `url` text COLLATE utf8_unicode_ci,
  `FullDescription` text COLLATE utf8_unicode_ci,
  `ProductCode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `i` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ts` timestamp NOT NULL
-- Дамп структуры для таблица yug.yug_images
CREATE TABLE IF NOT  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ProductID` (`ProductID`),
  KEY `i` (`i`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы yug.yug_base: 0 rows
/*!40000 ALTER TABLE `yug_base` DISABLE KEYS */;
/*!40000 ALTER TABLE `yug_base` ENABLE KEYS */;

EXISTS `yug_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_base` int(11) DEFAULT NULL,
  `images_brain_path` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `images_local_path` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_base` (`id_base`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы yug.yug_images: 0 rows
/*!40000 ALTER TABLE `yug_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `yug_images` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
