CREATE TABLE `filt2o` (
  `fid` int(10) unsigned NOT NULL default '0',
  `oid` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `filters` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(255) NOT NULL default '',
  `cond` varchar(255) NOT NULL default '',
  `act` varchar(255) NOT NULL default '',
  `ftype` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `out2s` (
  `oid` int(10) unsigned NOT NULL default '0',
  `sid` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `outs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  `geo` text NOT NULL,
  `isparam` tinyint(4) NOT NULL default '0',
  `empty_ref` varchar(255) NOT NULL default '',
  `reserved` tinyint(4) NOT NULL default '0',
  `redir_type` varchar(255) NOT NULL default 'location',
  `exitout` tinyint(4) NOT NULL default '0',
  `weight` int(11) NOT NULL default '1',
  `change_ref` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `outs_stat` (
  `oid` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `unics` int(10) unsigned NOT NULL default '0',
  UNIQUE KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `schems` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE `settings` (
  `name` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

INSERT INTO `settings` VALUES('time_offset', '-3');
INSERT INTO `settings` VALUES('arch_stats_time', '30');
INSERT INTO `settings` VALUES('arch_stats_type', 'csv');
INSERT INTO `settings` VALUES('stats_num_raws', '50');
INSERT INTO `settings` VALUES('stats_show_del', '0');
INSERT INTO `settings` VALUES('stats_show_selects', '0');
INSERT INTO `settings` VALUES('stats_show_ua', '1');
INSERT INTO `settings` VALUES('user_unic_time', '604800');
INSERT INTO `settings` VALUES('stats_do_arch', '1');

CREATE TABLE `stats` (
  `dt` datetime NOT NULL default '0000-00-00 00:00:00',
  `sid` int(10) unsigned NOT NULL default '0',
  `oid` int(10) unsigned NOT NULL default '0',
  `country` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL default '',
  `ref` varchar(255) NOT NULL default '',
  `refref` varchar(255) NOT NULL default '',
  `ua` varchar(255) NOT NULL default '',
  `se` varchar(255) NOT NULL default '',
  `query_string` varchar(255) NOT NULL default '',
  `out_url` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;