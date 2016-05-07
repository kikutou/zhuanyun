DROP TABLE IF EXISTS `phpcms_service`;
CREATE TABLE IF NOT EXISTS `phpcms_service` (
  `aid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL,
  `username` varchar(40) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL ,
  `remark` varchar(250) NOT NULL ,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `unit` int(10) unsigned NOT NULL DEFAULT '0',
  `currencyid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;