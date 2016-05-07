DROP TABLE IF EXISTS `phpcms_waybill`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill` (
  `aid` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `waybillid` varchar(50) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `paytypeid` int(2) unsigned NOT NULL DEFAULT '0',
  `sendid` int(10) unsigned NOT NULL DEFAULT '0',
  `takeid` int(10) unsigned NOT NULL DEFAULT '0',
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  `shippingid` int(10) unsigned NOT NULL DEFAULT '0',
  `sendaddressid` int(10) unsigned NOT NULL DEFAULT '0',
  `takeaddressid` int(10) unsigned NOT NULL DEFAULT '0' ,
  `ownsendaddressid` int(10) unsigned NOT NULL DEFAULT '0',
  `owntakeaddressid` int(10) unsigned NOT NULL DEFAULT '0' ,
  `remark` char(80) NOT NULL,
  `storageid` int(10) unsigned NOT NULL DEFAULT '0',
  `totalamount` int(10) unsigned NOT NULL DEFAULT '0',
  `totalprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `totalweight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `totalvolume` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `totalship` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `sysbillid` varchar(50) NOT NULL,
  `status` int(5) unsigned NOT NULL DEFAULT '0',
  `longwh` varchar(50) NOT NULL,
  `factweight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `payedfee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bill_long` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bill_width` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `bill_height` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `volumeweight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `checkedamount` smallint(5) unsigned NOT NULL DEFAULT '0',
  `kabanno` varchar(50) NOT NULL,
  `huodanno` varchar(50) NOT NULL,
  `paifa` varchar(50) NOT NULL,
  PRIMARY KEY (`aid`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM ;


DROP TABLE IF EXISTS `phpcms_waybill_goods`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill_goods` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `waybillid` varchar(50) NOT NULL,
  `expressid` int(10) unsigned NOT NULL DEFAULT '0',
  `expressno` varchar(100) NOT NULL,
  `goodsname` varchar(100) NOT NULL,
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `packageid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `waybillid` (`waybillid`)
) TYPE=MyISAM ;


DROP TABLE IF EXISTS `phpcms_waybill_serviceitem`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill_serviceitem` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `waybill_goodsid` int(10) unsigned NOT NULL DEFAULT '0',
  `waybillid` varchar(50) NOT NULL,
  `currencyid` int(10) unsigned NOT NULL DEFAULT '0',
  `unitid` int(10) unsigned NOT NULL DEFAULT '0',
  `servicetype` varchar(100) NOT NULL,
  `servicetypeid` int(10) unsigned NOT NULL DEFAULT '0',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `number` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `waybillid` (`waybillid`)
) TYPE=MyISAM ;


DROP TABLE IF EXISTS `phpcms_waybill_fahuo`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill_fahuo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `fahuono` varchar(50) NOT NULL,
  `waybillid` varchar(50) NOT NULL,
  `shipno` varchar(50) NOT NULL,
  `shipname` varchar(50) NOT NULL,
  `shipid` int(10) unsigned NOT NULL DEFAULT '0',
  `weight` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `placeid` int(5) unsigned NOT NULL DEFAULT '0',
  `placename` varchar(50) NOT NULL,
  `lastplaceid` int(5) unsigned NOT NULL DEFAULT '0',
  `lastplacename` varchar(50) NOT NULL,
  `status` int(5) unsigned NOT NULL DEFAULT '0',
  `isbadpackage` int(5) unsigned NOT NULL DEFAULT '0',
  `position` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM charset=utf8;

DROP TABLE IF EXISTS `phpcms_waybill_kaban`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill_kaban` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `kabanno` varchar(50) NOT NULL,
  `waybillid` varchar(1000) NOT NULL,
  `storageid` varchar(50) NOT NULL,
  `storagename` varchar(50) NOT NULL,
  `status` int(5) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(250) NOT NULL,
  `huodanno` varchar(50) NOT NULL,
  `paifa` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM charset=utf8;


DROP TABLE IF EXISTS `phpcms_waybill_huodan`;
CREATE TABLE IF NOT EXISTS `phpcms_waybill_huodan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` int(5) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL,
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `sysdanno` varchar(50) NOT NULL,
  `huodanno` varchar(50) NOT NULL,
  `kabanno` varchar(1000) NOT NULL,
  `storageid` varchar(50) NOT NULL,
  `storagename` varchar(50) NOT NULL,
  `status` int(5) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(250) NOT NULL,
  `unionhuodanno` varchar(1000) NOT NULL,
  `paifa` varchar(50) NOT NULL,	
  `handle` int(2) unsigned NOT NULL DEFAULT '0',
  `position` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `siteid` (`siteid`)
) TYPE=MyISAM charset=utf8;

