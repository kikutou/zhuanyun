DROP TABLE IF EXISTS `phpcms_package`;
CREATE TABLE IF NOT EXISTS `phpcms_package` (
  `aid` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `weight` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `storageid` int(10) unsigned NOT NULL DEFAULT '0',
  `expressno` varchar(150) NOT NULL,
  `issecure` int(2) unsigned NOT NULL DEFAULT '0',
  `expressid` int(10) unsigned NOT NULL DEFAULT '0',
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `goodsname` varchar(200) NOT NULL,
  `remark` varchar(250) NOT NULL,
  `issystem` smallint(2) unsigned NOT NULL DEFAULT '0',
  `operatorid` int(10) unsigned NOT NULL DEFAULT '0', 
  `operatorname` varchar(40) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `volume` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;

DROP TABLE IF EXISTS `phpcms_waybill_history`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  `sysbillid` varchar(50) NOT NULL,
  `waybillid` varchar(50) NOT NULL,
  `status` int(5) unsigned NOT NULL DEFAULT '0',
  `placeid` int(5) unsigned NOT NULL DEFAULT '0',
  `placename` varchar(50) NOT NULL,
  `remark` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;