DROP TABLE IF EXISTS `phpcms_turnway`;
CREATE TABLE IF NOT EXISTS `phpcms_turnway` (
  `aid` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `title` varchar(180) NOT NULL,
  `username` varchar(40) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `sendid` int(10) unsigned NOT NULL DEFAULT '0',
  `takeid` int(10) unsigned NOT NULL DEFAULT '0',
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  `usfee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `cnfee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;