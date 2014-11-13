CREATE TABLE IF NOT EXISTS `Versions` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `table` varchar(255) NOT NULL,
  `version` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]' AUTO_INCREMENT=1 ;
