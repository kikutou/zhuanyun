DROP TABLE IF EXISTS `phpcms_yuyue`;
CREATE TABLE IF NOT EXISTS `phpcms_yuyue` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `shippingtype` int(2) unsigned NOT NULL DEFAULT '0',
  `yuyuetime` int(10) unsigned NOT NULL DEFAULT '0',
  `yuyuetimedetail` int(2) unsigned NOT NULL DEFAULT '0',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `addressid` int(10) unsigned NOT NULL DEFAULT '0',
  `lianxiren` varchar(50) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(250) NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;