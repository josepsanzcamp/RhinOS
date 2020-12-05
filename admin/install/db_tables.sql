CREATE TABLE `db_tables` (
	`id` INTEGER PRIMARY KEY /*MYSQL AUTO_INCREMENT *//*SQLITE AUTOINCREMENT */,
	`tbl` TEXT NOT NULL DEFAULT '',
	`name` TEXT NOT NULL DEFAULT '',
	`description` TEXT NOT NULL DEFAULT '',
	`position` INTEGER NOT NULL DEFAULT '0',
	`icon` TEXT NOT NULL DEFAULT ''
) /*MYSQL ENGINE=MyISAM CHARSET=utf8 */;
