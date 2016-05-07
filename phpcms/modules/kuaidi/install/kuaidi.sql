DROP TABLE IF EXISTS `phpcms_kuaidi`;
CREATE TABLE `phpcms_kuaidi` (
  `kdid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) unsigned DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `fullname` varchar(100) NOT NULL DEFAULT '',
  `code` varchar(30) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(100) NOT NULL DEFAULT '',
  `tel` varchar(50) NOT NULL DEFAULT '',
  `introduce` text NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `passed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0',
  `letter` varchar(30) NOT NULL DEFAULT '',
  `style` char(15) NOT NULL DEFAULT '',
  `show_template` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`kdid`),
  KEY `typeid` (`typeid`,`passed`,`listorder`,`kdid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf-8;