<?php

require_once('config.php');

$db = db::obtain();

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('user_roles')."` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rname` varchar(100) NOT NULL,
  `have_admin_access` int(1) NOT NULL,
  `can_add_quest` int(1) NOT NULL,
  `can_update_quest` int(1) NOT NULL,
  `can_delete_quest` int(1) NOT NULL,
  `can_add_level` int(1) NOT NULL,
  `can_update_level` int(1) NOT NULL,
  `can_delete_level` int(1) NOT NULL,
  `can_upload_file` int(1) NOT NULL,
  `can_delete_file` int(1) NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

$db->exec("INSERT INTO `".db::real_tablename('user_roles')."` (`rid`, `rname`, `have_admin_access`, `can_add_quest`, `can_update_quest`, `can_delete_quest`, `can_add_level`, `can_update_level`, `can_delete_level`, `can_upload_file`, `can_delete_file`) VALUES
    (1, 'admin', 1, 1, 1, 1, 1, 1, 1, 1, 1),
	(2, 'user', 0, 0, 0, 0, 0, 0, 0, 0, 0)");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('quests')."` (
  `qid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `is_open` int(1) NOT NULL DEFAULT '1',
  `intro` varchar(2048) DEFAULT NULL,
  `outro` varchar(2048) DEFAULT NULL,
  `theme` varchar(256) DEFAULT 'default',
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('levels')."` (
  `lid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qid` int(10) unsigned NOT NULL,
  `task` varchar(8192) NOT NULL,
  `answer` varchar(128) NOT NULL,
  `oid` int(10) NOT NULL DEFAULT '1',
  `hint` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`lid`),
  KEY `FK_".config::get('db_table_prefix')."levels_".config::get('db_table_prefix')."quests` (`qid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."levels_".config::get('db_table_prefix')."quests` FOREIGN KEY (`qid`) REFERENCES `".db::real_tablename('quests')."` (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('users')."` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(10) NOT NULL,
  `rid` int(10) unsigned NOT NULL DEFAULT '2',
  PRIMARY KEY (`uid`),
  KEY `FK_".config::get('db_table_prefix')."users_".config::get('db_table_prefix')."user_roles` (`rid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."users_".config::get('db_table_prefix')."user_roles` FOREIGN KEY (`rid`) REFERENCES `".db::real_tablename('user_roles')."` (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

$db->exec("INSERT INTO `".db::real_tablename('users')."` (`uid`, `login`, `password`, `salt`, `rid`) VALUES
(1, 'qq-admin', '".user::gen_hash('qq-admin','0123456789')."', '0123456789', 1)");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('users_on_quests')."` (
  `qid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `current_level` int(10) NOT NULL DEFAULT '1',
  `complete_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`qid`,`uid`),
  KEY `FK_".config::get('db_table_prefix')."users_on_quests_".config::get('db_table_prefix')."users` (`uid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."users_on_quests_".config::get('db_table_prefix')."quests` FOREIGN KEY (`qid`) REFERENCES `".db::real_tablename('quests')."` (`qid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."users_on_quests_".config::get('db_table_prefix')."users` FOREIGN KEY (`uid`) REFERENCES `".db::real_tablename('users')."` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('users_on_quests_log')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qid` int(10) unsigned NOT NULL,
  `lid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_".config::get('db_table_prefix')."users_on_quests_log_".config::get('db_table_prefix')."quests` (`qid`),
  KEY `FK_".config::get('db_table_prefix')."users_on_quests_log_".config::get('db_table_prefix')."levels` (`lid`),
  KEY `FK_".config::get('db_table_prefix')."users_on_quests_log_".config::get('db_table_prefix')."users` (`uid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."users_on_quests_log_".config::get('db_table_prefix')."levels` FOREIGN KEY (`lid`) REFERENCES `".db::real_tablename('levels')."` (`lid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."users_on_quests_log_".config::get('db_table_prefix')."quests` FOREIGN KEY (`qid`) REFERENCES `".db::real_tablename('quests')."` (`qid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."users_on_quests_log_".config::get('db_table_prefix')."users` FOREIGN KEY (`uid`) REFERENCES `".db::real_tablename('users')."` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('sessions')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `token` varchar(16) NOT NULL,
  `sid` varchar(32) NOT NULL,
  `ip_hash` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  KEY `FK_".config::get('db_table_prefix')."sessions_".config::get('db_table_prefix')."users` (`uid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."sessions_".config::get('db_table_prefix')."users` FOREIGN KEY (`uid`) REFERENCES `".db::real_tablename('users')."` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;");

$db->exec("CREATE TABLE IF NOT EXISTS `".db::real_tablename('provided_answers')."` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `qid` int(10) unsigned NOT NULL,
  `lid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answer` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_".config::get('db_table_prefix')."provided_answers_".config::get('db_table_prefix')."quests` (`qid`),
  KEY `FK_".config::get('db_table_prefix')."provided_answers_".config::get('db_table_prefix')."levels` (`lid`),
  KEY `FK_".config::get('db_table_prefix')."provided_answers_".config::get('db_table_prefix')."users` (`uid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."provided_answers_".config::get('db_table_prefix')."levels` FOREIGN KEY (`lid`) REFERENCES `".db::real_tablename('levels')."` (`lid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."provided_answers_".config::get('db_table_prefix')."quests` FOREIGN KEY (`qid`) REFERENCES `".db::real_tablename('quests')."` (`qid`),
  CONSTRAINT `FK_".config::get('db_table_prefix')."provided_answers_".config::get('db_table_prefix')."users` FOREIGN KEY (`uid`) REFERENCES `".db::real_tablename('users')."` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

echo 'Done.';