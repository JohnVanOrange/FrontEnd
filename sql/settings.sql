CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(16) collate utf8_unicode_ci NOT NULL,
  `value` varchar(64) collate utf8_unicode_ci default NULL,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `settings` (`name`, `value`) VALUES
('web_root', 'http://example.com/'),
('site_name', 'John VanDefault'),
('branch', 'stable'),
('theme', 'jvo'),
('icon_set', 'orange_slice'),
('google_analytics', NULL),
('app_link', NULL),
('show_brazz', '0'),
('show_social', '1'),
('show_jvon', '1'),
('disable_upload', '0'),
('fb_app_id', NULL),
('amazon_aff', NULL),
('403_image', '403.jpg'),
('404_image', '404.jpg');