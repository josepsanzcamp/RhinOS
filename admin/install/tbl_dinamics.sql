CREATE TABLE `tbl_dinamics` (
	`id` INTEGER PRIMARY KEY /*MYSQL AUTO_INCREMENT *//*SQLITE AUTOINCREMENT */,
	`_needed` INTEGER NOT NULL DEFAULT '0',
	`titulo` TEXT NOT NULL DEFAULT '',
	`pagina` TEXT NOT NULL DEFAULT '',
	`pagina_count` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_titulo` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_subtitulo` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_descripcion` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_html` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_foto` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_fichero` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_url` INTEGER NOT NULL DEFAULT '0',
	`pagina_count_video` INTEGER NOT NULL DEFAULT '0',
	`pagina_search` TEXT NOT NULL DEFAULT '',
	`pagina_data_0` TEXT NOT NULL DEFAULT '',
	`pagina_data_0_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_0_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_0_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_1` TEXT NOT NULL DEFAULT '',
	`pagina_data_1_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_1_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_1_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_2` TEXT NOT NULL DEFAULT '',
	`pagina_data_2_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_2_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_2_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_3` TEXT NOT NULL DEFAULT '',
	`pagina_data_3_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_3_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_3_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_4` TEXT NOT NULL DEFAULT '',
	`pagina_data_4_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_4_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_4_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_5` TEXT NOT NULL DEFAULT '',
	`pagina_data_5_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_5_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_5_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_6` TEXT NOT NULL DEFAULT '',
	`pagina_data_6_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_6_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_6_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_7` TEXT NOT NULL DEFAULT '',
	`pagina_data_7_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_7_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_7_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_8` TEXT NOT NULL DEFAULT '',
	`pagina_data_8_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_8_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_8_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_9` TEXT NOT NULL DEFAULT '',
	`pagina_data_9_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_9_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_9_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_10` TEXT NOT NULL DEFAULT '',
	`pagina_data_10_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_10_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_10_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_11` TEXT NOT NULL DEFAULT '',
	`pagina_data_11_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_11_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_11_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_12` TEXT NOT NULL DEFAULT '',
	`pagina_data_12_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_12_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_12_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_13` TEXT NOT NULL DEFAULT '',
	`pagina_data_13_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_13_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_13_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_14` TEXT NOT NULL DEFAULT '',
	`pagina_data_14_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_14_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_14_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_15` TEXT NOT NULL DEFAULT '',
	`pagina_data_15_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_15_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_15_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_16` TEXT NOT NULL DEFAULT '',
	`pagina_data_16_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_16_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_16_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_17` TEXT NOT NULL DEFAULT '',
	`pagina_data_17_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_17_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_17_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_18` TEXT NOT NULL DEFAULT '',
	`pagina_data_18_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_18_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_18_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_19` TEXT NOT NULL DEFAULT '',
	`pagina_data_19_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_19_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_19_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_20` TEXT NOT NULL DEFAULT '',
	`pagina_data_20_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_20_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_20_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_21` TEXT NOT NULL DEFAULT '',
	`pagina_data_21_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_21_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_21_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_22` TEXT NOT NULL DEFAULT '',
	`pagina_data_22_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_22_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_22_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_23` TEXT NOT NULL DEFAULT '',
	`pagina_data_23_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_23_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_23_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_24` TEXT NOT NULL DEFAULT '',
	`pagina_data_24_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_24_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_24_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_25` TEXT NOT NULL DEFAULT '',
	`pagina_data_25_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_25_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_25_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_26` TEXT NOT NULL DEFAULT '',
	`pagina_data_26_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_26_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_26_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_27` TEXT NOT NULL DEFAULT '',
	`pagina_data_27_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_27_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_27_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_28` TEXT NOT NULL DEFAULT '',
	`pagina_data_28_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_28_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_28_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_29` TEXT NOT NULL DEFAULT '',
	`pagina_data_29_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_29_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_29_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_30` TEXT NOT NULL DEFAULT '',
	`pagina_data_30_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_30_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_30_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_31` TEXT NOT NULL DEFAULT '',
	`pagina_data_31_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_31_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_31_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_32` TEXT NOT NULL DEFAULT '',
	`pagina_data_32_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_32_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_32_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_33` TEXT NOT NULL DEFAULT '',
	`pagina_data_33_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_33_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_33_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_34` TEXT NOT NULL DEFAULT '',
	`pagina_data_34_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_34_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_34_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_35` TEXT NOT NULL DEFAULT '',
	`pagina_data_35_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_35_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_35_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_36` TEXT NOT NULL DEFAULT '',
	`pagina_data_36_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_36_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_36_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_37` TEXT NOT NULL DEFAULT '',
	`pagina_data_37_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_37_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_37_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_38` TEXT NOT NULL DEFAULT '',
	`pagina_data_38_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_38_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_38_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_39` TEXT NOT NULL DEFAULT '',
	`pagina_data_39_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_39_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_39_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_40` TEXT NOT NULL DEFAULT '',
	`pagina_data_40_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_40_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_40_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_41` TEXT NOT NULL DEFAULT '',
	`pagina_data_41_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_41_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_41_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_42` TEXT NOT NULL DEFAULT '',
	`pagina_data_42_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_42_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_42_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_43` TEXT NOT NULL DEFAULT '',
	`pagina_data_43_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_43_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_43_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_44` TEXT NOT NULL DEFAULT '',
	`pagina_data_44_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_44_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_44_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_45` TEXT NOT NULL DEFAULT '',
	`pagina_data_45_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_45_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_45_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_46` TEXT NOT NULL DEFAULT '',
	`pagina_data_46_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_46_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_46_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_47` TEXT NOT NULL DEFAULT '',
	`pagina_data_47_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_47_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_47_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_48` TEXT NOT NULL DEFAULT '',
	`pagina_data_48_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_48_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_48_size` INTEGER NOT NULL DEFAULT '0',
	`pagina_data_49` TEXT NOT NULL DEFAULT '',
	`pagina_data_49_file` TEXT NOT NULL DEFAULT '',
	`pagina_data_49_type` TEXT NOT NULL DEFAULT '',
	`pagina_data_49_size` INTEGER NOT NULL DEFAULT '0'
) /*MYSQL ENGINE=MyISAM CHARSET=utf8 */;
