CREATE TABLE `db_selects` (
	`id` INTEGER PRIMARY KEY /*MYSQL AUTO_INCREMENT *//*SQLITE AUTOINCREMENT */,
	`tbl` TEXT NOT NULL DEFAULT '',
	`row` TEXT NOT NULL DEFAULT '',
	`table_ref` TEXT NOT NULL DEFAULT '',
	`value_ref` TEXT NOT NULL DEFAULT '',
	`text_ref` TEXT NOT NULL DEFAULT ''
) /*MYSQL ENGINE=MyISAM CHARSET=utf8 */;
