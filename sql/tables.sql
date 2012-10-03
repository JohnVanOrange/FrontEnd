SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  `filename` varchar(256) collate utf8_unicode_ci NOT NULL,
  `uid` varchar(6) character set utf8 collate utf8_bin NOT NULL,
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

CREATE TABLE IF NOT EXISTS `imgur_history` (
  `id` varchar(5) character set utf8 collate utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `image_id` int(10) unsigned NOT NULL,
  `report_type` int(3) unsigned NOT NULL,
  `reason` varchar(255) collate utf8_unicode_ci default NULL,
  `resolved` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `report_types` (
  `id` int(3) unsigned NOT NULL auto_increment,
  `value` varchar(32) collate utf8_unicode_ci NOT NULL,
  `public` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

INSERT INTO `report_types` (`id`, `value`, `public`) VALUES
(1, 'NSFW', 1),
(2, 'Copyright Violation', 1),
(3, 'Lame', 1),
(4, 'Illegal Content', 1);

CREATE TABLE IF NOT EXISTS `sessions` (
  `user_id` int(8) unsigned NOT NULL,
  `sid` varchar(16) character set utf8 collate utf8_bin NOT NULL,
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(8) unsigned NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `tag_image` (`tag_id`,`image_id`),
  KEY `image_id` (`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `tag_list` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL,
  `basename` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `basename` (`basename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `username` varchar(32) collate utf8_unicode_ci NOT NULL,
  `password` varchar(32) collate utf8_unicode_ci NOT NULL,
  `salt` varchar(16) collate utf8_unicode_ci NOT NULL,
  `email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `type` int(10) unsigned NOT NULL default '1',
  `theme` varchar(8) collate utf8_unicode_ci NOT NULL default 'dark',
  `refresh` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `resources` (
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ip` varchar(15) collate utf8_unicode_ci default NULL,
  `image` varchar(6) character set utf8 collate utf8_bin NOT NULL,
  `user_id` int(8) unsigned default NULL,
  `value` int(8) default NULL,
  `type` varchar(8) collate utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;