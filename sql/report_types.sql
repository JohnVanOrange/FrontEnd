SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `report_types` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `value` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

INSERT INTO `report_types` (`id`, `value`) VALUES
(1, 'NSFW'),
(2, 'Copyright Violation'),
(3, 'Lame'),
(4, 'Illegal Content');
