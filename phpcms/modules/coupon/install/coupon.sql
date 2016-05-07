DROP TABLE IF EXISTS `phpcms_coupon`;
CREATE TABLE IF NOT EXISTS `phpcms_coupon` (
  `aid` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `title` char(80) NOT NULL,
  `username` varchar(40) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(250) NOT NULL ,
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `backpoints` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',	
  `okdays` int(10) unsigned NOT NULL DEFAULT '0',
  `minamount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0', 
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;