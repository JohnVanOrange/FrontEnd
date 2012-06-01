SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  `filename` varchar(256) collate utf8_unicode_ci NOT NULL,
  `uid` varchar(6) collate utf8_unicode_ci NOT NULL,
  `hash` varchar(32) collate utf8_unicode_ci default NULL,
  `type` varchar(4) collate utf8_unicode_ci default NULL,
  `width` smallint(5) unsigned default NULL,
  `height` smallint(5) unsigned default NULL,
  `c_link` varchar(256) collate utf8_unicode_ci default NULL,
  `display` tinyint(1) NOT NULL default '1',
  `reported` tinyint(1) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
