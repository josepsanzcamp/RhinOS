CREATE TABLE `tbl_labels` (
	`id` INTEGER PRIMARY KEY /*MYSQL AUTO_INCREMENT *//*SQLITE AUTOINCREMENT */,
	`tag` TEXT NOT NULL DEFAULT '',
	`html` INTEGER NOT NULL DEFAULT '0',
	`es` TEXT NOT NULL DEFAULT ''
) /*MYSQL ENGINE=MyISAM CHARSET=utf8 */;
