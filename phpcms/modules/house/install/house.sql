DROP TABLE IF EXISTS `phpcms_house`;
CREATE TABLE IF NOT EXISTS `phpcms_house` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `menuid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL,
  `username` varchar(50) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;

