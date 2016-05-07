DROP TABLE IF EXISTS `phpcms_currency`;
CREATE TABLE IF NOT EXISTS `phpcms_currency` (
  `aid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL,
  `username` varchar(40) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `isdefault` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `code` varchar(30) NOT NULL ,
  `symbol` varchar(10) NOT NULL ,
  `exchangerate` varchar(20) NOT NULL ,
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`,`default`)
) TYPE=MyISAM ;