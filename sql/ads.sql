CREATE TABLE IF NOT EXISTS `ads` (
  `id` int(8) NOT NULL auto_increment,
  `title` varchar(2068) collate utf8_unicode_ci NOT NULL,
  `ASIN` varchar(10) collate utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;