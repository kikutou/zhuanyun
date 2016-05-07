DROP TABLE IF EXISTS `phpcms_address`;
CREATE TABLE IF NOT EXISTS `phpcms_address` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `addresstype` int(2) unsigned NOT NULL DEFAULT '0',
  `truename` char(80) NOT NULL,
  `country` varchar(200) NOT NULL,
  `province` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `address` varchar(250) NOT NULL,
  `company` varchar(250) NOT NULL,
  `postcode` varchar(30) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `idcardtype` int(2) unsigned NOT NULL DEFAULT '0',
  `idcard` varchar(50) NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;