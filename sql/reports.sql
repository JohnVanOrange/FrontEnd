SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `image_id` int(10) unsigned NOT NULL,
  `report_type` int(3) unsigned NOT NULL,
  `reason` varchar(255) collate utf8_unicode_ci default NULL,
  `resolved` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
