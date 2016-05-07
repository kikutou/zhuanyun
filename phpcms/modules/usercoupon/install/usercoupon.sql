DROP TABLE IF EXISTS `phpcms_usercoupon`;
CREATE TABLE IF NOT EXISTS `phpcms_usercoupon` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(10) unsigned NOT NULL DEFAULT '0',
  `couponid` int(10) unsigned NOT NULL DEFAULT '0',
  `objecttype` int(10) unsigned NOT NULL DEFAULT '0',
  `objectid` int(10) unsigned NOT NULL DEFAULT '0',
  `objectname` varchar(50) NOT NULL,
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `okdays` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(30) NOT NULL ,
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;