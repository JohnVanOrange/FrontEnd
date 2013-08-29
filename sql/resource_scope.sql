ALTER TABLE  `resources` ADD  `public` TINYINT( 1 ) NOT NULL DEFAULT  '0';

UPDATE `resources` SET `public` = 1 WHERE type = 'tag' OR type = 'upload';