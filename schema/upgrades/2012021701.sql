CREATE TABLE `prefix_river_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) NOT NULL,
  `subtype` varchar(32) NOT NULL,
  `action` varchar(32) NOT NULL,
  `view` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `prefix_river_types` (`type`,`subtype`,`action`,`view`) SELECT DISTINCT r.`type`,r.`subtype`,r.`action_type`,r.`view` FROM `prefix_river` r ORDER BY `type`,`subtype`,`action_type`,`view`;

ALTER TABLE `prefix_river` ADD `river_type` int(11) NOT NULL AFTER `id`;

UPDATE `prefix_river` river, `prefix_river_types` types
SET river.`river_type`= types.`id`
WHERE types.`type` = river.`type` AND types.`subtype` = river.`subtype` AND types.`action` = river.`action_type` AND types.`view` = river.`view`;

ALTER TABLE `elgg_river` DROP `type`, DROP `subtype`, DROP `action_type`, DROP`view`;

