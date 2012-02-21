CREATE TABLE `prefix_river_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) NOT NULL,
  `subtype` varchar(32) NOT NULL,
  `action_type` varchar(32) NOT NULL,
  `view` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`subtype`,`action_type`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



