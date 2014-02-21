CREATE TABLE IF NOT EXISTS `media` (
  `uid` varchar(6) character set utf8 collate utf8_bin NOT NULL,
  `file` varchar(255) collate utf8_unicode_ci NOT NULL,
  `format` varchar(4) collate utf8_unicode_ci NOT NULL,
  `hash` varchar(32) collate utf8_unicode_ci NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `type` enum('thumb','primary') collate utf8_unicode_ci NOT NULL default 'primary'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;