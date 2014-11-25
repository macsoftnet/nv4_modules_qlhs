<?php

/**
 * @Project NUKEVIET 4
 * @Author Nguyễn Thanh Hoàng  (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2014 webvang.vn All rights reserved
 * @Createdate 08/10/2014
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();


$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_diem";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_dshs";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lop";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_monhoc";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_namhoc";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_xeploai";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_dsgv";

$result = $db->query( 'SHOW COLUMNS FROM '.NV_USERS_GLOBALTABLE.'_info LIKE "NoiSinh"' );
while( $item = $result->fetch() )
{
	$sql_drop_module[] = "ALTER TABLE ".NV_USERS_GLOBALTABLE."_info DROP NoiSinh";
	
}
$result = $db->query( 'SHOW COLUMNS FROM '.NV_USERS_GLOBALTABLE.'_info LIKE "AccType"' );
while( $item = $result->fetch() )
{
	$sql_drop_module[] = "ALTER TABLE ".NV_USERS_GLOBALTABLE."_info DROP AccType";
	
}
$sql_drop_module[] ="DELETE FROM ".NV_USERS_GLOBALTABLE."_field WHERE field = 'NoiSinh'";
$sql_drop_module[] ="DELETE FROM ".NV_USERS_GLOBALTABLE."_field WHERE field = 'AccType'";
$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_diem (
   id mediumint(9) NOT NULL auto_increment,
   mahs varchar(7) NOT NULL,
   lopid tinyint(3) NOT NULL,
   manamhoc tinyint(2) NOT NULL,
   mahocky tinyint(1) NOT NULL,
   monid tinyint(2) NOT NULL,
   m_1 varchar(3),
   m_2 varchar(3),
   15_1 varchar(3),
   15_2 varchar(3),
   15_3 varchar(3),
   15_4 varchar(3),
   15_5 varchar(3),
   45_1 varchar(3),
   45_2 varchar(3),
   45_3 varchar(3),
   45_4 varchar(3),
   45_5 varchar(3),
   thi varchar(3),
   tbm varchar(3),
   gvid mediumint(3) DEFAULT '0' NOT NULL,
   PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_dshs (
   id int(9) NOT NULL auto_increment,
   mahs varchar(7) NOT NULL,
   manamhoc tinyint(2) NOT NULL,
   lopid tinyint(3) NOT NULL,
   hoten varchar(40) NOT NULL,
   phai tinyint(1) DEFAULT '1' NOT NULL,
   ngaysinh int(11) NOT NULL,
   noisinh varchar(40) NOT NULL,
   PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_lop (
   lopid tinyint(3) NOT NULL,
   tenlop varchar(50) NOT NULL,
   gvid mediumint(3) DEFAULT '0' NOT NULL,
   PRIMARY KEY (lopid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_monhoc (
   monid tinyint(2) NOT NULL,
   tenmon varchar(50) NOT NULL,
   PRIMARY KEY (monid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_namhoc (
   manamhoc tinyint(2) NOT NULL,
   tennamhoc varchar(9) NOT NULL,
   PRIMARY KEY (manamhoc)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_xeploai (
   id int(11) NOT NULL auto_increment,
   mahs varchar(7) NOT NULL,
   lopid tinyint(3) NOT NULL,
   manamhoc tinyint(2) NOT NULL,
   mahocky tinyint(1) NOT NULL,
   tbm varchar(3) NOT NULL,
   hl varchar(11) NOT NULL,
   hk varchar(11) NOT NULL,
   snncp varchar(2),
   snnkp varchar(2),
   danhhieu varchar(11),
   nxgvcn varchar(255),
   PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_dsgv (
   gvid mediumint(3) NOT NULL auto_increment,
   tengv varchar(40) NOT NULL,
   user varchar(40),
   log text,
   chunhiem tinyint(1) DEFAULT '0' NOT NULL,
   active tinyint(1) DEFAULT '1' NOT NULL,
   PRIMARY KEY (gvid)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";

$sql_create_module[] = "ALTER TABLE ".NV_USERS_GLOBALTABLE."_info ADD NoiSinh varchar(255) NOT NULL DEFAULT ''"; 
$sql_create_module[] = "ALTER TABLE ".NV_USERS_GLOBALTABLE."_info ADD AccType varchar(255) NOT NULL DEFAULT '0'"; 
$lang1='a:1:{s:2:"vi";a:2:{i:0;s:9:"Nơi Sinh";i:1;s:0:"";}}';
$field_choices2='a:2:{i:1;s:10:"Học sinh";i:2;s:11:"Giáo viên";}';
$lang2='a:1:{s:2:"vi";a:2:{i:0;s:21:"Tài khoản này là";i:1;s:0:"";}}';
$sql_create_module[] = "INSERT INTO " .NV_USERS_GLOBALTABLE."_field ( field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_register, user_editable, show_profile, class, language, default_value) VALUES( 'NoiSinh', 1, 'textbox', '', '', 'none', '', '', 0, 255, 0, 0, 0, 1, 'input', '".$lang1."', 'TPHCM')";
$sql_create_module[] = "INSERT INTO " .NV_USERS_GLOBALTABLE."_field (fid, field, weight, field_type, field_choices, sql_choices, match_type, match_regex, func_callback, min_length, max_length, required, show_register, user_editable, show_profile, class, language, default_value) VALUES( '','AccType', 2, 'select', '".$field_choices2."', '', 'none', '', '', 0, 255, 0, 0, 0, 1, 'input', '".$lang2."', '1')";




