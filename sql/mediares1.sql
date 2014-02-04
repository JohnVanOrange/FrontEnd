ALTER TABLE  `images` DROP  `name` ;
ALTER TABLE  `images` ADD  `animated` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `c_link` ;