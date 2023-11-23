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

ALTER TABLE `outs` ADD `exitout` TINYINT NOT NULL DEFAULT '0',
ADD `weight` INT NOT NULL DEFAULT '1',
ADD `change_ref` TINYINT NOT NULL DEFAULT '0';